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

use App\Models\Pamong;
use App\Services\LaporanInventaris;

defined('BASEPATH') || exit('No direct script access allowed');

class Laporan_inventaris extends Admin_Controller
{
    public $modul_ini           = 'sekretariat';
    public $sub_modul_ini       = 'inventaris';
    private array $list_session = ['tahun'];

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $data['tip'] = 1;

        view('admin.inventaris.laporan.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $mutasi = $this->input->get('mutasi');

            return datatables()->of($this->sumberData(null, $mutasi))
                ->addIndexColumn()
                ->addColumn('aksi', static fn ($row): string => '<div class="btn-group" role="group" aria-label="..."><a href="' . ci_route($row['name']) . '" class="btn btn-default btn-sm"  title="Lihat Data" type="button"><i class="fa fa-eye"></i></a></div>')
                ->rawColumns(['aksi'])
                ->make();
        }

        return show_404();
    }

    private function sumberData($tahun = null, $mutasi = false)
    {
        return LaporanInventaris::all($tahun, $mutasi);
    }

    public function dialog($aksi = 'cetak', $mutasi = 0)
    {
        $data               = $this->modal_penandatangan();
        $data['aksi']       = $aksi;
        $data['formAction'] = ci_route('laporan_inventaris.cetak', $aksi) . '/' . $mutasi;

        return view('admin.inventaris.dialog_cetak', $data);
    }

    public function cetak($aksi = '', $mutasi = false)
    {
        $data           = $this->modal_penandatangan();
        $data['aksi']   = $aksi;
        $data['pamong'] = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong')])->first()->toArray();
        $tahun          = $this->input->post('tahun');
        $data['main']   = $this->sumberData($tahun, $mutasi);
        $data['tahun']  = 'Semua Tahun';

        $data['file']  = 'laporan_inventaris_';
        $data['title'] = 'BUKU INVENTARIS DAN KEKAYAAN DESA';

        if ($mutasi) {
            $data['file'] .= 'mutasi_';
            $data['title'] = 'BUKU INVENTARIS DAN KEKAYAAN DESA YANG TELAH DIHAPUS';
        }
        if ($tahun) {
            $data['tahun'] = 'Tahun ' . $tahun;
        }

        $data['isi']       = 'admin.inventaris.laporan.cetak';
        $data['letak_ttd'] = ['1', '2', '12'];

        return view('admin.layouts.components.format_cetak', $data);
    }

    public function mutasi(): void
    {
        $data['tip'] = 2;
        view('admin.inventaris.laporan.mutasi.index', $data);
    }

    // TODO: Ini digunakan dimana pada view
    public function filter($filter): void
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect('laporan_inventaris/permendagri_47');
    }
}
