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

use App\Models\Config;
use App\Models\User;

defined('BASEPATH') || exit('No direct script access allowed');

class User_setting extends Admin_Controller
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
        $id           = $_SESSION['user'];
        $data['main'] = $this->user_model->get_user($id);
        $this->load->view('setting', $data);
    }

    public function update()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pass_baru', 'Kata Sandi Baru', 'required|callback_syarat_sandi');
        $this->form_validation->set_message('syarat_sandi', 'Harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil');

        if ($this->form_validation->run() !== true) {
            session_error(validation_errors());
            set_session('error', validation_errors());
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->user_model->update_setting(auth()->id);
            if ($this->session->success == -1) {
                set_session('error', $this->session->error_msg);
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect('main');
            }
        }
    }

    public function update_password($id = '')
    {
        $this->user_model->update_password($id);
        if ($this->session->success == -1) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect('main');
        }
    }

    // Kata sandi harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil
    public function syarat_sandi($str)
    {
        return (bool) (preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/', $str));
    }

    public function change_pwd()
    {
        $id                  = $_SESSION['user'];
        $data['main']        = $this->user_model->get_user($id);
        $data['header']      = Config::first();
        $data['latar_login'] = to_base64(default_file(LATAR_SITEMAN, DEFAULT_LATAR_SITEMAN));
        $this->load->view('setting_pwd', $data);
    }

    public function kirim_verifikasi()
    {
        $user = $this->db->where('id', $this->session->user)->get('user')->row();

        if ($user->email_verified_at !== null) {
            $this->session->success = 1;

            return redirect('main');
        }

        try {
            $status = $this->password->driver('email')->sendVerifyLink([
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            log_message('error', $e);

            $this->session->success   = -1;
            $this->session->error_msg = 'Tidak berhasil mengirim verifikasi email';

            return redirect('main');
        }

        if ($status === 'verify') {
            $this->session->success = 6;
        } else {
            $this->session->success   = -1;
            $this->session->error_msg = lang($status);
        }

        return redirect('main');
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

            return redirect('main');
        }

        // Check if hash equal with current user email.
        if (! hash_equals($hash, sha1($user->email))) {
            $this->session->success   = -1;
            $this->session->error_msg = lang('token');

            return redirect('main');
        }

        $signature = hash_hmac('sha256', $user->email, config_item('encryption_key'));

        // Check signature key
        if (! hash_equals($signature, $this->input->get('signature'))) {
            $this->session->success   = -1;
            $this->session->error_msg = lang('token');

            return redirect('main');
        }

        // Check for token if expired
        if ($this->input->get('expires') < strtotime(date('Y-m-d H:i:s'))) {
            $this->session->success   = -1;
            $this->session->error_msg = lang('expired');

            return redirect('main');
        }

        $this->db->where('id', $this->session->user)->update('user', ['email_verified_at' => date('Y-m-d H:i:s')]);

        $this->session->success = 1;

        return redirect('main');
    }
}
