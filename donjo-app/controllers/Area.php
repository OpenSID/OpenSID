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
use App\Models\Area as AreaModel;
use App\Models\Garis;
use App\Models\Lokasi;
use App\Models\Pembangunan;
use App\Models\Polygon;
use App\Models\Wilayah;
use App\Traits\Upload;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Area extends Admin_Controller
{
    use Upload;

    public $modul_ini     = 'pemetaan';
    public $sub_modul_ini = 'pengaturan-peta';
    private int $tip      = 4;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index($parent = 0): void
    {
        $data            = ['tip' => $this->tip, 'parent' => $parent];
        $data['polygon'] = Polygon::root()->with(['children' => static fn ($q) => $q->select(['id', 'parrent', 'nama'])])->get();

        view('admin.peta.area.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status     = $this->input->get('status');
            $subpolygon = $this->input->get('subpolygon') ?? null;
            $polygon    = $this->input->get('polygon') ?? null;
            $parent     = $this->input->get('parent') ?? 0;

            return datatables()->of(AreaModel::status($status)
                ->when($polygon, static fn ($q) => $q->whereIn('ref_polygon', static fn ($q) => $q->select('id')->from('polygon')->whereParrent($polygon)))
                ->when($subpolygon, static fn ($q) => $q->whereRefPolygon($subpolygon))
                ->with([
                    'polygon' => static fn ($q) => $q->select(['id', 'nama', 'parrent'])->with(['parent' => static fn ($r) => $r->select(['id', 'nama', 'parrent'])]),
                ]))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($parent): string {
                    $aksi = '';

                    $aksi .= View::make('admin.layouts.components.buttons.edit', [
                        'url' => 'area/form/' . implode('/', [$row->polygon->parent->id ?? $parent, $row->id]),
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.buttons.btn', [
                        'url'        => ci_route('area.ajax_area_maps', implode('/', [$row->polygon->parent->id ?? $parent, $row->id])),
                        'icon'       => 'fa fa-map',
                        'judul'      => 'Lokasi ' . $row->nama,
                        'type'       => 'bg-olive',
                        'buttonOnly' => true,
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.tombol_aktifkan', [
                        'url'    => ci_route('area.lock', implode('/', [$row->polygon->parent->id ?? $parent, $row->id])),
                        'active' => $row->enabled,
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => ci_route('area.delete', implode('/', [$row->polygon->parent->id ?? $parent, $row->id])),
                        'confirmDelete' => true,
                    ])->render();

                    return $aksi;
                })
                ->editColumn('enabled', static fn ($row): string => $row->enabled == AktifEnum::AKTIF ? 'Ya' : 'Tidak')
                ->editColumn('ref_polygon', static fn ($row) => $row->polygon->parent->nama ?? '')
                ->editColumn('kategori', static fn ($row) => $row->polygon->nama ?? '')
                ->rawColumns(['aksi', 'ceklist'])
                ->make();
        }

        return show_404();
    }

    public function form($parent = 0, $id = '')
    {
        isCan('u');

        $data['area']        = null;
        $data['form_action'] = ci_route('area.insert', $parent);
        $data['foto_area']   = null;
        $data['parent']      = $parent;

        if ($id) {
            $data['area']        = AreaModel::findOrFail($id);
            $data['form_action'] = ci_route('area.update', implode('/', [$parent, $id]));

            // Ambil parent dari ref_polygon saat edit
            if ($data['area']->ref_polygon) {
                $currentPolygon = Polygon::find($data['area']->ref_polygon);
                if ($currentPolygon && $currentPolygon->parrent) {
                    $data['parent'] = $currentPolygon->parrent;
                }
            }
        }

        // Ambil semua data Root/Jenis untuk dropdown pertama
        $data['list_jenis'] = Polygon::root()->get();

        // Ambil data Child/Kategori untuk dropdown kedua
        if ($data['parent'] > 0) {
            $data['list_kategori'] = Polygon::child($data['parent'])->get();
        } else {
            $data['list_kategori'] = collect([]);
        }

        $data['tip'] = $this->tip;

        return view('admin.peta.area.form', $data);
    }

    /**
     * AJAX untuk mengambil kategori berdasarkan jenis yang dipilih
     */
    public function ajax_get_kategori()
    {
        if ($this->input->is_ajax_request()) {
            $jenis_id = $this->input->get('jenis_id');

            if ($jenis_id) {
                $kategori = Polygon::child($jenis_id)->get()->map(static function ($item) {
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

    public function ajax_area_maps($parent, int $id)
    {
        isCan('u');

        $data['area']   = AreaModel::find($id)->toArray();
        $data['parent'] = $parent;

        $data['wil_atas']               = $this->header['desa'];
        $data['dusun_gis']              = Wilayah::dusun()->get()->toArray();
        $data['rw_gis']                 = Wilayah::rw()->get()->toArray();
        $data['rt_gis']                 = Wilayah::rt()->get()->toArray();
        $data['all_lokasi']             = Lokasi::activeLocationMap();
        $data['all_garis']              = Garis::activeGarisMap();
        $data['all_area']               = AreaModel::activeAreaMap();
        $data['all_lokasi_pembangunan'] = Pembangunan::activePembangunanMap();
        $data['form_action']            = ci_route('area.update_maps', implode('/', [$parent, $id]));

        return view('admin.peta.area.maps', $data);
    }

    public function update_maps($parent, $id): void
    {
        isCan('u');

        try {
            $data = $this->input->post();
            if ($data['path'] !== '[[]]') {
                AreaModel::whereId($id)->update($data);
                redirect_with('success', 'Area berhasil disimpan', ci_route('area.index', $parent));
            } else {
                redirect_with('error', 'Titik koordinat area harus diisi', ci_route('area.index', $parent));
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Area gagal disimpan', ci_route('area.index', $parent));
        }
    }

    public function kosongkan($parent, $id): void
    {
        isCan('u');

        try {
            AreaModel::whereId($id)->update(['path' => null]);
            redirect_with('success', 'Peta area berhasil dikosongkan', ci_route('area.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Peta area gagal dikosongkan', ci_route('area.index', $parent));
        }
    }

    public function insert($parent): void
    {
        isCan('u');
        if ($this->validation()) {
            $data = $this->validasi($this->input->post());
        }

        try {
            AreaModel::create($data);
            redirect_with('success', 'Area berhasil disimpan', ci_route('area.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Area gagal disimpan', ci_route('area.index', $parent));
        }
    }

    public function update($parent, $id): void
    {
        isCan('u');

        if ($this->validation()) {
            $data = $this->validasi($this->input->post());
        }

        try {
            $obj = AreaModel::findOrFail($id);
            $obj->update($data);
            redirect_with('success', 'Area berhasil disimpan', ci_route('area.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Area gagal disimpan', ci_route('area.index', $parent));
        }
    }

    public function delete($parent, $id = null): void
    {
        isCan('h');

        try {
            AreaModel::destroy($this->request['id_cb'] ?? $id);
            redirect_with('success', 'Area berhasil dihapus', ci_route('area.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Area gagal dihapus', ci_route('area.index', $parent));
        }
    }

    public function lock($parent, $id)
    {
        isCan('u');

        try {
            $status  = AreaModel::gantiStatus($id, 'enabled');
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
        $this->form_validation->set_rules('ref_polygon', 'Kategori', 'required');
        $this->form_validation->set_rules('desk', 'Keterangan', 'required|trim');
        $this->form_validation->set_rules('enabled', 'Status', 'required');

        return $this->form_validation->run();
    }

    private function validasi(array $post)
    {
        $data['nama']        = nomor_surat_keputusan($post['nama']);
        $data['ref_polygon'] = bilangan($post['ref_polygon']);
        $data['desk']        = htmlentities((string) $post['desk']);
        $data['enabled']     = bilangan($post['enabled']);

        if ($_FILES['foto']['name']) {
            $data['foto'] = $this->uploadGambar('foto', LOKASI_FOTO_AREA);
        } else {
            unset($data['foto']);
        }

        return $data;
    }
}
