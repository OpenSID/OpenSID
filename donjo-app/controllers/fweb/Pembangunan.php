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

class Pembangunan extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['pembangunan_model', 'pembangunan_dokumentasi_model']);
    }

    public function index($p = 1): void
    {
        if (! $this->web_menu_model->menu_aktif('pembangunan')) {
            show_404();
        }

        $this->pembangunan_model->set_tipe(''); // Ambil semua pembangunan

        $data = $this->includes;
        $this->_get_common_data($data);

        $data['paging']         = $this->pembangunan_model->paging_pembangunan($p);
        $data['paging_page']    = 'pembangunan/index';
        $data['paging_range']   = 3;
        $data['start_paging']   = max($data['paging']->start_link, $p - $data['paging_range']);
        $data['end_paging']     = min($data['paging']->end_link, $p + $data['paging_range']);
        $data['pages']          = range($data['start_paging'], $data['end_paging']);
        $data['pembangunan']    = $this->pembangunan_model->get_data('', 'semua')->where('p.status', '1')->limit($data['paging']->per_page, $data['paging']->offset)->order_by('p.tahun_anggaran', 'desc')->get()->result();
        $data['halaman_statis'] = $this->controller . '/index';

        $this->set_template('layouts/halaman_statis_lebar.tpl.php');
        theme_view($this->template, $data);
    }

    public function detail($slug = null): void
    {
        $data = $this->includes;
        $this->_get_common_data($data);

        $data['pembangunan']    = $this->pembangunan_model->slug($slug);
        $data['dokumentasi']    = $this->pembangunan_dokumentasi_model->find_dokumentasi($data['pembangunan']->id);
        $data['halaman_statis'] = $this->controller . '/detail';

        $this->set_template('layouts/halaman_statis_lebar.tpl.php');
        theme_view($this->template, $data);
    }
}
