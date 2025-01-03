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

use App\Models\Point as ModelsPoint;

defined('BASEPATH') || exit('No direct script access allowed');

class Point extends Admin_Controller
{
    public $modul_ini     = 'pemetaan';
    public $sub_modul_ini = 'pengaturan-peta';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model('plan_point_model');
    }

    public function index()
    {
        $data['status'] = [
            ModelsPoint::LOCK   => 'Aktif',
            ModelsPoint::UNLOCK => 'Tidak Aktif',
        ];

        return view('admin.peta.point.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status   = $this->input->get('status') ?? null;
            $root     = $this->input->get('root') ?? null;
            $subpoint = $this->input->get('subpoint') ?? null;

            return datatables()->of(ModelsPoint::query()
                ->when($root, static fn ($q) => $q->whereTipe(ModelsPoint::ROOT))
                ->when($status, static fn ($q) => $q->whereEnabled($status))
                ->when($subpoint, static fn ($q) => $q->whereTipe(ModelsPoint::CHILD)->whereParrent($subpoint)))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($root, $subpoint): string {
                    $aksi = '';

                    if (can('u')) {
                        if ($root) {
                            $aksi .= '<a href="' . ci_route('point.form', $row->id) . '/' . $subpoint . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('point.ajax_add_sub_point', $subpoint) . '/' . $row->id . '" data-toggle="modal" data-target="#modalBox" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                        }

                        if ($row->enabled == ModelsPoint::LOCK) {
                            $aksi .= '<a href="' . ci_route('point.lock') . '/' . $row->id . '/' . ModelsPoint::UNLOCK . '/' . $subpoint . '" class="btn bg-navy btn-sm" title="Nonaktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('point.lock') . '/' . $row->id . '/' . ModelsPoint::LOCK . '/' . $subpoint . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if ($root) {
                        $aksi .= '<a href="' . ci_route('point.sub_point', $row->id) . '" class="btn bg-purple btn-sm"  title="Rincian ' . $row->nama . '"><i class="fa fa-bars"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('point.delete', $row->id) . '/' . $subpoint . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('enabled', static fn ($row): string => $row->enabled == '1' ? 'Ya' : 'Tidak')
                ->editColumn('path_simbol', static fn ($row): string => '<img src="' . $row->path_simbol . '" />')
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

    private function validasi(array $post, $parent = 0)
    {
        $data['nama']    = nomor_surat_keputusan($post['nama']);
        $data['simbol']  = $post['simbol'];
        $data['parrent'] = $parent;
        $data['tipe']    = $parent ? ModelsPoint::CHILD : ModelsPoint::ROOT;

        return $data;
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
        if (ModelsPoint::destroy($this->request['id_cb'] ?? $id) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data', $subpoint);
        }

        redirect_with('error', 'Gagal Hapus Data', $subpoint);
    }

    public function lock($id = 0, $val = 1, $subpoint = 0): void
    {
        isCan('u');
        $subpoint = $subpoint ? "point/sub_point/{$subpoint}" : null;
        if (ModelsPoint::findOrFail($id)->update(['enabled' => $val])) {
            redirect_with('success', 'Berhasil Ubah Status', $subpoint);
        }
        redirect_with('error', 'Gagal Ubah Status', $subpoint);
    }
}
