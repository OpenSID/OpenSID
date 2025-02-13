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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Enums\StatusEnum;
use App\Libraries\TinyMCE;
use App\Libraries\TinyMCE\KodeIsianGambar;
use App\Models\FormatSurat;
use App\Models\LogSurat;
use App\Models\Penduduk;
use App\Models\PermohonanSurat;
use App\Models\SyaratSurat;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use NotificationChannels\Telegram\Telegram;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;

class AnjunganSuratController extends MandiriModulController
{
    public $moduleName = 'Anjungan';
    protected TinyMCE $tinymce;

    public function __construct()
    {
        parent::__construct();

        $this->tinymce = new TinyMCE();

        if (! $this->session->is_anjungan) {
            redirect(route('layanan-mandiri.beranda.index'));
        }
    }

    public function buat($id = '')
    {
        $id_pend    = $this->is_login->id_pend;
        $permohonan = [];
        // Cek hanya status = 0 (belum lengkap) yg boleh di ubah
        if ($id) {
            $obj = PermohonanSurat::where(['id' => $id, 'id_pemohon' => $id_pend, 'status' => 0])->first();

            if (! $obj) {
                redirect(route('anjungan.surat.buat'));
            }

            $permohonan  = $obj->toArray();
            $form_action = route('anjungan.surat.form', $id);
        } else {
            $form_action = route('anjungan.surat.form');
        }

        $data = [
            'menu_surat_mandiri'   => FormatSurat::kunci(FormatSurat::KUNCI_DISABLE)->mandiri()->get(),
            'menu_dokumen_mandiri' => SyaratSurat::get()->toArray(),
            'permohonan'           => $permohonan,
            'form_action'          => $form_action,
        ];

        return view('anjungan::frontend.surat.buat', $data);
    }

    public function form($id = '')
    {
        $id_pend      = $this->is_login->id_pend;
        $surat        = FormatSurat::find($id);
        $syarat_surat = $this->getSyarat($surat->syarat_surat);
        $penduduk     = Penduduk::find($id_pend) ?? show_404();
        $individu     = $penduduk->formIndividu();
        $data         = [];
        $data         = array_merge($data, [
            'syarat_surat' => $syarat_surat,
            'url'          => $surat->url_surat,
            'individu'     => $individu,
            'anggota'      => $penduduk?->keluarga?->anggota?->toArray(),
            'surat_url'    => rtrim($_SERVER['REQUEST_URI'], '/clear'),
            'form_action'  => ci_route("surat/cetak/{$surat->url_surat}"),
            'anjungan'     => true,
        ]);
        $this->get_data_untuk_form($surat->url_surat, $data);

        return view('anjungan::frontend.surat.form', $data);
    }

    public function getSyarat($suratMaster)
    {
        $syaratSurat = SyaratSurat::query()->get();

        $data = [];

        $syaratSuratList = json_decode($suratMaster, true);

        foreach ($syaratSurat as $baris) {
            if (is_array($syaratSuratList) && in_array($baris->ref_syarat_id, $syaratSuratList)) {

                $data[] = $baris->ref_syarat_nama;
            }
        }

        return $data;
    }

    private function get_data_untuk_form($url, array &$data): void
    {
        // Panggil 1 penduduk berdasarkan datanya sendiri
        $data['penduduk'] = [$data['periksa']['penduduk']];

        $data['surat_terakhir']     = LogSurat::lastNomerSurat($url);
        $data['surat']              = FormatSurat::where('url_surat', $url)->first()->toArray();
        $data['input']              = $this->input->post();
        $data['input']['nomor']     = $data['surat_terakhir']['no_surat_berikutnya'];
        $data['format_nomor_surat'] = FormatSurat::format_penomoran_surat($data);
    }

    public function permohonan()
    {
        if ($this->input->is_ajax_request()) {
            $printer = $this->print_connector();

            return datatables(PermohonanSurat::with(['logSurat', 'surat'])->where('id_pemohon', $this->is_login->id_pend)->orWhereHas('logSurat', function ($q) {
                $q->where('id_pend', $this->is_login->id_pend)
                    ->where('deleted_at', null);
            }))
                ->addIndexColumn()
                ->addColumn('aksi', function ($item) use ($printer) {
                    $aksi = '';

                    if ($item->status == 0) {
                        $url = site_url("layanan-mandiri/surat/buat/{$item->id}");
                        $aksi .= "<a href='{$url}' class='btn btn-social bg-navy btn-sm' title='Lengkapi Surat' style='width: 170px'><i class='fa fa-info-circle'></i>Lengkapi Surat</a> ";
                    } elseif ($item->status == 1) {
                        $aksi .= "<a class='btn btn-social btn-info btn-sm btn-proses' title='Surat {$item->statusPermohonan}' style='width: 170px'><i class='fa fa-spinner'></i>{$item->statusPermohonan}</a> ";
                    } elseif ($item->status == 2) {
                        $aksi .= "<a class='btn btn-social bg-purple btn-sm btn-proses' title='Surat {$item->statusPermohonan}' style='width: 170px'><i class='fa fa-edit'></i>{$item->statusPermohonan}</a> ";
                    } elseif ($item->status == 3) {
                        $aksi .= "<a class='btn btn-social bg-orange btn-sm btn-proses' title='Surat {$item->statusPermohonan}' style='width: 170px'><i class='fa fa-thumbs-o-up'></i>{$item->statusPermohonan}</a> ";
                    } elseif ($item->status == 4) {
                        $aksi .= "<a class='btn btn-social btn-success btn-sm btn-proses' title='Surat {$item->statusPermohonan}' style='width: 170px'><i class='fa fa-check'></i>{$item->statusPermohonan}</a> ";
                    } else {
                        $aksi .= "
                            <a class='btn btn-social btn-danger btn-sm btn-proses' title='Surat {$item->statusPermohonan}' style='width: 170px'><i class='fa fa-times'></i>{$item->statusPermohonan}</a>
                            <button title='Keterangan' class='btn bg-orange btn-sm keterangan' data-toggle='popover' data-trigger='focus' data-content='{$item->alasan}'><i class='fa fa-info-circle'></i></button>
                        ";
                    }

                    if (in_array($item->status, ['0', '1'])) {
                        $url = site_url(MANDIRI . "/surat/proses/{$item->id}");
                        $aksi .= "<a href='{$url}' title='Batalkan Surat' class='btn bg-maroon btn-sm'><i class='fa fa-times'></i></a> ";
                    }

                    if ($item->no_antrian && $this->cek_anjungan && $printer) {
                        $url = site_url(MANDIRI . "/surat/cetak_no_antrian/{$item->no_antrian}");
                        $aksi .= "<a href='{$url}' class='btn btn-social btn-sm bg-navy' title='Cetak No. Antrean'><i class='fa fa-print'></i>No. Antrean</a> ";
                    }

                    if ($item->status == 3 && $item->logSurat?->last()?->tte != null) {
                        $url = site_url("layanan-mandiri/surat/cetak/{$item->logSurat?->last()?->id}");
                        $aksi .= "<a href='{$url}' class='btn bg-fuchsia btn-sm' title='Cetak Surat PDF' target='_blank'><i class='fa fa-file-pdf-o'></i></a>";
                    }

                    return $aksi;
                })
                ->editColumn('no_antrian', static fn ($item) => get_antrian($item->no_antrian))
                ->editColumn('created_at', static fn ($item) => tgl_indo2($item->created_at))
                ->rawColumns(['aksi'])
                ->make();
        }

        return view('anjungan::frontend.surat.permohonan');
    }

    protected function print_connector()
    {
        if (null === ($anjungan = $this->cek_anjungan)) {
            return;
        }

        try {
            $connector = new NetworkPrintConnector($anjungan['printer_ip'], $anjungan['printer_port'], 5);
        } catch (Exception $e) {
            logger()->error($e->getMessage());

            return false;
        }

        return $connector;
    }

    public function kirim($id = '')
    {
        $post  = $this->input->post();
        $surat = FormatSurat::where('url_surat', $post['url_surat'])->first();

        $syarat = collect(json_decode($surat->syarat_surat, true))
            ->mapWithKeys(static fn ($item, $key) => [(string) ($key + 1) => $item])
            ->all();

        $currentTimestamp = date('Y-m-d H:i:s');
        $data             = [
            'config_id'   => identitas('id'),
            'id_pemohon'  => bilangan($post['nik']),
            'id_surat'    => $surat->id,
            'isian_form'  => json_encode($post, JSON_THROW_ON_ERROR),
            'status'      => 1,
            'keterangan'  => 'Permohonan Surat dari Anjungan Mandiri',
            'no_hp_aktif' => bilangan($post['no_hp_aktif']),
            'syarat'      => json_encode($syarat, JSON_THROW_ON_ERROR),
            'updated_at'  => $currentTimestamp,
        ];

        $previewMode = $this->input->get('preview');

        if ($id) {
            PermohonanSurat::whereId($id)->update($data);
        } else {
            if ($previewMode) {
                $this->handlePreview($surat, $post, $data);

                if ($previewMode === 'cetak') {
                    $data['created_at'] = $currentTimestamp;
                    PermohonanSurat::insert($data);

                    if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
                        $this->sendTelegramNotification($post, $surat);
                    }
                }
            }
        }

        $this->session->unset_userdata('data_permohonan');

        return redirect(route('anjungan.permohonan'));
    }

    private function handlePreview($surat, $post, $data)
    {
        try {
            // Prepare log_surat inline
            $log_surat = [
                'id_pend'  => $data['id_pemohon'],
                'surat'    => $surat,
                'input'    => $post,
                'no_surat' => $post['nomor'],
            ];

            $setting_header = $surat->header == StatusEnum::TIDAK ? '' : setting('header_surat');
            $setting_footer = $surat->footer == StatusEnum::YA
                ? (setting('tte') == StatusEnum::YA ? setting('footer_surat_tte') : setting('footer_surat'))
                : '';
            $log_surat['isi_surat'] = preg_replace('/\\\\/', '', $setting_header)
                . '<!-- pagebreak -->'
                . ($surat->template_desa ?: $surat->template)
                . '<!-- pagebreak -->'
                . preg_replace('/\\\\/', '', $setting_footer);

            // Process the template
            $isi_surat = $this->tinymce->gantiKodeIsian($log_surat, false);
            $isi_cetak = $this->tinymce->formatPdf($surat->header, $surat->footer, $isi_surat);
            $isi_cetak = KodeIsianGambar::set($log_surat['surat'], $isi_cetak, $surat)['result'];

            $nama_surat = $this->namaSuratArsip(
                $log_surat['surat']['url_surat'],
                auth('penduduk')->user()->penduduk->nik,
                $log_surat['no_surat']
            );

            $margin_cm_to_mm = $log_surat['surat']['margin_cm_to_mm'];

            if ($log_surat['surat']['margin_global'] == '1') {
                $margin_cm_to_mm = setting('surat_margin_cm_to_mm');
            }

            $defaultFont = underscore(setting('font_surat'));

            // Generate PDF and attachments
            $this->tinymce->generateSurat($isi_cetak, $log_surat, $margin_cm_to_mm, $defaultFont);
            $this->tinymce->generateLampiran($log_surat['id_pend'], $log_surat, $log_surat['input']);

            // Handle preview mode
            $previewMode = $this->input->get('preview');
            if ($previewMode === 'cetak') {
                $this->tinymce->pdfMerge->merge(FCPATH . LOKASI_ARSIP . $nama_surat, 'FI');
            } else {
                return $this->tinymce->pdfMerge->merge($nama_surat, 'I');
            }
        } catch (Html2PdfException $e) {
            return $this->handlePdfException($e);
        }
    }

    private function sendTelegramNotification($post, $surat)
    {
        $telegram = new Telegram(setting('telegram_token'));

        try {
            $pesanTelegram = [
                '[nama_penduduk]' => $this->is_login->nama,
                '[judul_surat]'   => $surat->nama,
                '[tanggal]'       => tgl_indo2(date('Y-m-d H:i:s')),
                '[melalui]'       => 'Layanan Mandiri',
                '[website]'       => APP_URL,
            ];

            $kirimPesan = str_replace(array_keys($pesanTelegram), array_values($pesanTelegram), setting('notifikasi_pengajuan_surat'));
            $telegram->sendMessage([
                'text'       => $kirimPesan,
                'parse_mode' => 'Markdown',
                'chat_id'    => setting('telegram_user_id'),
            ]);
        } catch (Exception $e) {
            logger()->error($e->getMessage());
        }
    }

    private function handlePdfException($e)
    {
        $formatter = new ExceptionFormatter($e);
        logger()->error($e);

        return $this->output
            ->set_status_header(404, str_replace("\n", ' ', $formatter->getMessage()))
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'statusText' => $formatter->getMessage(),
            ], JSON_THROW_ON_ERROR));
    }

    private function namaSuratArsip(string $url, string $nik, $nomor): string
    {
        $nomor_surat = str_replace("'", '', $nomor);
        $nomor_surat = preg_replace('/[^a-zA-Z0-9.	]/', '-', $nomor_surat);

        return $url . '_' . $nik . '_' . date('Y-m-d') . '_' . $nomor_surat . '.pdf';
    }
}
