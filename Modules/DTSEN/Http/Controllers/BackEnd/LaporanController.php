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

use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Modules\DTSEN\Enums\DtsenEnum;
use Modules\DTSEN\Enums\Regsosek2022kEnum;
use Modules\DTSEN\Models\Dtsen as ModelDtsen;
use Modules\DTSEN\Services\DTSENRegsosEk2022k;

defined('BASEPATH') || exit('No direct script access allowed');

class LaporanController extends AdminModulController
{
    public $moduleName          = 'DTSEN';
    public $modul_ini           = 'dtsen';
    public $sub_modul_ini       = 'dtsen-laporan';
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

        $data['status_kesejahteraan']
            = Regsosek2022kEnum::pilihanBagian2()[205] ?? [];

        $data['peringkat_kesejahteraan']
            = Regsosek2022kEnum::pilihanBagian2()[207] ?? [];

        return view('dtsen::backend.laporan.index', $data);
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

            $status_kesejahteraan    = $this->input->get('kd_status_kesejahteraan');
            $peringkat_kesejahteraan = $this->input->get('kd_peringkat_kesejahteraan_keluarga');

            // Filter Status Kesejahteraan
            if (! empty($status_kesejahteraan)) {
                $join->where('dtsen.kd_hasil_pendataan_keluarga', $status_kesejahteraan);
            }

            // Filter Peringkat Kesejahteraan Keluarga
            if (! empty($peringkat_kesejahteraan)) {
                $join->where(
                    'dtsen.kd_peringkat_kesejahteraan_keluarga',
                    $peringkat_kesejahteraan
                );
            }

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
                    $statusLabels = Regsosek2022kEnum::pilihanBagian2()['205'];

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
                    $peringkatLabels = Regsosek2022kEnum::pilihanBagian2()['207'];

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
