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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\RentangUmur;
use App\Services\LaporanPenduduk;
use Illuminate\Support\Facades\DB;

class Rentang_umur extends Admin_Controller
{
    public $modul_ini     = 'statistik';
    public $sub_modul_ini = 'statistik-kependudukan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $data['lap']         = '13';
        $data['allKategori'] = LaporanPenduduk::menuLabel();
        $data['kategori']    = 'Penduduk';

        return view('admin.statistik.rentang_umur.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(RentangUmur::status()->orderBy('dari'))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('statistik.rentang_umur.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Rentang Umur"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('statistik.rentang_umur.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('tanggal', static fn ($row) => tgl_indo($row->tanggal))
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        if ($id === null) {
            $data['form_action']       = site_url('statistik/rentang_umur/insert');
            $data['rentang']           = RentangUmur::status()->select(DB::raw('CASE WHEN MAX(sampai) IS NULL THEN 0 ELSE (MAX(sampai) + 1) END as dari'))->first();
            $data['rentang']['nama']   = '';
            $data['rentang']['sampai'] = '';
        } else {
            $data['form_action'] = site_url("statistik/rentang_umur/update/{$id}");
            $data['rentang']     = RentangUmur::status()->findOrFail($id);
        }

        return view('admin.statistik.rentang_umur.form', $data);
    }

    public function insert(): void
    {
        isCan('u');

        if (RentangUmur::create($this->validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data', site_url('statistik/rentang_umur'));
        }

        redirect_with('error', 'Gagal Tambah Data', site_url('statistik/rentang_umur'));
    }

    public function update($id = 0): void
    {
        isCan('u');

        $update = RentangUmur::findOrFail($id);
        $data   = $this->validate($this->request, $id);

        if ($update->update($data)) {
            redirect_with('success', 'Berhasil Ubah Data', site_url('statistik/rentang_umur'));
        }

        redirect_with('error', 'Gagal Ubah Data', site_url('statistik/rentang_umur'));
    }

    public function delete($id): void
    {
        isCan('h');

        if (RentangUmur::destroy($id)) {
            redirect_with('success', 'Berhasil Hapus Data', site_url('statistik/rentang_umur'));
        }

        redirect_with('error', 'Gagal Hapus Data', site_url('statistik/rentang_umur'));
    }

    public function delete_all(): void
    {
        isCan('h');
        if (RentangUmur::destroy($this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data', site_url('statistik/rentang_umur'));
        }

        redirect_with('error', 'Gagal Hapus Data', site_url('statistik/rentang_umur'));
    }

    private function validate(array $data = [], $id = false): array
    {
        $rentang = RentangUmur::status()
            ->when($id, static fn ($query) => $query->where('id', '!=', $id))
            ->pluck('sampai', 'dari')
            ->flatMap(static fn ($end, $start) => range($start, $end === 150 ? RentangUmur::status()->max('sampai') : $end))
            ->unique()
            ->values()
            ->toArray();

        if (array_intersect($rentang, range($data['dari'], $data['sampai']))) {
            redirect_with('error', "Rentang umur tidak boleh tumpang tindih dengan rentang umur yang sudah ada. <br>Rentang umur dari {$data['dari']} sampai {$data['sampai']} sudah digunakan.", site_url('statistik/rentang_umur'));
        }

        $data['status'] = 1;

        if ($data['sampai'] != '150') {
            $data['nama'] = $data['dari'] . ' s/d ' . $data['sampai'] . ' Tahun';
        } else {
            $data['nama'] = 'Di atas ' . $data['dari'] . ' Tahun';
        }

        return $data;
    }
}
