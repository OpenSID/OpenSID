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

use App\Services\OtpService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

defined('BASEPATH') || exit('No direct script access allowed');

class Otp extends Admin_Controller
{
    public $modul_ini     = 'pengaturan';
    public $sub_modul_ini = 'pengguna';
    protected $otpService;

    public function __construct()
    {
        parent::__construct();
        // Inisialisasi OtpService agar bisa digunakan di dalam controller ini
        $this->otpService = new OtpService();
    }

    /**
     * Request OTP for activation
     */
    public function requestActivation()
    {
        if (! setting('login_otp')) {
            redirect_with('error', 'Fitur OTP tidak aktif.', ci_route('pengguna') . '#otp');
        }

        $request   = $this->input->post();
        $validator = Validator::make($request, [
            'channel'    => 'required|in:email,telegram',
            'identifier' => ['required', 'string'],
        ]);

        $this->validated(request(), $validator->getRules());

        $user       = Auth::user();
        $channel    = $request['channel'];
        $identifier = $request['identifier'];

        $emailNotifActive    = setting('email_notifikasi');
        $telegramNotifActive = setting('telegram_notifikasi');

        // Check if the selected channel is active
        if ($request['channel'] === 'email' && ! $emailNotifActive) {
            redirect_with('error', 'Notifikasi email tidak aktif. Silakan aktifkan di pengaturan sistem.', ci_route('pengguna') . '#otp');
        }

        if ($request['channel'] === 'telegram' && ! $telegramNotifActive) {
            redirect_with('error', 'Notifikasi Telegram tidak aktif. Silakan aktifkan di pengaturan sistem.', ci_route('pengguna') . '#otp');
        }

        // Validate identifier based on channel
        if ($channel === 'email') {
            $emailValidator = Validator::make(['email' => $identifier], [
                'email' => 'required|email',
            ]);

            if ($emailValidator->fails()) {
                redirect_with('error', 'Format email tidak valid', ci_route('pengguna') . '#otp');
            }
        } elseif ($channel === 'telegram') {
            // Verify Telegram chat ID
            if (! $this->otpService->verifyTelegramChatId($identifier)) {
                $botUsername = $this->otpService->getBotUsername();

                $errorMessage = 'Gagal memverifikasi Chat ID Telegram.<br><br>' .
                                'Pastikan Chat ID yang Anda masukkan sudah benar dan Anda sudah memulai percakapan dengan bot ' .
                                'Telegram dengan mengirim pesan <code>/start</code>.';

                if ($botUsername) {
                    $link = '<a href="https://t.me/' . $botUsername . '?start" target="_blank" class="btn btn-social btn-primary btn-sm" style="margin-top: 10px;"><i class="fa fa-telegram"></i> Klik di sini untuk memulai percakapan</a>';
                    $errorMessage .= '<br><br>' . $link;
                }

                redirect_with('error', $errorMessage, ci_route('pengguna') . '#otp', true);
            }
        }

        // Generate and send OTP
        $result = $this->otpService->generateAndSend($user, $channel, $identifier, 'activation');

        if (! $result['sent']) {
            redirect_with('error', 'Gagal mengirim kode OTP. Silakan coba lagi.', ci_route('pengguna') . '#otp');
        }

        // Store activation data in session
        $this->session->set_userdata([
            'otp_activation' => [
                'channel'    => $channel,
                'identifier' => $identifier,
                'sent_at'    => Carbon::now()->timestamp,
                'expires_at' => Carbon::now()->addMinutes(setting('otp_expiry_minutes'))->timestamp,
            ],
        ]);

        redirect_with('success', 'Kode OTP telah dikirim ke ' . ($channel === 'email' ? 'email' : 'Telegram') . ' Anda.', ci_route('pengguna') . '#otp');
    }

    /**
     * Show OTP verification form for activation
     */
    public function showVerifyActivationForm()
    {
        if (! setting('login_otp')) {
            redirect_with('error', 'Fitur OTP tidak aktif.', ci_route('pengguna') . '#otp');
        }

        if (! $this->session->userdata('otp_activation')) {
            redirect_with('error', 'Silakan minta kode OTP terlebih dahulu.', ci_route('pengguna') . '#otp');
        }

        $activation = $this->session->userdata('otp_activation');

        $data = [
            'page_title'       => 'Verifikasi OTP',
            'page_description' => 'Masukkan kode OTP untuk mengaktifkan fitur',
            'channel'          => $activation['channel'],
            'identifier'       => $activation['identifier'],
            'expiry_minutes'   => setting('otp_expiry_minutes') ?? 5,
        ];

        return view('admin.pengguna.index', $data);
    }

    /**
     * Verify and activate OTP
     */
    public function verifyActivation()
    {
        if (! setting('login_otp')) {
            redirect_with('error', 'Fitur OTP tidak aktif.', ci_route('pengguna') . '#otp');
        }

        $request = $this->input->post();

        $validator = Validator::make($request, [
            'otp' => 'required|numeric|digits:6',
        ]);

        $this->validated(request(), $validator->getRules());

        if (! $this->session->userdata('otp_activation')) {
            redirect_with('error', 'Sesi aktivasi tidak ditemukan. Silakan mulai lagi.', ci_route('pengguna') . '#otp');
        }

        $user       = Auth::user();
        $activation = $this->session->userdata('otp_activation');

        // Verify OTP
        $result = $this->otpService->verify($user, $request['otp'], 'activation');

        if (! $result['success']) {
            // Jika gagal karena maksimal percobaan, hapus sesi aktivasi
            if (isset($result['reason']) && $result['reason'] === 'max_attempts') {
                $this->session->unset_userdata('otp_activation');
            }
            redirect_with('error', $result['message'], ci_route('pengguna') . '#otp');
        }

        // Activate OTP for user
        $user->otp_enabled      = true;
        $user->otp_channel      = $activation['channel'];
        $user->otp_identifier   = $activation['identifier'];
        $user->telegram_chat_id = $activation['channel'] === 'telegram' ? $activation['identifier'] : null;
        $user->save();

        // Clear session
        $this->session->unset_userdata('otp_activation');

        redirect_with('success', 'OTP berhasil diaktifkan! Anda sekarang dapat menggunakan OTP untuk login.', ci_route('pengguna') . '#otp');
    }

    /**
     * Deactivate OTP
     */
    public function deactivate()
    {
        if (! setting('login_otp')) {
            redirect_with('error', 'Fitur OTP tidak aktif.', ci_route('pengguna') . '#otp');
        }

        $user                   = Auth::user();
        $user->otp_enabled      = false;
        $user->otp_channel      = null;
        $user->otp_identifier   = null;
        $user->telegram_chat_id = null;
        $user->save();

        // Hapus sesi aktivasi jika ada, untuk membatalkan proses yang sedang berjalan
        $this->session->unset_userdata('otp_activation');

        redirect_with('success', 'OTP berhasil dinonaktifkan.', ci_route('pengguna') . '#otp');
    }

    /**
     * Resend OTP
     */
    public function resend_otp()
    {
        if (! setting('login_otp')) {
            return json(['success' => false, 'message' => 'Fitur OTP tidak aktif.'], 400);
        }

        $request = $this->input->post();

        $purpose = $request['purpose'] ?? 'login'; // default to 'login'

        $result = $this->otpService->resend($purpose, $this->session);

        if ($result['success']) {
            return json(['success' => true, 'message' => $result['message']]);
        }

        return json(['success' => false, 'message' => $result['message']], 400);
    }
}
