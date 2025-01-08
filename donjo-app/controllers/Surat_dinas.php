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

use App\Enums\SHDKEnum;
use App\Enums\StatusEnum;
use App\Exports\SuratDinasExport;
use App\Libraries\TinyMCE;
use App\Models\AliasKodeIsian;
use App\Models\KlasifikasiSurat;
use App\Models\SettingAplikasi;
use App\Models\Sex;
use App\Models\StatusDasar;
use App\Models\SuratDinas;
use App\Models\SyaratSurat;
use App\Models\User;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

defined('BASEPATH') || exit('No direct script access allowed');

class Surat_dinas extends Admin_Controller
{
    public $modul_ini     = 'surat-dinas';
    public $sub_modul_ini = 'pengaturan-surat-dinas';
    protected TinyMCE $tinymce;
    private $reference;

    public function __construct()
    {
        parent::__construct();
        $this->tinymce = new TinyMCE();
        $this->load->library('upload');
    }

    public function index()
    {
        return view('admin.surat_dinas.pengaturan.index', [
            'jenisSurat'       => SuratDinas::JENIS_SURAT,
            'suratDinasBawaan' => ci_route('unduh', encrypt(DEFAULT_LOKASI_IMPOR . 'template-surat-dinas-tinymce.json')),
        ]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $kunci = $this->input->get('status');
            $jenis = $this->input->get('jenis');

            return datatables((new SuratDinas())->jenis($jenis)->kunci($kunci))
                ->addIndexColumn()
                ->addColumn('ceklist', static fn ($row): string => '<input type="checkbox" name="id_cb[]" value="' . $row->id . '" />')
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        if (in_array($row->jenis, SuratDinas::SISTEM)) {
                            $aksi .= '<a href="' . ci_route("surat_dinas.form.{$row->id}") . '" class="btn bg-info btn-sm" title="Lihat"><i class="fa fa-eye fa-sm"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route("surat_dinas.form.{$row->id}") . '" class="btn btn-warning btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                        }

                        $aksi .= '<a href="' . ci_route('surat_dinas.salin', $row->id) . '" class="btn bg-olive btn-sm" title="Salin"><i class="fa fa-copy"></i></a> ';
                        if ($row->kunci) {
                            $aksi .= '<a href="' . ci_route("surat_dinas.kunci.{$row->id}") . '" class="btn bg-navy btn-sm" title="Aktifkan Surat"><i class="fa fa-lock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route("surat_dinas.kunci.{$row->id}") . '" class="btn bg-navy btn-sm" title="Nonaktifkan Surat"><i class="fa fa-unlock"></i></a> ';

                            if ($row->favorit) {
                                $aksi .= '<a href="' . ci_route("surat_dinas.favorit.{$row->id}") . '" class="btn bg-purple btn-sm" title="Keluarkan dari Daftar Favorit"><i class="fa fa-star"></i></a> ';
                            } else {
                                $aksi .= '<a href="' . ci_route("surat_dinas.favorit.{$row->id}") . '" class="btn bg-purple btn-sm" title="Tambahkan ke Daftar Favorit"><i class="fa fa-star-o"></i></a> ';
                            }
                        }
                    }

                    if (can('h') && ($row->jenis === SuratDinas::TINYMCE_DESA)) {
                        $aksi .= '<a href="#" data-href="' . ci_route("surat_dinas.delete.{$row->id}") . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('lampiran', static fn ($row): string => kode_format($row->lampiran))
                ->rawColumns(['ceklist', 'aksi', 'template_surat'])
                ->make();
        }

        return show_404();
    }

    public function salin($id): void
    {
        $this->reference = $id;
        $this->form();
    }

    public function form($id = null)
    {
        isCan('u');
        $this->set_hak_akses_rfm();

        $data['action']     = $id ? 'Ubah' : 'Tambah';
        $data['formAction'] = $id ? ci_route('surat_dinas.update', $id) : ci_route('surat_dinas.insert');
        if ($this->reference) {
            $id = $this->reference;
        }
        $data['suratDinas'] = $id ? SuratDinas::findOrFail($id) : null;
        if ($this->reference) {
            $data['suratDinas']->nama  = null;
            $data['suratDinas']->id    = null;
            $data['suratDinas']->jenis = SuratDinas::TINYMCE_DESA;
        }
        if ($id) {
            $kategori_isian = [];
            // hanya ambil key saja
            $data['kategori_nama'] = collect(get_key_form_kategori($data['suratDinas']->form_isian))->keys()->toArray();
            $data['kategori']      = collect(get_key_form_kategori($data['suratDinas']->form_isian))->toArray();

            collect($data['suratDinas']->kode_isian)->filter(static function ($item) use (&$kategori_isian): bool {
                if (isset($item->kategori)) {
                    $item->kategori                                = strtolower($item->kategori);
                    $kategori_isian[strtolower($item->kategori)][] = $item;

                    return true;
                }

                return false;
            })->values();

            $data['kategori_isian'] = $kategori_isian;
            $data['kode_isian']     = collect($data['suratDinas']->kode_isian)->reject(static fn ($item): bool => isset($item->kategori))->values();

            $data['klasifikasiSurat'] = KlasifikasiSurat::where('kode', $data['suratDinas']->kode_surat)->first();
        }

        $data['margins']              = json_decode($data['suratDinas']->margin, null) ?? json_decode((string) setting('surat_dinas_margin'), true);
        $data['margin_global']        = $data['suratDinas']->margin_global ?? StatusEnum::YA;
        $data['orientations']         = SuratDinas::ORIENTATAIONS;
        $data['sizes']                = SuratDinas::SIZES;
        $data['default_orientations'] = SuratDinas::DEFAULT_ORIENTATAIONS;
        $data['default_sizes']        = SuratDinas::DEFAULT_SIZES;
        $data['header']               = $data['suratDinas']->header ?? StatusEnum::YA;
        $data['footer']               = $data['suratDinas']->footer ?? StatusEnum::YA;
        $data['daftar_lampiran']      = $this->tinymce->getDaftarLampiranSuratDinas();
        $data['format_nomor']         = $data['suratDinas']->format_nomor;
        $data['format_nomor_global']  = $data['suratDinas']->format_nomor_global ?? StatusEnum::YA;
        $data['form_isian']           = $this->form_isian();
        $data['simpan_sementara']     = ci_route('surat_dinas/simpan_sementara');
        $data['masaBerlaku']          = SuratDinas::MASA_BERLAKU;
        $data['attributes']           = SuratDinas::ATTRIBUTES;
        $data['pendudukLuar']         = json_decode(SettingAplikasi::where('key', 'form_penduduk_luar')->first()->value ?? [], true);
        $data['viewOnly']             = in_array($data['suratDinas']?->jenis, SuratDinas::SISTEM);

        return view('admin.surat_dinas.pengaturan.form', $data);
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
            $suratDinas = SuratDinas::select('syarat_surat')->find($id);

            return datatables(SyaratSurat::query())
                ->addColumn('ceklist', static function ($row) use ($suratDinas): string {
                    $checked = in_array($row->ref_syarat_id, json_decode($suratDinas->syarat_surat, null) ?? []) ? 'checked' : '';

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

        if (SuratDinas::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function simpan_sementara()
    {
        isCan('u');
        $id = $this->request['id_surat'] ?: null;
        $this->checkTags($this->request['template_desa']);

        $cek_surat = SuratDinas::find($id);

        if (in_array($cek_surat->jenis, SuratDinas::SISTEM)) {
            return redirect_with('error', 'Surat bawaan sistem tidak dapat diubah');
        }

        $surat = SuratDinas::updateOrCreate(['id' => $id, 'config_id' => identitas('id')], static::validate($this->request, $cek_surat->jenis ?? 4, $id));
        if ($surat) {
            redirect_with('success', 'Berhasil Simpan Data Sementara', ci_route('surat_dinas.form', $surat->id));
        }

        redirect_with('error', 'Gagal Simpan Data');
    }

    public function update($id = null)
    {
        isCan('u');

        if ($this->request['action'] == 'preview') {
            $this->preview();

            return;
        }

        $this->checkTags($this->request['template_desa']);

        $data = SuratDinas::findOrFail($id);

        if (in_array($data->jenis, SuratDinas::SISTEM)) {
            return redirect_with('error', 'Surat bawaan sistem tidak dapat diubah');
        }

        if ($data->update(static::validate($this->request, $data->jenis, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    private function checkTags($template_desa, $id = null): void
    {
        $invalid_tags = invalid_tags();

        foreach ($invalid_tags as $invalid_tag) {
            if (str_contains((string) $template_desa, (string) $invalid_tag)) {
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
            $counter = count($request['tipe_kode']);

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
                'judul' => $request['judul'] ?? 'Utama',
                'label' => $request['label'] ?? '',
                'info'  => $request['info'] ?? '',
            ],
        ];

        if (isset($request['kategori'])) {
            foreach ($request['kategori'] as $kategori) {
                $formIsian[$kategori] = [
                    'judul' => $request['kategori_judul'][$kategori] ?? null,
                    'label' => $request['kategori_label'][$kategori] ?? null,
                    'info'  => $request['kategori_info'][$kategori] ?? null,
                ];
                $manual_data = array_values(array_filter($request['kategori_pilihan_kode'][$kategori] ?? []));
                if ($manual_data !== []) {
                    $data    = [];
                    $no      = 0;
                    $counter = count($request['kategori_tipe_kode'][$kategori]);

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
            'config_id'           => identitas('id'),
            'nama'                => nama_surat($request['nama']),
            'kode_surat'          => $request['kode_surat'],
            'masa_berlaku'        => $request['masa_berlaku'],
            'satuan_masa_berlaku' => $request['satuan_masa_berlaku'],
            'jenis'               => $jenis,
            'qr_code'             => $request['qr_code'],
            'logo_garuda'         => $request['logo_garuda'],
            'template_desa'       => $request['template_desa'],
            'form_isian'          => json_encode($formIsian, JSON_THROW_ON_ERROR),
            'kode_isian'          => json_encode($kodeIsian, JSON_THROW_ON_ERROR),
            'orientasi'           => $request['orientasi'],
            'ukuran'              => $request['ukuran'],
            'lampiran'            => is_array($request['lampiran']) ? implode(',', $request['lampiran']) : $request['lampiran'],
            'header'              => (int) $request['header'],
            'footer'              => (int) $request['footer'],
            'format_nomor'        => $request['format_nomor'],
            'format_nomor_global' => (int) $request['format_nomor_global'],
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
        $suratDinas = SuratDinas::select(['kode_isian'])->first($id) ?? show_404();

        return view('admin.surat_dinas.pengaturan.kode_isian', ['suratDinas' => $suratDinas]);
    }

    public function kunci($id = null): void
    {
        isCan('u');

        if (SuratDinas::gantiStatus($id, 'kunci')) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function favorit($id = null): void
    {
        isCan('u');

        if (SuratDinas::gantiStatus($id, 'favorit')) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null): void
    {
        isCan('h');
        $suratSistem = SuratDinas::sistem()->whereIn('id', $this->request['id_cb'] ?? [$id])->count();

        if ($suratSistem) {
            redirect_with('error', 'Gagal Hapus Data, Surat Bawaan Sistem Tidak Dapat Dihapus');
        }

        if (SuratDinas::destroy($this->request['id_cb'] ?? $id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function pengaturan()
    {
        $this->set_hak_akses_rfm();
        $data['font_option']     = SettingAplikasi::where('key', '=', 'font_surat')->first()->option;
        $data['penomoran_surat'] = SettingAplikasi::where('key', '=', 'penomoran_surat_dinas')->first();
        $data['tte_demo']        = empty(setting('tte_api')) || get_domain(setting('tte_api')) === get_domain(APP_URL);
        $data['kades']           = User::where('active', '=', 1)->whereHas('pamong', static fn ($query) => $query->where('jabatan_id', '=', kades()->id))->exists();
        $data['sekdes']          = User::where('active', '=', 1)->whereHas('pamong', static fn ($query) => $query->where('jabatan_id', '=', sekdes()->id))->exists();
        $data['aksi']            = ci_route('surat_dinas.update');
        $data['formAksi']        = ci_route('surat_dinas.edit_pengaturan');
        $margin                  = setting('surat_dinas_margin');
        $data['margins']         = json_decode((string) $margin, null) ?? SuratDinas::MARGINS;
        $data['alias']           = AliasKodeIsian::get();

        return view('admin.surat_dinas.pengaturan.pengaturan', $data);
    }

    public function edit_pengaturan(): void
    {
        isCan('u');
        $data = static::validasi_pengaturan($this->request);

        foreach ($data as $key => $value) {
            SettingAplikasi::where('key', '=', $key)->update(['value' => $value]);
        }
        (new SettingAplikasi())->flushQueryCache();
        if ($data['kodeisian_alias']) {
            $judulAlias   = $data['kodeisian_alias']['judul'];
            $contentAlias = $data['kodeisian_alias']['content'];
            AliasKodeIsian::whereNotIn('judul', $data['kodeisian_alias']['judul'])->delete();

            foreach ($data['kodeisian_alias']['alias'] as $index => $alias) {
                // observer gak jalan ketika menggunakan upsert
                AliasKodeIsian::upsert(['updated_by' => ci_auth()->id, 'config_id' => identitas('id'), 'judul' => $judulAlias[$index], 'alias' => $alias, 'content' => $contentAlias[$index]], ['config_id', 'judul']);
            }
        } else {
            AliasKodeIsian::whereConfigId(identitas('id'))->delete();
        }

        redirect_with('success', 'Berhasil Ubah Data');
    }

    protected static function validasi_pengaturan($request)
    {
        $footer = setting('tte') == '1' ? 'footer_surat_dinas_tte' : 'footer_surat_dinas';

        return [
            'tinggi_header_surat_dinas'  => (float) $request['tinggi_header_surat_dinas'],
            'header_surat_dinas'         => $request['header_surat_dinas'],
            $footer                      => $request[$footer],
            'tinggi_footer_surat_dinas'  => (float) $request['tinggi_footer_surat_dinas'],
            'verifikasi_sekdes'          => (int) $request['verifikasi_sekdes'],
            'verifikasi_kades'           => ((int) $request['tte'] == StatusEnum::YA) ? StatusEnum::YA : (int) $request['verifikasi_kades'],
            'font_surat_dinas'           => alfanumerik_spasi($request['font_surat_dinas']),
            'format_nomor_surat_dinas'   => $request['format_nomor_surat_dinas'],
            'penomoran_surat_dinas'      => $request['penomoran_surat_dinas'],
            'panjang_nomor_surat_dinas'  => $request['panjang_nomor_surat_dinas'],
            'format_tanggal_surat_dinas' => $request['format_tanggal_surat_dinas'],
            'surat_dinas_margin'         => json_encode($request['surat_dinas_margin'], JSON_THROW_ON_ERROR),
            'kodeisian_alias'            => $request['alias_kodeisian'] ? ['judul' => $request['judul_kodeisian'], 'alias' => $request['alias_kodeisian'], 'content' => $request['content_kodeisian']] : null,
        ];
    }

    public function kode_isian($jenis = 'isi', $id = null)
    {
        if ($this->input->is_ajax_request()) {
            $log_surat['surat'] = SuratDinas::find($id);
            $kode_isian         = $this->tinymce->getFormatedKodeIsian($log_surat, false, true);

            return json($kode_isian);
        }

        return show_404();
    }

    public function salin_template($jenis = 'isi')
    {
        if ($this->input->is_ajax_request()) {
            $template = $jenis == 'isi' ? $this->tinymce->getTemplateSuratDinas() : $this->tinymce->getTemplateDinas();

            return json($template);
        }

        return show_404();
    }

    public function preview(): void
    {
        // konversi request agar formatnya sama
        $request             = static::validate($this->request);
        $request['id_surat'] = $this->request['id_surat'] ?? null;

        $preview   = $this->tinymce->getPreview($request, '_dinas');
        $isi_cetak = $preview->getResult();

        // Ubah jadi format pdf
        $pages = $this->tinymce->generateMultiPage($isi_cetak);

        $isi_cetak = $this->tinymce->formatPdf($this->request['header'], $this->request['footer'], implode("<div style=\"page-break-after: always;\">\u{a0}</div>", $pages));

        if ($this->request['margin_global'] == 1) {
            $margins = setting('surat_dinas_margin_cm_to_mm');
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
            $html2pdf->setDefaultFont(underscore(setting('font_surat_dinas')));
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

    public function ekspor()
    {
        isCan('u');

        $id = $this->request['id_cb'];

        if (null === $id || count($id) === 0) {
            redirect_with('error', 'Tidak ada surat yang dipilih.');
        }

        return (new SuratDinasExport($id))->download();
    }

    public function impor_filter($data)
    {
        set_session('data_impor_surat', $data);

        return view('admin.surat_dinas.pengaturan.impor_select', [
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

        $proses = $this->prosesImport(session('data_impor_surat'), $id);

        if (isset($proses['error'])) {
            redirect_with('error', $proses['error']);
        }

        redirect_with('success', 'Berhasil Impor Data');
    }

    public function impor(): void
    {
        isCan('u');
        $config['upload_path']   = sys_get_temp_dir();
        $config['allowed_types'] = 'json';
        $config['overwrite']     = true;
        $config['max_size']      = max_upload() * 1024;
        $config['file_name']     = time() . '_template-surat-dinas-tinymce.json';

        $this->upload->initialize($config);

        if ($this->upload->do_upload('userfile')) {
            $list_data = $this->formatImport(file_get_contents($this->upload->data()['full_path']));
            if ($list_data) {
                $this->impor_filter($list_data);
            }
        }

        redirect_with('error', 'Gagal Impor Data<br/>' . $this->upload->display_errors());
    }

    private function getTemplate($jenis = SuratDinas::TINYMCE)
    {
        return SuratDinas::jenis($jenis)
            ->latest('id')
            ->get()
            ->map(static fn ($item) => collect($item)->except('id', 'config_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'judul_surat', 'margin_cm_to_mm', 'url_surat_sistem', 'url_surat_desa')->toArray())
            ->toArray();
    }

    public function templateTinyMCE(): void
    {
        $list_data = file_get_contents(DEFAULT_LOKASI_IMPOR . 'template-surat-dinas-tinymce.json');

        $proses = $this->prosesImport($this->formatImport($list_data));

        if (isset($proses['error'])) {
            redirect_with('error', $proses['error']);
        }

        if ($proses) {
            $template = $this->getTemplate(SuratDinas::TINYMCE_SISTEM);
            $result   = file_put_contents(DEFAULT_LOKASI_IMPOR . 'template-surat-dinas-tinymce.json', json_encode($template, JSON_PRETTY_PRINT));

            if ($result) {
                redirect_with('success', 'Berhasil Buat Ulang Template Surat TinyMCE Bawaan');
            }
        }

        redirect_with('error', 'Gagal Buat Ulang Template Surat TinyMCE Bawaan');
    }

    private function formatImport($list_data = null)
    {
        return collect(json_decode((string) $list_data, true))
            ->map(static fn ($item): array => [
                'nama'                => $item['nama'],
                'url_surat'           => str_replace('sistem-', '', $item['url_surat']), // Hapus prefix sistem- pada url surat agar tidak sama dengan surat bawaan sistem
                'kode_surat'          => $item['kode_surat'],
                'lampiran'            => $item['lampiran'],
                'kunci'               => $item['kunci'] ? StatusEnum::YA : StatusEnum::TIDAK,
                'favorit'             => $item['favorit'] ? StatusEnum::YA : StatusEnum::TIDAK,
                'jenis'               => SuratDinas::TINYMCE_DESA, // Surat yang diimpor selalu jenis surat desa
                'masa_berlaku'        => $item['masa_berlaku'],
                'satuan_masa_berlaku' => $item['satuan_masa_berlaku'],
                'qr_code'             => $item['qr_code'] ? StatusEnum::YA : StatusEnum::TIDAK,
                'logo_garuda'         => $item['logo_garuda'] ? StatusEnum::YA : StatusEnum::TIDAK,
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
                'creted_by'           => ci_auth()->id,
                'updated_at'          => date('Y-m-d H:i:s'),
                'updated_by'          => ci_auth()->id,
            ])
            ->toArray();
    }

    private function prosesImport($list_data = null, $id = null): bool|array
    {
        if ($list_data) {
            foreach ($list_data as $key => $value) {
                if (strlen($value['nama']) > 100) {
                    return ['error' => 'Nama surat tidak boleh lebih dari 100 karakter'];
                }

                if ($id !== null) {
                    foreach ($id as $row) {
                        if ($row == $key) {
                            SuratDinas::updateOrCreate(['config_id' => identitas('id'), 'url_surat' => $value['url_surat']], $value);
                        }
                    }
                } else {
                    SuratDinas::updateOrCreate(['config_id' => identitas('id'), 'url_surat' => $value['url_surat']], $value);
                }
            }

            return true;
        }

        return false;
    }
}
