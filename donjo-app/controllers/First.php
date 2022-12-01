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

class First extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        parent::clear_cluster_session();

        // Load library statistik pengunjung
        $this->load->library('statistik_pengunjung');

        $this->load->model('first_artikel_m');
        $this->load->model('first_penduduk_m');
        $this->load->model('penduduk_model');
        $this->load->model('surat_model');
        $this->load->model('keluarga_model');
        $this->load->model('laporan_penduduk_model');
        $this->load->model('track_model');
        $this->load->model('keluar_model');
        $this->load->model('keuangan_model');
        $this->load->model('keuangan_manual_model');
        $this->load->model('web_dokumen_model');
        $this->load->model('program_bantuan_model');
        $this->load->model('keuangan_manual_model');
        $this->load->model('keuangan_grafik_model');
        $this->load->model('keuangan_grafik_manual_model');
        $this->load->model('plan_lokasi_model');
        $this->load->model('plan_area_model');
        $this->load->model('plan_garis_model');
        $this->load->model('analisis_import_model');
    }

    public function index($p = 1)
    {
        $data = $this->includes;

        $data['p']            = $p;
        $data['paging']       = $this->first_artikel_m->paging($p);
        $data['paging_page']  = 'index';
        $data['paging_range'] = 3;
        $data['start_paging'] = max($data['paging']->start_link, $p - $data['paging_range']);
        $data['end_paging']   = min($data['paging']->end_link, $p + $data['paging_range']);
        $data['pages']        = range($data['start_paging'], $data['end_paging']);
        $data['artikel']      = $this->first_artikel_m->artikel_show($data['paging']->offset, $data['paging']->per_page);

        $data['headline'] = $this->first_artikel_m->get_headline();
        $data['cari']     = $this->input->get('cari', true);
        if ($this->setting->covid_rss) {
            $data['feed'] = [
                'items' => $this->first_artikel_m->get_feed(),
                'title' => 'BERITA COVID19.GO.ID',
                'url'   => 'https://www.covid19.go.id',
            ];
        }

        if ($this->setting->apbdes_footer) {
            $data['transparansi'] = $this->setting->apbdes_manual_input
                ? $this->keuangan_grafik_manual_model->grafik_keuangan_tema()
                : $this->keuangan_grafik_model->grafik_keuangan_tema();
        }

        $data['covid'] = $this->laporan_penduduk_model->list_data('covid');

        $cari = trim($this->input->get('cari', true));
        if (! empty($cari)) {
            // Judul artikel bisa digunakan untuk serangan XSS
            $data['judul_kategori'] = htmlentities('Hasil pencarian : ' . substr($cari, 0, 50));
        }

        $this->_get_common_data($data);
        $this->track_model->track_desa('first');
        $this->load->view($this->template, $data);
    }

    /*
    | Artikel bisa ditampilkan menggunakan parameter pertama sebagai id, dan semua parameter lainnya dikosongkan. url artikel/:id
    | Kalau menggunakan slug, dipanggil menggunakan url artikel/:thn/:bln/:hri/:slug
    */
    public function artikel($url)
    {
        if (is_numeric($url)) {
            $data_artikel = $this->first_artikel_m->get_artikel_by_id($url);
            if ($data_artikel) {
                $data_artikel['slug'] = $this->security->xss_clean($data_artikel['slug']);
                redirect('artikel/' . buat_slug($data_artikel));
            }
        }
        $this->load->model('shortcode_model');
        $data = $this->includes;
        $this->first_artikel_m->hit($url); // catat artikel diakses
        $data['single_artikel'] = $this->first_artikel_m->get_artikel($url);
        $id                     = $data['single_artikel']['id'];

        // replace isi artikel dengan shortcodify
        $data['single_artikel']['isi'] = $this->shortcode_model->shortcode($data['single_artikel']['isi']);
        $data['title']                 = ucwords($data['single_artikel']['judul']);
        $data['detail_agenda']         = $this->first_artikel_m->get_agenda($id); //Agenda
        $data['komentar']              = $this->first_artikel_m->list_komentar($id);
        $this->_get_common_data($data);
        $this->set_template('layouts/artikel.tpl.php');
        $this->load->view($this->template, $data);
    }

    public function unduh_dokumen_artikel($id)
    {
        // Ambil nama berkas dari database
        $dokumen = $this->first_artikel_m->get_dokumen_artikel($id);
        ambilBerkas($dokumen, $this->controller, null, LOKASI_DOKUMEN);
    }

    public function arsip($p = 1)
    {
        $data           = $this->includes;
        $data['p']      = $p;
        $data['paging'] = $this->first_artikel_m->paging_arsip($p);
        $data['farsip'] = $this->first_artikel_m->full_arsip($data['paging']->offset, $data['paging']->per_page);

        $this->_get_common_data($data);

        $this->set_template('layouts/arsip.tpl.php');
        $this->load->view($this->template, $data);
    }

    public function gallery($p = 1)
    {
        if ($p > 1) {
            $index = '/index/' . $p;
        }

        redirect('galeri' . $index);
    }

    public function sub_gallery($parent = 0, $p = 1)
    {
        if ($parent) {
            $index = '/' . $parent;

            if ($p > 1) {
                $index .= '/index/' . $p;
            }
        }

        redirect('galeri' . $index);
    }

    public function statistik($stat = 0, $tipe = 0)
    {
        if (! $this->web_menu_model->menu_aktif('statistik/' . $stat)) {
            show_404();
        }

        $data = $this->includes;

        $data['heading'] = $this->laporan_penduduk_model->judul_statistik($stat);
        $data['title']   = 'Statistik ' . $data['heading'];
        $data['stat']    = $this->laporan_penduduk_model->list_data($stat);
        $data['tipe']    = $tipe;
        $data['st']      = $stat;

        $this->_get_common_data($data);

        $this->set_template('layouts/stat.tpl.php');
        $this->load->view($this->template, $data);
    }

    public function kelompok($slug = '')
    {
        redirect('data-kelompok/' . $slug);
    }

    public function suplemen($slug = '')
    {
        redirect('data-suplemen/' . $slug);
    }

    public function ajax_peserta_program_bantuan()
    {
        $peserta = $this->program_bantuan_model->get_peserta_bantuan();
        $data    = [];
        $no      = $_POST['start'];

        foreach ($peserta as $baris) {
            $no++;
            $row    = [];
            $row[]  = $no;
            $row[]  = $baris['program'];
            $row[]  = $baris['peserta'];
            $row[]  = $baris['alamat'];
            $data[] = $row;
        }

        $output = [
            'recordsTotal'    => $this->program_bantuan_model->count_peserta_bantuan_all(),
            'recordsFiltered' => $this->program_bantuan_model->count_peserta_bantuan_filtered(),
            'data'            => $data,
        ];
        echo json_encode($output);
    }

    public function data_analisis()
    {
        if (! $this->web_menu_model->menu_aktif('data_analisis')) {
            show_404();
        }

        $master = $this->input->get('master', true);

        $data                     = $this->includes;
        $data['master_indikator'] = $this->first_penduduk_m->master_indikator();
        $data['list_indikator']   = $this->first_penduduk_m->list_indikator($master);

        $this->_get_common_data($data);

        $this->set_template('layouts/analisis.tpl.php');
        $this->load->view($this->template, $data);
    }

    public function jawaban_analisis($stat = '', $sb = 0, $per = 0)
    {
        if (! $this->web_menu_model->menu_aktif('data_analisis')) {
            show_404();
        }

        $data               = $this->includes;
        $data['list_jawab'] = $this->first_penduduk_m->list_jawab($stat, $sb, $per);
        $data['indikator']  = $this->first_penduduk_m->get_indikator($stat);
        $this->_get_common_data($data);
        $this->set_template('layouts/analisis.tpl.php');
        $this->load->view($this->template, $data);
    }

    public function dpt()
    {
        if (! $this->web_menu_model->menu_aktif('dpt')) {
            show_404();
        }

        $this->load->model('dpt_model');
        $data                      = $this->includes;
        $data['title']             = 'Daftar Calon Pemilih Berdasarkan Wilayah';
        $data['main']              = $this->dpt_model->statistik_wilayah();
        $data['total']             = $this->dpt_model->statistik_total();
        $data['tanggal_pemilihan'] = $this->dpt_model->tanggal_pemilihan();
        $this->_get_common_data($data);
        $data['tipe'] = 4;
        $this->set_template('layouts/stat.tpl.php');
        $this->load->view($this->template, $data);
    }

    public function wilayah()
    {
        if (! $this->web_menu_model->menu_aktif('data-wilayah')) {
            show_404();
        }

        $this->load->model('wilayah_model');
        $data = $this->includes;

        $data['heading']      = 'Populasi Per Wilayah';
        $data['tipe']         = 3;
        $data['daftar_dusun'] = $this->wilayah_model->daftar_wilayah_dusun();
        $data['total']        = $this->wilayah_model->total();
        $data['st']           = 1;
        $this->_get_common_data($data);

        $this->set_template('layouts/stat.tpl.php');
        $this->load->view($this->template, $data);
    }

    public function peraturan_desa()
    {
        if (! $this->web_menu_model->menu_aktif('peraturan_desa')) {
            show_404();
        }

        $data = $this->includes;

        $data['kategori']       = $this->referensi_model->list_data('ref_dokumen', 1);
        $data['tahun']          = $this->web_dokumen_model->tahun_dokumen();
        $data['heading']        = 'Produk Hukum';
        $data['title']          = $data['heading'];
        $data['halaman_statis'] = 'web/halaman_statis/peraturan_desa';
        $this->_get_common_data($data);

        $this->set_template('layouts/halaman_statis.tpl.php');
        $this->load->view($this->template, $data);
    }

    public function ajax_table_peraturan()
    {
        $kategori_dokumen = '';
        $tahun_dokumen    = '';
        $tentang_dokumen  = '';
        $data             = $this->web_dokumen_model->all_peraturan($kategori_dokumen, $tahun_dokumen, $tentang_dokumen);
        echo json_encode($data);
    }

    // function filter peraturan
    public function filter_peraturan()
    {
        $kategori_dokumen = $this->input->post('kategori');
        $tahun_dokumen    = $this->input->post('tahun');
        $tentang_dokumen  = $this->input->post('tentang');

        $data = $this->web_dokumen_model->all_peraturan($kategori_dokumen, $tahun_dokumen, $tentang_dokumen);
        echo json_encode($data);
    }

    public function informasi_publik()
    {
        if (! $this->web_menu_model->menu_aktif('informasi_publik')) {
            show_404();
        }

        $data = $this->includes;

        $data['kategori']       = $this->referensi_model->list_data('ref_dokumen', 1);
        $data['tahun']          = $this->web_dokumen_model->tahun_dokumen();
        $data['heading']        = 'Informasi Publik';
        $data['title']          = $data['heading'];
        $data['halaman_statis'] = 'web/halaman_statis/informasi_publik';
        $this->_get_common_data($data);

        $this->set_template('layouts/halaman_statis.tpl.php');
        $this->load->view($this->template, $data);
    }

    public function ajax_informasi_publik()
    {
        $informasi_publik = $this->web_dokumen_model->get_informasi_publik();
        $data             = [];
        $no               = $_POST['start'];

        foreach ($informasi_publik as $baris) {
            $no++;
            $row   = [];
            $row[] = $no;
            $row[] = "<a href='" . site_url('dokumen_web/unduh_berkas/') . $baris['id'] . "' target='_blank'>" . $baris['nama'] . '</a>';
            $row[] = $baris['tahun'];
            // Ambil judul kategori
            $row[]  = $this->referensi_model->list_ref_flip(KATEGORI_PUBLIK)[$baris['kategori_info_publik']];
            $row[]  = $baris['tgl_upload'];
            $data[] = $row;
        }

        $output = [
            'recordsTotal'    => $this->web_dokumen_model->count_informasi_publik_all(),
            'recordsFiltered' => $this->web_dokumen_model->count_informasi_publik_filtered(),
            'data'            => $data,
        ];
        echo json_encode($output);
    }

    public function kategori($id, $p = 1)
    {
        $data = $this->includes;

        $data['p']              = $p;
        $data['judul_kategori'] = $this->first_artikel_m->get_kategori($id);
        $data['title']          = 'Artikel ' . $data['judul_kategori']['kategori'];
        $data['paging']         = $this->first_artikel_m->paging_kat($p, $id);
        $data['paging_page']    = 'artikel/kategori/' . $id;
        $data['paging_range']   = 3;
        $data['start_paging']   = max($data['paging']->start_link, $p - $data['paging_range']);
        $data['end_paging']     = min($data['paging']->end_link, $p + $data['paging_range']);
        $data['pages']          = range($data['start_paging'], $data['end_paging']);
        $data['artikel']        = $this->first_artikel_m->list_artikel($data['paging']->offset, $data['paging']->per_page, $id);

        $this->_get_common_data($data);
        $this->load->view($this->template, $data);
    }

    public function add_comment($id = 0)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('komentar', 'Komentar', 'required');
        $this->form_validation->set_rules('owner', 'Nama', 'required|max_length[50]');
        $this->form_validation->set_rules('no_hp', 'No HP', 'numeric|required|max_length[15]');
        $this->form_validation->set_rules('email', 'Email', 'valid_email|max_length[50]');

        $post = $this->input->post();

        if ($this->form_validation->run() == true) {
            // Periksa isian captcha
            include FCPATH . 'securimage/securimage.php';
            $securimage = new Securimage();

            if ($securimage->check($_POST['captcha_code']) == false) {
                $respon = [
                    'status' => -1, // Notif gagal
                    'pesan'  => 'Kode anda salah. Silakan ulangi lagi.',
                    'data'   => $post,
                ];
            } else {
                $data = [
                    'komentar'   => htmlentities($post['komentar']),
                    'owner'      => htmlentities($post['owner']),
                    'no_hp'      => bilangan($post['no_hp']),
                    'email'      => email($post['email']),
                    'status'     => 2,
                    'id_artikel' => $id,
                ];

                $res = $this->first_artikel_m->insert_comment($data);

                if ($res) {
                    $respon = [
                        'status' => 1, // Notif berhasil
                        'pesan'  => 'Komentar anda telah berhasil dikirim dan perlu dimoderasi untuk ditampilkan.',
                    ];
                } else {
                    $respon = [
                        'status' => -1, // Notif gagal
                        'pesan'  => 'Komentar anda gagal dikirim. Silakan ulangi lagi.',
                        'data'   => $post,
                    ];
                }
            }
        } else {
            $respon = [
                'status' => -1, // Notif gagal
                'pesan'  => validation_errors(),
                'data'   => $post,
            ];
        }

        $this->session->set_flashdata('notif', $respon);

        redirect($_SERVER['HTTP_REFERER'] . '#kolom-komentar');
    }

    public function peta()
    {
        if (! $this->web_menu_model->menu_aktif('peta')) {
            show_404();
        }

        $this->load->model('wilayah_model');
        $this->load->model('data_persil_model');
        $this->load->model('pembangunan_model');

        $data = $this->includes;

        $data['list_dusun']         = $this->wilayah_model->list_dusun();
        $data['wilayah']            = $this->wilayah_model->list_wil();
        $data['desa']               = $this->config_model->get_data();
        $data['title']              = 'Peta ' . ucwords($this->setting->sebutan_desa . ' ' . $data['desa']['nama_desa']);
        $data['dusun_gis']          = $data['list_dusun'];
        $data['rw_gis']             = $this->wilayah_model->list_rw();
        $data['rt_gis']             = $this->wilayah_model->list_rt();
        $data['list_ref']           = $this->referensi_model->list_ref(STAT_PENDUDUK);
        $data['covid']              = $this->laporan_penduduk_model->list_data('covid');
        $data['lokasi']             = $this->plan_lokasi_model->list_lokasi();
        $data['garis']              = $this->plan_garis_model->list_garis();
        $data['area']               = $this->plan_area_model->list_area();
        $data['lokasi_pembangunan'] = $this->pembangunan_model->list_lokasi_pembangunan();
        $data['persil']             = $this->data_persil_model->list_data();

        $data['halaman_peta'] = 'web/halaman_statis/peta';
        $this->_get_common_data($data);

        $this->set_template('layouts/peta_statis.tpl.php');
        $this->load->view($this->template, $data);
    }

    public function load_apbdes()
    {
        $data['transparansi'] = $this->keuangan_grafik_model->grafik_keuangan_tema();

        $this->_get_common_data($data);
        $this->load->view('gis/apbdes_web', $data);
    }

    public function load_aparatur_desa()
    {
        $this->_get_common_data($data);
        $this->load->view('gis/aparatur_desa_web', $data);
    }

    public function load_aparatur_wilayah($id = '', $kd_jabatan = 0)
    {
        $data['penduduk'] = $this->penduduk_model->get_penduduk($id);

        switch ($kd_jabatan) {
            case '1':
                $data['jabatan'] = 'Kepala Dusun';
                break;

            case '2':
                $data['jabatan'] = 'Ketua RW';
                break;

            case '3':
                $data['jabatan'] = 'Ketua RT';
                break;

            default:
                $data['jabatan'] = 'Kepala Dusun';
                break;
        }

        $this->load->view('gis/aparatur_wilayah', $data);
    }

    public function status_idm(int $tahun)
    {
    }

    public function status_sdgs()
    {
        if (! $this->web_menu_model->menu_aktif('status-sdgs')) {
            show_404();
        }

        $this->load->model('data_eksternal_model');
        $data = $this->includes;
        $this->_get_common_data($data);
        $kode_desa              = $data['desa']['kode_desa'];
        $data['evaluasi']       = $this->data_eksternal_model->sdgs_kemendes($kode_desa);
        $data['halaman_statis'] = '../../' . $this->theme_folder . '/' . $this->theme . '/partials/kemendes_sdgs.php';
        $this->set_template('layouts/halaman_statis_lebar.tpl.php');
        $this->load->view($this->template, $data);
    }

    public function get_form_info()
    {
        $redirect_link = $this->input->get('redirectLink', true);

        if ($this->session->inside_retry == false) {
            // Untuk kondisi SEBELUM autentikasi dan SETELAH RETRY hit API
            if ($this->input->get('outsideRetry', true) == 'true') {
                $this->session->inside_retry = true;
            }
            $this->session->google_form_id = $this->input->get('formId', true);
            $result                        = $this->analisis_import_model->import_gform($redirect_link);

            echo json_encode($result);
        } else {
            // Untuk kondisi SESAAT setelah Autentikasi
            $redirect_link = $this->session->inside_redirect_link;

            $this->session->unset_userdata(['inside_retry', 'inside_redirect_link']);

            header('Location: ' . $redirect_link . '?outsideRetry=true&code=' . $this->input->get('code', true) . '&formId=' . $this->session->google_form_id);
        }
    }
}
