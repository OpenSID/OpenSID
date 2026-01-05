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
use App\Models\Garis as GarisModel;
use App\Models\Line;
use App\Models\Lokasi;
use App\Models\Pembangunan;
use App\Models\Wilayah;
use App\Traits\Upload;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Garis extends Admin_Controller
{
    use Upload;

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
        $data         = ['tip' => $this->tip, 'parent' => $parent];
        $data['line'] = Line::root()->with(['children' => static fn ($q) => $q->select(['id', 'parrent', 'nama'])])->get();

        view('admin.peta.garis.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status  = $this->input->get('status');
            $subline = $this->input->get('subline') ?? null;
            $line    = $this->input->get('line') ?? null;
            $parent  = $this->input->get('parent') ?? 0;

            // Tidak filter data invalid, tampilkan semua
            $query = GarisModel::status($status)
                // Filter berdasarkan line (jenis) yang dipilih
                ->when($line, static function ($q) use ($line) {
                    return $q->whereHas('line', static function ($query) use ($line) {
                        $query->where('parrent', $line);
                    });
                })
                // Filter berdasarkan subline (kategori) yang dipilih
                ->when($subline, static fn ($q) => $q->whereRefLine($subline))
                // Eager load dengan validasi
                ->with(['line' => static function ($q) {
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
                    $parentId = ($row->line && $row->line->parrent) ? $row->line->parrent : $parent;

                    $aksi .= View::make('admin.layouts.components.buttons.edit', [
                        'url' => '/garis/form/' . implode('/', [$parentId, $row->id]),
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.buttons.btn', [
                        'url'        => ci_route('garis.ajax_garis_maps', implode('/', [$parentId, $row->id])),
                        'judul'      => 'Lokasi ' . $row->nama,
                        'icon'       => 'fa fa-map',
                        'type'       => 'bg-olive',
                        'buttonOnly' => true,
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.tombol_aktifkan', [
                        'url'    => ci_route('garis.lock', implode('/', [$parentId, $row->id])),
                        'active' => $row->enabled,
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => '/garis/delete/' . implode('/', [$parentId, $row->id]),
                        'confirmDelete' => true,
                    ])->render();

                    return $aksi;
                })
                ->editColumn('enabled', static fn ($row): string => $row->enabled == AktifEnum::AKTIF ? 'Ya' : 'Tidak')
                // KOLOM JENIS - Tampilkan label jika invalid
                ->editColumn('ref_line', static function ($row) {
                    // Validasi parent-child relationship
                    if (! $row->line) {
                        return '<span class="label label-danger" title="Line dengan ID ' . $row->ref_line . ' tidak ditemukan">Data Tidak Valid</span>';
                    }

                    // Line harus CHILD (tipe = 2)
                    if ($row->line->tipe != Line::CHILD) {
                        return '<span class="label label-warning" title="Line adalah ROOT, seharusnya CHILD">Data Tidak Valid</span>';
                    }

                    // Parent harus ada
                    if (! $row->line->parent) {
                        return '<span class="label label-danger" title="Parent dengan ID ' . $row->line->parrent . ' tidak ditemukan">Data Tidak Valid</span>';
                    }

                    // Parent harus ROOT (tipe = 0)
                    if ($row->line->parent->tipe != Line::ROOT) {
                        return '<span class="label label-warning" title="Parent bukan ROOT">Data Tidak Valid</span>';
                    }

                    // Jika valid, tampilkan nama parent (JENIS)
                    return $row->line->parent->nama;
                })
                // KOLOM KATEGORI - Tampilkan label jika invalid
                ->editColumn('kategori', static function ($row) {
                    // Validasi
                    if (! $row->line) {
                        return '<span class="label label-danger" title="Line tidak ditemukan">Data Tidak Valid</span>';
                    }

                    if ($row->line->tipe != Line::CHILD) {
                        return '<span class="label label-warning" title="Line bukan CHILD">Data Tidak Valid</span>';
                    }

                    // Jika valid, tampilkan nama line (KATEGORI)
                    return $row->line->nama;
                })
                ->rawColumns(['aksi', 'ceklist', 'ref_line', 'kategori'])
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
            $data['garis']       = GarisModel::findOrFail($id);
            $data['form_action'] = ci_route('garis.update', implode('/', [$parent, $id]));

            // Ambil parent dari ref_line saat edit
            if ($data['garis']->ref_line) {
                $currentLine = Line::find($data['garis']->ref_line);
                if ($currentLine && $currentLine->parrent) {
                    $data['parent'] = $currentLine->parrent;
                }
            }
        }

        // Ambil semua data Root/Jenis untuk dropdown pertama
        $data['list_jenis'] = Line::root()->get();

        // Ambil data Child/Kategori untuk dropdown kedua
        if ($data['parent'] > 0) {
            $data['list_kategori'] = Line::child($data['parent'])->get();
        } else {
            $data['list_kategori'] = collect([]);
        }

        $data['tip'] = $this->tip;

        return view('admin.peta.garis.form', $data);
    }

    /**
     * AJAX untuk mengambil kategori berdasarkan jenis yang dipilih
     */
    public function ajax_get_kategori()
    {
        if ($this->input->is_ajax_request()) {
            $jenis_id = $this->input->get('jenis_id');

            if ($jenis_id) {
                $kategori = Line::child($jenis_id)->get()->map(static function ($item) {
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

    public function lock($parent, $id)
    {
        isCan('u');

        try {
            $status  = GarisModel::gantiStatus($id, 'enabled');
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

        if ($_FILES['foto']['name']) {
            $data['foto'] = $this->uploadGambar('foto', LOKASI_FOTO_GARIS);
        }

        return $data;
    }
}
