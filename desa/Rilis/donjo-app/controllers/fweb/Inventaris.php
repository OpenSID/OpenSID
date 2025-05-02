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

use App\Models\InventarisJalan;
use App\Services\LaporanInventaris;

class Inventaris extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->menu_aktif('inventaris');
    }

    public function index(): void
    {
        $data = $this->includes;

        $data['halaman_statis'] = 'inventaris/index';
        $data                   = array_merge($data, LaporanInventaris::detail());

        $this->_get_common_data($data);
        $this->set_template('layouts/halaman_statis.tpl.php');
        theme_view($this->template, $data);
    }

    public function detail($slug = null)
    {
        $data = $this->includes;

        switch ($slug) {
            case 'tanah':
                $this->load->model('inventaris_tanah_model');
                $data['judul']          = 'Inventaris Tanah';
                $data['main']           = $this->inventaris_tanah_model->list_inventaris();
                $data['total']          = $this->inventaris_tanah_model->sum_inventaris();
                $data['halaman_statis'] = 'inventaris/tanah';
                break;

            case 'peralatan-dan-mesin':
                $this->load->model('inventaris_peralatan_model');
                $data['judul']          = 'Inventaris Peralatan dan Mesin';
                $data['main']           = $this->inventaris_peralatan_model->list_inventaris();
                $data['total']          = $this->inventaris_peralatan_model->sum_inventaris();
                $data['halaman_statis'] = 'inventaris/peralatan';
                break;

            case 'gedung-dan-bangunan':
                $this->load->model('inventaris_gedung_model');
                $data['judul']          = 'Inventaris Gedung dan Bangunan';
                $data['main']           = $this->inventaris_gedung_model->list_inventaris();
                $data['total']          = $this->inventaris_gedung_model->sum_inventaris();
                $data['halaman_statis'] = 'inventaris/gedung';
                break;

            case 'jalan-irigasi-dan-jaringan':
                $this->load->model('inventaris_jalan_model');
                $data['judul']          = 'Inventaris Jalan, Irigasi dan Jaringan';
                $data['main']           = InventarisJalan::listInventaris();
                $data['total']          = InventarisJalan::sumInventaris();
                $data['halaman_statis'] = 'inventaris/jalan';
                break;

            case 'asset-tetap-lainnya':
                $this->load->model('inventaris_asset_model');
                $data['judul']          = 'Inventaris Asset Tetap Lainnya';
                $data['main']           = $this->inventaris_asset_model->list_inventaris();
                $data['total']          = $this->inventaris_asset_model->sum_inventaris();
                $data['halaman_statis'] = 'inventaris/asset';
                break;

            case 'konstruksi-dalam-pengerjaan':
                $this->load->model('inventaris_kontruksi_model');
                $data['judul']          = 'Inventaris Konstruksi dalam Pengerjaan';
                $data['main']           = $this->inventaris_kontruksi_model->list_inventaris();
                $data['total']          = $this->inventaris_kontruksi_model->sum_inventaris();
                $data['halaman_statis'] = 'inventaris/konstruksi';
                break;

            default:
                show_404();
                break;
        }

        $this->_get_common_data($data);
        $this->set_template('layouts/halaman_statis.tpl.php');
        theme_view($this->template, $data);
    }
}
