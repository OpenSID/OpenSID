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

use App\Libraries\Checker;
use App\Models\Area;
use App\Models\Garis;
use App\Models\Lokasi;
use App\Models\Pembangunan;
use App\Models\Point;
use App\Models\Wilayah;

defined('BASEPATH') || exit('No direct script access allowed');

class Plan extends Admin_Controller
{
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
        $data           = ['tip' => $this->tip, 'parent' => $parent];
        $data['status'] = [Point::LOCK => 'Aktif', Point::UNLOCK => 'Tidak Aktif'];
        $data['point']  = Point::root()->with(['children' => static fn ($q) => $q->select(['id', 'parrent', 'nama'])])->get();

        view('admin.peta.lokasi.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status   = $this->input->get('status') ?? null;
            $subpoint = $this->input->get('subpoint') ?? null;
            $point    = $this->input->get('point') ?? null;
            $parent   = $this->input->get('parent') ?? 0;

            return datatables()->of(Lokasi::when($status, static fn ($q) => $q->whereEnabled($status))
                ->when($point, static fn ($q) => $q->whereIn('ref_point', static fn ($q) => $q->select('id')->from('point')->whereParrent($point)))
                ->when($subpoint, static fn ($q) => $q->whereRefPoint($subpoint))
                ->with(['point' => static fn ($q) => $q->select(['id', 'nama', 'parrent'])->with(['parent' => static fn ($r) => $r->select(['id', 'nama', 'parrent'])]),
                ]))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($parent): string {
                    $aksi = '';
                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('plan.form', implode('/', [$row->point->parent->id ?? $parent, $row->id])) . '" class="btn btn-warning btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a> ';
                        $aksi .= '<a href="' . ci_route('plan.ajax_lokasi_maps', implode('/', [$row->point->parent->id ?? $parent, $row->id])) . '" class="btn bg-olive btn-sm" title="Lokasi ' . $row->nama . '"><i class="fa fa-map"></i></a> ';
                        if ($row->isLock()) {
                            $aksi .= '<a href="' . ci_route('plan.unlock', implode('/', [$row->point->parent->id ?? $parent, $row->id])) . '" class="btn bg-navy btn-sm" title="Non Aktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('plan.lock', implode('/', [$row->point->parent->id ?? $parent, $row->id])) . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('plan.delete', implode('/', [$row->point->parent->id ?? $parent, $row->id])) . '" class="btn bg-maroon btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('enabled', static fn ($row): string => $row->enabled == '1' ? 'Ya' : 'Tidak')
                ->editColumn('ref_point', static fn ($row) => $row->point->parent->nama ?? '')
                ->editColumn('kategori', static fn ($row) => $row->point->nama ?? '')
                ->rawColumns(['aksi', 'ceklist'])
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
        }

        $data['list_point'] = empty($parent) ? Point::root()->get() : Point::child($parent)->whereHas('parent')->get();
        $data['tip']        = $this->tip;

        return view('admin.peta.lokasi.form', $data);
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

    public function lock($parent, $id): void
    {
        isCan('h');

        try {
            Lokasi::where(['id' => $id])->update(['enabled' => Lokasi::LOCK]);
            redirect_with('success', 'Lokasi berhasil diaktifkan', ci_route('plan.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Lokasi gagal diaktifkan', ci_route('plan.index', $parent));
        }
    }

    public function unlock($parent, $id): void
    {
        isCan('u');

        try {
            Lokasi::where(['id' => $id])->update(['enabled' => Lokasi::UNLOCK]);
            redirect_with('success', 'Lokasi berhasil dinonaktifkan', ci_route('plan.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Lokasi gagal dinonaktifkan', ci_route('plan.index', $parent));
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

        $lokasi_file = $_FILES['foto']['tmp_name'];
        $nama_file   = $_FILES['foto']['name'];
        $nama_file   = time() . '-' . str_replace(' ', '-', $nama_file);      // normalkan nama file
        if (! empty($lokasi_file)) {
            $nama_file    = (new Checker(get_app_key(), $nama_file))->encrypt();
            $data['foto'] = UploadPeta($nama_file, LOKASI_FOTO_LOKASI);
        } else {
            unset($data['foto']);
        }

        return $data;
    }
}
