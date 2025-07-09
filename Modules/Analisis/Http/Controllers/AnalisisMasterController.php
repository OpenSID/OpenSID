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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\AnalisisRefSubjekEnum;
use App\Models\KelompokMaster;
use App\Traits\Upload;
use Modules\Analisis\Libraries\Gform;
use Modules\Analisis\Libraries\Import;
use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisKlasifikasi;
use Modules\Analisis\Models\AnalisisMaster;
use Modules\Analisis\Models\AnalisisPeriode;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;

defined('BASEPATH') || exit('No direct script access allowed');

class AnalisisMasterController extends AdminModulController
{
    use Upload;

    public $moduleName          = 'Analisis';
    public $modul_ini           = 'analisis';
    public $kategori_pengaturan = 'Analisis';
    public $aliasController     = 'analisis_master';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        return view('analisis::master.index');
    }

    public function datatables()
    {
        if (request()->ajax()) {
            $canUpdate = can('u');

            return datatables()->of(AnalisisMaster::query())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($canUpdate): string {
                    $aksi = '<a href="' . ci_route('analisis_master.menu', $row->id) . '" class="btn bg-purple btn-sm" title="Rincian Analisis"><i class="fa fa-list-ol"></i></a> ';
                    if ($canUpdate) {
                        $aksi .= ' <a href="' . ci_route('analisis_master.form', $row->id) . '" class="btn bg-orange btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                        if ($row->gform_id) {
                            $aksi .= ' <a href="' . ci_route('analisis_master.update_gform', $row->id) . '" class="btn bg-navy btn-sm" title="Update Data Google Form"><i class="fa fa-refresh"></i></a> ';
                        }
                        $aksi .= ' <a href="' . ci_route('analisis_master.lock', $row->id) . '" class="btn bg-navy btn-sm"  title="Aktifkan"><i class="fa ' . ($row->isLock() ? 'fa-lock' : 'fa-unlock') . '">&nbsp;</i></a> ';

                        if ($row->jenis != 1 ) {
                            $aksi .= ' <a href="#" data-href="' . ci_route('analisis_master.delete', $row->id) . '" class="btn bg-maroon btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                        }
                    }
                    $aksi .= '<a href="' . ci_route('analisis_master.ekspor', $row->id) . '" class="btn bg-navy btn-sm" title="Ekspor Analisis"><i class="fa fa-download"></i></a> ';

                    return $aksi;
                })
                ->editColumn('subjek_tipe', static fn ($q) => AnalisisRefSubjekEnum::valueOf($q->subjek_tipe))
                ->editColumn('gform_last_sync', static fn ($q) => tgl_indo($q->gform_last_sync))
                ->rawColumns(['ceklist', 'aksi', 'gform_last_sync'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        isCan('u');
        $data['list_format_impor'] = ['1' => 'BDT 2015'];
        $data['list_subjek']       = AnalisisRefSubjekEnum::all();
        $data['list_kelompok']     = KelompokMaster::get()->toArray();
        $data['list_analisis']     = AnalisisMaster::subjekPenduduk()->get()->toArray();
        if ($id) {
            $data['action']          = 'Ubah';
            $data['form_action']     = ci_route('analisis_master.update', $id);
            $data['analisis_master'] = AnalisisMaster::findOrFail($id);
        } else {
            $data['action']          = 'Tambah';
            $data['form_action']     = ci_route('analisis_master.insert');
            $data['analisis_master'] = null;
        }

        return view('analisis::master.form', $data);
    }

    public function insert(): void
    {
        isCan('u');

        if (AnalisisMaster::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = null): void
    {
        isCan('u');

        $data = AnalisisMaster::findOrFail($id);

        if ($data->update(static::validate($this->request, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (AnalisisMaster::destroy($id ?? $this->request['id_cb']) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function importAnalisis()
    {
        isCan('u');

        return view('analisis::master.import', [
            'form_action' => ci_route('analisis_master.import'),
            'formatImpor' => ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'analisis.xlsx')),
            'formatPpls2' => ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'ppls2.xlsx')),
        ]);
    }

    public function import(): void
    {
        isCan('u');
        $config['upload_path']   = sys_get_temp_dir();
        $config['allowed_types'] = 'xlsx';

        $namaFile = $config['upload_path'] . DIRECTORY_SEPARATOR . $this->upload('userfile', $config);

        try {
            (new Import($namaFile))->analisis();
            redirect_with('success', 'Berhasil import analisis');
        } catch (Exception $e) {
            redirect_with('error', 'Gagal import analisis ' . $e->getMessage());
        }
    }

    public function ekspor($id): void
    {
        $writer = new Writer();
        $master = AnalisisMaster::find($id) ?? show_404();
        $master = $master->toArray();
        //Nama File
        $tgl      = date('Y_m_d');
        $fileName = 'analisis_' . urlencode((string) $master['nama']) . '_' . $tgl . '.xlsx';
        $writer->openToBrowser($fileName); // stream data directly to the browser

        $this->eksporMaster($writer, $master);
        $this->eksporPertanyaan($writer, $master);
        $this->eksporJawaban($writer, $master);
        $this->eksporKlasifikasi($writer, $master);

        $writer->close();

        redirect('analisis_master');
    }

    private function styleJudul(): Style
    {
        $border = new Border(
            new BorderPart(Border::TOP, Color::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::BOTTOM, Color::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::LEFT, Color::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::RIGHT, Color::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID)
        );

        return (new Style())
            ->setFontBold()
            ->setFontSize(14)
            ->setBorder($border);
    }

    private function styleBaris(): Style
    {
        $border = new Border(
            new BorderPart(Border::TOP, Color::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::BOTTOM, Color::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::LEFT, Color::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID),
            new BorderPart(Border::RIGHT, Color::GREEN, Border::WIDTH_THIN, Border::STYLE_SOLID)
        );

        return (new Style())
            ->setBorder($border);
    }

    private function eksporMaster(Writer $writer, array $master): void
    {
        $sheet = $writer->getCurrentSheet();
        $sheet->setName('master');
        $periode = AnalisisPeriode::active()->where('id_master', $master['id'])->first();
        //Tulis judul
        $master_analisis = [
            ['NAMA ANALISIS', $master['nama']],
            ['SUBJEK', $master['subjek_tipe']],
            ['STATUS', $master['lock']],
            ['BILANGAN PEMBAGI', $master['pembagi']],
            ['DESKRIPSI ANALISIS', $master['deskripsi']],
            ['NAMA PERIODE', $periode->nama ?? ''],
            ['TAHUN PENDATAAN', $periode->tahun_pelaksanaan ?? ''],
        ];

        foreach ($master_analisis as $baris_master) {
            $baris = [
                $baris_master[0],
                $baris_master[1],
            ];
            $row = Row::fromValues($baris);
            $writer->addRow($row);
        }
    }

    private function eksporPertanyaan(Writer $writer, array $master): void
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
        $header = Row::fromValues($judul, $this->styleJudul());
        $writer->addRow($header);
        // Tulis data
        $indikator = AnalisisIndikator::with(['kategori'])->where(['id_master' => $master['id']])->get()->toArray();

        foreach ($indikator as $p) {
            $baris_data = [$p['nomor'], $p['pertanyaan'], $p['kategori']['kategori'] ?? '', $p['id_tipe'], $p['bobot'], $p['act_analisis']];
            $baris      = Row::fromValues($baris_data, $this->styleBaris());
            $writer->addRow($baris);
        }
    }

    private function eksporJawaban(Writer $writer, array $master): void
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
        $header = Row::fromValues($judul, $this->styleJudul());
        $writer->addRow($header);
        // Tulis data
        $parameter = AnalisisIndikator::with(['parameter'])->where(['id_master' => $master['id']])->get();

        foreach ($parameter as $p) {
            $baris_data = [$p['nomor'], $p['parameter']['kode_jawaban'] ?? '', $p['parameter']['jawaban'] ?? '', $p['parameter']['nilai'] ?? ''];
            $baris      = Row::fromValues($baris_data, $this->styleBaris());
            $writer->addRow($baris);
        }
    }

    private function eksporKlasifikasi(Writer $writer, array $master): void
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
        $header = Row::fromValues($judul, $this->styleJudul());
        $writer->addRow($header);
        // Tulis data
        $klasifikasi = AnalisisKlasifikasi::where(['id_master' => $master['id']])->get();

        foreach ($klasifikasi as $k) {
            $baris_data = [$k['nama'], $k['minval'], $k['maxval']];
            $baris      = Row::fromValues($baris_data, $this->styleBaris());
            $writer->addRow($baris);
        }
    }

    public function importGform()
    {
        isCan('u');
        $data['form_action'] = ci_route('analisis_master.exec_import_gform');

        return view('analisis::master.import_gform', $data);
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
    private function getRedirectUri()
    {
        $api_gform_credential = setting('api_gform_credential') ?? config_item('api_gform_credential');
        if ($api_gform_credential) {
            $credential_data = json_decode(str_replace('\"', '"', $api_gform_credential), true);
            $redirect_uri    = $credential_data['web']['redirect_uris'][0];
        }
        if (empty($redirect_uri)) {
            return setting('api_gform_redirect_uri');
        }

        return $redirect_uri;
    }

    public function execImportGform(): void
    {
        isCan('u');
        $this->session->google_form_id = $this->request->get('input-form-id');

        $REDIRECT_URI = $this->getRedirectUri();
        $protocol     = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
        $self_link    = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

        if ($this->request->get('outsideRetry') == 'true') {
            $url = $REDIRECT_URI . '?formId=' . $this->request->get('formId') . '&redirectLink=' . $self_link . '&outsideRetry=true&code=' . $this->input->get('code');

            $client     = new Google\Client();
            $httpClient = $client->authorize();
            $response   = $httpClient->get($url);

            $variabel = json_decode((string) $response->getBody(), true);
            set_session('data_import', $variabel);
            set_session('gform_id', $this->request->get('formId'));
            set_session('success', 5);

            redirect('analisis_master');
        } else {
            $url = $REDIRECT_URI . '?formId=' . $this->request->get('input-form-id') . '&redirectLink=' . $self_link;
            header('Location: ' . $url);
        }
    }

    public function saveImportGform(): void
    {
        isCan('u');

        try {
            (new Gform($this->request))->save();
        } catch (Exception $e) {
            redirect_with('error', $e->getMessage());
        }

        redirect('analisis_master');
    }

    public function updateGform($id = 0): void
    {
        isCan('u');
        $form_id = AnalisisMaster::find($id)?->gform_id;

        $REDIRECT_URI = $this->getRedirectUri();
        $protocol     = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
        $self_link    = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

        if ($this->input->get('outsideRetry') == 'true') {
            $url = $REDIRECT_URI . '?formId=' . $this->input->get('formId') . '&redirectLink=' . $self_link . '&outsideRetry=true&code=' . $this->input->get('code');

            $client     = new Google\Client();
            $httpClient = $client->authorize();
            $response   = $httpClient->get($url);

            $variabel = json_decode((string) $response->getBody(), true);
            (new Gform($this->request))->update($id, $variabel);

            redirect('analisis_master');
        } else {
            $url = $REDIRECT_URI . '?formId=' . $this->session->google_form_id . '&redirectLink=' . $self_link;
            header('Location: ' . $url);
        }
    }

    public function lock($id): void
    {
        isCan('u');
        if (AnalisisMaster::gantiStatus($id, 'lock')) {
            redirect_with('success', 'Berhasil ubah status analisis');
        }

        redirect_with('error', 'Gagal status analisis');
    }

    public function menu($master)
    {
        $data = [
            'analisis_master' => AnalisisMaster::findOrFail($master),
        ];

        return view('analisis::master.menu_default', $data);
    }

    protected static function validate(array $request = []): array
    {
        return [
            'nama'         => judul($request['nama']),
            'subjek_tipe'  => $request['subjek_tipe'],
            'id_kelompok'  => $request['id_kelompok'] ?: null,
            'lock'         => $request['lock'] ?: null,
            'format_impor' => $request['format_impor'] ?: null,
            'pembagi'      => bilangan_titik($request['pembagi']),
            'id_child'     => $request['id_child'] ?: null,
            'deskripsi'    => htmlentities($request['deskripsi']),
        ];
    }
}
