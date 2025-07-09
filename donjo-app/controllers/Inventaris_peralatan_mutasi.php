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

use App\Models\InventarisPeralatan;
use App\Models\MutasiInventarisPeralatan;

defined('BASEPATH') || exit('No direct script access allowed');

class Inventaris_peralatan_mutasi extends Admin_Controller
{
    public $modul_ini     = 'sekretariat';
    public $sub_modul_ini = 'inventaris';
    public $akses_modul   = 'inventaris';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $data['tip'] = 2;

        view('admin.inventaris.peralatan.mutasi.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $data = InventarisPeralatan::with('mutasi')->visible()->whereHas('mutasi', static function ($query): void {
                $query->where('visible', 1);
            })->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    $aksi .= '<a href="' . site_url('inventaris_peralatan_mutasi/form/' . $row->id . '/ubah/1') . '" title="Lihat Data" class="btn bg-info btn-sm"><i class="fa fa-eye"></i></a>';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url('inventaris_peralatan_mutasi/form/' . $row->id . '/ubah') . '" title="Edit Data" class="btn bg-orange btn-sm"><i class="fa fa-edit"></i></a>';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . site_url('inventaris_peralatan_mutasi/delete/' . $row->mutasi->id) . '" class="btn bg-maroon btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                    }

                    return $aksi;
                })
                ->editColumn('kode_barang_register', static fn ($row): string => $row->kode_barang . '<br>' . $row->register)
                ->editColumn('tanggal_mutasi', static function ($row) {
                    if ($row->mutasi) {
                        return date('d M Y', strtotime($row->mutasi->tahun_mutasi));
                    }
                })
                ->rawColumns(['aksi', 'kode_barang_register'])
                ->make();
        }

        return show_404();
    }

    public function create($inventaris_id): void
    {
        isCan('u');

        if (MutasiInventarisPeralatan::create($this->validate($this->request, $inventaris_id))) {
            redirect_with('success', 'Berhasil Tambah Data', 'inventaris_peralatan_mutasi');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id): void
    {
        isCan('u');

        if (MutasiInventarisPeralatan::where('id_inventaris_peralatan', $id)->update($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data', 'inventaris_peralatan_mutasi');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function validate($data, $mutasi = null)
    {
        $data = [
            'status_mutasi' => $this->input->post('status_mutasi'),
            'jenis_mutasi'  => $this->input->post('mutasi'),
            'tahun_mutasi'  => date('Y-m-d', strtotime((string) $this->input->post('tahun_mutasi'))),
            'harga_jual'    => $this->input->post('harga_jual') == '' ? null : $this->input->post('harga_jual'),
            'sumbangkan'    => $this->input->post('sumbangkan'),
            'keterangan'    => $this->input->post('keterangan'),
            'visible'       => 1,
        ];

        if ($mutasi) {
            $data['id_inventaris_peralatan'] = $mutasi;
        }

        return $data;
    }

    public function form($id, $action, $view = false): void
    {
        isCan('u');

        if ($action == 'ubah') {
            $data['action']      = $view ? 'Rincian' : 'Ubah';
            $data['form_action'] = ci_route('inventaris_peralatan_mutasi.update', $id);
            $data['view_mark']   = $view ? 1 : 0;
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = ci_route('inventaris_peralatan_mutasi.create', $id);
            $data['view_mark']   = null;
        }

        $data['main']       = InventarisPeralatan::findOrFail($id);
        $data['tip']        = 2;
        $data['controller'] = str_replace_last('_mutasi', '', $this->controller);

        view('admin.inventaris.peralatan.mutasi.form', $data);
    }

    public function delete($id): void
    {
        isCan('h');
        if (MutasiInventarisPeralatan::findOrFail($id)->update(['visible' => 0])) {
            redirect_with('success', 'Berhasil Hapus Data', 'inventaris_peralatan_mutasi');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }
}
