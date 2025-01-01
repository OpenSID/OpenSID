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

namespace Modules\Analisis\Libraries;

use App\Enums\AnalisisRefSubjekEnum;
use App\Enums\JenisKelaminEnum;
use App\Models\Config;
use App\Models\Kelompok;
use App\Models\KeluargaAktif;
use App\Models\LogPenduduk;
use App\Models\PendudukHidup;
use App\Models\Rtm;
use App\Models\Wilayah;
use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisParameter;
use Modules\Analisis\Models\AnalisisRespon;
use Modules\Analisis\Models\AnalisisResponBukti;

class Analisis
{
    public static function judulSubjek($subjek_tipe): ?array
    {
        switch ($subjek_tipe) {
            case AnalisisRefSubjekEnum::PENDUDUK:
                $judul = [
                    'nama'  => 'Nama',
                    'nomor' => 'NIK',
                    'kolom' => [
                        ['data' => 'nid', 'name' => 'nik'],
                        ['data' => 'nama', 'name' => 'nama'],
                    ],
                ];
                break;

            case AnalisisRefSubjekEnum::KELUARGA:
                $judul = [
                    'nama'  => 'Kepala Keluarga',
                    'nomor' => 'Nomor KK',
                    'kolom' => [
                        ['data' => 'nid', 'name' => 'no_kk'],
                        ['data' => 'nama', 'name' => 'penduduk_hidup.nama'],
                    ],
                ];
                break;

            case AnalisisRefSubjekEnum::RUMAH_TANGGA:
                $judul = [
                    'nama'  => 'Kepala Rumah Tangga',
                    'nomor' => 'Nomor Rumah Tangga',
                    'kolom' => [
                        ['data' => 'nid', 'name' => 'tweb_rtm.no_kk'],
                        ['data' => 'nama', 'name' => 'penduduk_hidup.nama'],
                    ],
                ];
                break;

            case AnalisisRefSubjekEnum::KELOMPOK:
                $judul = [
                    'nama'  => 'Nama Kelompok',
                    'nomor' => 'ID Kelompok',
                    'kolom' => [
                        ['data' => 'nid', 'name' => 'kode'],
                        ['data' => 'nama', 'name' => 'nama'],
                    ],
                ];
                break;

            case AnalisisRefSubjekEnum::DESA:
                $desa  = ucwords(setting('sebutan_desa'));
                $judul = [
                    'nama'  => "Nama {$desa}",
                    'nomor' => "Kode {$desa}",
                    'kolom' => [
                        ['data' => 'nid', 'name' => 'kode_desa'],
                        ['data' => 'nama', 'name' => 'nama_desa'],
                    ],
                ];
                break;

            case AnalisisRefSubjekEnum::DUSUN:
                $dusun = ucwords(setting('sebutan_dusun'));
                $judul = [
                    'nama'  => "Nama {$dusun}",
                    'nomor' => $dusun,
                    'kolom' => [
                        ['data' => 'nid', 'name' => 'dusun'],
                        ['data' => 'nama', 'name' => 'dusun'],
                    ],
                ];
                break;

            case AnalisisRefSubjekEnum::RW:
                $judul = [
                    'nama'  => 'Nama ' . setting('sebutan_dusun') . '/RW',
                    'nomor' => 'RW',
                    'kolom' => [
                        ['data' => 'nid', 'name' => 'rw'],
                        ['data' => 'nama', 'name' => 'rw'],
                    ],
                ];
                break;

            case AnalisisRefSubjekEnum::RT:
                $judul = [
                    'nama'  => 'Nama ' . setting('sebutan_dusun') . '/RW/RT',
                    'nomor' => 'RT',
                    'kolom' => [
                        ['data' => 'nid', 'name' => 'rt'],
                        ['data' => 'nama', 'name' => 'rt'],
                    ],
                ];
                break;

            default:
                $judul = null;
        }

        return $judul;
    }

    public static function sumberData($subjek_tipe, $idCluster = [])
    {
        $sumber = null;
        $utama  = '';

        switch ($subjek_tipe) {
            case AnalisisRefSubjekEnum::PENDUDUK:
                $utama  = 'penduduk_hidup';
                $sumber = PendudukHidup::join('tweb_wil_clusterdesa', 'penduduk_hidup.id_cluster', '=', 'tweb_wil_clusterdesa.id')
                    ->join('tweb_keluarga', 'tweb_keluarga.id', '=', 'penduduk_hidup.id_kk')
                    ->when($idCluster, static fn ($q) => $q->whereIn('penduduk_hidup.id_cluster', $idCluster))
                    ->selectRaw('penduduk_hidup.id, nik AS nid, penduduk_hidup.nama, tweb_keluarga.no_kk as kk, tweb_keluarga.alamat, sex, tweb_wil_clusterdesa.dusun, tweb_wil_clusterdesa.rw, tweb_wil_clusterdesa.rt');
                break;

            case AnalisisRefSubjekEnum::KELUARGA:
                $utama  = 'keluarga_aktif';
                $sumber = KeluargaAktif::join('penduduk_hidup', 'keluarga_aktif.nik_kepala', '=', 'penduduk_hidup.id')
                    ->join('tweb_wil_clusterdesa', 'penduduk_hidup.id_cluster', '=', 'tweb_wil_clusterdesa.id')
                    ->when($idCluster, static fn ($q) => $q->whereIn('penduduk_hidup.id_cluster', $idCluster))
                    ->selectRaw('keluarga_aktif.id, keluarga_aktif.no_kk AS nid, penduduk_hidup.nama, penduduk_hidup.nik as kk, keluarga_aktif.alamat, penduduk_hidup.sex, tweb_wil_clusterdesa.dusun, tweb_wil_clusterdesa.rw, tweb_wil_clusterdesa.rt');
                break;

            case AnalisisRefSubjekEnum::RUMAH_TANGGA:
                $utama  = 'tweb_rtm';
                $sumber = Rtm::join('penduduk_hidup', 'tweb_rtm.nik_kepala', '=', 'penduduk_hidup.id')
                    ->join('tweb_keluarga', 'tweb_keluarga.id', '=', 'penduduk_hidup.id_kk')
                    ->join('tweb_wil_clusterdesa', 'penduduk_hidup.id_cluster', '=', 'tweb_wil_clusterdesa.id')
                    ->when($idCluster, static fn ($q) => $q->whereIn('penduduk_hidup.id_cluster', $idCluster))
                    ->selectRaw('tweb_rtm.id, tweb_rtm.no_kk AS nid, penduduk_hidup.nama, penduduk_hidup.nik as kk, tweb_keluarga.alamat, penduduk_hidup.sex, tweb_wil_clusterdesa.dusun, tweb_wil_clusterdesa.rw, tweb_wil_clusterdesa.rt');
                break;

            case AnalisisRefSubjekEnum::KELOMPOK:
                $utama  = 'kelompok';
                $sumber = Kelompok::leftJoin('penduduk_hidup', 'kelompok.id_ketua', '=', 'penduduk_hidup.id')
                    ->join('tweb_wil_clusterdesa', 'penduduk_hidup.id_cluster', '=', 'tweb_wil_clusterdesa.id')
                    ->when($idCluster, static fn ($q) => $q->whereIn('penduduk_hidup.id_cluster', $idCluster))
                    ->selectRaw('kelompok.id, kelompok.kode AS nid, kelompok.nama, penduduk_hidup.sex, tweb_wil_clusterdesa.dusun, tweb_wil_clusterdesa.rw, tweb_wil_clusterdesa.rt');
                break;

            case AnalisisRefSubjekEnum::DESA:
                $utama  = 'config';
                $sumber = Config::selectRaw('config.id, config.kode_desa as nid, config.nama_desa as nama, "-" as sex, "-" as dusun, "-" as rw, "-" as rt');
                break;

            case AnalisisRefSubjekEnum::DUSUN:
                $sebutanDusun = setting('sebutan_dusun');
                $utama        = 'tweb_wil_clusterdesa';
                $sumber       = Wilayah::where('rt', '0')->where('rw', '0')
                    ->when($idCluster, static fn ($q) => $q->whereIn('id', $idCluster))
                    ->selectRaw("id, dusun AS nid, CONCAT(UPPER('" . $sebutanDusun . " '), dusun) as nama, '-' as sex, dusun, '-' as rw, '-' as rt");
                break;

            case AnalisisRefSubjekEnum::RW:
                $sebutanDusun = setting('sebutan_dusun');
                $utama        = 'tweb_wil_clusterdesa';
                $sumber       = Wilayah::where('rt', '0')->where('rw', '!=', '0')->where('rw', '!=', '-')
                    ->when($idCluster, static fn ($q) => $q->whereIn('id', $idCluster))
                    ->selectRaw("id, rw AS nid, CONCAT( UPPER('" . $sebutanDusun . " '), dusun, ' RW ', rw) as nama, '-' as sex, dusun, rw, '-' as rt");
                break;

            case AnalisisRefSubjekEnum::RT:
                $sebutanDusun = setting('sebutan_dusun');
                $utama        = 'tweb_wil_clusterdesa';
                $sumber       = Wilayah::where('rt', '!=', '0')->where('rt', '!=', '-')
                    ->when($idCluster, static fn ($q) => $q->whereIn('id', $idCluster))
                    ->selectRaw("id, rt AS nid, CONCAT( UPPER('" . $sebutanDusun . " '), dusun, ' RW ', rw, ' RT ', rt) as nama, '-' as sex, dusun, rw, rt");
                break;
        }

        return ['sumber' => $sumber, 'utama' => $utama];
    }

    public function getSubjek($analisisMaster, $id)
    {
        $sebutan_dusun = ucwords(setting('sebutan_dusun'));
        $subjekTipe    = $analisisMaster->subjek_tipe;
        $sumber        = null;

        switch ($subjekTipe) {
            case AnalisisRefSubjekEnum::PENDUDUK:
                $sumber = PendudukHidup::selectRaw('penduduk_hidup.*, penduduk_hidup.nik AS nid, tweb_wil_clusterdesa.dusun, tweb_wil_clusterdesa.rw, tweb_wil_clusterdesa.rt')
                    ->selectRaw("CONCAT('{$sebutan_dusun} ', tweb_wil_clusterdesa.dusun, ', RT ', tweb_wil_clusterdesa.rt, ' / RW ', tweb_wil_clusterdesa.rw) as wilayah")
                    ->join('tweb_wil_clusterdesa', 'penduduk_hidup.id_cluster', '=', 'tweb_wil_clusterdesa.id')
                    ->where('penduduk_hidup.id', $id);
                break;

            case AnalisisRefSubjekEnum::KELUARGA:
                $sumber = KeluargaAktif::selectRaw('keluarga_aktif.*, keluarga_aktif.no_kk AS nid, penduduk_hidup.nik AS nik_kepala, penduduk_hidup.nama, penduduk_hidup.sex, tweb_wil_clusterdesa.dusun, tweb_wil_clusterdesa.rw, tweb_wil_clusterdesa.rt')
                    ->selectRaw("CONCAT('{$sebutan_dusun} ', tweb_wil_clusterdesa.dusun, ', RT ', tweb_wil_clusterdesa.rt, ' / RW ', tweb_wil_clusterdesa.rw) as wilayah")
                    ->leftJoin('penduduk_hidup', 'keluarga_aktif.nik_kepala', '=', 'penduduk_hidup.id')
                    ->leftJoin('tweb_wil_clusterdesa', 'penduduk_hidup.id_cluster', '=', 'tweb_wil_clusterdesa.id')
                    ->where('keluarga_aktif.id', $id);

                break;

            case AnalisisRefSubjekEnum::RUMAH_TANGGA:
                $sumber = Rtm::selectRaw('tweb_rtm.id, tweb_rtm.no_kk AS nid, penduduk_hidup.nama, penduduk_hidup.sex, tweb_wil_clusterdesa.dusun, tweb_wil_clusterdesa.rw, tweb_wil_clusterdesa.rt')
                    ->join('penduduk_hidup', 'tweb_rtm.nik_kepala', '=', 'penduduk_hidup.id')
                    ->join('tweb_wil_clusterdesa', 'penduduk_hidup.id_cluster', '=', 'tweb_wil_clusterdesa.id')
                    ->where('tweb_rtm.id', $id);
                break;

            case AnalisisRefSubjekEnum::KELOMPOK:
                $sumber = Kelompok::selectRaw('kelompok.nama AS no_kk, penduduk_hidup.nama')
                    ->leftJoin('penduduk_hidup', 'kelompok.id_ketua', '=', 'penduduk_hidup.id')
                    ->leftJoin('tweb_wil_clusterdesa', 'penduduk_hidup.id_cluster', '=', 'tweb_wil_clusterdesa.id')
                    ->where('kelompok.id', $id);
                break;

            case AnalisisRefSubjekEnum::DESA:
                $sumber = Config::selectRaw("config.id, config.kode_desa AS nid, config.nama_desa as nama, '-' as sex, '-' as dusun, '-' as rw, '-' as rt")
                    ->selectRaw("
                        config.nama_desa, config.kode_desa, config.kode_pos, config.alamat_kantor, config.telepon as no_telepon_kantor_desa, config.email_desa, CONCAT('Lintang : ', config.lat, ', ', 'Bujur : ', config.lng) as titik_koordinat_desa")
                    ->selectRaw('
                        tweb_desa_pamong.pamong_nip AS nip_kepala_desa,
                        (case when tweb_penduduk.sex is not null then tweb_penduduk.sex else tweb_desa_pamong.pamong_sex end) as jk_kepala_desa,
                        (case when tweb_penduduk.pendidikan_kk_id is not null then pendidikan_warga.nama else pendidikan_pamong.nama end) as pendidikan_kepala_desa,
                        (case when tweb_penduduk.nama is not null then tweb_penduduk.nama else tweb_desa_pamong.pamong_nama end) AS nama_kepala_desa,
                        tweb_penduduk.telepon as no_telepon_kepala_desa
                    ')
                    ->leftJoin('tweb_desa_pamong', 'config.pamong_id', '=', 'tweb_desa_pamong.pamong_id')
                    ->leftJoin('tweb_penduduk', 'tweb_desa_pamong.id_pend', '=', 'tweb_penduduk.id')
                    ->leftJoin('tweb_penduduk_pendidikan_kk as pendidikan_warga', 'tweb_penduduk.pendidikan_kk_id', '=', 'pendidikan_warga.id')
                    ->leftJoin('tweb_penduduk_pendidikan_kk as pendidikan_pamong', 'tweb_desa_pamong.pamong_pendidikan', '=', 'pendidikan_pamong.id')
                    ->where('config.id', $id);
                break;

            case AnalisisRefSubjekEnum::DUSUN:
                $sumber = Wilayah::selectRaw("id, dusun AS nid, UPPER('{$sebutan_dusun}') as nama, '-' as sex, dusun, '-' as rw, '-' as rt")
                    ->where('rt', '0')->where('rw', '0')->where('tweb_wil_clusterdesa.id', $id);
                break;

            case AnalisisRefSubjekEnum::RW:
                $sumber = Wilayah::selectRaw("id, rw AS nid, CONCAT( UPPER('{$sebutan_dusun} '), dusun, ' RW ', rw) as nama, '-' as sex, dusun, rw, '-' as rt")
                    ->where('rt', '0')->where('rw', '!=', '0')->where('tweb_wil_clusterdesa.id', $id);
                break;

            case AnalisisRefSubjekEnum::RT:
                $sumber = Wilayah::selectRaw("id, rt AS nid, CONCAT( UPPER('{$sebutan_dusun} '), dusun, ' RW ', rw, ' RT ', rt) as nama, '-' as sex, dusun, rw, rt")
                    ->where('rt', '!=', '0')->where('rt', '!=', '-')->where('tweb_wil_clusterdesa.id', $id);
                break;

            default: return null;
        }
        $data = $sumber->first()?->toArray();

        // Data tambahan subjek desa
        if ($subjekTipe == 5) {
            $tambahan = [
                'jumlah_total_penduduk'            => PendudukHidup::count(),
                'jumlah_penduduk_laki_laki'        => PendudukHidup::where('sex', JenisKelaminEnum::LAKI_LAKI)->count(),
                'jumlah_penduduk_perempuan'        => PendudukHidup::where('sex', JenisKelaminEnum::PEREMPUAN)->count(),
                'jumlah_penduduk_pedatang'         => PendudukHidup::where('status', 2)->count(),
                'jumlah_penduduk_yang_pergi'       => LogPenduduk::where('kode_peristiwa', 3)->count(),
                'jumlah_total_kepala_keluarga'     => KeluargaAktif::leftJoin('penduduk_hidup', 'keluarga_aktif.nik_kepala', '=', 'penduduk_hidup.id')->count(),
                'jumlah_kepala_keluarga_laki_laki' => KeluargaAktif::leftJoin('penduduk_hidup', 'keluarga_aktif.nik_kepala', '=', 'penduduk_hidup.id')->where('sex', JenisKelaminEnum::LAKI_LAKI)->count(),
                'jumlah_kepala_keluarga_perempuan' => KeluargaAktif::leftJoin('penduduk_hidup', 'keluarga_aktif.nik_kepala', '=', 'penduduk_hidup.id')->where('sex', JenisKelaminEnum::PEREMPUAN)->count(),
                'jumlah_peserta_bpjs'              => PendudukHidup::whereNotNull('bpjs_ketenagakerjaan')->count(),
            ];

            $data = array_merge($data, $tambahan);
        }

        return $data;
    }

    public function listAnggota($analisisMaster, $id)
    {
        $subjek = $analisisMaster->subjek_tipe;
        if (in_array($subjek, [AnalisisRefSubjekEnum::KELUARGA, AnalisisRefSubjekEnum::RUMAH_TANGGA])) {
            switch ($subjek) {
                case AnalisisRefSubjekEnum::KELUARGA:
                    return PendudukHidup::where('id_kk', $id)->orderBy('kk_level')->get()->toArray();

                case AnalisisRefSubjekEnum::RUMAH_TANGGA:
                    return PendudukHidup::where('id_rtm', $id)->orderBy('rtm_level')->get()->toArray();

                default: return null;
            }
        }

        return null;
    }

    public function listIndikator($analisisMaster, $periode, $id)
    {
        $per   = $periode;
        $delik = session('delik');

        $data = AnalisisIndikator::with(['kategori'])
            ->where('id_master', $analisisMaster->id)
            ->orderByRaw("LPAD(nomor, 10, ' ') ASC")
            ->get()
            ->toArray();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']       = $i + 1;
            $data[$i]['kategori'] = $data[$i]['kategori']['kategori'];
            if ($data[$i]['id_tipe'] == 1 || $data[$i]['id_tipe'] == 2) {
                $data[$i]['parameter_respon'] = $this->listJawab2($id, $data[$i]['id'], $per);
            } else {
                $data[$i]['parameter_respon'] = $delik ? '' : $this->listJawab3($id, $data[$i]['id'], $per);
            }
        }

        return $data;
    }

    private function listJawab2($id = 0, $in = 0, $per = 0)
    {
        $delik = session('delik');
        $query = AnalisisParameter::selectRaw('id as id_parameter,jawaban,kode_jawaban')
            ->where('id_indikator', $in)
            ->orderBy('kode_jawaban', 'ASC');
        if ($delik) {
            $query->selectRaw('0 as cek');
        } else {
            $query->selectRaw('(SELECT count(id_subjek) FROM analisis_respon WHERE id_parameter = analisis_parameter.id AND id_subjek =' . $id . ' AND id_periode=' . $per . ') as cek');
        }

        return $query->get()->toArray();
    }

    private function listJawab3($id = 0, $in = 0, $per = 0)
    {
        return AnalisisRespon::selectRaw('analisis_parameter.id as id_parameter,analisis_parameter.jawaban')
            ->leftJoin('analisis_parameter', 'analisis_respon.id_parameter', '=', 'analisis_parameter.id')
            ->where(['analisis_respon.id_indikator' => $in, 'analisis_respon.id_subjek' => $id, 'analisis_respon.id_periode' => $per])
            ->get()
            ->toArray();
    }

    public function listBukti($analisisMaster, $periode, $id)
    {
        $per = $periode;

        return AnalisisResponBukti::select(['pengesahan'])
            ->where('id_subjek', $id)
            ->where('id_master', $analisisMaster->id)
            ->where('id_periode', $per)
            ->orderBy('tgl_update', 'DESC')
            ->get()
            ->toArray();
    }

    private function listJawabLaporan($periode, $idSubjek, $in)
    {
        $per = $periode;
        $obj = AnalisisRespon::selectRaw('analisis_parameter.id as id_parameter, analisis_parameter.jawaban as jawaban,analisis_parameter.nilai')
            ->join('analisis_parameter', 'analisis_respon.id_parameter', '=', 'analisis_parameter.id')
            ->where('id_subjek', $idSubjek)
            ->where('id_periode', $per)
            ->where('analisis_respon.id_indikator', $in)
            ->first();

        $data['jawaban'] = $obj->jawaban ?? '-';
        $data['nilai']   = $obj->nilai ?? '0';

        return $data;
    }

    public function listIndikatorLaporan($analisisMaster, $periode, $id = 0)
    {
        $data    = AnalisisIndikator::where('id_master', $analisisMaster->id)->orderBy('nomor')->get()->toArray();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']      = $i + 1;
            $ret                 = $this->listJawabLaporan($periode, $id, $data[$i]['id']);
            $data[$i]['jawaban'] = $ret['jawaban'];
            $data[$i]['nilai']   = $ret['nilai'];
            $data[$i]['poin']    = $data[$i]['bobot'] * $ret['nilai'];
        }

        return $data;
    }

    public function multiJawab($master)
    {
        $kf = session('jawab') ?? '7777777';

        $data = AnalisisIndikator::selectRaw('analisis_indikator.pertanyaan,analisis_indikator.nomor,analisis_parameter.jawaban,analisis_parameter.id AS id_jawaban,analisis_parameter.kode_jawaban')
            ->selectRaw("(SELECT count(id) FROM analisis_parameter WHERE id IN ({$kf}) AND id = analisis_parameter.id AND analisis_indikator.config_id = " . identitas('id') . ') AS cek')
            ->where('id_master', $master)
            ->join('analisis_parameter', 'analisis_parameter.id_indikator', '=', 'analisis_indikator.id')
            ->orderBy('nomor')->orderBy('kode_jawaban')->get()->toArray();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;
        }

        return $data;
    }
}
