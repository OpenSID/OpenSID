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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use AdminModulController;
use App\Models\Pamong;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\View;
use Modules\Kehadiran\Enums\StatusApproval;
use Modules\Kehadiran\Models\PengajuanIzin;
use Yajra\DataTables\DataTables;

defined('BASEPATH') || exit('No direct script access allowed');

class PengajuanIzinController extends AdminModulController
{
    public $moduleName      = 'Kehadiran';
    public $modul_ini       = 'kehadiran';
    public $sub_modul_ini   = 'approval-izin';
    public $aliasController = 'kehadiran_pengajuan_izin';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    /**
     * Display a listing of pengajuan izin.
     */
    public function index()
    {
        $data = [
            'title'            => 'Pengajuan Izin',
            'subtitle'         => 'Kelola Pengajuan Izin Perangkat Desa',
            'jenisIzinOptions' => PengajuanIzin::getJenisIzinOptions(),
            'statusOptions'    => PengajuanIzin::getStatusApprovalOptions(),
            'pamongList'       => Pamong::where('pamong_status', 1)->get(),
        ];

        return view('kehadiran::backend.pengajuan_izin.index', $data);
    }

    /**
     * Get data for DataTables.
     */
    public function datatables(): JsonResponse
    {
        $query   = PengajuanIzin::with(['pamong', 'approvedBy']);
        $user    = auth()->user();
        $canEdit = true;
        if (! is_super_admin()) {
            $query->whereIn('id_pamong', static function ($subQuery) use ($user) {
                $subQuery->select('pamong_id')
                    ->from('tweb_desa_pamong')
                    ->where('atasan', $user->pamong_id);
            });
            $canEdit = can('u');
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pamong_nama', static fn ($row) => $row->pamong ? $row->pamong->pamong_nama : '-')->addColumn('pamong_jabatan', static fn ($row) => $row->pamong ? $row->pamong->jabatan?->nama : '-')
            ->editColumn('jenis_izin', static fn ($row) => $row->jenis_izin)->editColumn('tanggal_mulai', static fn ($row) => tgl_indo($row->tanggal_mulai))
            ->editColumn('tanggal_selesai', static fn ($row) => tgl_indo($row->tanggal_selesai))->editColumn('created_at', static fn ($row) => tgl_indo2($row->created_at))
            ->editColumn('status_approval', static function ($row) {
                $statusClass = match ($row->status_approval) {
                    'pending'  => 'label-warning',
                    'approved' => 'label-success',
                    'rejected' => 'label-danger',
                    default    => 'label-default'
                };

                return '<span class="label ' . $statusClass . '">' . StatusApproval::valueOf($row->status_approval) . '</span>';
            })
            ->addColumn('durasi_hari', static function ($row) {
                if ($row->tanggal_mulai && $row->tanggal_selesai) {
                    $start = Carbon\Carbon::parse($row->tanggal_mulai);
                    $end   = Carbon\Carbon::parse($row->tanggal_selesai);
                    $days  = $start->diffInDays($end) + 1;

                    return $days . ' hari';
                }

                return '-';
            })->addColumn('approved_by_name', static fn ($row) => $row->approvedBy ? $row->approvedBy->nama : '-')->editColumn('keterangan', static function ($row) {
                $linkLampiran = '';
                if (! empty($row->lampiran)) {
                    $urlLampiran  = $row->link_lampiran;
                    $linkLampiran = '<br><a href="' . base_url($urlLampiran) . '" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-paperclip"></i> Lampiran</a>';
                }

                return ($row->keterangan ? nl2br(e($row->keterangan)) : '-') . '  ' . $linkLampiran;
            })
            ->addColumn('aksi', static function ($row) use ($canEdit) {
                $aksi = '';

                // Approval buttons (only for pending status)
                if ($row->status_approval === StatusApproval::PENDING && $canEdit) {
                    $aksi .= str_replace(['bg-maroon', 'fa-trash-o'], ['bg-primary', 'fa-check approve-btn'], View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => ci_route('kehadiran_pengajuan_izin.approve', $row->id),
                        'judul'         => 'Setujui Pengajuan',
                        'confirmDelete' => true,
                    ])->render());

                    $aksi .= str_replace('fa-trash-o', 'fa-times reject-btn', View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => ci_route('kehadiran_pengajuan_izin.reject', $row->id),
                        'judul'         => 'Tolak Pengajuan',
                        'confirmDelete' => true,
                    ])->render());
                }

                return $aksi;
            })
            ->rawColumns(['status_approval', 'aksi', 'keterangan'])
            ->make(true);
    }

    /**
     * Show detail pengajuan izin.
     *
     * @param mixed $id
     */
    public function detail($id)
    {
        $pengajuan = PengajuanIzin::with(['pamong', 'approvedBy'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $pengajuan,
        ]);
    }

    /**
     * Approve pengajuan izin.
     *
     * @param mixed $id
     */
    public function approve($id): void
    {
        isCan('u');

        $pengajuan = PengajuanIzin::findOrFail($id);
        if ($pengajuan->status_approval !== StatusApproval::PENDING) {
            redirect_with('error', 'Pengajuan hanya dapat disetujui saat status masih pending.');
        }

        try {
            if ($pengajuan->approve(auth()->id(), 'Disetujui oleh admin')) {
                // insert ke tabel kehadiran jika belum ada
                $pengajuan->insertKehadiranForIzin();
                redirect_with('success', 'Berhasil Menyetujui Pengajuan Izin');
            }
        } catch (Exception $e) {
            redirect_with('error', 'Gagal Menyetujui Pengajuan Izin ' . $e->getMessage());
        }
    }

    public function reject($id): void
    {
        isCan('u');

        $pengajuan = PengajuanIzin::findOrFail($id);
        if ($pengajuan->status_approval !== StatusApproval::PENDING) {
            redirect_with('error', 'Pengajuan hanya dapat ditolak saat status masih pending.');
        }

        try {
            if ($pengajuan->reject(auth()->id(), 'Ditolak oleh admin')) {
                redirect_with('success', 'Berhasil Menolak Pengajuan Izin');
            }
        } catch (Exception $e) {
            redirect_with('error', 'Gagal Menolak Pengajuan Izin ' . $e->getMessage());
        }
    }
}
