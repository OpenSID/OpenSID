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

use App\Enums\Dtks\DtksEnum;
use App\Enums\StatusEnum;
use App\Models\Config;
use App\Models\Dtks as ModelDtks;
use App\Models\DtksAnggota;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Rtm;
use App\Models\Wilayah;
use App\Services\DTKSRegsosEk2022k;
use Illuminate\Support\Facades\DB;

// TODO : jika ada perubahan versi DTKS terbaru, selain merubah data yg ada
// silahkan buat kode untuk menghapus file pdf versi DTKS sebelumnya.
// cek kode DTKSRegsosEk2022k::generateCetakPdf()

class Dtks extends Admin_Controller
{
    public $modul_ini     = 'satu-data';
    public $sub_modul_ini = 'dtks';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    /**
     * proses singkronisasi jumlah anggota dtks dengan anggota keluarga yg berubah
     *
     * @param mixed $rtm
     */
    protected function syncDtksRtm($rtm)
    {
        $semua_anggota = Penduduk::without([
            'jenisKelamin',
            'agama',
            'pendidikan',
            'pendidikanKK',
            'pekerjaan',
            'wargaNegara',
            'golonganDarah',
            'cacat',
            'statusKawin',
            'pendudukStatus',
            'wilayah',
        ])
            ->select('id', 'nama', 'id_rtm', 'rtm_level', 'id_kk', 'kk_level')
            ->whereIn('id_rtm', $rtm->pluck('no_kk'))
            ->get();
        $semua_dtks = ModelDtks::select('id', 'id_rtm', 'id_keluarga', 'versi_kuisioner')
            ->withCount('dtksAnggota')
            ->whereIn('id_rtm', $rtm->pluck('id'))
            ->get();

        foreach ($rtm as $item) {
            $dtks_rtm = $semua_dtks->where('id_rtm', $item->id);

            if ($dtks_rtm->count() != 0) {
                $jumlah_dtks_anggota = $dtks_rtm->reduce(static fn ($carry, $item) => $carry + $item->dtks_anggota_count);
                $jumlah_anggota_rt   = $semua_anggota->where('id_rtm', $item->no_kk)->count();

                if ($jumlah_anggota_rt != $jumlah_dtks_anggota) {
                    foreach ($dtks_rtm as $dtks) {
                        if ($dtks->versi_kuisioner == DtksEnum::REGSOS_EK2022_K) {
                            return (new DTKSRegsosEk2022k())->generateDefaultDtks($dtks);
                        }
                    }
                }
            }
        }
    }

    public function index()
    {
        $data['rtm'] = Rtm::with([
            'kepalaKeluarga' => static function ($builder): void {
                $builder->select('id', 'nama', 'nik');
                $builder->without([
                    'jenisKelamin',
                    'agama',
                    'pendidikan',
                    'pendidikanKK',
                    'pekerjaan',
                    'wargaNegara',
                    'golonganDarah',
                    'cacat',
                    'statusKawin',
                    'pendudukStatus',
                    'wilayah',
                ]);
            },
        ])
            ->where('terdaftar_dtks', 1)
            ->get();

        $this->syncDtksRtm($data['rtm']);

        return view('admin.dtks.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $rtm      = (new Rtm())->getTable();
            $keluarga = (new Keluarga())->getTable();
            $penduduk = (new Penduduk())->getTable();
            $wilayah  = (new Wilayah())->getTable();
            //  =
            $join = DB::table('dtks')
                ->select(
                    'dtks.id',
                    'dtks.id_rtm',
                    'dtks.id_keluarga',
                    'is_draft',
                    'versi_kuisioner',
                    'dtks.updated_at',
                    'nama_petugas_pencacahan',
                    'nama_responden',
                    'nama_ppl'
                )
                ->addSelect('krt.nik as nik_krt', 'krt.nama as nama_krt', 'kk.nik as nik_kk', 'kk.nama as nama_kk')
                ->addSelect('wil_krt.dusun as dusun_krt', 'wil_krt.rt as rt_krt', 'wil_krt.rw as rw_krt', 'wil_kk.dusun as dusun_kk', 'wil_kk.rt as rt_kk', 'wil_kk.rw as rw_kk')
                ->addSelect(DB::raw("(SELECT COUNT(DISTINCT(a.id_kk)) FROM {$penduduk} AS a WHERE rtm.no_kk = a.id_rtm ) as `keluarga_count`"))
                ->addSelect(DB::raw('(SELECT COUNT(*) FROM dtks_anggota WHERE dtks.id = dtks_anggota.id_dtks) as `anggota_count`'))
                ->join($rtm . ' AS rtm', 'rtm.id', '=', 'dtks.id_rtm')
                ->join($keluarga . ' AS keluarga', 'keluarga.id', '=', 'dtks.id_keluarga')
                ->join($penduduk . ' AS krt', 'rtm.nik_kepala', '=', 'krt.id')
                ->join($penduduk . ' AS kk', 'keluarga.nik_kepala', '=', 'kk.id')
                ->join($wilayah . ' AS wil_krt', 'krt.id_cluster', '=', 'wil_krt.id')
                ->join($wilayah . ' AS wil_kk', 'kk.id_cluster', '=', 'wil_kk.id')
                ->where('dtks.config_id', identitas('id'));

            $case_sql = static function (&$query, $keyword, array $fields = [DtksEnum::REGSOS_EK2022_K => ''], string $operator = 'LIKE') {
                $sql     = '(versi_kuisioner = ' . DtksEnum::REGSOS_EK2022_K . ' AND ' . $fields[DtksEnum::REGSOS_EK2022_K] . ' ' . $operator . ' ?)';
                $binding = ["%{$keyword}%"];

                return $query->whereRaw($sql, $binding);
            };
            $add_column = static function (&$row, array $fields = [DtksEnum::REGSOS_EK2022_K => '']) {
                if ($row->versi_kuisioner == DtksEnum::REGSOS_EK2022_K) {
                    return $row->{$fields[DtksEnum::REGSOS_EK2022_K]};
                }
            };

            return datatables()->of($join)
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';
                    // $aksi .= '<a href=" '. ci_route("dtks.detail.{$row->id}") . '" class="btn bg-purple btn-flat btn-sm" title="Rincian Data"><i class="fa fa-list-ol"></i></a>';
                    if (can('u')) {
                        $aksi .= '&nbsp;<a href="' . ci_route("dtks.form.{$row->id}") . '" class="btn btn-warning btn-sm"  title="Lihat & Ubah Data"><i class="fa fa-edit"></i></a> ';
                        $aksi .= '&nbsp;<a href="#" data-id="' . $row->id . '" class="btn-hapus btn btn-danger btn-sm" data-remote="false" data-toggle="modal" data-target="#modal-confirm-delete-dtks" title="Hapus Data"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->addColumn('dusun', static fn ($row) => $add_column($row, [DtksEnum::REGSOS_EK2022_K => 'dusun_krt']))
                ->filterColumn('dusun', static fn ($query, $keyword) => $case_sql($query, $keyword, [DtksEnum::REGSOS_EK2022_K => 'wil_krt.dusun']))
                ->addColumn('rt', static fn ($row) => $add_column($row, [DtksEnum::REGSOS_EK2022_K => 'rt_krt']))
                ->filterColumn('rt', static fn ($query, $keyword) => $case_sql($query, $keyword, [DtksEnum::REGSOS_EK2022_K => 'wil_krt.rt']))
                ->addColumn('rw', static fn ($row) => $add_column($row, [DtksEnum::REGSOS_EK2022_K => 'rw_krt']))
                ->filterColumn('rw', static fn ($query, $keyword) => $case_sql($query, $keyword, [DtksEnum::REGSOS_EK2022_K => 'wil_krt.rw']))
                ->addColumn('petugas', static fn ($row) => $add_column($row, [DtksEnum::REGSOS_EK2022_K => 'nama_ppl']))
                ->addColumn('responden', static fn ($row) => $row->nama_responden)
                ->addColumn('versi_kuisioner', static fn ($row): string => DtksEnum::VERSION_LIST[$row->versi_kuisioner])
                ->filterColumn('versi_kuisioner', static function ($query, $keyword): void {
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->toJson();
        }

        return show_404();
    }

    public function listAnggota($id_dtks)
    {
        $this->syncDtksRtm(Rtm::where('terdaftar_dtks', 1)->get());
        $data['anggota'] = DtksAnggota::with([
            'penduduk' => static function ($builder): void {
                $builder->select('id', 'nama', 'nik');
                $builder->without([
                    'jenisKelamin',
                    'agama',
                    'pendidikan',
                    'pendidikanKK',
                    'pekerjaan',
                    'wargaNegara',
                    'golonganDarah',
                    'cacat',
                    'statusKawin',
                    'pendudukStatus',
                    'wilayah',
                ]);
            },
        ])
            ->select('id', 'id_dtks', 'id_penduduk')
            ->where('id_dtks', $id_dtks)
            ->get();

        return view('admin.dtks.list_anggota', $data);
    }

    public function loadRecentInfo()
    {
        try {
            return (new DTKSRegsosEk2022k())->info();
        } catch (Throwable $th) {
            echo 'File info tidak ditemukan';
        }
    }

    public function loadRecentImpor()
    {
        try {
            return (new DTKSRegsosEk2022k())->impor();
        } catch (Throwable $th) {
            echo 'File info tidak ditemukan';
        }
    }

    public function ekspor()
    {
        $versi_kuisioner = $this->input->get('versi');
        if ($versi_kuisioner == DtksEnum::REGSOS_EK2021_RT) {
            redirect_with('error', 'Proses versi tidak ditemukan', ci_route('dtks'));
        } elseif ($versi_kuisioner == DtksEnum::REGSOS_EK2022_K) {
            return (new DTKSRegsosEk2022k())->ekspor();
        } else {
            redirect_with('error', 'Versi tidak ditemukan', ci_route('dtks'));
        }
    }

    public function cetak2($id = null)
    {
        $ids = $this->request['id'] ?? [];

        $dtks = ModelDtks::whereIn('id', $ids)
            ->orWhere('id', $id)
            ->get();

        if ($dtks->count() == 0) {
            if ($this->input->is_ajax_request()) {
                return json(['message' => 'Data terpilih tidak ditemukan'], 404);
            }
            redirect_with('error', 'Data terpilih tidak ditemukan', $_SERVER['HTTP_REFERER']);
        } elseif ($dtks->count() == 1) {
            // lempar ke halaman baru tanpa ajax, (dilakukan oleh js)
            if ($this->input->is_ajax_request()) {
                return json(['message' => 'Mengunduh 1 data', 'href' => ci_route('dtks/cetak2/' . $dtks->first()->id)], 200);
            }
        }

        if ($dtks->count() == 1) {
            $versi_kuisioner = $dtks->first()->versi_kuisioner;
            if ($versi_kuisioner == DtksEnum::REGSOS_EK2022_K) {
                return (new DTKSRegsosEk2022k())->cetakPreviewSingle($dtks->first());
            }
        } else {
            $dtks = $dtks->groupBy('versi_kuisioner');

            // create each zip versi
            $list_path = [];

            foreach ($dtks as $versi_kuisioner => $item) {
                if ($versi_kuisioner == DtksEnum::REGSOS_EK2022_K) {
                    $paths = (new DTKSRegsosEk2022k())->cetakZip($item);
                    $list_path += $paths;
                }
            }
            // simpan
            $list_path_to_zip = collect($list_path);
            $list_path        = collect($list_path)->transform(static fn ($item, $key): array => ['id' => $item['id'], 'status_file' => $item['status_file']]);

            $proses_belum_selesai = $list_path->where('status_file', 0);

            if ($proses_belum_selesai->count() > 0) {
                return json(['message' => 'Proses Data', 'list' => $list_path], 200);
            }
            if ($this->input->is_ajax_request()) {
                return json(['message' => 'Download', 'list' => $list_path], 200);
            }

            if ($list_path_to_zip->count() != 0) {
                $this->load->library('zip');

                foreach ($list_path_to_zip as $item) {
                    $this->zip->read_file($item['file']);
                }
                $this->zip->download('berkas_dtks_regsosek_terpilih_' . date('d-m-Y') . '.zip');
            }
        }
    }

    public function new($id_rtm = 'A'): void
    {
        $id_rtm = ($id_rtm == 'A') ? bilangan($this->request['id_rtm']) : bilangan($id_rtm);

        if ($id_rtm == null) {
            redirect_with('error', 'RTM tidak ditemukan');
        }

        $dtks = ModelDtks::where([
            'id_rtm'          => $id_rtm,
            'versi_kuisioner' => DtksEnum::VERSION_CODE,
            // 'is_draft' => StatusEnum::YA, // belum terpakai karena yg dibutuhkan hanya 1 data per rtm
        ])->first();

        if (! $dtks) {
            DB::beginTransaction();
            $dtks = ModelDtks::create([
                'versi_kuisioner' => DtksEnum::VERSION_CODE,
                'id_rtm'          => $id_rtm,
                'is_draft'        => StatusEnum::YA,
            ]);
            $this->synchroniseDTKSWithOpenSid($dtks);
            DB::commit();
        }

        redirect("{$this->controller}/form/{$dtks->id}");
    }

    public function latest($id_rtm): void
    {
        $dtks = ModelDtks::where(['id_rtm' => $id_rtm])
            ->orderBy('created_at', 'ASC')
            ->first();

        if (! $dtks) {
            session_error(' : Belum ada data');
            redirect_with('error', 'Belum ada data', $_SERVER['HTTP_REFERER']);
        }
        redirect("{$this->controller}/form/{$dtks->id}");
    }

    public function form($id)
    {
        $dtks = ModelDtks::where(['id' => $id])->first();

        if (! $dtks) {
            return json(['message' => 'Formulir Tidak ditemukan'], 404);
        }

        if ($dtks->versi_kuisioner == DtksEnum::REGSOS_EK2022_K) {
            return (new DTKSRegsosEk2022k())->form($dtks);
        }
    }

    protected function synchroniseDTKSWithOpenSid(ModelDtks $dtks)
    {
        $config = Config::first();

        if (! $config) {
            session_error(' : Konfigurasi tidak ditemukan');
            redirect_with('error', 'Konfigurasi tidak ditemukan', ci_route('dtks'));
        }

        if ($dtks->versi_kuisioner == DtksEnum::REGSOS_EK2022_K) {
            $dtks = (new DTKSRegsosEk2022k())->syncronizeWithOpenSid($dtks);
        }
    }

    /**
     * savePengaturan
     *
     * @param mixed $versi_dtks
     */
    public function savePengaturan($versi_dtks)
    {
        if ($this->input->is_ajax_request()) {
            if ($versi_dtks == DtksEnum::REGSOS_EK2022_K) {
                $respon = (new DTKSRegsosEk2022k())->save($this->request);

                return json($respon['content'], $respon['header_code']);
            }

            return json(['message' => 'Tidak melakukan apapun'], 200);
        }
        if ($versi_dtks == DtksEnum::REGSOS_EK2022_K) {
            $respon = (new DTKSRegsosEk2022k())->save($this->request);

            return json($respon['content'], $respon['header_code']);
        }

        session_error(' : Tidak melakukan apapun');
        redirect_with('error', 'Tidak melakukan apapun', $_SERVER['HTTP_REFERER']);
    }

    /**
     * Save
     *
     * @param dtks_id $id
     */
    public function save($id)
    {
        $dtks = ModelDtks::with('dtksAnggota')
            ->where(['id' => $id])
            ->first();

        if ($this->input->is_ajax_request()) {
            if (! $dtks) {
                return json(['message' => 'Formulir Tidak ditemukan'], 404);
            }

            if ($dtks->versi_kuisioner == DtksEnum::REGSOS_EK2022_K) {
                $respon = (new DTKSRegsosEk2022k())->save($this->request, $dtks);

                return json($respon['content'], $respon['header_code']);
            }

            return json(['message' => 'Tidak melakukan apapun'], 200);
        }
        if (! $dtks) {
            session_error(' : Formulir tidak ditemukan');
            redirect_with('error', 'Formulir Tidak ditemukan', $_SERVER['HTTP_REFERER']);
        }

        if ($dtks->versi_kuisioner == DtksEnum::REGSOS_EK2022_K) {
            $respon = (new DTKSRegsosEk2022k())->save($this->request, $dtks);

            return json($respon['content'], $respon['header_code']);
        }

        session_error(' : Tidak melakukan apapun');
        redirect_with('error', 'Tidak melakukan apapun', $_SERVER['HTTP_REFERER']);
    }

    /**
     * Delete
     *
     * @param dtks_id $id
     */
    public function delete($id)
    {
        isCan('h');

        ModelDtks::find($id)->delete();

        return json(['message' => 'Berhasil'], 200);
    }

    /**
     * Remove some data
     *
     * @param dtks_id $id
     */
    public function remove($id)
    {
        $dtks = ModelDtks::find($id);

        if (! $dtks) {
            return json(['message' => 'Formulir Tidak ditemukan'], 404);
        }

        if ($dtks->versi_kuisioner == DtksEnum::REGSOS_EK2022_K) {
            $respon = (new DTKSRegsosEk2022k())->remove($dtks, $this->request);

            return json($respon['content'], $respon['header_code']);
        }

        return json(['message' => 'Tidak melakukan apapun'], 200);
    }
}
