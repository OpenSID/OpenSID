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

class Pengunjung extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('statistik_pengunjung');
        $this->modul_ini     = 13;
        $this->sub_modul_ini = 205;
    }

    public function index()
    {
        $data['hari_ini']   = $this->statistik_pengunjung->get_pengunjung_total('1');
        $data['kemarin']    = $this->statistik_pengunjung->get_pengunjung_total('2');
        $data['minggu_ini'] = $this->statistik_pengunjung->get_pengunjung_total('3');
        $data['bulan_ini']  = $this->statistik_pengunjung->get_pengunjung_total('4');
        $data['tahun_ini']  = $this->statistik_pengunjung->get_pengunjung_total('5');
        $data['jumlah']     = $this->statistik_pengunjung->get_pengunjung_total(null);
        $data['main']       = $this->statistik_pengunjung->get_pengunjung($this->session->id);

        $this->render('pengunjung/table', $data);
    }

    public function detail($id = null)
    {
        $this->session->set_userdata('id', $id);

        redirect('pengunjung');
    }

    public function clear()
    {
        $this->session->unset_userdata('id');

        redirect('pengunjung');
    }

    public function cetak()
    {
        $data['config'] = $this->config_model->get_data();
        $data['main']   = $this->statistik_pengunjung->get_pengunjung($this->session->id);
        $this->load->view('pengunjung/print', $data);
    }

    public function unduh()
    {
        $data['aksi']     = 'unduh';
        $data['config']   = $this->config_model->get_data();
        $data['filename'] = underscore('Laporan Data Statistik Pengunjung Website');
        $data['main']     = $this->statistik_pengunjung->get_pengunjung($this->session->id);
        $this->load->view('pengunjung/excel', $data);
    }
}
