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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\User;

defined('BASEPATH') || exit('No direct script access allowed');

class Pengguna extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('passwords');
        $this->load->library('Reset/Password', '', 'password');
        $this->load->library('OTP/OTP_manager', null, 'otp_library');
        $this->load->model('user_model');
    }

    public function index()
    {
        return view('admin.pengguna.index', [
            'form_action'     => 'pengguna/update',
            'password_action' => 'pengguna/update_password',
        ]);
    }

    public function update()
    {
        $data = User::findOrFail(auth()->id);

        if ($data->update($this->validate($this->request))) {
            $this->session->isAdmin = $data;
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    private function validate($request = [])
    {
        return [
            'nama'           => nama($request['nama']),
            'notif_telegram' => (int) $request['notif_telegram'],
            'id_telegram'    => alfanumerik(empty($request['id_telegram']) ? 0 : $request['id_telegram']),
            'foto'           => $this->user_model->urusFoto(Auth()->id),

        ];
    }

    public function update_password($request = [])
    {
        $user = $this->validate_password($this->request);

        if ($user['status']) {
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
        $pwMasihMD5 = (strlen(auth()->password) == 32) && (stripos(auth()->password, '$') === false) ? true : false;

        switch (true) {
            case config_item('demo_mode'):
                $respon = [
                    'status' => false,
                    'pesan'  => 'Sandi gagal diganti, <b>Demo</b> tidak bisa mengubah <b>Sandi</b>.',
                ];
                break;

            case empty($pass_lama) || empty($pass_baru) || empty($pass_baru1):
                $respon = [
                    'status' => false,
                    'pesan'  => 'Sandi gagal diganti, <b>Sandi</b> tidak boleh kosong.',
                ];
                break;

            case ! preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/', $pass_baru):
                $respon = [
                    'status' => false,
                    'pesan'  => 'Sandi gagal diganti, <b>Sandi Baru</b> harus 8 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil dan satu karakter khusus.',
                ];
                break;

            case $pwMasihMD5 && (md5($pass_lama) != auth()->password):
                $respon = [
                    'status' => false,
                    'pesan'  => 'Sandi gagal diganti, <b>Sandi Lama</b> yang anda masukkan tidak sesuai.',
                ];
                break;

            case ! $pwMasihMD5 && (password_verify($pass_lama, auth()->password) === false):
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
                $user           = User::findOrFail(auth()->id);
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
        $user = $this->db->where('id', $this->session->user)->get('user')->row();

        if ($user->email_verified_at !== null) {
            $this->session->success = 1;

            redirect('pengguna');
        }

        try {
            $status = $this->password->driver('email')->sendVerifyLink([
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            log_message('error', $e);

            $this->session->success   = -1;
            $this->session->error_msg = 'Tidak berhasil mengirim verifikasi email';

            redirect('pengguna');
        }

        if ($status === 'verify') {
            $this->session->success = 6;
        } else {
            $this->session->success   = -1;
            $this->session->error_msg = lang($status);
        }

        redirect('pengguna');
    }

    public function kirim_otp_telegram()
    {
        // cek telegram sudah pernah terpakai atau belum
        $id_telegram = (int) $this->input->post('id_telegram');
        if (User::where('id_telegram', '=', $id_telegram)->where('id', '!=', $this->session->user)->exists()) {
            return json([
                'status'  => false,
                'message' => 'Id telegram harus unik',
            ]);
        }

        try {
            $user  = User::find($this->session->user);
            $token = hash('sha256', $raw_token = mt_rand(100000, 999999));

            $user->id_telegram = $id_telegram;
            $user->token       = $token;
            $user->token_exp   = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +5 minutes'));
            $user->save();

            $this->otp_library->driver('telegram')->kirim_otp($user->id_telegram, $raw_token);

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

        $verifikasi_otp = User::where('id', '=', $this->session->user)
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
        $user = $this->db->where('id', $this->session->user)->get('user')->row();

        if ($user->email_verified_at !== null) {
            $this->session->success = 1;

            redirect('pengguna');
        }

        // Check if hash equal with current user email.
        if (! hash_equals($hash, sha1($user->email))) {
            $this->session->success   = -1;
            $this->session->error_msg = lang('token');

            redirect('pengguna');
        }

        $signature = hash_hmac('sha256', $user->email, config_item('encryption_key'));

        // Check signature key
        if (! hash_equals($signature, $this->input->get('signature'))) {
            $this->session->success   = -1;
            $this->session->error_msg = lang('token');

            redirect('pengguna');
        }

        // Check for token if expired
        if ($this->input->get('expires') < strtotime(date('Y-m-d H:i:s'))) {
            $this->session->success   = -1;
            $this->session->error_msg = lang('expired');

            redirect('pengguna');
        }

        $this->db->where('id', $this->session->user)->update('user', ['email_verified_at' => date('Y-m-d H:i:s')]);

        $this->session->success = 1;

        redirect('pengguna');
    }
}
