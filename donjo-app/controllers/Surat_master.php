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

use App\Enums\FontSuratEnum;
use App\Libraries\TinyMCE;
use App\Models\FormatSurat;
use App\Models\KlasifikasiSurat;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use App\Models\Sex;
use App\Models\StatusDasar;
use App\Models\SyaratSurat;
use App\Models\User;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

defined('BASEPATH') || exit('No direct script access allowed');

class Surat_master extends Admin_Controller
{
    protected $tinymce;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['surat_master_model', 'lapor_model']);
        $this->tinymce       = new TinyMCE();
        $this->modul_ini     = 4;
        $this->sub_modul_ini = 30;
    }

    public function index()
    {
        return view('admin.pengaturan_surat.index', [
            'jenisSurat' => FormatSurat::JENIS_SURAT,
        ]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables(FormatSurat::jenis($this->input->get('jenis')))
                ->addColumn('ceklist', static function ($row) {
                    return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("surat_master/form/{$row->id}") . '" class="btn btn-warning btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a> ';

                        if ($row->kunci) {
                            $aksi .= '<a href="' . site_url("surat_master/kunci/{$row->id}/1") . '" class="btn bg-navy btn-sm" title="Aktifkan Surat"><i class="fa fa-lock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . site_url("surat_master/kunci/{$row->id}/0") . '" class="btn bg-navy btn-sm" title="Nonaktifkan Surat"><i class="fa fa-unlock"></i></a> ';

                            if ($row->favorit) {
                                $aksi .= '<a href="' . site_url("surat_master/favorit/{$row->id}/1") . '" class="btn bg-purple btn-sm" title="Keluarkan dari Daftar Favorit"><i class="fa fa-star"></i></a> ';
                            } else {
                                $aksi .= '<a href="' . site_url("surat_master/favorit/{$row->id}/0") . '" class="btn bg-purple btn-sm" title="Tambahkan ke Daftar Favorit"><i class="fa fa-star-o"></i></a> ';
                            }
                        }
                    }

                    if (can('h') && ($row->jenis === 2 || $row->jenis === 4)) {
                        $aksi .= '<a href="#" data-href="' . site_url("surat_master/delete/{$row->id}") . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('lampiran', static function ($row) {
                    return kode_format($row->lampiran);
                })
                ->rawColumns(['ceklist', 'aksi', 'template_surat'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        $this->redirect_hak_akses('u');

        if ($id) {
            $suratMaster = FormatSurat::find($id) ?? show_404();

            $data['action']      = 'Ubah';
            $data['suratMaster'] = $suratMaster;

            if (in_array($suratMaster->jenis, FormatSurat::RTF)) {
                $data['formAction'] = route('surat_master.update', $id);
                $data['qrCode']     = QRCodeExist($suratMaster->url_surat);
            } else {
                $data['formAction'] = route('surat_master.update_baru', $id);
            }
        } else {
            $data['action']      = 'Tambah';
            $data['formAction']  = route('surat_master.insert');
            $data['suratMaster'] = null;
        }

        if (in_array($suratMaster->jenis, [3, 4, null])) {
            $data['margins']              = json_decode($suratMaster->margin) ?? FormatSurat::MARGINS;
            $data['orientations']         = FormatSurat::ORIENTATAIONS;
            $data['sizes']                = FormatSurat::SIZES;
            $data['default_orientations'] = FormatSurat::DEFAULT_ORIENTATAIONS;
            $data['default_sizes']        = FormatSurat::DEFAULT_SIZES;
            $data['qrCode']               = true;
            $data['header']               = $suratMaster->header ?? 1;
            $data['footer']               = $suratMaster->footer ?? 1;
            $data['daftar_lampiran']      = $this->tinymce->getDaftarLampiran();
        }

        $data['form_isian']       = $this->form_isian();
        $data['masaBerlaku']      = FormatSurat::MASA_BERLAKU;
        $data['attributes']       = FormatSurat::ATTRIBUTES;
        $data['klasifikasiSurat'] = KlasifikasiSurat::orderBy('kode')->enabled()->get(['kode', 'nama']);
        $data['pengaturanSurat']  = SettingAplikasi::whereKategori('format_surat')->pluck('value', 'key')->toArray();

        return view('admin.pengaturan_surat.form', $data);
    }

    private function form_isian()
    {
        return [
            'daftar_jenis_kelamin' => Sex::pluck('nama', 'id'),
            'daftar_status_dasar'  => StatusDasar::pluck('nama', 'id'),
        ];
    }

    public function syaratSuratDatatables($id = null)
    {
        if ($this->input->is_ajax_request()) {
            $suratMaster = FormatSurat::select('syarat_surat')->find($id);

            return datatables(SyaratSurat::query())
                ->addColumn('ceklist', static function ($row) use ($suratMaster) {
                    $checked = in_array($row->ref_syarat_id, json_decode($suratMaster->syarat_surat)) ? 'checked' : '';

                    return '<input type="checkbox" name="id_cb[]" value="' . $row->ref_syarat_id . '" ' . $checked . ' />';
                })
                ->addIndexColumn()
                ->rawColumns(['ceklist'])
                ->make();
        }

        return show_404();
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');

        if ($this->request['action'] == 'preview') {
            $this->preview();
        }

        if (FormatSurat::insert(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update_baru($id = null)
    {
        $this->redirect_hak_akses('u');

        if ($this->request['action'] == 'preview') {
            $this->preview();
        }

        $data = FormatSurat::find($id) ?? show_404();

        if ($data->update(static::validate($this->request, $data->jenis))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function update($id = null)
    {
        $this->redirect_hak_akses('u');

        if (! empty($this->request['surat'])) {
            $this->surat_master_model->upload($this->request['url_surat']);
        }

        $syarat  = $this->request['id_cb'];
        $mandiri = $this->request['mandiri'];
        unset($_POST['id_cb'], $_POST['tabeldata_length'], $_POST['surat']);

        $id = $this->surat_master_model->update($id);
        $this->lapor_model->update_syarat_surat($id, $syarat, $mandiri);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    private function validate($request = [], $jenis = 4)
    {
        $kodeIsian = [];

        for ($i = 0; $i < count($request['tipe_kode']); $i++) {
            if (empty($request['tipe_kode'][$i]) || empty($request['nama_kode'][$i]) || empty($request['deskripsi_kode'][$i])) {
                continue;
            }

            $kodeIsian[] = [
                'tipe'      => $request['tipe_kode'][$i],
                'kode'      => '[' . str_replace(' ', '_', strtolower($request['nama_kode'][$i])) . ']',
                'nama'      => $request['nama_kode'][$i],
                'deskripsi' => $request['deskripsi_kode'][$i],
                'atribut'   => $request['atribut_kode'][$i],
            ];
        }

        $formIsian = [
            'individu' => [
                'sex'          => $request['individu_sex'] ?? null,
                'status_dasar' => $request['individu_status_dasar'] ?? null,
            ],
        ];

        $nama_surat = nama_terbatas($request['nama']);

        $data = [
            'nama'                => $nama_surat,
            'url_surat'           => 'surat_' . strtolower(str_replace([' ', '-'], '_', $nama_surat)),
            'kode_surat'          => $request['kode_surat'],
            'masa_berlaku'        => $request['masa_berlaku'],
            'satuan_masa_berlaku' => $request['satuan_masa_berlaku'],
            'jenis'               => $jenis,
            'mandiri'             => $request['mandiri'],
            'syarat_surat'        => $request['mandiri'] ? json_encode($request['id_cb']) : null,
            'qr_code'             => $request['qr_code'],
            'logo_garuda'         => $request['logo_garuda'],
            'template_desa'       => $request['template_desa'],
            'form_isian'          => json_encode($formIsian),
            'kode_isian'          => json_encode($kodeIsian),
            'orientasi'           => $request['orientasi'],
            'ukuran'              => $request['ukuran'],
            'lampiran'            => $request['lampiran'],
            'header'              => (int) $request['header'],
            'footer'              => (int) $request['footer'],
        ];

        // Margin
        $data['margin'] = json_encode([
            'kiri'  => (float) $request['kiri'],
            'atas'  => (float) $request['atas'],
            'kanan' => (float) $request['kanan'],
            'bawah' => (float) $request['bawah'],
        ]);

        return $data;
    }

    // Versi baru
    public function kodeIsian($id = null)
    {
        $suratMaster = FormatSurat::select(['kode_isian'])->first($id) ?? show_404();

        return view('admin.pengaturan_surat.kode_isian', compact('suratMaster'));
    }

    public function kunci($id = null, $val = 0)
    {
        $this->redirect_hak_akses('u');

        $favorit = FormatSurat::find($id) ?? show_404();
        $favorit->update(['kunci' => ($val == 1) ? 0 : 1]);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    public function favorit($id = null, $val = 0)
    {
        $this->redirect_hak_akses('u');

        $favorit = FormatSurat::find($id) ?? show_404();
        $favorit->update(['favorit' => ($val == 1) ? 0 : 1]);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    public function delete($id = null)
    {
        $this->redirect_hak_akses('h');

        if ($this->surat_master_model->delete($id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function deleteAll()
    {
        $this->redirect_hak_akses('h');

        if ($this->surat_master_model->deleteAll()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    // Tambahkan surat desa jika folder surat tidak ada di surat master
    public function perbarui()
    {
        $this->redirect_hak_akses('u', null, null, true);

        $folderSuratDesa = glob(LOKASI_SURAT_DESA . '*', GLOB_ONLYDIR);
        $daftarSurat     = [];

        if ($folderSuratDesa) {
            foreach ($folderSuratDesa as $surat) {
                $url_surat = str_replace(LOKASI_SURAT_DESA, '', $surat);

                // Hanya folder dengan nama depat surat_ yg akan di simpan
                if (preg_match('/surat_/i', $url_surat)) {
                    $surat_baru  = underscore(trim(preg_replace('/[^a-zA-Z0-9 \\_]/', ' ', $url_surat)), true, true);
                    $lokasi_baru = LOKASI_SURAT_DESA . $surat_baru;

                    // Ubah nama folder penyimpanan template surat
                    rename($surat, $lokasi_baru);

                    // Ubah nama file surat
                    rename($lokasi_baru . '/' . $url_surat . '.rtf', $lokasi_baru . '/' . $surat_baru . '.rtf');
                    rename($lokasi_baru . '/' . $url_surat . '.php', $lokasi_baru . '/' . $surat_baru . '.php');
                    rename($lokasi_baru . '/data_rtf_' . $url_surat . '.php', $lokasi_baru . '/data_rtf_' . $surat_baru . '.php');
                    rename($lokasi_baru . '/data_form_' . $url_surat . '.php', $lokasi_baru . '/data_form_' . $surat_baru . '.php');

                    if (! FormatSurat::isExist($url_surat)) {
                        $data              = [];
                        $data['jenis']     = 2;
                        $data['nama']      = ucwords(trim(str_replace(['surat_', '_'], ' ', $surat_baru)));
                        $data['url_surat'] = $surat_baru;

                        FormatSurat::insert($data);
                    }

                    $daftarSurat[] = $url_surat;
                }
            }

            // Hapus surat ubahan desa yg sudah tidak ada
            FormatSurat::where('jenis', 2)->whereNotIn('url_surat', $daftarSurat)->delete();
        }

        redirect_with('success', 'Berhasil Perbaharui Data');
    }

    public function pengaturan()
    {
        $data['pengaturanSurat'] = SettingAplikasi::whereKategori('format_surat')->pluck('value', 'key')->toArray();
        $data['alur']            = SettingAplikasi::whereKategori('alur_surat')->pluck('value', 'key')->toArray();
        $data['tte']             = SettingAplikasi::whereKategori('tte')->pluck('value', 'key')->toArray();
        $data['kades']           = User::where('active', '=', 1)->whereHas('pamong', static function ($query) {
            return $query->where('jabatan_id', '=', '1');
        })->exists();
        $data['sekdes'] = User::where('active', '=', 1)->whereHas('pamong', static function ($query) {
            return $query->where('jabatan_id', '=', '2');
        })->exists();

        $data['ref_jabatan'] = RefJabatan::all();
        $data['aksi']        = route('surat_master.update');
        $data['formAksi']    = route('surat_master.edit_pengaturan');
        $data['fonts']       = FontSuratEnum::DAFTAR;

        return view('admin.pengaturan_surat.pengaturan', $data);
    }

    public function edit_pengaturan()
    {
        $this->redirect_hak_akses('u');
        $data = $this->validasi_pengaturan($this->request);

        foreach ($data as $key => $value) {
            SettingAplikasi::whereKey($key)->update(['value' => $value]);
        }

        redirect_with('success', 'Berhasil Ubah Data');
    }

    protected static function validasi_pengaturan($request)
    {
        $validasi = [
            'tinggi_header'     => (float) $request['tinggi_header'],
            'header_surat'      => $request['header_surat'],
            'tinggi_footer'     => (float) $request['tinggi_footer'],
            'verifikasi_sekdes' => (int) $request['verifikasi_sekdes'],
            'verifikasi_kades'  => ((int) $request['tte'] == 1) ? 1 : (int) $request['verifikasi_kades'],
            'tte'               => (int) $request['tte'],
            'font_surat'        => alfanumerik_spasi($request['font_surat']),
        ];

        if ($validasi['tte'] == 1) {
            $validasi['footer_surat_tte'] = $request['footer_surat_tte'];
            $validasi['tte_api']          = alamat_web($request['tte_api']);
            $validasi['tte_username']     = $request['tte_username'];
            $validasi['tte_password']     = $request['tte_password'];
        } else {
            $validasi['footer_surat'] = $request['footer_surat'];
        }

        return $validasi;
    }

    public function kode_isian($id = null)
    {
        $log_surat['surat'] = FormatSurat::find($id);

        return json($this->tinymce->getFormatedKodeIsian($log_surat));
    }

    public function salin_template()
    {
        return json($this->tinymce->getTemplate()->merge($this->tinymce->getTemplateSurat()));
    }

    public function preview()
    {
        $setting_footer = setting('footer_surat');
        $setting_header = setting('header_surat');
        $footer         = setting('tte') == 1 ? setting('footer_surat_tte') : $setting_footer;
        $isi_surat      = preg_replace('/\\\\/', '', $setting_header) . '<!-- pagebreak -->' . ($this->request['template_desa']) . '<!-- pagebreak -->' . preg_replace('/\\\\/', '', $footer);

        // Pisahkan isian surat
        $isi_surat  = str_replace('<p><!-- pagebreak --></p>', '', $isi_surat);
        $isi        = explode('<!-- pagebreak -->', $isi_surat);
        $backtop    = (((float) setting('tinggi_header')) * 10) . 'mm';
        $backbottom = (((float) setting('tinggi_footer')) * 10) . 'mm';

        $isi_cetak = '
            <page backtop="' . $backtop . '" backbottom="' . $backbottom . '">
                <page_header>
                ' . $isi[0] . '
                </page_header>
                <page_footer>
                ' . $isi[2] . '
                </page_footer>

                ' . $isi[1] . '
            </page>
        ';

        try {
            $html2pdf = new Html2Pdf($this->request['orientasi'], $this->request['ukuran'], 'en', true, 'UTF-8', [$this->request['kiri'] * 10, $this->request['atas'] * 10, $this->request['kanan'] * 10, $this->request['bawah'] * 10]);
            $html2pdf->setTestTdInOnePage(false);
            $html2pdf->setDefaultFont(underscore(setting('font_surat'), true, true));
            $html2pdf->writeHTML($isi_cetak);
            $html2pdf->output('preview.pdf', 'D');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new ExceptionFormatter($e);
            log_message('error', $formatter->getHtmlMessage());
        }

        exit();
    }
}
