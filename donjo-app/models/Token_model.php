<?php

/*
 * File ini:
 *
 * Model di Modul Token
 *
 * donjo-app/models/Token_model.php
 *
 */

/**
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
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */

class Token_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    public function verifyToken($token)
    {
        return $this->db->where('token', $token)->get('token')->num_rows();
    }
    public function verifyHash($hash)
    {
        $hashResult = $this->db->where('hash', $hash)->get('token')->num_rows();
        $timeRemain = $this->verifyTime($hash);

        return $hashResult == 1 && $timeRemain  <= 5;
    }
    public function getUserbyHash($hash)
    {
        $hashResult = $this->db->where('hash', $hash)->get('token')->row();


        return $hashResult->user_id;
    }
    public function verifyTime($hash)
    {
        $result = $this->db->select('TIMESTAMPDIFF(MINUTE,created_at,NOW()) AS minutes_remaining')->where('hash', $hash)->get('token')->row();
        if ($result) {
            return  intval($result->minutes_remaining);
        } else {
            return 0;
        }
    }
    public function getUserEmail($username)
    {
        $result = $this->db->where('username', $username)->get('user')->row();

        return $result;
    }

    function generateRandomString($length = 10)
    {
        return substr(sha1(rand()), 0, $length);
    }


    public function addToken($data)
    {
        $result = $this->db->insert('token', $data);
        return $result;
    }
    public function sendForgotMail($hash, $UserEmail)
    {
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => $this->config->item('smtp_host'),
            'smtp_port' => $this->config->item('smtp_port'),
            'auth' => $this->config->item('smtp_auth'),
            'smtp_user' => $this->config->item('smtp_user'),
            'smtp_pass' => $this->config->item('smtp_pass'),
            'mailtype'  => 'html',
            'smtp_crypto' => $this->config->item('smtp_crypto'),
        );

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($this->config->item('mail_from'),);
        $this->email->to($UserEmail);

        $baseUrl = $this->config->item('base_url');

        $this->email->subject('Forgot Password');
        $this->email->message("Ini adalah email pemberitahuan bahwa anda melakukan forgot password pada sistem informasi desa<br/>silahkan klik link berikut untuk merubah password baru anda<br/>" . $baseUrl . "index.php/siteman/forgot/" . $hash);

        if (!$this->email->send()) {
            show_error($this->email->print_debugger());
            return false;
        } else {
            return true;
        }
    }
}
