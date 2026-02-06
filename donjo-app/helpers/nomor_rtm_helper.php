<?php

use App\Enums\FormatNoRtmEnum;
use App\Models\Rtm;

defined('BASEPATH') || exit('No direct script access allowed');

if (! function_exists('_rtm_format_human')) {
    function _rtm_format_human(int $setting): array
    {
        return match ($setting) {
            FormatNoRtmEnum::ANGKA => [
                'label' => 'Angka',
                'contoh' => '00001',
            ],
            FormatNoRtmEnum::ANGKA_HURUF => [
                'label' => 'Angka Huruf',
                'contoh' => '1A',
            ],
            FormatNoRtmEnum::HURUF_ANGKA => [
                'label' => 'Huruf Angka',
                'contoh' => 'A1',
            ],
            FormatNoRtmEnum::ANGKA_HURUF_ANGKA => [
                'label' => 'Angka Huruf Angka',
                'contoh' => '1A1',
            ],
            FormatNoRtmEnum::HURUF_ANGKA_HURUF => [
                'label' => 'Huruf Angka Huruf',
                'contoh' => 'A1B',
            ],
            default => [
                'label' => 'Tidak dikenal',
                'contoh' => '-',
            ],
        };
    }
}


if (! function_exists('generate_next_rtm_number')) {
    /**
     * Generate nomor RTM berikutnya BERDASARKAN SETTING format_no_rtm
     */
    function generate_next_rtm_number(): ?string
    {
        $setting = (int) setting('format_no_rtm');

        if (! $setting) {
            return null;
        }

        $formatInfo = _rtm_format_from_setting($setting);

        if (! $formatInfo) {
            return null;
        }

        $lastRtm = _rtm_find_last_number($formatInfo['regex']);

        if (! $lastRtm) {
            return _rtm_generate_first_number($formatInfo['format']);
        }

        return _rtm_increment_number($lastRtm, $formatInfo);
    }

}


if (! function_exists('_rtm_format_from_setting')) {
    /**
     * Mapping setting ke format & regex
     */
    function _rtm_format_from_setting(int $setting): ?array
    {
        return match ($setting) {

            FormatNoRtmEnum::ANGKA => [
                'format' => 'A',
                'label'  => 'Angka',
                'contoh' => '123',
                'regex' => '/^(\d+)$/'
            ],

            FormatNoRtmEnum::ANGKA_HURUF => [
                'format' => 'A_H',
                'label'  => 'Angka diikuti Huruf',
                'contoh' => '12A',
                'regex' => '/^(\d+)([A-Z]+)$/i'
            ],

            FormatNoRtmEnum::HURUF_ANGKA => [
                'format' => 'H_A',
                'label'  => 'Huruf diikuti Angka',
                'contoh' => 'A12',
                'regex' => '/^([A-Z]+)(\d+)$/i'
            ],

            FormatNoRtmEnum::ANGKA_HURUF_ANGKA => [
                'format' => 'A_H_A',
                'label'  => 'Angka – Huruf – Angka',
                'contoh' => '12A3',
                'regex' => '/^(\d+)([A-Z]+)(\d+)$/i'
            ],

            FormatNoRtmEnum::HURUF_ANGKA_HURUF => [
                'format' => 'H_A_H',
                'label'  => 'Huruf – Angka – Huruf',
                'contoh' => 'A12B',
                'regex' => '/^([A-Z]+)(\d+)([A-Z]+)$/i'
            ],

            default => null,
        };
    }

}

if (! function_exists('_rtm_find_last_number')) {
    /**
     * Cari nomor RTM terakhir berdasarkan regex format
     */
    function _rtm_find_last_number(string $regex): ?string
    {
        // Bersihkan regex dari delimiter dan modifier untuk MySQL
        $sqlRegex = preg_replace('/^\/|\/[a-z]*$/i', '', $regex);
        // Ubah \d menjadi [0-9] untuk kompatibilitas MySQL
        $sqlRegex = str_replace('\d', '[0-9]', $sqlRegex);

        return Rtm::where('config_id', identitas('id'))
            ->where('no_kk', 'REGEXP', $sqlRegex)
            ->orderByRaw("CAST(REGEXP_SUBSTR(no_kk, '[0-9]+') AS UNSIGNED) DESC")
            ->first()
            ?->no_kk;
    }
}

if (! function_exists('_rtm_generate_first_number')) {
    /**
     * Generate nomor RTM pertama sesuai format
     */
    function _rtm_generate_first_number(string $format): string
    {
        return match ($format) {
            'A'     => str_pad('1', 5, '0', STR_PAD_LEFT),
            'A_H'   => '1A',
            'H_A'   => 'A1',
            'A_H_A' => '1A1',
            'H_A_H' => 'A1B',
            default => '1',
        };
    }
}

if (! function_exists('_rtm_increment_number')) {
    /**
     * Increment nomor RTM berdasarkan format
     */
    function _rtm_increment_number(string $number, array $formatInfo): ?string
    {
        if (! preg_match($formatInfo['regex'], $number, $matches)) {
            return null;
        }

        switch ($formatInfo['format']) {

            // Huruf – Angka – Huruf  → A12B → A13B
            case 'H_A_H':
                $prefix = $matches[1]; // huruf depan
                $num    = $matches[2]; // angka
                $suffix = $matches[3]; // huruf belakang

                return $prefix
                    . str_pad(((int) $num) + 1, strlen($num), '0', STR_PAD_LEFT)
                    . $suffix;

            // Angka – Huruf → 12A → 13A
            case 'A_H':
                $num    = $matches[1]; // angka
                $suffix = $matches[2]; // huruf

                return str_pad(((int) $num) + 1, strlen($num), '0', STR_PAD_LEFT)
                    . $suffix;

            // Huruf – Angka → A12 → A13
            case 'H_A':
                $prefix = $matches[1]; // huruf
                $num    = $matches[2]; // angka

                return $prefix
                    . str_pad(((int) $num) + 1, strlen($num), '0', STR_PAD_LEFT);

            // Angka – Huruf – Angka → 12A3 → 12A4  ✅
            case 'A_H_A':
                $prefixNum = $matches[1]; // angka depan
                $letters   = $matches[2]; // huruf tengah
                $num       = $matches[3]; // angka belakang

                return $prefixNum
                    . $letters
                    . str_pad(((int) $num) + 1, strlen($num), '0', STR_PAD_LEFT);

            // Angka saja → 00012 → 00013
            case 'A':
                $num = $matches[1];
                $incremented = ((int) $num) + 1;

                $pad = str_starts_with($num, '0')
                    ? strlen($num)
                    : 0;

                return str_pad($incremented, $pad, '0', STR_PAD_LEFT);

            default:
                return null;
        }
    }
}
