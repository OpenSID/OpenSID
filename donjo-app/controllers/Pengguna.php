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

use App\Libraries\OTP\OtpManager;
use App\Models\User;
use App\Rules\MatchesCurrentPassword;
use App\Rules\NotInPasswordHistory;
use App\Services\OtpService;
use App\Traits\UploadFotoUser;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

defined('BASEPATH') || exit('No direct script access allowed');

class Pengguna extends Admin_Controller
{
    use UploadFotoUser;

    private OtpManager $otp;
    private OtpService $otpService;

    public function __construct()
    {
        parent::__construct();
        $this->otp        = new OtpManager();
        $this->otpService = new OtpService();
    }

    public function index()
    {
        $userData      = User::findOrFail(ci_auth()->id);
        $botUsername   = null;
        $telegramError = false;
        $isChatStarted = false;

        try {
            $botUsername = setting('telegram_notifikasi') ? $this->otpService->getBotUsername() : null;
            // Jika username bot berhasil didapat dan pengguna memiliki id_telegram,
            // periksa apakah chat sudah dimulai.
            if (! empty($userData->id_telegram) && $botUsername) {
                $isChatStarted = $this->otpService->verifyTelegramChatId($userData->id_telegram);
            }
        } catch (Exception $e) {
            $telegramError = true;
        }

        return view('admin.pengguna.index', [
            'form_action'         => 'pengguna/update',
            'password_action'     => 'pengguna/update_password',
            'userData'            => $userData,
            'telegramBotUsername' => $botUsername,
            'telegramError'       => $telegramError,
            'isChatStarted'       => $isChatStarted,
        ]);

    }

    public function update(): void
    {
        $data    = User::findOrFail(ci_auth()->id);
        $newData = $this->validate($this->request);

        if ($data->email != $newData['email']) {
            if (User::where('email', $newData['email'])->where('id', '!=', $data->id)->exists()) {
                redirect_with('error', 'Email sudah digunakan oleh pengguna lain');
            }

            $newData['email_verified_at'] = null;
        }

        if ($data->id_telegram != $newData['id_telegram']) {
            $newData['telegram_verified_at'] = null;
        }

        if ($data->update($newData)) {
            $this->session->isAdmin = $data;
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function update_keamanan()
    {
        $user = Auth::user();

        if (! $user->hasVerifiedEmail()) {
            return redirect_with('error', 'Anda harus memverifikasi email sebelum mengaktifkan autentikasi dua faktor.', 'pengguna#2fa');
        }

        $password = $this->request['konfirmasi_password'] ?? '';

        if (empty($password)) {
            return redirect_with('error', 'Password harus diisi untuk mengubah pengaturan keamanan.', 'pengguna#2fa');
        }

        if (! Hash::check($password, $user->password)) {
            return redirect_with('error', 'Password yang Anda masukkan tidak sesuai.', 'pengguna#2fa');
        }

        $user->two_factor_enabled = (int) $this->request['two_factor_enabled'];
        $user->save();

        return redirect_with('success', 'Pengaturan keamanan berhasil diperbarui.', 'pengguna#2fa');
    }

    public function update_password(): void
    {
        $user = $this->validate_password($this->request);

        if ($user['status']) {
            $this->session->change_password       = true;
            $this->session->force_change_password = false;

            redirect_with('success', $user['pesan']);
        }

        redirect_with('error', $user['pesan']);
    }

    public function kirim_verifikasi()
    {
        $request = request();

        if ($request->user()->hasVerifiedEmail()) {
            return redirect('pengguna');
        }

        // Email Desa diperlukan sebagai pengirim verifikasi email.
        if (empty(identitas('email_desa'))) {
            return redirect_with(
                key: 'error',
                value: 'Tidak dapat mengirim verifikasi email karena <b>Email Desa</b> belum diisi. Silakan lengkapi terlebih dahulu pada Identitas Desa.',
                to: 'identitas_desa/form',
                autodismis: true
            );
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (Exception $e) {
            log_message('error', $e);

            return redirect_with('error', 'Tidak berhasil mengirim verifikasi email', 'pengguna');
        }

        return redirect_with('success', 'Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.', 'pengguna');
    }

    public function kirim_otp_telegram()
    {
        // cek telegram sudah pernah terpakai atau belum
        $id_telegram = (int) $this->input->post('id_telegram');
        if (! empty($id_telegram) && User::where('id_telegram', '=', $id_telegram)->where('id', '!=', ci_auth()->id)->exists()) {
            return json([
                'status'  => false,
                'message' => 'ID telegram harus unik. ID telegram yang Anda masukkan sudah digunakan oleh pengguna lain',
            ]);
        }

        try {
            $user  = Auth::user() ?: User::find(ci_auth()->id);
            $token = hash('sha256', $raw_token = random_int(100000, 999999));

            $user->id_telegram = $id_telegram;
            $user->token       = $token;
            $user->token_exp   = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +5 minutes'));
            $user->save();

            $this->otp->driver('telegram')->kirimOtp($user->id_telegram, $raw_token);

            return json([
                'status'  => true,
                'message' => 'sucess',
                'data'    => $id_telegram,
            ]);
        } catch (Exception $e) {
            return json([
                'status'  => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function verifikasi_telegram()
    {
        $otp         = $this->input->post('otp');
        $id_telegram = $this->input->post('id_telegram');
        if ($otp == '') {
            return json([
                'status'  => false,
                'message' => 'kode otp kosong',
            ]);
        }

        $verifikasi_otp = User::where('id', '=', ci_auth()->id)
            ->where('id_telegram', '=', $id_telegram)
            ->where('token_exp', '>', date('Y-m-d H:i:s'))
            ->where('token', '=', hash('sha256', $otp))
            ->first();

        if ($verifikasi_otp == null) {
            return json([
                'status'  => false,
                'message' => 'kode otp Salah',
            ]);
        }

        $verifikasi_otp->telegram_verified_at = date('Y-m-d H:i:s');
        $verifikasi_otp->save();
        $this->session->isAdmin->telegram_verified_at = date('Y-m-d H:i:s');
        $this->session->isAdmin->id_telegram          = $id_telegram;

        return json([
            'status'  => true,
            'message' => 'Verifikasi berhasil',
        ]);
    }

    public function verifikasi(string $hash)
    {
        $request = request();
        $user    = request()->user();

        if ($request->user()->hasVerifiedEmail()) {
            return redirect_with('success', 'Verifikasi berhasil', 'pengguna');
        }

        // Check if hash equal with current user email.
        if (! hash_equals($hash, sha1($user->email))) {
            return redirect_with('error', 'Token pengaturan ulang kata sandi ini tidak valid.', 'pengguna');
        }

        $signature = hash_hmac('sha256', $user->email, config('app.key'));

        // Check signature key
        if (! hash_equals($signature, $this->input->get('signature'))) {
            return redirect_with('error', 'Token pengaturan ulang kata sandi ini tidak valid.', 'pengguna');
        }

        // Check for token if expired
        if ($this->input->get('expires') < strtotime(date('Y-m-d H:i:s'))) {
            return redirect_with('error', 'Token reset password ini sudah kadaluarsa.', 'pengguna');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        redirect_with('success', 'Verifikasi berhasil', 'pengguna');
    }

    private function validate($request = []): array
    {
        return [
            'nama'               => nama($request['nama']),
            'email'              => email($request['email']),
            'two_factor_enabled' => (int) $request['two_factor_enabled'],
            'notif_telegram'     => (int) $request['notif_telegram'],
            'id_telegram'        => alfanumerik(empty($request['id_telegram']) ? 0 : $request['id_telegram']),
            'foto'               => $this->urusFoto(auth()->id),
        ];
    }

    private function validate_password($request = [])
    {
        if (config_item('demo_mode') && is_super_admin()) {
            return [
                'status' => false,
                'pesan'  => 'Dalam mode demo, pengguna dengan grup Super Admin tidak dapat mengubah kata sandi.',
            ];
        }

        $user = auth('admin')->user();

        $validator = Validator::make(
            $request,
            [
                'pass_lama' => [
                    'required',
                    new MatchesCurrentPassword($user),
                ],
                'pass_baru' => [
                    'required',
                    'different:pass_lama',
                    'regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/',
                    new NotInPasswordHistory($user),
                ],
                'pass_baru1' => [
                    'required',
                    'same:pass_baru',
                ],
            ],
            [
                'pass_lama.required'  => 'Sandi gagal diganti, <b>Sandi</b> tidak boleh kosong.',
                'pass_baru.required'  => 'Sandi gagal diganti, <b>Sandi</b> tidak boleh kosong.',
                'pass_baru1.required' => 'Sandi gagal diganti, <b>Sandi</b> tidak boleh kosong.',
                'pass_baru.different' => '<b>Sandi</b> gagal diganti, Silakan ganti <b>Sandi Lama</b> Anda dengan <b>Sandi Baru</b>.',
                'pass_baru.regex'     => 'Sandi gagal diganti, <b>Sandi Baru</b> ' . SYARAT_SANDI . '.',
                'pass_baru1.same'     => 'Sandi gagal diganti, <b>Sandi Baru</b> dan <b>Sandi Baru (Ulangi)</b> tidak sama.',
            ]
        );

        if ($validator->fails()) {
            return [
                'status' => false,
                'pesan'  => $validator->errors()->first(),
            ];
        }

        $user->password = Hash::make($request['pass_baru']);

        if ($user->update()) {
            $this->session->sess_regenerate();
            $this->session->isAdmin = $user;

            return [
                'status' => true,
                'pesan'  => 'Sandi berhasil diganti.',
            ];
        }

        return [
            'status' => false,
            'pesan'  => 'Sandi gagal diganti.',
        ];
    }
}
