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

use App\Enums\SHDKEnum;
use App\Enums\StatusEnum;
use App\Libraries\TinyMCE;
use App\Models\AliasKodeIsian;
use App\Models\FormatSurat;
use App\Models\KlasifikasiSurat;
use App\Models\LogSurat;
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
    public $modul_ini     = 'layanan-surat';
    public $sub_modul_ini = 'pengaturan-surat';
    protected TinyMCE $tinymce;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->tinymce = new TinyMCE();
        $this->load->library('MY_Upload', null, 'upload');
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
            return datatables((new FormatSurat())->jenis($this->input->get('jenis')))
                ->addIndexColumn()
                ->addColumn('ceklist', static fn ($row): string => '<input type="checkbox" name="id_cb[]" value="' . $row->id . '" />')
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("surat_master/form/{$row->id}") . '" class="btn btn-warning btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a> ';

                        if ($row->kunci) {
                            $aksi .= '<a href="' . site_url("surat_master/kunci/{$row->id}") . '" class="btn bg-navy btn-sm" title="Aktifkan Surat"><i class="fa fa-lock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . site_url("surat_master/kunci/{$row->id}") . '" class="btn bg-navy btn-sm" title="Nonaktifkan Surat"><i class="fa fa-unlock"></i></a> ';

                            if ($row->favorit) {
                                $aksi .= '<a href="' . site_url("surat_master/favorit/{$row->id}") . '" class="btn bg-purple btn-sm" title="Keluarkan dari Daftar Favorit"><i class="fa fa-star"></i></a> ';
                            } else {
                                $aksi .= '<a href="' . site_url("surat_master/favorit/{$row->id}") . '" class="btn bg-purple btn-sm" title="Tambahkan ke Daftar Favorit"><i class="fa fa-star-o"></i></a> ';
                            }
                        }
                    }

                    if (can('h') && ($row->jenis === FormatSurat::TINYMCE_DESA)) {
                        $aksi .= '<a href="#" data-href="' . site_url("surat_master/delete/{$row->id}") . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('lampiran', static fn ($row): string => kode_format($row->lampiran))
                ->rawColumns(['ceklist', 'aksi', 'template_surat'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        isCan('u');
        $this->set_hak_akses_rfm();

        $data['action']      = $id ? 'Ubah' : 'Tambah';
        $data['formAction']  = $id ? ci_route('surat_master.update', $id) : ci_route('surat_master.insert');
        $data['suratMaster'] = $id ? FormatSurat::findOrFail($id) : null;

        if ($id) {
            $kategori_isian = [];
            // hanya ambil key saja
            $data['kategori_nama'] = collect(get_key_form_kategori($data['suratMaster']->form_isian))->keys()->toArray();
            $data['kategori']      = collect(get_key_form_kategori($data['suratMaster']->form_isian))->toArray();

            collect($data['suratMaster']->kode_isian)->filter(static function ($item) use (&$kategori_isian): bool {
                if (isset($item->kategori)) {
                    $item->kategori                                = strtolower($item->kategori);
                    $kategori_isian[strtolower($item->kategori)][] = $item;

                    return true;
                }

                return false;
            })->values();

            $data['kategori_isian'] = $kategori_isian;
            $data['kode_isian']     = collect($data['suratMaster']->kode_isian)->reject(static fn ($item): bool => isset($item->kategori))->values();

            $data['klasifikasiSurat'] = KlasifikasiSurat::where('kode', $data['suratMaster']->kode_surat)->first();

            $data['formAction'] = ci_route('surat_master.update', $id);
        }

        $data['margins']              = json_decode($data['suratMaster']->margin, null) ?? FormatSurat::MARGINS;
        $data['margin_global']        = $data['suratMaster']->margin_global ?? StatusEnum::YA;
        $data['orientations']         = FormatSurat::ORIENTATAIONS;
        $data['sizes']                = FormatSurat::SIZES;
        $data['default_orientations'] = FormatSurat::DEFAULT_ORIENTATAIONS;
        $data['default_sizes']        = FormatSurat::DEFAULT_SIZES;
        $data['header']               = $data['suratMaster']->header ?? StatusEnum::YA;
        $data['footer']               = $data['suratMaster']->footer ?? StatusEnum::YA;
        $data['daftar_lampiran']      = $this->tinymce->getDaftarLampiran();
        $data['format_nomor']         = $data['suratMaster']->format_nomor;
        $data['format_nomor_global']  = $data['suratMaster']->format_nomor_global ?? StatusEnum::YA;
        $data['form_isian']           = $this->form_isian();
        $data['simpan_sementara']     = site_url('surat_master/simpan_sementara');
        $data['masaBerlaku']          = FormatSurat::MASA_BERLAKU;
        $data['attributes']           = FormatSurat::ATTRIBUTES;
        $data['pendudukLuar']         = json_decode(SettingAplikasi::where('key', 'form_penduduk_luar')->first()->value ?? [], true);

        return view('admin.pengaturan_surat.form', $data);
    }

    public function apisurat()
    {
        if ($this->input->is_ajax_request()) {
            $cari = $this->input->get('q');

            $surat = KlasifikasiSurat::select(['kode', 'nama'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->orWhere('kode', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                })
                ->orderBy('kode')
                ->enabled()
                ->paginate(10);

            return json([
                'results' => collect($surat->items())
                    ->map(static fn ($item): array => [
                        'id'   => $item->kode,
                        'text' => $item->kode . ' - ' . $item->nama,
                    ]),
                'pagination' => [
                    'more' => $surat->currentPage() < $surat->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    private function form_isian(): array
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
                ->addColumn('ceklist', static function ($row) use ($suratMaster): string {
                    $checked = in_array($row->ref_syarat_id, json_decode($suratMaster->syarat_surat, null) ?? []) ? 'checked' : '';

                    return '<input type="checkbox" name="id_cb[]" value="' . $row->ref_syarat_id . '" ' . $checked . ' />';
                })
                ->addIndexColumn()
                ->rawColumns(['ceklist'])
                ->make();
        }

        return show_404();
    }

    public function insert(): void
    {
        isCan('u');

        if ($this->request['action'] == 'preview') {
            $this->preview();
        }

        $this->checkTags($this->request['template_desa']);

        if (FormatSurat::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function simpan_sementara(): void
    {
        isCan('u');
        $id = $this->request['id_surat'] ?: null;
        $this->checkTags($this->request['template_desa'], $id);

        $cek_surat = FormatSurat::find($id);

        $surat = FormatSurat::updateOrCreate(['id' => $id, 'config_id' => identitas('id')], static::validate($this->request, $cek_surat->jenis ?? 4, $id));
        if ($surat) {
            redirect_with('success', 'Berhasil Simpan Data Sementara', 'surat_master/form/' . $surat->id);
        }

        redirect_with('error', 'Gagal Simpan Data');
    }

    public function update($id = null): void
    {
        isCan('u');

        if ($this->request['action'] == 'preview') {
            $this->preview();
        }

        $this->checkTags($this->request['template_desa'], $id);

        $data = FormatSurat::findOrFail($id);

        if ($data->update(static::validate($this->request, $data->jenis, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    private function checkTags($template_desa, $id = null): void
    {
        $invalid_tags = invalid_tags();

        foreach ($invalid_tags as $invalid_tag) {
            if (strpos($template_desa, (string) $invalid_tag) !== false) {
                redirect_with('error', 'Template surat Tidak Valid', 'surat_master/form/' . $id);
            }
        }
    }

    private function validate($request = [], $jenis = 4, $id = null): array
    {
        // fix bagian key select-manual
        $kodeIsian   = null;
        $manual_data = array_values(array_filter($request['pilihan_kode'] ?? []));
        if ($manual_data !== []) {
            $data    = [];
            $no      = 0;
            $counter = count($request['tipe_kode'] ?? []);

            for ($i = 0; $i < $counter; $i++) {
                if ($request['tipe_kode'][$i] == 'select-manual') {
                    $data[$i] = $manual_data[$no++];
                }
            }
        }
        $counter = count($request['tipe_kode'] ?? []);

        for ($i = 0; $i < $counter; $i++) {
            if (empty($request['tipe_kode'][$i])) {
                continue;
            }
            if (empty($request['nama_kode'][$i])) {
                continue;
            }
            $kodeIsian[] = [
                'tipe'         => $request['tipe_kode'][$i],
                'kode'         => form_kode_isian($request['nama_kode'][$i]),
                'nama'         => $request['nama_kode'][$i],
                'deskripsi'    => $request['deskripsi_kode'][$i],
                'required'     => $request['required_kode'][$i] ?? '0',
                'atribut'      => $request['atribut_kode'][$i] ?: null,
                'pilihan'      => null,
                'refrensi'     => null,
                'kolom'        => $request['kolom'][$i] ?? '',
                'label'        => $request['label_kode'][$i] ?? '',
                'kaitkan_kode' => $request['kaitkan_kode'][$i] ?? '',
            ];

            if ($request['tipe_kode'][$i] == 'select-manual') {
                $kodeIsian[$i]['pilihan'] = $data[$i];
            } elseif ($request['tipe_kode'][$i] == 'select-otomatis') {
                $kodeIsian[$i]['refrensi'] = $request['referensi_kode'][$i];
            }
        }

        // TODO:: Gabungkan kategori individu dengan kategori lainnya, jika individu hilangkan prefix kategorinya (individu)
        $formIsian = [
            'individu' => [
                'sumber'         => 1, // sumber data untuk individu (utama) harus ada
                'data'           => $request['data_utama'] ?? [1],
                'sex'            => $request['individu_sex'] ?? null,
                'status_dasar'   => $request['individu_status_dasar'] ?? null,
                'kk_level'       => $request['individu_kk_level'] ?? null,
                'data_orang_tua' => $request['data_orang_tua'] ?? 0,
                'data_pasangan'  => $request['data_pasangan'] ?? 0,
                'judul'          => $request['judul'] ?? 'Utama',
                'label'          => $request['label'] ?? '',
                'info'           => $request['info'] ?? '',
                'sebagai'        => 1, // sebagai untuk individu (utama) harus ada
                'hubungan'       => null,
            ],
        ];

        if (isset($request['kategori'])) {
            foreach ($request['kategori'] as $kategori) {
                $formIsian[$kategori] = [
                    'sumber'       => (int) $request['kategori_sumber'][$kategori] ?? 1,
                    'data'         => $request['kategori_data_utama'][$kategori] ?? [1],
                    'sex'          => $request['kategori_individu_sex'][$kategori] ?? null,
                    'status_dasar' => $request['kategori_individu_status_dasar'][$kategori] ?? null,
                    'kk_level'     => $request['kategori_individu_kk_level'][$kategori] ?? null,
                    'judul'        => $request['kategori_judul'][$kategori] ?? null,
                    'label'        => $request['kategori_label'][$kategori] ?? null,
                    'info'         => $request['kategori_info'][$kategori] ?? null,
                    'sebagai'      => (int) $request['kategori_sebagai'][$kategori] ?? 0,
                    'hubungan'     => $request['kategori_hubungan'][$kategori] ?? null,
                    // 'data_orang_tua' => $request['kategori_data_orang_tua'] ?? 0,
                    // 'data_pasangan'  => $request['kategori_data_pasangan'] ?? 0,
                ];
                $manual_data = array_values(array_filter($request['kategori_pilihan_kode'][$kategori] ?? []));
                if ($manual_data !== []) {
                    $data    = [];
                    $no      = 0;
                    $counter = count($request['kategori_tipe_kode'][$kategori] ?? []);

                    for ($i = 0; $i < $counter; $i++) {
                        if ($request['kategori_tipe_kode'][$kategori][$i] == 'select-manual') {
                            $data[$i] = $manual_data[$no];
                            // benerin data key nya mungkin
                            $no++;
                        }
                    }
                }
                $counter = count($request['kategori_tipe_kode'][$kategori] ?? []);

                for ($i = 0; $i < $counter; $i++) {
                    if (empty($request['kategori_tipe_kode'][$kategori][$i])) {
                        continue;
                    }
                    if (empty($request['kategori_nama_kode'][$kategori][$i])) {
                        continue;
                    }
                    $kategori_isian = [
                        'kategori'     => $kategori,
                        'tipe'         => $request['kategori_tipe_kode'][$kategori][$i],
                        'kode'         => form_kode_isian($request['kategori_nama_kode'][$kategori][$i]),
                        'nama'         => $request['kategori_nama_kode'][$kategori][$i],
                        'deskripsi'    => $request['kategori_deskripsi_kode'][$kategori][$i],
                        'required'     => $request['kategori_required_kode'][$kategori][$i] ?? '0',
                        'atribut'      => $request['kategori_atribut_kode'][$kategori][$i] ?: null,
                        'pilihan'      => null,
                        'refrensi'     => null,
                        'kolom'        => $request['kategori_kolom'][$kategori][$i] ?? '',
                        'label'        => $request['kategori_label_kode'][$kategori][$i] ?? '',
                        'kaitkan_kode' => $request['kategori_kaitkan_kode'][$kategori][$i] ?? '',
                    ];

                    if ($request['kategori_tipe_kode'][$kategori][$i] == 'select-manual') {
                        $kategori_isian['pilihan'] = $data[$i];
                    } elseif ($request['kategori_tipe_kode'][$kategori][$i] == 'select-otomatis') {
                        $kategori_isian['refrensi'] = $request['kategori_referensi_kode'][$kategori][$i];
                    }
                    $kodeIsian[] = $kategori_isian;
                }
                unset($data);
            }
        }

        $data = [
            'config_id'                => identitas('id'),
            'nama'                     => nama_surat($request['nama']),
            'kode_surat'               => $request['kode_surat'],
            'masa_berlaku'             => $request['masa_berlaku'],
            'satuan_masa_berlaku'      => $request['satuan_masa_berlaku'],
            'jenis'                    => $jenis,
            'mandiri'                  => (collect($formIsian)->where('sumber', '1')->count() == 1) && ($request['mandiri'] == 1),
            'syarat_surat'             => $request['mandiri'] ? json_encode($request['id_cb']) : null,
            'qr_code'                  => $request['qr_code'],
            'logo_garuda'              => $request['logo_garuda'],
            'kecamatan'                => (int) ((setting('tte') == StatusEnum::YA) ? $request['kecamatan'] : 0),
            'template_desa'            => $request['template_desa'],
            'form_isian'               => json_encode($formIsian, JSON_THROW_ON_ERROR),
            'kode_isian'               => json_encode($kodeIsian, JSON_THROW_ON_ERROR),
            'orientasi'                => $request['orientasi'],
            'ukuran'                   => $request['ukuran'],
            'lampiran'                 => is_array($request['lampiran']) ? implode(',', $request['lampiran']) : $request['lampiran'],
            'header'                   => (int) $request['header'],
            'footer'                   => (int) $request['footer'],
            'format_nomor'             => $request['format_nomor'],
            'format_nomor_global'      => (int) $request['format_nomor_global'],
            'sumber_penduduk_berulang' => $request['sumber_penduduk_berulang'],
        ];

        if (null === $id) {
            $data['url_surat'] = unique_slug('tweb_surat_format', "surat-{$data['nama']}", $id, 'url_surat', '-');
        }

        // Margin
        $data['margin_global'] = $request['margin_global'] == 1 ? 1 : 0;
        $data['margin']        = json_encode([
            'kiri'  => (float) $request['kiri'],
            'atas'  => (float) $request['atas'],
            'kanan' => (float) $request['kanan'],
            'bawah' => (float) $request['bawah'],
        ], JSON_THROW_ON_ERROR);

        return $data;
    }

    public function kodeIsian($id = null)
    {
        $suratMaster = FormatSurat::select(['kode_isian'])->first($id) ?? show_404();

        return view('admin.pengaturan_surat.kode_isian', ['suratMaster' => $suratMaster]);
    }

    public function kunci($id = null): void
    {
        isCan('u');

        if (FormatSurat::gantiStatus($id, 'kunci')) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function favorit($id = null): void
    {
        isCan('u');

        if (FormatSurat::gantiStatus($id, 'favorit')) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id): void
    {
        isCan('h');
        $surat = FormatSurat::findOrFail($id);

        if ($surat->jenis !== FormatSurat::TINYMCE_DESA) {
            redirect_with('error', 'Gagal Hapus Data, Surat Bawaan Sistem Tidak Dapat Dihapus');
        }

        if ($surat->delete($id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function delete_all(): void
    {
        isCan('h');

        foreach ($this->request['id_cb'] as $id) {
            $this->delete($id);
        }
    }

    public function restore_surat_bawaan($url_surat = ''): void
    {
        $cek_surat = FormatSurat::where('url_surat', $url_surat);
        $ada_surat = $cek_surat->first() ?? show_404();

        if (super_admin() && $ada_surat) {
            $list_data = file_get_contents('assets/import/template_surat_tinymce.json');
            $list_data = collect(json_decode($list_data, true))
                ->where('url_surat', $url_surat)
                ->map(static fn ($item) => collect($item)->except('id', 'config_id', 'url_surat', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'judul_surat', 'margin_cm_to_mm', 'url_surat_sistem', 'url_surat_desa')->toArray())
                ->first();

            if ($list_data && $cek_surat->update($list_data)) {
                redirect_with('success', 'Berhasil Mengembalikan Surat Bawaan/Sistem', 'surat_master/form/' . $ada_surat->id);
            }
        }

        redirect_with('error', 'Gagal Mengembalikan Surat Bawaan/Sistem', 'surat_master/form/' . $ada_surat->id);
    }

    public function pengaturan()
    {
        $this->set_hak_akses_rfm();
        $data['font_option']   = SettingAplikasi::where('key', '=', 'font_surat')->first()->option;
        $data['tte_demo']      = empty($this->setting->tte_api) || get_domain($this->setting->tte_api) === get_domain(APP_URL);
        $data['kades']         = User::where('active', '=', 1)->whereHas('pamong', static fn ($query) => $query->where('jabatan_id', '=', kades()->id))->exists();
        $data['sekdes']        = User::where('active', '=', 1)->whereHas('pamong', static fn ($query) => $query->where('jabatan_id', '=', sekdes()->id))->exists();
        $data['aksi']          = ci_route('surat_master.update');
        $data['formAksi']      = ci_route('surat_master.edit_pengaturan');
        $margin                = setting('surat_margin');
        $data['margins']       = json_decode($margin, null) ?? FormatSurat::MARGINS;
        $data['penduduk_luar'] = json_decode(SettingAplikasi::where('key', '=', 'form_penduduk_luar')->first()->value, true);
        $data['alias']         = AliasKodeIsian::get();

        return view('admin.pengaturan_surat.pengaturan', $data);
    }

    public function edit_pengaturan(): void
    {
        isCan('u');
        $this->load->model('setting_model');
        $data = static::validasi_pengaturan($this->request);

        if (! empty($_FILES['font_custom']['name'])) {
            $this->upload->initialize([
                'file_name'     => $_FILES['font_custom']['name'],
                'upload_path'   => LOKASI_FONT_DESA,
                'allowed_types' => 'ttf',
                'max_size'      => 2048,
                'overwrite'     => true,
            ]);

            if ($this->upload->do_upload('font_custom')) {
                $font = TCPDF_FONTS::addTTFfont(
                    $this->upload->data('full_path'),
                    '',
                    '',
                    32,
                    realpath(LOKASI_FONT_DESA) . DIRECTORY_SEPARATOR
                );

                if ($font) {
                    // Merge font yang sudah di tambahkan ke option setting.
                    $font_surat         = SettingAplikasi::where('key', 'font_surat')->first();
                    $font_surat->option = array_unique(array_merge($font_surat->option, [$font]));
                    $font_surat->save();

                    rename($this->upload->data('full_path'), LOKASI_FONT_DESA . "{$font}.ttf");
                }
            } else {
                redirect_with('error', $this->upload->display_errors());
            }
        }

        foreach ($data as $key => $value) {
            SettingAplikasi::where('key', '=', $key)->update(['value' => $value]);
        }

        // upload gambar visual tte
        if ($_FILES['visual_tte_gambar'] && $_FILES['visual_tte_gambar']['name'] != '') {
            $file = $this->setting_model->upload_img('visual_tte_gambar', LOKASI_MEDIA);
            $file ? SettingAplikasi::where('key', '=', 'visual_tte_gambar')->update(['value' => $file]) : redirect_with('error', $this->upload->display_errors(null, null));
        }

        if ($data['kodeisian_alias']) {
            $judulAlias   = $data['kodeisian_alias']['judul'];
            $contentAlias = $data['kodeisian_alias']['content'];
            AliasKodeIsian::whereNotIn('judul', $data['kodeisian_alias']['judul'])->delete();

            foreach ($data['kodeisian_alias']['alias'] as $index => $alias) {
                // observer gak jalan ketika menggunakan upsert
                AliasKodeIsian::upsert(['updated_by' => auth()->id, 'config_id' => identitas('id'), 'judul' => $judulAlias[$index], 'alias' => $alias, 'content' => $contentAlias[$index]], ['config_id', 'judul']);
            }
        } else {
            AliasKodeIsian::whereConfigId(identitas('id'))->delete();
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
            'tinggi_header'        => (float) $request['tinggi_header'],
            'header_surat'         => $request['header_surat'],
            'tinggi_footer'        => (float) $request['tinggi_footer'],
            'verifikasi_sekdes'    => (int) $request['verifikasi_sekdes'],
            'verifikasi_kades'     => ((int) $request['tte'] == StatusEnum::YA) ? StatusEnum::YA : (int) $request['verifikasi_kades'],
            'tte'                  => (int) $request['tte'],
            'font_surat'           => alfanumerik_spasi($request['font_surat']),
            'visual_tte'           => (int) $request['visual_tte'],
            'visual_tte_weight'    => (int) $request['visual_tte_weight'],
            'visual_tte_height'    => (int) $request['visual_tte_height'],
            'format_nomor_surat'   => $request['format_nomor_surat'],
            'ganti_data_kosong'    => $request['ganti_data_kosong'],
            'format_tanggal_surat' => $request['format_tanggal_surat'],
            'surat_margin'         => json_encode($request['surat_margin'], JSON_THROW_ON_ERROR),
            'form_penduduk_luar'   => json_encode(updateIndex($request['penduduk_luar']), JSON_THROW_ON_ERROR),
            'kodeisian_alias'      => $request['alias_kodeisian'] ? ['judul' => $request['judul_kodeisian'], 'alias' => $request['alias_kodeisian'], 'content' => $request['content_kodeisian']] : null,
        ];

        if ($validasi['tte'] == StatusEnum::YA) {
            $validasi['footer_surat_tte'] = $request['footer_surat_tte'];
            $validasi['tte_api']          = alamat_web($request['tte_api']);
            $validasi['tte_username']     = $request['tte_username'];
            if ($request['tte_password'] != '') {
                $validasi['tte_password'] = $request['tte_password'];
            }
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
            $template = $jenis == 'isi' ? $this->tinymce->getTemplateSurat() : $this->tinymce->getTemplate();

            return json($template);
        }

        return show_404();
    }

    public function preview(): void
    {
        // konversi request agar formatnya sama
        $request             = static::validate($this->request);
        $request['id_surat'] = $this->request['id_surat'] ?? null;

        $isi_cetak = $this->tinymce->getPreview($request);

        // Ubah jadi format pdf
        $pages = $this->tinymce->generateMultiPage($isi_cetak);

        $isi_cetak = $this->tinymce->formatPdf($this->request['header'], $this->request['footer'], implode("<div style=\"page-break-after: always;\">\u{a0}</div>", $pages));

        if ($this->request['margin_global'] == 1) {
            $margins = setting('surat_margin_cm_to_mm');
        } else {
            $margins = [
                $this->request['kiri'] * 10,
                $this->request['atas'] * 10,
                $this->request['kanan'] * 10,
                $this->request['bawah'] * 10,
            ];
        }

        try {
            $html2pdf = new Html2Pdf($this->request['orientasi'], $this->request['ukuran'], 'en', true, 'UTF-8', $margins);
            $html2pdf->pdf->SetTitle($this->request['nama'] . ' (Pratinjau)');
            $html2pdf->setTestTdInOnePage(false);
            $html2pdf->setDefaultFont(underscore(setting('font_surat'), true, true));
            $html2pdf->writeHTML($isi_cetak);
            $html2pdf->output(tempnam(sys_get_temp_dir(), '') . '.pdf', 'FI');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new ExceptionFormatter($e);
            log_message('error', $formatter->getHtmlMessage());
            log_message('error', 'belum redirect');
            header('HTTP/1.0 404 ' . str_replace("\n", ' ', $formatter->getMessage()));

            exit();
        }
    }

    public function ekspor(): void
    {
        isCan('u');

        $id = $this->request['id_cb'];

        if (null === $id) {
            redirect_with('error', 'Tidak ada surat yang dipilih.');
        }

        $ekspor = FormatSurat::jenis(FormatSurat::TINYMCE)->whereIn('id', $id)->latest('id')->get();

        if ($ekspor->count() === 0) {
            redirect_with('error', 'Tidak ada surat TinyMCE yang ditemukan dari pilihan anda.');
        }

        $file_name = namafile('Template Surat TInyMCE') . '.json';
        $ekspor    = $ekspor->map(static fn ($item) => collect($item)->except('id', 'config_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'judul_surat', 'margin_cm_to_mm', 'url_surat_sistem', 'url_surat_desa')->toArray())->toArray();

        $this->output
            ->set_header("Content-Disposition: attachment; filename={$file_name}")
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($ekspor, JSON_PRETTY_PRINT));
    }

    public function impor_filter($data)
    {
        set_session('data_impor_surat', $data);

        return view('admin.pengaturan_surat.impor_select', [
            'data' => $data,
        ]);
    }

    public function impor_store(): void
    {
        isCan('u');

        $id = $this->request['id_cb'];

        if (null === $id) {
            redirect_with('error', 'Tidak ada surat yang dipilih.');
        }

        $this->prosesImport(session('data_impor_surat'), $id);

        redirect_with('success', 'Berhasil Impor Data');
    }

    public function impor(): void
    {
        isCan('u');
        $config['upload_path']   = sys_get_temp_dir();
        $config['allowed_types'] = 'json';
        $config['overwrite']     = true;
        $config['max_size']      = max_upload() * 1024;
        $config['file_name']     = time() . '_template_surat_tinymce.json';

        $this->upload->initialize($config);

        if ($this->upload->do_upload('userfile')) {
            $list_data = $this->formatImport(file_get_contents($this->upload->data()['full_path']));
            if ($list_data) {
                $this->impor_filter($list_data);
            }
        }

        redirect_with('error', 'Gagal Impor Data<br/>' . $this->upload->display_errors());
    }

    private function getTemplate($jenis = FormatSurat::TINYMCE)
    {
        return FormatSurat::jenis($jenis)
            ->latest('id')
            ->get()
            ->map(static fn ($item) => collect($item)->except('id', 'config_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'judul_surat', 'margin_cm_to_mm', 'url_surat_sistem', 'url_surat_desa')->toArray())
            ->toArray();
    }

    public function templateTinyMCE(): void
    {
        $list_data = file_get_contents('assets/import/template_surat_tinymce.json');

        $proses = $this->prosesImport($this->formatImport($list_data));

        if ($proses) {
            $template = $this->getTemplate(FormatSurat::TINYMCE_SISTEM);
            $result   = file_put_contents(FCPATH . 'assets/import/template_surat_tinymce.json', json_encode($template, JSON_PRETTY_PRINT));

            if ($result) {
                redirect_with('success', 'Berhasil Buat Ulang Template Surat TinyMCE Bawaan');
            }
        }

        redirect_with('error', 'Gagal Buat Ulang Template Surat TinyMCE Bawaan');
    }

    private function formatImport($list_data = null)
    {
        return collect(json_decode($list_data, true))
            ->map(static fn ($item): array => [
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
                'form_isian'          => json_encode($item['form_isian'], JSON_THROW_ON_ERROR),
                'kode_isian'          => collect($item['kode_isian'])->filter(static fn ($item): bool => ! in_array($item['kode'], ['[form_nik_non_warga]', '[form_nama_non_warga]']))->values()->toJson(),
                'orientasi'           => $item['orientasi'],
                'ukuran'              => $item['ukuran'],
                'margin_global'       => $item['margin_global'] ? StatusEnum::YA : StatusEnum::TIDAK,
                'margin'              => $item['margin'],
                'format_nomor_global' => $item['format_nomor_global'] ? StatusEnum::YA : StatusEnum::TIDAK,
                'format_nomor'        => $item['format_nomor'],
                'footer'              => $item['footer'],
                'header'              => $item['header'],
                'created_at'          => date('Y-m-d H:i:s'),
                'creted_by'           => auth()->id,
                'updated_at'          => date('Y-m-d H:i:s'),
                'updated_by'          => auth()->id,
            ])
            ->toArray();
    }

    private function prosesImport($list_data = null, $id = null): bool
    {
        if ($list_data) {
            foreach ($list_data as $key => $value) {
                if ($id !== null) {
                    foreach ($id as $row) {
                        if ($row == $key) {
                            FormatSurat::updateOrCreate(['config_id' => identitas('id'), 'url_surat' => $value['url_surat']], $value);
                        }
                    }
                } else {
                    FormatSurat::updateOrCreate(['config_id' => identitas('id'), 'url_surat' => $value['url_surat']], $value);
                }
            }

            return true;
        }

        return false;
    }
}
