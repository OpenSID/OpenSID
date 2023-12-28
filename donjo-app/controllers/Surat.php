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
use App\Enums\StatusSuratKecamatanEnum;
use App\Libraries\TinyMCE;
use App\Models\FormatSurat;
use App\Models\Keluarga;
use App\Models\LogSurat;
use App\Models\Pamong;
use App\Models\Penduduk;
use Carbon\Carbon;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

defined('BASEPATH') || exit('No direct script access allowed');

class Surat extends Admin_Controller
{
    private $tinymce;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['penduduk_model', 'keluarga_model', 'surat_model', 'keluar_model', 'penomoran_surat_model', 'permohonan_surat_model']);
        $this->modul_ini     = 'layanan-surat';
        $this->sub_modul_ini = 'cetak-surat';
        $this->tinymce       = new TinyMCE();
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(FormatSurat::kunci(FormatSurat::KUNCI_DISABLE)->orderBy('favorit', 'desc')->latest('updated_at'))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
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
                ->addColumn('jenis', static function ($row) {
                    return in_array($row->jenis, FormatSurat::RTF) ? 'RTF' : 'TinyMCE';
                })
                ->editColumn('lampiran', static function ($row) {
                    return kode_format($row->lampiran);
                })
                ->rawColumns(['aksi', 'template_surat'])
                ->make();
        }

        return view('admin.surat.index');
    }

    public function apidaftarsurat()
    {
        if ($this->input->is_ajax_request()) {
            $cari = $this->input->get('q');

            $surat = FormatSurat::select(['id', 'nama', 'jenis', 'url_surat'])
                ->when($cari, static function ($query) use ($cari) {
                    $query->orWhere('nama', 'like', "%{$cari}%");
                })
                ->kunci(FormatSurat::KUNCI_DISABLE)
                ->latest('updated_at')
                ->orderBy('favorit', 'desc')
                ->paginate(10);

            return json([
                'results' => collect($surat->items())
                    ->map(static function ($item) {
                        return [
                            'id'   => $item->url_surat,
                            'text' => ($item->jenis == 1 ? '[RTF]' : '[TinyMCE]') . " - {$item->nama}",
                        ];
                    }),
                'pagination' => [
                    'more' => $surat->currentPage() < $surat->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    public function form($url = '', $id = '')
    {
        $nik = $this->input->post('nik') ?? $id;

        $this->session->unset_userdata('log_surat');

        $data['surat'] = FormatSurat::cetak($url)->first();

        if ($data['surat']) {
            $data['url']       = $url;
            $data['anchor']    = $this->input->post('anchor');
            $data['surat_url'] = rtrim($_SERVER['REQUEST_URI'], '/clear');

            // NIK => id
            if (! empty($nik)) {
                if (in_array($data['surat']['jenis'], FormatSurat::RTF)) {
                    $data['individu'] = $this->surat_model->get_penduduk($nik);
                    $data['anggota']  = $this->keluarga_model->list_anggota($data['individu']['id_kk'], ['dengan_kk' => true], true);
                } else {
                    $data['individu'] = Penduduk::findOrFail($nik);
                    $data['anggota']  = null;
                }
            } else {
                $data['individu'] = null;
                $data['anggota']  = null;
            }

            $this->get_data_untuk_form($url, $data);

            if (in_array($data['surat']['jenis'], FormatSurat::RTF)) {
                $data['form_action'] = site_url("surat/doc/{$url}");
                $data_form           = $this->surat_model->get_data_form($url);
                if (is_file($data_form)) {
                    include $data_form;
                }

                return $this->render('surat/form_surat', $data);
            }
            // TODO:: Gunakan 1 list_dokumen untuk RTF dan TinyMCE
            $data['list_dokumen'] = empty($nik) ? null : $this->penduduk_model->list_dokumen($data['individu']['id']);
            $data['form_action']  = route('surat.pratinjau', $url);

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
        }

        $surat     = FormatSurat::cetak($url)->first();
        $kecamatan = $surat->kecamatan == StatusEnum::TIDAK ? StatusSuratKecamatanEnum::TidakAktif : StatusSuratKecamatanEnum::BelumDikirim;

        if ($surat && $this->request) {
            // Simpan data ke log_surat sebagai draf
            $id_pamong = $this->ttd($this->request['pilih_atas_nama'], $this->request['pamong_id']);
            $pamong    = Pamong::find($id_pamong);
            $log_surat = [
                'id_format_surat' => $surat->id,
                'id_pend'         => $this->request['nik'],
                'nama_non_warga'  => $this->request['nama_non_warga'],
                'nik_non_warga'   => $this->request['nik_non_warga'],
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

            $log_surat['surat']     = $surat;
            $log_surat['input']     = $this->request;
            $setting_header         = $surat->header == StatusEnum::YA ? setting('header_surat') : '';
            $setting_footer         = $surat->footer == StatusEnum::YA ? (setting('tte') == StatusEnum::YA ? setting('footer_surat_tte') : setting('footer_surat')) : '';
            $log_surat['isi_surat'] = preg_replace('/\\\\/', '', $setting_header) . '<!-- pagebreak -->' . ($surat->template_desa ?: $surat->template) . '<!-- pagebreak -->' . preg_replace('/\\\\/', '', $setting_footer);

            // Lewati ganti kode_isian
            $isi_surat = $this->tinymce->replceKodeIsian($log_surat);

            unset($log_surat['isi_surat']);
            $this->session->log_surat = $log_surat;

            $aksi_konsep = site_url('surat/konsep');
            $aksi_cetak  = site_url('surat/pdf');

            $id_surat = $surat->id;

            return view('admin.surat.konsep', compact('content', 'aksi_konsep', 'aksi_cetak', 'isi_surat', 'id_surat'));
        }

        set_session('error', "Data Surat {$surat->nama} tidak ditemukan");

        redirect("surat/form/{$url}");
    }

    public function pdf($preview = false)
    {
        // Cetak Konsep
        $cetak = $this->session->log_surat;
        if ($cetak) {
            $id_pamong = $this->ttd($this->request['pilih_atas_nama'], $this->request['pamong_id']);
            $pamong    = Pamong::find($id_pamong);
            $log_surat = [
                'id_format_surat' => $cetak['id_format_surat'],
                'id_pend'         => $cetak['id_pend'], // nik = id_pend
                'id_pamong'       => $id_pamong,
                'nama_jabatan'    => $pamong->jabatan->nama,
                'nama_pamong'     => $pamong->pamong_nama,
                'id_user'         => auth()->id,
                'tanggal'         => Carbon::now(),
                'bulan'           => date('m'),
                'tahun'           => date('Y'),
                'no_surat'        => $cetak['input']['nomor'],
                'keterangan'      => $cetak['keterangan'],
                'kecamatan'       => $cetak['kecamatan'],
            ];

            if ($nik = $cetak['input']['nik']) {
                $nik = Penduduk::find($nik)->nik;
            } else {
                // Surat untuk non-warga
                $log_surat['nama_non_warga'] = $cetak['input']['nama_non_warga'];
                $log_surat['nik_non_warga']  = $cetak['input']['nik_non_warga'];
                $nik                         = $log_surat['nik_non_warga'];
            }

            $log_surat['surat']     = $cetak['surat'];
            $log_surat['input']     = $cetak['input'];
            $log_surat['isi_surat'] = $this->request['isi_surat'];

            $isi_surat = $this->tinymce->replceKodeIsian($log_surat);

            // Pisahkan isian surat
            $isi_surat  = str_replace('<p><!-- pagebreak --></p>', '', $isi_surat);
            $isi        = explode('<!-- pagebreak -->', $isi_surat);
            $backtop    = $cetak['surat']->header == 0 ? 0 : (((float) setting('tinggi_header')) * 10) . 'mm';
            $backbottom = $cetak['surat']->footer == 0 ? 0 : (((float) setting('tinggi_footer')) * 10) . 'mm';

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

            $nama_surat = $this->nama_surat_arsip($cetak['surat']['url_surat'], $nik, $cetak['no_surat']);

            $log_surat['nama_surat'] = $nama_surat;

            unset($log_surat['surat'], $log_surat['input']);
            $id    = LogSurat::updateOrCreate(['id' => $cetak['id']], $log_surat)->id;
            $surat = LogSurat::findOrFail($id);

            // Logo Surat
            $file_logo = ($cetak['surat']['logo_garuda'] ? FCPATH . LOGO_GARUDA : gambar_desa(identitas()->logo, false, true));

            $logo      = (is_file($file_logo)) ? '<img src="' . $file_logo . '" width="90" height="90" alt="logo-surat" />' : '';
            $logo_bsre = str_replace('[logo]', $logo, $isi_cetak);

            // Logo BSrE
            $file_logo_bsre = FCPATH . LOGO_BSRE;
            $bsre           = (is_file($file_logo_bsre) && setting('tte') == 1) ? '<img src="' . $file_logo_bsre . '" height="90" alt="logo-bsre" />' : '';
            $logo_qrcode    = str_replace('[logo_bsre]', $bsre, $logo_bsre);

            // QR_Code Surat
            if ($cetak['surat']['qr_code'] && ((setting('tte') == 1 && $surat->verifikasi_kades == LogSurat::TERIMA) || (setting('tte') == 0))) {
                $cek = $this->surat_model->buatQrCode($surat->nama_surat);

                $qrcode         = ($cek['viewqr']) ? '<img src="' . $cek['viewqr'] . '" width="90" height="90" alt="qrcode-surat" />' : '';
                $logo_qrcode    = str_replace('[qr_code]', $qrcode, $logo_qrcode);
                $surat->urls_id = $cek['urls_id'];
            } else {
                $logo_qrcode = str_replace('[qr_code]', '', $logo_qrcode);
            }

            // Lampiran
            $logo_qrcode = $this->buatLampiran($id, $cetak, $logo_qrcode);

            // convert in PDF
            try {
                $html2pdf = new Html2Pdf($cetak['surat']['orientasi'], $cetak['surat']['ukuran'], 'en', true, 'UTF-8', $cetak['surat']['margin_cm_to_mm']);
                $html2pdf->setTestTdInOnePage(true);
                $html2pdf->setDefaultFont(underscore(setting('font_surat'), true, true));
                $html2pdf->writeHTML($logo_qrcode);
                if ($preview) {
                    $html2pdf->output(tempnam(sys_get_temp_dir(), '') . '.pdf', 'FI');
                } else {
                    $html2pdf->output(FCPATH . LOKASI_ARSIP . $nama_surat, 'FI');

                    // Untuk surat yang sudah dicetak, simpan isian suratnya yang sudah jadi (siap di konversi)
                    $surat->isi_surat = $isi_cetak;
                    $surat->status    = LogSurat::CETAK;
                }
            } catch (Html2PdfException $e) {
                $html2pdf->clean();
                $formatter = new ExceptionFormatter($e);
                log_message('error', $formatter->getHtmlMessage());

                // Untuk surat yang sudah tersimpan sebagai draf, simpan isian suratnya yang belum jadi (hanya isian surat dari konversi template surat)
                $surat->isi_surat = $isi[1];
                $surat->status    = LogSurat::KONSEP;
            }

            if ($preview) {
                LogSurat::destroy($id);
            } else {
                // Jika verifikasi sekdes atau verifikasi kades di non-aktifkan
                $surat->verifikasi_operator = (setting('verifikasi_sekdes') || setting('verifikasi_kades')) ? LogSurat::PERIKSA : LogSurat::TERIMA;

                $surat->save();
            }

            redirect('surat');
        } else {
            redirect_with('error', 'Tidak ada surat yang akan dicetak.');
        }
    }

    public function konsep()
    {
        $cetak = $this->session->log_surat;

        if ($cetak) {
            $id_pamong = $this->ttd($this->request['pilih_atas_nama'], $this->request['pamong_id']);
            $pamong    = Pamong::find($id_pamong);
            $log_surat = [
                'id_format_surat' => $cetak['id_format_surat'],
                'id_pend'         => $cetak['id_pend'], // nik = id_pend
                'id_pamong'       => $id_pamong,
                'nama_jabatan'    => $pamong->jabatan->nama,
                'nama_pamong'     => $pamong->pamong_nama,
                'id_user'         => auth()->id,
                'tanggal'         => Carbon::now(),
                'kecamatan'       => $cetak['kecamatan'],
            ];
            $log_surat['verifikasi_operator'] = 0;

            if ($nik = $cetak['input']['nik']) {
                $nik = Penduduk::find($nik)->nik;
            } else {
                // Surat untuk non-warga
                $log_surat['nama_non_warga'] = $cetak['input']['nama_non_warga'];
                $log_surat['nik_non_warga']  = $cetak['input']['nik_non_warga'];
                $nik                         = $log_surat['nik_non_warga'];
            }

            $isi_surat = $this->request['isi_surat'];

            // Kembalikan kode isian [format_nomor_surat]
            $format_surat = $this->tinymce->substitusiNomorSurat($cetak['input']['nomor'], setting('format_nomor_surat'));
            $format_surat = str_replace('[kode_surat]', $cetak['surat']['kode_surat'], $format_surat);
            $format_surat = str_replace('[kode_desa]', identitas()->kode_desa, $format_surat);
            $format_surat = str_replace('[bulan_romawi]', bulan_romawi((int) (date('m'))), $format_surat);
            $format_surat = str_replace('[tahun]', date('Y'), $format_surat);

            $isi_surat = str_replace($format_surat, '[format_nomor_surat]', $isi_surat);

            // Kembalikan kode isian [tgl_surat]
            $tgl_surat = tgl_indo($log_surat['tanggal']);
            $isi_surat = str_replace($tgl_surat, '[tgl_surat]', $isi_surat);

            // Hanya simpan isian surat
            $isi_surat = explode('<!-- pagebreak -->', $isi_surat)[1];

            $log_surat['isi_surat'] = $isi_surat;

            // Jika verifikasi sekdes atau verifikasi kades di non-aktifkan
            $log_surat['verifikasi_operator'] = (setting('verifikasi_sekdes') || setting('verifikasi_kades')) ? LogSurat::PERIKSA : LogSurat::TERIMA;

            if (LogSurat::updateOrCreate(['id' => $cetak['id']], $log_surat)) {
                redirect_with('success', 'Berhasil Simpan Konsep');
            }
        }

        redirect_with('success', 'Gagal Simpan Konsep');
    }

    public function cetak($id)
    {
        $surat = LogSurat::find($id);

        if ($surat->status && $surat->verifikasi_operator != '-1') {
            // Cek ada file
            if (file_exists(FCPATH . LOKASI_ARSIP . $surat->nama_surat)) {
                return ambilBerkas($surat->nama_surat, $this->controller, null, LOKASI_ARSIP, true);
            }

            $isi_cetak      = $surat->isi_surat;
            $nama_surat     = $surat->nama_surat;
            $cetak['surat'] = $surat->formatSurat;

            // Logo Surat
            $file_logo = ($cetak['surat']['logo_garuda'] ? FCPATH . LOGO_GARUDA : gambar_desa(identitas()->logo, false, true));

            $logo      = (is_file($file_logo)) ? '<img src="' . $file_logo . '" width="90" height="90" alt="logo-surat" />' : '';
            $isi_cetak = str_replace('[logo]', $logo, $isi_cetak);

            // Logo BSrE
            $file_logo_bsre = FCPATH . LOGO_BSRE;
            $bsre           = (is_file($file_logo_bsre) && setting('tte') == 1) ? '<img src="' . $file_logo_bsre . '" height="90" alt="logo-bsre" />' : '';
            $isi_cetak      = str_replace('[logo_bsre]', $bsre, $isi_cetak);

            // QR_Code Surat
            if ($cetak['surat']['qr_code']) {
                $cek       = $this->surat_model->buatQrCode($nama_surat);
                $qrcode    = ($cek['viewqr']) ? '<img src="' . $cek['viewqr'] . '" width="90" height="90" alt="qrcode-surat" />' : '';
                $isi_cetak = str_replace('[qr_code]', $qrcode, $isi_cetak);
            } else {
                $isi_cetak = str_replace('[qr_code]', '', $isi_cetak);
            }

            // Lampiran
            $isi_cetak = $this->buatLampiran($surat->id_pend, $cetak, $isi_cetak);

            // convert in PDF
            try {
                $html2pdf = new Html2Pdf($cetak['surat']['orientasi'], $cetak['surat']['ukuran'], 'en', true, 'UTF-8', $cetak['surat']['margin_cm_to_mm']);
                $html2pdf->setTestTdInOnePage(false);
                $html2pdf->setDefaultFont(underscore(setting('font_surat'), true, true));
                $html2pdf->writeHTML($isi_cetak);
                // $html2pdf->output($nama_surat, 'D');
                $html2pdf->output(FCPATH . LOKASI_ARSIP . $nama_surat, 'FI');
            } catch (Html2PdfException $e) {
                $html2pdf->clean();
                $formatter = new ExceptionFormatter($e);
                log_message('error', $formatter->getHtmlMessage());
            }
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

            $log_surat['no_surat'] = $this->surat_model->get_last_nosurat_log($surat->url_surat)['no_surat_berikutnya'];
            $log_surat['surat']    = $surat->formatSurat;
            $log_surat['input']    = [
                'nik'            => $surat->id_pend,
                'nama_non_warga' => $surat->nama_non_warga,
                'nik_non_warga'  => $surat->nik_non_warga,

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
            $isi_surat       = $this->tinymce->replceKodeIsian($log_surat);

            unset($log_surat['isi_surat']);
            $this->session->log_surat = $log_surat;

            $aksi_konsep = site_url('surat/konsep');
            $aksi_cetak  = site_url('surat/pdf');
            $tolak       = $surat->verifikasi_operator;
            $id_surat    = $surat->id;

            return view('admin.surat.konsep', compact('content', 'aksi_konsep', 'aksi_cetak', 'isi_surat', 'id_surat', 'tolak'));
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

    private function nama_surat_arsip($url, $nik, $nomor)
    {
        $nomor_surat = str_replace("'", '', $nomor);
        $nomor_surat = preg_replace('/[^a-zA-Z0-9.	]/', '-', $nomor_surat);

        return $url . '_' . $nik . '_' . date('Y-m-d') . '_' . $nomor_surat . '.pdf';
    }

    public function periksa_doc($id, $url)
    {
        // Ganti status menjadi 'Menunggu Tandatangan'
        $this->permohonan_surat_model->proses($id, 2);
        $this->cetak_doc($url);
    }

    public function doc($url = '')
    {
        $this->load->config('develbar', false);
        $this->cetak_doc($url);
        $this->load->config('develbar', true);
    }

    private function cetak_doc($url)
    {
        $format                    = $this->surat_model->get_surat($url);
        $id_pamong                 = $this->ttd($this->request['pilih_atas_nama'], $this->request['pamong_id']);
        $pamong                    = Pamong::find($id_pamong);
        $log_surat['url_surat']    = $format['id'];
        $log_surat['nama_jabatan'] = $pamong->jabatan->nama;
        $log_surat['nama_pamong']  = $pamong->pamong_nama;
        $log_surat['id_user']      = $_SESSION['user'];
        $log_surat['no_surat']     = $_POST['nomor'];
        $id                        = $_POST['nik'];
        $keperluan                 = $_POST['keperluan'];
        $keterangan                = $_POST['keterangan'];
        $log_surat['id_pamong']    = $id_pamong;

        switch ($url) {
            case 'surat_ket_kelahiran':
                // surat_ket_kelahiran id-nya ibu atau bayi
                if (! $id) {
                    $id = $_SESSION['id_ibu'];
                }
                if (! $id) {
                    $id = $_SESSION['id_bayi'];
                }
                break;

            case 'surat_ket_nikah':
                // id-nya calon pasangan pria atau wanita
                if (! $id) {
                    $id = $_POST['id_pria'];
                }
                if (! $id) {
                    $id = $_POST['id_wanita'];
                }
                break;

            case 'surat_kuasa':
                // id-nya pemberi kuasa atau penerima kuasa
                if (! $id) {
                    $id = $_POST['id_pemberi_kuasa'];
                }
                if (! $id) {
                    $id = $_POST['id_penerima_kuasa'];
                }
                break;

            default:
                // code...
                break;
        }

        if ($id) {
            $log_surat['id_pend'] = $id;
            // TODO: Sederhanakan query ini, pindahkan ke model
            $nik = $this->db->select('nik')->where('config_id', identitas('id'))->where('id', $id)->get('tweb_penduduk')->row()->nik;
        } else {
            // Surat untuk non-warga
            $log_surat['nama_non_warga'] = $_POST['nama_non_warga'];
            $log_surat['nik_non_warga']  = $_POST['nik_non_warga'];
            $nik                         = $log_surat['nik_non_warga'];
        }

        $log_surat['keterangan'] = $keterangan ?: $keperluan;
        $nama_surat              = $this->keluar_model->nama_surat_arsip($url, $nik, $_POST['nomor']);
        $log_surat['nama_surat'] = $nama_surat;
        if ($format['lampiran']) {
            $lampiran              = pathinfo($nama_surat, PATHINFO_FILENAME) . '_lampiran.pdf';
            $log_surat['lampiran'] = $lampiran;
        }
        $log_surat['verifikasi_operator'] = LogSurat::TERIMA;
        $this->keluar_model->log_surat($log_surat);

        $surat      = $this->surat_model->buat_surat($url, $nama_surat, $lampiran);
        $nama_surat = $surat['namaSurat'];

        // TODO: Sederhanakan query ini, pindahkan ke model
        // Update urls_id log_surat (untuk link qrcode)
        $this->db->where('config_id', identitas('id'))->where('nama_surat', $nama_surat)->update('log_surat', ['urls_id' => $surat['qrCode']['urls_id']]);

        if (function_exists('exec') && $this->input->post('submit_cetak') == 'cetak_pdf') {
            $nama_surat = $this->surat_model->rtf_to_pdf($nama_surat);
        }

        if ($lampiran) {
            $this->load->library('zip');

            $this->zip->read_file(LOKASI_ARSIP . $nama_surat);
            $this->zip->read_file(LOKASI_ARSIP . $lampiran);
            $this->zip->download(pathinfo($nama_surat, PATHINFO_FILENAME) . '.zip');
        } else {
            ambilBerkas($nama_surat, $this->controller);
        }
    }

    public function nomor_surat_duplikat()
    {
        $hasil = $this->penomoran_surat_model->nomor_surat_duplikat('log_surat', $_POST['nomor'], $_POST['url']);
        echo $hasil ? 'false' : 'true';
    }

    public function search()
    {
        $cari = $this->input->post('nik');
        if ($cari != '') {
            redirect("surat/form/{$cari}");
        } else {
            redirect('surat');
        }
    }

    // Data yang digunakan surat jenis rtf dan tinymce
    private function get_data_untuk_form($url, &$data)
    {
        // RTF
        if (in_array($data['surat']['jenis'], FormatSurat::RTF)) {
            $data['config']    = $data['lokasi'] = $this->header['desa'];
            $data['penduduk']  = $this->surat_model->list_penduduk();
            $data['perempuan'] = $this->surat_model->list_penduduk_perempuan();
        } else {
            // TinyMCE
            // Data penduduk diambil sesuai pengaturan surat
            if ($data['surat']['form_isian']->data == 2) {
                $data['penduduk'] = null;
                $data['anggota']  = null;
            } else {
                $filters          = collect($data['surat']['form_isian']->individu)->toArray();
                $data['penduduk'] = Penduduk::filters($filters)->get();
                $kk_level         = $data['individu']['kk_level'];
                $ada_anggota      = ($filters['kk_level'] == SHDKEnum::KEPALA_KELUARGA || $kk_level == SHDKEnum::KEPALA_KELUARGA) ? true : false;

                if ($ada_anggota) {
                    $data['anggota'] = Keluarga::find($data['individu']['id_kk'])->anggota;
                } else {
                    $data['anggota'] = null;
                }
            }
        }

        $data['surat_terakhir']     = $this->surat_model->get_last_nosurat_log($url);
        $data['input']              = $this->input->post();
        $data['input']['nomor']     = $data['surat_terakhir']['no_surat_berikutnya'];
        $data['format_nomor_surat'] = $this->penomoran_surat_model->format_penomoran_surat($data);

        $penandatangan     = $this->tinymce->formPenandatangan();
        $data['pamong']    = $penandatangan['penandatangan'];
        $data['atas_nama'] = $penandatangan['atas_nama'];
    }

    public function favorit($id = null, $val = 0)
    {
        $this->redirect_hak_akses('u');

        $favorit = FormatSurat::findOrFail($id);
        $favorit->update(['favorit' => ($val == 1) ? 0 : 1]);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    /*
        Ajax POST data:
        url -- url surat
        nomor -- nomor surat
    */
    public function format_nomor_surat()
    {
        $data['surat']          = $this->surat_model->get_surat($this->input->post('url'));
        $data['input']['nomor'] = $this->input->post('nomor');
        $format_nomor           = $this->penomoran_surat_model->format_penomoran_surat($data);
        echo json_encode($format_nomor);
    }

    /*
        Ajax url query data:
        q -- kata pencarian
        page -- nomor paginasi
    */
    public function list_penduduk_ajax()
    {
        $cari          = $this->input->get('q');
        $page          = $this->input->get('page');
        $filter_sex    = $this->input->get('filter_sex');
        $filter['sex'] = ($filter_sex == 'perempuan') ? 2 : $filter_sex;
        $penduduk      = $this->surat_model->list_penduduk_ajax($cari, $filter, $page);
        echo json_encode($penduduk);
    }

    // list untuk dropdown arsip layanan tampil hanya yg bersurat saja
    public function list_penduduk_bersurat_ajax()
    {
        $cari     = $this->input->get('q');
        $page     = $this->input->get('page');
        $penduduk = $this->surat_model->list_penduduk_bersurat_ajax($cari, $page);
        echo json_encode($penduduk);
    }

    private function buatLampiran($id = null, $data = [], $view_surat = null)
    {
        // Catatan : untuk sekarang hanya bisa menggunakan 1 lampiran saja untuk surat TinyMCE
        if (empty($data['surat']['lampiran'])) {
            return $view_surat;
        }

        $surat    = $data['surat'];
        $config   = $this->header['desa'];
        $individu = $this->surat_model->get_data_surat($id);
        $lampiran = strtolower($surat['lampiran']);

        // Cek lampiran desa
        $view_lampiran = FCPATH . LOKASI_LAMPIRAN_SURAT_DESA . $lampiran . '/view.php';

        if (! file_exists($view_lampiran)) {
            $view_lampiran = FCPATH . DEFAULT_LOKASI_LAMPIRAN_SURAT . $lampiran . '/view.php';
        }

        $data_lampiran = FCPATH . LOKASI_LAMPIRAN_SURAT_DESA . $lampiran . '/data.php';
        if (! file_exists($data_lampiran)) {
            $data_lampiran = FCPATH . DEFAULT_LOKASI_LAMPIRAN_SURAT . $lampiran . '/data.php';
        }

        // Data lampiran
        include $data_lampiran;

        ob_start();

        // View Lampiran
        include $view_lampiran;

        $content = ob_get_clean();

        return $view_surat . $content;
    }

    public function apipenduduksurat()
    {
        if ($this->input->is_ajax_request()) {
            $cari    = $this->input->get('q');
            $filters = collect(FormatSurat::select('form_isian')->find($this->input->get('surat'))->form_isian->individu)->toArray();

            $penduduk = Penduduk::select(['id', 'nik', 'tag_id_card', 'nama', 'id_cluster'])
                ->when($cari, static function ($query) use ($cari) {
                    $query->orWhere('nik', 'like', "%{$cari}%")
                        ->orWhere('tag_id_card', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                })
                ->filters($filters)
                ->paginate(10);

            return json([
                'results' => collect($penduduk->items())
                    ->map(static function ($item) {
                        return [
                            'id'   => $item->id,
                            'text' => 'NIK : ' . $item->nik . '<br>Tag ID Card : ' . ($item->tag_id_card ?: '-') . '<br>Nama : ' . $item->nama . '<br>Alamat : RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun') . ' ' . $item->wilayah->dusun),
                        ];
                    }),
                'pagination' => [
                    'more' => $penduduk->currentPage() < $penduduk->lastPage(),
                ],
            ]);
        }

        return show_404();
    }
}
