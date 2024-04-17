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

use App\Enums\FirebaseEnum;
use App\Libraries\TinyMCE;
use App\Models\Dokumen;
use App\Models\FcmToken;
use App\Models\FormatSurat;
use App\Models\LogNotifikasiAdmin;
use App\Models\LogSurat;
use App\Models\LogTolak;
use App\Models\Pamong;
use App\Models\Penduduk;
use App\Models\PermohonanSurat;
use App\Models\RefJabatan;
use App\Models\Urls;
use App\Models\User;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Keluar extends Admin_Controller
{
    public $modul_ini     = 'layanan-surat';
    public $sub_modul_ini = 'arsip-layanan';
    private $isAdmin;
    private TinyMCE $tinymce;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->tinymce = new TinyMCE();
        $this->load->helper('download');
        $this->isAdmin = $this->session->isAdmin->pamong;
        $this->load->library('OTP/OTP_manager', null, 'otp_library');
    }

    public function index(): void
    {
        $data['tab_ini'] = 10;
        $data['state']   = 'arsip';

        $this->show($data);
    }

    public function masuk(): void
    {
        $this->alihkan();

        $data['tab_ini']    = 11;
        $data['state']      = 'masuk';
        $data['title']      = 'Permohonan Surat';
        $data['redirect']   = 'masuk';
        $ref_jabatan_kades  = setting('sebutan_kepala_desa');
        $ref_jabatan_sekdes = setting('sebutan_sekretaris_desa');

        if ($this->isAdmin->jabatan_id == kades()->id) {
            $data['next'] = null;
        } elseif ($this->isAdmin->jabatan_id == sekdes()->id) {
            $data['next'] = setting('verifikasi_kades') ? $ref_jabatan_kades : null;
        } elseif (setting('verifikasi_sekdes')) {
            $data['next'] = $ref_jabatan_sekdes;
        } elseif (setting('verifikasi_kades')) {
            $data['next'] = $ref_jabatan_kades;
        } else {
            $data['next'] = null;
        }

        $this->show($data);
    }

    public function ditolak(): void
    {
        $this->alihkan();

        $data['tab_ini']  = 12;
        $data['state']    = 'tolak';
        $data['title']    = 'Surat Ditolak';
        $data['redirect'] = 'ditolak';

        $this->show($data);
    }

    private function show($dataView): void
    {
        if (setting('verifikasi_kades') || setting('verifikasi_sekdes')) {
            $data['operator'] = ($this->isAdmin->jabatan_id == kades()->id || $this->isAdmin->jabatan_id == sekdes()->id) ? false : true;
            $data['widgets']  = $this->widget();
        }

        $data['user_admin']  = config_item('user_admin') == auth()->id;
        $data['title']       = 'Arsip Layanan Surat';
        $data['tahun_surat'] = LogSurat::withOnly([])->selectRaw(DB::raw('YEAR(tanggal) as tahun'))->groupBy(DB::raw('YEAR(tanggal)'))->orderBy(DB::raw('YEAR(tanggal)'), 'desc')->get();
        $data['bulan_surat'] = [];
        $data['jenis_surat'] = FormatSurat::whereHas('logSurat')->distinct()->select(['id', 'nama'])->get();
        $data['redirect']    = 'index';

        view('admin.surat.keluar.index', array_merge($data, $dataView));
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $canDelete       = can('h');
            $canUpdate       = can('u');
            $state           = $this->input->get('state') ?? 'arsip';
            $tahun           = $this->input->get('tahun');
            $bulan           = $this->input->get('bulan');
            $jenis           = $this->input->get('jenis');
            $idJabatanKades  = kades()->id;
            $idJabatanSekdes = sekdes()->id;
            $jabatanId       = $this->isAdmin->jabatan_id;
            $operator        = false;
            $isAdmin         = $this->isAdmin;
            $redirectDelete  = '';
            if (setting('tte')) {
                switch($state) {
                    case 'masuk':
                        $redirectDelete = 'masuk';
                        break;

                    case 'tolak':
                        $redirectDelete = 'ditolak';
                        break;

                    default:
                }
            }
            if (setting('verifikasi_kades') || setting('verifikasi_sekdes')) {
                $operator = ! in_array($jabatanId, [$idJabatanKades, $idJabatanKades]);
            }

            return datatables()->of(LogSurat::withOnly(['formatSuratArsip', 'penduduk', 'pamong', 'tolak'])->selectRaw('*')
                ->when($tahun, static fn ($q) => $q->whereYear('tanggal', $tahun))
                ->when($bulan, static fn ($q) => $q->whereMonth('tanggal', $bulan))
                ->when($jenis, static fn ($q) => $q->where('id_format_surat', $jenis))
                ->when(($jabatanId == $idJabatanKades && setting('verifikasi_kades') == 1), static fn ($q) => $q->selectRaw('verifikasi_kades as verifikasi'))
                // ->when(($jabatanId == $idJabatanSekdes && setting('verifikasi_sekdes') == 1 ), static fn ($q) => $q->selectRaw('verifikasi_sekdes as verifikasi')->where(static fn($r) => $q->whereIn('verifikasi_sekdes', [1,0])->orWhereNull('verifikasi_operator')))
                ->when(($jabatanId == $idJabatanSekdes && setting('verifikasi_sekdes') == 1), static fn ($q) => $q->selectRaw('verifikasi_sekdes as verifikasi'))
                ->when(! in_array($jabatanId, [$idJabatanKades, $idJabatanSekdes]), static fn ($q) => $q->selectRaw('verifikasi_operator as verifikasi'))
                ->when($state == 'arsip', static function ($q) use ($isAdmin, $jabatanId, $idJabatanKades, $idJabatanSekdes) {
                    $listJabatan = [
                        'jabatan_id'        => $jabatanId,
                        'jabatan_kades_id'  => $idJabatanKades,
                        'jabatan_sekdes_id' => $idJabatanSekdes,
                    ];

                    return $q->arsip($isAdmin, $listJabatan);
                })
                ->when($state == 'masuk', static function ($q) use ($isAdmin, $jabatanId, $idJabatanKades, $idJabatanSekdes) {
                    $listJabatan = [
                        'jabatan_id'        => $jabatanId,
                        'jabatan_kades_id'  => $idJabatanKades,
                        'jabatan_sekdes_id' => $idJabatanSekdes,
                    ];

                    return $q->masuk($isAdmin, $listJabatan);
                })
                ->when($state == 'tolak', static fn ($q) => $q->ditolak())
                ->withOnly(['formatSurat', 'penduduk', 'pamong', 'user'])->whereNull('deleted_at'))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($state, $canUpdate, $canDelete, $operator, $jabatanId, $idJabatanKades, $idJabatanSekdes, $redirectDelete): string {
                    $aksi          = '';
                    $statusPeriksa = $row->statusPeriksa($jabatanId, $idJabatanKades, $idJabatanSekdes);
                    if ($state == 'arsip' && $canUpdate) {
                        if (in_array($row->formatSuratArsip->jenis, FormatSurat::RTF)) {
                            $aksi .= '<a href="' . ci_route('keluar.edit_keterangan', $row->id) . '" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Keterangan" class="btn bg-orange btn-sm"><i class="fa fa-edit"></i></a> ';
                        }
                        if (! in_array($row->formatSuratArsip->jenis, FormatSurat::RTF) && $row->status == 0) {
                            $aksi .= '<a href="' . ci_route('surat.cetak', $row->id) . '" class="btn bg-orange btn-sm" title="Ubah" target="_blank"><i class="fa  fa-pencil-square-o"></i></a> ';
                            // hapus surat draft
                            if ($canDelete) {
                                $aksi .= '<a href="#" data-href="' . ci_route('keluar.delete', $row->id) . '?redirect=' . $redirectDelete . '" class="btn bg-maroon btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                            }
                        }
                    }

                    // hanya untuk surat permohonan
                    if (in_array($state, ['masuk', 'tolak']) && $canUpdate) {
                        if (in_array($row->formatSuratArsip->jenis, FormatSurat::RTF) && $operator) {
                            $aksi .= '<a href="' . ci_route('keluar.edit_keterangan', $row->id) . '" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Keterangan" class="btn bg-orange btn-sm"><i class="fa fa-edit"></i></a> ';
                        } elseif ($row->status == 0 || $row->verifikasi == '-1') {
                            $aksi .= '<a href="' . ci_route('surat.cetak', $row->id) . '" class="btn bg-orange btn-sm" title="Ubah" target="_blank"><i class="fa  fa-pencil-square-o"></i></a> ';
                        }
                        if ($row->verifikasi == '-1' && $row->mandiri == '1') {
                            $aksi .= '<button data-id="' . $row->id . '" type="button" class="btn bg-blue btn-sm kembalikan" title="Kembalikan"> <i class="fa fa-undo"></i></button> ';
                        }
                        if ($statusPeriksa == 0 && $row->status != 0) {
                            $aksi .= '<a href="' . ci_route('keluar.periksa', $row->id) . '" class="btn bg-olive btn-sm" title="verifikasi"><i class="fa fa-check-square-o"></i></a> ';
                        }
                        if ($statusPeriksa == 2) {
                            $aksi .= '<button data-id="' . $row->id . '" type="button" class="btn bg-blue btn-sm passphrase " title="passphrase"> <i class="fa fa-key"></i></button> ';
                        }
                    }

                    // hanya untuk arsip surat -->
                    if ($row->status == '1') {
                        if (in_array($row->formatSuratArsip->jenis, FormatSurat::RTF)) {
                            if (is_file($row->rtfFile())) {
                                $aksi .= '<a href="' . ci_route('keluar.unduh.rtf', $row->id) . '" class="btn bg-purple btn-sm" title="Unduh Surat RTF" target="_blank"><i class="fa fa-file-word-o"></i></a> ';
                            }
                            if (is_file($row->pdfFile())) {
                                $aksi .= '<a href="' . ci_route('keluar.unduh.pdf', $row->id) . '" class="btn bg-fuchsia btn-sm" title="Cetak Surat PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a> ';
                            }
                            if (is_file($row->lampiranFile())) {
                                $aksi .= '<a href="' . ci_route('keluar.unduh.lampiran', $row->id) . '" target="_blank" class="btn btn-social bg-olive btn-sm" title="Unduh Lampiran"><i class="fa fa-paperclip"></i> Lampiran</a> ';
                            }
                        }
                        if ($row->urls_id) {
                            $aksi .= '<a href="' . ci_route('keluar.qrcode', $row->urls_id) . '" title="QR Code" data-size="modal-sm" class="viewQR btn bg-aqua btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="QR Code"><i class="fa fa-qrcode"></i></a> ';
                        }
                        if ($row->isi_surat && $row->verifikasi_operator != '-1') {
                            $aksi .= '<a href="' . ci_route('keluar.unduh.tinymce', $row->id) . '" class="btn bg-fuchsia btn-sm" title="Cetak Surat PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a> ';
                        }
                        if ($row->tte && $row->kecamatan == 2) {
                            if (setting('sinkronisasi_opendk')) {
                                $aksi .= '<a data-id="' . $row->id . '" class="btn btn-social bg-olive btn-sm kirim-kecamatan" title="Kirim ke Kecamatan"><i class="fa fa-send"></i> Kirim ke Kecamatan</a> ';
                            } else {
                                $aksi .= '<a class="btn btn-social bg-olive btn-sm" title="Kirim ke Kecamatan" disabled><i class="fa fa-send"></i> Kirim ke Kecamatan</a> ';
                            }
                        }

                        // hapus surat -->
                        if ($canDelete) {
                            $aksi .= '<a href="#" data-href="' . ci_route('keluar.delete', $row->id) . '?redirect=' . $redirectDelete . '" class="btn bg-maroon btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                        }
                    }

                    return $aksi;
                })
                ->addColumn('kode_surat', static fn ($row) => $row->formatSuratArsip->kode_surat ?? '')
                ->editColumn('id_format_surat', static fn ($row) => $row->formatSuratArsip->nama ?? '')
                ->editColumn('id_user', static fn ($row) => $row->user->nama ?? '')
                ->editColumn('keterangan', static fn ($row) => $row->keterangan ?? '-')
                ->editColumn('tanggal', static fn ($row) => tgl_indo2($row->tanggal))
                ->editColumn('penduduk_non_warga', static fn ($row) => $row->penduduk->nama ?? ($row->nama_non_warga ? '<strong>Non-warga: </strong>' . $row->nama_non_warga . '<br><strong>NIK: </strong>' . $row->nik_non_warga : ''))
                ->addColumn('pemohon', static function ($row) {
                    if ($row->pemohon) {
                        return json_decode($row->pemohon)->nama ?? '<strong>Non-warga: </strong>' . ((json_decode($row->pemohon))->nama_non_warga ?? '') . '<br><strong>NIK: </strong>' . ((json_decode($row->pemohon))->nik_non_warga ?? '');
                    }

                    return $row->penduduk->nama ?? ($row->nama_non_warga ? '<strong>Non-warga: </strong>' . $row->nama_non_warga . '<br><strong>NIK: </strong>' . $row->nik_non_warga : '');
                })->addColumn('status_label', static function ($row) use ($jabatanId, $idJabatanKades, $idJabatanSekdes): string {
                    $status        = '';
                    $statusPeriksa = $row->statusPeriksa($jabatanId, $idJabatanKades, $idJabatanSekdes);

                    if ($row->status == 1) {
                        if ($row->verifikasi == 1) {
                            if ($statusPeriksa == 1) {
                                if ($row->kecamatan == 2) {
                                    $status = '<span class="label label-success">Siap Dikirim ke Kecamatan</span>';
                                } elseif ($row->kecamatan == 3) {
                                    $status = '<span class="label label-success">Telah Dikirim ke Kecamatan</span>';
                                } else {
                                    $status = '<span class="label label-success">Siap Cetak</span>';
                                }
                            } else {
                                $status = '<span class="label label-warning">Menunggu ' . $row->log_verifikasi . ' </span>';
                            }
                        } else {
                            $status = '<span class="label label-warning">Menunggu ' . $row->log_verifikasi . ' </span>';
                        }
                    } else {
                        $status = '<span class="label label-danger">Konsep</span>';
                    }

                    return $status;
                })
                ->rawColumns(['aksi', 'penduduk_non_warga', 'pemohon', 'status_label'])
                ->make();
        }

        return show_404();
    }

    public function verifikasi(): void
    {
        $this->alihkan();

        $id                 = $this->input->post('id');
        $surat              = LogSurat::find($id);
        $mandiri            = PermohonanSurat::where('id_surat', $surat->id_format_surat)->where('isian_form->nomor', $surat->no_surat)->first();
        $ref_jabatan_kades  = setting('sebutan_kepala_desa');
        $ref_jabatan_sekdes = setting('sebutan_sekretaris_desa');

        switch ($this->isAdmin->jabatan_id) {
            // verifikasi kades
            case kades()->id:
                $current = 'verifikasi_kades';
                $next    = (setting('tte') && in_array($surat->formatSurat->jenis, FormatSurat::TINYMCE)) ? 'tte' : null;
                $log     = (setting('tte')) ? 'TTE' : null;
                break;

                // verifikasi sekdes
            case sekdes()->id:
                $current = 'verifikasi_sekdes';
                $next    = setting('verifikasi_kades') ? 'verifikasi_kades' : null;
                $log     = 'Verifikasi ' . $ref_jabatan_kades;
                break;

                // verifikasi operator
            default:
                $current = 'verifikasi_operator';
                if (setting('verifikasi_sekdes')) {
                    $next = 'verifikasi_sekdes';
                    $log  = 'Verifikasi ' . $ref_jabatan_sekdes;
                } elseif (setting('verifikasi_kades')) {
                    $next = 'verifikasi_kades';
                    $log  = 'Verifikasi ' . $ref_jabatan_kades;
                } else {
                    $next = null;
                    $log  = null;
                }
                break;
        }

        if ($next == null) {
            LogSurat::where('id', '=', $id)->update([$current => 1, 'log_verifikasi' => $log]);

            if ($mandiri != null) {
                $mandiri->update(['status' => 3]);

                // kirim notifikasi ke pemohon bahwa suratnya siap untuk diambil
                $id_penduduk = $mandiri['id_pemohon'];
                $pesan       = 'Surat ' . $mandiri->surat->nama . ' siap untuk dambil';
                $judul       = 'Surat ' . $mandiri->surat->nama . ' siap untuk dambil';

                $this->kirim_notifikasi_penduduk($id_penduduk, $pesan, $judul);
            }
        } else {
            $log_surat = LogSurat::where('id', '=', $id)->first();
            $log_surat->update([$current => 1,  $next => 0, 'log_verifikasi' => $log]);

            // hapus surat pdf agar bisa digenerate ulang.
            unlink(FCPATH . LOKASI_ARSIP . $log_surat->nama_surat);

            $kirim_telegram = User::whereHas('pamong', static function ($query) use ($next) {
                if ($next == 'verifikasi_sekdes') {
                    return $query->where('jabatan_id', '=', sekdes()->id)->where('pamong_ttd', '=', '1');
                }
                if ($next == 'verifikasi_kades') {
                    return $query->where('jabatan_id', '=', kades()->id);
                }
            })->where('notif_telegram', '=', '1')->first();

            $pesan = [
                '[nama_penduduk]' => Penduduk::find($log_surat->id_pend)->nama,
                '[judul_surat]'   => $log_surat->formatSurat->nama,
                '[tanggal]'       => tgl_indo2(date('Y-m-d H:i:s')),
                '[melalui]'       => 'Halaman Admin',
            ];

            $pesanFCM              = $pesan;
            $pesanFCM['[melalui]'] = 'aplikasi OpenSID Admin';

            // buat log notifikasi mobile admin
            $kirimPesan = setting('notifikasi_pengajuan_surat');
            $kirimFCM   = str_replace(array_keys($pesanFCM), array_values($pesanFCM), $kirimPesan);
            $judul      = 'Pengajuan Surat - ' . $pesan['[judul_surat]'];
            $payload    = '/permohonan/surat/periksa/' . $id . '/Periksa Surat';

            $allToken = FcmToken::whereHas('user.pamong', static function ($query) use ($next) {
                if ($next == 'verifikasi_sekdes') {
                    return $query->where('jabatan_id', '=', sekdes()->id)->where('pamong_ttd', '=', '1');
                }
                if ($next == 'verifikasi_kades') {
                    return $query->where('jabatan_id', '=', kades()->id);
                }
            })->get();

            // log ke notifikasi
            $isi_notifikasi = [
                'judul'      => $judul,
                'isi'        => $kirimFCM,
                'payload'    => $payload,
                'read'       => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->create_log_notifikasi_admin($next, $isi_notifikasi);

            if (cek_koneksi_internet()) {
                if ($kirim_telegram != null) {
                    try {
                        $telegram = new Telegram();

                        // Data pesan telegram yang akan digantikan
                        $kirimPesan = str_replace(array_keys($pesan), array_values($pesan), $kirimPesan);

                        $telegram->sendMessage([
                            'chat_id'      => $kirim_telegram->id_telegram,
                            'text'         => $kirimPesan,
                            'parse_mode'   => 'Markdown',
                            'reply_markup' => json_encode([
                                'inline_keyboard' => [[
                                    ['text' => 'Lihat detail', 'url' => ci_route("keluar/periksa/{$id}")],
                                ]],
                            ]),
                        ]);
                    } catch (Exception $e) {
                        log_message('error', $e->getMessage());
                    }
                }

                // kirim ke aplikasi android admin.
                try {
                    $client       = new Fcm\FcmClient(FirebaseEnum::SERVER_KEY, FirebaseEnum::SENDER_ID);
                    $notification = new Fcm\Push\Notification();

                    $notification
                        ->addRecipient($allToken->pluck('token')->all())
                        ->setTitle($judul)
                        ->setBody($kirimFCM)
                        ->addData('payload', '/permohonan/surat/periksa/' . $id . '/Periksa Surat');
                    $client->send($notification);
                } catch (Exception $e) {
                    log_message('error', $e->getMessage());
                }
                // bagian akhir kirim ke aplikasi android admin.
            }
        }
    }

    public function tolak()
    {
        $this->alihkan();

        try {
            $id        = $this->input->post('id');
            $alasan    = $this->input->post('alasan');
            $log_surat = LogSurat::where('id', '=', $id)->first();
            $file      = FCPATH . LOKASI_ARSIP . $log_surat->nama_surat;
            $log_surat->update([
                'verifikasi_kades'    => null,
                'verifikasi_sekdes'   => null,
                'verifikasi_operator' => -1,
            ]);

            // create log tolak
            LogTolak::create([
                'config_id'  => identitas('id'),
                'keterangan' => $alasan,
                'id_surat'   => $id,
                'created_by' => $this->session->user,
            ]);

            if ($log_surat->isi_surat != null) {
                unlink($file); //hapus file pdf
                $log_surat->update([
                    'nama_surat' => null,
                ]);
            }

            $jenis_surat = $log_surat->formatSurat->nama;

            $kirim_telegram = User::whereHas('pamong', static fn ($query) => $query->where('pamong_ub', '=', '0')->where('pamong_ttd', '=', '0'))
                ->where('notif_telegram', '=', '1')
                ->get();

            $telegram = new Telegram();

            foreach ($kirim_telegram as $value) {
                $telegram->sendMessage([
                    'chat_id' => $value->id_telegram,
                    'text'    => <<<EOD
                        Permohonan Surat telah ditolak,
                        Nomor Surat : {$log_surat->formatpenomoransurat}
                        Jenis Surat : {$jenis_surat}
                        Alasan : {$alasan}

                        TERIMA KASIH.
                        EOD,
                    'parse_mode'   => 'Markdown',
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [[
                            ['text' => 'Lihat detail', 'url' => ci_route('keluar/ditolak')],
                        ]],
                    ]),
                ]);
            }

            // log ke notifikasi
            $kirimFCM = <<<EOD
                Permohonan Surat telah ditolak,
                Nomor Surat : {$log_surat->formatpenomoransurat}
                Jenis Surat : {$jenis_surat}
                Alasan : {$alasan}

                TERIMA KASIH.
                EOD;
            $judul   = 'Pengajuan Surat ditolak - ' . $log_surat->formatSurat->nama;
            $payload = '/home/arsip';

            $allToken = FcmToken::doesntHave('user.pamong')
                ->orWhereHas('user.pamong', static fn ($query) => $query->whereNotIn('jabatan_id', RefJabatan::getKadesSekdes()))
                ->get();
            $log_notification = $allToken->map(static fn ($log): array => [
                'id_user'    => $log->id_user,
                'judul'      => $judul,
                'isi'        => $kirimFCM,
                'token'      => $log->token,
                'device'     => $log->device,
                'payload'    => $payload,
                'read'       => 0,
                'config_id'  => $log->config_id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            LogNotifikasiAdmin::insert($log_notification->toArray());

            // kirim ke aplikasi android admin.
            try {
                $client       = new Fcm\FcmClient(FirebaseEnum::SERVER_KEY, FirebaseEnum::SENDER_ID);
                $notification = new Fcm\Push\Notification();

                $notification
                    ->addRecipient($allToken->pluck('token')->all())
                    ->setTitle($judul)
                    ->setBody($kirimFCM)
                    ->addData('payload', $payload);
                $client->send($notification);
            } catch (Exception $e) {
                log_message('error', $e->getMessage());
            }

            // bagian akhir kirim ke aplikasi android admin.

            return json([
                'status' => true,
            ]);
        } catch (Exception $e) {
            return json([
                'status'   => false,
                'messages' => $e->getMessage(),
            ]);
        }
    }

    public function tte()
    {
        $this->alihkan();

        $id = $this->input->post('id');
        LogSurat::where('id', '=', $id)->update([
            'tte' => 1,
        ]);

        return json([
            'status' => true,
        ]);
    }

    public function kembalikan()
    {
        isCan('u');

        try {
            $id      = $this->input->post('id');
            $alasan  = $this->input->post('alasan');
            $surat   = LogSurat::find($id);
            $mandiri = PermohonanSurat::where('id_surat', $surat->id_format_surat)->where('isian_form->nomor', $surat->no_surat)->first();
            if ($mandiri == null) {
                return json([
                    'status'  => false,
                    'message' => 'Surat tidak ditemukan!',
                ]);
            }
            $mandiri->update(['status' => 0, 'alasan' => $alasan]);
            $surat->delete();

            return json([
                'status'  => true,
                'message' => 'success',
            ]);
        } catch (Exception $e) {
            return json([
                'status'  => true,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function periksa($id)
    {
        isCan('u');

        $surat                = LogSurat::find($id);
        $data['surat']        = $surat;
        $data['mandiri']      = PermohonanSurat::where('id_surat', $surat->id_format_surat)->where('isian_form->nomor', $surat->no_surat)->first();
        $data['individu']     = $surat->penduduk;
        $data['operator']     = ($this->isAdmin->jabatan_id == kades()->id || $this->isAdmin->jabatan_id == sekdes()->id) ? false : true;
        $data['list_dokumen'] = Dokumen::hidup()->where('id_pend', $data['individu']->id)->get();
        if ($data['mandiri']) {
            $data['list_dokumen_syarat'] = $data['list_dokumen']->whereIn('id', $data['mandiri']->syarat);
        }
        if ($this->isAdmin->jabatan_id == kades()->id) {
            $next = null;
        } elseif ($this->isAdmin->jabatan_id == sekdes()->id) {
            $next = setting('verifikasi_kades') ? setting('sebutan_kepala_desa') : null;
        } elseif (setting('verifikasi_sekdes')) {
            $next = setting('sebutan_sekretaris_desa');
        } elseif (setting('verifikasi_kades')) {
            $next = setting('sebutan_kepala_desa');
        } else {
            $next = null;
        }
        $data['next'] = $next;

        return view('admin.surat.periksa', $data);
    }

    public function edit_keterangan(int $id): void
    {
        isCan('u');
        $data['main']        = LogSurat::select(['nama_surat', 'lampiran', 'keterangan'])->find($id);
        $data['form_action'] = ci_route('keluar.update_keterangan', $id);
        view('admin.surat.keluar.ajax_edit_keterangan', $data);
    }

    public function update_keterangan(int $id): void
    {
        isCan('u');

        try {
            $data = ['keterangan' => $this->input->post('keterangan')];
            $data = $this->security->xss_clean($data);
            $data = html_escape($data);
            LogSurat::whereId($id)->update($data);
            redirect_with('success', 'Berhasil menyimpan data surat');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Gagal menyimpan data surat');
        }
    }

    public function delete(int $id): void
    {
        isCan('h');

        try {
            $surat = LogSurat::findOrFail($id);
            if ($surat->status == 0) {
                $surat->delete();
            } else {
                $surat->update(['deleted_at' => date('Y-m-d')]);
            }
            redirect_with('success', 'Berhasil menghapus data surat', ci_route("keluar.{$this->input->get('redirect')}"));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Gagal menghapus data surat', ci_route("keluar.{$this->input->get('redirect')}"));
        }
    }

    public function perorangan($id): void
    {
        $data['penduduk'] = $id ? Penduduk::find($id) : null;

        view('admin.surat.keluar.perorangan', $data);
    }

    public function perorangan_datatables()
    {
        if ($this->input->is_ajax_request()) {
            $canDelete = can('h');
            $canUpdate = can('u');

            return datatables()->of(LogSurat::selectRaw('*')
                ->withOnly(['formatSurat', 'penduduk', 'pamong', 'user'])->whereNull('deleted_at'))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($canUpdate, $canDelete): string {
                    $aksi = '';
                    if (is_file($row->rtfFile())) {
                        $aksi .= '<a href="' . ci_route($row->rtfFile()) . '" class="btn bg-purple btn-sm" title="Unduh Surat RTF" target="_blank"><i class="fa fa-file-word-o"></i></a> ';
                    }
                    if (is_file($row->pdfFile())) {
                        $aksi .= '<a href="' . ci_route($row->pdfFile()) . '" class="btn bg-fuchsia btn-sm" title="Cetak Surat PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a> ';
                    }

                    // if (is_file($row->qrFile())):
                    //     $aksi .= '<a href="'. ci_route("dokumen_web.check_surat2",$row->id).'" onclick="return confirm(\'Apakah anda yakin?\'));" class="btn bg-green btn-sm" title="Lihat Verifikasi" target="_blank"><i class="fa fa-check"></i></a> ';
                    //     $aksi .= '<a href="#myModal" data-fileqr="'.ci_route($row->qrFile()).'" title="Lihat QR Code" class="viewQR btn bg-aqua btn-sm"><i class="fa fa-qrcode"></i></a> ';
                    // endif;

                    if (is_file($row->lampiranFile())) {
                        $aksi .= '<a href="' . ci_route($row->lampiranFile()) . '" target="_blank" class="btn btn-social bg-olive btn-sm" title="Unduh Lampiran"><i class="fa fa-paperclip"></i> Lampiran</a> ';
                    }
                    if ($canUpdate) {
                        $aksi .= '<a href="' . ci_route('keluar.edit_keterangan', $row->id) . '" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Keterangan" class="btn bg-orange btn-sm"><i class="fa fa-edit"></i></a> ';
                    }
                    if ($canDelete) {
                        $aksi .= '<a href="#" data-href="' . ci_route('keluar.delete', $row->id) . '?redirect=perorangan" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                    }

                    return $aksi;
                })
                ->addColumn('kode_surat', static fn ($row) => $row->formatSurat->kode_surat ?? '')
                ->editColumn('id_format_surat', static fn ($row) => $row->formatSurat->nama ?? '')
                ->editColumn('id_user', static fn ($row) => $row->user->nama ?? '')
                ->editColumn('tanggal', static fn ($row) => tgl_indo2($row->tanggal))
                ->editColumn('id_pend', static fn ($row) => $row->penduduk->nama ?? '')

                ->rawColumns(['aksi', 'nama', 'pemohon'])
                ->make();
        }

        return show_404();
    }

    public function graph(): void
    {
        $data['stat'] = FormatSurat::distinct()->select(['nama'])->withCount('logSurat')->get();

        view('admin.surat.keluar.graph', $data);
    }

    public function unduh($tipe, $id, $preview = false): void
    {
        $berkas = LogSurat::find($id);
        if ($tipe == 'tinymce') {
            $this->tinymce->cetak_surat($id);
        } else {
            if ($tipe == 'pdf') {
                $berkas->nama_surat = basename($berkas->nama_surat, 'rtf') . 'pdf';
            }
            ambilBerkas($tipe == 'lampiran' ? $berkas->lampiran : $berkas->nama_surat, $this->controller, null, LOKASI_ARSIP, (bool) $preview);
        }
    }

    public function dialog_cetak($aksi = ''): void
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = $aksi;
        $data['form_action'] = ci_route('keluar.cetak', $aksi);
        view('admin.layouts.components.ttd_pamong', $data);
    }

    public function cetak($aksi = ''): void
    {
        $listJabatan = [
            'jabatan_id'        => $this->isAdmin->jabatan_id,
            'jabatan_kades_id'  => kades()->id,
            'jabatan_sekdes_id' => sekdes()->id,
        ];
        $data['aksi']           = $aksi;
        $data['input']          = $this->input->post();
        $data['config']         = $this->header['desa'];
        $data['pamong_ttd']     = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ttd')])->first()->toArray();
        $data['pamong_ketahui'] = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ketahui')])->first()->toArray();
        $data['desa']           = $this->header['desa'];
        $data['main']           = LogSurat::withOnly(['formatSurat', 'penduduk', 'pamong', 'user'])->whereNull('deleted_at')->arsip($this->isAdmin, $listJabatan)->orderBy('tanggal', 'desc')->get();

        //pengaturan data untuk format cetak/ unduh
        $data['file']      = 'Data Arsip Layanan Desa ';
        $data['isi']       = 'admin.surat.keluar.cetak';
        $data['letak_ttd'] = ['2', '2', '3'];

        view('admin.layouts.components.format_cetak', $data);
    }

    public function qrcode($id = null): void
    {
        if ($id) {
            $urls   = Urls::find($id);
            $qrCode = [
                'isiqr'  => ci_route('v', $urls->alias),
                'logoqr' => gambar_desa($this->header['desa']['logo'], false, true),
                'sizeqr' => 6,
                'foreqr' => '#000000',
            ];

            $qrCode['viewqr'] = qrcode_generate($qrCode, true);
            view('admin.surat.keluar.qrcode', $qrCode);
        }
    }

    public function widget()
    {
        if (! setting('verifikasi_sekdes') && ! setting('verifikasi_kades')) {
            return null;
        }

        $listJabatan = [
            'jabatan_id'        => $this->isAdmin->jabatan_id,
            'jabatan_kades_id'  => kades()->id,
            'jabatan_sekdes_id' => sekdes()->id,
        ];

        return [
            'suratMasuk' => LogSurat::whereNull('deleted_at')->masuk($this->isAdmin, $listJabatan)->count(),
            'arsip'      => LogSurat::whereNull('deleted_at')->arsip($this->isAdmin, $listJabatan)->count(),
            'tolak'      => LogSurat::whereNull('deleted_at')->ditolak()->count(),
            'kecamatan'  => count($this->data_kecamatan() ?? []),
        ];
    }

    private function alihkan(): void
    {
        if (null === $this->widget()) {
            redirect('keluar');
        }
    }

    // TODO: OpenKab - Cek ORM ini
    public function perbaiki(): void
    {
        isCan('u');

        LogSurat::where('config_id', identitas('id'))->update(['status' => LogSurat::CETAK, 'verifikasi_operator' => 1, 'verifikasi_sekdes' => 1, 'verifikasi_kades' => 1]);

        redirect('keluar');
    }

    public function kecamatan(): void
    {
        $data['tab_ini'] = 13;

        if (setting('verifikasi_kades') || setting('verifikasi_sekdes')) {
            $data['operator'] = ($this->isAdmin->jabatan_id == 1 || $this->isAdmin->jabatan_id == 2) ? false : true;
            $data['widgets']  = $this->widget();
        }

        $data['main'] = $this->data_kecamatan();

        view('admin.surat.keluar.kecamatan', $data);
    }

    private function data_kecamatan()
    {
        if (empty($this->setting->sinkronisasi_opendk)) {
            return null;
        }
        $desa = kode_wilayah($this->header['desa']['kode_desa']);

        try {
            $client = new GuzzleHttp\Client([
                'base_uri' => "{$this->setting->api_opendk_server}/api/v1/surat?desa_id={$desa}",
            ]);

            $response = $client->get('', [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => "Bearer {$this->setting->api_opendk_key}",
                ],
            ]);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            log_message('error', $e);

            return null;
        } catch (Exception $exception) {
            log_message('error', $exception);

            return null;
        }

        $surat = json_decode($response->getBody()->getContents(), null);

        return $surat->data;
    }

    public function dataPenduduk(int $id): void
    {
        $penduduk = Penduduk::withOnly(['wilayah', 'agama', 'pendidikanKK', 'wargaNegara'])->findOrFail($id);
        $data     = [
            'ttl'         => $penduduk->tempatlahir . ' / ' . tgl_indo($penduduk->tanggallahir) . ' (' . $penduduk->usia . ')',
            'alamat'      => $penduduk->alamat_wilayah,
            'pendidikan'  => $penduduk->pendidikanKK->nama ?? '',
            'warganegara' => $penduduk->wargaNegara->nama ?? '',
            'agama'       => $penduduk->agama->nama ?? '',
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($data, JSON_THROW_ON_ERROR));
    }

    public function bulanTahun(int $tahun)
    {
        $surat = LogSurat::withOnly([])->distinct()->selectRaw(DB::raw('MONTH(tanggal) as bulan'))->whereNull('deleted_at')->whereYear('tanggal', '=', $tahun)->orderBy(DB::raw('MONTH(tanggal)'), 'asc')->get()->map(static function ($item) {
            $item->name = getBulan((int) ($item->bulan));

            return $item;
        })->toArray();
        $data = [
            'bulan' => $surat,
        ];

        return json($data);
    }
}
