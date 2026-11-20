<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Services\SpamDetection\Analyzers;

use App\Services\SpamDetection\DTOs\AnalyzerResult;

class PhoneNumberAnalyzer extends AbstractAnalyzer
{
    private const PATTERNS = [
        '/(\+62|0062|0)[2-9]\d{7,11}/',  // Format Indonesia standar
        '/(\(\d{2,4}\)\s?\d{6,9})/',     // Format dengan kode area berkurung
    ];

    /** Jumlah nomor unik minimal sebelum dianggap mencurigakan */
    private const UNIQUE_PHONE_THRESHOLD = 2;

    public function type(): string
    {
        return 'phone_number_abuse';
    }

    public function maxScore(): int
    {
        return 35;
    }

    public function analyze(string $content): ?AnalyzerResult
    {
        $allNumbers = $this->extractPhoneNumbers($content);

        if (empty($allNumbers)) {
            return null;
        }

        $totalCount  = count($allNumbers);
        $uniqueCount = count(array_unique($allNumbers));
        $score       = 0;
        $reasons     = [];

        // Cek: terlalu banyak nomor berbeda (promosi/iklan)
        if ($uniqueCount > self::UNIQUE_PHONE_THRESHOLD) {
            $extraNumbers = $uniqueCount - self::UNIQUE_PHONE_THRESHOLD;
            $score       += $extraNumbers * 10;
            $reasons[]    = 'multiple_unique_numbers';
        }

        // Cek: nomor yang sama diulang (copy-paste spam)
        if ($uniqueCount < $totalCount) {
            $duplicateCount = $totalCount - $uniqueCount;
            $score         += $duplicateCount * 12;
            $reasons[]      = 'duplicate_phone_numbers';
        }

        if ($score === 0) {
            return null;
        }

        return $this->result(
            rawScore: $score,
            metadata: [
                'total_count'  => $totalCount,
                'unique_count' => $uniqueCount,
                'reasons'      => $reasons,
                'numbers'      => array_unique($allNumbers),
            ],
        );
    }

    /**
     * Ekstrak semua nomor telepon dari konten
     *
     * @return string[]
     */
    private function extractPhoneNumbers(string $content): array
    {
        $found = [];

        foreach (self::PATTERNS as $pattern) {
            if (preg_match_all($pattern, $content, $matches)) {
                // Normalisasi nomor: hapus spasi dan karakter non-digit
                $normalized = array_map(
                    fn (string $num) => preg_replace('/\D/', '', $num),
                    $matches[0]
                );
                $found = array_merge($found, $normalized);
            }
        }

        return $found;
    }
}
