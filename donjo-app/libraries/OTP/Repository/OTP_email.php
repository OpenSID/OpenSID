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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

require_once 'donjo-app/libraries/OTP/Interface/OTP_interface.php';

class OTP_email implements OTP_interface
{
    /**
     * Intance class codeigniter.
     *
     * @var CI_Controller
     */
    protected $ci;

    /**
     * @var Email
     */
    protected $email;

    public function __construct()
    {
        $this->ci = get_instance();
        $this->ci->load->library('email', config_item('email'));
        $this->ci->email->initialize(config_email());
    }

    /**
     * {@inheritDoc}
     */
    public function kirim_otp($user, $otp)
    {
        if ($this->cek_verifikasi_otp($user)) {
            return true;
        }

        $this->ci->email->from($this->ci->email->smtp_user, 'OpenSID')
            ->to($user)
            ->subject('Verifikasi Akun Email')
            ->set_mailtype('html')
            ->message($this->ci->load->view('fmandiri/email/verifikasi', ['token' => $otp], true));

        if ($this->ci->email->send()) {
            return true;
        }

        throw new Exception($this->ci->email->print_debugger());
    }

    /**
     * {@inheritDoc}
     */
    public function verifikasi_otp($otp, $user = null): bool
    {
        if ($this->cek_verifikasi_otp($user)) {
            return true;
        }

        $token = $this->ci->db->from('tweb_penduduk')
            ->where('email_token', $raw_token = hash('sha256', $otp))
            ->get()
            ->row();

        if (null === $token) {
            return false;
        }

        if (date('Y-m-d H:i:s') > $token->email_tgl_kadaluarsa) {
            return false;
        }

        if (hash_equals($token->email_token, $raw_token)) {
            $this->ci->db
                ->where('id', $user)
                ->update('tweb_penduduk', [
                    'email_tgl_verifikasi' => date('Y-m-d H:i:s'),
                ]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function cek_verifikasi_otp($user): bool
    {
        $token = $this->ci->db->from('tweb_penduduk')
            ->select('email_tgl_verifikasi')
            ->where('id', $user)
            ->get()
            ->row();

        return $token->email_tgl_verifikasi != null;
    }

    /**
     * {@inheritDoc}
     */
    public function verifikasi_berhasil($email, $nama)
    {
        $this->ci->email->from($this->ci->email->smtp_user, 'OpenSID')
            ->to($email)
            ->subject('Berhasil Verifikasi Email')
            ->set_mailtype('html')
            ->message($this->ci->load->view('fmandiri/email/verifikasi-berhasil', ['nama' => $nama], true));

        if ($this->ci->email->send()) {
            return true;
        }

        throw new Exception($this->ci->email->print_debugger());
    }

    /**
     * {@inheritDoc}
     */
    public function kirim_pin_baru($user, $pin, $nama)
    {
        try {
            $this->ci->email->from($this->ci->email->smtp_user, 'OpenSID')
                ->to($user)
                ->subject('PIN Baru')
                ->set_mailtype('html')
                ->message($this->ci->load->view('fmandiri/email/kirim-pin', ['pin' => $pin, 'nama' => $nama], true));

            return (bool) ($this->ci->email->send());
        } catch (Throwable $th) {
            throw new Exception($this->ci->email->print_debugger(), $th->getCode(), $th);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function cek_akun_terdaftar($user): bool
    {
        return isset($this->ci->db) && $this->ci->db->where('email', $user['email'])->where_not_in('id', $user['id'])->get('tweb_penduduk')->num_rows() === 0;
    }

    /**
     * {@inheritDoc}
     */
    public function kirim_pesan($data = [])
    {
        $this->ci->email
            ->from($this->ci->email->smtp_user, 'OpenSID')
            ->to($data['tujuan'])
            ->subject($data['subjek'])
            ->set_mailtype('html')
            ->message($this->ci->load->view('sms/template_email', ['subjek' => $data['subjek'], 'isi' => $data['isi']], true));

        if ($this->ci->email->send()) {
            return true;
        }

        throw new Exception($this->ci->email->print_debugger());
    }
}
