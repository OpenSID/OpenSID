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

use App\Enums\InventarisSubMenuEnum;
use App\Models\Aset;
use App\Models\InventarisAsset;
use App\Models\MutasiInventarisAsset;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Inventaris_asset extends Admin_Controller
{
    public $modul_ini     = 'sekretariat';
    public $sub_modul_ini = 'inventaris';
    public $akses_modul   = 'inventaris-asset';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $data['tip']    = 1;
        $data['action'] = 'Daftar';
        $data['header'] = InventarisSubMenuEnum::ASET['header'];

        view('admin.inventaris.asset.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $data = InventarisAsset::query()->with('mutasi');

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u') && ! $row->mutasi) {
                        $aksi .= View::make('admin.layouts.components.buttons.btn', [
                            'url'        => ci_route('inventaris_asset_mutasi.form/') . $row->id,
                            'judul'      => 'Mutasi Data',
                            'icon'       => 'fa fa-external-link-square',
                            'type'       => 'bg-olive',
                            'buttonOnly' => true,
                        ])->render();
                    }

                    $aksi .= View::make('admin.layouts.components.buttons.lihat', [
                        'url'   => site_url('inventaris_asset/form/' . $row->id . '/1'),
                        'judul' => 'Lihat Data',
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.buttons.edit', [
                        'url' => "inventaris_asset/form/{$row->id}",
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => site_url('inventaris_asset/delete/' . $row->id),
                        'confirmDelete' => true,
                    ])->render();

                    return $aksi;
                })
                ->editColumn('kode_barang_register', static fn ($row): string => $row->kode_barang . '<br>' . $row->register)
                ->editColumn('harga', static fn ($row): string => number_format($row->harga, 0, '.', '.'))
                ->rawColumns(['aksi', 'kode_barang_register'])
                ->make();
        }

        return show_404();
    }

    public function form($id = '', $view = false): void
    {
        isCan('u');

        if ($id) {
            $data['action']      = $view ? 'Rincian' : 'Ubah';
            $data['form_action'] = ci_route('inventaris_asset.update', $id);
            $data['main']        = InventarisAsset::findOrFail($id);
            $data['view_mark']   = $view ? 1 : 0;
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = ci_route('inventaris_asset.create');
            $data['main']        = null;
            $data['view_mark']   = null;
        }

        $data['tip']      = 1;
        $data['aset']     = Aset::golongan(6)->get()->toArray();
        $data['get_kode'] = $this->header['desa'];
        $count_reg        = InventarisAsset::reg();

        $reg            = $count_reg + 1;
        $data['hasil']  = sprintf('%06s', $reg);
        $data['kd_reg'] = InventarisAsset::ListKdRegister();
        $data['header'] = InventarisSubMenuEnum::ASET['header'];

        view('admin.inventaris.asset.form', $data);
    }

    public function create(): void
    {
        isCan('u');

        if (InventarisAsset::create($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');

    }

    public function update($id): void
    {
        isCan('u');
        if (InventarisAsset::find($id)->update($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id): void
    {
        isCan('h');

        // cek jika inventaris sudah di mutasi
        if (InventarisAsset::with('mutasi')->find($id)->mutasi) {
            // Set kolom id_inventaris_jalan menjadi null untuk baris terkait di tabel mutasi_inventaris_jalan
            MutasiInventarisAsset::where('id_inventaris_jalan', $id)->update(['id_inventaris_jalan' => null]);
        }
        if (InventarisAsset::with('mutasi')->find($id)->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function validate($data)
    {
        $nama_barang = explode('_', $this->input->post('nama_barang'))[0];

        return [
            // nama barang perlu diambil nama nya saja tanpa kode barang etc
            // next : cek bagian edit dan detail, setelah itu cek bagian cetak
            'nama_barang'      => $nama_barang,
            'kode_barang'      => $this->input->post('kode_barang'),
            'register'         => $this->input->post('register'),
            'jenis'            => $this->input->post('jenis_asset'),
            'judul_buku'       => $this->input->post('judul'),
            'spesifikasi_buku' => $this->input->post('spesifikasi'),
            'asal_daerah'      => $this->input->post('asal_kesenian'),
            'pencipta'         => $this->input->post('pencipta_kesenian'),
            'bahan'            => $this->input->post('bahan_kesenian'),
            'jenis_hewan'      => $this->input->post('jenis_hewan'),
            'ukuran_hewan'     => $this->input->post('ukuran_hewan'),
            'jenis_tumbuhan'   => $this->input->post('jenis_tumbuhan'),
            'ukuran_tumbuhan'  => $this->input->post('ukuran_tumbuhan'),
            'jumlah'           => $this->input->post('jumlah'),
            'tahun_pengadaan'  => $this->input->post('tahun_pengadaan'),
            'asal'             => $this->input->post('asal'),
            'harga'            => $this->input->post('harga'),
            'keterangan'       => $this->input->post('keterangan'),
            'visible'          => 1,
        ];
    }

    public function dialog($aksi = 'cetak')
    {
        $data               = $this->modal_penandatangan();
        $data['aksi']       = $aksi;
        $data['formAction'] = ci_route('inventaris_asset.cetak', $aksi);

        return view('admin.inventaris.dialog_cetak', $data);
    }

    public function cetak($aksi = '')
    {
        $data          = $this->modal_penandatangan();
        $data['aksi']  = $aksi;
        $data['tahun'] = $this->input->post('tahun');

        $data['letak_ttd'] = ['1', '2', '12'];
        $data['file']      = 'Asset_Lainnya_';

        $data['total'] = (int) (InventarisAsset::aktif()->cetak($data['tahun'])->get()->sum('harga'));
        $data['print'] = InventarisAsset::aktif()->cetak($data['tahun'])->get();

        return view('admin.inventaris.asset.cetak', $data);
    }
}
