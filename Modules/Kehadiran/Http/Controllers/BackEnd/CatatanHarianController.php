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
use Modules\Kehadiran\Enums\StatusCatatan;
use Modules\Kehadiran\Http\Requests\CatatanHarianRequest;
use Modules\Kehadiran\Models\CatatanHarian;
use Modules\Kehadiran\Models\CatatanHarianFoto;
use Modules\Kehadiran\Services\CatatanHarianService;

defined('BASEPATH') || exit('No direct script access allowed');

class CatatanHarianController extends AdminModulController
{
    use Upload;

    public $moduleName      = 'Kehadiran';
    public $modul_ini       = 'kehadiran';
    public $sub_modul_ini   = 'catatan-harian-kerja';
    public $aliasController = 'kehadiran_catatan_harian';
    private $configUpload   = [];
    private CatatanHarianService $service;

    public function __construct()
    {
        parent::__construct();
        isCan('b');

        $this->configUpload = [
            'upload_path'   => LOKASI_CATATAN_HARIAN,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size'      => max_upload(),
        ];

        $this->service = new CatatanHarianService();
    }

    public function index()
    {
        return view('kehadiran::backend.catatan_harian.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $user   = auth()->user();
            $status = request('status');

            $query = CatatanHarian::where('pamong_id', $user->pamong_id);

            if (! empty($status)) {
                $query->where('status', $status);
            }

            return datatables()->of(
                $query->with(['pamong'])
                    ->orderBy('tanggal', 'desc')
            )
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    $aksi .= View::make('admin.layouts.components.buttons.lihat', [
                        'url'   => site_url('kehadiran_catatan_harian/view/' . $row->uuid),
                        'judul' => 'Lihat Data',
                    ])->render();

                    if (can('u') && in_array($row->status, [StatusCatatan::DRAFT, StatusCatatan::DITOLAK])) {
                        $aksi .= View::make('admin.layouts.components.buttons.edit', [
                            'url' => 'kehadiran_catatan_harian/form/' . $row->uuid,
                        ])->render();

                        $aksi .= ' <a href="#" data-href="' . route('kehadiran_catatan_harian.submit', $row->uuid) . '" class="btn btn-success btn-sm btn-submit-catatan" title="Kirim untuk Persetujuan"><i class="fa fa-send"></i></a> ';
                    }

                    if (can('h') && in_array($row->status, [StatusCatatan::DRAFT, StatusCatatan::DITOLAK])) {
                        $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                            'url'           => route('kehadiran_catatan_harian.delete', $row->uuid),
                            'confirmDelete' => true,
                            'selectData'    => false,
                            'visible'       => false,
                            'target'        => 'confirm-delete',
                        ])->render();
                    }

                    return $aksi;
                })
                ->editColumn('tanggal', static fn ($row) => tgl_indo($row->tanggal))
                ->editColumn('status', static function ($row) {
                    $status = $row->status;
                    $class  = 'label-' . $status->badgeColor();

                    return '<span class="label ' . $class . '">' . $status->label() . '</span>';
                })
                ->editColumn('uraian_kegiatan', static fn ($row) => mb_strlen($row->uraian_kegiatan) > 50 ? mb_substr($row->uraian_kegiatan, 0, 50) . '...' : $row->uraian_kegiatan)
                ->rawColumns(['aksi', 'status'])
                ->make();
        }

        return show_404();
    }

    public function form(?string $id = null)
    {
        isCan('u');
        $user   = auth()->user();
        $pamong = Pamong::where('pamong_id', $user->pamong_id)->first();

        if (! $pamong) {
            return redirect_with('error', 'Anda tidak terdaftar sebagai ' . SebutanDesa('[Pemerintah Desa]') . '.');
        }

        if ($id) {
            $action         = 'Ubah';
            $form_action    = ci_route('kehadiran_catatan_harian.update', $id);
            $catatan_harian = CatatanHarian::with('fotos')->find($id);

            if (! $catatan_harian) {
                return redirect_with('error', 'Catatan tidak ditemukan atau sudah dihapus.');
            }

            if ($catatan_harian->pamong_id !== $user->pamong_id) {
                return redirect_with('error', 'Anda tidak memiliki izin untuk mengubah catatan ini.');
            }

            if (! in_array($catatan_harian->status, [StatusCatatan::DRAFT, StatusCatatan::DITOLAK])) {
                return redirect_with('error', 'Catatan hanya dapat diubah jika status masih draft atau ditolak.');
            }
        } else {
            $action         = 'Tambah';
            $form_action    = ci_route('kehadiran_catatan_harian.create');
            $catatan_harian = null;
        }

        return view('kehadiran::backend.catatan_harian.form', [
            'action'         => $action,
            'form_action'    => $form_action,
            'catatan_harian' => $catatan_harian,
            'pamong'         => $pamong,
        ]);
    }

    public function create()
    {
        isCan('u');

        try {
            $user   = auth()->user();
            $pamong = Pamong::where('pamong_id', $user->pamong_id)->first();

            if (! $pamong) {
                return json([
                    'status'  => false,
                    'message' => 'Anda tidak terdaftar sebagai ' . SebutanDesa('[Pemerintah Desa]') . '.',
                ]);
            }

            $request           = new CatatanHarianRequest();
            $data              = $request->validated();
            $data['config_id'] = identitas('id');
            $data['pamong_id'] = $user->pamong_id;
            $data['status']    = StatusCatatan::DRAFT->value;

            $fotos = [];
            if (! empty($_FILES['fotos']['name'][0])) {
                $uploadedFotos = $this->handleFotoUpload($data['config_id'], $data['tanggal']);
                if ($uploadedFotos === false) {
                    return json([
                        'status'  => false,
                        'message' => 'Gagal mengunggah foto.',
                    ]);
                }
                $fotos = $uploadedFotos ?? [];
            }

            $data = array_merge($data, [
                'fotos' => $fotos,
            ]);

            $this->service->createCatatan($data, $fotos);

            return json([
                'status'       => true,
                'message'      => 'Berhasil Tambah Data',
                'redirect_url' => ci_route('kehadiran_catatan_harian'),
            ]);
        } catch (Illuminate\Database\QueryException $e) {
            logger()->error('Gagal tambah catatan harian: ' . $e->getMessage());
            if (strpos($e->getMessage(), 'catatan_harian_config_pamong_tanggal_unique') !== false) {
                return json([
                    'status'  => false,
                    'message' => 'Tanggal ini sudah pernah Anda inputkan. Satu catatan hanya bisa dibuat satu kali per hari.',
                ]);
            }

            return json([
                'status'  => false,
                'message' => 'Gagal menyimpan data. Silakan coba lagi.',
            ]);
        } catch (Exception $e) {
            logger()->error('Gagal tambah catatan harian: ' . $e->getMessage());

            return json([
                'status'  => false,
                'message' => 'Gagal menyimpan data. Silakan coba lagi.',
            ]);
        }
    }

    public function update(string $id)
    {
        isCan('u');

        try {
            $user   = auth()->user();
            $pamong = Pamong::where('pamong_id', $user->pamong_id)->first();

            if (! $pamong) {
                return json([
                    'status'  => false,
                    'message' => 'Anda tidak terdaftar sebagai ' . SebutanDesa('[Pemerintah Desa]') . '.',
                ]);
            }

            $catatan = CatatanHarian::find($id);

            if (! $catatan) {
                return json([
                    'status'  => false,
                    'message' => 'Catatan tidak ditemukan atau sudah dihapus.',
                ]);
            }

            if ($catatan->pamong_id !== $user->pamong_id) {
                return json([
                    'status'  => false,
                    'message' => 'Anda tidak memiliki izin untuk mengubah catatan ini.',
                ]);
            }

            if (! in_array($catatan->status, [StatusCatatan::DRAFT, StatusCatatan::DITOLAK])) {
                return json([
                    'status'  => false,
                    'message' => 'Catatan hanya dapat diubah jika status masih draft atau ditolak.',
                ]);
            }

            $request = new CatatanHarianRequest();
            $data    = $request->validated();

            $fotos = [];
            if (! empty($_FILES['fotos']['name'][0])) {
                $uploadedFotos = $this->handleFotoUpload($catatan->config_id, $data['tanggal']);
                if ($uploadedFotos === false) {
                    return json([
                        'status'  => false,
                        'message' => 'Gagal mengunggah foto.',
                    ]);
                }
                $fotos = $uploadedFotos ?? [];
            }

            $fotosToDelete    = request('fotos_to_delete');
            $fotosToDeleteArr = ! empty($fotosToDelete) ? explode(',', $fotosToDelete) : [];

            $this->service->updateCatatan($catatan, $data, $fotos, $fotosToDeleteArr);

            return json([
                'status'       => true,
                'message'      => 'Berhasil Ubah Data',
                'redirect_url' => ci_route('kehadiran_catatan_harian'),
            ]);
        } catch (Illuminate\Database\QueryException $e) {
            logger()->error('Gagal ubah catatan harian: ' . $e->getMessage());
            if (strpos($e->getMessage(), 'catatan_harian_config_pamong_tanggal_unique') !== false) {
                return json([
                    'status'  => false,
                    'message' => 'Tanggal ini sudah pernah Anda inputkan. Satu catatan hanya bisa dibuat satu kali per hari.',
                ]);
            }

            return json([
                'status'  => false,
                'message' => 'Gagal menyimpan data. Silakan coba lagi.',
            ]);
        } catch (Exception $e) {
            logger()->error('Gagal ubah catatan harian: ' . $e->getMessage());

            return json([
                'status'  => false,
                'message' => 'Gagal menyimpan data. Silakan coba lagi.',
            ]);
        }
    }

    public function detail(string $id)
    {
        $user           = auth()->user();
        $catatan_harian = CatatanHarian::find($id);

        if (! $catatan_harian) {
            redirect_with('error', 'Catatan tidak ditemukan atau sudah dihapus.');
        }

        if ($catatan_harian->pamong_id !== $user->pamong_id) {
            redirect_with('error', 'Anda tidak memiliki izin untuk melihat catatan ini.');
        }

        return view('kehadiran::backend.catatan_harian.detail', [
            'catatan_harian' => $catatan_harian,
        ]);
    }

    public function view(string $id)
    {
        $user   = auth()->user();
        $pamong = Pamong::where('pamong_id', $user->pamong_id)->first();

        if (! $pamong) {
            return redirect_with('error', 'Anda tidak terdaftar sebagai ' . SebutanDesa('[Pemerintah Desa]') . '.');
        }

        $catatan_harian = CatatanHarian::with('fotos')->find($id);

        if (! $catatan_harian) {
            return redirect_with('error', 'Catatan tidak ditemukan atau sudah dihapus.');
        }

        if ($catatan_harian->pamong_id !== $user->pamong_id && ! can('b', 'kehadiran_approval_catatan')) {
            return redirect_with('error', 'Anda tidak memiliki izin untuk melihat catatan ini.');
        }

        return view('kehadiran::backend.catatan_harian.form', [
            'action'         => 'Lihat',
            'form_action'    => '#',
            'catatan_harian' => $catatan_harian,
            'pamong'         => $pamong,
            'isView'         => true,
        ]);
    }

    public function submit(string $id)
    {
        isCan('u');
        $user    = auth()->user();
        $catatan = CatatanHarian::find($id);

        if (! $catatan) {
            return redirect_with('error', 'Catatan tidak ditemukan atau sudah dihapus.');
        }

        if ($catatan->pamong_id !== $user->pamong_id) {
            return redirect_with('error', 'Anda tidak memiliki izin untuk mengirim catatan ini.');
        }

        if (! in_array($catatan->status, [StatusCatatan::DRAFT, StatusCatatan::DITOLAK])) {
            return redirect_with('error', 'Catatan ini tidak dapat dikirim.');
        }

        try {
            $catatan->update(['status' => StatusCatatan::MENUNGGU->value]);

            return redirect_with('success', 'Catatan berhasil dikirim untuk persetujuan.');
        } catch (Exception $e) {
            logger()->error('Gagal kirim catatan harian: ' . $e->getMessage());

            return redirect_with('error', 'Gagal mengirim catatan. Silakan coba lagi.');
        }
    }

    public function delete(string $id): void
    {
        isCan('h');
        $user    = auth()->user();
        $catatan = CatatanHarian::find($id);

        // Null check: Record might have been deleted
        if (! $catatan) {
            redirect_with('error', 'Catatan tidak ditemukan atau sudah dihapus.');
        }

        if ($catatan->pamong_id !== $user->pamong_id) {
            redirect_with('error', 'Anda tidak memiliki izin untuk menghapus catatan ini.');
        }

        if (! in_array($catatan->status, [StatusCatatan::DRAFT, StatusCatatan::DITOLAK])) {
            redirect_with('error', 'Catatan hanya dapat dihapus jika status masih draft atau ditolak.');
        }

        try {
            $this->service->deleteCatatan($catatan);
            redirect_with('success', 'Berhasil Hapus Data');
        } catch (Exception $e) {
            redirect_with('error', 'Gagal Hapus Data: ' . $e->getMessage());
        }
    }

    /**
     * Delete a single foto from catatan harian
     */
    /**
     * Delete multiple fotos (batch delete)
     */
    public function deleteFotosBatch()
    {
        try {
            isCan('u');
            $user = auth()->user();

            $fotos = request('fotos', []);

            if (empty($fotos)) {
                return json(['status' => false, 'message' => 'Tidak ada foto untuk dihapus']);
            }

            $deletedCount = 0;

            foreach ($fotos as $fotoId) {
                $foto = CatatanHarianFoto::find($fotoId);

                if (! $foto) {
                    continue;
                }

                $catatan = $foto->catatanHarian;

                if (! $catatan || $catatan->pamong_id !== $user->pamong_id) {
                    continue;
                }

                $this->deleteFile($foto->file_path);
                $foto->delete();
                $deletedCount++;
            }

            return json([
                'status'  => true,
                'message' => 'Berhasil menghapus ' . $deletedCount . ' foto',
            ]);
        } catch (Exception $e) {
            logger()->error('Gagal menghapus foto: ' . $e->getMessage());

            return json([
                'status'  => false,
                'message' => 'Gagal menghapus foto',
            ]);
        }
    }

    public function fotos()
    {
        $uuid = request('uuid');

        if (empty($uuid)) {
            return json(['success' => false, 'message' => 'UUID tidak ditemukan']);
        }

        try {
            $catatan = CatatanHarian::find($uuid);

            if (! $catatan) {
                return json(['success' => false, 'message' => 'Catatan tidak ditemukan']);
            }

            $user = auth()->user();
            if ($catatan->pamong_id !== $user->pamong_id && ! can('b', 'kehadiran_approval_catatan')) {
                return json(['success' => false, 'message' => 'Anda tidak memiliki izin mengakses foto ini']);
            }

            $fotos = CatatanHarianFoto::where('catatan_harian_id', $uuid)
                ->get(['file_path', 'keterangan'])
                ->map(static fn ($foto) => [
                    'file_path'  => base_url($foto->file_path),
                    'keterangan' => $foto->keterangan,
                ])
                ->toArray();

            return json(['success' => true, 'fotos' => $fotos]);
        } catch (Exception $e) {
            logger()->error('Gagal memuat foto: ' . $e->getMessage());

            return json(['success' => false, 'message' => 'Gagal memuat foto']);
        }
    }

    /**
     * Handle multiple foto uploads with server-side validation
     */
    private function handleFotoUpload(int $configId, string $tanggal): array|bool
    {
        $return = [];
        $files  = $_FILES['fotos'];

        // Allowed MIME types
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowedExts  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        for ($i = 0; $i < count($files['name']); $i++) {
            if (empty($files['name'][$i])) {
                continue;
            }

            // Move file to temp location
            $tmpFile  = $files['tmp_name'][$i];
            $fileName = basename($files['name'][$i]);
            $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // 1. Validasi ekstensi file
            if (! in_array($fileExt, $allowedExts, true)) {
                continue; // Skip file dengan ekstensi tidak diizinkan
            }

            // 2. Validasi MIME type server-side menggunakan finfo
            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $tmpFile);
            finfo_close($finfo);

            if (! in_array($mimeType, $allowedMimes, true)) {
                continue; // Skip file dengan MIME type tidak diizinkan
            }

            // Create upload directory if not exists
            $uploadDir = LOKASI_CATATAN_HARIAN . $configId . '/' . date('Y/m/');
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // 3. Sanitasi nama file - hanya alphanumeric + extension
            $fileName = date('YmdHis') . '_' . $i . '_' . uniqid() . '.' . $fileExt;
            $filePath = $uploadDir . $fileName;

            // Validate file size
            $fileSize = filesize($tmpFile);
            // max_upload_size in php.ini should also be set to 2MB or higher
            if ($fileSize > $this->configUpload['max_size'] * 1024) {
                return false;
            }

            // Move file
            if (move_uploaded_file($tmpFile, $filePath)) {
                // Resize gambar ke max 1024px width
                $this->resizeImage($filePath, 1024);

                $relativePath = str_replace(FCPATH, '', $filePath);

                // Sanitasi keterangan foto
                $keterangan = isset($_POST['keterangan_foto'][$i]) ?
                    htmlspecialchars(trim($_POST['keterangan_foto'][$i]), ENT_QUOTES, 'UTF-8') : null;

                $return[] = [
                    'path'       => $relativePath,
                    'keterangan' => $keterangan,
                ];
            }
        }

        return $return;
    }

    /**
     * Resize image to max width
     */
    private function resizeImage(string $filePath, int $maxWidth): void
    {
        $info = getimagesize($filePath);
        if ($info === false) {
            return;
        }

        $width  = $info[0];
        $height = $info[1];
        $type   = $info[2];

        if ($width <= $maxWidth) {
            return;
        }

        // Calculate new dimensions
        $newHeight = ($height / $width) * $maxWidth;

        // Create new image
        $image = imagecreatetruecolor($maxWidth, (int) $newHeight);

        // Load original image
        switch ($type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($filePath);
                break;

            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($filePath);
                break;

            default:
                return;
        }

        // Resize
        imagecopyresampled($image, $source, 0, 0, 0, 0, $maxWidth, (int) $newHeight, $width, $height);

        // Save resized image
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($image, $filePath, 90);
                break;

            case IMAGETYPE_PNG:
                imagepng($image, $filePath, 9);
                break;
        }

        imagedestroy($image);
        imagedestroy($source);
    }

    /**
     * Delete file
     */
    private function deleteFile(string $filePath): void
    {
        $fullPath = FCPATH . $filePath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
