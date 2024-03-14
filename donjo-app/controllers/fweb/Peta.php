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

class Peta extends Web_Controller
{
    public function index(): void
    {
        if (! $this->web_menu_model->menu_aktif('peta')) {
            show_404();
        }

        $this->load->model(['wilayah_model', 'referensi_model', 'laporan_penduduk_model', 'plan_garis_model', 'plan_lokasi_model', 'data_persil_model', 'plan_area_model', 'pembangunan_model']);

        $data = $this->includes;

        $data['list_dusun']         = $this->wilayah_model->list_dusun();
        $data['wilayah']            = $this->wilayah_model->list_wil();
        $data['desa']               = $this->header;
        $data['title']              = 'Peta ' . ucwords($this->setting->sebutan_desa . ' ' . $data['desa']['nama_desa']);
        $data['dusun_gis']          = $data['list_dusun'];
        $data['rw_gis']             = $this->wilayah_model->list_rw();
        $data['rt_gis']             = $this->wilayah_model->list_rt();
        $data['list_ref']           = $this->referensi_model->list_ref(STAT_PENDUDUK);
        $data['covid']              = $this->laporan_penduduk_model->list_data('covid');
        $data['lokasi']             = $this->plan_lokasi_model->list_lokasi(1);
        $data['garis']              = $this->plan_garis_model->list_garis(1);
        $data['area']               = $this->plan_area_model->list_area(1);
        $data['lokasi_pembangunan'] = $this->pembangunan_model->list_lokasi_pembangunan(1);
        $data['persil']             = $this->data_persil_model->list_data();
        $data['list_bantuan']       = collect(unserialize(STAT_BANTUAN))->toArray() + collect($this->program_bantuan_model->list_program(0))->pluck('nama', 'lap')->toArray();
        $data['halaman_peta']       = 'web/halaman_statis/peta';

        $this->_get_common_data($data);
        $this->set_template('layouts/peta_statis.tpl.php');
        theme_view($this->template, $data);
    }
}
