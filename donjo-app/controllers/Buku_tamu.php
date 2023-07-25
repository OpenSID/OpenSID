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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Models\BukuKepuasan;
use App\Models\BukuTamu;
use Carbon\Carbon;

class Buku_tamu extends Anjungan_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini     = 354;
        $this->sub_modul_ini = 355;
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(BukuTamu::query()->with('jk', 'bidang', 'keperluan'))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    if (can('h')) {
                        return '<a href="#" data-href="' . route('buku_tamu.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }
                })
                ->addColumn('tampil_foto', static function ($row) {
                    return '<img src="' . $row->url_foto . '" class="penduduk_kecil text-center" alt="' . $row->nama . '">';
                })
                ->editColumn('created_at', static function ($row) {
                    return Carbon::parse($row->created_at)->dayName . ' / ' . tgl_indo($row->created_at);
                })
                ->rawColumns(['ceklist', 'tampil_foto', 'aksi'])
                ->make();
        }

        return view('admin.buku_tamu.tamu.index');
    }

    public function delete($id = null)
    {
        $this->redirect_hak_akses('h');

        if (BukuTamu::destroy($this->request['id_cb'] ?? $id)) {
            // Hapus juga data indeks kepuasan
            BukuKepuasan::whereIdNama($this->request['id_cb'] ?? $id)->delete();
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function cetak()
    {
        return view('admin.buku_tamu.tamu.cetak', [
            'data_tamu' => BukuTamu::latest()->get(),
        ]);
    }
}
