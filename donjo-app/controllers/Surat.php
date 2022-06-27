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

use App\Libraries\TinyMCE;
use App\Models\Config;
use App\Models\FormatSurat;
use App\Models\LogSurat;
use App\Models\Penduduk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

defined('BASEPATH') || exit('No direct script access allowed');

class Surat extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['penduduk_model', 'keluarga_model', 'surat_model', 'keluar_model', 'penomoran_surat_model', 'permohonan_surat_model']);
        $this->modul_ini     = 4;
        $this->sub_modul_ini = 31;
    }

    public function index()
    {
        return view('admin.surat.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(FormatSurat::favorit(0))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("surat/form/{$row->url_surat}") . '" class="btn btn-social bg-purple btn-sm"  title="Buat Surat"><i class="fa fa-file-word-o"></i>Buat Surat</a> ';

                        if ($row->favorit) {
                            $aksi .= '<a href="' . site_url("surat/favorit/{$row->id}/1") . '" class="btn bg-purple btn-sm" title="Keluarkan dari Daftar Favorit"><i class="fa fa-star"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . site_url("surat/favorit/{$row->id}/0") . '" class="btn bg-purple btn-sm" title="Tambahkan ke Daftar Favorit"><i class="fa fa-star-o"></i></a> ';
                        }
                    }

                    return $aksi;
                })
                ->editColumn('lampiran', static function ($row) {
                    return kode_format($row->lampiran);
                })
                ->rawColumns(['aksi', 'template_surat'])
                ->make();
        }

        return show_404();
    }

    public function datatablesFavorit()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(FormatSurat::favorit())
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("surat/form/{$row->url_surat}") . '" class="btn btn-social bg-olive btn-sm" title="Buat Surat"><i class="fa fa-file-word-o"></i>Buat Surat</a> ';

                        if ($row->favorit) {
                            $aksi .= '<a href="' . site_url("surat/favorit/{$row->id}/1") . '" class="btn bg-purple btn-sm" title="Keluarkan dari Daftar Favorit"><i class="fa fa-star"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . site_url("surat/favorit/{$row->id}/0") . '" class="btn bg-purple btn-sm" title="Tambahkan ke Daftar Favorit"><i class="fa fa-star-o"></i></a> ';
                        }
                    }

                    return $aksi;
                })
                ->editColumn('lampiran', static function ($row) {
                    return kode_format($row->lampiran);
                })
                ->rawColumns(['aksi', 'template_surat'])
                ->make();
        }

        return show_404();
    }

    public function form($url = '')
    {
        $data['url']    = $url;
        $data['anchor'] = $this->input->post('anchor');
        if (! empty($_POST['nik'])) {
            $data['individu'] = $this->surat_model->get_penduduk($_POST['nik']);
            $data['anggota']  = $this->keluarga_model->list_anggota($data['individu']['id_kk'], ['dengan_kk' => true], true);
        } else {
            $data['individu'] = null;
            $data['anggota']  = null;
        }

        $this->get_data_untuk_form($url, $data);

        $data['surat_url']    = rtrim($_SERVER['REQUEST_URI'], '/clear');
        $data['form_action']  = site_url("surat/doc/{$url}");
        $data['masa_berlaku'] = $this->surat_model->masa_berlaku_surat($url);

        if (in_array($data['surat']['jenis'], [3, 4])) {
            $data['list_dokumen'] = empty($_POST['nik']) ? null : $this->penduduk_model->list_dokumen($data['individu']['id']);
            $data['form_action']  = route('surat.pratinjau', $url);
            $data['kode_isian']   = json_decode($data['surat']['kode_isian']);

            return view('admin.surat.form_desa', $data);
        }

        $this->render('surat/form_surat', $data);
    }

    public function pratinjau($url)
    {
        $surat = FormatSurat::where('url_surat', $url)->first();

        // Simpan data ke log_surat sebagai draf
        $log_surat = [
            'id_format_surat' => $surat->id,
            'id_pend'         => $this->request['nik'], // nik = id_pend
            'id_pamong'       => $this->request['pamong_id'],
            'id_user'         => auth()->id,
            'tanggal'         => Carbon::now(),
            'bulan'           => date('m'),
            'tahun'           => date('Y'),
            'no_surat'        => $this->request['nomor'],
            'isi_surat'       => preg_replace('/\\\\/', '', setting('header_surat')) . '<!-- pagebreak -->' . ($surat->template_desa ?? $surat->template) . '<!-- pagebreak -->' . preg_replace('/\\\\/', '', setting('footer_surat')),
            'status'          => null,
        ];

        if ($log_surat['id_pend']) {
            $nik = Penduduk::find($log_surat['id_pend'])->nik;
        } else {
            // Surat untuk non-warga
            $log_surat['nama_non_warga'] = $this->request['nama_non_warga'];
            $log_surat['nik_non_warga']  = $this->request['nik_non_warga'];
            $nik                         = $log_surat['nik_non_warga'];
        }

        $log_surat['nama_surat'] = $this->nama_surat_arsip($url, $nik, $log_surat['no_surat']);

        if (LogSurat::insert($log_surat)) {
            $id = DB::getPdo()->lastInsertId();
        }

        $log_surat['surat'] = $surat;
        $log_surat['input'] = $this->request;

        // Lewati ganti kode_isian
        // $kecuali   = ['[logo]', '[qr_code]', '[tgl_surat]', '[format_nomor_suart]'];
        // $isi_surat = $this->replceKodeIsian($log_surat, $kecuali);
        $isi_surat = $this->replceKodeIsian($log_surat);

        $aksi_konsep = site_url("surat/konsep/{$id}");
        $aksi_cetak  = site_url("surat/pdf/{$id}");

        $id_surat = $surat->id;

        return view('admin.surat.konsep', compact('content', 'aksi_konsep', 'aksi_cetak', 'isi_surat', 'id_surat'));
    }

    public function cetak_konsep($id = null)
    {
        $konsep    = LogSurat::find($id);
        $isi_surat = $konsep->isi_surat;

        $aksi_konsep = site_url("surat/konsep/{$id}");
        $aksi_cetak  = site_url("surat/pdf/{$id}");

        return view('admin.surat.konsep', compact('content', 'aksi_konsep', 'aksi_cetak', 'isi_surat'));
    }

    private function replceKodeIsian($data = [], $kecuali = [])
    {
        $result = $data['isi_surat'];

        $tinymce   = new TinyMCE();
        $kodeIsian = $tinymce->getFormatedKodeIsian($data, true);

        foreach ($kodeIsian as $key => $value) {
            if (! in_array($key, $kecuali)) {
                $result = $this->caseReplace($key, $value, $result);
            }
        }

        return $result;
    }

    /**
     * Dipanggil untuk setiap kode isian ditemukan,
     * dan diganti dengan kata pengganti yang huruf besar/kecil mengikuti huruf kode isian.
     * Berdasarkan contoh di http://stackoverflow.com/questions/19317493/php-preg-replace-case-insensitive-match-with-case-sensitive-replacement
     *
     * Huruf pertama dan kedua huruf besar --> ganti dengan huruf besar semua:
     * [SEbutan_desa] ==> KAMPUNG
     *
     * Huruf pertama besar dan kedua kecil --> ganti dengan huruf besar pertama saja:
     * [Sebutan_desa] ==> Kampung
     *
     * Huruf pertama kecil --> ganti dengan huruf kecil semua:
     * [sebutan_desa] ==> kampung
     *
     * @param [type] $dari
     * @param [type] $ke
     * @param [type] $str
     *
     * @return void
     */
    public function caseReplace($dari, $ke, $str)
    {
        $replacer = static function ($matches) use ($ke) {
            $matches = array_map(static function ($match) {
                return preg_replace('/[\\[\\]]/', '', $match);
            }, $matches);
            if (ctype_upper($matches[0][0]) && ctype_upper($matches[0][1])) {
                return strtoupper($ke);
            }
            if (ctype_upper($matches[0][0])) {
                return ucwords($ke);
            }

            return strtolower($ke);
        };
        $dari = str_replace('[', '\\[', $dari);
        $str  = preg_replace_callback('/(' . $dari . ')/i', $replacer, $str);

        return $str;
    }

    public function konsep($id = null)
    {
        $konsep            = LogSurat::find($id);
        $konsep->isi_surat = $this->request['isi_surat'];
        $konsep->status    = 0;

        if ($konsep->save()) {
            redirect_with('success', 'Berhasil Simpan Konsep');
        }

        redirect_with('success', 'Gagal Simpan Konsep');
    }

    public function pdf($id = null)
    {
        $cetak     = LogSurat::find($id);
        $isi_surat = $this->request['isi_surat'] ?? $cetak->isi_surat;

        // Pisahkan isian surat
        $isi = explode('<p><!-- pagebreak --></p>', $isi_surat);

        $isi_cetak = '
        <page backtop="30mm" backbottom="30mm">
            <page_header>
            ' . $isi[0] . '
            </page_header>
            ' . $isi[1] . '
            <page_footer>
            ' . $isi[2] . '
            </page_footer>
        </page>
        ';

        // Logo Surat
        $file_logo = ($cetak->formatSurat->logo_garuda ? FCPATH . 'assets/images/garuda.png' : gambar_desa(Config::pluck('logo'), false, true));
        $logo      = (is_file($file_logo)) ? '<img src="' . $file_logo . '" width="90" height="90" alt="logo-surat" />' : '';
        $isi_cetak = str_replace('[logo]', $logo, $isi_cetak);

        // QR_Code Surat
        if ($cetak->formatSurat->qr_code) {
            $cek            = $this->surat_model->buatQrCode($cetak->nama_surat);
            $qrcode         = ($cek['viewqr']) ? '<img src="' . $cek['viewqr'] . '" width="90" height="90" alt="qrcode-surat" />' : '';
            $isi_cetak      = str_replace('[qr_code]', $qrcode, $isi_cetak);
            $cetak->urls_id = $cek['urls_id'];
        } else {
            $isi_cetak = str_replace('[qr_code]', '', $isi_cetak);
        }

        // convert in PDF
        try {
            $html2pdf = new Html2Pdf($cetak->formatSurat->orientasi, $cetak->formatSurat->ukuran, 'en', true, 'UTF-8', json_decode($cetak->formatSurat->margin));
            $html2pdf->setTestTdInOnePage(false);
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($isi_cetak);
            $html2pdf->output($cetak->nama_surat, 'D');

            // Setelah dicetak, ubah status dari draft jadi cetak
            $cetak->isi_surat = $isi_surat;
            $cetak->status    = 1;
            $cetak->save();
        } catch (Html2PdfException $e) {
            $html2pdf->clean();
            $formatter = new ExceptionFormatter($e);
            log_message('error', $formatter->getHtmlMessage());
        }
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
        $format                 = $this->surat_model->get_surat($url);
        $log_surat['url_surat'] = $format['id'];
        $log_surat['id_pamong'] = $_POST['pamong_id'];
        $log_surat['id_user']   = $_SESSION['user'];
        $log_surat['no_surat']  = $_POST['nomor'];
        $id                     = $_POST['nik'];
        $keperluan              = $_POST['keperluan'];
        $keterangan             = $_POST['keterangan'];

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
            $nik                  = $this->db->select('nik')->where('id', $id)->get('tweb_penduduk')->row()->nik;
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
        $this->keluar_model->log_surat($log_surat);

        $surat      = $this->surat_model->buat_surat($url, $nama_surat, $lampiran);
        $nama_surat = $surat['namaSurat'];

        // Update urls_id log_surat (untuk link qrcode)
        $this->db->where('nama_surat', $nama_surat)->update('log_surat', ['urls_id' => $surat['qrCode']['urls_id']]);

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

    private function get_data_untuk_form($url, &$data)
    {
        $this->session->unset_userdata('qrcode');
        $this->load->model('pamong_model');
        $data['surat_terakhir']     = $this->surat_model->get_last_nosurat_log($url);
        $data['surat']              = $this->surat_model->get_surat($url);
        $data['config']             = $this->config_model->get_data();
        $data['input']              = $this->input->post();
        $data['input']['nomor']     = $data['surat_terakhir']['no_surat_berikutnya'];
        $data['format_nomor_surat'] = $this->penomoran_surat_model->format_penomoran_surat($data);
        $data['lokasi']             = $this->config_model->get_data();
        $data['penduduk']           = $this->surat_model->list_penduduk();
        $data['pamong']             = $this->surat_model->list_pamong();
        $pamong_ttd                 = $this->pamong_model->get_ttd();
        $pamong_ub                  = $this->pamong_model->get_ub();
        $data['perempuan']          = $this->surat_model->list_penduduk_perempuan();
        if ($pamong_ttd) {
            $str_ttd             = ucwords($pamong_ttd['jabatan'] . ' ' . $data['lokasi']['nama_desa']);
            $data['atas_nama'][] = "a.n {$str_ttd}";
            if ($pamong_ub) {
                $data['atas_nama'][] = "u.b {$pamong_ub['jabatan']}";
            }
        }
        $data_form = $this->surat_model->get_data_form($url);
        if (is_file($data_form)) {
            include $data_form;
        }
    }

    public function favorit($id = null, $val = 0)
    {
        $this->redirect_hak_akses('u');

        $favorit = FormatSurat::find($id) ?? show_404();
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
}
