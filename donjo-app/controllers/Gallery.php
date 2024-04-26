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

use App\Enums\StatusEnum;
use App\Models\Galery;

defined('BASEPATH') || exit('No direct script access allowed');

class Gallery extends Admin_Controller
{
    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'galeri';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $parent = $this->input->get('parent') ?? 0;
        $data   = [
            'status' => [StatusEnum::YA => 'Aktif', StatusEnum::TIDAK => 'Non Aktif'],
            'parent' => strlen($parent) > 20 ? decrypt($parent) : $parent,
        ];
        $data['parentEncrypt'] = encrypt($data['parent']);
        $data['subtitle']      = $data['parent'] > 0 ? strtoupper(Galery::find($data['parent'])->nama ?? '') : '';
        view('admin.web.gallery.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $parent    = (int) ($this->input->get('parent') ?? 0);
            $canDelete = can('h');
            $canUpdate = can('u');

            return datatables()->of(Galery::child($parent)->with(['parent']))
                ->addColumn('ceklist', static function ($row) use ($canDelete) {
                    if ($canDelete) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('drag-handle', static fn () => '<i class="fa fa-sort-alpha-desc"></i>')
                ->addColumn('aksi', static function ($row) use ($parent, $canUpdate, $canDelete): string {
                    $aksi      = '';
                    $judul     = $parent > 0 ? 'Subgallery' : 'gallery';
                    $idEncrypt = encrypt($row->id);
                    if ($canUpdate) {
                        if ($parent == 0) {
                            $aksi .= '<a href="' . ci_route('gallery.index') . '?parent=' . $idEncrypt . '" class="btn bg-purple btn-sm"><i class="fa fa-bars"></i></a> ';
                        }
                        $aksi .= '<a href="' . ci_route('gallery.form', implode('/', [$row->parent->id ?? $parent, $idEncrypt])) . '" class="btn bg-orange btn-sm" title="Ubah ' . $judul . '"><i class="fa fa-edit"></i></a> ';
                        if ($row->isActive()) {
                            $aksi .= '<a href="' . ci_route('gallery.lock', implode('/', [$row->parent->id ?? $parent, $idEncrypt])) . '" class="btn bg-navy btn-sm" title="Non Aktifkan Album"><i class="fa fa-unlock">&nbsp;</i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('gallery.lock', implode('/', [$row->parent->id ?? $parent, $idEncrypt])) . '" class="btn bg-navy btn-sm" title="Aktifkan Album"><i class="fa fa-lock"></i></a> ';
                        }
                        if ($parent == 0) {
                            if ($row->isSlider()) {
                                $aksi .= '<a href="' . ci_route('gallery.slider', implode('/', [$row->parent->id ?? $parent, $idEncrypt])) . '" class="btn bg-gray btn-sm" title="Keluarkan Dari Slider"><i class="fa fa-play">&nbsp;</i></a> ';
                            } else {
                                $aksi .= '<a href="' . ci_route('gallery.slider', implode('/', [$row->parent->id ?? $parent, $idEncrypt])) . '" class="btn bg-gray btn-sm" title="Tampilkan Di Slider"><i class="fa fa-eject"></i></a> ';
                            }
                        }

                    }

                    if ($canDelete) {
                        $aksi .= '<a href="#" data-href="' . ci_route('gallery.delete', implode('/', [$row->parent->id ?? $parent, $idEncrypt])) . '" class="btn bg-maroon btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })->editColumn('nama', static function ($row) {
                    $gambarSedang = ($row->jenis == 1 ? AmbilGaleri($row->gambar ?? '', 'sedang') : $row->gambar);
                    $gambarKecil  = ($row->jenis == 1 ? AmbilGaleri($row->gambar ?? '', 'kecil') : $row->gambar);

                    return '<label style="cursor: pointer;" class="tampil" data-img="' . $gambarSedang . '" data-rel="popover" data-content="<img width=200 height=134 src=' . $gambarKecil . '>" >' . $row->nama . '</label>';
                } )
                ->editColumn('tgl_upload', static fn ($row) => tgl_indo2($row->tgl_upload))
                ->editColumn('enabled', static fn ($row) => $row->enabled ? 'Ya' : 'Tidak')
                ->rawColumns(['drag-handle', 'aksi', 'ceklist', 'nama'])
                ->make();
        }

        return show_404();
    }

    public function form($parent, $id = ''): void
    {
        isCan('u');
        $data['file_path_required'] = true;
        if ($id) {
            $action              = ci_route("gallery.update.{$parent}.{$id}");
            $id                  = decrypt($id);
            $gallery             = Galery::findOrFail($id)->toArray();
            $data['gallery']     = $gallery;
            $data['form_action'] = $action;
            if ($gallery['jenis'] == 1 && $gallery['gambar']) {
                $data['file_path_required'] = false;
            }
        } else {
            $data['gallery']     = null;
            $data['form_action'] = ci_route("gallery.insert.{$parent}");
        }
        view('admin.web.gallery.form', $data);
    }

    public function insert($parent): void
    {
        isCan('u');
        $data = $this->validasi($this->input->post());
        if (! $data) {
            redirect_with('error', $_SESSION['error_msg'], ci_route('gallery.index') . '?parent=' . $parent);
        }
        $rawParent       = decrypt($parent);
        $data['parrent'] = $rawParent;
        $data['enabled'] = 1;
        if ($this->session->grup == 4) {
            $data['enabled'] = 0;
        }
        $data['tipe'] = $rawParent == 0 ? 1 : 2;

        try {
            Galery::create($data);
            redirect_with('success', 'gallery berhasil disimpan', ci_route('gallery.index') . '?parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'gallery gagal disimpan', ci_route('gallery.index') . '?parent=' . $parent);
        }
    }

    public function update($parent, $id): void
    {
        isCan('u');
        $data = $this->validasi($this->input->post());
        if (! $data) {
            redirect_with('error', $_SESSION['error_msg'], ci_route('gallery.index') . '?parent=' . $parent);
        }

        try {
            $id  = decrypt($id);
            $obj = Galery::findOrFail($id);
            // tipe file
            if ($data['jenis'] == 1) {
                if (empty($data['gambar'])) {
                    $data['gambar'] = $obj->gambar;
                }
            }
            $obj->update($data);
            redirect_with('success', 'gallery berhasil disimpan', ci_route('gallery.index') . '?parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'gallery gagal disimpan', ci_route('gallery.index') . '?parent=' . $parent);
        }
    }

    public function delete($parent, $id = null): void
    {
        isCan('h');
        if ($id) {
            $id = decrypt($id);
        }
        if (Galery::whereIn('id', $this->request['id_cb'] ?? [$id] )->whereHas('children')->count()) {
            redirect_with('error', 'gallery tidak dapat dihapus karena masih memiliki subgallery');
        }

        try {
            Galery::destroy($this->request['id_cb'] ?? $id);
            redirect_with('success', 'gallery berhasil dihapus', ci_route('gallery.index') . '?parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'gallery gagal dihapus', ci_route('gallery.index') . '?parent=' . $parent);
        }
    }

    public function lock($parent, $id): void
    {
        isCan('h');

        try {
            $id      = decrypt($id);
            $gallery = Galery::find($id);
            if ($gallery->isSlider() && $gallery->isActive()) {
                redirect_with('error', 'Album tidak bisa dinonaktifkan karena diset sebagai slider', ci_route('gallery.index') . '?parent=' . $parent);
            }
            Galery::gantiStatus($id, 'enabled');
            redirect_with('success', 'Berhasil ubah status', ci_route('gallery.index') . '?parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Gagal ubah status', ci_route('gallery.index') . '?parent=' . $parent);
        }
    }

    public function slider($parent, $id): void
    {
        isCan('h');

        try {
            $id = decrypt($id);
            Galery::gantiStatus($id, 'slider', true);
            Galery::where(['id' => $id])->update(['enabled' => StatusEnum::YA]);
            redirect_with('success', 'Berhasil ubah status', ci_route('gallery.index') . '?parent=' . $parent);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Gagal ubah status', ci_route('gallery.index') . '?parent=' . $parent);
        }
    }

    public function tukar()
    {
        $gallery = $this->input->post('data');
        Galery::setNewOrder($gallery);

        return json(['status' => 1]);
    }

    private function validasi($post)
    {
        $gambar = null;
        if ($post['jenis'] == 2) {
            $gambar = $post['url'];
        } else {
            if (UploadError($_FILES['gambar'])) {
                return false;
            }

            $lokasi_file = $_FILES['gambar']['tmp_name'];
            $tipe_file   = TipeFile($_FILES['gambar']);
            // Bolehkan album tidak ada gambar cover
            if (! empty($lokasi_file)) {
                if (! CekGambar($_FILES['gambar'], $tipe_file)) {
                    return false;
                }
                $nama_file = urldecode(generator(6) . '_' . $_FILES['gambar']['name']);
                $nama_file = strtolower(str_replace(' ', '_', $nama_file));
                UploadGallery($nama_file, '', $tipe_file);
                $gambar = $nama_file;
            }
        }

        return [
            'nama'   => nomor_surat_keputusan($post['nama']),
            'jenis'  => $post['jenis'],
            'gambar' => $gambar,
        ];
    }
}
