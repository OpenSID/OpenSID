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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

require_once APPPATH . 'controllers/Statistik.php';

use App\Models\RentangUmur;
use Illuminate\Support\Facades\DB;

class Rentang_umur extends Statistik
{
    public $modul_ini       = 'statistik';
    public $sub_modul_ini   = 'statistik-kependudukan';
    public $aliasController = 'statistik';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function rentang_umur()
    {
        $data['lap']                   = 13;
        $data['stat_penduduk']         = $this->referensi_model->list_ref(STAT_PENDUDUK);
        $data['stat_keluarga']         = $this->referensi_model->list_ref(STAT_KELUARGA);
        $data['stat_kategori_bantuan'] = $this->referensi_model->list_ref(STAT_BANTUAN);
        $data['stat_bantuan']          = $this->program_bantuan_model->list_program(0);
        $data['judul_kelompok']        = 'Jenis Kelompok';

        $this->get_data_stat($data, $data['lap']);

        return view('admin.statistik.rentang_umur.index', $data);
    }

    public function datatables_rentang_umur()
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
                        $aksi .= '<a href="' . ci_route('statistik.form_rentang', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Rentang Umur"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('statistik.rentang_delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('tanggal', static fn ($row) => tgl_indo($row->tanggal))
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form_rentang($id = 0)
    {
        if ($id == 0) {
            $data['form_action']       = site_url('statistik/rentang_insert');
            $data['rentang']           = RentangUmur::status()->select(DB::raw('CASE WHEN MAX(sampai) IS NULL THEN 0 ELSE (MAX(sampai) + 1) END as dari'))->first();
            $data['rentang']['nama']   = '';
            $data['rentang']['sampai'] = '';
        } else {
            $data['form_action'] = site_url("statistik/rentang_update/{$id}");
            $data['rentang']     = RentangUmur::status()->findOrFail($id);
        }

        return view('admin.statistik.rentang_umur.form', $data);
    }

    public function rentang_insert(): void
    {
        isCan('u');

        if (RentangUmur::create($this->validate_rentang($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data', site_url('statistik/rentang_umur'));
        }

        redirect_with('error', 'Gagal Tambah Data', site_url('statistik/rentang_umur'));
    }

    public function rentang_update($id = 0): void
    {
        isCan('u');

        $update = RentangUmur::findOrFail($id);
        $data   = $this->validate_rentang($this->request);

        if ($update->update($data)) {
            redirect_with('success', 'Berhasil Ubah Data', site_url('statistik/rentang_umur'));
        }

        redirect_with('error', 'Gagal Ubah Data', site_url('statistik/rentang_umur'));
    }

    public function rentang_delete($id): void
    {
        isCan('h');

        if (RentangUmur::destroy($id)) {
            redirect_with('success', 'Berhasil Hapus Data', site_url('statistik/rentang_umur'));
        }

        redirect_with('error', 'Gagal Hapus Data', site_url('statistik/rentang_umur'));
    }

    public function delete_all_rentang(): void
    {
        isCan('h');

        if (RentangUmur::destroy($this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data', site_url('statistik/rentang_umur'));
        }

        redirect_with('error', 'Gagal Hapus Data', site_url('statistik/rentang_umur'));
    }

    private function validate_rentang($data = []): array
    {
        $data['status'] = 1;
        if ($data['sampai'] != '99999') {
            $data['nama'] = $data['dari'] . ' s/d ' . $data['sampai'] . ' Tahun';
        } else {
            $data['nama'] = 'Di atas ' . $data['dari'] . ' Tahun';
        }

        return $data;
    }
}
