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

use App\Enums\FirebaseEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusEnum;
use App\Enums\StatusSuratKecamatanEnum;
use App\Libraries\TinyMCE;
use App\Libraries\TinyMCE\KodeIsianGambar;
use App\Models\FcmToken;
use App\Models\FormatSurat;
use App\Models\Keluarga;
use App\Models\LogPenduduk;
use App\Models\LogSurat;
use App\Models\Pamong;
use App\Models\Penduduk;
use App\Models\PermohonanSurat;
use App\Models\RefJabatan;
use App\Models\SettingAplikasi;
use App\Models\Urls;
use Carbon\Carbon;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;

defined('BASEPATH') || exit('No direct script access allowed');

class Surat extends Admin_Controller
{
    public $modul_ini     = 'layanan-surat';
    public $sub_modul_ini = 'cetak-surat';
    private readonly TinyMCE $tinymce;
    private readonly LogPenduduk $logpenduduk;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['penduduk_model', 'keluarga_model', 'surat_model', 'keluar_model', 'penomoran_surat_model', 'permohonan_surat_model']);
        $this->tinymce     = new TinyMCE();
        $this->logpenduduk = new LogPenduduk();
    }

    public function index()
    {
        return view('admin.surat.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of((new FormatSurat())->kunci(FormatSurat::KUNCI_DISABLE)->orderBy('favorit', 'desc')->latest('updated_at'))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        if ($row->favorit) {
                            $aksi .= '<a href="' . site_url("surat/form/{$row->url_surat}") . '" class="btn btn-social bg-olive btn-sm" title="Buat Surat"><i class="fa fa-file-word-o"></i>Buat Surat</a> ';
                            $aksi .= '<a href="' . site_url("surat/favorit/{$row->id}/1") . '" class="btn bg-olive btn-sm" title="Keluarkan dari Daftar Favorit"><i class="fa fa-star"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . site_url("surat/form/{$row->url_surat}") . '" class="btn btn-social bg-purple btn-sm" title="Buat Surat"><i class="fa fa-file-word-o"></i>Buat Surat</a> ';
                            $aksi .= '<a href="' . site_url("surat/favorit/{$row->id}/0") . '" class="btn bg-purple btn-sm" title="Tambahkan ke Daftar Favorit"><i class="fa fa-star-o"></i></a> ';
                        }
                    }

                    return $aksi;
                })
                ->editColumn('lampiran', static fn ($row): string => kode_format($row->lampiran))
                ->rawColumns(['aksi', 'template_surat'])
                ->make();
        }

        return show_404();
    }

    public function apidaftarsurat()
    {
        if ($this->input->is_ajax_request()) {
            $cari  = $this->input->get('q');
            $surat = FormatSurat::select(['id', 'nama', 'jenis', 'url_surat'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->orWhere('nama', 'like', "%{$cari}%");
                })->whereIn('jenis', FormatSurat::TINYMCE)
                ->kunci(FormatSurat::KUNCI_DISABLE)
                ->latest('updated_at')
                ->orderBy('favorit', 'desc')
                ->paginate(10);

            return json([
                'results' => collect($surat->items())
                    ->map(static fn ($item): array => [
                        'id'   => $item->url_surat,
                        'text' => "Surat {$item->nama}",
                    ]),
                'pagination' => [
                    'more' => $surat->currentPage() < $surat->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    public function form($url = '', $id = '')
    {
        $this->session->unset_userdata('pengaturan_surat');
        $nik = $this->input->post('nik') ?? $id;

        $this->session->unset_userdata('log_surat');
        unset($_SESSION['id_ibu'], $_SESSION['id_bayi'], $_SESSION['id_pelapor'], $_SESSION['id_saksi1'], $_SESSION['id_saksi2']);
        $data['surat'] = FormatSurat::cetak($url)->first();

        if ($data['surat']) {
            $data['url']       = $url;
            $data['anchor']    = $this->input->post('anchor');
            $data['surat_url'] = rtrim((string) $_SERVER['REQUEST_URI'], '/clear');

            // NIK => id
            if (! empty($nik)) {
                $data['individu'] = null;
                $data['anggota']  = null;
            }
            // cek apakah surat itu memiliki form kategori ( saksi etc )
            $kategori = get_key_form_kategori($data['surat']['form_isian']);
            if (! empty($kategori)) {
                $form_kategori   = [];
                $kategori_isian  = [];
                $filter_kategori = collect($data['surat']->kode_isian)->filter(static function ($item) use (&$kategori_isian): bool {
                    $item->kategori                    = strtolower($item->kategori);
                    $kategori_isian[$item->kategori][] = $item;

                    return isset($item->kategori);
                })->values();

                foreach ($kategori as $key => $ktg) {
                    $form_kategori[$key]['form']       = $this->get_data_untuk_form($url, $data);
                    $form_kategori[$key]['kode_isian'] = $this->groupByLabel($kategori_isian[$key]);
                    $form_kategori[$key]['saksi']      = $this->input->post("id_pend_{$key}") ?? '';

                    if (! empty($form_kategori[$key]['saksi'])) {
                        $form_kategori[$key]["saksi_{$key}"] = Penduduk::findOrFail($form_kategori[$key]['saksi']);
                    }

                    $form_kategori[$key]["list_dokumen_{$key}"] = empty($form_kategori[$key]["saksi_{$key}"])
                        ? null : $this->penduduk_model->list_dokumen($form_kategori[$key]["saksi_{$key}"]->id);
                }
                $filtered_kode_isian = collect($data['surat']->kode_isian)->reject(static fn ($item): bool => isset($item->kategori))->values();

                $data['surat']['kode_isian'] = $this->groupByLabel($filtered_kode_isian);
                $data['form_kategori']       = $form_kategori;
            } else {
                $data['surat']['kode_isian'] = $this->groupByLabel($data['surat']->kode_isian);
            }
            $this->get_data_untuk_form($url, $data);
            // TODO:: Gunakan 1 list_dokumen untuk RTF dan TinyMCE
            $data['list_dokumen'] = empty($nik) ? null : $this->penduduk_model->list_dokumen($data['individu']['id']);
            $data['form_action']  = ci_route('surat.pratinjau', $url);

            $data['judul_kategori'] = collect($data['surat']->form_isian)->map(static fn ($item) => $item->label);
            $data['pendudukLuar']   = json_decode(SettingAplikasi::where('key', 'form_penduduk_luar')->first()->value ?? [], true);
            $data['lampiran']       = explode(',', strtolower($data['surat']->lampiran));

            return view('admin.surat.form_desa', $data);
        }

        redirect_with('error', 'Surat tidak ditemukan');
    }

    public function pratinjau($url, $id = null)
    {
        $this->set_hak_akses_rfm();
        if ($id) {
            // Ganti status menjadi 'Menunggu Tandatangan'
            $this->permohonan_surat_model->proses($id, 2);

            //update isian form
            $post       = $this->input->post();
            $remove     = ['berlaku_dari', 'berlaku_sampai', 'pilih_atas_nama', 'submit_cetak'];
            $isian_form = array_diff_key($post, array_flip($remove));

            PermohonanSurat::where('id', $id)->update(['isian_form' => json_encode($isian_form)]);
        }

        $surat     = FormatSurat::cetak($url)->first();
        $kecamatan = $surat->kecamatan == StatusEnum::TIDAK ? StatusSuratKecamatanEnum::TidakAktif : StatusSuratKecamatanEnum::BelumDikirim;

        if ($surat && $this->request) {
            // Simpan data ke log_surat sebagai draf
            $id_pamong = $this->ttd($this->request['pilih_atas_nama'], $this->request['pamong_id']);
            $pamong    = Pamong::find($id_pamong);
            $log_surat = [
                'id_format_surat' => $surat->id,
                'id_pamong'       => $id_pamong,
                'nama_jabatan'    => $pamong->jabatan->nama,
                'nama_pamong'     => $pamong->pamong_nama,
                'tanggal'         => Carbon::now(),
                'bulan'           => date('m'),
                'tahun'           => date('Y'),
                'no_surat'        => $this->request['nomor'],
                'keterangan'      => $this->request['keterangan'] ?: $this->request['keperluan'],
                'kecamatan'       => $kecamatan,
            ];

            // non warga
            if ($this->request['nik']) {
                $log_surat['id_pend']        = $this->request['nik'];
                $log_surat['nama_non_warga'] = null;
                $log_surat['nik_non_warga']  = null;
            } else {
                $log_surat['id_pend']        = null;
                $log_surat['nama_non_warga'] = $this->request['individu']['nama'];
                $log_surat['nik_non_warga']  = $this->request['individu']['nik'];
            }

            if ($this->request['sebagai']) {
                $name_pelapor = $this->request['sebagai'];
                if ($this->request['id_pend_' . $name_pelapor]) {
                    $pelapor['id_pend_pelapor'] = $this->request['id_pend_' . $name_pelapor];
                    $pelapor                    = Penduduk::where('id', $pelapor['id_pend_pelapor'])->first();
                    $pelapor['nik_pelapor']     = $pelapor->nik;
                    $pelapor['nama_pelapor']    = $pelapor->nama;
                } else {
                    $pelapor['id_pend_pelapor'] = null;
                    $pelapor['nik_pelapor']     = $this->request[$name_pelapor]['nik'];
                    $pelapor['nama_pelapor']    = $this->request[$name_pelapor]['nama'];
                }
                $log_surat['pemohon'] = json_encode(['id_pend' => $pelapor['id'], 'nik' => $pelapor['nik_pelapor'], 'nama' => $pelapor['nama_pelapor']]);
            } else {
                $log_surat['pemohon'] = null;
            }

            $log_surat['surat'] = $surat;
            $log_surat['input'] = $this->request;
            // dd($log_surat);
            $setting_header         = $surat->header == StatusEnum::TIDAK ? '' : setting('header_surat');
            $setting_footer         = $surat->footer == StatusEnum::YA ? (setting('tte') == StatusEnum::YA ? setting('footer_surat_tte') : setting('footer_surat')) : '';
            $log_surat['isi_surat'] = preg_replace('/\\\\/', '', $setting_header) . '<!-- pagebreak -->' . ($surat->template_desa ?: $surat->template) . '<!-- pagebreak -->' . preg_replace('/\\\\/', '', $setting_footer);

            if (isset($log_surat['input']['id_pengikut'])) {
                $pengikut     = Penduduk::whereIn('id', $log_surat['input']['id_pengikut'])->orderKeluarga()->get();
                $keterangan[] = [];

                foreach ($pengikut as $anak) {
                    $keterangan[$anak->id] = $log_surat['input']['ket_' . $anak->id] ?? '';
                }

                $log_surat['pengikut_surat'] = generatePengikut($pengikut, $keterangan);
            }

            if (isset($log_surat['input']['id_pengikut_kis'])) {
                $pengikut = Penduduk::whereIn('id', $log_surat['input']['id_pengikut_kis'])->orderKeluarga()->get();
                $kis      = [];

                foreach ($pengikut as $anggota) {
                    $kis[$anggota->id] = $log_surat['input']['kis'][$anggota->nik];
                }

                $log_surat['pengikut_kis']       = generatePengikutSuratKIS($pengikut);
                $log_surat['pengikut_kartu_kis'] = generatePengikutKartuKIS($kis);
            }

            if (isset($log_surat['input']['id_pengikut_pindah'])) {
                $pengikut = Penduduk::with('pendudukHubungan')->whereIn('id', $log_surat['input']['id_pengikut_pindah'])->orderKeluarga()->get();
                $pindah   = [];

                foreach ($pengikut as $anggota) {
                    $pindah[$anggota->id] = $log_surat['input']['pindah'][$anggota->nik];
                }

                $log_surat['pengikut_pindah'] = generatePengikutPindah($pengikut);
            }

            $daftar_kategori = get_key_form_kategori($surat->form_isian);

            foreach ($daftar_kategori as $key => $kategori) {
                $log_surat['kategori'][$key] = $this->request['id_pend_' . $key];
            }

            $isi_surat = $this->tinymce->gantiKodeIsian($log_surat, false);

            unset($log_surat['isi_surat']);
            $this->session->log_surat = $log_surat;

            $aksi_konsep = site_url('surat/konsep');
            $aksi_cetak  = site_url('surat/pdf');

            $id_surat = $surat->id;

            $font_option = SettingAplikasi::where('key', '=', 'font_surat')->first()->option;
            $margins     = json_decode((string) setting('surat_margin'), null) ?? FormatSurat::MARGINS;

            return view('admin.surat.konsep', [
                'content'     => $content,
                'aksi_konsep' => $aksi_konsep,
                'aksi_cetak'  => $aksi_cetak,
                'isi_surat'   => $isi_surat,
                'id_surat'    => $id_surat,
                'font_option' => $font_option,
                'margins'     => $margins,
            ]);
        }

        set_session('error', "Data Surat {$surat->nama} tidak ditemukan");

        redirect("surat/form/{$url}");
    }

    public function pdf($preview = false)
    {
        $ubah = $this->input->get('ubah');
        // Cetak Konsep
        $cetak = $this->session->log_surat;
        if ($cetak) {
            $id_pamong = $this->ttd($cetak['input']['pilih_atas_nama'], $cetak['input']['pamong_id']);
            $pamong    = Pamong::find($id_pamong);
            $log_surat = [
                'id_format_surat' => $cetak['id_format_surat'],
                'id_pend'         => $cetak['id_pend'], // nik = id_pend
                'id_pamong'       => $id_pamong,
                'nama_jabatan'    => $pamong->jabatan->nama,
                'nama_pamong'     => $pamong->pamong_nama,
                'id_user'         => ci_auth()->id,
                'tanggal'         => Carbon::now(),
                'bulan'           => date('m'),
                'tahun'           => date('Y'),
                'no_surat'        => $preview ? '' : $cetak['input']['nomor'],
                'keterangan'      => $cetak['keterangan'],
                'kecamatan'       => $cetak['kecamatan'] ?? StatusSuratKecamatanEnum::TidakAktif,
            ];

            if ($nik = $cetak['input']['nik']) {
                $nik = Penduduk::find($nik)->nik;
            } else {
                // Surat untuk non-warga
                $log_surat['nama_non_warga'] = $cetak['input']['individu']['nama'];
                $log_surat['nik_non_warga']  = $cetak['input']['individu']['nik'];
                $nik                         = $log_surat['nik_non_warga'];
            }

            if ($cetak['input']['sebagai']) {
                $name_pelapor = $cetak['input']['sebagai'];
                if ($cetak['input']['id_pend_' . $name_pelapor]) {
                    $pelapor['id_pend_pelapor'] = $cetak['input']['id_pend_' . $name_pelapor];
                    $pelapor                    = Penduduk::where('id', $pelapor['id_pend_pelapor'])->first();
                    $pelapor['nik_pelapor']     = $pelapor->nik;
                    $pelapor['nama_pelapor']    = $pelapor->nama;
                } else {
                    $pelapor['id_pend_pelapor'] = null;
                    $pelapor['nik_pelapor']     = $cetak['input'][$name_pelapor]['nik'];
                    $pelapor['nama_pelapor']    = $cetak['input'][$name_pelapor]['nama'];
                }
                $log_surat['pemohon'] = json_encode(['id_pend' => $pelapor['id'], 'nik' => $pelapor['nik_pelapor'], 'nama' => $pelapor['nama_pelapor']]);
            } else {
                $log_surat['pemohon'] = null;
            }

            $log_surat['surat']          = $cetak['surat'];
            $log_surat['input']          = $cetak['input'];
            $log_surat['isi_surat']      = $this->request['isi_surat'];
            $log_surat['isi_surat_temp'] = $this->request['isi_surat'];

            $isi_surat = $this->tinymce->gantiKodeIsian($log_surat, false);

            // Ubah jadi format pdf
            $isi_cetak  = $this->tinymce->formatPdf($cetak['surat']->header, $cetak['surat']->footer, $isi_surat, $preview);
            $nama_surat = $this->nama_surat_arsip($cetak['surat']['url_surat'], $nik, $cetak['no_surat']);

            $log_surat['nama_surat'] = $nama_surat;
            $log_surat['input']      = json_encode($log_surat['input']);

            unset($log_surat['surat']);

            // jika ubah tidak kosong jangan kosongkan cetak_id
            if ($preview && ($ubah != null)) {
                $cetak['id'] = null;
            }

            $id    = LogSurat::updateOrCreate(['id' => $cetak['id']], $log_surat)->id;
            $surat = LogSurat::findOrFail($id);
            header('id_arsip: ' . $id); // sisipkan id

            // Replace Gambar
            $data_gambar    = KodeIsianGambar::set($cetak['surat'], $isi_cetak, $surat);
            $isi_cetak      = $data_gambar['result'];
            $surat->urls_id = $data_gambar['urls_id'];

            $margin_cm_to_mm = $this->session->has_userdata('pengaturan_surat')
                ? [
                    json_decode($this->session->pengaturan_surat['surat_margin'])->kiri * 10,
                    json_decode($this->session->pengaturan_surat['surat_margin'])->atas * 10,
                    json_decode($this->session->pengaturan_surat['surat_margin'])->kanan * 10,
                    json_decode($this->session->pengaturan_surat['surat_margin'])->bawah * 10,
                ]
                : $cetak['surat']['margin_cm_to_mm'];

            if ($cetak['surat']['margin_global'] == '1' && ! $this->session->has_userdata('pengaturan_surat')) {
                $margin_cm_to_mm = setting('surat_margin_cm_to_mm');
            }

            // convert in PDF
            try {
                $defaultFont = underscore($this->session->pengaturan_surat['font_surat'] ?? setting('font_surat'));

                // pakai try catch untuk menghindari error saat generate surat
                try {
                    $this->tinymce->generateSurat($isi_cetak, $cetak, $margin_cm_to_mm, $defaultFont);
                } catch (Throwable $th) {
                    log_message('error', $th->getMessage());
                }

                $this->tinymce->generateLampiran($surat->id_pend, $cetak, $cetak['input']);

                if ($preview) {
                    // TODO: gunakan relasi
                    Urls::destroy($surat->urls_id);
                    LogSurat::destroy($id);
                    $this->tinymce->pdfMerge->merge('document.pdf', 'I');
                } else {
                    // Untuk surat yang sudah dicetak, simpan isian suratnya yang sudah jadi (siap di konversi)
                    $surat->isi_surat = $isi_cetak;
                    $surat->status    = LogSurat::CETAK;

                    // Jika verifikasi sekdes atau verifikasi kades di non-aktifkan
                    $surat->verifikasi_operator = (setting('verifikasi_sekdes') || setting('verifikasi_kades')) ? LogSurat::PERIKSA : LogSurat::TERIMA;

                    $surat->save();
                    $this->notifikasiMobile($cetak, $id);

                    $this->tinymce->pdfMerge->merge(FCPATH . LOKASI_ARSIP . $nama_surat, 'FI');
                }
            } catch (Html2PdfException $e) {
                $formatter = new ExceptionFormatter($e);
                log_message('error', trim((string) preg_replace('/\s\s+/', ' ', $formatter->getMessage())));

                // Untuk surat yang sudah tersimpan sebagai draf, simpan isian suratnya yang belum jadi (hanya isian surat dari konversi template surat)
                $surat->isi_surat = $isi[1];
                $surat->status    = LogSurat::KONSEP;

                return $this->output
                    ->set_status_header(404, str_replace("\n", ' ', $formatter->getMessage()))
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'statusText' => $formatter->getMessage(),
                    ], JSON_THROW_ON_ERROR));
            }

            exit();
        }
            redirect_with('error', 'Tidak ada surat yang akan dicetak.');

    }

    public function konsep(): void
    {
        $cetak = $this->session->log_surat;

        if ($cetak) {
            $id_pamong = $this->ttd($cetak['input']['pilih_atas_nama'], $cetak['input']['pamong_id']);
            $pamong    = Pamong::find($id_pamong);
            $log_surat = [
                'id_format_surat' => $cetak['id_format_surat'],
                'id_pend'         => $cetak['id_pend'], // nik = id_pend
                'id_pamong'       => $id_pamong,
                'nama_jabatan'    => $pamong->jabatan->nama,
                'nama_pamong'     => $pamong->pamong_nama,
                'id_user'         => ci_auth()->id,
                'tanggal'         => Carbon::now(),
                'kecamatan'       => $cetak['surat']->kecamatan,
                'input'           => json_encode($cetak['input']),
            ];
            $log_surat['verifikasi_operator'] = 0;

            if ($nik = $cetak['input']['nik']) {
                $nik = Penduduk::find($nik)->nik;
            } else {
                // Surat untuk non-warga
                $log_surat['nama_non_warga'] = $cetak['input']['individu']['nama'];
                $log_surat['nik_non_warga']  = $cetak['input']['individu']['nik'];
                $nik                         = $log_surat['nik_non_warga'];
            }

            if ($cetak['input']['sebagai']) {
                $name_pelapor = $cetak['input']['sebagai'];
                if ($cetak['input']['id_pend_' . $name_pelapor]) {
                    $pelapor['id_pend_pelapor'] = $cetak['input']['id_pend_' . $name_pelapor];
                    $pelapor                    = Penduduk::where('id', $pelapor['id_pend_pelapor'])->first();
                    $pelapor['nik_pelapor']     = $pelapor->nik;
                    $pelapor['nama_pelapor']    = $pelapor->nama;
                } else {
                    $pelapor['id_pend_pelapor'] = null;
                    $pelapor['nik_pelapor']     = $cetak['input'][$name_pelapor]['nik'];
                    $pelapor['nama_pelapor']    = $cetak['input'][$name_pelapor]['nama'];
                }
                $log_surat['pemohon'] = json_encode(['id_pend' => $pelapor['id'], 'nik' => $pelapor['nik_pelapor'], 'nama' => $pelapor['nama_pelapor']]);
            } else {
                $log_surat['pemohon'] = null;
            }

            $isi_surat = $this->request['isi_surat'];

            // Kembalikan kode isian [format_nomor_surat]
            $format_surat = substitusiNomorSurat($cetak['input']['nomor'], format_penomoran_surat($cetak['surat']['format_nomor_global'], setting('format_nomor_surat'), $cetak['surat']['format_nomor_surat']));
            $format_surat = str_ireplace('[kode_surat]', $cetak['surat']['kode_surat'], $format_surat);
            $format_surat = str_ireplace('[kode_desa]', identitas()->kode_desa, $format_surat);
            $format_surat = str_ireplace('[bulan_romawi]', bulan_romawi((int) (date('m'))), $format_surat);
            $format_surat = str_ireplace('[tahun]', date('Y'), $format_surat);

            $isi_surat = str_ireplace($format_surat, '[format_nomor_surat]', $isi_surat);

            // Hanya simpan isian surat
            $isi_surat = explode('<!-- pagebreak -->', $isi_surat)[1];

            $log_surat['isi_surat'] = $isi_surat;

            // Jika verifikasi sekdes atau verifikasi kades di non-aktifkan
            $log_surat['verifikasi_operator'] = (setting('verifikasi_sekdes') || setting('verifikasi_kades')) ? LogSurat::PERIKSA : LogSurat::TERIMA;

            if (LogSurat::updateOrCreate(['id' => $cetak['id']], $log_surat)) {
                redirect_with('success', 'Berhasil Simpan Konsep', 'keluar/masuk');
            }
        }

        redirect_with('success', 'Gagal Simpan Konsep');
    }

    public function cetak($id)
    {
        $surat = LogSurat::find($id);

        if ($surat->status && $surat->verifikasi_operator != '-1') {
            $this->tinymce->cetak_surat($id);
        } else {
            $log_surat = [
                'id_format_surat' => $surat->id_format_surat,
                'id_pend'         => $surat->id_pend,
            ];

            // Untuk sementara :
            // 1. penanda tangan sama dengan log surat yang disimpan sebagai draf
            $pamong = Pamong::find($surat->id_pamong);

            $atas_nama = '';
            if ($pamong->pamong_ttd === 1) {
                $atas_nama .= 'a.n ' . ucwords($pamong->pamong_jabatan . ' ' . identitas()->nama_desa);
            } elseif ($pamong->pamong_ub === 1) {
                $atas_nama .= 'u.b ' . ucwords($pamong->pamong_jabatan . ' ' . identitas()->nama_desa);
            }

            $log_surat['no_surat'] = LogSurat::lastNomerSurat($surat->url_surat)['no_surat_berikutnya'];
            $log_surat['surat']    = $surat->formatSurat;
            $input                 = json_decode($surat->input, true);
            $log_surat['input']    = [
                'nik'            => $surat->id_pend,
                'nama_non_warga' => $surat->nama_non_warga,
                'nik_non_warga'  => $surat->nik_non_warga,

                // 1. Nomer surat dicek dan dibuat ulang
                'nomor'           => $log_surat['no_surat'],
                'pilih_atas_nama' => $atas_nama,
                'pamong_id'       => $pamong->pamong_id,
            ];
            $log_surat['input'] = array_merge($log_surat['input'], $input);

            if ($surat->verifikasi_operator != '-1') {
                $log_surat['isi_surat'] = preg_replace('/\\\\/', '', setting('header_surat')) . '<!-- pagebreak -->' . ($surat->isi_surat) . '<!-- pagebreak -->' . preg_replace('/\\\\/', '', setting('footer_surat'));
            } else {
                $log_surat['isi_surat'] = preg_replace('/\\\\/', '', ($surat->isi_surat));
            }

            $log_surat['id'] = $surat->id;
            $isi_surat       = $this->tinymce->gantiKodeIsian($log_surat);

            unset($log_surat['isi_surat']);
            $this->session->log_surat = $log_surat;

            $aksi_konsep = site_url('surat/konsep');
            $aksi_cetak  = site_url('surat/pdf');
            $tolak       = $surat->verifikasi_operator;
            $id_surat    = $surat->id;

            return view('admin.surat.konsep', ['aksi_konsep' => $aksi_konsep, 'aksi_cetak' => $aksi_cetak, 'isi_surat' => $isi_surat, 'id_surat' => $id_surat, 'tolak' => $tolak]);
        }

        return show_404();
    }

    private function ttd($ttd = '', $pamong_id = null)
    {
        if (preg_match('/a.n/i', (string) $ttd)) {
            return Pamong::ttd('a.n')->first()->pamong_id;
        }
        if (preg_match('/u.b/i', (string) $ttd)) {
            return $pamong_id;
        }

        return Pamong::kepalaDesa()->first()->pamong_id;
    }

    private function nama_surat_arsip(string $url, string $nik, $nomor): string
    {
        $nomor_surat = str_replace("'", '', $nomor);
        $nomor_surat = preg_replace('/[^a-zA-Z0-9.	]/', '-', $nomor_surat);

        return $url . '_' . $nik . '_' . date('Y-m-d') . '_' . $nomor_surat . '.pdf';
    }

    public function nomor_surat_duplikat(): void
    {
        $hasil = $this->penomoran_surat_model->nomor_surat_duplikat('log_surat', $_POST['nomor'], $_POST['url']);
        echo $hasil ? 'false' : 'true';
    }

    public function search(): void
    {
        $cari = $this->input->post('nik');
        if ($cari != '') {
            redirect("surat/form/{$cari}");
        } else {
            redirect('surat');
        }
    }

    // Data yang digunakan surat jenis rtf dan tinymce
    private function get_data_untuk_form($url, array &$data, $kategori = 'individu')
    {
        // TinyMCE
        // Data penduduk diambil sesuai pengaturan surat
        if ($data['surat']['form_isian']->individu->data == 2) {
            $data['penduduk'] = false;
            $data['anggota']  = null;
        } else {
            $filters = collect($data['surat']['form_isian']->{$kategori})->toArray();
            unset($filters['data']);
            $data['penduduk'] = true;
            $kk_level         = $data['individu']['kk_level'];
            $ada_anggota      = $filters['kk_level'] == SHDKEnum::KEPALA_KELUARGA || $kk_level == SHDKEnum::KEPALA_KELUARGA;

            $data['anggota'] = $ada_anggota ? Keluarga::find($data['individu']['id_kk'])->anggota : null;
            if ($kategori != 'individu') {
                return $data;
            }
        }
        $template = $data['surat']->template_desa ?: $data['surat']->template;
        if (preg_match('/\[pengikut_surat\]/i', $template)) {
            $pengikut = $this->pengikutDibawah18Tahun($data);
            if ($pengikut) {
                $data['pengikut'] = $pengikut;
            }
        }

        if (preg_match('/\[pengikut_kis\]/i', $template)) {
            $pengikut = $this->pengikutSuratKIS($data);
            if ($pengikut) {
                $data['pengikut_kis'] = $pengikut;
            }
        }

        if (preg_match('/\[pengikut_pindah\]/i', $template)) {
            $pengikut = $this->pengikutPindah($data);
            if ($pengikut) {
                $data['pengikut_pindah'] = $pengikut;
            }
        }

        $data['surat_terakhir']     = LogSurat::lastNomerSurat($url);
        $data['input']              = $this->input->post();
        $data['input']['nomor']     = $data['surat_terakhir']['no_surat_berikutnya'];
        $data['format_nomor_surat'] = FormatSurat::format_penomoran_surat($data);

        $penandatangan     = $this->tinymce->formPenandatangan();
        $data['pamong']    = $penandatangan['penandatangan'];
        $data['atas_nama'] = $penandatangan['atas_nama'];
    }

    public function favorit($id = null, $val = 0): void
    {
        isCan('u');

        $favorit = FormatSurat::findOrFail($id);
        $favorit->update(['favorit' => ($val == 1) ? 0 : 1]);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    /*
        Ajax POST data:
        url -- url surat
        nomor -- nomor surat
    */
    public function format_nomor_surat(): void
    {
        $data['surat']          = FormatSurat::where('url_surat', $this->input->post('url'))->first()?->toArray();
        $data['input']['nomor'] = $this->input->post('nomor');
        $format_nomor           = FormatSurat::format_penomoran_surat($data);
        echo json_encode($format_nomor, JSON_THROW_ON_ERROR);
    }

    /*
        Ajax url query data:
        q -- kata pencarian
        page -- nomor paginasi
    */
    public function list_penduduk_ajax(): void
    {
        $cari          = $this->input->get('q');
        $page          = $this->input->get('page');
        $hubungan      = $this->input->get('hubungan');
        $filter_sex    = $this->input->get('filter_sex');
        $filter['sex'] = ($filter_sex == 'perempuan') ? 2 : $filter_sex;
        $kategori      = $this->input->get('kategori') ?? null;
        $kecuali       = $this->input->get('kecuali') ?? null;
        if ($kategori) {
            $filterPenduduk = collect(FormatSurat::select('form_isian')->find($this->input->get('surat'))->form_isian->{$kategori})->toArray();
            if (isset($filterPenduduk['data'])) {
                unset($filterPenduduk['data']);
            }
            $filter = array_merge($filter, $filterPenduduk);

            if ($hubungan) {
                $filter['hubungan'] = $hubungan;
            }

            if ($kecuali) {
                $filter['kecuali'] = $kecuali;
            }
        }

        $penduduk = $this->surat_model->list_penduduk_ajax($cari, $filter, $page);
        echo json_encode($penduduk, JSON_THROW_ON_ERROR);
    }

    // list untuk dropdown arsip layanan tampil hanya yg bersurat saja
    public function list_penduduk_bersurat_ajax(): void
    {
        $cari     = $this->input->get('q');
        $page     = $this->input->get('page');
        $penduduk = $this->surat_model->list_penduduk_bersurat_ajax($cari, $page);
        echo json_encode($penduduk, JSON_THROW_ON_ERROR);
    }

    public function apipenduduksurat()
    {
        if ($this->input->is_ajax_request()) {
            $cari     = $this->input->get('q');
            $filters  = FormatSurat::select('form_isian')->find($this->input->get('surat'))->form_isian;
            $individu = collect($filters->individu)->toArray();
            $penduduk = Penduduk::select(['id', 'nik', 'tag_id_card', 'nama', 'id_cluster'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->orWhere('nik', 'like', "%{$cari}%")
                        ->orWhere('tag_id_card', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                });

            if ($individu['data_orang_tua'] == 1) {
                $penduduk = $penduduk->where('id_kk', '>', '0');
            }

            // hanya ambil surat
            $penduduk = $penduduk->filters($individu)->paginate(10);

            return json([
                'results' => collect($penduduk->items())
                    ->map(static fn ($item): array => [
                        'id'   => $item->id,
                        'text' => 'NIK : ' . $item->nik . '<br>Tag ID Card : ' . ($item->tag_id_card ?: '-') . '<br>Nama : ' . $item->nama . '<br>Alamat : RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun') . ' ' . $item->wilayah->dusun),
                    ]),
                'pagination' => [
                    'more' => $penduduk->currentPage() < $penduduk->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    private function pengikutDibawah18Tahun(array $data)
    {
        $pengikut = null;
        $minUmur  = 18;
        $kk_level = $data['individu']['kk_level'];
        if ($kk_level == SHDKEnum::KEPALA_KELUARGA) {
            if (! empty($data['anggota'])) {
                $pengikut = $data['anggota']->filter(static fn ($item): bool => $item->umur < $minUmur);
            }
        } else {
            // cek apakah ada penduduk yang nik_ayah atau nik_ibu = nik pemohon
            $filterColumn = 'ibu_nik';
            if ($data['individu']['jenis_kelamin'] == JenisKelaminEnum::LAKI_LAKI) {
                $filterColumn = 'ayah_nik';
            }
            $anak = Penduduk::where($filterColumn, $data['individu']['nik'])->orderKeluarga()->withoutGlobalScope(App\Scopes\ConfigIdScope::class)->get();
            if ($anak) {
                $pengikut = $anak->filter(static fn ($item): bool => $item->umur < $minUmur);
            }
        }

        return $pengikut;
    }

    private function pengikutSuratKIS(array $data)
    {
        return Penduduk::where(['id_kk' => $data['individu']['id_kk']])->orderKeluarga()->get();
    }

    private function pengikutPindah(array $data)
    {
        return Penduduk::where(['id_kk' => $data['individu']['id_kk']])->orderKeluarga()->get();
    }

    private function groupByLabel($array)
    {
        return collect($array)->groupBy(static function ($item): string {
            $label = $item->label ?? '';
            if (empty($label)) {
                $label = underscore($item->nama, false);
            }

            return ucwords($label);
        });
    }

    private function notifikasiMobile($cetak, $id)
    {
        // notifikasi Mobile Admin
        try {
            $judul    = 'Pembuatan Surat - ' . $cetak['surat']['nama'];
            $kirimFCM = 'Segera cek Halaman Admin,  ' . $cetak['surat']['nama'] . ' berhasil dibuat.';

            $allToken = FcmToken::doesntHave('user.pamong')
                ->orWhereHas('user.pamong', static fn ($query) => $query->whereNotIn('jabatan_id', RefJabatan::getKadesSekdes()))
                ->get()
                ->pluck('token')
                ->all();

            $client       = new Fcm\FcmClient(FirebaseEnum::SERVER_KEY, FirebaseEnum::SENDER_ID);
            $notification = new Fcm\Push\Notification();

            $notification
                ->addRecipient($allToken)
                ->setTitle($judul)
                ->setBody($kirimFCM)
                ->addData('payload', '/permohonan/surat/periksa/' . $id . '/Periksa Surat');
            $client->send($notification);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
        }
        // akhir notifikasi Mobile Admin
    }
}
