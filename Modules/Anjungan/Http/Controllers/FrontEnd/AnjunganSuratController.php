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

use App\Models\FormatSurat;
use App\Models\LogSurat;
use App\Models\Penduduk;
use App\Models\PermohonanSurat;
use App\Models\SyaratSurat;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use NotificationChannels\Telegram\Telegram;

class AnjunganSuratController extends MandiriModulController
{
    public $moduleName = 'Anjungan';

    public function __construct()
    {
        parent::__construct();
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

    public function kirim($id = ''): void
    {
        $post = $this->input->post();

        $surat = FormatSurat::where('url_surat', $post['url_surat'])->first();

        $syrat = collect(json_decode($surat->syarat_surat, true))
            ->mapWithKeys(static fn ($item, $key) => [(string) ($key + 1) => $item])
            ->all();

        $data = [
            'config_id'   => identitas('id'),
            'id_pemohon'  => bilangan($post['nik']),
            'id_surat'    => $surat->id,
            'isian_form'  => json_encode($post, JSON_THROW_ON_ERROR),
            'status'      => 1, // Selalu 1 bagi pengguna layanan mandiri
            'keterangan'  => 'Permohonan Surat dari Anjungan Mandiri',
            'no_hp_aktif' => bilangan($post['no_hp_aktif']),
            'syarat'      => json_encode($syrat, JSON_THROW_ON_ERROR),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if ($id) {
            PermohonanSurat::whereId($id)->update($data);
        } else {
            $data['created_at'] = $data['updated_at'];

            PermohonanSurat::insert($data);

            if (setting('telegram_notifikasi') && cek_koneksi_internet()) {
                $telegram = new Telegram(setting('telegram_token'));

                try {
                    // Data pesan telegram yang akan digantikan
                    $pesanTelegram = [
                        '[nama_penduduk]' => $this->is_login->nama,
                        '[judul_surat]'   => FormatSurat::find($post['id_surat'])->nama,
                        '[tanggal]'       => tgl_indo2(date('Y-m-d H:i:s')),
                        '[melalui]'       => 'Layanan Mandiri',
                        '[website]'       => APP_URL,
                    ];

                    $kirimPesan = setting('notifikasi_pengajuan_surat');
                    $kirimPesan = str_replace(array_keys($pesanTelegram), array_values($pesanTelegram), $kirimPesan);
                    $telegram->sendMessage([
                        'text'       => $kirimPesan,
                        'parse_mode' => 'Markdown',
                        'chat_id'    => setting('telegram_user_id'),
                    ]);
                } catch (Exception $e) {
                    logger()->error($e->getMessage());
                }
            }
        }

        $this->session->unset_userdata('data_permohonan');

        redirect(route('anjungan.permohonan'));
    }
}
