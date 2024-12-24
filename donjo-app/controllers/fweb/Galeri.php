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

class Galeri extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('first_gallery_m');
        if (! $this->web_menu_model->menu_aktif('galeri')) {
            show_404();
        }
    }

    public function index($p = 1): void
    {
        $p ??= 1;
        $data                 = $this->includes;
        $data['p']            = $p;
        $data['paging']       = $this->first_gallery_m->paging($p);
        $data['paging_range'] = 3;
        $data['start_paging'] = max($data['paging']->start_link, $p - $data['paging_range']);
        $data['end_paging']   = min($data['paging']->end_link, $p + $data['paging_range']);
        $data['pages']        = range($data['start_paging'], $data['end_paging']);
        $data['gallery']      = $this->first_gallery_m->gallery_show($data['paging']->offset, $data['paging']->per_page);
        $data['paging_page']  = 'galeri/index';

        $this->_get_common_data($data);
        $this->set_template('layouts/gallery.tpl.php');
        theme_view($this->template, $data);
    }

    public function detail($parent = 0, $p = 1): void
    {
        $parent ??= 0;
        $p ??= 1;
        $data                 = $this->includes;
        $data['p']            = $p;
        $data['paging']       = $this->first_gallery_m->paging2($parent, $p);
        $data['paging_range'] = 3;
        $data['start_paging'] = max($data['paging']->start_link, $p - $data['paging_range']);
        $data['end_paging']   = min($data['paging']->end_link, $p + $data['paging_range']);
        $data['pages']        = range($data['start_paging'], $data['end_paging']);
        $data['gallery']      = $this->first_gallery_m->sub_gallery_show($parent, $data['paging']->offset, $data['paging']->per_page);
        $data['parent']       = $this->first_gallery_m->get_parent($parent);
        $data['paging_page']  = "galeri/{$parent}/index";

        $this->_get_common_data($data);
        $this->set_template('layouts/sub_gallery.tpl.php');
        theme_view($this->template, $data);
    }
}
