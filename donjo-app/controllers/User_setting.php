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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\User;

defined('BASEPATH') || exit('No direct script access allowed');

class User_setting extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('passwords');
        $this->load->library('Reset/Password', '', 'password');
        $this->load->model('user_model');
    }

    public function index()
    {
        $id           = $_SESSION['user'];
        $data['main'] = $this->user_model->get_user($id);
        $this->load->view('setting', $data);
    }

    public function update($id = '')
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pass_baru', 'Kata Sandi Baru', 'required|callback_syarat_sandi');
        $this->form_validation->set_message('syarat_sandi', 'Harus 6 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil');

        if ($this->form_validation->run() !== true) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->user_model->update_setting($id);
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
        $id             = $_SESSION['user'];
        $data['main']   = $this->user_model->get_user($id);
        $data['header'] = $this->config_model->get_data();
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
