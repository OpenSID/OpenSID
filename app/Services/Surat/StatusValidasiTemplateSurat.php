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

namespace App\Services\Surat;

use App\Enums\StatusValidasiEnum;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Menentukan status validasi template surat saat pengaturan surat (FormatSurat/SuratDinas)
 * disimpan ulang.
 *
 * Hasil "Valid" dari tombol "Validasi Template" dipertahankan selama bagian yang memengaruhi
 * hasil render surat tidak berubah. Kalau salah satunya berubah, status di-reset ke
 * "Belum Divalidasi" supaya template wajib divalidasi ulang sebelum dipakai mencetak
 * (issue #11920).
 *
 * Dipakai kembar oleh Surat_master::validate() dan Surat_dinas::validate().
 */
class StatusValidasiTemplateSurat
{
    /**
     * Field yang menentukan hasil render surat -- persis yang dibaca FakeDataIsian + Html2Pdf
     * saat dry-run ValidatorTemplateSurat. Dibandingkan apa adanya seperti yang akan disimpan
     * ke database (template_desa mentah, header/footer int, form_isian/kode_isian sudah JSON,
     * orientasi/ukuran string).
     */
    private const FIELD_RENDER = [
        'template_desa',
        'header',
        'footer',
        'form_isian',
        'kode_isian',
        'orientasi',
        'ukuran',
    ];

    /**
     * @param \App\Models\FormatSurat|\App\Models\SuratDinas|null $suratLama data tersimpan sebelum ditimpa (null = surat baru)
     * @param array<string, mixed>                                $data      nilai final yang akan disimpan
     *
     * @return array{status_validasi: int, pesan_validasi: string|null, divalidasi_pada: mixed}
     */
    public static function untukSimpan($suratLama, array $data): array
    {
        if ($suratLama !== null && ! self::templateBerubah($suratLama, $data)) {
            $lama = $suratLama->getAttributes();

            return [
                'status_validasi' => (int) ($lama['status_validasi'] ?? StatusValidasiEnum::BELUM_DIVALIDASI),
                'pesan_validasi'  => $lama['pesan_validasi'] ?? null,
                'divalidasi_pada' => $lama['divalidasi_pada'] ?? null,
            ];
        }

        return [
            'status_validasi' => StatusValidasiEnum::BELUM_DIVALIDASI,
            'pesan_validasi'  => null,
            'divalidasi_pada' => null,
        ];
    }

    /**
     * @param \App\Models\FormatSurat|\App\Models\SuratDinas $suratLama
     * @param array<string, mixed>                           $data
     */
    private static function templateBerubah($suratLama, array $data): bool
    {
        // Atribut mentah (persis nilai kolom di DB), bukan hasil accessor Eloquent yang
        // sudah men-decode JSON form_isian/kode_isian.
        $lama = $suratLama->getAttributes();

        foreach (self::FIELD_RENDER as $field) {
            if ((string) ($lama[$field] ?? '') !== (string) ($data[$field] ?? '')) {
                return true;
            }
        }

        return false;
    }
}
