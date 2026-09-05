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
use App\Models\Point as ModelsPoint;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Point extends Admin_Controller
{
    public $modul_ini     = 'pemetaan';
    public $sub_modul_ini = 'pengaturan-peta';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        return view('admin.peta.point.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status   = $this->input->get('status');
            $root     = $this->input->get('root') ?? null;
            $subpoint = $this->input->get('subpoint') ?? null;

            return datatables()->of(
                ModelsPoint::when(
                    $subpoint,
                    static fn ($q) => $q->whereTipe(ModelsPoint::CHILD)->whereParrent($subpoint),
                    static fn ($q) => $q->status($status)
                        ->when($root, static fn ($qq) => $qq->whereTipe(ModelsPoint::ROOT))
                )
            )
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        if ($row->sumber != 'OpenKab' && $row->config_id != null) {
                            return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                        }
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($root, $subpoint): string {
                    $aksi = '';

                    if ($root) {
                        $aksi .= '<a href="' . ci_route('point.sub_point', $row->id) . '" class="btn bg-purple btn-sm"  title="Rincian ' . $row->nama . '"><i class="fa fa-bars"></i></a> ';
                    }

                    if ($row->sumber != 'OpenKab' && $row->config_id != null) {

                        if (can('u')) {
                            if ($root) {
                                $aksi .= '<a href="' . ci_route('point.form', $row->id) . '/' . $subpoint . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                            } else {
                                $aksi .= '<a href="' . ci_route('point.ajax_add_sub_point', $subpoint) . '/' . $row->id . '" data-toggle="modal" data-target="#modalBox" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                            }
                        }

                        $aksi .= View::make('admin.layouts.components.tombol_aktifkan', [
                            'url'    => ci_route('point.lock') . '/' . $row->id . '/' . $subpoint,
                            'active' => $row->enabled,
                        ])->render();

                        if (can('h')) {
                            $aksi .= '<a href="#" data-href="' . ci_route('point.delete', $row->id) . '/' . $subpoint . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                        }
                    }

                    return $aksi;
                })
                ->editColumn('enabled', static fn ($row): string => $row->enabled == AktifEnum::AKTIF ? 'Ya' : 'Tidak')
                ->editColumn('path_simbol', static fn ($row): string => '<img src="' . base_url() . $row->path_simbol . '" />')
                ->rawColumns(['ceklist', 'aksi', 'simbol', 'path_simbol'])
                ->make();
        }

        return show_404();
    }

    public function form($id = '', $subpoint = 0)
    {
        isCan('u');

        if ($id) {
            $data['point']       = ModelsPoint::findOrFail($id);
            $data['form_action'] = ci_route('point.update', $id) . '/' . $subpoint;
            $data['aksi']        = 'Ubah';
            if ($data['point']->sumber == 'OpenKab' && $data['point']->config_id == null) {
                redirect_with('error', 'Anda tidak memiliki akses untuk halaman tersebut!');
            }
        } else {
            $data['point']       = null;
            $data['aksi']        = 'Tambah';
            $data['form_action'] = ci_route('point.insert');
        }

        $data['simbol'] = gis_simbols();
        $data['tip']    = 0;

        return view('admin.peta.point.form', $data);
    }

    public function sub_point($point = 1)
    {
        $data['subpoint'] = ModelsPoint::child($point)->get()->toArray();
        $data['point']    = ModelsPoint::findOrFail($point);
        $data['tip']      = 0;

        return view('admin.peta.point.subpoint', $data);
    }

    public function ajax_add_sub_point($point = 0, $id = 0)
    {
        $cekpoint = ModelsPoint::findOrFail($point);
        if ($cekpoint->sumber == 'OpenKab' && $cekpoint->config_id == null) {
            redirect_with('error', 'Anda tidak memiliki akses untuk halaman tersebut!');
        }

        if ($id) {
            $data['point']       = ModelsPoint::findOrFail($id);
            $data['form_action'] = ci_route('point.update', $id) . '/' . $point;
        } else {
            $data['point']       = null;
            $data['form_action'] = ci_route('point.insert', $point);
        }

        $data['simbol'] = gis_simbols();

        return view('admin.peta.point.subpoint_form', $data);
    }

    public function insert($subpoint = 0): void
    {
        isCan('u');
        $data  = $this->input->post();
        $url   = $subpoint ? "point/sub_point/{$subpoint}" : null;
        $label = $subpoint ? 'Kategori' : 'Tipe';

        try {
            ModelsPoint::create($this->validasi($data, $subpoint));
            redirect_with('success', $label . ' Lokasi berhasil disimpan', $url);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', $label . ' Lokasi disimpan', $url);
        }
    }

    public function update($id = '', $subpoint = 0): void
    {
        isCan('u');
        $data  = $this->input->post();
        $url   = $subpoint ? "point/sub_point/{$subpoint}" : null;
        $label = $subpoint ? 'Kategori' : 'Tipe';

        try {
            ModelsPoint::findOrFail($id)->update($this->validasi($data, $subpoint));
            redirect_with('success', $label . ' Lokasi berhasil diubah', $url);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', $label . ' Lokasi diubah', $subpoint);
        }
    }

    public function delete($id = '', $subpoint = 0): void
    {
        isCan('h');

        $subpoint = $subpoint ? "point/sub_point/{$subpoint}" : null;

        if ($this->hasChild($id ?? $this->request['id_cb'])) {
            redirect_with('error', __('notification.deleted.error') . '. Silakan hapus subdata terlebih dahulu.', $subpoint);
        }

        if ($id) {
            $point = ModelsPoint::findOrFail($id);
            if ($point->sumber == 'OpenKab' && $point->config_id == null) {
                redirect_with('error', 'Anda tidak memiliki akses untuk halaman tersebut!');
            }
        }

        if (ModelsPoint::destroy($this->request['id_cb'] ?? $id) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data', $subpoint);
        }

        redirect_with('error', 'Gagal Hapus Data', $subpoint);
    }

    public function lock($id = 0, $subpoint = 0)
    {
        isCan('u');

        try {
            $subpoint = $subpoint ? "point/sub_point/{$subpoint}" : null;
            $point    = ModelsPoint::findOrFail($id);
            if ($point->sumber == 'OpenKab' && $point->config_id == null) {
                return json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk halaman tersebut!',
                ]);
            }

            $status  = $point->gantiStatus($id, 'enabled');
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

    private function validasi(array $post, $parent = 0)
    {
        $data['nama']    = nomor_surat_keputusan($post['nama']);
        $data['simbol']  = $post['simbol'];
        $data['parrent'] = $parent;
        $data['tipe']    = $parent ? ModelsPoint::CHILD : ModelsPoint::ROOT;
        $data['enabled'] = $post['enabled'] ?? AktifEnum::TIDAK_AKTIF;

        return $data;
    }

    private function hasChild($id): bool
    {
        if (is_array($id)) {
            return ModelsPoint::whereIn('parrent', $id)->exists();
        }

        return ModelsPoint::where('parrent', $id)->exists();
    }
}
