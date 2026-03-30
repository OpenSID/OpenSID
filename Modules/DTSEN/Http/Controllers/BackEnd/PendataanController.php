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

use App\Enums\StatusEnum;
use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Modules\DTSEN\Enums\DtsenEnum;
use Modules\DTSEN\Models\Dtsen as ModelDtsen;
use Modules\DTSEN\Models\DtsenAnggota;
use Modules\DTSEN\Services\DTSENRegsosEk2022k;
use Modules\DTSEN\Services\DtsenService;
use STS\ZipStream\Facades\Zip;

defined('BASEPATH') || exit('No direct script access allowed');

class PendataanController extends AdminModulController
{
    public $moduleName          = 'DTSEN';
    public $modul_ini           = 'dtsen';
    public $sub_modul_ini       = 'dtsen-pendataan';
    public $kategori_pengaturan = 'DTSEN';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $keluargaTerdaftarIds = ModelDtsen::pluck('id_keluarga')->toArray();

        $keluarga = Keluarga::whereHas('kepalaKeluarga')
            ->with([
                'kepalaKeluarga' => static function ($q): void {
                    $q->select([
                        'id',
                        'id_kk',
                        'nik',
                        'nama',
                    ]);
                },
            ])
            ->get();

        $this->syncDtsenKeluarga($keluarga);

        $data['keluarga'] = $keluarga->filter(
            static fn ($value) => ! in_array($value->id, $keluargaTerdaftarIds)
        );

        return view('dtsen::backend.pendataan.index', $data);
    }

  public function datatables()
  {
        if ($this->input->is_ajax_request()) {
            $keluarga = (new Keluarga())->getTable();
            $penduduk = (new Penduduk())->getTable();
            $wilayah  = (new Wilayah())->getTable();

            $join = DB::table('dtsen')
                ->select(
                    'dtsen.id',
                    'dtsen.id_keluarga',
                    'dtsen.kd_hasil_pendataan_keluarga',
                    'dtsen.kd_peringkat_kesejahteraan_keluarga',
                    'dtsen.nama_petugas_pencacahan',
                    'dtsen.updated_at'
                )
                ->addSelect('kk.nik as nik_kk', 'kk.nama as nama_kk')
                ->addSelect('wil_kk.dusun as dusun_kk', 'wil_kk.rt as rt_kk', 'wil_kk.rw as rw_kk')
                ->addSelect(DB::raw("(SELECT COUNT(*) FROM {$penduduk} AS a WHERE keluarga.id = a.id_kk AND a.status_dasar = 1) as `anggota_count`"))
                ->addSelect(DB::raw('(SELECT COUNT(*) FROM dtsen_anggota WHERE dtsen.id = dtsen_anggota.id_dtsen) as `dtsen_anggota_count`'))
                ->join($keluarga . ' AS keluarga', 'keluarga.id', '=', 'dtsen.id_keluarga')
                ->join($penduduk . ' AS kk', 'keluarga.nik_kepala', '=', 'kk.id')
                ->join($wilayah . ' AS wil_kk', 'kk.id_cluster', '=', 'wil_kk.id')
                ->where('dtsen.config_id', identitas('id'));

            return datatables()->of($join)
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    $aksi .= View::make('admin.layouts.components.buttons.edit', [
                        'url'   => 'dtsen/pendataan/form/' . $row->id,
                        'judul' => 'Lihat & Ubah',
                    ])->render();

                    if (can('u')) {
                        $aksi .= View::make('admin.layouts.components.buttons.btn', [
                            'url'         => '#',
                            'icon'        => 'fa fa-trash',
                            'judul'       => 'Hapus Data',
                            'type'        => 'btn-hapus bg-maroon',
                            'modalTarget' => 'modal-confirm-delete-dtsen',
                            'buttonOnly'  => true,
                            'modal'       => true,
                            'attribut'    => 'data-id=' . $row->id,
                        ])->render();
                    }

                    return $aksi;
                })
                ->addColumn('kd_hasil_pendataan_keluarga', static function ($row) {
                    $statusLabels = Modules\DTSEN\Enums\Regsosek2022kEnum::pilihanBagian2()['205'];

                    if (! $row->kd_hasil_pendataan_keluarga) {
                        return '-';
                    }

                    $labelRaw = $statusLabels[$row->kd_hasil_pendataan_keluarga] ?? 'Tidak diketahui';
                    // Hilangkan nomor di depan (misal: "1. Terisi lengkap" jadi "Terisi lengkap")
                    $label = preg_replace('/^\d+\.\s*/', '', $labelRaw);

                    // Badge hijau untuk terisi lengkap (1), merah untuk responden menolak (4), tanpa badge untuk lainnya
                    if ($row->kd_hasil_pendataan_keluarga == '1') {
                        return '<span class="label label-success">' . $label . '</span>';
                    }
                    if ($row->kd_hasil_pendataan_keluarga == '4') {
                        return '<span class="label label-danger">' . $label . '</span>';
                    }

                    return $label;
                })
                ->filterColumn('kd_hasil_pendataan_keluarga', static fn ($query, $keyword) => $query->where('dtsen.kd_hasil_pendataan_keluarga', 'LIKE', "%{$keyword}%"))
                ->addColumn('kd_peringkat_kesejahteraan_keluarga', static function ($row) {
                    $peringkatLabels = Modules\DTSEN\Enums\Regsosek2022kEnum::pilihanBagian2()['207'];

                    if (! $row->kd_peringkat_kesejahteraan_keluarga) {
                        return '-';
                    }

                    $labelRaw = $peringkatLabels[$row->kd_peringkat_kesejahteraan_keluarga] ?? 'Tidak diketahui';

                    // Hilangkan nomor di depan (misal: "1. Desil 1" jadi "Desil 1")
                    return preg_replace('/^\d+\.\s*/', '', $labelRaw);
                })
                ->filterColumn('kd_peringkat_kesejahteraan_keluarga', static fn ($query, $keyword) => $query->where('dtsen.kd_peringkat_kesejahteraan_keluarga', 'LIKE', "%{$keyword}%"))
                ->addColumn('nik_kk', static fn ($row) => $row->nik_kk)
                ->filterColumn('nik_kk', static fn ($query, $keyword) => $query->where('kk.nik', 'LIKE', "%{$keyword}%"))
                ->addColumn('nama_kk', static fn ($row) => $row->nama_kk)
                ->filterColumn('nama_kk', static fn ($query, $keyword) => $query->where('kk.nama', 'LIKE', "%{$keyword}%"))
                ->addColumn('jumlah_anggota', static function ($row) {
                    if ($row->dtsen_anggota_count != null) {
                        return '<a href="' . ci_route('dtsen/pendataan/listAnggota') . '/' . $row->id . '" title="Lihat Nama Anggota" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Daftar Anggota">' . $row->dtsen_anggota_count . '</a>';
                    }

                    return '0';
                })
                ->addColumn('dusun', static fn ($row) => $row->dusun_kk ?? '-')
                ->filterColumn('dusun', static fn ($query, $keyword) => $query->where('wil_kk.dusun', 'LIKE', "%{$keyword}%"))
                ->addColumn('rt', static fn ($row) => $row->rt_kk ?? '-')
                ->filterColumn('rt', static fn ($query, $keyword) => $query->where('wil_kk.rt', 'LIKE', "%{$keyword}%"))
                ->addColumn('rw', static fn ($row) => $row->rw_kk ?? '-')
                ->filterColumn('rw', static fn ($query, $keyword) => $query->where('wil_kk.rw', 'LIKE', "%{$keyword}%"))
                ->addColumn('petugas', static fn ($row) => $row->nama_petugas_pencacahan ?? '-')
                ->filterColumn('petugas', static fn ($query, $keyword) => $query->where('dtsen.nama_petugas_pencacahan', 'LIKE', "%{$keyword}%"))
                ->rawColumns(['ceklist', 'aksi', 'jumlah_anggota', 'kd_hasil_pendataan_keluarga', 'kd_peringkat_kesejahteraan_keluarga'])
                ->toJson();
        }

        return show_404();
    }

    public function listAnggota($id_dtsen)
    {
        // Ambil semua keluarga untuk sinkronisasi
        $keluarga = Keluarga::all();
        $this->syncDtsenKeluarga($keluarga);

        $data['anggota'] = DtsenAnggota::with([
            'penduduk' => static function ($builder): void {
                $builder->select('id', 'nama', 'nik');
                $builder->without([
                    'keluarga',
                    'wilayah',
                ]);
            },
        ])
            ->select('id', 'id_dtsen', 'id_penduduk')
            ->where('id_dtsen', $id_dtsen)
            ->get();

        return view('dtsen::backend.pendataan.list_anggota', $data);
    }

    public function loadRecentInfo()
    {
        try {
            return (new DTSENRegsosEk2022k())->info();
        } catch (Throwable $th) {
            echo 'File info tidak ditemukan';
        }
    }

    public function loadRecentImpor()
    {
        try {
            return (new DTSENRegsosEk2022k())->impor();
        } catch (Throwable $th) {
            echo 'File info tidak ditemukan';
        }
    }

    public function ekspor()
    {
        $versi_kuisioner = $this->input->get('versi');
        if ($versi_kuisioner == DtsenEnum::REGSOS_EK2021_RT) {
            redirect_with('error', 'Proses versi tidak ditemukan', ci_route('dtsen/pendataan'));
        } elseif ($versi_kuisioner == DtsenEnum::REGSOS_EK2022_K) {
            return (new DTSENRegsosEk2022k())->ekspor();
        } else {
            redirect_with('error', 'Versi tidak ditemukan', ci_route('dtsen/pendataan'));
        }
    }

    public function cetak2($id = null)
    {
        $ids = $this->request['id'] ?? [];

        $dtsen = ModelDtsen::whereIn('id', $ids)
            ->orWhere('id', $id)
            ->get();

        if ($dtsen->count() == 0) {
            if ($this->input->is_ajax_request()) {
                return json(['message' => 'Data terpilih tidak ditemukan'], 404);
            }
            redirect_with('error', 'Data terpilih tidak ditemukan', $_SERVER['HTTP_REFERER']);
        } elseif ($dtsen->count() == 1) {
            if ($this->input->is_ajax_request()) {
                return json(['message' => 'Mengunduh 1 data', 'href' => ci_route('dtsen/pendataan/cetak2/' . $dtsen->first()->id)], 200);
            }
        }

        if ($dtsen->count() == 1) {
            $versi_kuisioner = $dtsen->first()->versi_kuisioner;
            if ($versi_kuisioner == DtsenEnum::REGSOS_EK2022_K) {
                return (new DTSENRegsosEk2022k())->cetakPreviewSingle($dtsen->first());
            }
        } else {
            $dtsen = $dtsen->groupBy('versi_kuisioner');

            $list_path = [];

            foreach ($dtsen as $versi_kuisioner => $item) {
                if ($versi_kuisioner == DtsenEnum::REGSOS_EK2022_K) {
                    $paths = (new DTSENRegsosEk2022k())->cetakZip($item);
                    $list_path += $paths;
                }
            }

            $list_path_to_zip = collect($list_path);
            $list_path        = collect($list_path)->transform(static fn ($item, $key): array => ['id' => $item['id'], 'status_file' => $item['status_file']]);

            $proses_belum_selesai = $list_path->where('status_file', 0);

            if ($proses_belum_selesai->count() > 0) {
                return json(['message' => 'Proses Data', 'list' => $list_path], 200);
            }
            if ($this->input->is_ajax_request()) {
                return json(['message' => 'Data Siap Diunduh', 'list' => $list_path], 200);
            }

            if ($list_path_to_zip->count() !== 0) {
                $files = $list_path_to_zip
                    ->mapWithKeys(static function ($item) {
                        // key = path di filesystem
                        // value = nama file di dalam zip
                        return [
                            $item['file'] => basename($item['file']),
                        ];
                    })
                    ->toArray();

                return Zip::create(
                    name: 'berkas_dtsen_regsosek_terpilih_' . date('Y_m_d') . '.zip',
                    files: $files
                )
                    ->response()
                    ->send();
            }

        }
    }

    public function new($id_keluarga = 'A'): void
    {
        $id_keluarga = ($id_keluarga == 'A') ? bilangan($this->request['id_keluarga']) : bilangan($id_keluarga);

        if ($id_keluarga == null) {
            redirect_with('error', 'Keluarga tidak ditemukan', ci_route('dtsen/pendataan'));
        }

        $dtsen = ModelDtsen::where([
            'id_keluarga'     => $id_keluarga,
            'versi_kuisioner' => DtsenEnum::VERSION_CODE,
        ])->first();

        if (! $dtsen) {
            DB::beginTransaction();
            $dtsen = ModelDtsen::create([
                'versi_kuisioner' => DtsenEnum::VERSION_CODE,
                'id_keluarga'     => $id_keluarga,
                'is_draft'        => StatusEnum::YA,
            ]);

            try {
                (new DtsenService())->synchroniseDTSENWithOpenSid($dtsen);
            } catch (Exception $e) {
                DB::rollBack();
                redirect_with('error', 'Keluarga gagal disimpan: ' . $e->getMessage(), ci_route('dtsen/pendataan'));
            }

            DB::commit();
        }

        redirect("dtsen/pendataan/form/{$dtsen->id}");
    }

    public function latest($id_keluarga): void
    {
        $dtsen = ModelDtsen::where(['id_keluarga' => $id_keluarga])
            ->orderBy('created_at', 'ASC')
            ->first();

        if (! $dtsen) {
            session_error(' : Belum ada data');
            redirect_with('error', 'Belum ada data', $_SERVER['HTTP_REFERER']);
        }
        redirect("dtsen/pendataan/form/{$dtsen->id}");
    }

    public function form($id)
    {
        $dtsen = ModelDtsen::where('id', $id)->first();

        if (! $dtsen) {
            return response()->json([
                'message' => 'Formulir tidak ditemukan',
            ], 404);
        }

        if ($dtsen->versi_kuisioner == DtsenEnum::REGSOS_EK2022_K) {
            return (new DTSENRegsosEk2022k())->form($dtsen);
        }

        // ⬇️ INI YANG SEBELUMNYA HILANG
        return response()->json([
            'message' => 'Versi kuisioner tidak didukung',
        ], 400);
    }

    public function savePengaturan($versi_dtsen)
    {
        if ($this->input->is_ajax_request()) {
            if ($versi_dtsen == DtsenEnum::REGSOS_EK2022_K) {
                $respon = (new DTSENRegsosEk2022k())->save($this->request);

                return json($respon['content'], $respon['header_code']);
            }

            return json(['message' => 'Tidak melakukan apapun'], 200);
        }
        if ($versi_dtsen == DtsenEnum::REGSOS_EK2022_K) {
            $respon = (new DTSENRegsosEk2022k())->save($this->request);

            return json($respon['content'], $respon['header_code']);
        }

        session_error(' : Tidak melakukan apapun');
        redirect_with('error', 'Tidak melakukan apapun', $_SERVER['HTTP_REFERER']);
    }

    public function save($id)
    {
        $dtsen = ModelDtsen::with('dtsenAnggota')
            ->where(['id' => $id])
            ->first();

        if ($this->input->is_ajax_request()) {
            if (! $dtsen) {
                return json(['message' => 'Formulir Tidak ditemukan'], 404);
            }

            if ($dtsen->versi_kuisioner == DtsenEnum::REGSOS_EK2022_K) {
                try {
                    $respon = (new DTSENRegsosEk2022k())->save($this->request, $dtsen);

                    return json($respon['content'], $respon['header_code']);
                } catch (Exception $e) {
                    log_message('error', 'Error save DTSEN: ' . $e->getMessage());

                    return json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
                }
            }

            return json(['message' => 'Tidak melakukan apapun'], 200);
        }

        if (! $dtsen) {
            session_error(' : Formulir tidak ditemukan');
            redirect_with('error', 'Formulir Tidak ditemukan', $_SERVER['HTTP_REFERER']);
        }

        if ($dtsen->versi_kuisioner == DtsenEnum::REGSOS_EK2022_K) {
            try {
                $respon = (new DTSENRegsosEk2022k())->save($this->request, $dtsen);

                if ($respon['header_code'] == 200) {
                    redirect_with('success', $respon['content']['message'], $_SERVER['HTTP_REFERER']);
                } else {
                    redirect_with('error', $respon['content']['message'], $_SERVER['HTTP_REFERER']);
                }
            } catch (Exception $e) {
                log_message('error', 'Error save DTSEN: ' . $e->getMessage());
                redirect_with('error', 'Terjadi kesalahan: ' . $e->getMessage(), $_SERVER['HTTP_REFERER']);
            }
        }

        session_error(' : Tidak melakukan apapun');
        redirect_with('error', 'Tidak melakukan apapun', $_SERVER['HTTP_REFERER']);
    }

    public function delete($id)
    {
        isCan('h');

        ModelDtsen::find($id)->delete();

        return json(['message' => 'Berhasil'], 200);
    }

    public function remove($id)
    {
        $dtsen = ModelDtsen::find($id);

        if (! $dtsen) {
            return json(['message' => 'Formulir Tidak ditemukan'], 404);
        }

        if ($dtsen->versi_kuisioner == DtsenEnum::REGSOS_EK2022_K) {
            $respon = (new DTSENRegsosEk2022k())->remove($dtsen, $this->request);

            return json($respon['content'], $respon['header_code']);
        }

        return json(['message' => 'Tidak melakukan apapun'], 200);
    }

    /**
     * Proses sinkronisasi jumlah anggota dtsen dengan anggota keluarga yang berubah
     *
     * @param mixed $keluarga
     */
    protected function syncDtsenKeluarga($keluarga)
    {
        $semua_anggota = Penduduk::without([
            'pekerjaan',
            'cacat',
            'wilayah',
        ])
            ->select('id', 'nama', 'id_kk', 'kk_level')
            ->whereIn('id_kk', $keluarga->pluck('id'))
            ->where('status_dasar', 1)
            ->get();

        $semua_dtsen = ModelDtsen::select('id', 'id_keluarga', 'versi_kuisioner')
            ->withCount('dtsenAnggota')
            ->whereIn('id_keluarga', $keluarga->pluck('id'))
            ->get();

        foreach ($keluarga as $item) {
            $dtsen_keluarga = $semua_dtsen->where('id_keluarga', $item->id);

            if ($dtsen_keluarga->count() != 0) {
                $jumlah_dtsen_anggota    = $dtsen_keluarga->reduce(static fn ($carry, $item) => $carry + $item->dtsen_anggota_count);
                $jumlah_anggota_keluarga = $semua_anggota->where('id_kk', $item->id)->count();

                if ($jumlah_anggota_keluarga != $jumlah_dtsen_anggota) {
                    foreach ($dtsen_keluarga as $dtsen) {
                        if ($dtsen->versi_kuisioner == DtsenEnum::REGSOS_EK2022_K) {
                            return (new DTSENRegsosEk2022k())->generateDefaultDtsen($dtsen);
                        }
                    }
                }
            }
        }
    }
}
