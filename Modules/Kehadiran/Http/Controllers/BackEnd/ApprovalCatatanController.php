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

use App\Models\Pamong;
use Illuminate\Support\Facades\View;
use Modules\Kehadiran\Enums\StatusCatatan;
use Modules\Kehadiran\Http\Requests\ApprovalCatatanRejectRequest;
use Modules\Kehadiran\Models\CatatanHarian;
use Modules\Kehadiran\Services\ApprovalCatatanService;

defined('BASEPATH') || exit('No direct script access allowed');

class ApprovalCatatanController extends AdminModulController
{
    public $moduleName      = 'Kehadiran';
    public $modul_ini       = 'kehadiran';
    public $sub_modul_ini   = 'persetujuan-catatan';
    public $aliasController = 'kehadiran_approval_catatan';
    private ApprovalCatatanService $service;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->checkOnlyKepalaDesaCanAccess();
        $this->service = new ApprovalCatatanService();
    }

    public function index()
    {
        $statuses = collect(StatusCatatan::cases())
            ->mapWithKeys(static fn ($status) => [$status->value => $status->label()])
            ->toArray();

        $pamongs = Pamong::with('penduduk:id,nama')
            ->whereHas('jabatan')
            ->orderBy('id_pend')
            ->get()
            ->map(static fn ($pamong) => [
                'id'   => $pamong->pamong_id,
                'nama' => $pamong->pamong_nama,
            ]);

        return view('kehadiran::backend.approval_catatan.index', [
            'statuses' => $statuses,
            'pamongs'  => $pamongs,
        ]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status = request('status');

            if ($status === null) {
                $status = StatusCatatan::MENUNGGU->value;
            }

            $query = CatatanHarian::query()
                ->with([
                    'pamong:pamong_id,id_pend,jabatan_id,pamong_nama,gelar_depan,gelar_belakang',
                    'pamong.penduduk:id,nama',
                    'approver:pamong_id,id_pend',
                    'approver.penduduk:id,nama',
                ]);

            $query->when($status === '', static function ($q) {
                $q->whereNotIn('status', [StatusCatatan::DRAFT->value]);
            }, static function ($q) use ($status) {
                $q->where('status', $status);
            });

            $pamongId = request('pamong_id');
            $query->when(! empty($pamongId), static function ($q) use ($pamongId) {
                $q->where('pamong_id', $pamongId);
            });

            $query->orderBy('created_at', 'asc');

            return datatables()->of($query)
                ->addIndexColumn()
                ->addColumn('nama_pamong', static fn ($row) => $row->pamong?->pamong_nama ?? '-')
                ->addColumn('jabatan', static fn ($row) => $row->pamong?->jabatan?->nama ?? '-')
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (in_array($row->status->value, [StatusCatatan::MENUNGGU->value, StatusCatatan::DISETUJUI->value, StatusCatatan::DITOLAK->value])) {
                        $aksi .= View::make('admin.layouts.components.buttons.lihat', [
                            'url'   => site_url('kehadiran_approval_catatan/detail/' . $row->uuid),
                            'judul' => 'Lihat Data',
                        ])->render();
                    }

                    if (can('u') && $row->status->value === StatusCatatan::MENUNGGU->value) {
                        $aksi .= ' <a href="#" data-action="approve" data-id="' . $row->uuid . '" class="btn btn-success btn-sm btn-action" title="Setujui Catatan"><i class="fa fa-check"></i></a>';
                        $aksi .= ' <a href="#" data-action="reject" data-id="' . $row->uuid . '" class="btn bg-maroon btn-sm btn-action" title="Tolak Catatan"><i class="fa fa-times"></i></a>';
                    }

                    return $aksi;
                })
                ->editColumn('tanggal', static fn ($row) => tgl_indo($row->tanggal))
                ->editColumn('uraian_kegiatan', static fn ($row) => mb_strlen($row->uraian_kegiatan) > 50 ? mb_substr($row->uraian_kegiatan, 0, 50) . '...' : $row->uraian_kegiatan)
                ->rawColumns(['aksi'])
                ->make();
        }

        return show_404();
    }

    public function detail(string $id)
    {
        $catatan = CatatanHarian::with([
            'pamong',
            'pamong.penduduk',
            'fotos:uuid,catatan_harian_id,file_path,keterangan',
        ])
            ->whereIn('status', [StatusCatatan::MENUNGGU->value, StatusCatatan::DISETUJUI->value, StatusCatatan::DITOLAK->value])
            ->find($id);

        if (! $catatan) {
            redirect_with('error', 'Catatan tidak ditemukan, sudah dihapus, atau statusnya bukan menunggu persetujuan.');
        }

        return view('kehadiran::backend.approval_catatan.detail', [
            'catatan' => $catatan,
            'proses'  => $catatan->status->value === StatusCatatan::MENUNGGU->value,
        ]);
    }

    public function approve(string $id)
    {
        isCan('u');

        try {
            $catatan = CatatanHarian::find($id);

            if (! $catatan) {
                return json([
                    'status'  => false,
                    'message' => 'Catatan tidak ditemukan atau sudah dihapus.',
                ]);
            }

            $approverPamongId = auth()->user()?->pamong_id;

            $this->service->approveCatatan($catatan, $approverPamongId);

            return json([
                'status'       => true,
                'message'      => 'Catatan berhasil disetujui',
                'redirect_url' => ci_route('kehadiran_approval_catatan'),
            ]);
        } catch (InvalidArgumentException $e) {
            logger()->error('Gagal menyetujui catatan: ' . $e->getMessage());

            return json([
                'status'  => false,
                'message' => 'Gagal menyetujui catatan',
            ]);
        } catch (Exception $e) {
            logger()->error('Gagal menyetujui catatan: ' . $e->getMessage());

            return json([
                'status'  => false,
                'message' => 'Gagal menyetujui catatan',
            ]);
        }
    }

    public function reject(ApprovalCatatanRejectRequest $request, string $id)
    {
        isCan('u');

        $catatan = CatatanHarian::find($id);

        if (! $catatan) {
            return json([
                'status'  => false,
                'message' => 'Catatan tidak ditemukan atau sudah dihapus.',
            ]);
        }

        $data    = $request->validated();

        try {
            $approverPamongId = auth()->user()?->pamong_id;
            $this->service->rejectCatatan($catatan, $data['alasan_penolakan'], $approverPamongId);

            return json([
                'status'       => true,
                'message'      => 'Catatan berhasil ditolak',
                'redirect_url' => ci_route('kehadiran_approval_catatan'),
            ]);
        } catch (InvalidArgumentException $e) {
            logger()->error('Gagal menolak catatan: ' . $e->getMessage());

            return json([
                'status'  => false,
                'message' => 'Gagal menolak catatan',
            ]);
        } catch (Exception $e) {
            logger()->error('Gagal menolak catatan: ' . $e->getMessage());

            return json([
                'status'  => false,
                'message' => 'Gagal menolak catatan',
            ]);
        }
    }

    public function bulkApprove()
    {
        isCan('u');

        try {
            $ids              = request('id_cb');
            $approverPamongId = auth()->user()?->pamong_id;

            $updated = $this->service->bulkApproveCatatan(
                (array) $ids,
                $approverPamongId
            );

            return json([
                'status'  => true,
                'message' => 'Sebanyak ' . $updated . ' catatan berhasil disetujui',
            ]);
        } catch (InvalidArgumentException $e) {
            logger()->error('Gagal menyetujui catatan: ' . $e->getMessage());

            return json([
                'status'  => false,
                'message' => 'Gagal menyetujui catatan',
            ]);
        } catch (Exception $e) {
            logger()->error('Gagal menyetujui catatan: ' . $e->getMessage());

            return json([
                'status'  => false,
                'message' => 'Gagal menyetujui catatan',
            ]);
        }
    }

    /**
     * Check if user is Kepala Desa
     */
    private function checkOnlyKepalaDesaCanAccess(): void
    {
        $user = auth()->user();

        $pamong = Pamong::where('pamong_id', $user->pamong_id)
            ->with('jabatan:id,nama')
            ->first();

        if (! $pamong || $pamong->jabatan_id !== kades()->id) {
            redirect_with('error', 'Hanya Kepala Desa yang dapat mengakses halaman persetujuan catatan harian.', ci_route('beranda'));
        }
    }
}
