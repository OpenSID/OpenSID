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

class Keluarga extends Admin_Controller
{
    private $_set_page;
    private $_list_session;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['keluarga_model', 'penduduk_model', 'wilayah_model', 'program_bantuan_model']);
        $this->modul_ini     = 2;
        $this->sub_modul_ini = 22;
        $this->_set_page     = ['20', '50', '100'];
        $this->_list_session = ['jenis_peristiwa', 'status_hanya_tetap', 'status_dasar', 'sex', 'dusun', 'rw', 'rt', 'cari', 'kelas', 'filter', 'id_bos', 'judul_statistik', 'bantuan_keluarga', 'kumpulan_kk'];
    }

    public function clear_session()
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->per_page     = $this->_set_page[0];
        $this->session->status_dasar = 1; // tampilkan KK aktif saja
    }

    public function clear()
    {
        $this->clear_session();
        redirect($this->controller);
    }

    public function index($p = 1, $o = 1)
    {
        $data['p'] = $p;
        $data['o'] = $o;

        foreach ($this->_list_session as $list) {
            if (in_array($list, ['dusun', 'rw', 'rt'])) {
                ${$list} = $this->session->{$list};
            } else {
                $data[$list] = $this->session->{$list} ?: '';
            }
        }

        if (isset($dusun)) {
            $data['dusun']   = $dusun;
            $data['list_rw'] = $this->wilayah_model->list_rw($dusun);

            if (isset($rw)) {
                $data['rw']      = $rw;
                $data['list_rt'] = $this->wilayah_model->list_rt($dusun, $rw);

                if (isset($rt)) {
                    $data['rt'] = $rt;
                } else {
                    $data['rt'] = '';
                }
            } else {
                $data['rw'] = '';
            }
        } else {
            $data['dusun'] = $data['rw'] = $data['rt'] = '';
        }

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['func']       = 'index';
        $data['set_page']   = $this->_set_page;
        $list_data          = $this->keluarga_model->list_data($o, $p);
        $data['paging']     = $list_data['paging'];
        $data['main']       = $list_data['main'];
        $data['list_sex']   = $this->referensi_model->list_data('tweb_penduduk_sex');
        $data['list_dusun'] = $this->wilayah_model->list_dusun();

        $this->render('sid/kependudukan/keluarga', $data);
    }

    public function autocomplete()
    {
        return json($this->keluarga_model->autocomplete($this->input->post('cari')));
    }

    public function cetak($o = 0, $aksi = '', $privasi_kk = 0)
    {
        $data['main'] = $this->keluarga_model->list_data($o, -1);
        if ($privasi_kk == 1) {
            $data['privasi_kk'] = true;
        }
        $this->load->view("sid/kependudukan/keluarga_{$aksi}", $data);
    }

    public function form_peristiwa($peristiwa = '')
    {
        $this->redirect_hak_akses('u');
        // Acuan jenis peristiwa berada pada ref_peristiwa
        // Yg valid hanya peristiwa datang masuk
        if ($peristiwa != 5) {
            redirect($this->controller);
        }

        $this->session->jenis_peristiwa = $peristiwa;
        $this->form();
    }

    public function form_peristiwa_a($peristiwa = '', $p = 1, $o = 0, $id = 0)
    {
        $this->redirect_hak_akses('u');
        // Acuan jenis peristiwa berada pada ref_peristiwa
        $this->session->jenis_peristiwa = $peristiwa;
        $this->form_a($p, $o, $id);
    }

    // Masukkan KK baru
    public function form($p = 1, $o = 0)
    {
        $this->redirect_hak_akses('u');
        // Reset kalau dipanggil dari luar pertama kali ($_POST kosong)
        if (empty($_POST) && (! isset($_SESSION['dari_internal']) || ! $_SESSION['dari_internal'])) {
            unset($_SESSION['validation_error']);
        }

        $data['kk_baru'] = true;

        // Validasi dilakukan di keluarga_model sewaktu insert dan update
        if (isset($_SESSION['validation_error']) && $_SESSION['validation_error']) {
            // Kalau dipanggil internal pakai data yang disimpan di $_SESSION
            if ($_SESSION['dari_internal']) {
                $data['penduduk'] = $_SESSION['post'];
            } else {
                $data['penduduk'] = $_POST;
            }
            // Di penduduk_isian_form memakai 'sex' sesuai dengan nama kolom
            // tapi pengisian nilai sebelumnya menggunakan 'id_sex'
            $data['penduduk']['id_sex'] = $data['penduduk']['sex'];
        } else {
            $data['penduduk'] = null;
        }
        $data['kk']                 = null;
        $data['form_action']        = site_url("{$this->controller}/insert_new");
        $data['penduduk_lepas']     = $this->keluarga_model->list_penduduk_lepas();
        $data['dusun']              = $this->wilayah_model->list_dusun();
        $data['rw']                 = $this->wilayah_model->list_rw($data['penduduk']['dusun']);
        $data['rt']                 = $this->wilayah_model->list_rt($data['penduduk']['dusun'], $data['penduduk']['rw']);
        $data['agama']              = $this->referensi_model->list_data('tweb_penduduk_agama');
        $data['pendidikan_sedang']  = $this->penduduk_model->list_pendidikan_sedang();
        $data['pendidikan_kk']      = $this->penduduk_model->list_pendidikan_kk();
        $data['pekerjaan']          = $this->penduduk_model->list_pekerjaan();
        $data['warganegara']        = $this->penduduk_model->list_warganegara();
        $data['hubungan']           = $this->penduduk_model->list_hubungan();
        $data['kawin']              = $this->penduduk_model->list_status_kawin();
        $data['golongan_darah']     = $this->penduduk_model->list_golongan_darah();
        $data['bahasa']             = $this->referensi_model->list_data('ref_penduduk_bahasa');
        $data['cacat']              = $this->penduduk_model->list_cacat();
        $data['sakit_menahun']      = $this->referensi_model->list_data('tweb_sakit_menahun');
        $data['cara_kb']            = $this->penduduk_model->list_cara_kb($data['penduduk']['id_sex']);
        $data['ktp_el']             = $this->referensi_model->list_ktp_el();
        $data['status_rekam']       = $this->referensi_model->list_status_rekam();
        $data['tempat_dilahirkan']  = $this->referensi_model->list_ref_flip(TEMPAT_DILAHIRKAN);
        $data['jenis_kelahiran']    = $this->referensi_model->list_ref_flip(JENIS_KELAHIRAN);
        $data['penolong_kelahiran'] = $this->referensi_model->list_ref_flip(PENOLONG_KELAHIRAN);
        $data['pilihan_asuransi']   = $this->referensi_model->list_data('tweb_penduduk_asuransi');
        $data['kehamilan']          = $this->referensi_model->list_data('ref_penduduk_hamil');
        $data['suku']               = $this->penduduk_model->get_suku();
        $data['nik_sementara']      = $this->penduduk_model->nik_sementara();
        $data['cek_nik']            = get_nik($data['penduduk']['nik']);
        $data['cek_nokk']           = get_nokk($data['kk']['no_kk']);
        $data['nokk_sementara']     = $this->keluarga_model->nokk_sementara();

        if ($this->session->status_hanya_tetap) {
            $data['status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status', $this->session->status_hanya_tetap, 1);
        } else {
            $data['status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status', null, 1);
        }
        $data['jenis_peristiwa'] = $this->session->jenis_peristiwa;

        $this->session->unset_userdata(['dari_internal']);

        $this->render('sid/kependudukan/keluarga_form', $data);
    }

    // Tambah anggota keluarga dari penduduk baru
    // Tidak boleh tambah anggota bagi kasus kepala keluarga mati/hilang/pindah
    public function form_a($p = 1, $o = 0, $id = 0)
    {
        $this->redirect_hak_akses('u');
        $kepala = $this->keluarga_model->get_kepala_a($id);
        $this->redirect_tidak_valid(empty($kepala['id']) || $kepala['status_dasar'] == 1);

        if (empty($_POST) && ! $_SESSION['dari_internal']) {
            unset($_SESSION['validation_error']);
        } else {
            unset($_SESSION['dari_internal']);
        }

        $data['id_kk']              = $id;
        $data['kk']                 = $this->keluarga_model->get_kepala_a($id);
        $data['form_action']        = site_url("{$this->controller}/insert_a");
        $data['agama']              = $this->referensi_model->list_data('tweb_penduduk_agama');
        $data['pendidikan_kk']      = $this->penduduk_model->list_pendidikan_kk();
        $data['pendidikan_sedang']  = $this->penduduk_model->list_pendidikan_sedang();
        $data['pekerjaan']          = $this->penduduk_model->list_pekerjaan();
        $data['warganegara']        = $this->penduduk_model->list_warganegara();
        $data['hubungan']           = $this->penduduk_model->list_hubungan($data['kk']['status_kawin'], $data['kk']['sex']);
        $data['kawin']              = $this->penduduk_model->list_status_kawin();
        $data['golongan_darah']     = $this->penduduk_model->list_golongan_darah();
        $data['bahasa']             = $this->referensi_model->list_data('ref_penduduk_bahasa');
        $data['cacat']              = $this->penduduk_model->list_cacat();
        $data['sakit_menahun']      = $this->referensi_model->list_data('tweb_sakit_menahun');
        $data['cara_kb']            = $this->penduduk_model->list_cara_kb($data['penduduk']['id_sex']);
        $data['ktp_el']             = $this->referensi_model->list_ktp_el();
        $data['status_rekam']       = $this->referensi_model->list_status_rekam();
        $data['tempat_dilahirkan']  = $this->referensi_model->list_ref_flip(TEMPAT_DILAHIRKAN);
        $data['jenis_kelahiran']    = $this->referensi_model->list_ref_flip(JENIS_KELAHIRAN);
        $data['penolong_kelahiran'] = $this->referensi_model->list_ref_flip(PENOLONG_KELAHIRAN);
        $data['pilihan_asuransi']   = $this->referensi_model->list_data('tweb_penduduk_asuransi');
        $data['kehamilan']          = $this->referensi_model->list_data('ref_penduduk_hamil');
        $data['suku']               = $this->penduduk_model->get_suku();
        $data['nik_sementara']      = $this->penduduk_model->nik_sementara();

        if ($this->session->status_hanya_tetap) {
            $data['status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status', $this->session->status_hanya_tetap, 1);
        } else {
            $data['status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status', null, 1);
        }
        $data['jenis_peristiwa'] = $this->session->jenis_peristiwa;

        // Validasi dilakukan di keluarga_model sewaktu insert dan update
        if ($_SESSION['validation_error']) {
            $data['id_kk']    = $_SESSION['id_kk'];
            $data['kk']       = $_SESSION['kk'];
            $data['penduduk'] = $_SESSION['post'];
        }

        $this->render('sid/kependudukan/keluarga_form_a', $data);
    }

    public function edit_nokk($p = 1, $o = 0, $id = 0)
    {
        $this->redirect_hak_akses('u');
        $data['kk']                 = $this->keluarga_model->get_keluarga($id);
        $data['dusun']              = $this->wilayah_model->list_dusun();
        $data['rw']                 = $this->wilayah_model->list_rw($data['kk']['dusun']);
        $data['rt']                 = $this->wilayah_model->list_rt($data['kk']['dusun'], $data['kk']['rw']);
        $data['program']            = $this->program_bantuan_model->list_program_keluarga($id);
        $data['keluarga_sejahtera'] = $this->referensi_model->list_data('tweb_keluarga_sejahtera');
        $data['cek_nokk']           = get_nokk($data['kk']['no_kk']);
        $data['nokk_sementara']     = $this->keluarga_model->nokk_sementara();
        $data['form_action']        = site_url("{$this->controller}/update_nokk/{$id}");

        $this->load->view('sid/kependudukan/ajax_edit_nokk', $data);
    }

    // Tambah KK dari penduduk yg ada
    public function form_old($p = 1, $o = 0, $id = 0)
    {
        $this->redirect_hak_akses('u');
        $data['penduduk']       = $this->keluarga_model->list_penduduk_lepas();
        $data['cek_nokk']       = get_nokk($data['kk']['no_kk']);
        $data['nokk_sementara'] = $this->keluarga_model->nokk_sementara();
        $data['form_action']    = site_url("{$this->controller}/insert/{$id}");
        $this->load->view('sid/kependudukan/ajax_add_keluarga', $data);
    }

    public function filter($filter)
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }

        redirect($this->controller);
    }

    public function dusun()
    {
        $this->session->unset_userdata(['rw', 'rt']);
        $dusun = $this->input->post('dusun');
        if ($dusun != '') {
            $this->session->dusun = $dusun;
        } else {
            $this->session->unset_userdata('dusun');
        }

        redirect($this->controller);
    }

    public function rw()
    {
        $this->session->unset_userdata('rt');
        $rw = $this->input->post('rw');
        if ($rw != '') {
            $this->session->rw = $rw;
        } else {
            $this->session->unset_userdata('rw');
        }

        redirect($this->controller);
    }

    public function rt()
    {
        $rt = $this->input->post('rt');
        if ($rt != '') {
            $this->session->rt = $rt;
        } else {
            $this->session->unset_userdata('rt');
        }

        redirect($this->controller);
    }

    // Tambah KK dengan memilih dari penduduk yg sudah ada
    public function insert()
    {
        $this->redirect_hak_akses('u');
        $this->keluarga_model->insert();

        redirect($this->controller);
    }

    public function insert_a()
    {
        $this->redirect_hak_akses('u');
        $id_kk = $this->input->post('id_kk');
        $this->keluarga_model->insert_a();
        if ($_SESSION['validation_error']) {
            $_SESSION['id_kk']         = $id_kk;
            $_SESSION['kk']            = $this->keluarga_model->get_kepala_a($id_kk);
            $_SESSION['dari_internal'] = true;
            redirect("{$this->controller}/form_a/1/0/{$id_kk}");
        } else {
            redirect("{$this->controller}/anggota/1/0/{$id_kk}");
        }
    }

    // Tambah KK dengan mengisi form penduduk kepala keluarga baru
    public function insert_new()
    {
        $this->redirect_hak_akses('u');
        $this->keluarga_model->insert_new();
        if ($_SESSION['success'] == -1) {
            $_SESSION['dari_internal'] = true;
            redirect("{$this->controller}/form");
        } else {
            redirect($this->controller);
        }
    }

    public function update_nokk($id = 0)
    {
        $this->redirect_hak_akses('u');
        $this->redirect_tidak_valid($this->keluarga_model->get_kepala_a($id)['status_dasar'] == 1);
        $this->keluarga_model->update_nokk($id);

        redirect($this->controller);
    }

    public function delete($p = 1, $o = 0, $id = 0)
    {
        $this->redirect_hak_akses('h');
        $this->redirect_tidak_valid($this->keluarga_model->cek_boleh_hapus($id));
        $this->keluarga_model->delete($id);

        redirect($this->controller);
    }

    public function delete_all()
    {
        $this->redirect_hak_akses('h');
        $this->keluarga_model->delete_all();

        redirect($this->controller);
    }

    public function anggota($p = 1, $o = 0, $id = 0)
    {
        $data['p']  = $p;
        $data['o']  = $o;
        $data['kk'] = $id;

        $data['main']      = $this->keluarga_model->list_anggota($id);
        $data['kepala_kk'] = $this->keluarga_model->get_kepala_kk($id);
        $data['program']   = $this->program_bantuan_model->get_peserta_program(2, $data['kepala_kk']['no_kk']);

        $this->render('sid/kependudukan/keluarga_anggota', $data);
    }

    public function ajax_add_anggota($p = 1, $o = 0, $id = 0)
    {
        $this->redirect_hak_akses('u');
        $data['p'] = $p;
        $data['o'] = $o;

        $kk = $this->keluarga_model->get_kepala_kk($id);
        if ($kk) {
            $data['kepala_kk'] = $kk;
        } else {
            $data['kepala_kk'] = null;
        }
        $data['hubungan']    = $this->penduduk_model->list_hubungan($data['kepala_kk']['status_kawin_id'], $data['kepala_kk']['sex_id']);
        $data['main']        = $this->keluarga_model->list_anggota($id);
        $data['penduduk']    = $this->keluarga_model->list_penduduk_lepas();
        $data['form_action'] = site_url("{$this->controller}/add_anggota/{$p}/{$o}/{$id}");

        $this->load->view('sid/kependudukan/ajax_add_anggota_form', $data);
    }

    // $id adalah id tweb_penduduk
    public function edit_anggota($p = 1, $o = 0, $id_kk = 0, $id = 0)
    {
        $this->redirect_hak_akses('u');
        $data['p'] = $p;
        $data['o'] = $o;

        $data['hubungan'] = $this->keluarga_model->list_hubungan();
        $data['main']     = $this->keluarga_model->get_anggota($id);

        $kk = $this->keluarga_model->get_kepala_kk($id_kk);
        if ($kk) {
            $data['kepala_kk'] = $kk;
        } else {
            $data['kepala_kk'] = null;
        }

        $data['form_action'] = site_url("{$this->controller}/update_anggota/{$p}/{$o}/{$id_kk}/{$id}");

        $this->load->view('sid/kependudukan/ajax_edit_anggota_form', $data);
    }

    public function kartu_keluarga($p = 1, $o = 0, $id = 0)
    {
        $data['p']     = $p;
        $data['o']     = $o;
        $data['id_kk'] = $id;

        $data['hubungan'] = $this->keluarga_model->list_hubungan();
        $data['main']     = $this->keluarga_model->list_anggota($id);
        $kk               = $this->keluarga_model->get_kepala_kk($id);
        $data['desa']     = $this->config_model->get_data();

        if ($kk) {
            $data['kepala_kk'] = $kk;
        } else {
            $data['kepala_kk'] = $this->keluarga_model->get_keluarga($id);
        }

        $data['penduduk']    = $this->keluarga_model->list_penduduk_lepas();
        $data['form_action'] = site_url("{$this->controller}/print");

        $this->render('sid/kependudukan/kartu_keluarga', $data);
    }

    public function cetak_kk($id = 0)
    {
        $data = $this->keluarga_model->get_data_cetak_kk($id);

        $this->load->view('sid/kependudukan/cetak_kk_all', $data);
    }

    public function cetak_kk_all()
    {
        $data = $this->keluarga_model->get_data_cetak_kk_all();

        $this->load->view('sid/kependudukan/cetak_kk_all', $data);
    }

    public function doc_kk($id = 0)
    {
        $this->keluarga_model->unduh_kk($id);
    }

    public function doc_kk_all($id = 0)
    {
        $this->keluarga_model->unduh_kk();
    }

    // Tidak boleh tambah anggota bagi kasus kepala keluarga mati/hilang/pindah
    public function add_anggota($p = 1, $o = 0, $id = 0)
    {
        $this->redirect_hak_akses('u');
        $kepala = $this->keluarga_model->get_kepala_a($id);
        $this->redirect_tidak_valid(empty($kepala['id']) || $kepala['status_dasar'] == 1);
        $this->keluarga_model->add_anggota($id);

        redirect("{$this->controller}/anggota/{$p}/{$o}/{$id}");
    }

    public function update_anggota($p = 1, $o = 0, $id_kk = 0, $id = 0)
    {
        $this->redirect_hak_akses('u');
        $this->redirect_tidak_valid($this->keluarga_model->get_kepala_a($id_kk)['status_dasar'] == 1);
        $this->keluarga_model->update_anggota($id);

        redirect("{$this->controller}/anggota/{$p}/{$o}/{$id_kk}");
    }

    // Pecah keluarga
    public function delete_anggota($p = 1, $o = 0, $kk = 0, $id = 0)
    {
        $this->redirect_hak_akses('u');
        $this->keluarga_model->rem_anggota($kk, $id);

        redirect("{$this->controller}/anggota/{$p}/{$o}/{$kk}");
    }

    // Keluarkan karena salah mengisi
    public function keluarkan_anggota($kk, $id = 0)
    {
        $this->redirect_hak_akses('u');
        $this->keluarga_model->rem_anggota($no_kk_sebelumnya = 0, $id); // Tidak simpan no KK

        redirect("{$this->controller}/anggota/1/0/{$kk}");
    }

    public function delete_all_anggota($p = 1, $o = 0, $kk = 0)
    {
        $this->redirect_hak_akses('h');
        $this->keluarga_model->rem_all_anggota($kk);

        redirect("{$this->controller}/anggota/{$p}/{$o}/{$kk}");
    }

    public function statistik($tipe = '0', $nomor = 0, $sex = null)
    {
        $this->clear_session();
        // Untuk tautan TOTAL di laporan statistik, di mana arg-2 = sex dan arg-3 kosong
        if ($sex == null) {
            if ($nomor != 0) {
                $this->session->sex = $nomor;
            } else {
                $this->session->unset_userdata('sex');
            }
            $this->session->unset_userdata('judul_statistik');
            redirect($this->controller);
        }

        $this->session->unset_userdata('program_bantuan');
        $this->session->sex = ($sex == 0) ? null : $sex;

        switch (true) {
            case $tipe == 'kelas_sosial':
                $session  = 'kelas';
                $kategori = 'KLASIFIKASI SOSIAL : ';
                break;

            case $tipe == 'bantuan_keluarga':
                if (! in_array($nomor, [BELUM_MENGISI, TOTAL])) {
                    $this->session->status_dasar = null;
                } // tampilkan semua peserta walaupun bukan hidup/aktif
                $session  = 'bantuan_keluarga';
                $kategori = 'PENERIMA BANTUAN (KELUARGA) : ';
                break;

            case $tipe > 50:
                $program_id                     = preg_replace('/^50/', '', $tipe);
                $this->session->program_bantuan = $program_id;
                $nama                           = $this->db->select('nama')
                    ->where('id', $program_id)
                    ->get('program')->row()
                    ->nama;
                if (! in_array($nomor, [BELUM_MENGISI, TOTAL])) {
                    $this->session->status_dasar = null; // tampilkan semua peserta walaupun bukan hidup/aktif
                    $nomor                       = $program_id;
                }
                $kategori = $nama . ' : ';
                $session  = 'bantuan_keluarga';
                $tipe     = 'bantuan_keluarga';
                break;
        }

        // Filter berdasarkan kategori tdk dilakukan jika $nomer = TOTAL (888)
        if ($nomor != TOTAL) {
            $this->session->{$session} = $nomor;
        }

        $judul = $this->keluarga_model->get_judul_statistik($tipe, $nomor, $sex);

        if ($judul['nama']) {
            $this->session->judul_statistik = $kategori . $judul['nama'];
        } else {
            $this->session->unset_userdata('judul_statistik');
        }

        redirect($this->controller);
    }

    public function cetak_statistik($tipe = 0)
    {
        $data['main'] = $this->keluarga_model->list_data_statistik($tipe);

        $this->load->view('sid/kependudukan/keluarga_print', $data);
    }

    public function search_kumpulan_kk()
    {
        $data['kumpulan_kk'] = $this->session->kumpulan_kk ?: '';
        $data['form_action'] = site_url("{$this->controller}/filter/kumpulan_kk");

        $this->load->view('sid/kependudukan/ajax_search_kumpulan_kk', $data);
    }

    public function ajax_cetak($o = 0, $aksi = '')
    {
        $data['o']                   = $o;
        $data['aksi']                = $aksi;
        $data['form_action']         = site_url("{$this->controller}/cetak/{$o}/{$aksi}");
        $data['form_action_privasi'] = site_url("{$this->controller}/cetak/{$o}/{$aksi}/1");

        $this->load->view('sid/kependudukan/ajax_cetak_bersama', $data);
    }

    public function program_bantuan()
    {
        // TODO : Ubah cara ini untuk menampilkan data
        $this->session->sasaran  = 2; // sasaran keluarga
        $this->session->per_page = 100000; // tampilkan semua program bantuan
        $list_bantuan            = $this->program_bantuan_model->get_program(1, false);

        $data = [
            'form_action'     => site_url("{$this->controller}/program_bantuan_proses"),
            'program_bantuan' => $list_bantuan['program'],
            'id_program'      => $this->session->bantuan_keluarga,
        ];

        $this->load->view('sid/kependudukan/pencarian_program_bantuan', $data);
    }

    public function program_bantuan_proses()
    {
        $id_program = $this->input->post('program_bantuan');
        $this->statistik('bantuan_keluarga', $id_program, '0');
    }

    public function nokk_sementara()
    {
        $this->session->nokk_sementara = '0';

        redirect($this->controller);
    }

    public function form_pecah_semua($id = 0)
    {
        $this->redirect_hak_akses('u');
        $data['kk']             = $this->keluarga_model->get_keluarga($id);
        $data['anggota']        = $this->keluarga_model->list_anggota($id, ['dengan_kk' => false]);
        $data['cek_nokk']       = get_nokk($data['kk']['no_kk']);
        $data['nokk_sementara'] = $this->keluarga_model->nokk_sementara();
        $data['form_action']    = site_url("{$this->controller}/pecah_semua/{$id}");

        $this->load->view('sid/kependudukan/pecah_semua', $data);
    }

    public function pecah_semua($id = 0)
    {
        $this->redirect_hak_akses('u');
        $this->keluarga_model->pecah_semua($id, $this->input->post());

        redirect("{$this->controller}/clear");
    }
}
