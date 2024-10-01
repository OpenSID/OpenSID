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

use App\Enums\StatusEnum;
use App\Models\Agama;
use App\Models\Pekerjaan;
use App\Models\Pemilihan;
use App\Models\Pendidikan;
use App\Models\PendidikanKK;
use App\Models\Penduduk;
use App\Models\PendudukStatus;
use App\Models\Sex;
use App\Models\StatusKawin;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Dpt extends Admin_Controller
{
    public $modul_ini     = 'kependudukan';
    public $sub_modul_ini = 'calon-pemilih';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        isCan('b');
        $data['jenis_kelamin']        = Sex::get();
        $data['dusun']                = Wilayah::dusun()->get();
        $data['tanggal_pemilihan']    = Schema::hasTable('pemilihan') ? Pemilihan::tanggalPemilihan() : Carbon::now()->format('Y-m-d');
        $data['input_umur']           = true;
        $data['list_agama']           = Agama::get()->toArray();
        $data['list_pendidikan']      = Pendidikan::get()->toArray();
        $data['list_pendidikan_kk']   = PendidikanKK::get()->toArray();
        $data['list_pekerjaan']       = Pekerjaan::get()->toArray();
        $data['list_status_kawin']    = StatusKawin::get()->toArray();
        $data['list_status_penduduk'] = PendudukStatus::get()->toArray();
        $data['list_tag_id_card']     = StatusEnum::all();

        view('admin.dpt.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $tglPemilihan = $this->input->get('tgl_pemilihan') ?? date('d-m-Y');

            return datatables()->of(
                $this->sumberData()
            )
                ->addIndexColumn()
                ->editColumn('alamat_sekarang', static fn ($row) => $row->keluarga->alamat ?? $row->alamat_sekarang)
                ->addColumn('dusun', static fn ($row): string => strtoupper($row->keluarga->wilayah->dusun ?? $row->wilayah->dusun))
                ->addColumn('rw', static fn ($row) => $row->keluarga->wilayah->rw ?? $row->wilayah->rw)
                ->addColumn('rt', static fn ($row) => $row->keluarga->wilayah->rt ?? $row->wilayah->rt)
                ->addColumn('umur_pemilihan', static fn ($row): string => usia($row->tanggallahir, $tglPemilihan, '%y'))
                ->make();
        }

        return show_404();
    }

    private function sumberData()
    {
        $tglPemilihan   = $this->input->get('tgl_pemilihan') ?? date('d-m-Y');
        $sex            = $this->input->get('sex');
        $dusun          = $this->input->get('dusun');
        $rw             = $this->input->get('rw');
        $rt             = $this->input->get('rt');
        $advanceSearch  = $this->input->get('advanced');
        $umurFilter     = $advanceSearch['umur'];
        $filterKategori = [];
        $tagIdFilter    = null;
        parse_str($advanceSearch['search'], $kategoriFilter);

        foreach ($kategoriFilter as $key => $val) {
            if (trim($val) !== '') {
                $filterKategori[$key] = $val;
            }
        }
        if (isset($filterKategori['tag_id_card'])) {
            $tagIdFilter = $filterKategori['tag_id_card'];
            unset($filterKategori['tag_id_card']);
        }
        $listCluster = [];
        if ($dusun) {
            $cluster = new Wilayah();
            $cluster = $cluster->whereDusun($dusun);
            if ($rw) {
                $cluster = $cluster->whereRw($rw);
                if ($rt) {
                    $cluster = $cluster->whereRt($rt);
                }
            }
            $listCluster = $cluster->select(['id'])->get()->pluck('id', 'id')->toArray();
        }

        return Penduduk::batasiUmur($tglPemilihan, $umurFilter)->dpt($tglPemilihan)
            ->when($tagIdFilter, static fn ($q) => $tagIdFilter == '1' ? $q->whereNotNull('tag_id_card') : $q->whereNull('tag_id_card'))
            ->when($filterKategori, static fn ($q) => $q->where($filterKategori))
            ->when($sex, static fn ($q) => $q->where('sex', $sex))
            ->when($listCluster, static fn ($q) => $q->whereIn('id_cluster', $listCluster))
            ->withOnly(['jenisKelamin', 'keluarga', 'wilayah', 'pendidikanKK', 'pekerjaan', 'statusKawin']);
    }

    public function cetak($aksi = 'cetak', $privasi_nik = 0): void
    {
        $paramDatatable = json_decode($this->input->post('params'), 1);
        $_GET           = $paramDatatable;

        $orderColumn = $paramDatatable['columns'][$paramDatatable['order'][0]['column']]['name'];
        $orderDir    = $paramDatatable['order'][0]['dir'];
        $query       = $this->sumberData();

        if ($orderColumn == 'keluarga.no_kk') {
            $query->selectRaw('tweb_penduduk.*, tweb_keluarga.no_kk as no_kk')->leftJoin('tweb_keluarga', 'tweb_keluarga.id', '=', 'tweb_penduduk.id_kk');
            $orderColumn = 'no_kk';
        }

        if ($paramDatatable['start']) {
            $query->skip($paramDatatable['start']);
        }

        $data = [
            'tanggal_pemilihan' => $paramDatatable['tgl_pemilihan'],
            'main'              => $query->take($paramDatatable['length'])->orderBy($orderColumn, $orderDir)->get(),
            'start'             => $paramDatatable['start'],
        ];
        if ($privasi_nik == 1) {
            $data['privasi_nik'] = true;
        }
        if ($aksi == 'unduh') {
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=DPT_' . $paramDatatable['tgl_pemilihan'] . '.xls');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
        view('admin.dpt.dpt_cetak', $data);
    }

    public function ajax_cetak(string $aksi = 'cetak'): void
    {
        $data['aksi']   = $aksi;
        $data['action'] = ci_route('dpt.cetak.' . $aksi);

        view('admin.dpt.ajax_cetak_bersama', $data);
    }
}
