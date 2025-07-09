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

use App\Models\User;
use App\Libraries\OTP\OtpManager;
use Illuminate\Auth\Events\Verified;

defined('BASEPATH') || exit('No direct script access allowed');

class Pengguna extends Admin_Controller
{
    private OtpManager $otp;

    public function __construct()
    {
        parent::__construct();
        $this->otp      = new OtpManager();
        $this->load->model('user_model');
        log_message('error', auth()->id);
        log_message('error', ci_auth()->id);
    }

    public function index()
    {
        $userData = User::findOrFail(ci_auth()->id);

        return view('admin.pengguna.index', [
            'form_action'     => 'pengguna/update',
            'password_action' => 'pengguna/update_password',
            'userData'        => $userData,
        ]);
    }

    public function update(): void
    {
        $data    = User::findOrFail(ci_auth()->id);
        $newData = $this->validate($this->request);
        if ($data->email != $newData['email']) {
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

    private function validate($request = []): array
    {
        return [
            'nama'           => nama($request['nama']),
            'email'          => email($request['email']),
            'notif_telegram' => (int) $request['notif_telegram'],
            'id_telegram'    => alfanumerik(empty($request['id_telegram']) ? 0 : $request['id_telegram']),
            'foto'           => $this->user_model->urusFoto(Auth()->id),
        ];
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

    private function validate_password($request = [])
    {
        $pass_lama  = $request['pass_lama'];
        $pass_baru  = $request['pass_baru'];
        $pass_baru1 = $request['pass_baru1'];
        $pwMasihMD5 = (strlen(ci_auth()->password) == 32) && (stripos(ci_auth()->password, '$') === false);

        switch (true) {
            case empty($pass_lama) || empty($pass_baru) || empty($pass_baru1):
                $respon = [
                    'status' => false,
                    'pesan'  => 'Sandi gagal diganti, <b>Sandi</b> tidak boleh kosong.',
                ];
                break;

            case ! preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/', $pass_baru):
                $respon = [
                    'status' => false,
                    'pesan'  => 'Sandi gagal diganti, <b>Sandi Baru</b> ' . SYARAT_SANDI . '.',
                ];
                break;

            case $pwMasihMD5 && (md5($pass_lama) != ci_auth()->password):

            case ! $pwMasihMD5 && (! password_verify($pass_lama, ci_auth()->password)):
                $respon = [
                    'status' => false,
                    'pesan'  => 'Sandi gagal diganti, <b>Sandi Lama</b> yang anda masukkan tidak sesuai.',
                ];
                break;

            case $pass_baru == $pass_lama:
                $respon = [
                    'status' => false,
                    'pesan'  => '<b>Sandi</b> gagal diganti, Silahkan ganti <b>Sandi Lama</b> anda dengan <b>Sandi Baru</b>.',
                ];
                break;

            case $pass_baru != $pass_baru1:
                $respon = [
                    'status' => false,
                    'pesan'  => 'Sandi gagal diganti, <b>Sandi Baru</b> dan <b>Sandi Baru (Ulangi)</b> tidak sama.',
                ];
                break;

            default:
                $user           = User::findOrFail(ci_auth()->id);
                $user->password = generatePasswordHash($pass_baru);

                if ($user->update()) {
                    $this->session->isAdmin = $user;
                    $respon                 = [
                        'status' => true,
                        'pesan'  => 'Sandi berhasil diganti.',
                    ];
                } else {
                    $respon = [
                        'status' => false,
                        'pesan'  => 'Sandi gagal diganti.',
                    ];
                }
        }

        return $respon;
    }

    public function kirim_verifikasi()
    {
        $request = request();

        if ($request->user()->hasVerifiedEmail()) {
            return redirect('pengguna');
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            return redirect_with('error', 'Tidak berhasil mengirim verifikasi email', 'pengguna');
        }

        return redirect_with('success', 'Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.', 'pengguna');;
    }

    public function kirim_otp_telegram()
    {
        // cek telegram sudah pernah terpakai atau belum
        $id_telegram = (int) $this->input->get('id_telegram');
        if (User::where('id_telegram', '=', $id_telegram)->where('id', '!=', ci_auth()->id)->exists()) {
            return json([
                'status'  => false,
                'message' => 'Id telegram harus unik',
            ]);
        }

        try {
            $user  = User::find(ci_auth()->id);
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
                'status'   => false,
                'messages' => $e->getMessage(),
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
}
