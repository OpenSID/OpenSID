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

class Bumindes_inventaris_kekayaan extends Admin_Controller
{
    private $list_session = ['tahun'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['pamong_model', 'inventaris_laporan_model']);
        $this->modul_ini     = 301;
        $this->sub_modul_ini = 302;
    }

    public function index()
    {
        $tahun  = (empty($this->session->tahun) || $this->session->tahun == 'semua') ? date('Y') : $this->session->tahun;
        $pamong = $this->pamong_model->list_data();

        $data = [
            'subtitle'     => 'Buku Inventaris dan Kekayaan Desa',
            'selected_nav' => 'inventaris',
            'main_content' => 'bumindes/umum/content_inventaris',
            'min_tahun'    => $this->inventaris_laporan_model->min_tahun(),
            'data'         => $this->inventaris_laporan_model->permen_47($tahun, null),
            'kades'        => $data['sekdes'] = $pamong,
            'sekdes'       => $data['sekdes'] = $pamong,
            'tahun'        => $this->session->tahun,
        ];

        $this->render('bumindes/umum/main', $data);
    }

    public function filter($filter)
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect('bumindes_inventaris_kekayaan');
    }
}
