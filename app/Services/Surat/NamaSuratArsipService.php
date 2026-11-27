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

/**
 * Bangun nama file arsip PDF surat (Surat Layanan & Surat Dinas) dan pastikan
 * panjangnya tidak melebihi kolom log_surat/log_surat_dinas.nama_surat (255),
 * agar surat dengan nama/slug panjang tetap bisa dicetak/dipratinjau.
 */
class NamaSuratArsipService
{
    private const MAKS_PANJANG = 255;

    public static function buat(string $url, $nomor, ?string $nik = null, bool $sertakanNik = false): string
    {
        $nomorSurat = str_replace("'", '', (string) $nomor);
        $nomorSurat = preg_replace('/[^a-zA-Z0-9.	]/', '-', $nomorSurat);

        $akhiran = $sertakanNik
            ? '_' . ($nik ?: 'TANPA_NIK') . '_' . date('Y-m-d') . '_' . $nomorSurat . '.pdf'
            : '_' . date('Y-m-d') . '_' . $nomorSurat . '.pdf';

        $url = substr($url, 0, self::MAKS_PANJANG - strlen($akhiran));

        return $url . $akhiran;
    }
}
