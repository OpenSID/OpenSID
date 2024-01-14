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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\SHDKEnum;
use App\Enums\StatusEnum;
use App\Libraries\TinyMCE;
use App\Models\FormatSurat;
use App\Models\KlasifikasiSurat;
use App\Models\LogSurat;
use App\Models\Penduduk;
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
        $this->load->model(['surat_master_model']);
        $this->tinymce       = new TinyMCE();
        $this->modul_ini     = 'layanan-surat';
        $this->sub_modul_ini = 'pengaturan-surat';
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
                ->addColumn('jenis', static function ($row) {
                    return in_array($row->jenis, FormatSurat::RTF) ? 'RTF' : 'TinyMCE';
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

        $this->set_hak_akses_rfm();

        if ($id) {
            $suratMaster = FormatSurat::findOrFail($id);

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
            $data['format_nomor']         = $suratMaster->format_nomor;
        }

        $data['form_isian']       = $this->form_isian();
        $data['masaBerlaku']      = FormatSurat::MASA_BERLAKU;
        $data['attributes']       = FormatSurat::ATTRIBUTES;
        $data['klasifikasiSurat'] = KlasifikasiSurat::orderBy('kode')->enabled()->get(['kode', 'nama']);
        $data['pengaturanSurat']  = SettingAplikasi::whereKategori('format_surat')->pluck('value', 'key')->toArray();

        return view('admin.pengaturan_surat.form', $data);
    }

    public function apisurat()
    {
        if ($this->input->is_ajax_request()) {
            $cari = $this->input->get('q');

            $surat = KlasifikasiSurat::select(['kode', 'nama'])
                ->when($cari, static function ($query) use ($cari) {
                    $query->orWhere('kode', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                })
                ->orderBy('kode')
                ->enabled()
                ->paginate(10);

            return json([
                'results' => collect($surat->items())
                    ->map(static function ($item) {
                        return [
                            'id'   => $item->kode,
                            'text' => $item->kode . ' - ' . $item->nama,
                        ];
                    }),
                'pagination' => [
                    'more' => $surat->currentPage() < $surat->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    private function form_isian()
    {
        return [
            'daftar_jenis_kelamin' => Sex::pluck('nama', 'id'),
            'daftar_status_dasar'  => StatusDasar::pluck('nama', 'id'),
            'daftar_shdk'          => SHDKEnum::all(),
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

        if (FormatSurat::create(static::validate($this->request))) {
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

        $data = FormatSurat::findOrFail($id);
        if ($data->update(static::validate($this->request, $data->jenis, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function update($id = null)
    {
        $this->redirect_hak_akses('u');
        $this->load->model('setting_model');

        if (! empty($this->request['surat'])) {
            $this->surat_master_model->upload($this->request['url_surat']);
        }

        $syarat  = $this->request['id_cb'];
        $mandiri = $this->request['mandiri'];
        unset($_POST['id_cb'], $_POST['tabeldata_length'], $_POST['surat']);

        $id = $this->surat_master_model->update($id);

        if (! empty($id) && $mandiri == 1) {
            FormatSurat::where('id', $id)->update(['syarat_surat' => json_encode($syarat)]);
        }

        redirect_with('success', 'Berhasil Ubah Data');
    }

    private function validate($request = [], $jenis = 4, $id = null)
    {
        $kodeIsian = null;

        for ($i = 0; $i < count($request['tipe_kode']); $i++) {
            if (empty($request['tipe_kode'][$i])) {
                continue;
            }

            $kodeIsian[] = [
                'tipe'      => $request['tipe_kode'][$i],
                'kode'      => form_kode_isian($request['nama_kode'][$i]),
                'nama'      => $request['nama_kode'][$i],
                'deskripsi' => $request['deskripsi_kode'][$i],
                'atribut'   => $request['atribut_kode'][$i] ?: null,
                'pilihan'   => null,
                'refrensi'  => null,
            ];

            if ($request['tipe_kode'][$i] == 'select-manual') {
                $kodeIsian[$i]['pilihan'] = json_decode(preg_replace('/[\r\n\t]/', '', $request['pilihan_kode'][$i]), true);
            } elseif ($request['tipe_kode'][$i] == 'select-otomatis') {
                $kodeIsian[$i]['refrensi'] = $request['referensi_kode'][$i];
            }
        }

        $formIsian = [
            'data'     => $request['data_utama'] ?? 1,
            'individu' => null,
        ];

        if ($request['data_utama'] != 2) {
            $formIsian['individu'] = [
                'sex'          => $request['individu_sex'] ?? null,
                'status_dasar' => $request['individu_status_dasar'] ?? null,
                'kk_level'     => $request['individu_kk_level'] ?? null,
            ];
        }

        $data = [
            'nama'                => nama_terbatas($request['nama']),
            'kode_surat'          => $request['kode_surat'],
            'masa_berlaku'        => $request['masa_berlaku'],
            'satuan_masa_berlaku' => $request['satuan_masa_berlaku'],
            'jenis'               => $jenis,
            'mandiri'             => $request['mandiri'],
            'syarat_surat'        => $request['mandiri'] ? json_encode($request['id_cb']) : null,
            'qr_code'             => $request['qr_code'],
            'logo_garuda'         => $request['logo_garuda'],
            'kecamatan'           => (int) ((setting('tte') == StatusEnum::YA) ? $request['kecamatan'] : 0),
            'template_desa'       => $request['template_desa'],
            'form_isian'          => json_encode($formIsian),
            'kode_isian'          => json_encode($kodeIsian),
            'orientasi'           => $request['orientasi'],
            'ukuran'              => $request['ukuran'],
            'lampiran'            => $request['lampiran'],
            'header'              => (int) $request['header'],
            'footer'              => (int) $request['footer'],
            'format_nomor'        => $request['format_nomor'],
        ];

        if (null === $id) {
            if (in_array($jenis, FormatSurat::RTF)) {
                $data['url_surat'] = unique_slug('tweb_surat_format', "surat_{$data['nama']}", $id, 'url_surat', '_');
            } else {
                $data['url_surat'] = unique_slug('tweb_surat_format', "surat-{$data['nama']}", $id, 'url_surat', '-');
            }
        }

        // Margin
        $data['margin'] = json_encode([
            'kiri'  => (float) $request['kiri'],
            'atas'  => (float) $request['atas'],
            'kanan' => (float) $request['kanan'],
            'bawah' => (float) $request['bawah'],
        ]);

        return $data;
    }

    public function kodeIsian($id = null)
    {
        $suratMaster = FormatSurat::select(['kode_isian'])->first($id) ?? show_404();

        return view('admin.pengaturan_surat.kode_isian', compact('suratMaster'));
    }

    public function kunci($id = null, $val = 0)
    {
        $this->redirect_hak_akses('u');

        $favorit = FormatSurat::findOrFail($id);
        $favorit->update(['kunci' => ($val == 1) ? 0 : 1]);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    public function favorit($id = null, $val = 0)
    {
        $this->redirect_hak_akses('u');

        $favorit = FormatSurat::findOrFail($id);
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

                        FormatSurat::create($data);
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
        $data['font_option'] = SettingAplikasi::where('key', '=', 'font_surat')->first()->option;
        $data['tte_demo']    = empty($this->setting->tte_api) || get_domain($this->setting->tte_api) === get_domain(APP_URL);
        $data['kades']       = User::where('active', '=', 1)->whereHas('pamong', static function ($query) {
            return $query->where('jabatan_id', '=', kades()->id);
        })->exists();
        $data['sekdes'] = User::where('active', '=', 1)->whereHas('pamong', static function ($query) {
            return $query->where('jabatan_id', '=', sekdes()->id);
        })->exists();
        $data['aksi']     = route('surat_master.update');
        $data['formAksi'] = route('surat_master.edit_pengaturan');

        return view('admin.pengaturan_surat.pengaturan', $data);
    }

    public function edit_pengaturan()
    {
        $this->redirect_hak_akses('u');
        $this->load->model('setting_model');
        $data = $this->validasi_pengaturan($this->request);

        foreach ($data as $key => $value) {
            SettingAplikasi::where('key', '=', $key)->update(['value' => $value]);
        }

        // upload gambar visual tte
        if ($_FILES['visual_tte_gambar'] && $_FILES['visual_tte_gambar']['name'] != '') {
            $file = $this->setting_model->upload_img('visual_tte_gambar', LOKASI_MEDIA);
            SettingAplikasi::where('key', '=', 'visual_tte_gambar')->update(['value' => $file]); //update setting
        }

        // Perbarui log_surat jika ada perubahan pengaturan verifikasi kades / sekdes
        if (! setting('verifikasi_kades') || ! setting('verifikasi_sekdes')) {
            LogSurat::where('verifikasi_operator', LogSurat::PERIKSA)->update(['verifikasi_operator' => LogSurat::TERIMA]);

            redirect_with('success', 'Berhasil Ubah Data dan Perbaharui Log Surat');
        }

        redirect_with('success', 'Berhasil Ubah Data');
    }

    protected static function validasi_pengaturan($request)
    {
        $validasi = [
            'tinggi_header'      => (float) $request['tinggi_header'],
            'header_surat'       => $request['header_surat'],
            'tinggi_footer'      => (float) $request['tinggi_footer'],
            'verifikasi_sekdes'  => (int) $request['verifikasi_sekdes'],
            'verifikasi_kades'   => ((int) $request['tte'] == StatusEnum::YA) ? StatusEnum::YA : (int) $request['verifikasi_kades'],
            'tte'                => (int) $request['tte'],
            'font_surat'         => alfanumerik_spasi($request['font_surat']),
            'visual_tte'         => (int) $request['visual_tte'],
            'visual_tte_weight'  => (int) $request['visual_tte_weight'],
            'visual_tte_height'  => (int) $request['visual_tte_height'],
            'format_nomor_surat' => $request['format_nomor_surat'],
        ];

        if ($validasi['tte'] == StatusEnum::YA) {
            $validasi['footer_surat_tte'] = $request['footer_surat_tte'];
            $validasi['tte_api']          = alamat_web($request['tte_api']);
            $validasi['tte_username']     = $request['tte_username'];
            $validasi['tte_password']     = $request['tte_password'];
        } else {
            $validasi['footer_surat'] = $request['footer_surat'];
        }

        if ($request['visual_tte_gambar'] != null) {
            $validasi['visual_tte_gambar'] = $request['visual_tte_gambar'];
        }

        return $validasi;
    }

    public function kode_isian($jenis = 'isi', $id = null)
    {
        if ($this->input->is_ajax_request()) {
            $log_surat['surat'] = FormatSurat::find($id);
            $kode_isian         = $this->tinymce->getFormatedKodeIsian($log_surat);

            return json($kode_isian);
        }

        return show_404();
    }

    public function salin_template($jenis = 'isi')
    {
        if ($this->input->is_ajax_request()) {
            if ($jenis == 'isi') {
                $template = $this->tinymce->getTemplateSurat();
            } else {
                $template = $this->tinymce->getTemplate();
            }

            return json($template);
        }

        return show_404();
    }

    public function preview()
    {
        $setting_header    = $this->request['header'] == StatusEnum::YA ? setting('header_surat') : '';
        $setting_footer    = $this->request['footer'] == StatusEnum::YA ? (setting('tte') == StatusEnum::YA ? setting('footer_surat_tte') : setting('footer_surat')) : '';
        $data['isi_surat'] = preg_replace('/\\\\/', '', $setting_header) . '<!-- pagebreak -->' . ($this->request['template_desa']) . '<!-- pagebreak -->' . preg_replace('/\\\\/', '', $setting_footer);

        if ($this->request['data_utama'] == 1) {
            $data['id_pend'] = Penduduk::filters([
                'sex'          => $this->request['individu_sex'],
                'status_dasar' => $this->request['individu_status_dasar'],
                'kk_level'     => $this->request['individu_kk_level'],
            ])->first('id')->id;

            if (! $data['id_pend']) {
                redirect_with('error', 'Tidak ditemukan penduduk untuk dijadikan contoh');
            }
        } else {
            $data['nik_non_warga']  = '1234567890123456';
            $data['nama_non_warga'] = 'Nama Non Warga';
        }

        for ($i = 0; $i < count($this->request['tipe_kode']); $i++) {
            if (empty($this->request['tipe_kode'][$i])) {
                continue;
            }

            $kode = $this->request['nama_kode'][$i];

            if ($this->request['tipe_kode'][$i] == 'select-manual') {
                $pilihan    = json_decode(preg_replace('/[\r\n\t]/', '', $this->request['pilihan_kode'][$i]), true);
                $kode_isian = $pilihan[array_rand($pilihan)];
            } elseif ($this->request['tipe_kode'][$i] == 'select-otomatis') {
                $pilihan    = ref($this->request['referensi_kode'][$i]);
                $kode_isian = $pilihan[array_rand($pilihan)]->nama;
            } else {
                $kode_isian = 'Masukkan ' . $kode;
            }

            $data = case_replace(form_kode_isian($kode), $kode_isian, $data);
        }

        $data      = str_replace('[JUdul_surat]', strtoupper($this->request['nama']), $data);
        $isi_surat = $this->tinymce->replceKodeIsian($data);

        // Manual replace kode isian non warga
        $isi_surat = str_replace('[Form_nik_non_wargA]', $data['nik_non_warga'], $isi_surat);
        $isi_surat = str_replace('[Form_nama_non_wargA]', $data['nama_non_warga'], $isi_surat);

        // Pisahkan isian surat
        $isi_surat  = str_replace('<p><!-- pagebreak --></p>', '', $isi_surat);
        $isi        = explode('<!-- pagebreak -->', $isi_surat);
        $backtop    = $this->request['header'] == 0 ? 0 : (((float) setting('tinggi_header')) * 10) . 'mm';
        $backbottom = $this->request['footer'] == 0 ? 0 : (((float) setting('tinggi_footer')) * 10) . 'mm';

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

        // Logo Surat
        $file_logo = ($this->request['logo_garuda'] ? FCPATH . LOGO_GARUDA : gambar_desa(identitas()->logo, false, true));

        $logo      = (is_file($file_logo)) ? '<img src="' . $file_logo . '" width="90" height="90" alt="logo-surat" />' : '';
        $logo_bsre = str_replace('[logo]', $logo, $isi_cetak);

        // Logo BSrE
        $file_logo_bsre = FCPATH . LOGO_BSRE;
        $bsre           = (is_file($file_logo_bsre) && setting('tte') == 1) ? '<img src="' . $file_logo_bsre . '" height="90" alt="logo-bsre" />' : '';
        $logo_qrcode    = str_replace('[logo_bsre]', $bsre, $logo_bsre);

        // QrCode
        $file_qrcode   = FCPATH . GAMBAR_QRCODE;
        $qrcode        = (is_file($file_qrcode)) ? '<img src="' . $file_qrcode . '" width="90" height="90" alt="logo-surat" />' : '';
        $gambar_qecode = str_replace('[qr_code]', $qrcode, $logo_qrcode);

        try {
            $html2pdf = new Html2Pdf($this->request['orientasi'], $this->request['ukuran'], 'en', true, 'UTF-8', [$this->request['kiri'] * 10, $this->request['atas'] * 10, $this->request['kanan'] * 10, $this->request['bawah'] * 10]);
            $html2pdf->pdf->SetTitle($this->request['nama'] . ' (Pratinjau)');
            $html2pdf->setTestTdInOnePage(false);
            $html2pdf->setDefaultFont(underscore(setting('font_surat'), true, true));
            $html2pdf->writeHTML($gambar_qecode);
            $html2pdf->output(tempnam(sys_get_temp_dir(), '') . '.pdf', 'FI');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new ExceptionFormatter($e);
            log_message('error', $formatter->getHtmlMessage());
        }

        exit();
    }

    public function ekspor()
    {
        $this->redirect_hak_akses('u');

        $id = $this->request['id_cb'];

        if (null === $id) {
            redirect_with('error', 'Tidak ada surat yang dipilih.');
        }

        $ekspor = FormatSurat::jenis(FormatSurat::TINYMCE)->whereIn('id', $id)->get();

        if ($ekspor->count() === 0) {
            redirect_with('error', 'Tidak ada surat TinyMCE yang ditemukan dari pilihan anda.');
        }

        $file_name = namafile('Template Surat TInyMCE') . '.json';

        $this->output
            ->set_header("Content-Disposition: attachment; filename={$file_name}")
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($ekspor, JSON_PRETTY_PRINT));
    }

    public function impor()
    {
        $this->redirect_hak_akses('u');

        $this->load->library('upload');

        $config['upload_path']   = sys_get_temp_dir();
        $config['allowed_types'] = 'json';
        $config['overwrite']     = true;
        $config['max_size']      = max_upload() * 1024;
        $config['file_name']     = 'template_surat_tinymce.json';

        $this->upload->initialize($config);

        if ($this->upload->do_upload('userfile')) {
            $list_data = file_get_contents($this->upload->data()['full_path']);

            $list_data = collect(json_decode($list_data, true))->map(static function ($item) {
                return [
                    'nama'                => $item['nama'],
                    'url_surat'           => $item['url_surat'],
                    'kode_surat'          => $item['kode_surat'],
                    'lampiran'            => $item['lampiran'],
                    'kunci'               => $item['kunci'] ? StatusEnum::YA : StatusEnum::TIDAK,
                    'favorit'             => $item['favorit'] ? StatusEnum::YA : StatusEnum::TIDAK,
                    'jenis'               => $item['jenis'],
                    'mandiri'             => $item['mandiri'] ? StatusEnum::YA : StatusEnum::TIDAK,
                    'masa_berlaku'        => $item['masa_berlaku'],
                    'satuan_masa_berlaku' => $item['satuan_masa_berlaku'],
                    'qr_code'             => $item['qr_code'] ? StatusEnum::YA : StatusEnum::TIDAK,
                    'logo_garuda'         => $item['logo_garuda'] ? StatusEnum::YA : StatusEnum::TIDAK,
                    'syarat_surat'        => json_decode($item['syarat_surat'], true),
                    'template'            => $item['template'],
                    'template_desa'       => $item['template_desa'],
                    'form_isian'          => json_encode($item['form_isian']),
                    'kode_isian'          => json_encode($item['kode_isian']),
                    'orientasi'           => $item['orientasi'],
                    'ukuran'              => $item['ukuran'],
                    'margin'              => $item['margin'],
                    'footer'              => $item['footer'],
                    'header'              => $item['header'],
                    'created_at'          => date('Y-m-d H:i:s'),
                    'creted_by'           => auth()->id,
                    'updated_at'          => date('Y-m-d H:i:s'),
                    'updated_by'          => auth()->id,
                ];
            })->toArray();

            if ($list_data) {
                foreach ($list_data as $value) {
                    FormatSurat::updateOrCreate(['url_surat' => $value['url_surat']], $value);
                }
            }

            redirect_with('success', 'Berhasil Impor Data');
        }

        redirect_with('error', 'Gagal Impor Data<br/>' . $this->upload->display_errors());
    }

    // Hanya untuk develpment
    public function migrasi()
    {
        if (ENVIRONMENT !== 'development') {
            redirect_with('error', 'Hanya untuk development');
        }

        $simpan = FormatSurat::updateOrCreate(['id' => $this->request['id_surat']], static::validate($this->request));

        // Pilih surat yang akan dibuat migrasinya
        $surat = FormatSurat::jenis(FormatSurat::TINYMCE)->find($simpan->id);

        $nama_fuction = 'surat' . str_replace(' ', '', ucwords(str_replace('_', ' ', $surat->nama)));

        $kode_isian     = json_encode($surat->kode_isian);
        $form_isian     = json_encode($surat->form_isian);
        $template_surat = str_replace(['\/', '\u00a0'], ['/', ' '], json_encode($surat->template_desa ?? $surat->template));
        $qr_code        = getVariableName(StatusEnum::class, $surat->qr_code);
        $mandiri        = getVariableName(StatusEnum::class, $surat->mandiri);
        $syarat_surat   = $surat->syarat_surat ?: 'null';
        $lampiran       = $surat->lampiran ?: 'null';

        $import = <<<'EOS'
            use App\Enums\StatusEnum;
            EOS;

        $get_fuction = <<<EOS
            \$hasil = \$hasil && \$this->{$nama_fuction}(\$hasil, \$id);
                        // Jalankan Migrasi TinyMCE'
            EOS;

        $set_fuction = <<<EOS
            protected function {$nama_fuction}(\$hasil, \$id)
                {
                    \$data = [
                        'nama'                => '{$surat->nama}',
                        'kode_surat'          => '{$surat->kode_surat}',
                        'masa_berlaku'        => {$surat->masa_berlaku},
                        'satuan_masa_berlaku' => '{$surat->satuan_masa_berlaku}',
                        'orientasi'           => '{$surat->orientasi}',
                        'ukuran'              => '{$surat->ukuran}',
                        'margin'              => '{$surat->margin}',
                        'qr_code'             => StatusEnum::{$qr_code},
                        'kode_isian'          => '{$kode_isian}',
                        'form_isian'          => '{$form_isian}',
                        'mandiri'             => StatusEnum::{$mandiri},
                        'syarat_surat'        => {$syarat_surat},
                        'lampiran'            => {$lampiran},
                        'template'            => {$template_surat},
                    ];

                    return \$hasil && \$this->tambah_surat_tinymce(\$data, \$id);
                }

                // Function Migrasi TinyMCE
            EOS;

        $file_migrasi = nextVersion();

        // tentukan migrasi
        $migrasi = file_get_contents(APPPATH . 'models/migrations/Migrasi_fitur_premium_' . $file_migrasi . '.php');
        $migrasi = str_replace([
            '// Import TinyMCE',
            '// Jalankan Migrasi TinyMCE',
            '// Function Migrasi TinyMCE',
        ], [
            $import,
            $get_fuction,
            $set_fuction,
        ], $migrasi);
        file_put_contents(APPPATH . 'models/migrations/Migrasi_fitur_premium_' . $file_migrasi . '.php', $migrasi);

        if ($simpan) {
            redirect_with('success', 'Berhasil Simpan Data Sementara', 'surat_master/form/' . $simpan->id);
        }

        redirect_with('error', 'Gagal Simpan Data');
    }
}
