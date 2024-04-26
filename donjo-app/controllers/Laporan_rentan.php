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

class Laporan_rentan extends Admin_Controller
{
    public $modul_ini     = 'statistik';
    public $sub_modul_ini = 'laporan-kelompok-rentan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['laporan_bulanan_model', 'wilayah_model']);
    }

    public function clear(): void
    {
        $session = ['cari', 'filter', 'dusun', 'rw', 'rt'];
        $this->session->unset_userdata($session);
        $this->session->per_page = 20;
        session_error_clear();

        redirect('laporan_rentan');
    }

    public function index(): void
    {
        $data['dusun']      = $this->session->dusun ?? '';
        $data['config']     = $this->header['desa'];
        $data['list_dusun'] = $this->wilayah_model->list_dusun();
        $data['main']       = $this->laporan_bulanan_model->list_data();
        $this->render('laporan/kelompok', $data);
    }

    public function cetak(): void
    {
        $data['config'] = $this->header['desa'];
        $data['main']   = $this->laporan_bulanan_model->list_data();
        $this->load->view('laporan/kelompok_print', $data);
    }

    public function excel(): void
    {
        $data['config'] = $this->header['desa'];
        $data['main']   = $this->laporan_bulanan_model->list_data();
        $this->load->view('laporan/kelompok_excel', $data);
    }

    public function dusun(): void
    {
        $dusun = $this->input->post('dusun');
        if ($dusun != '') {
            $this->session->dusun = $dusun;
        } else {
            $this->session->unset_userdata('dusun');
        }
        redirect('laporan_rentan');
    }
}
