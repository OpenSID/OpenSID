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

use App\Enums\AktifEnum;
use App\Models\Area;
use App\Models\Garis;
use App\Models\Lokasi;
use App\Models\Pembangunan;
use App\Models\Point;
use App\Models\Wilayah;
use App\Traits\Upload;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Plan extends Admin_Controller
{
    use Upload;

    public $modul_ini       = 'pemetaan';
    public $sub_modul_ini   = 'pengaturan-peta';
    public $aliasController = 'plan';
    private int $tip        = 3;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index($parent = 0): void
    {
        $data          = ['tip' => $this->tip, 'parent' => $parent];
        $data['point'] = Point::root()->with(['children' => static fn ($q) => $q->select(['id', 'parrent', 'nama'])])->get();

        view('admin.peta.lokasi.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status   = $this->input->get('status') ?? null;
            $subpoint = $this->input->get('subpoint') ?? null;
            $point    = $this->input->get('point') ?? null;
            $parent   = $this->input->get('parent') ?? 0;

            // Tidak filter data invalid, tampilkan semua
            $query = Lokasi::status($status)
                // Filter berdasarkan point (jenis) yang dipilih
                ->when($point, static function ($q) use ($point) {
                    return $q->whereHas('point', static function ($query) use ($point) {
                        $query->where('parrent', $point);
                    });
                })
                // Filter berdasarkan subpoint (kategori) yang dipilih
                ->when($subpoint, static fn ($q) => $q->whereRefPoint($subpoint))
                // Eager load dengan validasi
                ->with(['point' => static function ($q) {
                    $q->select(['id', 'nama', 'parrent', 'tipe'])
                        ->with(['parent' => static function ($r) {
                            $r->select(['id', 'nama', 'tipe']);
                        }]);
                }]);

            return datatables()->of($query)
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($parent): string {
                    $aksi = '';

                    // Ambil parent_id untuk URL
                    // Gunakan parent dari point jika ada, kalau tidak gunakan parent parameter
                    $parentId = ($row->point && $row->point->parrent) ? $row->point->parrent : $parent;

                    // Tombol edit - selalu tampil
                    $aksi .= View::make('admin.layouts.components.buttons.edit', [
                        'url' => 'plan/form/' . implode('/', [$parentId, $row->id]),
                    ])->render();

                    if (can('u')) {
                        $aksi .= View::make('admin.layouts.components.buttons.btn', [
                            'url' => ci_route(
                                'plan.ajax_lokasi_maps',
                                implode('/', [$parentId, $row->id])
                            ),
                            'icon'       => 'fa fa-map',
                            'judul'      => 'Lokasi ' . $row->nama,
                            'type'       => 'bg-olive',
                            'buttonOnly' => true,
                        ])->render();
                    }

                    $aksi .= View::make('admin.layouts.components.tombol_aktifkan', [
                        'url'    => ci_route('plan.lock', implode('/', [$parentId, $row->id])),
                        'active' => $row->enabled,
                    ])->render();

                    // Tombol hapus - selalu tampil
                    $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                        'url' => ci_route(
                            'plan.delete',
                            implode('/', [$parentId, $row->id])
                        ),
                        'confirmDelete' => true,
                    ])->render();

                    return $aksi;
                })
                ->editColumn('enabled', static fn ($row): string => $row->enabled == AktifEnum::AKTIF ? 'Ya' : 'Tidak')
                // KOLOM JENIS - Tampilkan label jika invalid
                ->editColumn('ref_point', static function ($row) {
                    // Validasi parent-child relationship
                    if (! $row->point) {
                        return '<span class="label label-danger" title="Point dengan ID ' . $row->ref_point . ' tidak ditemukan">Data Tidak Valid</span>';
                    }

                    // Point harus CHILD (tipe = 2)
                    if ($row->point->tipe != Point::CHILD) {
                        return '<span class="label label-warning" title="Point adalah ROOT, seharusnya CHILD">Data Tidak Valid</span>';
                    }

                    // Parent harus ada
                    if (! $row->point->parent) {
                        return '<span class="label label-danger" title="Parent dengan ID ' . $row->point->parrent . ' tidak ditemukan">Data Tidak Valid</span>';
                    }

                    // Parent harus ROOT (tipe = 0)
                    if ($row->point->parent->tipe != Point::ROOT) {
                        return '<span class="label label-warning" title="Parent bukan ROOT">Data Tidak Valid</span>';
                    }

                    // Jika valid, tampilkan nama parent (JENIS)
                    return $row->point->parent->nama;
                })
                // KOLOM KATEGORI - Tampilkan label jika invalid
                ->editColumn('kategori', static function ($row) {
                    // Validasi
                    if (! $row->point) {
                        return '<span class="label label-danger" title="Point tidak ditemukan">Data Tidak Valid</span>';
                    }

                    if ($row->point->tipe != Point::CHILD) {
                        return '<span class="label label-warning" title="Point bukan CHILD">Data Tidak Valid</span>';
                    }

                    // Jika valid, tampilkan nama point (KATEGORI)
                    return $row->point->nama;
                })
                ->rawColumns(['aksi', 'ceklist', 'ref_point', 'kategori'])
                ->make();
        }

        return show_404();
    }

    public function form($parent = 0, $id = '')
    {
        isCan('u');

        $data['plan']        = null;
        $data['form_action'] = ci_route('plan.insert', $parent);
        $data['foto_plan']   = null;
        $data['parent']      = $parent;

        if ($id) {
            $data['plan']        = Lokasi::findOrFail($id);
            $data['form_action'] = ci_route('plan.update', implode('/', [$parent, $id]));

            // Ambil parent dari ref_point saat edit
            if ($data['plan']->ref_point) {
                $currentPoint = Point::find($data['plan']->ref_point);
                if ($currentPoint && $currentPoint->parrent) {
                    $data['parent'] = $currentPoint->parrent;
                }
            }
        }

        // Ambil semua data Root/Jenis untuk dropdown pertama
        $data['list_jenis'] = Point::root()->get();

        // Ambil data Child/Kategori untuk dropdown kedua
        // Jika ada parent, ambil child-nya
        if ($data['parent'] > 0) {
            $data['list_kategori'] = Point::child($data['parent'])->get();
        } else {
            $data['list_kategori'] = collect([]); // kosong jika belum pilih jenis
        }

        $data['tip'] = $this->tip;

        return view('admin.peta.lokasi.form', $data);
    }

    /**
     * AJAX untuk mengambil kategori berdasarkan jenis yang dipilih
     */
    public function ajax_get_kategori()
    {
        if ($this->input->is_ajax_request()) {
            $jenis_id = $this->input->get('jenis_id');

            if ($jenis_id) {
                $kategori = Point::child($jenis_id)->get()->map(static function ($item) {
                    return [
                        'id'   => $item->id,
                        'nama' => $item->nama,
                    ];
                });

                return json([
                    'success' => true,
                    'data'    => $kategori,
                ]);
            }

            return json([
                'success' => false,
                'data'    => [],
            ]);
        }

        return show_404();
    }

    public function ajax_lokasi_maps($parent, int $id)
    {
        isCan('u');

        $data['lokasi'] = Lokasi::findOrFail($id)->toArray();
        $data['parent'] = $parent;

        $data['wil_atas']               = $this->header['desa'];
        $data['dusun_gis']              = Wilayah::dusun()->get()->toArray();
        $data['rw_gis']                 = Wilayah::rw()->get()->toArray();
        $data['rt_gis']                 = Wilayah::rt()->get()->toArray();
        $data['all_lokasi']             = Lokasi::activeLocationMap();
        $data['all_garis']              = Garis::activeGarisMap();
        $data['all_area']               = Area::activeAreaMap();
        $data['all_lokasi_pembangunan'] = Pembangunan::activePembangunanMap();
        $data['form_action']            = ci_route('plan.update_maps', implode('/', [$parent, $id]));

        return view('admin.peta.lokasi.maps', $data);
    }

    public function update_maps($parent, $id): void
    {
        isCan('u');

        try {
            $data = $this->input->post();
            if (! empty($data['lat']) && ! empty($data['lng'])) {
                Lokasi::whereId($id)->update($data);
                redirect_with('success', 'Lokasi berhasil disimpan', ci_route('plan.index', $parent));
            } else {
                redirect_with('error', 'Titik koordinat lokasi harus diisi', ci_route('plan.index', $parent));
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Lokasi gagal disimpan', ci_route('plan.index', $parent));
        }
    }

    public function insert($parent): void
    {
        isCan('u');

        if ($this->validation()) {
            $data = $this->validasi($this->input->post());
        }

        try {
            Lokasi::create($data);
            redirect_with('success', 'Lokasi berhasil disimpan', ci_route('plan.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Lokasi gagal disimpan', ci_route('plan.index', $parent));
        }
    }

    public function update($parent, $id): void
    {
        isCan('u');

        if ($this->validation()) {
            $data = $this->validasi($this->input->post());
        }

        try {
            $obj = Lokasi::findOrFail($id);
            $obj->update($data);
            redirect_with('success', 'Lokasi berhasil disimpan', ci_route('plan.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Lokasi gagal disimpan', ci_route('plan.index', $parent));
        }
    }

    public function delete($parent, $id = null): void
    {
        isCan('h');

        try {
            Lokasi::destroy($this->request['id_cb'] ?? $id);
            redirect_with('success', 'Lokasi berhasil dihapus', ci_route('plan.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Lokasi gagal dihapus', ci_route('plan.index', $parent));
        }
    }

    public function lock($parent, $id)
    {
        isCan('u');

        try {
            $status  = Lokasi::gantiStatus($id, 'enabled');
            $success = (bool) $status;

            return json([
                'success' => $success,
                'message' => $success ? __('notification.status.success') : __('notification.status.error'),
            ]);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            return json([
                'success' => false,
                'message' => __('notification.status.error'),
            ]);
        }
    }

    private function validation()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('ref_point', 'Kategori', 'required');
        $this->form_validation->set_rules('desk', 'Keterangan', 'required|trim');
        $this->form_validation->set_rules('enabled', 'Status', 'required');

        return $this->form_validation->run();
    }

    private function validasi(array $post)
    {
        $data['nama']      = nomor_surat_keputusan($post['nama']);
        $data['ref_point'] = bilangan($post['ref_point']);
        $data['desk']      = htmlentities((string) $post['desk']);
        $data['enabled']   = bilangan($post['enabled']);

        if ($_FILES['foto']['name']) {
            $data['foto'] = $this->uploadPicture('foto', LOKASI_FOTO_LOKASI);
        }

        return $data;
    }
}
