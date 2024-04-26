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

defined('BASEPATH') || exit('No direct script access allowed');

class Web_Controller extends MY_Controller
{
    public $CI;
    public $cek_anjungan;

    public function __construct()
    {
        // To inherit directly the attributes of the parent class.
        parent::__construct();
        $CI           = &get_instance();
        $this->header = identitas();

        $this->load->helper('theme');
        $theme              = theme_active();
        $this->theme        = str_replace('desa-', '', $theme->path);
        $this->theme_folder = str_replace($this->theme, '', $theme->path);

        // Variabel untuk tema
        $this->set_template();
        $this->includes['folder_themes'] = theme_view_path();

        if ($this->setting->offline_mode == 2) {
            $this->view_maintenance();

            exit;
        }
        if ($this->setting->offline_mode == 1 && can('b', 'web')) {
            $this->view_maintenance();

            exit;
        }

        $this->load->model('web_menu_model');
    }

    /**
     * set_template function
     *
     * @param string $template_file
     */
    public function set_template($template_file = 'template'): void
    {
        $this->template = $template_file;
    }

    public function _get_common_data(&$data): void
    {
        $this->load->model('statistik_pengunjung_model');
        $this->load->model('first_menu_m');
        $this->load->model('teks_berjalan_model');
        $this->load->model('first_artikel_m');
        $this->load->model('web_widget_model');
        $this->load->model('keuangan_grafik_manual_model');
        $this->load->model('keuangan_grafik_model');
        $this->load->model('pengaduan_model'); // TODO: Cek digunakan halaman apa saja

        // Counter statistik pengunjung
        $this->statistik_pengunjung_model->counter_visitor();

        // Data statistik pengunjung
        $data['statistik_pengunjung'] = $this->statistik_pengunjung_model->get_statistik();

        $data['latar_website'] = default_file($this->theme_model->lokasi_latar_website() . $this->setting->latar_website, DEFAULT_LATAR_WEBSITE);
        $data['desa']          = $this->header;
        $data['menu_atas']     = $this->first_menu_m->list_menu_atas();
        $data['menu_kiri']     = $this->first_menu_m->list_menu_kiri();
        $data['teks_berjalan'] = $this->db->field_exists('tipe', 'teks_berjalan') ? $this->teks_berjalan_model->list_data(true) : null;
        $data['slide_artikel'] = $this->first_artikel_m->slide_show();
        $data['slider_gambar'] = $this->first_artikel_m->slider_gambar();
        $data['w_cos']         = $this->web_widget_model->get_widget_aktif();
        $data['cek_anjungan']  = $this->cek_anjungan;

        $this->web_widget_model->get_widget_data($data);
        $data['data_config'] = $this->header;
        if ($this->setting->apbdes_footer && $this->setting->apbdes_footer_all) {
            $data['transparansi'] = $this->setting->apbdes_manual_input
                ? $this->keuangan_grafik_manual_model->grafik_keuangan_tema()
                : $this->keuangan_grafik_model->grafik_keuangan_tema();
        }
        // Pembersihan tidak dilakukan global, karena artikel yang dibuat oleh
        // petugas terpecaya diperbolehkan menampilkan <iframe> dsbnya..
        $list_kolom = [
            'arsip',
            'w_cos',
        ];

        foreach ($list_kolom as $kolom) {
            $data[$kolom] = $this->security->xss_clean($data[$kolom]);
        }
    }

    private function view_maintenance()
    {
        $data['jabatan']          = kades()->nama;
        $data['nama_kepala_desa'] = $this->header['nama_kepala_desa'];
        $data['nip_kepala_desa']  = $this->header['nip_kepala_desa'];

        return view('layouts.maintenance', $data);
    }
}
