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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Enums\JenisKelaminEnum;
use App\Enums\StatusHubunganEnum;
use App\Models\Anak;
use App\Models\IbuHamil;
use App\Models\KIA;
use App\Models\Paud;
use App\Models\Penduduk;
use App\Models\Posyandu;
use App\Models\SasaranPaud;
use Carbon\Carbon;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

class Stunting extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('rekap');
        $this->load->helper('tglindo_helper');
        $this->modul_ini     = 'kesehatan';
        $this->sub_modul_ini = 'stunting';
    }

    public function index()
    {
        $data             = $this->widget();
        $data['navigasi'] = 'posyandu';

        return view('admin.stunting.index', $data);
    }

    public function datatablesPosyandu()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(Posyandu::withConfigId())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . route('stunting.formPosyandu', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . route('stunting.deletePosyandu', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function formPosyandu($id = null)
    {
        $this->redirect_hak_akses('u');

        $data             = $this->widget();
        $data['navigasi'] = 'posyandu';

        if ($id) {
            $data['action']     = 'Ubah';
            $data['formAction'] = route('stunting.updatePosyandu', $id);
            $data['posyandu']   = Posyandu::findOrFail($id);
        } else {
            $data['action']     = 'Tambah';
            $data['formAction'] = route('stunting.insertPosyandu');
            $data['posyandu']   = null;
        }

        return view('admin.stunting.posyandu_form', $data);
    }

    public function insertPosyandu()
    {
        $this->redirect_hak_akses('u');

        if (Posyandu::create(static::validatePosyandu($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data', 'stunting');
        }

        redirect_with('error', 'Gagal Tambah Data', 'stunting');
    }

    public function updatePosyandu($id = null)
    {
        $this->redirect_hak_akses('u');

        $data = Posyandu::findOrFail($id);

        if ($data->update(static::validatePosyandu($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data', 'stunting');
        }

        redirect_with('error', 'Gagal Ubah Data', 'stunting');
    }

    public function deletePosyandu($id = null)
    {
        $this->redirect_hak_akses('h');

        $data = $this->request['id_cb'] ?? [$id];

        if (IbuHamil::whereIn('posyandu_id', $data)->exists() || Anak::whereIn('posyandu_id', $data)->exists() || Paud::whereIn('posyandu_id', $data)->exists()) {
            redirect_with('error', 'Posyandu terkait masih digunakan pada ibu hamil/anak', 'stunting');
        }

        if (Posyandu::destroy($data)) {
            redirect_with('success', 'Berhasil Hapus Data', 'stunting');
        }

        redirect_with('error', 'Gagal Hapus Data', 'stunting');
    }

    protected static function validatePosyandu($request = [])
    {
        return [
            'nama'   => htmlentities($request['nama']),
            'alamat' => htmlentities($request['alamat']),
        ];
    }
    // Akhir Posyandu

    // Awal KIA
    public function kia()
    {
        $data             = $this->widget();
        $data['navigasi'] = 'kia';

        return view('admin.stunting.kia', $data);
    }

    public function datatablesKia()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(KIA::with(['ibu', 'anak']))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->editColumn('hari_perkiraan_lahir', static function ($row) {
                    return tgl_indo($row->hari_perkiraan_lahir);
                })
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . route('stunting.formKia', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . route('stunting.deleteKia', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function formKia($id = null)
    {
        $this->redirect_hak_akses('u');

        $data             = $this->widget();
        $data['navigasi'] = 'kia';
        $data['ibu']      = Penduduk::where(static function ($query) {
            $query->where('kk_level', StatusHubunganEnum::KEPALA_KELUARGA)
                ->orWhere('kk_level', StatusHubunganEnum::ISTRI)
                ->orWhere('kk_level', StatusHubunganEnum::ANAK)
                ->orWhere('kk_level', StatusHubunganEnum::MENANTU);
        })
            ->where('sex', JenisKelaminEnum::PEREMPUAN)
            ->get();

        $data['anak'] = Penduduk::select(['id', 'nik', 'nama'])
            ->whereNotIn('id', KIA::pluck('anak_id'))
            ->whereIn('kk_level', [StatusHubunganEnum::ANAK, StatusHubunganEnum::CUCU, StatusHubunganEnum::FAMILI_LAIN])
            ->where('tanggallahir', '>=', Carbon::now()->subYears(6))
            ->get();

        if ($id) {
            $data['action']     = 'Ubah';
            $data['formAction'] = route('stunting.updateKia', $id);
            $data['kia']        = KIA::findOrFail($id);
            $data['ibu']        = $data['ibu']->prepend(Penduduk::find($data['kia']->ibu_id));
            $data['anak']       = $data['anak']->where('id', '!=', $data['kia']->ibu_id)->prepend(Penduduk::find($data['kia']->anak_id));
        } else {
            $data['action']     = 'Tambah';
            $data['formAction'] = route('stunting.insertKia');
            $data['kia']        = null;
        }

        return view('admin.stunting.kia_form', $data);
    }

    public function getIbu()
    {
        if ($this->input->is_ajax_request()) {
            $cari = $this->input->get('q');

            $penduduk = Penduduk::select(['id', 'nik', 'nama', 'id_cluster'])
                ->when($cari, static function ($query) use ($cari) {
                    $query->orWhere('nik', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                })
                ->where(static function ($query) {
                    $query->where('kk_level', StatusHubunganEnum::KEPALA_KELUARGA)
                        ->orWhere('kk_level', StatusHubunganEnum::ISTRI)
                        ->orWhere('kk_level', StatusHubunganEnum::ANAK)
                        ->orWhere('kk_level', StatusHubunganEnum::MENANTU);
                })
                ->where('sex', JenisKelaminEnum::PEREMPUAN)
                ->paginate(10);

            return json([
                'results' => collect($penduduk->items())
                    ->map(static function ($item) {
                        return [
                            'id'   => $item->id,
                            'text' => 'NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun') . ' ' . $item->wilayah->dusun),
                        ];
                    }),
                'pagination' => [
                    'more' => $penduduk->currentPage() < $penduduk->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    public function getAnak()
    {
        foreach (KiA::all() as $data) {
            $anakId[] = $data->anak_id ?? 0;
        }

        if ($this->input->is_ajax_request()) {
            $ibu      = $this->input->get('ibu');
            $penduduk = Penduduk::find($ibu);
            $anak     = Penduduk::where('id_kk', $penduduk->id_kk)
                ->where('id', '!=', $ibu)->whereNotIn('id', $anakId)
                ->whereIn('kk_level', [StatusHubunganEnum::ANAK, StatusHubunganEnum::CUCU, StatusHubunganEnum::FAMILI_LAIN])->where('tanggallahir', '>=', Carbon::now()
                ->subYears(6))
                ->get();

            return json($anak);
        }
    }

    public function insertKia()
    {
        $this->redirect_hak_akses('u');

        if (KIA::create(static::validateKia($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data', 'stunting/kia');
        }

        redirect_with('error', 'Gagal Tambah Data', 'stunting/kia');
    }

    public function updateKia($id = null)
    {
        $this->redirect_hak_akses('u');

        $data = KIA::findOrFail($id);

        if ($data->update(static::validateKia($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data', 'stunting/kia');
        }

        redirect_with('error', 'Gagal Ubah Data', 'stunting/kia');
    }

    public function deleteKia($id = null)
    {
        $this->redirect_hak_akses('h');

        $data = $this->request['id_cb'] ?? [$id];

        if (IbuHamil::whereIn('kia_id', $data)->exists() || Anak::whereIn('kia_id', $data)->exists() || Paud::whereIn('kia_id', $data)->exists()) {
            redirect_with('error', 'KIA terkait masih digunakan pada ibu hamil/anak', 'stunting/kia');
        }

        if (KIA::destroy($data)) {
            redirect_with('success', 'Berhasil Hapus Data', 'stunting/kia');
        }

        redirect_with('error', 'Gagal Hapus Data', 'stunting/kia');
    }

    protected static function validateKia($request = [])
    {
        $kia = KIA::where('no_kia', $request['no_kia'])->first();

        if ($kia && $kia->no_kia != $request['no_kia_lama']) {
            redirect_with('error', 'Tidak dapat memasukkan no kia yang sama', 'stunting/kia');
        }

        if (! empty($request['perkiraan_lahir'])) {
            $status = 1;
        } else {
            $status = 2;
        }

        Penduduk::where('id', $request['id_ibu'])->update(['hamil' => $status]);

        return [
            'no_kia'               => $request['no_kia'],
            'ibu_id'               => $request['id_ibu'],
            'anak_id'              => empty($request['id_anak']) ? null : $request['id_anak'],
            'hari_perkiraan_lahir' => empty($request['perkiraan_lahir']) ? null : date('Y-m-d', strtotime($request['perkiraan_lahir'])),
        ];
    }
    // Akhir KIA

    // Mulai Pemantauan
    public function pemantauan_ibu_hamil()
    {
        $data             = $this->widget();
        $data['navigasi'] = 'pemantauan-bulanan-ibu-hamil';
        $data['bulan']    = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $data['tahun']    = IbuHamil::select(IbuHamil::raw('YEAR(created_at) tahun'))->groupBy('tahun')->get();
        $data['posyandu'] = Posyandu::all();

        return view('admin.stunting.pemantauan_ibu_hamil', $data);
    }

    public function datatablesIbuHamil()
    {
        if ($this->input->is_ajax_request()) {
            $filters = [
                'bulan'    => $this->input->get('bulan'),
                'tahun'    => $this->input->get('tahun'),
                'posyandu' => $this->input->get('posyandu'),
            ];

            return datatables()->of(IbuHamil::select('ibu_hamil.created_at as tanggal_periksa', 'ibu_hamil.*')->with(['kia', 'kia.ibu'])->filter($filters))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id_ibu_hamil . '"/>';
                    }
                })
                ->addIndexColumn()
                ->editColumn('kia.hari_perkiraan_lahir', static function ($row) {
                    return tgl_indo($row->kia->hari_perkiraan_lahir);
                })
                ->editColumn('tanggal_melahirkan', static function ($row) {
                    return tgl_indo($row->tanggal_melahirkan);
                })
                ->editColumn('tanggal_periksa', static function ($row) {
                    return tgl_indo($row->tanggal_periksa);
                })
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . route('stunting.formIbuHamil', $row->id_ibu_hamil) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . route('stunting.deleteIbuHamil', $row->id_ibu_hamil) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function formIbuHamil($id = null)
    {
        $this->redirect_hak_akses('u');

        $data             = $this->widget();
        $data['navigasi'] = 'pemantauan-bulanan-ibu-hamil';
        $data['kia']      = KIA::with('ibu')->get();
        $data['posyandu'] = Posyandu::pluck('nama', 'id');

        if ($this->input->is_ajax_request()) {
            $hamil = KIA::find($this->input->get('kia'));

            return json($hamil->anak_id ?? 0);
        }

        if ($id) {
            $data['action']     = 'Ubah';
            $data['formAction'] = route('stunting.updateIbuHamil', $id);
            $data['ibuHamil']   = IbuHamil::findOrFail($id);
        } else {
            $data['action']     = 'Tambah';
            $data['formAction'] = route('stunting.insertIbuHamil');
            $data['ibuHamil']   = null;
        }

        $data['status_kehamilan_ibu'] = collect(IbuHamil::STATUS_KEHAMILAN_IBU)->pluck('nama', 'id');

        return view('admin.stunting.pemantauan_ibu_hamil_form', $data);
    }

    public function insertIbuHamil()
    {
        $this->redirect_hak_akses('u');

        $bulan = date('m', strtotime($this->request['tanggal_periksa']));
        $tahun = date('Y', strtotime($this->request['tanggal_periksa']));

        $data = IbuHamil::where('kia_id', $this->request['id_kia'])->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->first();

        if ($data) {
            redirect_with('error', 'Data telah ditambahkan', 'stunting/pemantauan_ibu_hamil');
        }

        if (IbuHamil::create(static::validateIbuHamil($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data', 'stunting/pemantauan_ibu_hamil');
        }

        redirect_with('error', 'Gagal Tambah Data', 'stunting/pemantauan_ibu_hamil');
    }

    public function updateIbuHamil($id = null)
    {
        $this->redirect_hak_akses('u');

        $data = IbuHamil::findOrFail($id);

        if ($data->update(static::validateIbuHamil($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data', 'stunting/pemantauan_ibu_hamil');
        }

        redirect_with('error', 'Gagal Ubah Data', 'stunting/pemantauan_ibu_hamil');
    }

    public function deleteIbuHamil($id = null)
    {
        $this->redirect_hak_akses('h');

        if (IbuHamil::destroy($this->request['id_cb'] ?? $id)) {
            redirect_with('success', 'Berhasil Hapus Data', 'stunting/pemantauan_ibu_hamil');
        }

        redirect_with('error', 'Gagal Hapus Data', 'stunting/pemantauan_ibu_hamil');
    }

    protected static function validateIbuHamil($request = [])
    {
        return [
            'posyandu_id'           => $request['id_posyandu'],
            'kia_id'                => $request['id_kia'],
            'created_at'            => date('Y-m-d', strtotime($request['tanggal_periksa'])),
            'status_kehamilan'      => $request['status_kehamilan'],
            'usia_kehamilan'        => $request['usia_kehamilan'],
            'tanggal_melahirkan'    => empty($request['tanggal_melahirkan']) ? null : date('Y-m-d', strtotime($request['tanggal_melahirkan'])),
            'pemeriksaan_kehamilan' => $request['pemeriksaan_kehamilan'],
            'konsumsi_pil_fe'       => $request['konsumsi_pil_fe'],
            'butir_pil_fe'          => $request['butir_pil_fe'] ?? 0,
            'pemeriksaan_nifas'     => $request['pemeriksaan_nifas'],
            'konseling_gizi'        => $request['konseling_gizi'],
            'kunjungan_rumah'       => $request['kunjungan_rumah'],
            'akses_air_bersih'      => $request['akses_air_bersih'],
            'kepemilikan_jamban'    => $request['kepemilikan_jamban'],
            'jaminan_kesehatan'     => $request['jaminan_kesehatan'],
        ];
    }

    public function eksporIbuHamil()
    {
        $filters = [
            'bulan'    => $this->input->get('bulan'),
            'tahun'    => $this->input->get('tahun'),
            'posyandu' => $this->input->get('posyandu'),
        ];

        $judul = [
            'No KIA',
            'Nama Ibu',
            'Status Kehamilan',
            'Hari Perkiraan Lahir',
            'Usia Kehamilan (Bulan)',
            'Tanggal Melahirkan',
            'Pemeriksaan Kehamilan',
            'Konsumsi Pil Fe',
            'Pemeriksaan Nifas',
            'Kunjungan Rumah',
            'Kepemilikan Akses Air Bersih',
            'Kepemilikan jamban',
            'Jaminan Kesehatan',
        ];

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser(namafile('Laporan Bulanan Ibu Hamil') . '.xlsx');
        $writer->addRow(WriterEntityFactory::createRowFromArray($judul));

        $dataIbuHamil = IbuHamil::with(['kia', 'kia.ibu'])->filter($filters)->get();

        foreach ($dataIbuHamil as $row) {
            $data = [
                $row->kia->no_kia,
                $row->kia->ibu->nama,
                $row->status_kehamilan = ($row->status_kehamilan == 1) ? 'NORMAL' : (($row->status_kehamilan == 2) ? 'RISTI' : (($row->status_kehamilan == 3) ? 'KEK' : '-')),
                tgl_indo($row->kia->hari_perkiraan_lahir),
                $row->usia_kehamilan ?? '-',
                tgl_indo($row->tanggal_melahirkan),
                $row->pemeriksaan_kehamilan == 1 ? 'v' : 'x',
                $row->konsumsi_pil_fe == 1 ? 'v' : 'x',
                $row->pemeriksaan_nifas == 1 ? 'v' : 'x',
                $row->konseling_gizi == 1 ? 'v' : 'x',
                $row->kunjungan_rumah == 1 ? 'v' : 'x',
                $row->akses_air_bersih == 1 ? 'v' : 'x',
                $row->kepemilikan_jamban == 1 ? 'v' : 'x',
                $row->jaminan_kesehatan == 1 ? 'v' : 'x',
            ];
            $writer->addRow(WriterEntityFactory::createRowFromArray($data));
        }
        $writer->close();
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////
    public function pemantauan_anak()
    {
        $data             = $this->widget();
        $data['navigasi'] = 'pemantauan-bulanan-anak';
        $data['bulan']    = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $data['tahun']    = Anak::select(Anak::raw('YEAR(created_at) tahun'))->groupBy('tahun')->get();
        $data['posyandu'] = Posyandu::all();

        return view('admin.stunting.pemantauan_anak', $data);
    }

    public function datatablesAnak()
    {
        if ($this->input->is_ajax_request()) {
            $filters = [
                'bulan'    => $this->input->get('bulan'),
                'tahun'    => $this->input->get('tahun'),
                'posyandu' => $this->input->get('posyandu'),
            ];

            return datatables()->of(Anak::select('bulanan_anak.created_at as tanggal_periksa', 'bulanan_anak.*')->with(['kia', 'kia.anak'])->filter($filters))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id_bulanan_anak . '"/>';
                    }
                })
                ->addIndexColumn()
                ->editColumn('kia.anak.tanggallahir', static function ($row) {
                    return tgl_indo($row->kia->anak->tanggallahir);
                })
                ->editColumn('berat_badan', static function ($row) {
                    return $row->berat_badan . ' kg';
                })
                ->editColumn('tinggi_badan', static function ($row) {
                    return $row->tinggi_badan . ' cm';
                })
                ->editColumn('tanggal_periksa', static function ($row) {
                    return tgl_indo($row->tanggal_periksa);
                })
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . route('stunting.formAnak', $row->id_bulanan_anak) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . route('stunting.deleteAnak', $row->id_bulanan_anak) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function formAnak($id = null)
    {
        $this->redirect_hak_akses('u');

        $data             = $this->widget();
        $data['navigasi'] = 'pemantauan-bulanan-anak';
        $data['kia']      = KIA::with('anak')->where('anak_id', '!=', null)
            ->WhereHas('anak', static function ($query) {
                $query->where('tanggallahir', '>', Carbon::now()->subMonths(24));
            })
            ->get();
        $data['posyandu']                = Posyandu::pluck('nama', 'id');
        $data['status_gizi_anak']        = collect(Anak::STATUS_GIZI_ANAK)->pluck('nama', 'id');
        $data['status_tikar_anak']       = collect(Anak::STATUS_TIKAR_ANAK)->pluck('nama', 'id');
        $data['status_imunisasi_campak'] = Anak::STATUS_IMUNISASI_CAMPAK;

        if ($this->input->is_ajax_request()) {
            $kia     = KIA::find($this->input->get('kia'));
            $data    = Penduduk::find($kia->anak_id);
            $tanggal = Carbon::create($data->tanggallahir);

            return json($tanggal->diff(Carbon::now()));
        }

        if ($id) {
            $kia          = KIA::find($id);
            $anak         = Penduduk::find($kia->anak_id);
            $tanggal      = Carbon::create($anak->tanggallahir);
            $data['umur'] = $tanggal->diff(Carbon::now());

            $data['action']     = 'Ubah';
            $data['formAction'] = route('stunting.updateAnak', $id);
            $data['anak']       = Anak::findOrFail($id);
        } else {
            $data['action']     = 'Tambah';
            $data['formAction'] = route('stunting.insertAnak');
            $data['anak']       = null;
        }

        return view('admin.stunting.pemantauan_anak_form', $data);
    }

    public function insertAnak()
    {
        $this->redirect_hak_akses('u');

        $bulan = date('m', strtotime($this->request['tanggal_periksa']));
        $tahun = date('Y', strtotime($this->request['tanggal_periksa']));

        $data = Anak::where('kia_id', $this->request['id_kia'])->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->first();

        if ($data) {
            redirect_with('error', 'Data telah ditambahkan', 'stunting/pemantauan_anak');
        }

        if (Anak::create(static::validateAnak($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data', 'stunting/pemantauan_anak');
        }

        redirect_with('error', 'Gagal Tambah Data', 'stunting/pemantauan_anak');
    }

    public function updateAnak($id = null)
    {
        $this->redirect_hak_akses('u');

        $data = Anak::findOrFail($id);

        if ($data->update(static::validateAnak($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data', 'stunting/pemantauan_anak');
        }

        redirect_with('error', 'Gagal Ubah Data', 'stunting/pemantauan_anak');
    }

    public function deleteAnak($id = null)
    {
        $this->redirect_hak_akses('h');

        if (Anak::destroy($this->request['id_cb'] ?? $id)) {
            redirect_with('success', 'Berhasil Hapus Data', 'stunting/pemantauan_anak');
        }

        redirect_with('error', 'Gagal Hapus Data', 'stunting/pemantauan_anak');
    }

    protected static function validateAnak($request = [])
    {
        return [
            'posyandu_id'                => $request['id_posyandu'],
            'kia_id'                     => $request['id_kia'],
            'created_at'                 => date('Y-m-d', strtotime($request['tanggal_periksa'])),
            'status_gizi'                => $request['status_gizi'],
            'umur_bulan'                 => $request['umur_bulan'],
            'status_tikar'               => $request['status_tikar'],
            'pemberian_imunisasi_campak' => $request['pemberian_imunisasi_campak'] ?? 0,
            'pemberian_imunisasi_dasar'  => $request['pemberian_imunisasi_dasar'],
            'berat_badan'                => $request['berat_badan'],
            'pengukuran_berat_badan'     => $request['pengukuran_berat_badan'],
            'tinggi_badan'               => $request['tinggi_badan'],
            'pengukuran_tinggi_badan'    => $request['pengukuran_tinggi_badan'],
            'konseling_gizi_ayah'        => $request['konseling_gizi_ayah'],
            'konseling_gizi_ibu'         => $request['konseling_gizi_ibu'],
            'kunjungan_rumah'            => $request['kunjungan_rumah'],
            'air_bersih'                 => $request['air_bersih'],
            'kepemilikan_jamban'         => $request['kepemilikan_jamban'],
            'akta_lahir'                 => $request['akta_lahir'],
            'jaminan_kesehatan'          => $request['jaminan_kesehatan'],
            'pengasuhan_paud'            => $request['pengasuhan_paud'],
        ];
    }

    public function eksporAnak()
    {
        $filters = [
            'bulan'    => $this->input->get('bulan'),
            'tahun'    => $this->input->get('tahun'),
            'posyandu' => $this->input->get('posyandu'),
        ];

        $judul = [
            'No KIA',
            'Nama Anak',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Status Gizi',
            'Umur (Bulan)',
            'Hasil (M/K/H)',
            'Pemberian Imunisasi Dasar',
            'Pengukuran Berat Badan',
            'Pengukuran Tinggi Badan',
            'Konseling Gizi Ayah',
            'Konseling Gizi Ibu',
            'Kunjungan Rumah',
            'Kepemilikan Akses Air Bersih',
            'Kepemilikan Jamban',
            'Akta Lahir',
            'Jaminan Kesehatan',
            'Pengasuhan PAUD',
        ];

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser(namafile('Laporan Bulanan Anak') . '.xlsx');
        $writer->addRow(WriterEntityFactory::createRowFromArray($judul));

        $dataAnak     = Anak::with(['kia', 'kia.anak'])->filter($filters)->get();
        $status_tikar = collect(Anak::STATUS_TIKAR_ANAK)->pluck('simbol', 'id');

        foreach ($dataAnak as $row) {
            if ($row->status_gizi == 1) {
                $row->status_gizi = 'N';
            } elseif ($row->status_gizi == 2) {
                $row->status_gizi = 'GK';
            } elseif ($row->status_gizi == 3) {
                $row->status_gizi = 'GB';
            } else {
                $row->status_gizi = 'S';
            }

            $data = [
                $row->kia->no_kia,
                $row->kia->anak->nama,
                $row->kia->anak->sex == JenisKelaminEnum::LAKI_LAKI ? 'LAKI-LAKI' : 'PEREMPUAN',
                tgl_indo($row->kia->anak->tanggallahir),
                $row->status_gizi,
                $row->umur_bulan,
                $status_tikar[$row->status_tikar],
                $row->pemberian_imunisasi_dasar == 1 ? 'v' : 'x',
                $row->pengukuran_berat_badan == 1 ? 'v' : 'x',
                $row->pengukuran_tinggi_badan == 1 ? 'v' : 'x',
                $row->konseling_gizi == 1 ? 'v' : 'x',
                $row->konseling_gizi_ayah == 1 ? 'v' : 'x',
                $row->konseling_gizi_ibu == 1 ? 'v' : 'x',
                $row->kunjungan_rumah == 1 ? 'v' : 'x',
                $row->air_bersih == 1 ? 'v' : 'x',
                $row->kepemilikan_jamban == 1 ? 'v' : 'x',
                $row->akta_lahir == 1 ? 'v' : 'x',
                $row->jaminan_kesehatan == 1 ? 'v' : 'x',
                $row->pengasuhan_paud == 1 ? 'v' : 'x',
            ];
            $writer->addRow(WriterEntityFactory::createRowFromArray($data));
        }
        $writer->close();
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////
    public function pemantauan_paud()
    {
        $data             = $this->widget();
        $data['navigasi'] = 'pemantauan-sasaran-paud';
        $data['tahun']    = Paud::select(Paud::raw('YEAR(created_at) tahun'))->groupBy('tahun')->get();
        $data['posyandu'] = Posyandu::all();

        return view('admin.stunting.pemantauan_paud', $data);
    }

    public function datatablesPaud()
    {
        if ($this->input->is_ajax_request()) {
            $filters = [
                'tahun'    => $this->input->get('tahun'),
                'posyandu' => $this->input->get('posyandu'),
            ];

            return datatables()->of(Paud::select('sasaran_paud.created_at as tanggal_periksa', 'sasaran_paud.*')->with(['kia', 'kia.anak'])->filter($filters))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id_sasaran_paud . '"/>';
                    }
                })
                ->addIndexColumn()
                ->editColumn('kia.anak.tanggallahir', static function ($row) {
                    return tgl_indo($row->kia->anak->tanggallahir);
                })
                ->editColumn('tanggal_periksa', static function ($row) {
                    return tgl_indo($row->tanggal_periksa);
                })
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . route('stunting.formPaud', $row->id_sasaran_paud) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . route('stunting.deletePaud', $row->id_sasaran_paud) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function formPaud($id = null)
    {
        $this->redirect_hak_akses('u');

        $data             = $this->widget();
        $data['navigasi'] = 'pemantauan-sasaran-paud';
        $data['kia']      = KIA::with('anak')->where('anak_id', '!=', null)
            ->WhereHas('anak', static function ($query) {
                $query->where('tanggallahir', '<=', Carbon::now()->subMonths(24));
            })
            ->get();
        $data['posyandu'] = Posyandu::all();

        if ($id) {
            $data['action']     = 'Ubah';
            $data['formAction'] = route('stunting.updatePaud', $id);
            $data['paud']       = Paud::findOrFail($id);
        } else {
            $data['action']     = 'Tambah';
            $data['formAction'] = route('stunting.insertPaud');
            $data['paud']       = null;
        }

        return view('admin.stunting.pemantauan_paud_form', $data);
    }

    public function insertPaud()
    {
        $this->redirect_hak_akses('u');

        $bulan = date('m', strtotime($this->request['tanggal_periksa']));
        $tahun = date('Y', strtotime($this->request['tanggal_periksa']));

        $data = Paud::where('kia_id', $this->request['id_kia'])->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->first();

        if ($data) {
            redirect_with('error', 'Data telah ditambahkan', 'stunting/pemantauan_paud');
        }

        if (Paud::create(static::validatePaud($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data', 'stunting/pemantauan_paud');
        }

        redirect_with('error', 'Gagal Tambah Data', 'stunting/pemantauan_paud');
    }

    public function updatePaud($id = null)
    {
        $this->redirect_hak_akses('u');

        $data = Paud::findOrFail($id);

        if ($data->update(static::validatePaud($this->request))) {
            redirect_with('success', 'Berhasil Ubah Data', 'stunting/pemantauan_paud');
        }

        redirect_with('error', 'Gagal Ubah Data', 'stunting/pemantauan_paud');
    }

    public function deletePaud($id = null)
    {
        $this->redirect_hak_akses('h');

        if (Paud::destroy($this->request['id_cb'] ?? $id)) {
            redirect_with('success', 'Berhasil Hapus Data', 'stunting/pemantauan_paud');
        }

        redirect_with('error', 'Gagal Hapus Data', 'stunting/pemantauan_paud');
    }

    protected static function validatePaud($request = [])
    {
        return [
            'posyandu_id'   => $request['id_posyandu'],
            'kia_id'        => $request['id_kia'],
            'created_at'    => date('Y-m-d', strtotime($request['tanggal_periksa'])),
            'kategori_usia' => $request['kategori_usia'],
            'januari'       => $request['januari'],
            'februari'      => $request['februari'],
            'maret'         => $request['maret'],
            'april'         => $request['april'],
            'mei'           => $request['mei'],
            'juni'          => $request['juni'],
            'juli'          => $request['juli'],
            'agustus'       => $request['agustus'],
            'september'     => $request['september'],
            'oktober'       => $request['oktober'],
            'november'      => $request['november'],
            'desember'      => $request['desember'],
        ];
    }

    public function eksporPaud()
    {
        $filters = [
            'tahun'    => $this->input->get('tahun'),
            'posyandu' => $this->input->get('posyandu'),
        ];

        $judul = [
            'No KIA',
            'Nama Anak',
            'Jenis Kelamin',
            'Kategori Usia',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser(namafile('Laporan Sasaran Paud') . '.xlsx');
        $writer->addRow(WriterEntityFactory::createRowFromArray($judul));

        $dataPaud = Paud::with(['kia', 'kia.ibu'])->filter($filters)->get();

        foreach ($dataPaud as $row) {
            $data = [
                $row->kia->no_kia,
                $row->kia->anak->nama,
                $row->kia->anak->sex == JenisKelaminEnum::LAKI_LAKI ? 'LAKI-LAKI' : 'PEREMPUAN',
                $row->kategori_usia == 1 ? 'Anak Usia 2 - < 3 Tahun' : 'Anak Usia 3 - 6 Tahun',
                $row->januari   = ($row->januari == 1) ? '-' : (($row->januari == 2) ? 'v' : 'x'),
                $row->februari  = ($row->februari == 1) ? '-' : (($row->februari == 2) ? 'v' : 'x'),
                $row->maret     = ($row->maret == 1) ? '-' : (($row->maret == 2) ? 'v' : 'x'),
                $row->april     = ($row->april == 1) ? '-' : (($row->april == 2) ? 'v' : 'x'),
                $row->mei       = ($row->mei == 1) ? '-' : (($row->mei == 2) ? 'v' : 'x'),
                $row->juni      = ($row->juni == 1) ? '-' : (($row->juni == 2) ? 'v' : 'x'),
                $row->juli      = ($row->juli == 1) ? '-' : (($row->juli == 2) ? 'v' : 'x'),
                $row->agustus   = ($row->agustus == 1) ? '-' : (($row->agustus == 2) ? 'v' : 'x'),
                $row->september = ($row->september == 1) ? '-' : (($row->september == 2) ? 'v' : 'x'),
                $row->oktober   = ($row->oktober == 1) ? '-' : (($row->oktober == 2) ? 'v' : 'x'),
                $row->november  = ($row->november == 1) ? '-' : (($row->november == 2) ? 'v' : 'x'),
                $row->desember  = ($row->desember == 1) ? '-' : (($row->desember == 2) ? 'v' : 'x'),
            ];
            $writer->addRow(WriterEntityFactory::createRowFromArray($data));
        }
        $writer->close();
    }

    ////////////////////////////////////
    public function rekapitulasi_ibu_hamil($kuartal = null, $tahun = null, $id = null)
    {
        if ($kuartal < 1 || $kuartal > 4) {
            $kuartal = null;
        }

        if ($kuartal == null) {
            $bulanSekarang = date('m');

            if ($bulanSekarang <= 3) {
                $_kuartal = 1;
            } elseif ($bulanSekarang <= 6) {
                $_kuartal = 2;
            } elseif ($bulanSekarang <= 9) {
                $_kuartal = 3;
            } elseif ($bulanSekarang <= 12) {
                $_kuartal = 4;
            }
        }

        if ($kuartal == null || $tahun == null) {
            if ($tahun == null) {
                $tahun = date('Y');
            }
            $kuartal = $_kuartal;
            redirect(site_url('stunting/rekapitulasi_ibu_hamil/') . $kuartal . '/' . $tahun);
        }

        $data             = $this->widget();
        $data['navigasi'] = 'rekapitulasi-hasil-pemantauan-ibu-hamil';
        $data['id']       = $id;
        $data['posyandu'] = Posyandu::get();
        $data             = array_merge($data, $this->rekap->get_data_ibu_hamil($kuartal, $tahun, $id));

        return view('admin.stunting.rekapitulasi-ibu-hamil', $data);
    }

    public function rekapitulasi_bulanan_anak($kuartal = null, $tahun = null, $id = null)
    {
        if ($kuartal < 1 || $kuartal > 4) {
            $kuartal = null;
        }

        if ($kuartal == null) {
            $bulanSekarang = date('m');

            if ($bulanSekarang <= 3) {
                $_kuartal = 1;
            } elseif ($bulanSekarang <= 6) {
                $_kuartal = 2;
            } elseif ($bulanSekarang <= 9) {
                $_kuartal = 3;
            } elseif ($bulanSekarang <= 12) {
                $_kuartal = 4;
            }
        }

        if ($kuartal == null || $tahun == null) {
            if ($tahun == null) {
                $tahun = date('Y');
            }
            $kuartal = $_kuartal;
            redirect(site_url('stunting/rekapitulasi_bulanan_anak/') . $kuartal . '/' . $tahun);
        }

        $data             = $this->widget();
        $data['navigasi'] = 'rekapitulasi-hasil-pemantauan-anak';
        $data['id']       = $id;
        $data['posyandu'] = Posyandu::get();
        $data             = array_merge($data, $this->rekap->get_data_bulanan_anak($kuartal, $tahun, $id));

        return view('admin.stunting.rekapitulasi-bulanan-anak', $data);
    }

    ///////////////////////////////////
    public function scorecard_konvergensi($kuartal = null, $tahun = null, $id = null)
    {
        if ($kuartal < 1 || $kuartal > 4) {
            $kuartal = null;
        }

        if ($kuartal == null) {
            $bulanSekarang = date('m');

            if ($bulanSekarang <= 3) {
                $_kuartal = 1;
            } elseif ($bulanSekarang <= 6) {
                $_kuartal = 2;
            } elseif ($bulanSekarang <= 9) {
                $_kuartal = 3;
            } elseif ($bulanSekarang <= 12) {
                $_kuartal = 4;
            }
        } else {
            if ($kuartal == 1) {
                $batasBulanBawah = 1;
                $batasBulanAtas  = 3;
            } elseif ($kuartal == 2) {
                $batasBulanBawah = 4;
                $batasBulanAtas  = 6;
            } elseif ($kuartal == 3) {
                $batasBulanBawah = 7;
                $batasBulanAtas  = 9;
            } elseif ($kuartal == 4) {
                $batasBulanBawah = 10;
                $batasBulanAtas  = 12;
            } else {
                exit('Terjadi Kesalahan di kuartal!');
            }
        }

        if ($kuartal == null || $tahun == null) {
            if ($tahun == null) {
                $tahun = date('Y');
            }
            $kuartal = $_kuartal;
            redirect(site_url('stunting/scorecard_konvergensi/') . $kuartal . '/' . $tahun);
        }

        $JTRT_IbuHamil = IbuHamil::query()
            ->distinct()
            ->join('kia', 'ibu_hamil.kia_id', '=', 'kia.id')
            ->whereMonth('ibu_hamil.created_at', '>=', $batasBulanBawah)
            ->whereMonth('ibu_hamil.created_at', '<=', $batasBulanAtas)
            ->whereYear('ibu_hamil.created_at', $tahun)
            ->selectRaw('ibu_hamil.kia_id as kia_id')
            ->get();

        $JTRT_BulananAnak = Anak::query()
            ->distinct()
            ->join('kia', 'bulanan_anak.kia_id', '=', 'kia.id')
            ->whereMonth('bulanan_anak.created_at', '>=', $batasBulanBawah)
            ->whereMonth('bulanan_anak.created_at', '<=', $batasBulanAtas)
            ->whereYear('bulanan_anak.created_at', $tahun)
            ->selectRaw('bulanan_anak.kia_id as kia_id')
            ->get();

        foreach ($JTRT_IbuHamil as $item_ibuHamil) {
            $dataNoKia[] = $item_ibuHamil;

            foreach ($JTRT_BulananAnak as $item_bulananAnak) {
                if (! in_array($item_bulananAnak, $dataNoKia)) {
                    $dataNoKia[] = $item_bulananAnak;
                }
            }
        }

        $ibu_hamil    = $this->rekap->get_data_ibu_hamil($kuartal, $tahun, $id);
        $bulanan_anak = $this->rekap->get_data_bulanan_anak($kuartal, $tahun, $id);

        //HITUNG KEK ATAU RISTI
        $jumlahKekRisti = 0;

        foreach ($ibu_hamil['dataFilter'] as $item) {
            if (! in_array($item['user']['status_kehamilan'], [null, '1'])) {
                $jumlahKekRisti++;
            }
        }
        //HITUNG HASIL PENGUKURAN TIKAR PERTUMBUHAN
        $status_tikar = collect(Anak::STATUS_TIKAR_ANAK)->pluck('simbol', 'id');
        $tikar        = ['TD' => 0, 'M' => 0, 'K' => 0, 'H' => 0];

        if ($bulanan_anak['dataGrup'] != null) {
            foreach ($bulanan_anak['dataGrup'] as $detail) {
                $totalItem = count($detail);
                $i         = 0;

                foreach ($detail as $item) {
                    if (++$i === $totalItem) {
                        $tikar[$status_tikar[$item['status_tikar']]]++;
                    }
                }
            }

            $jumlahGiziBukanNormal = 0;

            foreach ($bulanan_anak['dataFilter'] as $item) {
                // N = 1
                if ($item['umur_dan_gizi']['status_gizi'] != 'N') {
                    $jumlahGiziBukanNormal++;
                }
            }
        } else {
            $dataNoKia             = [];
            $jumlahGiziBukanNormal = 0;
        }

        //START ANAK PAUD------------------------------------------------------------
        $totalAnak = [
            'januari'   => ['total' => 0, 'v' => 0],
            'februari'  => ['total' => 0, 'v' => 0],
            'maret'     => ['total' => 0, 'v' => 0],
            'april'     => ['total' => 0, 'v' => 0],
            'mei'       => ['total' => 0, 'v' => 0],
            'juni'      => ['total' => 0, 'v' => 0],
            'juli'      => ['total' => 0, 'v' => 0],
            'agustus'   => ['total' => 0, 'v' => 0],
            'september' => ['total' => 0, 'v' => 0],
            'oktober'   => ['total' => 0, 'v' => 0],
            'november'  => ['total' => 0, 'v' => 0],
            'desember'  => ['total' => 0, 'v' => 0],
        ];

        $anak2sd6 = SasaranPaud::query();

        // if ($this->session->userdata('isAdmin')->id_grup !== UserGrup::getGrupId(UserGrup::ADMINISTRATOR)) {
        //     $anak2sd6->where('posyandu_id', $this->session->userdata('id'));
        // } else {
        //     if ($id != null) {
        //         $anak2sd6->where('posyandu_id', $id);
        //     }
        // }

        $anak2sd6->whereYear('sasaran_paud.created_at', $tahun)->get();

        foreach ($anak2sd6 as $datax) {
            $datax->januari != 'belum' ? $totalAnak['januari']['total']++ : $totalAnak['januari']['total'];
            $datax->februari != 'belum' ? $totalAnak['februari']['total']++ : $totalAnak['februari']['total'];
            $datax->maret != 'belum' ? $totalAnak['maret']['total']++ : $totalAnak['maret']['total'];
            $datax->april != 'belum' ? $totalAnak['april']['total']++ : $totalAnak['april']['total'];
            $datax->mei != 'belum' ? $totalAnak['mei']['total']++ : $totalAnak['mei']['total'];
            $datax->juni != 'belum' ? $totalAnak['juni']['total']++ : $totalAnak['juni']['total'];
            $datax->juli != 'belum' ? $totalAnak['juni']['total']++ : $totalAnak['juni']['total'];
            $datax->agustus != 'belum' ? $totalAnak['agustus']['total']++ : $totalAnak['agustus']['total'];
            $datax->september != 'belum' ? $totalAnak['juni']['total']++ : $totalAnak['juni']['total'];
            $datax->oktober != 'belum' ? $totalAnak['oktober']['total']++ : $totalAnak['oktober']['total'];
            $datax->november != 'belum' ? $totalAnak['november']['total']++ : $totalAnak['november']['total'];
            $datax->desember != 'belum' ? $totalAnak['desember']['total']++ : $totalAnak['desember']['total'];

            $datax->januari == 'v' ? $totalAnak['januari']['v']++ : $totalAnak['januari']['v'];
            $datax->februari == 'v' ? $totalAnak['februari']['v']++ : $totalAnak['februari']['v'];
            $datax->maret == 'v' ? $totalAnak['maret']['v']++ : $totalAnak['maret']['v'];
            $datax->april == 'v' ? $totalAnak['april']['v']++ : $totalAnak['april']['v'];
            $datax->mei == 'v' ? $totalAnak['mei']['v']++ : $totalAnak['mei']['v'];
            $datax->juni == 'v' ? $totalAnak['juni']['v']++ : $totalAnak['juni']['v'];
            $datax->juli == 'v' ? $totalAnak['juni']['v']++ : $totalAnak['juni']['v'];
            $datax->agustus == 'v' ? $totalAnak['agustus']['v']++ : $totalAnak['agustus']['v'];
            $datax->september == 'v' ? $totalAnak['juni']['v']++ : $totalAnak['juni']['v'];
            $datax->oktober == 'v' ? $totalAnak['oktober']['v']++ : $totalAnak['oktober']['v'];
            $datax->november == 'v' ? $totalAnak['november']['v']++ : $totalAnak['november']['v'];
            $datax->desember == 'v' ? $totalAnak['desember']['v']++ : $totalAnak['desember']['v'];
        }

        $dataAnak0sd2Tahun = ['jumlah' => 0, 'persen' => 0];
        if ($kuartal == 1) {
            $jmlAnk = $totalAnak['januari']['total'] + $totalAnak['februari']['total'] + $totalAnak['maret']['total'];
            $jmlV   = $totalAnak['januari']['v'] + $totalAnak['februari']['v'] + $totalAnak['maret']['v'];
        } elseif ($kuartal == 2) {
            $jmlAnk = $totalAnak['april']['total'] + $totalAnak['mei']['total'] + $totalAnak['juni']['total'];
            $jmlV   = $totalAnak['april']['v'] + $totalAnak['mei']['v'] + $totalAnak['juni']['v'];
        } elseif ($kuartal == 3) {
            $jmlAnk = $totalAnak['juli']['total'] + $totalAnak['agustus']['total'] + $totalAnak['september']['total'];
            $jmlV   = $totalAnak['juli']['v'] + $totalAnak['agustus']['v'] + $totalAnak['september']['v'];
        } elseif ($kuartal == 4) {
            $jmlAnk = $totalAnak['oktober']['total'] + $totalAnak['november']['total'] + $totalAnak['desember']['total'];
            $jmlV   = $totalAnak['oktober']['v'] + $totalAnak['november']['v'] + $totalAnak['desember']['v'];
        }
        $dataAnak0sd2Tahun['jumlah'] = $jmlV;
        $dataAnak0sd2Tahun['persen'] = $jmlAnk != 0 ? number_format($jmlV / $jmlAnk * 100, 2) : 0;

        //END ANAK PAUD------------------------------------------------------------

        $data                          = $this->widget();
        $data['navigasi']              = 'scorcard-konvergensi';
        $data['dataAnak0sd2Tahun']     = $dataAnak0sd2Tahun;
        $data['id']                    = $id;
        $data['posyandu']              = Posyandu::get();
        $data['JTRT']                  = count($dataNoKia);
        $data['jumlahKekRisti']        = $jumlahKekRisti;
        $data['jumlahGiziBukanNormal'] = $jumlahGiziBukanNormal;
        $data['tikar']                 = $tikar;
        $data['ibu_hamil']             = $ibu_hamil;
        $data['bulanan_anak']          = $bulanan_anak;
        $data['dataTahun']             = $data['ibu_hamil']['dataTahun'];
        $data['kuartal']               = $kuartal;
        $data['_tahun']                = $tahun;
        $data['aktif']                 = 'scorcard';

        return view('admin.stunting.scorcard-konvergensi-desa', $data);
    }

    private function widget()
    {
        return [
            'bulanIniIbuHamil' => IbuHamil::whereMonth('created_at', date('m'))->count(),
            'bulanIniAnak'     => Anak::whereMonth('created_at', date('m'))->count(),
            'totalIbuHamil'    => IbuHamil::count(),
            'totalAnak'        => Anak::count(),
        ];
    }
}
