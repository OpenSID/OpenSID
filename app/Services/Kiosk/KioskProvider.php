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

namespace App\Services\Kiosk;

/**
 * Kontrak penyedia kios (didaftarkan add-on ke {@see KioskResolver}).
 */
interface KioskProvider
{
    /**
     * Data sesi kios dari sesi/cookie saat ini, atau `[]` bila bukan sesi kios.
     *
     * @return array<string, mixed>
     */
    public function resolve(): array;

    /**
     * Validasi & aktifkan sesi kios dari pengenal (uuid) yang dikirim client.
     *
     * @param KioskActivationMode $mode {@see KioskActivationMode::DeviceCheck}
     *              (hanya kios "utama" mis. ANJUNGAN, tanpa menandai sesi) atau
     *              {@see KioskActivationMode::Login} (semua tipe kios + tandai
     *              sesi sebagai sesi kios).
     */
    public function activate(string $uuid, KioskActivationMode $mode): KioskActivationResult;

    /**
     * Apakah sesi saat ini adalah sesi kios (ditandai oleh add-on saat login)?
     * Flag sesi dimiliki add-on; core menanyakannya lewat method ini.
     */
    public function isActiveSession(): bool;
}
