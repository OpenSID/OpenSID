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

namespace App\Services;

use App\Enums\JenisKelaminEnum;
use App\Enums\PendidikanSedangEnum;
use App\Enums\Statistik\StatistikJenisBantuanEnum;
use App\Enums\Statistik\StatistikKeluargaEnum;
use App\Enums\Statistik\StatistikPendudukEnum;
use App\Enums\Statistik\StatistikRtmEnum;
use App\Models\Bantuan;
use Illuminate\Support\Facades\DB;

class LaporanPenduduk
{
    private $lap;
    private $tahun;
    private $filter;
    private $paramCetak;

    public function listData($lap = 0, $filter = [], $paramCetak = [])
    {
        $this->lap        = $lap;
        $this->filter     = $filter;
        $this->paramCetak = $paramCetak;

        $judul_jumlah = 'JUMLAH';
        $judul_belum  = 'BELUM MENGISI';

        $data = $this->select_per_kategori();

        $semua = $this->get_data_jml();
        $semua = $this->persentase_semua($semua);
        $total = $this->hitung_total($data);

        // Statistik tanpa tabel referensi
        if ($lap === 'bdt') {
            $data = [];
        }

        $data[] = $this->baris_jumlah($total, $judul_jumlah);
        $data[] = $this->baris_belum($semua, $total, $judul_belum);
        $this->hitung_persentase($data, $semua);

        if ($lap == '14') {
            $val              = collect($data);
            $pendidikanSedang = collect(PendidikanSedangEnum::all());

            $data = $pendidikanSedang->map(static function ($item, $key) use ($val) {
                $valItem = $val->where('id', $key)->first() ?? ['jumlah' => '0', 'laki' => '0', 'perempuan' => '0', 'persen' => '0%', 'persen1' => '0%', 'persen2' => '0%'];

                return [
                    'id'        => (string) $key,
                    'nama'      => $item,
                    'jumlah'    => $valItem['jumlah'],
                    'laki'      => $valItem['laki'],
                    'perempuan' => $valItem['perempuan'],
                    'no'        => $key,
                    'persen'    => $valItem['persen'],
                    'persen1'   => $valItem['persen1'],
                    'persen2'   => $valItem['persen2'],
                ];
            })
                ->merge($val->slice(-3))
                ->map(static function ($item, $key) {
                    $item['no'] = in_array($item['id'], [JUMLAH, BELUM_MENGISI, TOTAL]) ? '' : $key + 1;

                    return $item;
                })
                ->toArray();
        }

        return $data;
    }

    public static function judulStatistik($lap)
    {
        // Program bantuan berbentuk '50<program_id>'
        if ((int) $lap > 50) {
            $program_id = preg_replace('/^50/', '', $lap);

            return Bantuan::find($program_id)->nama;
        }

        $list_judul = StatistikPendudukEnum::allKeyLabel() + StatistikKeluargaEnum::allKeyLabel() + StatistikRtmEnum::allKeyLabel() + StatistikJenisBantuanEnum::allKeyLabel();

        return $list_judul[$lap];
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
            $semua = $this->data_jml_semua_penduduk()->whereRaw("((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1))")->get()->toArray();
        } elseif ($lap == 'kia') {
            $semua = $this->data_jml_semua_penduduk()->whereRaw("((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)<=17)")->get()->toArray();
        } elseif (in_array($lap, ['kelas_sosial', 'bantuan_keluarga'])) {
            $semua = $this->data_jml_semua_keluarga();
        } elseif ($lap == 'bdt') {
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

    protected function select_per_kategori()
    {
        $lap = $this->lap;

        // Bagian Penduduk
        $statistik_penduduk = [
            '0'           => ['id_referensi' => 'pendidikan_kk_id', 'tabel_referensi' => 'tweb_penduduk_pendidikan_kk'],
            '1'           => ['id_referensi' => 'pekerjaan_id', 'tabel_referensi' => 'tweb_penduduk_pekerjaan'],
            '2'           => ['id_referensi' => 'status_kawin', 'tabel_referensi' => 'tweb_penduduk_kawin'],
            '3'           => ['id_referensi' => 'agama_id', 'tabel_referensi' => 'tweb_penduduk_agama'],
            '4'           => ['id_referensi' => 'sex', 'tabel_referensi' => 'tweb_penduduk_sex'],
            'hubungan_kk' => ['id_referensi' => 'kk_level', 'tabel_referensi' => 'tweb_penduduk_hubungan'],
            '5'           => ['id_referensi' => 'warganegara_id', 'tabel_referensi' => 'tweb_penduduk_warganegara'],
            '6'           => ['id_referensi' => 'status', 'tabel_referensi' => 'tweb_penduduk_status'],
            '7'           => ['id_referensi' => 'golongan_darah_id', 'tabel_referensi' => 'tweb_golongan_darah'],
            '9'           => ['id_referensi' => 'cacat_id', 'tabel_referensi' => 'tweb_cacat'],
            '10'          => ['id_referensi' => 'sakit_menahun_id', 'tabel_referensi' => 'tweb_sakit_menahun'],
            // '14'          => ['id_referensi' => 'pendidikan_sedang_id', 'tabel_referensi' => 'tweb_penduduk_pendidikan'],
            '16' => ['id_referensi' => 'cara_kb_id', 'tabel_referensi' => 'tweb_cara_kb'],
            '19' => ['id_referensi' => 'id_asuransi', 'tabel_referensi' => 'tweb_penduduk_asuransi'],
        ];

        switch ("{$lap}") {

            case 'hamil':
                // Kehamilan
                $data = $this->select_jml_penduduk_per_kategori('hamil', 'ref_penduduk_hamil');

                return $data->where('p.sex', 2)->get();
                break;

            case '13':
                // Umur rentang
                $where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai";
                $jml   = $this->select_jml($where);

                return DB::table('tweb_penduduk_umur as u')
                    ->select('u.*')
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

                // with reference enum
            case '14':
                // Pendidikan Sedang
                return DB::table('penduduk_hidup as u')
                    ->select('u.pendidikan_sedang_id as id', 'u.pendidikan_sedang_id as nama')
                    ->selectRaw('COUNT(u.sex) as jumlah')
                    ->selectRaw('COUNT(CASE WHEN u.sex = 1 THEN 1 END) as laki')
                    ->selectRaw('COUNT(CASE WHEN u.sex = 2 THEN 1 END) as perempuan')
                    ->whereNotNull('u.pendidikan_sedang_id')
                    ->where('u.pendidikan_sedang_id', '!=', '')
                    ->where('u.config_id', identitas('id'))
                    ->groupBy('u.pendidikan_sedang_id')
                    ->get();

                break;

            case 'akta-kematian':
                // Akta Kematian
                $where = "(DATE_FORMAT(FROM_DAYS(TO_DAYS( NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0)>=u.dari AND (DATE_FORMAT(FROM_DAYS( TO_DAYS(NOW()) - TO_DAYS(tanggallahir)) , '%Y')+0) <= u.sampai AND l.akta_mati IS NOT NULL ";
                $jml   = $this->select_jml($where, '2');

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

                // BANTUAN
            case 'bantuan_penduduk':
                $sql = 'SELECT u.*,
                    (SELECT COUNT(kartu_nik) FROM program_peserta WHERE program_id = u.id AND config_id = u.config_id) AS jumlah,
                    (SELECT COUNT(k.kartu_nik) FROM program_peserta k INNER JOIN tweb_penduduk p ON k.kartu_nik=p.nik WHERE program_id = u.id AND p.sex = 1 AND config_id = u.config_id) AS laki,
                    (SELECT COUNT(k.kartu_nik) FROM program_peserta k INNER JOIN tweb_penduduk p ON k.kartu_nik=p.nik WHERE program_id = u.id AND p.sex = 2 AND config_id = u.config_id) AS perempuan
                    FROM program u WHERE (u.config_id = ' . identitas('id') . ' OR u.config_id IS NULL)';
                break;

                // PENDUDUK
            case 'hamil':
                // Kehamilan
                $data = $this->select_jml_penduduk_per_kategori('hamil', 'ref_penduduk_hamil');

                return $data->where('p.sex', JenisKelaminEnum::PEREMPUAN);
                break;

            case 'buku-nikah':
                // kepemilikan buku nikah
                $data = $this->select_jml_penduduk_per_kategori('status_kawin', 'tweb_penduduk_kawin');

                return $data->where('p.akta_perkawinan', '!=', null)
                    ->where('p.akta_perkawinan', '!=', '')
                    ->where('p.status_kawin', '!=', 1)
                    ->get();
                break;

            case 'kia':
                // Kepemilikan kia
                $where = "((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)<=17) AND u.status_rekam = status_rekam AND b.ktp_el = '3'";
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

            case in_array($lap, array_keys($statistik_penduduk)):
                // Dengan tabel referensi
                return $this->select_jml_penduduk_per_kategori($statistik_penduduk["{$lap}"]['id_referensi'], $statistik_penduduk["{$lap}"]['tabel_referensi'])->get();
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
                $where = "((DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(tanggallahir)), '%Y')+0)>=17 OR (status_kawin IS NOT NULL AND status_kawin <> 1)) AND u.status_rekam = status_rekam AND b.ktp_el != '3'";

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

    public static function menuLabel()
    {
        return [
            'Penduduk'        => ['data' => StatistikPendudukEnum::allKeyLabel(), 'kategori' => 'penduduk'],
            'Keluarga'        => ['data' => StatistikKeluargaEnum::allKeyLabel(), 'kategori' => 'keluarga'],
            'RTM'             => ['data' => StatistikRtmEnum::allKeyLabel(), 'kategori' => 'penduduk'],
            'Program Bantuan' => ['data' => StatistikJenisBantuanEnum::allKeyLabel() + Bantuan::pluck('nama', 'id')->toArray(), 'kategori' => 'bantuan'],
        ];
    }
}
