<?php

declare(strict_types=1);

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

namespace App\Services\Acak\Support;

use App\Services\Acak\AcakContext;

/**
 * Menyanitasi nilai PII di dalam entri form surat (kolom `input` pada log_surat)
 * secara rekursif, berdasarkan nama kunci dan pola nilai.
 *
 * Aturan:
 *  - kunci `no_kk`/`nomor_kk` bernilai 16 digit  → remap via {@see AcakContext::$noKkMap}.
 *  - nilai string 16 digit apa pun                → diperlakukan sebagai NIK, remap
 *    via {@see AcakContext::$nikMap} (atau NIK yatim deterministik bila tak dikenal).
 *  - kunci telepon/HP bernilai mirip nomor HP     → nomor telepon sintetis deterministik.
 *  - kunci nama orang (daftar eksplisit)          → nama sintetis deterministik.
 *
 * `input.nik` tingkat atas untuk warga berisi ID baris penduduk (bukan 16 digit),
 * sehingga sengaja dibiarkan agar regenerasi menarik data penduduk yang sudah
 * tersanitasi. Dipakai bersama oleh Batch surat (metadata) dan render surat sintetis.
 */
final class SuratInputSanitizer
{
    /** @var list<string> kunci yang nilainya diperlakukan sebagai nama orang */
    private const NAME_KEYS = [
        'nama', 'nama_ayah', 'nama_ibu', 'kepala_kk', 'nama_sponsor',
        'penanggung_jawab', 'nama_pelapor', 'nama_pemohon', 'nama_saksi',
        'nama_saksi1', 'nama_saksi2', 'nama_almarhum', 'nama_jenazah',
    ];

    /**
     * @param  array<mixed>  $input
     * @return array<mixed>
     */
    public function sanitize(array $input, int $logSuratId, AcakContext $context): array
    {
        $gen = $context->gen;

        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $input[$key] = $this->sanitize($value, $logSuratId, $context);

                continue;
            }

            if (! is_string($value) && ! is_numeric($value)) {
                continue;
            }

            $strVal = (string) $value;
            $lowKey = strtolower((string) $key);

            // Nomor KK 16 digit.
            if (preg_match('/(^|_)(no|nomor)_?kk$/', $lowKey) && preg_match('/^\d{16}$/', $strVal)) {
                $input[$key] = $context->noKkMap[$strVal] ?? $gen->orphanKk($strVal);

                continue;
            }

            // NIK: setiap nilai 16 digit.
            if (preg_match('/^\d{16}$/', $strVal)) {
                $input[$key] = $context->nikMap[$strVal] ?? $gen->orphanNik($strVal);

                continue;
            }

            // Nomor telepon / HP.
            $normalized = preg_replace('/[\s-]/', '', $strVal) ?? '';
            if (preg_match('/telepon|telp|no_?hp|nohp|whatsapp|wa$/', $lowKey) && preg_match('/^\+?0?8\d{6,}$/', $normalized)) {
                $input[$key] = $gen->phone("input:{$logSuratId}:{$lowKey}");

                continue;
            }

            // Nama orang.
            if (in_array($lowKey, self::NAME_KEYS, true) && trim($strVal) !== '') {
                $input[$key] = $gen->name("input:{$logSuratId}:{$lowKey}", 1);

                continue;
            }
        }

        return $input;
    }

    /**
     * Apakah kolom `input` berisi entri form yang layak disanitasi?
     */
    public function hasInput(mixed $input): bool
    {
        $input = (string) $input;

        return $input !== '' && $input !== '[]' && $input !== '{}' && $input !== 'null';
    }
}
