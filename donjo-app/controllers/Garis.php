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
use App\Models\Garis as GarisModel;
use App\Models\Line;
use App\Models\Lokasi;
use App\Models\Pembangunan;
use App\Models\Wilayah;

defined('BASEPATH') || exit('No direct script access allowed');

class Garis extends Admin_Controller
{
    public $modul_ini     = 'pemetaan';
    public $sub_modul_ini = 'pengaturan-peta';
    private int $tip      = 1;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index($parent = 0): void
    {
        $data           = ['tip' => $this->tip, 'parent' => $parent];
        $data['status'] = [Line::LOCK => 'Aktif', Line::UNLOCK => 'Tidak Aktif'];
        $data['line']   = Line::root()->with(['children' => static fn ($q) => $q->select(['id', 'parrent', 'nama'])])->get();

        view('admin.peta.garis.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status  = $this->input->get('status') ?? null;
            $subline = $this->input->get('subline') ?? null;
            $line    = $this->input->get('line') ?? null;
            $parent  = $this->input->get('parent') ?? 0;

            return datatables()->of(GarisModel::when($status, static fn ($q) => $q->whereEnabled($status))
                ->when($line, static fn ($q) => $q->whereIn('ref_line', static fn ($q) => $q->select('id')->from('line')->whereParrent($line)))
                ->when($subline, static fn ($q) => $q->whereRefLine($subline))
                ->with([
                    'line' => static fn ($q) => $q->select(['id', 'nama', 'parrent'])->with(['parent' => static fn ($r) => $r->select(['id', 'nama', 'parrent'])]),
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
                        $aksi .= '<a href="' . ci_route('garis.form', implode('/', [$row->line->parent->id ?? $parent, $row->id])) . '" class="btn btn-warning btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a> ';
                    }
                    $aksi .= '<a href="' . ci_route('garis.ajax_garis_maps', implode('/', [$row->line->parent->id ?? $parent, $row->id])) . '" class="btn bg-olive btn-sm" title="Lokasi ' . $row->nama . '"><i class="fa fa-map"></i></a> ';
                    if (can('u')) {
                        if ($row->isLock()) {
                            $aksi .= '<a href="' . ci_route('garis.unlock', implode('/', [$row->line->parent->id ?? $parent, $row->id])) . '" class="btn bg-navy btn-sm" title="Non Aktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('garis.lock', implode('/', [$row->line->parent->id ?? $parent, $row->id])) . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a> ';
                        }
                    }
                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('garis.delete', implode('/', [$row->line->parent->id ?? $parent, $row->id])) . '" class="btn bg-maroon btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('enabled', static fn ($row): string => $row->enabled == '1' ? 'Ya' : 'Tidak')
                ->editColumn('ref_line', static fn ($row) => $row->line->parent->nama ?? '')
                ->editColumn('kategori', static fn ($row) => $row->line->nama ?? '')
                ->rawColumns(['aksi', 'ceklist'])
                ->make();
        }

        return show_404();
    }

    public function form($parent = 0, $id = '')
    {
        isCan('u');
        $data['garis']       = null;
        $data['form_action'] = ci_route('garis.insert', $parent);
        $data['foto_garis']  = null;
        $data['parent']      = $parent;

        if ($id) {
            $data['garis']       = GarisModel::find($id);
            $data['form_action'] = ci_route('garis.update', implode('/', [$parent, $id]));
        }

        $data['list_line'] = empty($parent) ? Line::root()->with(['children' => static fn ($q) => $q->select(['id', 'parrent', 'nama'])])->get() : Line::child($parent)->whereHas('parent')->get();
        $data['tip']       = $this->tip;

        return view('admin.peta.garis.form', $data);
    }

    public function ajax_garis_maps($parent, int $id)
    {
        $data['garis'] = GarisModel::with(['line'])->find($id)->toArray();

        $data['parent'] = $parent;

        $data['wil_atas']               = $this->header['desa'];
        $data['dusun_gis']              = Wilayah::dusun()->get()->toArray();
        $data['rw_gis']                 = Wilayah::rw()->get()->toArray();
        $data['rt_gis']                 = Wilayah::rt()->get()->toArray();
        $data['all_lokasi']             = Lokasi::activeLocationMap();
        $data['all_garis']              = GarisModel::activeGarisMap();
        $data['all_area']               = Area::activeAreaMap();
        $data['all_lokasi_pembangunan'] = Pembangunan::activePembangunanMap();
        $data['form_action']            = ci_route('garis.update_maps', implode('/', [$parent, $id]));

        return view('admin.peta.garis.maps', $data);
    }

    public function update_maps($parent, $id): void
    {
        isCan('u');

        try {
            $data = $this->input->post();
            if ($data['path'] !== '[[]]') {
                GarisModel::whereId($id)->update($data);
                redirect_with('success', 'Pengaturan garis berhasil disimpan', ci_route('garis.index', $parent));
            } else {
                redirect_with('error', 'Titik koordinat garis harus diisi', ci_route('garis.index', $parent));
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Pengaturan garis gagal disimpan', ci_route('garis.index', $parent));
        }
    }

    public function kosongkan($parent, $id): void
    {
        isCan('u');

        try {
            GarisModel::whereId($id)->update(['path' => null]);
            redirect_with('success', 'Pengaturan garis berhasil dikosongkan', ci_route('garis.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Pengaturan garis gagal dikosongkan', ci_route('garis.index', $parent));
        }
    }

    public function insert($parent): void
    {
        isCan('u');
        if ($this->validation()) {
            $data = $this->validasi($this->input->post());
        }

        try {
            GarisModel::create($data);
            redirect_with('success', 'Pengaturan garis berhasil disimpan', ci_route('garis.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Pengaturan garis gagal disimpan', ci_route('garis.index', $parent));
        }
    }

    public function update($parent, $id): void
    {
        isCan('u');

        if ($this->validation()) {
            $data = $this->validasi($this->input->post());
        }

        try {
            $obj = GarisModel::findOrFail($id);
            $obj->update($data);
            redirect_with('success', 'Pengaturan garis berhasil disimpan', ci_route('garis.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Pengaturan garis gagal disimpan', ci_route('garis.index', $parent));
        }
    }

    public function delete($parent, $id = null): void
    {
        isCan('h');

        try {
            GarisModel::destroy($this->request['id_cb'] ?? $id);
            redirect_with('success', 'Pengaturan garis berhasil dihapus', ci_route('garis.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Pengaturan garis gagal dihapus', ci_route('garis.index', $parent));
        }
    }

    public function lock($parent, $id): void
    {
        isCan('h');

        try {
            GarisModel::where(['id' => $id])->update(['enabled' => GarisModel::LOCK]);
            redirect_with('success', 'Pengaturan garis berhasil dinonaktifkan', ci_route('garis.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Pengaturan garis gagal dinonaktifkan', ci_route('garis.index', $parent));
        }
    }

    public function unlock($parent, $id): void
    {
        isCan('h');

        try {
            GarisModel::where(['id' => $id])->update(['enabled' => GarisModel::UNLOCK]);
            redirect_with('success', 'Pengaturan garis berhasil dinonaktifkan', ci_route('garis.index', $parent));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Pengaturan garis gagal dinonaktifkan', ci_route('garis.index', $parent));
        }
    }

    private function validation()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('ref_line', 'Kategori', 'required');
        $this->form_validation->set_rules('desk', 'Keterangan', 'required|trim');
        $this->form_validation->set_rules('enabled', 'Status', 'required');

        return $this->form_validation->run();
    }

    private function validasi(array $post)
    {
        $data['nama']     = nomor_surat_keputusan($post['nama']);
        $data['ref_line'] = bilangan($post['ref_line']);
        $data['desk']     = htmlentities((string) $post['desk']);
        $data['enabled']  = bilangan($post['enabled']);

        $garis_file = $_FILES['foto']['tmp_name'];
        $nama_file  = $_FILES['foto']['name'];
        $nama_file  = time() . '-' . str_replace(' ', '-', $nama_file);      // normalkan nama file
        if (! empty($garis_file)) {
            $nama_file    = (new Checker(get_app_key(), $nama_file))->encrypt();
            $data['foto'] = UploadPeta($nama_file, LOKASI_FOTO_GARIS);
        } else {
            unset($data['foto']);
        }

        return $data;
    }
}
