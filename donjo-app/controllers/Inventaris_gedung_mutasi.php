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

use App\Models\InventarisGedung;
use App\Models\MutasiInventarisGedung;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Inventaris_gedung_mutasi extends Admin_Controller
{
    public $modul_ini     = 'sekretariat';
    public $sub_modul_ini = 'inventaris';
    public $akses_modul   = 'inventaris-gedung';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $data['tip'] = 2;

        view('admin.inventaris.gedung.mutasi.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $data = MutasiInventarisGedung::query()->select('mutasi_inventaris_gedung.*')->with('inventaris');

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    $aksi .= View::make('admin.layouts.components.buttons.lihat', [
                        'url'   => site_url('inventaris_gedung_mutasi/form/' . $row->id . '/ubah/1'),
                        'judul' => 'Lihat Data',
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.buttons.edit', [
                        'url' => "inventaris_gedung_mutasi/form/{$row->id}/ubah",
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => site_url('inventaris_gedung_mutasi/delete/' . $row->id),
                        'confirmDelete' => true,
                    ])->render();

                    return $aksi;
                })
                ->editColumn('kode_barang_register', static fn ($row): string => $row->inventaris->kode_barang . '<br>' . $row->inventaris->register)
                ->editColumn('tahun_pengadaan', static fn ($row): string => date('Y', strtotime($row->inventaris->tanggal_dokument)))
                ->editColumn('tanggal_mutasi', static fn ($row) => date('d M Y', strtotime($row->tahun_mutasi)))
                ->rawColumns(['aksi', 'kode_barang_register'])
                ->make();
        }

        return show_404();
    }

    public function create($inventaris_id): void
    {
        isCan('u');

        if (MutasiInventarisGedung::create($this->validate($this->request, $inventaris_id))) {
            redirect_with('success', 'Berhasil Tambah Data', 'inventaris_gedung_mutasi');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id): void
    {
        isCan('u');

        $mutasi = MutasiInventarisGedung::findOrFail($id);

        if ($mutasi->update($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data', 'inventaris_gedung_mutasi');
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
            $data['id_inventaris_gedung'] = $mutasi;
        }

        return $data;
    }

    public function form($id, $action, $view = false): void
    {
        isCan('u');

        if ($action == 'ubah') {
            $data['action']      = $view ? 'Rincian' : 'Ubah';
            $data['form_action'] = ci_route('inventaris_gedung_mutasi.update', $id);
            $data['view_mark']   = $view ? 1 : 0;
            $data['main']        = MutasiInventarisGedung::with('inventaris')->find($id) ?? show_404();
        } else {
            $data['action']           = 'Tambah';
            $data['form_action']      = ci_route('inventaris_gedung_mutasi.create', $id);
            $data['view_mark']        = null;
            $data['main']             = new MutasiInventarisGedung();
            $data['main']->inventaris = InventarisGedung::find($id) ?? show_404();
        }

        $data['tip']        = 2;
        $data['controller'] = str_replace_last('_mutasi', '', $this->controller);

        view('admin.inventaris.gedung.mutasi.form', $data);
    }

    public function delete($id): void
    {
        isCan('h');
        if (MutasiInventarisGedung::findOrFail($id)->delete()) {
            redirect_with('success', 'Berhasil Hapus Data', 'inventaris_gedung_mutasi');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }
}
