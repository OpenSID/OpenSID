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

use App\Enums\DerajatSuratEnum;
use App\Enums\FirebaseEnum;
use App\Enums\KarakterSuratEnum;
use App\Enums\StatusEnum;
use App\Libraries\TinyMCE;
use App\Libraries\TinyMCE\KodeIsianGambar;
use App\Models\FcmToken;
use App\Models\LogSuratDinas;
use App\Models\Pamong;
use App\Models\RefJabatan;
use App\Models\SuratDinas;
use App\Models\SuratKeluar;
use App\Models\Urls;
use Carbon\Carbon;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;

defined('BASEPATH') || exit('No direct script access allowed');

class Surat_dinas_cetak extends Admin_Controller
{
    public $modul_ini     = 'surat-dinas';
    public $sub_modul_ini = 'cetak-surat-dinas';
    private TinyMCE $tinymce;

    public function __construct()
    {
        parent::__construct();
        $this->tinymce = new TinyMCE();
        $this->load->model(['penomoran_surat_model']);
    }

    public function index()
    {
        return view('admin.surat_dinas.cetak.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of((new SuratDinas())->kunci(SuratDinas::KUNCI_DISABLE)->orderBy('favorit', 'desc')->latest('updated_at'))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        if ($row->favorit) {
                            $aksi .= '<a href="' . ci_route('surat_dinas_cetak.form', $row->url_surat) . '" class="btn btn-social bg-olive btn-sm" title="Buat Surat"><i class="fa fa-file-word-o"></i>Buat Surat</a> ';
                            $aksi .= '<a href="' . ci_route("surat_dinas_cetak.favorit.{$row->id}", 1) . '" class="btn bg-olive btn-sm" title="Keluarkan dari Daftar Favorit"><i class="fa fa-star"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('surat_dinas_cetak.form', $row->url_surat) . '" class="btn btn-social bg-purple btn-sm" title="Buat Surat"><i class="fa fa-file-word-o"></i>Buat Surat</a> ';
                            $aksi .= '<a href="' . ci_route("surat_dinas_cetak.favorit.{$row->id}", 0) . '" class="btn bg-purple btn-sm" title="Tambahkan ke Daftar Favorit"><i class="fa fa-star-o"></i></a> ';
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
            $surat = SuratDinas::select(['id', 'nama', 'jenis', 'url_surat'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->orWhere('nama', 'like', "%{$cari}%");
                })->whereIn('jenis', SuratDinas::TINYMCE)
                ->kunci(SuratDinas::KUNCI_DISABLE)
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
        $this->session->unset_userdata('log_surat');
        $data['surat'] = SuratDinas::cetak($url)->first();

        if ($data['surat']) {
            $data['url']       = $url;
            $data['anchor']    = $this->input->post('anchor');
            $data['surat_url'] = rtrim($_SERVER['REQUEST_URI'], '/clear');

            // cek apakah surat itu memiliki form kategori ( saksi etc )
            $kategori = get_key_form_kategori($data['surat']['form_isian']);

            if (! empty($kategori)) {
                $form_kategori   = [];
                $kategori_isian  = [];
                $filter_kategori = collect($data['surat']->kode_isian)->filter(static function ($item) use (&$kategori_isian): bool {
                    $kategori_isian[$item->kategori][] = $item;

                    return isset($item->kategori);
                })->values();

                foreach ($kategori as $key => $ktg) {
                    // $form_kategori[$key]['form']       = $this->get_data_untuk_form($url, $data);
                    $form_kategori[$key]['kode_isian'] = $this->groupByLabel($kategori_isian[$key]);
                }
                $filtered_kode_isian = collect($data['surat']->kode_isian)->reject(static fn ($item): bool => isset($item->kategori))->values();

                $data['surat']['kode_isian'] = $this->groupByLabel($filtered_kode_isian);
                $data['form_kategori']       = $form_kategori;
            } else {
                $data['surat']['kode_isian'] = $this->groupByLabel($data['surat']->kode_isian);
            }
            $this->get_data_untuk_form($url, $data);

            // TODO:: Gunakan 1 list_dokumen untuk RTF dan TinyMCE
            $data['form_action'] = ci_route('surat_dinas_cetak.pratinjau', $url);

            $data['judul_kategori'] = collect($data['surat']->form_isian)->map(static fn ($item) => $item->label);
            $data['lampiran']       = explode(',', strtolower($data['surat']->lampiran));

            return view('admin.surat_dinas.cetak.form_desa', $data);
        }

        redirect_with('error', 'Surat tidak ditemukan');
    }

    public function pratinjau($url, $id = null)
    {
        $this->set_hak_akses_rfm();
        $surat = SuratDinas::cetak($url)->first();

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
                'karakter'        => $this->request['karakter'] ?? 1,
                'derajat'         => $this->request['derajat'] ?? 1,
            ];

            $log_surat['surat']     = $surat;
            $log_surat['input']     = $this->request;
            $setting_header         = $surat->header == StatusEnum::TIDAK ? '' : setting('header_surat');
            $setting_footer         = $surat->footer == StatusEnum::YA ? (setting('tte') == StatusEnum::YA ? setting('footer_surat_tte') : setting('footer_surat')) : '';
            $log_surat['isi_surat'] = preg_replace('/\\\\/', '', $setting_header) . '<!-- pagebreak -->' . ($surat->template_desa ?: $surat->template) . '<!-- pagebreak -->' . preg_replace('/\\\\/', '', $setting_footer);

            $isi_surat = $this->tinymce->gantiKodeIsian($log_surat, false);

            unset($log_surat['isi_surat']);
            $this->session->log_surat = $log_surat;

            $aksi_konsep = ci_route('surat_dinas_cetak.konsep');
            $aksi_cetak  = ci_route('surat_dinas_cetak.pdf');

            $id_surat = $surat->id;

            return view('admin.surat_dinas.cetak.konsep', ['aksi_konsep' => $aksi_konsep, 'aksi_cetak' => $aksi_cetak, 'isi_surat' => $isi_surat, 'id_surat' => $id_surat]);
        }

        set_session('error', "Data Surat {$surat->nama} tidak ditemukan");

        redirect("surat/form/{$url}");
    }

    public function pdf($preview = false)
    {
        // Cetak Konsep
        $cetak = $this->session->log_surat;
        if ($cetak) {
            $id_pamong = $this->ttd($cetak['input']['pilih_atas_nama'], $cetak['input']['pamong_id']);
            $pamong    = Pamong::find($id_pamong);
            $log_surat = [
                'id_format_surat' => $cetak['id_format_surat'],
                'id_pamong'       => $id_pamong,
                'nama_jabatan'    => $pamong->jabatan->nama,
                'nama_pamong'     => $pamong->pamong_nama,
                'id_user'         => auth()->id,
                'tanggal'         => Carbon::now(),
                'bulan'           => date('m'),
                'tahun'           => date('Y'),
                'no_surat'        => $preview ? '' : $cetak['input']['nomor'],
                'keterangan'      => $cetak['keterangan'],
                'karakter'        => $cetak['karakter'] ?? 1,
                'derajat'         => $cetak['derajat'] ?? 1,
            ];

            $log_surat['surat']     = $cetak['surat'];
            $log_surat['input']     = $cetak['input'];
            $log_surat['isi_surat'] = $this->request['isi_surat'];

            $isi_surat = $this->tinymce->gantiKodeIsian($log_surat, false);

            // Ubah jadi format pdf
            $isi_cetak = $this->tinymce->formatPdf($cetak['surat']->header, $cetak['surat']->footer, $isi_surat);

            $nama_surat = $this->nama_surat_arsip($cetak['surat']['url_surat'], $cetak['no_surat']);

            $log_surat['nama_surat'] = $nama_surat;
            $log_surat['input']      = json_encode($log_surat['input']);

            unset($log_surat['surat']);

            $surat = $cetak['id'] ? LogSuratDinas::find($cetak['id']) : new LogSuratDinas($log_surat);

            $keluar = json_decode($surat->input, true);

            if (! $preview && $keluar['surat_keluar']) {
                $format_surat = substitusiNomorSurat($cetak['input']['nomor'], $cetak['surat']['format_nomor_global'] ? setting('format_nomor_surat') : $cetak['surat']['format_nomor_surat']);
                $format_surat = str_ireplace('[kode_surat]', $cetak['surat']['kode_surat'], $format_surat);
                $format_surat = str_ireplace('[kode_desa]', identitas()->kode_desa, $format_surat);
                $format_surat = str_ireplace('[bulan_romawi]', bulan_romawi((int) (date('m'))), $format_surat);
                $format_surat = str_ireplace('[tahun]', date('Y'), $format_surat);
                $last_surat   = $this->penomoran_surat_model->get_surat_terakhir('surat_keluar');

                SuratKeluar::create([
                    'nomor_urut'    => $last_surat['no_surat'] + 1,
                    'nomor_surat'   => $format_surat,
                    'kode_surat'    => $surat->suratDinas->kode_surat,
                    'tanggal_surat' => tgl_indo_in($keluar['tanggal_surat']),
                    'tujuan'        => $keluar['tujuan'],
                    'isi_singkat'   => $keluar['isi_singkat'],
                    'berkas_scan'   => $surat->nama_surat,
                ]);
            }

            // Replace Gambar
            $data_gambar    = KodeIsianGambar::set($cetak['surat'], $isi_cetak, $surat);
            $isi_cetak      = $data_gambar['result'];
            $surat->urls_id = $data_gambar['urls_id'];

            if ($preview) {
                if (setting('tte') == 0) {
                    $qrCode = [
                        'isiqr'   => 'preview',
                        'urls_id' => 'url_preview',
                        'logoqr'  => gambar_desa($this->header['desa']['logo'], false, true),
                        'sizeqr'  => 6,
                        'foreqr'  => '#000000',
                    ];

                    $qrcode    = '<img src="' . qrcode_generate($qrCode) . '" width="90" height="90" alt="qrcode-surat" />';
                    $isi_cetak = str_replace('[qr_code]', $qrcode, $isi_cetak);
                }
                // jika preview hapus data pada urls
                if ($surat->urls_id) {
                    Urls::destroy($surat->urls_id);
                }
            }

            $margin_cm_to_mm = $cetak['surat']['margin_cm_to_mm'];
            if ($cetak['surat']['margin_global'] == '1') {
                $margin_cm_to_mm = setting('surat_margin_cm_to_mm');
            }

            // convert in PDF
            try {
                $this->tinymce->generateSurat($isi_cetak, $cetak, $margin_cm_to_mm);
                $this->tinymce->generateLampiran(null, $cetak, $cetak['input']);

                if ($preview) {
                    $this->tinymce->pdfMerge->merge('document.pdf', 'I');
                } else {
                    // Untuk surat yang sudah dicetak, simpan isian suratnya yang sudah jadi (siap di konversi)
                    $surat->isi_surat = $isi_cetak;
                    $surat->status    = LogSuratDinas::CETAK;

                    $this->tinymce->pdfMerge->merge(FCPATH . LOKASI_ARSIP . $nama_surat, 'FI');
                }
            } catch (Html2PdfException $e) {
                $formatter = new ExceptionFormatter($e);
                log_message('error', trim(preg_replace('/\s\s+/', ' ', $formatter->getMessage())));

                return $this->output
                    ->set_status_header(404, str_replace("\n", ' ', $formatter->getMessage()))
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'statusText' => $formatter->getMessage(),
                    ], JSON_THROW_ON_ERROR));
            }

            if (! $preview) {
                // Jika verifikasi sekdes atau verifikasi kades di non-aktifkan
                $surat->verifikasi_operator = (setting('verifikasi_sekdes') || setting('verifikasi_kades')) ? LogSuratDinas::PERIKSA : LogSuratDinas::TERIMA;

                $surat->save();
                if ($surat->urls_id) {
                    $surat->urlId->update(['url' => ci_route('c1.' . $surat->id . '.surat_dinas')]);
                }
                header('id_arsip: ' . $surat->id); // sisipkan id

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
                        ->addData('payload', '/permohonan/surat/periksa/' . $surat->id . '/Periksa Surat');
                    $client->send($notification);
                } catch (Exception $e) {
                    log_message('error', $e->getMessage());
                }
                // akhir notifikasi Mobile Admin
            }

            redirect('surat_dinas_cetak');
        } else {
            redirect_with('error', 'Tidak ada surat yang akan dicetak.');
        }
    }

    public function konsep(): void
    {
        $cetak = $this->session->log_surat;

        if ($cetak) {
            $id_pamong = $this->ttd($cetak['input']['pilih_atas_nama'], $cetak['input']['pamong_id']);
            $pamong    = Pamong::find($id_pamong);
            $log_surat = [
                'id_format_surat' => $cetak['id_format_surat'],
                'id_pamong'       => $id_pamong,
                'nama_jabatan'    => $pamong->jabatan->nama,
                'nama_pamong'     => $pamong->pamong_nama,
                'id_user'         => auth()->id,
                'tanggal'         => Carbon::now(),
                'karakter'        => $cetak['karakter'] ?? 1,
                'derajat'         => $cetak['derajat'] ?? 1,
                'input'           => json_encode($cetak['input']),
            ];
            $log_surat['verifikasi_operator'] = 0;

            $isi_surat = $this->request['isi_surat'];

            // Kembalikan kode isian [format_nomor_surat]
            $format_surat = substitusiNomorSurat($cetak['input']['nomor'], $cetak['surat']['format_nomor_global'] ? setting('format_nomor_surat') : $cetak['surat']['format_nomor_surat']);
            $format_surat = str_ireplace('[kode_surat]', $cetak['surat']['kode_surat'], $format_surat);
            $format_surat = str_ireplace('[kode_desa]', identitas()->kode_desa, $format_surat);
            $format_surat = str_ireplace('[bulan_romawi]', bulan_romawi((int) (date('m'))), $format_surat);
            $format_surat = str_ireplace('[tahun]', date('Y'), $format_surat);

            $isi_surat = str_ireplace($format_surat, '[format_nomor_surat]', $isi_surat);

            // Kembalikan kode isian [tgl_surat]
            $tgl_surat = tgl_indo($log_surat['tanggal']);
            $isi_surat = str_replace($tgl_surat, '[tgl_surat]', $isi_surat);

            // Hanya simpan isian surat
            $isi_surat = explode('<!-- pagebreak -->', $isi_surat)[1];

            $log_surat['isi_surat'] = $isi_surat;

            // Jika verifikasi sekdes atau verifikasi kades di non-aktifkan
            $log_surat['verifikasi_operator'] = (setting('verifikasi_sekdes') || setting('verifikasi_kades')) ? LogSuratDinas::PERIKSA : LogSuratDinas::TERIMA;

            if (LogSuratDinas::updateOrCreate(['id' => $cetak['id']], $log_surat)) {
                redirect_with('success', 'Berhasil Simpan Konsep');
            }
        }

        redirect_with('success', 'Gagal Simpan Konsep');
    }

    public function cetak($id)
    {
        $surat = LogSuratDinas::find($id);

        if ($surat->status && $surat->verifikasi_operator != '-1') {
            $this->tinymce->cetak_surat($id);
        } else {
            $log_surat = [
                'id_format_surat' => $surat->id_format_surat,
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

            $log_surat['no_surat'] = LogSuratDinas::lastNomerSurat($surat->suratDinas->url_surat)['no_surat_berikutnya'];
            $log_surat['surat']    = $surat->suratDinas;
            $log_surat['input']    = [
                // 1. Nomer surat dicek dan dibuat ulang
                'nomor'           => $log_surat['no_surat'],
                'pilih_atas_nama' => $atas_nama,
                'pamong_id'       => $pamong->pamong_id,
            ];

            if ($surat->verifikasi_operator != '-1') {
                $log_surat['isi_surat'] = preg_replace('/\\\\/', '', setting('header_surat')) . '<!-- pagebreak -->' . ($surat->isi_surat) . '<!-- pagebreak -->' . preg_replace('/\\\\/', '', setting('footer_surat'));
            } else {
                $log_surat['isi_surat'] = preg_replace('/\\\\/', '', ($surat->isi_surat));
            }

            $log_surat['id'] = $surat->id;
            $isi_surat       = $this->tinymce->gantiKodeIsian($log_surat);

            unset($log_surat['isi_surat']);
            $this->session->log_surat = $log_surat;

            $aksi_konsep = ci_route('surat_dinas_cetak.konsep');
            $aksi_cetak  = ci_route('surat_dinas_cetak.pdf');
            $tolak       = $surat->verifikasi_operator;
            $id_surat    = $surat->id;

            return view('admin.surat_dinas.cetak.konsep', ['aksi_konsep' => $aksi_konsep, 'aksi_cetak' => $aksi_cetak, 'isi_surat' => $isi_surat, 'id_surat' => $id_surat, 'tolak' => $tolak]);
        }
    }

    private function ttd($ttd = '', $pamong_id = null)
    {
        if (preg_match('/a.n/i', $ttd)) {
            return Pamong::ttd('a.n')->first()->pamong_id;
        }
        if (preg_match('/u.b/i', $ttd)) {
            return $pamong_id;
        }

        return Pamong::kepalaDesa()->first()->pamong_id;
    }

    private function nama_surat_arsip($url, $nomor)
    {
        $nomor_surat = str_replace("'", '', $nomor);
        $nomor_surat = preg_replace('/[^a-zA-Z0-9.	]/', '-', $nomor_surat);

        return $url . '_' . date('Y-m-d') . '_' . $nomor_surat . '.pdf';
    }

    public function nomor_surat_duplikat(): void
    {
        $hasil = LogSuratDinas::isDuplikat('log_surat', $_POST['nomor'], $_POST['url']);
        echo $hasil ? 'false' : 'true';
    }

    // Data yang digunakan surat jenis rtf dan tinymce
    private function get_data_untuk_form($url, array &$data): void
    {
        // TinyMCE
        $data['surat_terakhir']     = LogSuratDinas::lastNomerSurat($url);
        $data['input']              = $this->input->post();
        $data['input']['nomor']     = $data['surat_terakhir']['no_surat_berikutnya'];
        $data['format_nomor_surat'] = SuratDinas::format_penomoran_surat($data);

        $penandatangan          = $this->tinymce->formPenandatangan();
        $data['pamong']         = $penandatangan['penandatangan'];
        $data['atas_nama']      = $penandatangan['atas_nama'];
        $data['karakter_surat'] = KarakterSuratEnum::all();
        $data['derajat_surat']  = DerajatSuratEnum::all();
    }

    public function favorit($id = null, $val = 0): void
    {
        isCan('u');

        $favorit = SuratDinas::findOrFail($id);
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
        $data['surat']          = SuratDinas::where('url_surat', $this->input->post('url'));
        $data['input']['nomor'] = $this->input->post('nomor');
        $format_nomor           = SuratDinas::format_penomoran_surat($data);
        echo json_encode($format_nomor, JSON_THROW_ON_ERROR);
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
}
