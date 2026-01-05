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
use App\Traits\Upload;
use Illuminate\Support\Facades\View;
use Modules\Kehadiran\Enums\JenisIzin;
use Modules\Kehadiran\Enums\StatusApproval;
use Modules\Kehadiran\Models\PengajuanIzin;

defined('BASEPATH') || exit('No direct script access allowed');

class PengajuanIzinPamongController extends AdminModulController
{
    use Upload;

    public $moduleName      = 'Kehadiran';
    public $modul_ini       = 'kehadiran';
    public $sub_modul_ini   = 'pengajuan-izin';
    public $aliasController = 'kehadiran_pengajuan_izin_pamong';
    private $configUpload   = [];

    public function __construct()
    {
        parent::__construct();
        isCan('b');

        $this->configUpload = [
            'upload_path'   => LOKASI_UPLOAD . 'pengajuan_izin/',
            'allowed_types' => 'pdf|jpg|jpeg|png',
            'max_size'      => 10240, // 10MB
        ];
    }

    public function index()
    {
        return view('kehadiran::backend.pengajuan_izin_pamong.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            // Get current user's pamong data
            $user = auth()->user();

            return datatables()->of(PengajuanIzin::where('id_pamong', $user->pamong_id)->with(['approvedBy']))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h') && $row->status_approval === StatusApproval::PENDING) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    // Edit if pending
                    if (can('u') && $row->status_approval === StatusApproval::PENDING) {
                        $aksi .= View::make('admin.layouts.components.buttons.edit', [
                            'url' => 'kehadiran_pengajuan_izin_pamong/form/' . $row->id,
                        ])->render();
                    }

                    // Delete if pending
                    if (can('h') && $row->status_approval === StatusApproval::PENDING) {
                        $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                            'url'           => ci_route('kehadiran_pengajuan_izin_pamong.delete', $row->id),
                            'title'         => 'Hapus Data',
                            'confirmDelete' => true,
                        ])->render();
                    }

                    return $aksi;
                })
                ->editColumn('jenis_izin', static fn ($row) => JenisIzin::valueOf($row->jenis_izin) ?? $row->jenis_izin)
                ->editColumn('tanggal_mulai', static fn ($row) => tgl_indo($row->tanggal_mulai))
                ->editColumn('tanggal_selesai', static fn ($row) => tgl_indo($row->tanggal_selesai))->editColumn('created_at', static fn ($row) => tgl_indo2($row->created_at))
                ->editColumn('status_approval', static function ($row) {
                    $class = match ($row->status_approval) {
                        StatusApproval::PENDING  => 'label-warning',
                        StatusApproval::APPROVED => 'label-success',
                        StatusApproval::REJECTED => 'label-danger',
                        default                  => 'label-default'
                    };

                    return '<span class="label ' . $class . '">' . (StatusApproval::valueOf($row->status_approval) ?? $row->status_approval) . '</span>';
                })
                ->editColumn('approved_by', static fn ($row) => $row->approvedBy->nama ?? '-')
                ->rawColumns(['ceklist', 'aksi', 'status_approval'])
                ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        isCan('u');
        $user   = auth()->user();
        $pamong = Pamong::where('pamong_id', $user->pamong_id)->first();

        if (! $pamong) {
            redirect_with('error', 'Anda tidak terdaftar sebagai ' . SebutanDesa('[Pemerintah Desa]') . '.');
        }

        if ($id) {
            $action                   = 'Ubah';
            $form_action              = ci_route('kehadiran_pengajuan_izin_pamong.update', $id);
            $kehadiran_pengajuan_izin = PengajuanIzin::findOrFail($id);
            if ($kehadiran_pengajuan_izin->id_pamong !== $user->pamong_id) {
                redirect_with('error', 'Anda tidak memiliki izin untuk mengubah pengajuan ini.');
            }
            if ($kehadiran_pengajuan_izin->status_approval !== StatusApproval::PENDING) {
                show_error('Pengajuan hanya dapat diubah saat status masih pending.');
            }
        } else {
            $action                   = 'Tambah';
            $form_action              = ci_route('kehadiran_pengajuan_izin_pamong.create');
            $kehadiran_pengajuan_izin = null;
        }

        return view('kehadiran::backend.pengajuan_izin_pamong.form', [
            'action'                   => $action,
            'form_action'              => $form_action,
            'kehadiran_pengajuan_izin' => $kehadiran_pengajuan_izin,
            'pamong'                   => $pamong,
        ]);
    }

    public function create(): void
    {
        isCan('u');

        $user   = auth()->user();
        $pamong = Pamong::where('pamong_id', $user->pamong_id)->first();

        if (! $pamong) {
            redirect_with('error', 'Anda tidak terdaftar sebagai ' . SebutanDesa('[Pemerintah Desa]') . '.');
        }

        $data              = $this->validate($this->request);
        $data['id_pamong'] = $user->pamong_id;

        // Handle file upload: lampiran wajib hanya untuk jenis_izin == 'sakit'
        if (($data['jenis_izin'] ?? '') === 'sakit') {
            if (! empty($_FILES['lampiran']['name'])) {
                // Upload file using Upload trait
                $upload = $this->upload('lampiran', $this->configUpload, ci_route('kehadiran_pengajuan_izin_pamong.form'));

                if (! $upload) {
                    redirect_with('error', 'Gagal mengunggah lampiran.');
                }

                if (is_array($upload)) {
                    redirect_with('error', $upload['error']);
                }

                $data['lampiran'] = $upload;
            } else {
                redirect_with('error', 'Lampiran surat dokter wajib untuk izin sakit.');
            }
        } else {
            // untuk jenis izin selain sakit, lampiran bersifat opsional.
            if (! empty($_FILES['lampiran']['name'])) {
                $upload = $this->upload('lampiran', $this->configUpload, ci_route('kehadiran_pengajuan_izin_pamong.form'));

                if (! $upload) {
                    redirect_with('error', 'Gagal mengunggah lampiran.');
                }

                if (is_array($upload)) {
                    redirect_with('error', $upload['error']);
                }

                $data['lampiran'] = $upload;
            }
        }

        if (PengajuanIzin::create($data)) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = ''): void
    {
        isCan('u');

        $user   = auth()->user();
        $pamong = Pamong::where('pamong_id', $user->pamong_id)->first();

        if (! $pamong) {
            redirect_with('error', 'Anda tidak terdaftar sebagai ' . SebutanDesa('[Pemerintah Desa]') . '.');
        }

        $update = PengajuanIzin::findOrFail($id);
        if ($update->id_pamong !== $user->pamong_id) {
            redirect_with('error', 'Anda tidak memiliki izin untuk mengubah pengajuan ini.');
        }
        if ($update->status_approval !== StatusApproval::PENDING) {
            redirect_with('error', 'Pengajuan hanya dapat diubah saat status masih pending.');
        }

        $data = $this->validate($this->request, $id);

        // Handle file upload for sick leave attachments

            if (! empty($_FILES['lampiran']['name'])) {
                // Upload new file using Upload trait
                $upload = $this->upload('lampiran', $this->configUpload);

                if (! $upload) {
                    redirect_with('error', 'Gagal mengunggah lampiran.');
                }

                if (is_array($upload)) {
                    redirect_with('error', $upload['error']);
                }

                $data['lampiran'] = $upload;
            } else {
                // No new file uploaded, keep existing file
                $data['lampiran'] = $update->lampiran;

                // Validate that sick leave has attachment (either existing or new) only when jenis_izin == 'sakit'
                if (($data['jenis_izin'] ?? '') === 'sakit' && empty($data['lampiran'])) {
                    redirect_with('error', 'Lampiran surat dokter wajib untuk izin sakit.');
                }
            }

        if ($update->update($data)) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id): void
    {
        isCan('h');

        $user = auth()->user();
    $pamong   = Pamong::where('pamong_id', $user->pamong_id)->first();

        if (! $pamong) {
            redirect_with('error', 'Anda tidak terdaftar sebagai ' . SebutanDesa('[Pemerintah Desa]') . '.');
        }

    $pengajuan = PengajuanIzin::where('id_pamong', $pamong->pamong_id)->findOrFail($id);

        if ($pengajuan->status_approval !== StatusApproval::PENDING) {
            redirect_with('error', 'Pengajuan hanya dapat dihapus saat status masih pending.');
        }

        if (PengajuanIzin::destroy($id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function delete_all(): void
    {
        isCan('h');

        $user = auth()->user();
    $pamong   = Pamong::where('pamong_id', $user->pamong_id)->first();

        if (! $pamong) {
            redirect_with('error', 'Anda tidak terdaftar sebagai ' . SebutanDesa('[Pemerintah Desa]') . '.');
        }

        $pengajuanIds = PengajuanIzin::whereIn('id', $this->request['id_cb'])
            ->where('id_pamong', $pamong->pamong_id)
            ->where('status_approval', StatusApproval::PENDING)
            ->get();

        if ($pengajuanIds->count() > 0 && PengajuanIzin::destroy($pengajuanIds->pluck('id'))) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    private function validate(array $request = [], $id = ''): array
    {
        $errors = [];

        // Validate jenis_izin
        $jenisIzinCases = array_keys(JenisIzin::all());
        if (empty($request['jenis_izin']) || ! in_array($request['jenis_izin'], $jenisIzinCases)) {
            $errors[] = 'Jenis izin harus dipilih.';
        }

        // Validate dates
        if (empty($request['tanggal_mulai'])) {
            $errors[] = 'Tanggal mulai harus diisi.';
        }

        if (empty($request['tanggal_selesai'])) {
            $errors[] = 'Tanggal selesai harus diisi.';
        }

        // Validate keterangan
        if (empty($request['keterangan'])) {
            $errors[] = 'Keterangan harus diisi.';
        }

        if (! empty($errors)) {
            redirect_with('error', implode(' ', $errors));
        }

        $tanggalMulai   = date('Y-m-d', strtotime($request['tanggal_mulai']));
        $tanggalSelesai = date('Y-m-d', strtotime($request['tanggal_selesai']));

        if ($tanggalSelesai < $tanggalMulai) {
            redirect_with('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
        }

        // pastikan tidak overlap pengajuannya
        $user      = auth()->user();
        $excludeId = $id ?: null;
        if (PengajuanIzin::hasConflict($user->pamong_id, $tanggalMulai, $tanggalSelesai, $excludeId)) {
            redirect_with('error', 'Sudah ada pengajuan di periode tanggal tersebut');
        }

        return [
            'jenis_izin'      => strip_tags($request['jenis_izin']),
            'tanggal_mulai'   => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'keterangan'      => strip_tags($request['keterangan']),
        ];
    }
}
