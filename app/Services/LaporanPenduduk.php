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

namespace App\Services;

use App\Enums\AgamaEnum;
use App\Enums\AsuransiEnum;
use App\Enums\CacatEnum;
use App\Enums\CaraKBEnum;
use App\Enums\GolonganDarahEnum;
use App\Enums\HamilEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\PekerjaanEnum;
use App\Enums\PendidikanKKEnum;
use App\Enums\PendidikanSedangEnum;
use App\Enums\SakitMenahunEnum;
use App\Enums\SHDKEnum;
use App\Enums\Statistik\StatistikJenisBantuanEnum;
use App\Enums\Statistik\StatistikKeluargaEnum;
use App\Enums\Statistik\StatistikPendudukEnum;
use App\Enums\Statistik\StatistikRtmEnum;
use App\Enums\StatusKawinEnum;
use App\Enums\StatusPendudukEnum;
use App\Enums\StatusRekamEnum;
use App\Enums\WargaNegaraEnum;
use App\Models\Bantuan;
use Closure;
use Illuminate\Support\Facades\DB;

class LaporanPenduduk
{
    private $lap;
    private $filter;
    private $paramCetak;

    public static function judulStatistik($lap)
    {
        if ($bantuan = Bantuan::whereSlug($lap)->first()) {
            return $bantuan->nama;
        }

        $list_judul = StatistikPendudukEnum::allKeyLabel() + StatistikKeluargaEnum::allKeyLabel() + StatistikRtmEnum::allKeyLabel() + StatistikJenisBantuanEnum::allKeyLabel();

        return $list_judul[$lap];
    }

    public static function menuLabel()
    {
        return [
            'Penduduk'        => ['data' => StatistikPendudukEnum::allKeyLabel(), 'kategori' => 'penduduk'],
            'Keluarga'        => ['data' => StatistikKeluargaEnum::allKeyLabel(), 'kategori' => 'keluarga'],
            'RTM'             => ['data' => StatistikRtmEnum::allKeyLabel(), 'kategori' => 'penduduk'],
            'Program Bantuan' => ['data' => StatistikJenisBantuanEnum::allKeyLabel() + Bantuan::pluck('nama', 'slug')->toArray(), 'kategori' => 'bantuan'],
        ];
    }

    public function listData($lap = 0, $filter = [], $paramCetak = [])
    {
        $this->lap        = $lap;
        $this->filter     = $filter;
        $this->paramCetak = $paramCetak;

        $judul_jumlah = 'JUMLAH';
        $judul_belum  = 'BELUM MENGISI';

        $data  = $this->select_per_kategori();
        $semua = $this->get_data_jml();
        $semua = $this->persentase_semua($semua);
        $total = $this->hitung_total($data);

        // Statistik tanpa tabel referensi
        if (in_array($lap, ['bdt', 'dtsen'])) {
            $data = [];
        }

        $data[] = $this->baris_jumlah($total, $judul_jumlah);
        $data[] = $this->baris_belum($semua, $total, $judul_belum);
        $this->hitung_persentase($data, $semua);

        return $data;
    }

    protected function hitung_persentase(&$data, $semua)
    {
        // Hitung semua presentase
        $counter = count($data);

        // Hitung semua presentase
        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['persen']  = persen2($data[$i]['jumlah'], $semua['jumlah']);
            $data[$i]['persen1'] = persen2($data[$i]['laki'], $semua['jumlah']);
            $data[$i]['persen2'] = persen2($data[$i]['perempuan'], $semua['jumlah']);
        }

        $data['total'] = $semua;
    }

    protected function baris_jumlah($total, $nama)
    {
        // Isi Total
        return [
            'no'        => '',
            'id'        => JUMLAH,
            'nama'      => $nama,
            'jumlah'    => $total['jumlah'],
            'perempuan' => $total['perempuan'],
            'laki'      => $total['laki'],
        ];
    }

    protected function baris_belum($semua, $total, $nama)
    {
        // Isi data jml belum mengisi
        $baris_belum = [
            'no'        => '',
            'id'        => BELUM_MENGISI,
            'nama'      => $nama,
            'jumlah'    => $semua['jumlah'] - $total['jumlah'],
            'perempuan' => $semua['perempuan'] - $total['perempuan'],
            'laki'      => $semua['laki'] - $total['laki'],
        ];
        if (isset($total['jumlah_nonaktif'])) {
            $baris_belum['jumlah'] += $total['jumlah_nonaktif'];
            $baris_belum['perempuan'] += $total['jumlah_nonaktif_perempuan'];
            $baris_belum['laki'] += $total['jumlah_nonaktif_laki'];
        }

        return $baris_belum;
    }

    protected function get_data_jml()
    {
        $lap          = $this->lap;
        $status_dasar = '1';

        //Siapkan data baris rekaps
        if ((int) $lap == 18) {
            $semua = $this->data_jml_semua_penduduk()
                ->whereRaw("((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) >= 17
                 OR (status_kawin IS NOT NULL AND status_kawin <> 1))
                AND (ktp_el != '3' OR ktp_el IS NULL)")
                ->get()
                ->toArray();

        } elseif ($lap == 'kia') {
            $semua = $this->data_jml_semua_penduduk()->whereRaw("((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)<=17)")->get()->toArray();
        } elseif (in_array($lap, ['kelas_sosial', 'bantuan_keluarga'])) {
            $semua = $this->data_jml_semua_keluarga();
        } elseif (in_array($lap, ['bdt', 'dtsen'])) {
            $semua = $this->data_jml_semua_rtm();
        } else {
            $query = $this->data_jml_semua_penduduk($status_dasar);
            if ($lap == 'hamil') {
                $semua = $query->where('b.sex', 2);
            } elseif ($lap == 'buku-nikah') {
                $semua = $query->where('b.status_kawin', '!=', 1);
            } elseif ($lap == 'akta-kematian') {
                $status_dasar = '2';
                $semua        = $this->data_jml_semua_penduduk($status_dasar);
            } else {
                $semua = $query;
            }

            return $semua->get()->toArray()[0];
        }

        return $semua[0];
    }

    protected function data_jml_semua_keluarga()
    {
        $dusun = $this->filter['dusun'];
        $rw    = $this->filter['rw'];
        $rt    = $this->filter['rt'];

        return DB::table('keluarga_aktif as k')
            ->selectRaw('COUNT(k.id) as jumlah')
            ->selectRaw('COUNT(CASE WHEN p.sex = 1 THEN p.id END) as laki')
            ->selectRaw('COUNT(CASE WHEN p.sex = 2 THEN p.id END) as perempuan')
            ->leftJoin('tweb_penduduk as p', 'p.id', '=', 'k.nik_kepala')
            ->leftJoin('tweb_wil_clusterdesa as a', 'p.id_cluster', '=', 'a.id')
            ->when($dusun, static function ($sq) use ($dusun, $rw, $rt) {
                $sq->where(static function ($subquery) use ($dusun, $rw, $rt) {
                    $subquery->where('a.dusun', $dusun);
                    if ($rw) {
                        $subquery->where('a.rw', $rw);
                        if ($rt) {
                            $subquery->where('a.rt', $rt);
                        }
                    }
                });
            })->where('k.config_id', identitas('id'))
            ->get()
            ->toArray();
    }

    protected function data_jml_semua_rtm()
    {
        $dusun = $this->filter['dusun'];
        $rw    = $this->filter['rw'];
        $rt    = $this->filter['rt'];

        return DB::table('tweb_rtm as r')
            ->selectRaw('COUNT(r.id) as jumlah')
            ->selectRaw('COUNT(CASE WHEN p.sex = 1 THEN p.id END) as laki')
            ->selectRaw('COUNT(CASE WHEN p.sex = 2 THEN p.id END) as perempuan')
            ->leftJoin('tweb_penduduk as p', 'p.id', '=', 'r.nik_kepala') // TODO: Ganti kolom no_kk jadi no_rtm
            ->leftJoin('tweb_wil_clusterdesa as a', 'p.id_cluster', '=', 'a.id')
            ->whereNotNull('r.nik_kepala')
            ->when($dusun, static function ($sq) use ($dusun, $rw, $rt) {
                $sq->where(static function ($subquery) use ($dusun, $rw, $rt) {
                    $subquery->where('a.dusun', $dusun);
                    if ($rw) {
                        $subquery->where('a.rw', $rw);
                        if ($rt) {
                            $subquery->where('a.rt', $rt);
                        }
                    }
                });
            })->where('r.config_id', identitas('id'))
            ->get()
            ->toArray();
    }

    protected function persentase_semua($semua)
    {
        $semua = (array) $semua;
        // Hitung persentase
        $semua['no']      = '';
        $semua['id']      = TOTAL;
        $semua['nama']    = 'TOTAL';
        $semua['persen']  = persen2(($semua['laki'] + $semua['perempuan']), $semua['jumlah']);
        $semua['persen1'] = persen2($semua['laki'], $semua['jumlah']);
        $semua['persen2'] = persen2($semua['perempuan'], $semua['jumlah']);

        return $semua;
    }

    protected function data_jml_semua_penduduk($status_dasar = '1')
    {
        $idCluster = $this->filter['idCluster'];
        $query     = DB::table('tweb_penduduk as b')
            ->selectRaw('COUNT(b.id) as jumlah')
            ->selectRaw('COUNT(CASE WHEN b.sex = 1 THEN b.id END) as laki')
            ->selectRaw('COUNT(CASE WHEN b.sex = 2 THEN b.id END) as perempuan')
            ->leftJoin('tweb_wil_clusterdesa as a', 'b.id_cluster', '=', 'a.id')
            ->when($idCluster, static function ($sq) use ($idCluster) {
                $sq->whereIn('a.id', $idCluster);
            })
            ->where('b.config_id', identitas('id'))
            ->where('b.status_dasar', $status_dasar);

        return $query;
    }

    protected function hitung_total(&$data)
    {
        $total['no']        = '';
        $total['id']        = TOTAL;
        $total['jumlah']    = 0;
        $total['laki']      = 0;
        $total['perempuan'] = 0;

        $data = collect($data)->map(static function ($item) use (&$total) {
            $item = (array) $item;
            $total['jumlah'] += $item['jumlah'];
            $total['laki'] += $item['laki'];
            $total['perempuan'] += $item['perempuan'];

            return $item;
        })->toArray();

        return $total;
    }

    protected function select_per_kategori()
    {
        $lap = $this->lap;

        $statistik_penduduk = [];

        switch ("{$lap}") {
            // Pendidikan KK
            case '0':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'pendidikan_kk_id',
                    PendidikanKKEnum::all()
                );
                break;

            // Pekerjaan
            case '1':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'pekerjaan_id',
                    PekerjaanEnum::all()
                );
                break;

            // Status Kawin
            case '2':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'status_kawin',
                    StatusKawinEnum::all()
                );
                break;

            // Hubungan KK (SHDK)
            case 'hubungan_kk':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'kk_level',
                    SHDKEnum::all()
                );
                break;

            // Warga Negara
            case '5':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'warganegara_id',
                    WargaNegaraEnum::all()
                );
                break;

            // Penduduk Status
            case '6':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'status',
                    StatusPendudukEnum::all()
                );
                break;

            // Golongan Darah
            case '7':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'golongan_darah_id',
                    GolonganDarahEnum::all()
                );
                break;

            // Sakit Menahun
            case '10':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'sakit_menahun_id',
                    SakitMenahunEnum::all()
                );
                break;

            // Cara KB
            case '16':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'cara_kb_id',
                    CaraKBEnum::all()
                );
                break;

            // Cacat
            case '9':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'cacat_id',
                    CacatEnum::all()
                );
                break;

            // Pendidikan Sedang
            case '14':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'pendidikan_sedang_id',
                    PendidikanSedangEnum::all()
                );
                break;

            // Agama
            case '3':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'agama_id',
                    AgamaEnum::all()
                );
                break;

            // Jenis Kelamin
            case '4':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'sex',
                    JenisKelaminEnum::all()
                );
                break;

            // Asuransi
            case '19':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'id_asuransi',
                    AsuransiEnum::all()
                );
                break;

            // Kehamilan
            case 'hamil':
                return $this->select_jml_penduduk_per_kategori_enum(
                    'hamil',
                    HamilEnum::all(),
                    static fn ($query) => $query->where('p.sex', JenisKelaminEnum::PEREMPUAN)
                );
                break;

            // Umur rentang
            case '13':
                $where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai";
                $jml   = $this->select_jml($where);

                return DB::table('tweb_penduduk_umur as u')
                    ->select('u.*')
                    ->selectRaw(DB::raw('(' . $jml['str_jml_penduduk'] . ') as jumlah'))
                    ->selectRaw(DB::raw('(' . $jml['str_jml_laki'] . ') as laki'))
                    ->selectRaw(DB::raw('(' . $jml['str_jml_perempuan'] . ') as perempuan'))
                    ->where('u.status', '1')
                    ->where('u.config_id', identitas('id'))
                    ->when($this->paramCetak, static function ($query, $param) {
                        $query->take($param['length'])->skip($param['start']);
                    })
                    ->get();
                break;

            case 'akta-kematian':
                // Akta Kematian
                $where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) >= u.dari
    AND (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) <= u.sampai
    AND l.akta_mati IS NOT NULL
    AND l.akta_mati != ''
    AND l.file_akta_mati IS NOT NULL ";

                $jml = $this->select_jml($where, '2');

                return DB::table('tweb_penduduk_umur as u')
                    ->select('u.*')
                    ->selectRaw("CONCAT('UMUR ', u.dari, ' S/D ', u.sampai, ' TAHUN') as nama")
                    ->selectRaw(DB::raw('(' . $jml['str_jml_penduduk'] . ') as jumlah'))
                    ->selectRaw(DB::raw('(' . $jml['str_jml_laki'] . ') as laki'))
                    ->selectRaw(DB::raw('(' . $jml['str_jml_perempuan'] . ') as perempuan'))
                    ->where('u.status', '1')
                    ->where('u.config_id', identitas('id'))
                // kondisi param datatable
                    ->when($this->paramCetak, static function ($query, $param) {
                        $query->take($param['length'])->skip($param['start']);
                    })
                    ->get();
                break;

                // KELUARGA
            case 'kelas_sosial':
                // Kelas Sosial
                return DB::table('tweb_keluarga_sejahtera as u')
                    ->select('u.*')
                    ->selectRaw('COUNT(k.id) as jumlah')
                    ->selectRaw('COUNT(CASE WHEN k.kelas_sosial = u.id AND p.sex = 1 THEN p.id END) AS laki')
                    ->selectRaw('COUNT(CASE WHEN k.kelas_sosial = u.id AND p.sex = 2 THEN p.id END) AS perempuan')
                    ->leftJoin('keluarga_aktif as k', static function ($join) {
                        $join->on('k.kelas_sosial', '=', 'u.id')
                            ->where('k.config_id', '=', identitas('id'));
                    })
                    ->leftJoin('tweb_penduduk as p', 'p.id', '=', 'k.nik_kepala')
                    ->groupBy(['u.id', 'u.nama'])
                    ->get();
                break;

                // RTM
            case 'bdt':
                // BDT
                return DB::table('tweb_rtm as u')
                    ->selectRaw('COUNT(u.id) as jumlah')
                    ->selectRaw('COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki')
                    ->selectRaw('COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan')
                    ->join('tweb_penduduk as p', 'p.id', '=', 'u.nik_kepala')
                    ->whereNotNull('u.bdt')
                    ->where('u.config_id', identitas('id'))
                    ->groupBy('u.id')
                    ->get();
                break;

            case 'dtsen':
                // DTSEN
                return DB::table('tweb_rtm as u')
                    ->selectRaw('COUNT(u.id) as jumlah')
                    ->selectRaw('COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki')
                    ->selectRaw('COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan')
                    ->join('tweb_penduduk as p', 'p.id', '=', 'u.nik_kepala')
                    ->where('u.terdaftar_dtks', '!=', '0')
                    ->where('u.config_id', identitas('id'))
                    ->groupBy('u.id')
                    ->get();
                break;

                // BANTUAN
            case 'bantuan_penduduk':
                $sql = 'SELECT u.*,
                    (SELECT COUNT(kartu_nik) FROM program_peserta WHERE program_id = u.id AND config_id = u.config_id) AS jumlah,
                    (SELECT COUNT(k.kartu_nik) FROM program_peserta k INNER JOIN tweb_penduduk p ON k.kartu_nik=p.nik WHERE program_id = u.id AND p.sex = 1 AND config_id = u.config_id) AS laki,
                    (SELECT COUNT(k.kartu_nik) FROM program_peserta k INNER JOIN tweb_penduduk p ON k.kartu_nik=p.nik WHERE program_id = u.id AND p.sex = 2 AND config_id = u.config_id) AS perempuan
                    FROM program u WHERE (u.config_id = ' . identitas('id') . ' OR u.config_id IS NULL)';
                break;

            // PENDUDUK
            case 'buku-nikah':
                // kepemilikan buku nikah dengan enum StatusKawinEnum
                $data = $this->select_jml_penduduk_per_kategori_enum(
                    'status_kawin',
                    StatusKawinEnum::all()
                );

                return $data->filter(static function ($row) {
                    return ! empty($row['jumlah'])
                        && $row['id'] != StatusKawinEnum::BELUMKAWIN;
                })->values();
                break;

            case 'kia':
                // Kepemilikan kia
                $where = "((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)<=17) AND u.status_rekam = status_rekam AND b.ktp_el != '2'";
                $jml   = $this->select_jml($where);

                return DB::table('tweb_status_ktp as u')
                    ->select('u.*')
                    ->selectRaw(DB::raw('(' . $jml['str_jml_penduduk'] . ') as jumlah'))
                    ->selectRaw(DB::raw('(' . $jml['str_jml_laki'] . ') as laki'))
                    ->selectRaw(DB::raw('(' . $jml['str_jml_perempuan'] . ') as perempuan'))
                    ->get();
                break;

            case 'covid':
                // Covid
                return DB::table('ref_status_covid as u')
                    ->select('u.*')
                    ->selectRaw('COUNT(k.id) as jumlah')
                    ->selectRaw('COUNT(CASE WHEN k.status_covid = u.id AND p.sex = 1 THEN k.id_terdata END) AS laki')
                    ->selectRaw('COUNT(CASE WHEN k.status_covid = u.id AND p.sex = 2 THEN k.id_terdata END) AS perempuan')
                    ->leftJoin('covid19_pemudik as k', static function ($join) {
                        $join->on('k.status_covid', '=', 'u.id')
                            ->where('k.config_id', '=', identitas('id'));
                    })
                    ->leftJoin('tweb_penduduk as p', 'p.id', '=', 'k.id_terdata')
                    ->groupBy('u.id', 'u.nama')
                    ->get();
                break;

                case 'adat':
                    // Adat
                    $idCluster = $this->filter['idCluster'];

                    $query = DB::table('penduduk_hidup as u')
                        ->select('u.adat as nama', 'u.adat as id')
                        ->selectRaw('COUNT(u.sex) as jumlah')
                        ->selectRaw('COUNT(CASE WHEN u.sex = 1 THEN 1 END) as laki')
                        ->selectRaw('COUNT(CASE WHEN u.sex = 2 THEN 1 END) as perempuan')
                        ->leftJoin('tweb_wil_clusterdesa as a', 'u.id_cluster', '=', 'a.id')
                        ->whereNotNull('u.adat')
                        ->where('u.adat', '!=', null)
                        ->where('u.adat', '!=', '')
                        ->where('u.config_id', identitas('id'))
                        ->groupBy('u.adat')
                        ->when($idCluster, static function ($sq) use ($idCluster) {
                            $sq->whereIn('a.id', $idCluster);
                        })
                        ->get();

                    return $query;

                    break;

            case 'suku':
                // Suku
                $idCluster = $this->filter['idCluster'];

                $query = DB::table('penduduk_hidup as u')
                    ->select('u.suku as nama', 'u.suku as id')
                    ->selectRaw('COUNT(u.sex) as jumlah')
                    ->selectRaw('COUNT(CASE WHEN u.sex = 1 THEN 1 END) as laki')
                    ->selectRaw('COUNT(CASE WHEN u.sex = 2 THEN 1 END) as perempuan')
                    ->leftJoin('tweb_wil_clusterdesa as a', 'u.id_cluster', '=', 'a.id')
                    ->whereNotNull('u.suku')
                    ->where('u.suku', '!=', '')
                    ->where('u.config_id', identitas('id'))
                    ->groupBy('u.suku')
                    ->when($idCluster, static function ($sq) use ($idCluster) {
                        $sq->whereIn('a.id', $idCluster);
                    })
                    ->get();

                return $query;

                break;

            case 'marga':
                // Marga
                $idCluster = $this->filter['idCluster'];

                $query = DB::table('penduduk_hidup as u')
                    ->select('u.marga as nama', 'u.marga as id')
                    ->selectRaw('COUNT(u.sex) as jumlah')
                    ->selectRaw('COUNT(CASE WHEN u.sex = 1 THEN 1 END) as laki')
                    ->selectRaw('COUNT(CASE WHEN u.sex = 2 THEN 1 END) as perempuan')
                    ->leftJoin('tweb_wil_clusterdesa as a', 'u.id_cluster', '=', 'a.id')
                    ->whereNotNull('u.marga')
                    ->where('u.marga', '!=', null)
                    ->where('u.marga', '!=', '')
                    ->where('u.config_id', identitas('id'))
                    ->groupBy('u.marga')
                    ->when($idCluster, static function ($sq) use ($idCluster) {
                        $sq->whereIn('a.id', $idCluster);
                    })
                    ->get();

                return $query;

                break;

            case 'pekerja_migran':
                // Pekerja Migran
                $idCluster = $this->filter['idCluster'];

                $query = DB::table('penduduk_hidup as u')
                    ->select('u.pekerja_migran as nama', 'u.pekerja_migran as id')
                    ->selectRaw('COUNT(u.sex) as jumlah')
                    ->selectRaw('COUNT(CASE WHEN u.sex = 1 THEN 1 END) as laki')
                    ->selectRaw('COUNT(CASE WHEN u.sex = 2 THEN 1 END) as perempuan')
                    ->leftJoin('tweb_wil_clusterdesa as a', 'u.id_cluster', '=', 'a.id')
                    ->whereNotNull('u.pekerja_migran')
                    ->where('u.pekerja_migran', '!=', '')
                    ->where('u.config_id', identitas('id'))
                    ->groupBy('u.pekerja_migran')
                    ->when($idCluster, static function ($sq) use ($idCluster) {
                        $sq->whereIn('a.id', $idCluster);
                    })
                    ->get();

                return $query;

                break;

            case 'bpjs-tenagakerja':
                // BPJS Tenaga Kerja
                $data = $this->select_jml_penduduk_per_kategori('pekerjaan_id', 'tweb_penduduk_pekerjaan');

                return $data->where('p.bpjs_ketenagakerjaan', '!=', null)
                    ->where('p.bpjs_ketenagakerjaan', '!=', '')
                    ->get();

                break;

            case 'status-asuransi-kesehatan':
                $idCluster = $this->filter['idCluster'];

                return DB::table('tweb_penduduk as u')
                    ->select('u.status_asuransi as id')
                    ->selectRaw("
                        case
                            when status_asuransi = 1 then 'Aktif'
                            when status_asuransi = 0 then 'Tidak Aktif'
                        end as nama
                    ")
                    ->selectRaw('count(u.sex) as jumlah')
                    ->selectRaw('count(CASE when u.sex = 1 then 1 end) as laki')
                    ->selectRaw('count(CASE when u.sex = 2 then 1 end) as perempuan')
                    ->leftJoin('tweb_wil_clusterdesa as a', 'u.id_cluster', '=', 'a.id')
                    ->whereNotNull('u.status_asuransi')
                    ->where('u.status_dasar', '1')
                    ->where('u.config_id', identitas('id'))
                    ->when($idCluster, static function ($sq) use ($idCluster) {
                        $sq->whereIn('a.id', $idCluster);
                    })
                    ->groupBy('u.status_asuransi')
                    ->get();

                break;

            case '15':
                // Umur kategori
                $where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai";
                $jml   = $this->select_jml($where);

                return DB::table('tweb_penduduk_umur as u')
                    ->select('u.*')
                    // ->selectRaw("CONCAT(u.nama, (', u.dari, ' - ', u.sampai, ')') as nama")
                    ->selectRaw("CONCAT(u.nama, ' (', u.dari, ' - ', u.sampai, ')') as nama")
                    ->selectRaw(DB::raw('(' . $jml['str_jml_penduduk'] . ') as jumlah'))
                    ->selectRaw(DB::raw('(' . $jml['str_jml_laki'] . ') as laki'))
                    ->selectRaw(DB::raw('(' . $jml['str_jml_perempuan'] . ') as perempuan'))
                    ->where('u.status', '0')
                    ->where('u.config_id', identitas('id'))
                    // kondisi param datatable
                    ->when($this->paramCetak, static function ($query, $param) {
                        $query->take($param['length'])->skip($param['start']);
                    })
                    ->get();

                break;

            case '17':
                // Akta kelahiran
                $where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai AND akta_lahir <> '' ";
                $jml   = $this->select_jml($where);

                return DB::table('tweb_penduduk_umur as u')
                    ->select('u.*')
                    ->selectRaw("CONCAT('UMUR ', u.dari, ' S/D ', u.sampai, ' TAHUN') as nama")
                    ->selectRaw(DB::raw('(' . $jml['str_jml_penduduk'] . ') as jumlah'))
                    ->selectRaw(DB::raw('(' . $jml['str_jml_laki'] . ') as laki'))
                    ->selectRaw(DB::raw('(' . $jml['str_jml_perempuan'] . ') as perempuan'))
                    ->where('u.status', '1')
                    ->where('u.config_id', identitas('id'))
                // kondisi param datatable
                    ->when($this->paramCetak, static function ($query, $param) {
                        $query->take($param['length'])->skip($param['start']);
                    })
                    ->get();

                break;

            case '18':
                // Kepemilikan ktp
                $where = "(
              (DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0) >= 17
              OR (status_kawin IS NOT NULL AND status_kawin <> 1)
          )
          AND u.status_rekam = status_rekam
          AND b.ktp_el != '" . StatusRekamEnum::KIA . "'";

                $jml = $this->select_jml($where);

                return DB::table('tweb_status_ktp as u')
                    ->select('u.*')
                    ->selectRaw(DB::raw('(' . $jml['str_jml_penduduk'] . ') as jumlah'))
                    ->selectRaw(DB::raw('(' . $jml['str_jml_laki'] . ') as laki'))
                    ->selectRaw(DB::raw('(' . $jml['str_jml_perempuan'] . ') as perempuan'))
                // kondisi param datatable
                    ->when($this->paramCetak, static function ($query, $param) {
                        $query->take($param['length'])->skip($param['start']);
                    })
                    ->get();
                break;

            default:
                return $this->select_jml_penduduk_per_kategori($statistik_penduduk['0']['id_referensi'], $statistik_penduduk['0']['tabel_referensi'])->get();
        }

        return true;
    }

    private function str_jml_penduduk(string $where, string $sex = '', string $status_dasar = '1')
    {
        $query = DB::table('tweb_penduduk as b')
            ->selectRaw('COUNT(b.id)')
            ->leftJoin('tweb_wil_clusterdesa as a', 'b.id_cluster', '=', 'a.id');

        if ($sex !== '' && $sex !== '0') {
            $query->where('b.sex', $sex);
        }

        if ($status_dasar !== '1') {
            $query->leftJoin('log_penduduk as l', 'l.id_pend', '=', 'b.id');
        }

        $idCluster = $this->filter['idCluster'];

        $query->when($idCluster, static function ($sq) use ($idCluster) {
            $sq->whereIn('a.id', $idCluster);
        });

        return $query
            ->where('b.status_dasar', $status_dasar)
            ->where('b.config_id', identitas('id'))
            ->whereRaw($where)
            ->toRawSql();
    }

    private function select_jml(string $where, string $status_dasar = '1')
    {
        $str_jml_penduduk  = $this->str_jml_penduduk($where, '', $status_dasar);
        $str_jml_laki      = $this->str_jml_penduduk($where, '1', $status_dasar);
        $str_jml_perempuan = $this->str_jml_penduduk($where, '2', $status_dasar);

        return [
            'str_jml_penduduk'  => $str_jml_penduduk,
            'str_jml_laki'      => $str_jml_laki,
            'str_jml_perempuan' => $str_jml_perempuan,
        ];
    }

    private function select_jml_penduduk_per_kategori(string $id_referensi, string $tabel_referensi)
    {
        $query = DB::table("{$tabel_referensi} as u")
            ->select('u.*')
            ->selectRaw('COUNT(p.id) AS jumlah')
            ->selectRaw('COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki')
            ->selectRaw('COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan')
            ->leftJoin('penduduk_hidup as p', static function ($join) use ($id_referensi) {
                $join->on('u.id', '=', "p.{$id_referensi}")
                    ->where('p.config_id', '=', identitas('id'));
            })
            ->leftJoin('tweb_wil_clusterdesa as a', 'p.id_cluster', '=', 'a.id');

        $idCluster = $this->filter['idCluster'];

        $query->when($idCluster, static function ($sq) use ($idCluster) {
            $sq->whereIn('a.id', $idCluster);
        });

        // dapatkan semua kolom di table referensi
        $allColumns = DB::getSchemaBuilder()->getColumnListing($tabel_referensi);

        return $query->groupBy($allColumns);
    }

    private function select_jml_penduduk_per_kategori_enum(string $id_referensi, array $enum_ref, $where = null)
    {
        $query = DB::table('penduduk_hidup as p')
            ->select("p.{$id_referensi}")
            ->selectRaw('COUNT(p.id) AS jumlah')
            ->selectRaw('COUNT(CASE WHEN p.sex = 1 THEN p.id END) AS laki')
            ->selectRaw('COUNT(CASE WHEN p.sex = 2 THEN p.id END) AS perempuan')
            ->where('p.config_id', identitas('id'));

        if ($where instanceof Closure) {
            $query->where($where);
        } elseif (is_string($where)) {
            $query->whereRaw($where);
        }

        $idCluster = $this->filter['idCluster'] ?? null;

        if ($idCluster) {
            $query->leftJoin('tweb_wil_clusterdesa as a', 'p.id_cluster', '=', 'a.id')
                ->whereIn('a.id', $idCluster);
        }

        $rows = $query->groupBy("p.{$id_referensi}")->get()->keyBy($id_referensi);

        $result = [];

        foreach ($enum_ref as $id => $label) {
            $jumlah    = $rows[$id]->jumlah ?? 0;
            $laki      = $rows[$id]->laki ?? 0;
            $perempuan = $rows[$id]->perempuan ?? 0;

            $result[] = [
                'id'        => $id,
                'nama'      => $label,
                'jumlah'    => $jumlah,
                'laki'      => $laki,
                'perempuan' => $perempuan,
            ];
        }

        return collect($result);
    }
}
