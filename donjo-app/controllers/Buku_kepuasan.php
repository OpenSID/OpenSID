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

use App\Models\BukuKepuasan;
use App\Models\BukuPertanyaan;
use Carbon\Carbon;

class Buku_kepuasan extends Anjungan_Controller
{
    public $modul_ini           = 'buku-tamu';
    public $sub_modul_ini       = 'data-kepuasan';
    public $kategori_pengaturan = 'buku-tamu';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(BukuPertanyaan::query()->whereIn('id', BukuKepuasan::select('id_pertanyaan')->groupBy('id_pertanyaan')))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '<a href="' . site_url('buku_kepuasan/show/' . $row->id) . '" class="btn bg-teal btn-sm" title="Lihat Data"><i class="fa fa-list"></i></a> ';

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('buku_kepuasan.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return view('admin.buku_tamu.kepuasan.index');
    }

    public function show($id = null)
    {
        BukuKepuasan::where('id_pertanyaan', $id)->first() ?? show_404();

        return view('admin.buku_tamu.kepuasan.show', [
            'id_pertanyaan' => $id,
        ]);
    }

    public function datatables_show($id = null)
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(BukuKepuasan::query()->where('id_pertanyaan', $id)->with('tamu'))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '<a href="' . site_url('buku_kepuasan/show/' . $row->id) . '" class="btn bg-teal btn-sm" title="Lihat Data"><i class="fa fa-list"></i></a> ';

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('buku_kepuasan.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('created_at', static fn ($row): string => Carbon::parse($row->created_at)->dayName . ' / ' . tgl_indo($row->created_at))
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (BukuKepuasan::where('id_pertanyaan', $id)->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function deleteAll(): void
    {
        isCan('h');

        foreach ($this->request['id_cb'] as $id) {
            $this->delete($id);
        }
    }
}
