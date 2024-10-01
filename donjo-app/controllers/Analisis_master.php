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

use App\Enums\AnalisisRefSubjekEnum;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Writer\Common\Creator\Style\BorderBuilder;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

defined('BASEPATH') || exit('No direct script access allowed');

class Analisis_master extends Admin_Controller
{
    public $modul_ini     = 'analisis';
    public $sub_modul_ini = 'master-analisis';

    public function __construct()
    {
        parent::__construct();
        isCan('b');

        $this->load->model('analisis_master_model');
        $this->load->model('analisis_import_model');
        $this->load->model('analisis_indikator_model');
        $this->load->model('analisis_parameter_model');
        $this->load->model('analisis_klasifikasi_model');
        $this->session->unset_userdata(['submenu', 'asubmenu']);
        $this->set_page     = ['20', '50', '100'];
        $this->list_session = ['cari', 'filter', 'state'];
    }

    public function clear(): void
    {
        $this->session->unset_userdata($this->list_session);
        $this->session->per_page = $this->set_page[0];

        redirect($this->controller);
    }

    public function leave(): void
    {
        $id = $this->session->analisis_master;
        $this->session->unset_userdata(['analisis_master']);

        redirect("{$this->controller}/menu/{$id}");
    }

    public function index($p = 1, $o = 0): void
    {
        $this->session->unset_userdata(['analisis_master', 'analisis_nama']);

        $data['p'] = $p;
        $data['o'] = $o;

        foreach ($this->list_session as $list) {
            $data[$list] = $this->session->{$list} ?: '';
        }

        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }
        $data['func']        = 'index';
        $data['set_page']    = $this->set_page;
        $data['per_page']    = $this->session->per_page;
        $data['paging']      = $this->analisis_master_model->paging($p, $o);
        $data['data_import'] = $this->session->data_import;
        $data['list_error']  = $this->session->list_error;
        $data['keyword']     = $this->analisis_master_model->autocomplete();
        $data['list_subjek'] = $this->analisis_master_model->list_subjek();
        $data['main']        = $this->analisis_master_model->list_data($o, $data['paging']->offset, $data['paging']->per_page);

        $this->session->unset_userdata('list_error');

        $this->render('analisis_master/table', $data);
    }

    public function form($p = 1, $o = 0, $id = 0): void
    {
        isCan('u');
        $data['p'] = $p;
        $data['o'] = $o;

        if ($id) {
            $data['analisis_master'] = $this->analisis_master_model->get_analisis_master($id) ?? show_404();
            $data['form_action']     = site_url("{$this->controller}/update/{$p}/{$o}/{$id}");
        } else {
            $data['analisis_master'] = null;
            $data['form_action']     = site_url("{$this->controller}/insert");
        }

        $data['list_format_impor'] = ['1' => 'BDT 2015'];
        $data['list_subjek']       = $this->analisis_master_model->list_subjek();
        $data['list_kelompok']     = $this->analisis_master_model->list_kelompok();
        $data['list_analisis']     = $this->analisis_master_model->list_analisis_child();

        $this->render('analisis_master/form', $data);
    }

    public function panduan(): void
    {
        $this->render('analisis_master/panduan');
    }

    public function import_analisis(): void
    {
        isCan('u');
        $data['form_action'] = site_url("{$this->controller}/import");

        $this->load->view('analisis_master/import', $data);
    }

    public function import(): void
    {
        isCan('u');
        $this->analisis_import_model->impor_analisis();

        redirect($this->controller);
    }

    public function ekspor($id): void
    {
        $writer = WriterEntityFactory::createXLSXWriter();
        $master = $this->analisis_master_model->get_analisis_master($id) ?? show_404();
        //Nama File
        $tgl      = date('Y_m_d');
        $fileName = 'analisis_' . urlencode($master['nama']) . '_' . $tgl . '.xlsx';
        $writer->openToBrowser($fileName); // stream data directly to the browser

        $this->ekspor_master($writer, $master);
        $this->ekspor_pertanyaan($writer, $master);
        $this->ekspor_jawaban($writer, $master);
        $this->ekspor_klasifikasi($writer, $master);

        $writer->close();

        redirect($this->controller);
    }

    private function style_judul()
    {
        $border = (new BorderBuilder())
            ->setBorderBottom()
            ->setBorderTop()
            ->setBorderRight()
            ->setBorderLeft()
            ->build();

        return (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(14)
            ->setBorder($border)
            ->build();
    }

    private function style_baris()
    {
        $border = (new BorderBuilder())
            ->setBorderBottom(null, Border::WIDTH_THIN)
            ->setBorderRight(null, Border::WIDTH_THIN)
            ->setBorderLeft(null, Border::WIDTH_THIN)
            ->build();

        return (new StyleBuilder())
            ->setBorder($border)
            ->build();
    }

    private function ekspor_master($writer, array $master): void
    {
        $sheet = $writer->getCurrentSheet();
        $sheet->setName('master');
        $periode = $this->analisis_master_model->get_periode($master['id']);
        //Tulis judul
        $master_analisis = [
            ['NAMA ANALISIS', $master['nama']],
            ['SUBJEK', $master['subjek_tipe']],
            ['STATUS', $master['lock']],
            ['BILANGAN PEMBAGI', $master['pembagi']],
            ['DESKRIPSI ANALISIS', $master['deskripsi']],
            ['NAMA PERIODE', $periode->nama],
            ['TAHUN PENDATAAN', $periode->tahun_pelaksanaan],
        ];

        foreach ($master_analisis as $baris_master) {
            $baris = [
                WriterEntityFactory::createCell($baris_master[0], $this->style_judul()),
                WriterEntityFactory::createCell($baris_master[1], $this->style_baris()),
            ];
            $row = WriterEntityFactory::createRow($baris);
            $writer->addRow($row);
        }
    }

    private function ekspor_pertanyaan($writer, array $master): void
    {
        $sheet = $writer->addNewSheetAndMakeItCurrent();
        $sheet->setName('pertanyaan');
        //Tulis judul
        $daftar_kolom = [
            ['NO / KODE', 'nomor'],
            ['PERTANYAAN / INDIKATOR', 'pertanyaan'],
            ['KATEGORI / ASPEK', 'kategori'],
            ['TIPE PERTANYAAN', 'id_tipe'],
            ['BOBOT', 'bobot'],
            ['AKSI ANALISIS', 'act_analisis'],
        ];
        $judul  = array_column($daftar_kolom, 0);
        $header = WriterEntityFactory::createRowFromArray($judul, $this->style_judul());
        $writer->addRow($header);
        // Tulis data
        $indikator = $this->analisis_indikator_model->raw_analisis_indikator_by_id_master($master['id']);

        foreach ($indikator as $p) {
            $baris_data = [$p['nomor'], $p['pertanyaan'], $p['kategori'], $p['id_tipe'], $p['bobot'], $p['act_analisis']];
            $baris      = WriterEntityFactory::createRowFromArray($baris_data, $this->style_baris());
            $writer->addRow($baris);
        }
    }

    private function ekspor_jawaban($writer, array $master): void
    {
        $jawaban = $writer->addNewSheetAndMakeItCurrent();
        $jawaban->setName('jawaban');
        //Tulis judul
        $daftar_kolom = [
            ['KODE PERTANYAAN', 'nomor'],
            ['KODE JAWABAN', 'kode_jawaban'],
            ['ISI JAWABAN', 'jawaban'],
            ['NILAI', 'nilai'],
        ];
        $judul  = array_column($daftar_kolom, 0);
        $header = WriterEntityFactory::createRowFromArray($judul, $this->style_judul());
        $writer->addRow($header);
        // Tulis data
        $parameter = $this->analisis_parameter_model->list_parameter_by_id_master($master['id']);

        foreach ($parameter as $p) {
            $baris_data = [$p['nomor'], $p['kode_jawaban'], $p['jawaban'], $p['nilai']];
            $baris      = WriterEntityFactory::createRowFromArray($baris_data, $this->style_baris());
            $writer->addRow($baris);
        }
    }

    private function ekspor_klasifikasi($writer, array $master): void
    {
        $klasifikasi = $writer->addNewSheetAndMakeItCurrent();
        $klasifikasi->setName('klasifikasi');
        //Tulis judul
        $daftar_kolom = [
            ['KLASIFIKASI', 'nama'],
            ['NILAI MINIMAL', 'minval'],
            ['NILAI MAKSIMAL', 'maxval'],
        ];
        $judul  = array_column($daftar_kolom, 0);
        $header = WriterEntityFactory::createRowFromArray($judul, $this->style_judul());
        $writer->addRow($header);
        // Tulis data
        $klasifikasi = $this->analisis_klasifikasi_model->list_klasifikasi_by_id_master($master['id']);

        foreach ($klasifikasi as $k) {
            $baris_data = [$k['nama'], $k['minval'], $k['maxval']];
            $baris      = WriterEntityFactory::createRowFromArray($baris_data, $this->style_baris());
            $writer->addRow($baris);
        }
    }

    public function import_gform(): void
    {
        isCan('u');
        $data['form_action'] = site_url("{$this->controller}/exec_import_gform");

        $this->load->view('analisis_master/import_gform', $data);
    }

    public function menu($id = 0): void
    {
        $this->session->analisis_master = $id;
        $data['analisis_master']        = $this->analisis_master_model->get_analisis_master($id) ?? show_404();
        $master                         = $data['analisis_master'];
        $this->session->analisis_nama   = $master['nama'];
        $this->session->subjek_tipe     = $master['subjek_tipe'];

        $data['menu_respon']  = 'analisis_respon';
        $data['menu_laporan'] = 'analisis_laporan';

        if ($master['subjek_tipe'] == 5) {
            $data['subjek'] = ucwords($this->setting->sebutan_desa);
        } elseif ($master['subjek_tipe'] == 6) {
            $data['subjek'] = ucwords($this->setting->sebutan_dusun);
        } else {
            $data['subjek'] = AnalisisRefSubjekEnum::all()[$master['subjek_tipe']];
        }

        // TODO: Periksa apakah perlu lakukan pre_update
        // $this->load->model('analisis_respon_model');
        // $this->analisis_respon_model->pre_update();

        $this->render('analisis_master/menu', $data);
    }

    public function search(): void
    {
        $cari = $this->input->post('cari');
        if ($cari != '') {
            $_SESSION['cari'] = $cari;
        } else {
            unset($_SESSION['cari']);
        }

        redirect($this->controller);
    }

    public function filter(): void
    {
        $filter = $this->input->post('filter');
        if ($filter != 0) {
            $_SESSION['filter'] = $filter;
        } else {
            unset($_SESSION['filter']);
        }

        redirect($this->controller);
    }

    public function state(): void
    {
        $filter = $this->input->post('state');
        if ($filter != 0) {
            $_SESSION['state'] = $filter;
        } else {
            unset($_SESSION['state']);
        }

        redirect($this->controller);
    }

    public function insert(): void
    {
        isCan('u');
        $this->analisis_master_model->insert();

        redirect($this->controller);
    }

    /**
     * 1. Credential
     * 2. Id script
     * 3. Redirect URI
     *
     * - Jika 1 dan 2 diisi (asumsi user pakai akun google sendiri) eksekusi dari nilai yg diisi user. Abaikan isisan 3. Redirect ambil dari isian 1
     * - Jika 1 dan 2 kosong. 3 diisi. Import gform langsung menuju redirect field 3
     * - Jika semua tidak terisi (asumsi opensid ini yang jalan di server OpenDesa) ambil credential setting di file config
     */
    private function get_redirect_uri()
    {
        if ($this->setting->api_gform_credential) {
            $api_gform_credential = $this->setting->api_gform_credential;
        } elseif (empty($this->setting->api_gform_redirect_uri)) {
            $api_gform_credential = config_item('api_gform_credential');
        }
        if ($api_gform_credential) {
            $credential_data = json_decode(str_replace('\"', '"', $api_gform_credential), true);
            $redirect_uri    = $credential_data['web']['redirect_uris'][0];
        }
        if (empty($redirect_uri)) {
            return $this->setting->api_gform_redirect_uri;
        }

        return $redirect_uri;
    }

    public function exec_import_gform(): void
    {
        isCan('u');
        $this->session->google_form_id = $this->input->post('input-form-id');

        $REDIRECT_URI = $this->get_redirect_uri();
        $protocol     = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
        $self_link    = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

        if ($this->input->get('outsideRetry') == 'true') {
            $url = $REDIRECT_URI . '?formId=' . $this->input->get('formId') . '&redirectLink=' . $self_link . '&outsideRetry=true&code=' . $this->input->get('code');

            $client     = new Google\Client();
            $httpClient = $client->authorize();
            $response   = $httpClient->get($url);

            $variabel                   = json_decode($response->getBody(), true);
            $this->session->data_import = $variabel;
            $this->session->gform_id    = $this->input->get('formId');
            $this->session->success     = 5;

            redirect($this->controller);
        } else {
            $url = $REDIRECT_URI . '?formId=' . $this->input->post('input-form-id') . '&redirectLink=' . $self_link;
            header('Location: ' . $url);
        }
    }

    public function update($p = 1, $o = 0, $id = 0): void
    {
        isCan('u');
        $this->analisis_master_model->update($id);

        redirect("{$this->controller}/index/{$p}/{$o}");
    }

    public function delete($p = 1, $o = 0, $id = 0): void
    {
        isCan('h');
        $this->analisis_master_model->delete($id);

        redirect("{$this->controller}/index/{$p}/{$o}");
    }

    public function delete_all($p = 1, $o = 0): void
    {
        isCan('h');
        $this->analisis_master_model->delete_all();

        redirect("{$this->controller}/index/{$p}/{$o}");
    }

    public function save_import_gform(): void
    {
        isCan('u');
        $this->analisis_import_model->save_import_gform();
        $this->session->unset_userdata('data_import');

        redirect($this->controller);
    }

    public function update_gform($id = 0): void
    {
        isCan('u');
        $this->session->google_form_id = $this->analisis_master_model->get_analisis_master($id)['gform_id'];

        $REDIRECT_URI = $this->get_redirect_uri();
        $protocol     = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
        $self_link    = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

        if ($this->input->get('outsideRetry') == 'true') {
            $url = $REDIRECT_URI . '?formId=' . $this->input->get('formId') . '&redirectLink=' . $self_link . '&outsideRetry=true&code=' . $this->input->get('code');

            $client     = new Google\Client();
            $httpClient = $client->authorize();
            $response   = $httpClient->get($url);

            $variabel                   = json_decode($response->getBody(), true);
            $this->session->data_import = $variabel;
            $this->analisis_import_model->update_import_gform($id, $variabel);

            redirect($this->controller);
        } else {
            $url = $REDIRECT_URI . '?formId=' . $this->session->google_form_id . '&redirectLink=' . $self_link;
            header('Location: ' . $url);
        }
    }
}
