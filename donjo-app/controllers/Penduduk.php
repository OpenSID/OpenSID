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

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class Penduduk extends Admin_Controller
{
    private $_set_page;
    private $_list_session;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['penduduk_model', 'keluarga_model', 'wilayah_model', 'web_dokumen_model', 'program_bantuan_model', 'lapor_model', 'referensi_model', 'penduduk_log_model', 'impor_model', 'ekspor_model']);

        $this->modul_ini     = 2;
        $this->sub_modul_ini = 21;
        $this->_set_page     = ['50', '100', '200'];
        $this->_list_session = ['filter_tahun', 'filter_bulan', 'status_hanya_tetap', 'jenis_peristiwa', 'filter', 'status_dasar', 'sex', 'agama', 'dusun', 'rw', 'rt', 'cari', 'umur_min', 'umur_max', 'umurx', 'pekerjaan_id', 'status', 'pendidikan_sedang_id', 'pendidikan_kk_id', 'status_penduduk', 'judul_statistik', 'cacat', 'cara_kb_id', 'akta_kelahiran', 'status_ktp', 'id_asuransi', 'status_covid', 'bantuan_penduduk', 'log', 'warganegara', 'menahun', 'hubungan', 'golongan_darah', 'hamil', 'kumpulan_nik', 'suku', 'bpjs_ketenagakerjaan', 'nik_sementara', 'tag_id_card'];
    }

    private function clear_session()
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->status_dasar = 1; // default status dasar = hidup
        $this->session->per_page     = $this->_set_page[0];
    }

    public function clear()
    {
        $this->clear_session();
        redirect($this->controller);
    }

    public function index($p = 1, $o = 0)
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

        $data['func']                 = 'index';
        $data['set_page']             = $this->_set_page;
        $list_data                    = $this->penduduk_model->list_data($o, $p);
        $data['paging']               = $list_data['paging'];
        $data['main']                 = $list_data['main'];
        $data['list_dusun']           = $this->wilayah_model->list_dusun();
        $data['list_status_dasar']    = $this->referensi_model->list_data('tweb_status_dasar');
        $data['list_status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status');
        $data['list_jenis_kelamin']   = $this->referensi_model->list_data('tweb_penduduk_sex');

        $this->render('sid/kependudukan/penduduk', $data);
    }

    public function form_peristiwa($peristiwa = '')
    {
        $this->redirect_hak_akses('u');
        // Acuan jenis peristiwa berada pada ref_peristiwa
        $this->session->jenis_peristiwa = $peristiwa;
        $this->form();
    }

    public function form($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        // Reset kalau dipanggil dari luar pertama kali ($_POST kosong)
        if (empty($_POST) && (! isset($_SESSION['dari_internal']) || ! $_SESSION['dari_internal'])) {
            unset($_SESSION['validation_error']);
        }

        $data['p'] = $p;
        $data['o'] = $o;

        if ($id) {
            $data['id'] = $id;
            // Validasi dilakukan di penduduk_model sewaktu insert dan update
            if (isset($_SESSION['validation_error']) && $_SESSION['validation_error']) {
                // Kalau dipanggil internal pakai data yang disimpan di $_SESSION
                if ($_SESSION['dari_internal']) {
                    $data['penduduk'] = $_SESSION['post'];
                } else {
                    $data['penduduk'] = $_POST;
                }
            } else {
                $data['penduduk']     = $this->penduduk_model->get_penduduk($id);
                $_SESSION['nik_lama'] = $data['penduduk']['nik'];
            }
            $data['form_action'] = site_url("{$this->controller}/update/1/{$o}/{$id}");
        } else {
            // Validasi dilakukan di penduduk_model sewaktu insert dan update
            if (isset($_SESSION['validation_error']) && $_SESSION['validation_error']) {
                // Kalau dipanggil internal pakai data yang disimpan di $_SESSION
                if ($_SESSION['dari_internal']) {
                    $data['penduduk'] = $_SESSION['post'];
                } else {
                    $data['penduduk'] = $_POST;
                }
            } else {
                $data['penduduk'] = null;
            }
            $data['form_action'] = site_url("{$this->controller}/insert");
        }

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
        $data['keluarga']           = $this->keluarga_model->get_kepala_kk($data['penduduk']['id_kk']);
        $data['cek_nik']            = get_nik($data['penduduk']['nik']);

        if ($this->session->status_hanya_tetap) {
            $data['status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status', $this->session->status_hanya_tetap);
        } else {
            $data['status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status');
        }
        $data['jenis_peristiwa'] = $this->session->jenis_peristiwa;

        $this->session->unset_userdata(['dari_internal']);

        $this->render('sid/kependudukan/penduduk_form', $data);
    }

    public function detail($p = 1, $o = 0, $id = 0)
    {
        $data['p']            = $p;
        $data['o']            = $o;
        $data['list_dokumen'] = $this->penduduk_model->list_dokumen($id);
        $data['penduduk']     = $this->penduduk_model->get_penduduk($id);
        $data['program']      = $this->program_bantuan_model->get_peserta_program(1, $data['penduduk']['nik']);
        $this->render('sid/kependudukan/penduduk_detail', $data);
    }

    public function dokumen($id = '')
    {
        $data['penduduk']           = $this->penduduk_model->get_penduduk($id) ?? show_404();
        $data['list_dokumen']       = $this->penduduk_model->list_dokumen($id);
        $data['jenis_syarat_surat'] = $this->referensi_model->list_by_id('ref_syarat_surat', 'ref_syarat_id');

        $this->render('sid/kependudukan/penduduk_dokumen', $data);
    }

    public function dokumen_form($id = 0, $id_dokumen = 0)
    {
        $this->redirect_hak_akses('u');
        $data['penduduk']           = $this->penduduk_model->get_penduduk($id);
        $data['jenis_syarat_surat'] = $this->lapor_model->get_surat_ref_all();

        if ($data['penduduk']['kk_level'] === '1') { //Jika Kepala Keluarga
            $data['kk'] = $this->keluarga_model->list_anggota($data['penduduk']['id_kk']);
        }

        if ($id_dokumen) {
            $data['dokumen'] = $this->web_dokumen_model->get_dokumen($id_dokumen);

            // Ambil data anggota KK
            if ($data['penduduk']['kk_level'] === '1') { //Jika Kepala Keluarga
                $data['dokumen_anggota'] = $this->web_dokumen_model->get_dokumen_di_anggota_lain($id_dokumen);

                if (count($data['dokumen_anggota']) > 0) {
                    $id_pend_anggota = [];

                    foreach ($data['dokumen_anggota'] as $item_dokumen) {
                        $id_pend_anggota[] = $item_dokumen['id_pend'];
                    }

                    foreach ($data['kk'] as $key => $value) {
                        if (in_array($value['id'], $id_pend_anggota)) {
                            $data['kk'][$key]['checked'] = 'checked';
                        }
                    }
                }
            }

            $data['form_action'] = site_url("{$this->controller}/dokumen_update/{$id_dokumen}");
        } else {
            $data['dokumen']     = null;
            $data['form_action'] = site_url("{$this->controller}/dokumen_insert");
        }

        $this->load->view('sid/kependudukan/dokumen_form', $data);
    }

    public function dokumen_list($id = 0)
    {
        $data['list_dokumen'] = $this->penduduk_model->list_dokumen($id);
        $data['penduduk']     = $this->penduduk_model->get_penduduk($id);

        $this->load->view('sid/kependudukan/dokumen_ajax', $data);
    }

    public function dokumen_insert()
    {
        $this->redirect_hak_akses('u');
        $this->web_dokumen_model->insert();
        $id = $_POST['id_pend'];

        redirect("{$this->controller}/dokumen/{$id}");
    }

    public function dokumen_update($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->web_dokumen_model->update($id);
        $id = $_POST['id_pend'];

        redirect("{$this->controller}/dokumen/{$id}");
    }

    public function delete_dokumen($id_pend = 0, $id = '')
    {
        $this->redirect_hak_akses('h', "penduduk/dokumen/{$id_pend}");
        $this->web_dokumen_model->delete($id);

        redirect("{$this->controller}/dokumen/{$id_pend}");
    }

    public function delete_all_dokumen($id_pend = 0)
    {
        $this->redirect_hak_akses('h', "penduduk/dokumen/{$id_pend}");
        $this->web_dokumen_model->delete_all();

        redirect("{$this->controller}/dokumen/{$id_pend}");
    }

    public function cetak_biodata($id = '')
    {
        $data['desa']     = $this->header['desa'];
        $data['penduduk'] = $this->penduduk_model->get_penduduk($id);
        $this->load->view('sid/kependudukan/cetak_biodata', $data);
    }

    public function filter($filter)
    {
        if ($filter == 'dusun') {
            $this->session->unset_userdata(['rw', 'rt']);
        }
        if ($filter == 'rw') {
            $this->session->unset_userdata('rt');
        }

        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect($this->controller);
    }

    public function nik_sementara()
    {
        $this->session->nik_sementara = '0';

        redirect($this->controller);
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');
        $id = $this->penduduk_model->insert();
        if ($_SESSION['success'] == -1) {
            $_SESSION['dari_internal'] = true;
            redirect("{$this->controller}/form");
        } else {
            redirect("{$this->controller}/detail/1/0/{$id}");
        }
    }

    public function update($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->penduduk_model->update($id);
        if ($_SESSION['success'] == -1) {
            $_SESSION['dari_internal'] = true;
            redirect("{$this->controller}/form/{$p}/{$o}/{$id}");
        } else {
            redirect("{$this->controller}/detail/1/0/{$id}");
        }
    }

    public function delete($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('h');
        $this->penduduk_model->delete($id);

        redirect("{$this->controller}/index/{$p}/{$o}");
    }

    public function delete_all($p = 1, $o = 0)
    {
        $this->redirect_hak_akses('h');
        $this->penduduk_model->delete_all();

        redirect("{$this->controller}/index/{$p}/{$o}");
    }

    public function ajax_adv_search()
    {
        $list_session = ['umur_min', 'umur_max', 'pekerjaan_id', 'status', 'agama', 'pendidikan_sedang_id', 'pendidikan_kk_id', 'status_penduduk', 'sex', 'status_dasar', 'cacat', 'cara_kb_id', 'status_ktp', 'id_asuransi', 'warganegara', 'golongan_darah', 'hamil', 'menahun', 'tag_id_card'];

        foreach ($list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        $data['input_umur']           = true;
        $data['list_agama']           = $this->referensi_model->list_data('tweb_penduduk_agama');
        $data['list_pendidikan']      = $this->referensi_model->list_data('tweb_penduduk_pendidikan');
        $data['list_pendidikan_kk']   = $this->referensi_model->list_data('tweb_penduduk_pendidikan_kk');
        $data['list_pekerjaan']       = $this->referensi_model->list_data('tweb_penduduk_pekerjaan');
        $data['list_status_kawin']    = $this->referensi_model->list_data('tweb_penduduk_kawin');
        $data['list_status_penduduk'] = $this->referensi_model->list_data('tweb_penduduk_status');
        $data['list_sex']             = $this->referensi_model->list_data('tweb_penduduk_sex');
        $data['list_status_dasar']    = $this->referensi_model->list_data('tweb_status_dasar');
        $data['list_cacat']           = $this->referensi_model->list_data('tweb_cacat');
        $data['list_cara_kb']         = $this->referensi_model->list_data('tweb_cara_kb');
        $data['list_status_ktp']      = $this->referensi_model->list_data('tweb_status_ktp');
        $data['list_asuransi']        = $this->referensi_model->list_data('tweb_penduduk_asuransi');
        $data['list_warganegara']     = $this->referensi_model->list_data('tweb_penduduk_warganegara');
        $data['list_golongan_darah']  = $this->referensi_model->list_data('tweb_golongan_darah');
        $data['list_sakit_menahun']   = $this->referensi_model->list_data('tweb_sakit_menahun');
        $data['list_tag_id_card']     = $this->referensi_model->list_ref(STATUS);
        $data['form_action']          = site_url("{$this->controller}/adv_search_proses");

        $this->load->view('sid/kependudukan/ajax_adv_search_form', $data);
    }

    public function adv_search_proses()
    {
        $this->clear_session();
        $adv_search = $this->validasi_pencarian($this->input->post());

        $i = 0;

        while ($i++ < count($adv_search)) {
            $col[$i] = key($adv_search);
            next($adv_search);
        }
        $i = 0;

        while ($i++ < count($col)) {
            if ($adv_search[$col[$i]] == '') {
                unset($adv_search[$col[$i]], $_SESSION[$col[$i]]);
            } else {
                $_SESSION[$col[$i]] = $adv_search[$col[$i]];
            }
        }

        redirect($this->controller);
    }

    private function validasi_pencarian($post)
    {
        $data['umur_min']             = bilangan($post['umur_min']);
        $data['umur_max']             = bilangan($post['umur_max']);
        $data['pekerjaan_id']         = $post['pekerjaan_id'];
        $data['status']               = $post['status'];
        $data['agama']                = $post['agama'];
        $data['pendidikan_sedang_id'] = $post['pendidikan_sedang_id'];
        $data['pendidikan_kk_id']     = $post['pendidikan_kk_id'];
        $data['status_penduduk']      = $post['status_penduduk'];
        $data['filter']               = $post['status_penduduk'];
        $data['sex']                  = $post['sex'];
        $data['status_dasar']         = $post['status_dasar'];
        $data['cara_kb_id']           = $post['cara_kb_id'];
        $data['status_ktp']           = $post['status_ktp'];
        $data['id_asuransi']          = $post['id_asuransi'];
        $data['warganegara']          = $post['warganegara'];
        $data['golongan_darah']       = $post['golongan_darah'];
        $data['menahun']              = $post['menahun'];
        $data['cacat']                = $post['cacat'];
        $data['tag_id_card']          = $post['tag_id_card'];

        return $data;
    }

    public function ajax_penduduk_pindah_rw($dusun = '')
    {
        $dusun = urldecode($dusun);
        $rw    = $this->wilayah_model->list_rw($dusun);
        echo "<div class='form-group'><label>RW</label>
		<select class='form-control input-sm' name='rw' onchange=RWSel('" . rawurlencode($dusun) . "',this.value)>
		<option value=''>Pilih RW</option>";

        foreach ($rw as $data) {
            echo '<option>' . $data['rw'] . '</option>';
        }
        echo '</select></div>';
    }

    public function ajax_penduduk_pindah_rt($dusun = '', $rw = '')
    {
        $dusun = urldecode($dusun);
        $rt    = $this->wilayah_model->list_rt($dusun, $rw);
        echo "<div class='form-group'><label>RT</label>
		<select class='form-control input-sm' name='id_cluster'>
		<option value=''>Pilih RT</option>";

        foreach ($rt as $data) {
            echo '<option value=' . $data['id'] . '>' . $data['rt'] . '</option>';
        }
        echo '</select></div>';
    }

    public function ajax_penduduk_cari_rw($dusun = '')
    {
        $rw = $this->wilayah_model->list_rw($dusun);

        echo "<td>RW</td>
		<td><select name='rw' onchange=RWSel('" . $dusun . "',this.value)>
		<option value=''>Pilih RW&nbsp;</option>";

        foreach ($rw as $data) {
            echo '<option>' . $data['rw'] . '</option>';
        }
        echo '</select>
		</td>';
    }

    public function ajax_penduduk_maps($p = 1, $o = 0, $id = null, $edit = 1)
    {
        $this->redirect_hak_akses('u');

        $data['p']    = $p;
        $data['o']    = $o;
        $data['id']   = $id;
        $data['edit'] = $edit;

        $data['penduduk']    = $this->penduduk_model->get_penduduk_map($id);
        $data['desa']        = $this->header['desa'];
        $data['wil_atas']    = $this->header['desa'];
        $data['dusun_gis']   = $this->wilayah_model->list_dusun();
        $data['rw_gis']      = $this->wilayah_model->list_rw();
        $data['rt_gis']      = $this->wilayah_model->list_rt();
        $data['form_action'] = site_url("{$this->controller}/update_maps/{$p}/{$o}/{$id}/{$data['edit']}");

        $this->render('sid/kependudukan/ajax_penduduk_maps', $data);
    }

    public function update_maps($p = 1, $o = 0, $id = '', $edit = '')
    {
        $this->redirect_hak_akses('u');

        $this->penduduk_model->update_position($id);
        if ($edit == 1) {
            redirect("{$this->controller}/form/{$p}/{$o}/{$id}");
        } else {
            redirect($this->controller);
        }
    }

    public function edit_status_dasar($p = 1, $o = 0, $id = 0)
    {
        $this->redirect_hak_akses('u');
        $data['nik']             = $this->penduduk_model->get_penduduk($id);
        $data['form_action']     = site_url("{$this->controller}/update_status_dasar/{$p}/{$o}/{$id}");
        $data['list_ref_pindah'] = $this->referensi_model->list_data('ref_pindah');
        $data['sebab']           = $this->referensi_model->list_ref(SEBAB);
        $data['penolong_mati']   = $this->referensi_model->list_ref(PENOLONG_MATI);

        //Pengecualian status dasar: Penduduk Tetap => ('TIDAK VALID', 'HIDUP', 'PERGI') , Penduduk Tidak Tetap => ('TIDAK VALID', 'HIDUP')
        $excluded_status           = $data['nik']['id_status'] == 1 ? '9, 1, 6' : '9, 1';
        $data['list_status_dasar'] = $this->referensi_model->list_data('tweb_status_dasar', $excluded_status);

        $this->load->view('sid/kependudukan/ajax_edit_status_dasar', $data);
    }

    public function update_status_dasar($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->penduduk_model->update_status_dasar($id);

        redirect("{$this->controller}/index/{$p}/{$o}");
    }

    public function kembalikan_status($p = 1, $o = 0, $id = '')
    {
        $this->redirect_hak_akses('u');
        $this->penduduk_model->kembalikan_status($id);

        redirect("{$this->controller}/index/{$p}/{$o}");
    }

    public function cetak($o = 0, $aksi = '', $privasi_nik = 0)
    {
        $data['main'] = $this->penduduk_model->list_data($o, 0);

        if ($privasi_nik == 1) {
            $data['privasi_nik'] = true;
        }

        $this->load->view("sid/kependudukan/penduduk_{$aksi}", $data);
    }

    public function statistik($tipe = '0', $nomor = 0, $sex = null)
    {
        $this->clear_session();
        // Untuk tautan TOTAL di laporan statistik, di mana arg-2 = sex dan arg-3 kosong
        // kecuali untuk laporan wajib KTP
        if ($sex == null && $tipe != 18) {
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

        switch ($tipe) {
            case '0':
                $session  = 'pendidikan_kk_id';
                $kategori = 'PENDIDIKAN DALAM KK : ';
                break;

            case 1:
                $session  = 'pekerjaan_id';
                $kategori = 'PEKERJAAN : ';
                break;

            case 2:
                $session  = 'status';
                $kategori = 'STATUS PERKAWINAN : ';
                break;

            case 3:
                $session  = 'agama';
                $kategori = 'AGAMA : ';
                break;

            case 4:
                $session  = 'sex';
                $kategori = 'JENIS KELAMIN : ';
                break;

            case 5:
                $session  = 'warganegara';
                $kategori = 'WARGANEGARA : ';
                break;

            case 6:
                $session  = 'status_penduduk';
                $kategori = 'STATUS PENDUDUK : ';
                break;

            case 7:
                $session  = 'golongan_darah';
                $kategori = 'GOLONGAN DARAH : ';
                break;

            case 9:
                $session  = 'cacat';
                $kategori = 'CACAT : ';
                break;

            case 10:
                $session  = 'menahun';
                $kategori = 'SAKIT MENAHUN : ';
                break;

            case 13:
                $session  = 'umurx';
                $kategori = 'UMUR (RENTANG) : ';
                break;

            case 14:
                $session  = 'pendidikan_sedang_id';
                $kategori = 'PENDIDIKAN SEDANG DITEMPUH : ';
                break;

            case 15:
                $session  = 'umurx';
                $kategori = 'UMUR (KATEGORI) : ';
                break;

            case 16:
                $session  = 'cara_kb_id';
                $kategori = 'CARA KB : ';
                break;

            case 17:
                $session  = 'akta_kelahiran';
                $kategori = 'AKTA KELAHIRAN : UMUR ';
                break;

            case 19:
                $session  = 'id_asuransi';
                $kategori = 'ASURANSI KESEHATAN : ';
                break;

            case 'bpjs-tenagakerja':
                $session                             = ($nomor == BELUM_MENGISI || $nomor == JUMLAH) ? 'bpjs_ketenagakerjaan' : 'pekerjaan_id';
                $kategori                            = 'BPJS Ketenagakerjaan : ';
                $this->session->bpjs_ketenagakerjaan = ($nomor == TOTAL) ? false : true;
                break;

            case 'hubungan_kk':
                $session  = 'hubungan';
                $kategori = 'HUBUNGAN DALAM KK : ';
                break;

            case 'covid':
                $session  = 'status_covid';
                $kategori = 'STATUS COVID : ';
                break;

            case 'bantuan_penduduk':
                if (! in_array($nomor, [BELUM_MENGISI, TOTAL])) {
                    $this->session->status_dasar = null;
                } // tampilkan semua peserta walaupun bukan hidup/aktif
                $session  = 'bantuan_penduduk';
                $kategori = 'PENERIMA BANTUAN PENDUDUK : ';
                break;

            case 18:
                if ($sex == null) {
                    $this->session->status_ktp = 0;
                    $this->session->sex        = ($nomor == 0) ? null : $nomor;
                    $sex                       = $this->session->sex;
                    unset($nomor);
                } else {
                    $this->session->status_ktp = $nomor;
                }

                $kategori = 'KEPEMILIKAN WAJIB KTP : ';
                break;

            case 'suku':
                $session  = 'suku';
                $kategori = 'Suku: ';
                break;

            case 'hamil':
                $session  = 'hamil';
                $kategori = 'STATUS KEHAMILAN : ';
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
                $session  = 'bantuan_penduduk';
                $tipe     = 'bantuan_penduduk';
                break;
        }

        // Filter berdasarkan kategori tdk dilakukan jika $nomer = TOTAL (888)
        if ($tipe != 18 && $nomor != TOTAL) {
            $this->session->{$session} = rawurldecode($nomor);
        }

        $judul = $this->penduduk_model->get_judul_statistik($tipe, $nomor, $sex);
        // Laporan wajib KTP berbeda - menampilkan sebagian dari penduduk, jadi selalu perlu judul
        if ($judul['nama'] || $tipe = 18) {
            $this->session->judul_statistik = $kategori . $judul['nama'];
        } else {
            $this->session->unset_userdata('judul_statistik');
        }

        redirect($this->controller);
    }

    public function lap_statistik($id_cluster = 0, $tipe = 0, $nomor = 0)
    {
        $this->clear_session();
        $cluster = $this->penduduk_model->get_cluster($id_cluster);

        switch ($tipe) {
            case 1:
                $_SESSION['sex']   = '1';
                $_SESSION['dusun'] = $cluster['dusun'];
                $_SESSION['rw']    = $cluster['rw'];
                $_SESSION['rt']    = $cluster['rt'];
                $pre               = 'JENIS KELAMIN LAKI-LAKI  ';
                break;

            case 2:
                $_SESSION['sex']   = '2';
                $_SESSION['dusun'] = $cluster['dusun'];
                $_SESSION['rw']    = $cluster['rw'];
                $_SESSION['rt']    = $cluster['rt'];
                $pre               = 'JENIS KELAMIN PEREMPUAN ';
                break;

            case 3:
                $_SESSION['umur_min'] = '0';
                $_SESSION['umur_max'] = '0';
                $_SESSION['dusun']    = $cluster['dusun'];
                $_SESSION['rw']       = $cluster['rw'];
                $_SESSION['rt']       = $cluster['rt'];
                $pre                  = 'BERUMUR <1 ';
                break;

            case 4:
                $_SESSION['umur_min'] = '1';
                $_SESSION['umur_max'] = '5';
                $_SESSION['dusun']    = $cluster['dusun'];
                $_SESSION['rw']       = $cluster['rw'];
                $_SESSION['rt']       = $cluster['rt'];
                $pre                  = 'BERUMUR 1-5 ';
                break;

            case 5:
                $_SESSION['umur_min'] = '6';
                $_SESSION['umur_max'] = '12';
                $_SESSION['dusun']    = $cluster['dusun'];
                $_SESSION['rw']       = $cluster['rw'];
                $_SESSION['rt']       = $cluster['rt'];
                $pre                  = 'BERUMUR 6-12 ';
                break;

            case 6:
                $_SESSION['umur_min'] = '13';
                $_SESSION['umur_max'] = '15';
                $_SESSION['dusun']    = $cluster['dusun'];
                $_SESSION['rw']       = $cluster['rw'];
                $_SESSION['rt']       = $cluster['rt'];
                $pre                  = 'BERUMUR 13-16 ';
                break;

            case 7:
                $_SESSION['umur_min'] = '16';
                $_SESSION['umur_max'] = '18';
                $_SESSION['dusun']    = $cluster['dusun'];
                $_SESSION['rw']       = $cluster['rw'];
                $_SESSION['rt']       = $cluster['rt'];
                $pre                  = 'BERUMUR 16-18 ';
                break;

            case 8:
                $_SESSION['umur_min'] = '61';
                $_SESSION['dusun']    = $cluster['dusun'];
                $_SESSION['rw']       = $cluster['rw'];
                $_SESSION['rt']       = $cluster['rt'];
                $pre                  = 'BERUMUR >60';
                break;

            case 91:
            case 92:
            case 93:
            case 94:
            case 95:
            case 96:
            case 97:
                $kode_cacat        = $tipe - 90;
                $_SESSION['cacat'] = $kode_cacat;
                $_SESSION['dusun'] = $cluster['dusun'];
                $_SESSION['rw']    = $cluster['rw'];
                $_SESSION['rt']    = $cluster['rt'];
                $stat              = $this->penduduk_model->get_judul_statistik(9, $kode_cacat, null);
                $pre               = $stat['nama'];
                break;

            case 10:
                $_SESSION['menahun'] = '90';
                $_SESSION['sex']     = '1';
                $_SESSION['dusun']   = $cluster['dusun'];
                $_SESSION['rw']      = $cluster['rw'];
                $_SESSION['rt']      = $cluster['rt'];
                $pre                 = 'SAKIT MENAHUN LAKI-LAKI ';
                break;

            case 11:
                $_SESSION['menahun'] = '90';
                $_SESSION['sex']     = '2';
                $_SESSION['dusun']   = $cluster['dusun'];
                $_SESSION['rw']      = $cluster['rw'];
                $_SESSION['rt']      = $cluster['rt'];
                $pre                 = 'SAKIT MENAHUN PEREMPUAN ';
                break;

            case 12:
                $_SESSION['hamil'] = '1';
                $_SESSION['dusun'] = $cluster['dusun'];
                $_SESSION['rw']    = $cluster['rw'];
                $_SESSION['rt']    = $cluster['rt'];
                $pre               = 'HAMIL ';
                break;
        }

        if ($pre) {
            $_SESSION['judul_statistik'] = $pre;
        } else {
            unset($_SESSION['judul_statistik']);
        }

        redirect($this->controller);
    }

    public function autocomplete()
    {
        $data = $this->penduduk_model->autocomplete($this->input->post('cari'));
        echo json_encode($data);
    }

    public function search_kumpulan_nik()
    {
        $data['kumpulan_nik'] = $this->session->kumpulan_nik;
        $data['form_action']  = site_url("{$this->controller}/filter/kumpulan_nik");

        $this->load->view('sid/kependudukan/ajax_search_kumpulan_nik', $data);
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
        $this->session->sasaran  = 1; // sasaran penduduk
        $this->session->per_page = 100000; // tampilkan semua program bantuan
        $list_bantuan            = $this->program_bantuan_model->get_program(1, false);

        $data = [
            'form_action'     => site_url("{$this->controller}/program_bantuan_proses"),
            'program_bantuan' => $list_bantuan['program'],
            'id_program'      => $this->session->bantuan_penduduk,
        ];

        $this->load->view('sid/kependudukan/pencarian_program_bantuan', $data);
    }

    public function program_bantuan_proses()
    {
        $id_program = $this->input->post('program_bantuan');
        $this->statistik('bantuan_penduduk', $id_program, '0');
    }

    /**
     * Unduh berkas berdasarkan kolom dokumen.id
     *
     * @param int   $id_dokumen Id berkas pada koloam dokumen.id
     * @param mixed $tampil
     *
     * @return void
     */
    public function unduh_berkas($id_dokumen = 0, $tampil = false)
    {
        // Ambil nama berkas dari database
        $data = $this->web_dokumen_model->get_dokumen($id_dokumen);
        ambilBerkas($data['satuan'], $this->controller, null, LOKASI_DOKUMEN, $tampil);
    }

    public function impor()
    {
        if (config_item('demo_mode')) {
            redirect($this->controller);
        }

        $this->redirect_hak_akses('u');

        $data = [
            'form_action'          => route('penduduk.proses_impor'),
            'boleh_hapus_penduduk' => $this->impor_model->boleh_hapus_penduduk(),
        ];

        return view('admin.penduduk.impor', $data);
    }

    public function proses_impor()
    {
        if (config_item('demo_mode')) {
            redirect($this->controller);
        }

        $this->redirect_hak_akses('u');
        $hapus = isset($_POST['hapus_data']);
        $this->impor_model->impor_excel($hapus);
        redirect('penduduk/impor');
    }

    public function impor_bip()
    {
        if (config_item('demo_mode')) {
            redirect($this->controller);
        }

        $this->redirect_hak_akses('u');

        $data = [
            'form_action'          => route('penduduk.proses_impor_bip'),
            'boleh_hapus_penduduk' => $this->impor_model->boleh_hapus_penduduk(),
        ];

        return view('admin.penduduk.impor_bip', $data);
    }

    public function proses_impor_bip()
    {
        if (config_item('demo_mode')) {
            redirect($this->controller);
        }

        $this->redirect_hak_akses('u');

        if ($this->db->get('tweb_penduduk')->num_rows() > 0) {
            redirect_with('error', 'Tidak dapat mengimpor BIP ketika data penduduk telah ada', 'penduduk/impor_bip');
        }

        $this->impor_model->impor_bip($this->input->post('hapus_data'));
        redirect('penduduk/impor_bip');
    }

    public function ekspor()
    {
        try {
            $daftar_kolom = $this->impor_model->daftar_kolom;

            $writer = WriterEntityFactory::createXLSXWriter();
            $writer->openToBrowser(namafile('penduduk') . '.xlsx');
            $writer->addRow(WriterEntityFactory::createRowFromArray($daftar_kolom));

            //Isi Tabel
            $get = $this->ekspor_model->expor();

            foreach ($get as $row) {
                $penduduk = [];

                foreach ($daftar_kolom as $kolom) {
                    $penduduk[] = $row->{$kolom};
                }

                $writer->addRow(WriterEntityFactory::createRowFromArray($penduduk));
            }
            $writer->close();
        } catch (\Exception $e) {
            log_message('error', $e);

            $this->session->set_flashdata('notif', 'Tidak berhasil mengekspor data penduduk, harap mencoba kembali.');

            redirect('penduduk');
        }
    }

    public function foto_bawaan($id)
    {
        $penduduk = $this->db->get_where('tweb_penduduk', ['id' => $id])->row();

        if (empty($penduduk)) {
            return redirect('penduduk');
        }

        $this->db->where('id', $penduduk->id)->set('foto', null)->update('tweb_penduduk');

        // Hapus file foto penduduk yg di hapus di folder desa/upload/user_pict
        $file_foto = LOKASI_USER_PICT . $penduduk->foto;
        if (is_file($file_foto)) {
            unlink($file_foto);
        }

        // Hapus file foto kecil penduduk yg di hapus di folder desa/upload/user_pict
        $file_foto_kecil = LOKASI_USER_PICT . 'kecil_' . $penduduk->foto;
        if (is_file($file_foto_kecil)) {
            unlink($file_foto_kecil);
        }

        redirect("penduduk/form/1/0/{$penduduk->id}");
    }
}
