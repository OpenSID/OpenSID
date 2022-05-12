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

defined('BASEPATH') || exit('No direct script access allowed');

class Mandiri extends Admin_Controller
{
    private $_set_page;
    private $_list_session;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mandiri_model');
        $this->load->library('OTP/OTP_manager', null, 'otp_library');
        $this->load->library('email', config_item('email'));

        $this->modul_ini     = 14;
        $this->sub_modul_ini = 56;
        $this->_set_page     = ['20', '50', '100'];
        $this->_list_session = ['cari', 'order_by'];
        $this->telegram      = new Telegram();
    }

    public function clear()
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->per_page = $this->_set_page[0];
        $this->session->order_by = 6;
        redirect($this->controller);
    }

    public function index($p = 1)
    {
        foreach ($this->_list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['func']     = 'index';
        $data['set_page'] = $this->_set_page;
        $data['paging']   = $this->mandiri_model->paging($p);
        $data['main']     = $this->mandiri_model->list_data($data['order_by'], $data['paging']->offset, $data['paging']->per_page);
        $data['keyword']  = $this->mandiri_model->autocomplete();

        $this->render('mandiri/mandiri', $data);
    }

    public function filter($filter = '', $order_by = '')
    {
        $value = $order_by ?: $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect($this->controller);
    }

    public function ajax_pin($id_pend = '')
    {
        session_error_clear();

        $this->redirect_hak_akses('u');
        $data['penduduk'] = $this->mandiri_model->list_penduduk();

        if ($id_pend) {
            $data['id_pend']     = $id_pend;
            $data['form_action'] = site_url("{$this->controller}/update/{$id_pend}");
        } else {
            $data['id_pend']     = null;
            $data['form_action'] = site_url("{$this->controller}/insert");
        }

        $data['tgl_verifikasi_telegram'] = $this->otp_library->driver('telegram')->cek_verifikasi_otp($data['id_pend']);
        $data['tgl_verifikasi_email']    = $this->otp_library->driver('email')->cek_verifikasi_otp($data['id_pend']);

        $this->load->view('mandiri/ajax_pin', $data);
    }

    public function ajax_hp($id_pend)
    {
        $this->redirect_hak_akses('u');
        $data['form_action'] = site_url("{$this->controller}/ubah_hp/{$id_pend}");
        $data['penduduk']    = $this->mandiri_model->get_penduduk($id_pend);

        $this->load->view('mandiri/ajax_hp', $data);
    }

    public function ajax_verifikasi_warga($id_pend)
    {
        $this->redirect_hak_akses('u');
        $data['tgl_verifikasi_telegram'] = $this->otp_library->driver('telegram')->cek_verifikasi_otp($id_pend);
        $data['tgl_verifikasi_email']    = $this->otp_library->driver('email')->cek_verifikasi_otp($id_pend);
        $data['form_action']             = site_url("{$this->controller}/verifikasi_warga/{$id_pend}");
        $data['penduduk']                = $this->mandiri_model->get_mandiri($id_pend);

        $this->load->view('mandiri/ajax_verifikasi_warga', $data);
    }

    public function verifikasi_warga($id_pend)
    {
        $this->redirect_hak_akses('u');

        $post          = $this->input->post();
        $pilihan_kirim = $post['pilihan_kirim'];
        $data          = $this->db->from('tweb_penduduk')->select('telegram, email, nama')->where('id', $id_pend)->get()->row();

        switch (true) {
            case $pilihan_kirim == 'kirim_telegram':
                $this->update_warga_aktif_telegram($id_pend, $data);
                break;

            case $pilihan_kirim == 'kirim_email':
                $this->update_warga_aktif_email($id_pend, $data);
                break;

            default:
                redirect($this->controller);
                break;
        }
    }

    protected function update_warga_aktif_telegram($id_pend, $data)
    {
        $this->redirect_hak_akses('u');
        $this->db->trans_begin();

        try {
            $outp = $this->db->where('id_pend', $id_pend)->set('aktif', true)->update('tweb_penduduk_mandiri');

            $this->telegram->sendMessage([
                'chat_id' => $data->telegram,
                'text'    => <<<EOD
                    HALO {$data->nama},

                    SELAMAT AKUN LAYANAN MANDIRI ANDA SUDAH DIVERIFIKASI DAN TELAH DISETUJUI
                    SAAT INI ANDA SUDAH DAPAT LOGIN DI FITUR LAYANAN MANDIRI

                    TERIMA KASIH.
                    EOD,
                'parse_mode' => 'Markdown',
            ]);

            $this->db->trans_commit();
        } catch (\Exception $e) {
            log_message('error', $e);

            $this->db->trans_rollback();

            status_sukses(false);
            redirect($this->controller);
        }

        status_sukses($outp);
        redirect($this->controller);
    }

    protected function update_warga_aktif_email($id_pend, $data)
    {
        $this->redirect_hak_akses('u');
        $this->db->trans_begin();

        try {
            $outp = $this->db->where('id_pend', $id_pend)->set('aktif', true)->update('tweb_penduduk_mandiri');

            $this->email->from($this->email->smtp_user, 'OpenSID')
                ->to($data->email)
                ->subject('Verifikasi Akun Layanan Mandiri')
                ->set_mailtype('html')
                ->message($this->load->view('mandiri/email/verifikasi-berhasil', ['nama' => $data->nama], true));

            if (! $this->email->send()) {
                throw new \Exception($this->email->print_debugger());
            }

            $this->db->trans_commit();
        } catch (\Exception $e) {
            log_message('error', $e);

            $this->db->trans_rollback();

            status_sukses(false);
            redirect($this->controller);
        }

        status_sukses($outp);
        redirect($this->controller);
    }

    public function ubah_hp($id_pend)
    {
        $this->redirect_hak_akses('u');
        $outp = $this->db->where('id', $id_pend)
            ->set('telepon', bilangan($this->input->post('telepon')))
            ->update('tweb_penduduk');
        status_sukses($outp);
        redirect($this->controller);
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');
        $this->mandiri_model->insert();
        redirect($this->controller);
    }

    public function update($id_pend)
    {
        $this->redirect_hak_akses('u');
        $this->mandiri_model->update($id_pend);
        redirect($this->controller);
    }

    public function delete($id = '')
    {
        $this->redirect_hak_akses('h');
        $this->mandiri_model->delete($id);
        redirect($this->controller);
    }

    public function kirim($id_pend = '')
    {
        $this->redirect_hak_akses('u');
        $pin  = $this->input->post('pin');
        $data = $this->mandiri_model->get_mandiri($id_pend);
        $desa = $this->header['desa'];

        if (cek_koneksi_internet() && $data['telepon']) {
            $no_tujuan = '+62' . substr($data['telepon'], 1);

            $pesan = 'Selamat Datang di Layanan Mandiri ' . ucwords($this->setting->sebutan_desa . ' ' . $desa['nama_desa']) . ' %0A%0AUntuk Menggunakan Layanan Mandiri, silahkan kunjungi ' . site_url('layanan-mandiri') . '%0AAkses Layanan Mandiri : %0A- NIK : ' . sensor_nik_kk($data[nik]) . ' %0A- PIN : ' . $pin . '%0A%0AHarap merahasiakan NIK dan PIN untuk keamanan data anda.%0A%0AHormat kami %0AKepala Desa ' . $desa[nama_desa] . '%0A%0A%0A' . $desa[nama_kepala_desa];

            redirect("https://api.whatsapp.com/send?phone={$no_tujuan}&text={$pesan}");
        }
        redirect($this->controller);
    }
}
