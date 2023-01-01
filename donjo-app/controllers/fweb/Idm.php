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

class Idm extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('data_publik');
    }

    public function index($tahun = null)
    {
        if (! $this->web_menu_model->menu_aktif('status-idm/' . $tahun) || null === $tahun) {
            show_404();
        }

        $data = $this->includes;
        $this->_get_common_data($data);
        $kode_desa = $data['desa']['kode_desa'];
        $cache     = 'idm_' . $tahun . '_' . $kode_desa;

        if (cek_koneksi_internet()) {
            $this->data_publik
                ->set_api_url(config_item('api_idm') . "/{$kode_desa}/{$tahun}", $cache)
                ->set_interval(7)
                ->set_cache_folder(config_item('cache_path'));

            $idm = $this->data_publik->get_url_content();
            if (! $idm->body || $idm->body->error) {
                $idm->body->mapData->error_msg = ($idm->body->message ? '<a href="' . $idm->header->url . ' ">' . $idm->header->url . '</a>' : 'Tidak dapat mengambil data IDM');
            }

            $data['idm'] = $idm->body->mapData;
        }

        $data['halaman_statis'] = 'idm/index';

        $this->_get_common_data($data);
        $this->set_template('layouts/halaman_statis_lebar.tpl.php');
        $this->load->view($this->template, $data);
    }
}
