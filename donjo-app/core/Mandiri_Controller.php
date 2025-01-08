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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Mandiri_Controller extends MY_Controller
{
    public $CI;
    public $is_login;

    public function __construct()
    {
        // To inherit directly the attributes of the parent class.
        parent::__construct();

        $CI             = &get_instance();
        $this->is_login = $this->session->is_login;
        $this->header   = identitas();

        if (setting('layanan_mandiri') == 0 && ! $this->cek_anjungan) {
            show_404();
        }

        // Periksa jika pengguna belum terautentikasi.
        if (! auth('penduduk')->check()) {
            $redirectUrl = $this->session->login_ektp
                ? 'layanan-mandiri/masuk-ektp'
                : 'layanan-mandiri/masuk';

            return redirect($redirectUrl);
        }

        /** @var App\Models\PendudukMandiri $user */
        $user = auth('penduduk')->user();

        $isMustVerify         = $user instanceof Illuminate\Contracts\Auth\MustVerifyEmail;
        $hasVerifiedEmail     = $isMustVerify && $user->hasVerifiedEmail();
        $hasVerifiedTelegram  = $isMustVerify && $user->hasVerifiedTelegram();
        $hasRequiredDocuments = $user->scan_ktp !== null && $user->scan_kk !== null && $user->foto_selfie !== null;

        // Periksa jika pengguna belum verifikasi email atau telegram dan sudah memiliki dokumen yang diperlukan.
        if (! $hasVerifiedEmail && $hasRequiredDocuments) {
            // Pengguna belum memverifikasi email, arahkan ke halaman verifikasi email
            return redirect('layanan-mandiri/daftar/verifikasi/email');
        }
        if (! $hasVerifiedTelegram && $hasRequiredDocuments) {
            // Pengguna belum memverifikasi Telegram, arahkan ke halaman verifikasi Telegram
            return redirect('layanan-mandiri/daftar/verifikasi/telegram');
        }
    }
}
