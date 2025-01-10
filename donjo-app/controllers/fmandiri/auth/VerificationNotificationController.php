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

class VerificationNotificationController extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(['mandiri_model', 'theme_model']);

        if (! setting('tampilkan_pendaftaran')) {
            show_404();
        }

        if (! auth('penduduk')->check()) {
            redirect('layanan-mandiri/masuk');
        }
    }

    public function telegram()
    {
        return auth('penduduk')->user()->hasVerifiedTelegram()
            ? redirect('layanan-mandiri/beranda')
            : view('layanan_mandiri.auth.verify');
    }

    public function sendNotificationTelegram()
    {
        /** @var App\Models\PendudukMandiri $user */
        $user = auth('penduduk')->user();

        if ($user->hasVerifiedTelegram()) {
            return redirect('layanan-mandiri/beranda');
        }

        try {
            $user->sendTelegramVerificationNotification();
            $this->session->set_flashdata('notif', 'Tautan verifikasi baru telah dikirim ke alamat telegram yang Anda berikan saat pendaftaran.');

            return redirect('layanan-mandiri/daftar/verifikasi/telegram');
        } catch (Exception $e) {
            log_message('error', $e);

            $this->session->set_flashdata('notif', 'Tidak berhasil mengirim tautan verifikasi, silahkan mencoba kembali.');

            return redirect('layanan-mandiri/daftar/verifikasi/telegram');
        }
    }

    public function verifyTelegram($hash)
    {
        /** @var App\Models\PendudukMandiri $user */
        $user = auth('penduduk')->user();

        if ($user->hasVerifiedTelegram()) {
            return redirect('layanan-mandiri/beranda');
        }

        // Check if hash equal with current user email.
        if (! hash_equals($hash, sha1($user->telegram))) {
            redirect_with('notif', __('passwords.token'), 'layanan-mandiri/daftar/verifikasi/telegram');
        }

        $signature = hash_hmac('sha256', $user->telegram, config_item('encryption_key'));

        // Check signature key
        if (! hash_equals($signature, $this->input->get('signature'))) {
            redirect_with('notif', __('passwords.token'), 'layanan-mandiri/daftar/verifikasi/telegram');
        }

        // Check for token if expired
        if ($this->input->get('expires') < strtotime(date('Y-m-d H:i:s'))) {
            redirect_with('notif', __('Token reset password ini sudah kadaluarsa.'), 'layanan-mandiri/daftar/verifikasi/telegram');
        }

        $user->markTelegramAsVerified();

        if (! $user->hasVerifiedEmail()) {
            redirect('layanan-mandiri/daftar/verifikasi/email');
        }

        // Logout user after verify
        auth('penduduk')->logout();

        $this->session->unset_userdata([
            'mandiri', 'is_login',
            'is_anjungan', 'data_permohonan',
            'auth_mandiri', 'login_ektp',
        ]);

        redirect_with('notif', 'Anda sudah terdaftar di Layanan Mandiri. Saat ini, akun Anda sedang ditinjau oleh admin. Silakan tunggu konfirmasi lebih lanjut sebelum dapat melakukan login.', 'layanan-mandiri/masuk');
    }

    public function email()
    {
        return auth('penduduk')->user()->hasVerifiedEmail()
            ? redirect('layanan-mandiri/beranda')
            : view('layanan_mandiri.auth.verify');
    }

    public function sendNotificationEmail()
    {
        /** @var App\Models\PendudukMandiri $user */
        $user = auth('penduduk')->user();

        if ($user->hasVerifiedEmail()) {
            return redirect('layanan-mandiri/beranda');
        }

        try {
            $user->sendEmailVerificationNotification();
            $this->session->set_flashdata('notif', 'Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.');

            return redirect('layanan-mandiri/daftar/verifikasi/email');
        } catch (Exception $e) {
            log_message('error', $e);

            $this->session->set_flashdata('notif', 'Tidak berhasil mengirim tautan verifikasi, silahkan mencoba kembali.');

            return redirect('layanan-mandiri/daftar/verifikasi/email');
        }
    }

    public function verifyEmail($hash)
    {
        /** @var App\Models\PendudukMandiri $user */
        $user = auth('penduduk')->user();

        if ($user->hasVerifiedEmail()) {
            return redirect('layanan-mandiri/beranda');
        }

        // Check if hash equal with current user email.
        if (! hash_equals($hash, sha1($user->email))) {
            redirect_with('notif', __('passwords.token'), 'layanan-mandiri/daftar/verifikasi/email');
        }

        $signature = hash_hmac('sha256', $user->email, config_item('encryption_key'));

        // Check signature key
        if (! hash_equals($signature, $this->input->get('signature'))) {
            redirect_with('notif', __('passwords.token'), 'layanan-mandiri/daftar/verifikasi/email');
        }

        // Check for token if expired
        if ($this->input->get('expires') < strtotime(date('Y-m-d H:i:s'))) {
            redirect_with('notif', __('Token reset password ini sudah kadaluarsa.'), 'layanan-mandiri/daftar/verifikasi/email');
        }

        $user->markEmailAsVerified();

        if (! $user->hasVerifiedTelegram()) {
            redirect('layanan-mandiri/daftar/verifikasi/telegram');
        }

        // Logout user after verify
        auth('penduduk')->logout();

        $this->session->unset_userdata([
            'mandiri', 'is_login',
            'is_anjungan', 'data_permohonan',
            'auth_mandiri', 'login_ektp',
        ]);

        redirect_with('notif', 'Anda sudah terdaftar di Layanan Mandiri. Saat ini, akun Anda sedang ditinjau oleh admin. Silakan tunggu konfirmasi lebih lanjut sebelum dapat melakukan login.', 'layanan-mandiri/masuk');
    }
}
