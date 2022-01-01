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

class Bumindes_umum extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini = 301;
    }

    public function index()
    {
        redirect('dokumen_sekretariat/peraturan_desa/3');
    }

    // TABLES
    public function tables($page = 'peraturan', $page_number = 1, $offset = 0)
    {
        $this->sub_modul_ini = 302;

        // set session
        if (isset($_SESSION['cari'])) {
            $data['cari'] = $_SESSION['cari'];
        } else {
            $data['cari'] = '';
        }

        if (isset($_SESSION['filter'])) {
            $data['filter'] = $_SESSION['filter'];
        } else {
            $data['filter'] = '';
        }

        if (isset($_POST['per_page'])) {
            $_SESSION['per_page'] = $_POST['per_page'];
        }
        $data['per_page'] = $_SESSION['per_page'];
        // set session END

        // load data for displaying at tables
        $data = array_merge($data, $this->load_data_tables($page, $page_number, $offset));

        $header = $this->header_model->get_data();

        $this->load->view('header', $header);
        $this->load->view('nav');
        $this->load->view('bumindes/umum/main', $data);
        $this->load->view('footer');
    }

    private function load_data_tables($page, $page_number, $offset)
    {
        $data['selected_nav'] = $page;

        switch (strtolower($page)) {
            case 'ekspedisi':
                $data = array_merge($data, $this->load_ekspedisi_data_tables($page_number, $offset));
                break;

            case 'berita':
                $data = array_merge($data, $this->load_berita_data_tables($page_number, $offset));
                break;

            default:
                $data = array_merge($data, $this->load_ekspedisi_data_tables($page_number, $offset));
                break;
        }

        return $data;
    }

    private function load_ekspedisi_data_tables($page_number = 1, $offset = 0)
    {
        $data['main_content'] = 'bumindes/umum/content_ekspedisi';
        $data['subtitle']     = 'Buku Ekspedisi';

        return $data;
    }

    private function load_berita_data_tables($page_number = 1, $offset = 0)
    {
        $data['main_content'] = 'bumindes/umum/content_berita';
        $data['subtitle']     = 'Buku Lembaran Desa dan Berita Desa';

        return $data;
    }
    // TABLES END

    // FORM
    public function form($page = 'peraturan', $page_number = 1, $offset = 0, $key = null)
    {
        $this->sub_modul_ini = 302;

        $data = [];
        $data = array_merge($data, $this->load_form($page, $page_number, $offset, $key));

        $header = $this->header_model->get_data();

        $this->load->view('header', $header);
        $this->load->view('nav', $nav);
        $this->load->view('bumindes/umum/main', $data);
        $this->load->view('footer');
    }

    private function load_form($page, $page_number, $offset, $key)
    {
        $data['p'] = $page_number;
        $data['o'] = $offset;

        $data['selected_nav'] = $page;

        switch (strtolower($page)) {
            case 'ekspedisi':
                $data = array_merge($data, $this->load_form_ekspedisi($page_number, $offset, $key));
                break;

            case 'berita':
                $data = array_merge($data, $this->load_form_berita($page_number, $offset, $key));
                break;

            default:
                $data = array_merge($data, $this->load_form_peraturan($page_number, $offset, $key));
                break;
        }

        return $data;
    }

    public function load_form_ekspedisi($page_number, $offset, $key)
    {
    }

    public function load_form_berita($page_number, $offset, $key)
    {
    }

    // FORM END

    // INSERT
    public function insert($page)
    {
        switch (strtolower($page)) {
            case 'ekspedisi':

                break;

            case 'berita':

                break;

            default:

                break;
        }
    }
    // INSERT END

    // DELETE
    public function delete($page, $p = 1, $o = 0, $id = '')
    {
        switch (strtolower($page)) {
            case 'ekspedisi':

                break;

            case 'berita':

                break;

            default:

                break;
        }
    }

    public function delete_all($page, $p = 1, $o = 0)
    {
        switch (strtolower($page)) {
            case 'ekspedisi':

                break;

            case 'berita':

                break;

            default:

                break;
        }
    }

    // UPDATE
    public function update($page, $p = 1, $o = 0, $id = '')
    {
        switch (strtolower($page)) {
            case 'ekspedisi':

                break;

            case 'berita':

                break;

            default:

                break;
        }
    }
    // UPDATE END
}
