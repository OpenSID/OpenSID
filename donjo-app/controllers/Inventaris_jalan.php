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

use App\Models\Aset;
use App\Models\InventarisJalan;
use App\Models\MutasiInventarisJalan;

defined('BASEPATH') || exit('No direct script access allowed');

class Inventaris_jalan extends Admin_Controller
{
    public $modul_ini     = 'sekretariat';
    public $sub_modul_ini = 'inventaris';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $data['tip'] = 1;

        view('admin.inventaris.jalan.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $data = InventarisJalan::with('mutasi')->aktif();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u') && ! $row->mutasi) {
                        $aksi .= '<a href="' . site_url('inventaris_jalan_mutasi/form/' . $row->id) . '" title="Mutasi Data" class="btn bg-olive btn-sm"><i class="fa fa-external-link-square"></i></a>';
                    }

                    $aksi .= '<a href="' . site_url('inventaris_jalan/form/' . $row->id . '/1') . '" title="Lihat Data" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url('inventaris_jalan/form/' . $row->id) . '" title="Edit Data" class="btn bg-orange btn-sm"><i class="fa fa-edit"></i></a>';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . site_url('inventaris_jalan/delete/' . $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                    }

                    return $aksi;
                })
                ->editColumn('kode_barang_register', static fn ($row): string => $row->kode_barang . '<br>' . $row->register)
                ->editColumn('tanggal_dokument', static fn ($row): string => date('d M Y', strtotime($row->tanggal_dokument)))
                ->editColumn('tanggal_mutasi', static function ($row) {
                    if ($row->mutasi) {
                        return date('d M Y', strtotime($row->mutasi->tahun_mutasi));
                    }
                })
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
            $data['form_action'] = ci_route('inventaris_jalan.update', $id);
            $data['main']        = InventarisJalan::findOrFail($id);
            $data['view_mark']   = $view ? 1 : 0;
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = ci_route('inventaris_jalan.create');
            $data['main']        = null;
            $data['view_mark']   = null;
        }

        $data['tip']      = 1;
        $data['aset']     = Aset::golongan(5)->get()->toArray();
        $data['get_kode'] = $this->header['desa'];
        $count_reg        = InventarisJalan::reg();

        $reg           = $count_reg + 1;
        $data['hasil'] = sprintf('%06s', $reg);

        view('admin.inventaris.jalan.form', $data);
    }

    public function create(): void
    {
        isCan('u');

        if (InventarisJalan::create($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');

    }

    public function update($id): void
    {
        isCan('u');
        if (InventarisJalan::find($id)->update($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id): void
    {
        isCan('h');

        // cek jika inventaris sudah di mutasi
        if (InventarisJalan::with('mutasi')->find($id)->mutasi) {
            // Set kolom id_inventaris_jalan menjadi null untuk baris terkait di tabel mutasi_inventaris_jalan
            MutasiInventarisJalan::where('id_inventaris_jalan', $id)->update(['id_inventaris_jalan' => null]);
        }
        if (InventarisJalan::with('mutasi')->find($id)->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function validate($data)
    {
        return [
            'nama_barang'      => $this->input->post('nama_barang'),
            'kode_barang'      => $this->input->post('kode_barang'),
            'register'         => $this->input->post('register'),
            'kondisi'          => $this->input->post('kondisi'),
            'kontruksi'        => $this->input->post('kontruksi'),
            'panjang'          => $this->input->post('panjang'),
            'lebar'            => $this->input->post('lebar'),
            'luas'             => $this->input->post('luas'),
            'letak'            => $this->input->post('alamat'),
            'no_dokument'      => $this->input->post('no_bangunan'),
            'tanggal_dokument' => date('Y-m-d', strtotime((string) $this->input->post('tanggal_bangunan'))),
            'status_tanah'     => $this->input->post('status_tanah'),
            'kode_tanah'       => $this->input->post('kode_tanah'),
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
        $data['formAction'] = ci_route('inventaris_jalan.cetak', $aksi);

        return view('admin.inventaris.dialog_cetak', $data);
    }

    public function cetak($aksi = '')
    {
        $data          = $this->modal_penandatangan();
        $data['aksi']  = $aksi;
        $data['tahun'] = $this->input->post('tahun');

        $data['isi']       = 'admin.inventaris.jalan.cetak';
        $data['letak_ttd'] = ['1', '2', '12'];
        $data['file']      = 'Jalan_Irigasi_Jaringan_';

        $data['total'] = (int) (InventarisJalan::aktif()->cetak($data['tahun'])->get()->sum('harga'));
        $data['print'] = InventarisJalan::aktif()->cetak($data['tahun'])->get();

        return view('admin.layouts.components.format_cetak', $data);
    }
}
