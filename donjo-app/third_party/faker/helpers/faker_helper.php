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

use App\Enums\HubunganRTMEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\SHDKEnum;
use App\Models\Agama;
use App\Models\GolonganDarah;
use App\Models\LogKeluarga;
use App\Models\LogPenduduk;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use App\Models\PendidikanKK;
use App\Models\StatusKawin;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

function faker()
{
    return Factory::create('id_ID');
}

function configFaker($key = null)
{
    $ci = &get_instance();
    $ci->load->config('faker', true);
    $config = $ci->config->item('faker');

    if ($key) {
        return $config[$key];
    }

    return $config;
}

function truncateTable($daftarTabel): void
{
    DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    if (is_array($daftarTabel)) {
        foreach ($daftarTabel as $tabel) {
            DB::table($tabel)->truncate();
        }
    } else {
        DB::table($daftarTabel)->truncate();
    }
    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
}

// Wilayah
function buatWilayah($configId)
{
    // Dusun
    $minDusun    = configFaker('wilayah')['dusun']['min'];
    $maxDusun    = configFaker('wilayah')['dusun']['max'];
    $jumlahDusun = faker()->numberBetween($minDusun, $maxDusun);

    $dusun = [];

    for ($i = 1; $i <= $jumlahDusun; $i++) {
        $dusun[] = buatDusun($configId, $i);
    }

    $wilayah = collect($dusun)->flatten(1)->toArray();

    DB::table('tweb_wil_clusterdesa')->insert($wilayah);

    return $wilayah;
}

// Wilayah Dusun
function buatDusun($configId, $urutDusun)
{
    // $namaDusun = strtoupper(faker()->word) . ' ' . $urutDusun;
    $namaDusun = strtoupper(faker()->word);

    $dusun = [
        [
            'config_id' => $configId,
            'rt'        => 0,
            'rw'        => 0,
            'dusun'     => $namaDusun,
        ],
        [
            'config_id' => $configId,
            'rt'        => 0,
            'rw'        => '-',
            'dusun'     => $namaDusun,
        ],
        [
            'config_id' => $configId,
            'rt'        => '-',
            'rw'        => '-',
            'dusun'     => $namaDusun,
        ],
    ];

    // RW
    $minRW    = configFaker('wilayah')['rw']['min'];
    $maxRW    = configFaker('wilayah')['rw']['max'];
    $jumlahRW = faker()->numberBetween($minRW, $maxRW);

    $rw = [];

    for ($i = 1; $i <= $jumlahRW; $i++) {
        $rw[] = buatRW($configId, $namaDusun, $urutDusun, $i);
    }

    return collect($dusun)->merge(collect($rw)->flatten(1))->all();
}

// Wilayah RW
function buatRW($configId, $namaDusun, string $urutDusun, string $urutRW)
{
    $namaRW = $urutDusun . $urutRW;

    $rw = [
        [
            'config_id' => $configId,
            'rt'        => 0,
            'rw'        => $namaRW,
            'dusun'     => $namaDusun,
        ],
        [
            'config_id' => $configId,
            'rt'        => '-',
            'rw'        => $namaRW,
            'dusun'     => $namaDusun,
        ],
    ];

    // RW
    $minRT    = configFaker('wilayah')['rt']['min'];
    $maxRT    = configFaker('wilayah')['rt']['max'];
    $jumlahRT = faker()->numberBetween($minRT, $maxRT);

    $rt = [];

    for ($i = 1; $i <= $jumlahRT; $i++) {
        $rt[] = buatRT($configId, $namaDusun, $namaRW, $i);
    }

    return collect($rw)->merge(collect($rt)->flatten(1))->all();
}

// Wilayah RT
function buatRT($configId, $namaDusun, string $namaRW, string $urutRT)
{
    $namaRT = $namaRW . $urutRT;

    return [
        [
            'config_id' => $configId,
            'rt'        => $namaRT,
            'rw'        => $namaRW,
            'dusun'     => $namaDusun,
        ],
    ];
}

// Keluarga
function buatKeluarga($configId, $kodeKecamatan): void
{
    // $minAnggota        = configFaker('keluarga')['anggota']['min'];
    // $maxAnggota = configFaker('keluarga')['anggota']['max'];
    // $minPenduduk       = configFaker('penduduk')['min'];
    $maxPenduduk = configFaker('penduduk')['max'];
    // $rand              = faker()->numberBetween($minAnggota, $maxAnggota);
    // $minJumlahKeluarga = ceil($minPenduduk / $rand);
    // $maxJumlahKeluarga = ceil($maxPenduduk / $rand);
    // $jumlahKeluarga    = faker()->numberBetween($maxJumlahKeluarga, $minJumlahKeluarga + $maxJumlahKeluarga);

    $jumlahKeluarga = faker()->numberBetween($maxPenduduk / 3, $maxPenduduk / 2);

    $totalPenduduk = 0;

    for ($i = 1; $i <= $jumlahKeluarga; $i++) {
        $totalPenduduk = buatAnggota($configId, $kodeKecamatan, $i) + $totalPenduduk;
        if ($totalPenduduk >= $maxPenduduk) {
            break;
        }
    }
}

// Penduduk
function buatIndividu($configId, string $kodeKecamatan, $kkLevel, $statusKawin = null): array
{
    // Buat data Penduduk
    if ($kkLevel === SHDKEnum::ISTRI) {
        $sex        = JenisKelaminEnum::PEREMPUAN;
        $nameForSex = 'female';
    } else {
        $sex        = faker()->randomElement([1, 1, 2, 1, 1, 2, 1, 1, 2, 1]);
        $nameForSex = $sex === 1 ? 'male' : 'female';
    }

    // tanggal lahir anak harus 17 tahun lebih kecil dari ayah atau ibu
    if ($kkLevel === SHDKEnum::ANAK) {
        $tanggallahir = faker()->dateTimeBetween('-17 years', '-1 years')->format('Y-m-d');
    } else {
        $tanggallahir = faker()->dateTimeBetween('-70 years', '-17 years')->format('Y-m-d');
    }

    // status kawin
    if ($kkLevel === SHDKEnum::ANAK || $kkLevel === SHDKEnum::CUCU) {
        $statusKawin = 1;
    } elseif (in_array($kkLevel, [SHDKEnum::SUAMI, SHDKEnum::ISTRI, SHDKEnum::SUAMI])) {
        $statusKawin = 2;
    } else {
        $statusKawin = faker()->numberBetween(2, StatusKawin::count());
    }

    // NIK diambil dari kode kecamatan + 6 digit tgl lahir + 4 digit nomer urut
    // ambil 6 digit tanggal lahir, jika perempuan + 40
    $tglAwal = substr($tanggallahir, 8, 2);
    if ($sex === 2) {
        $tglAwal += 40;
    }
    $tglLahir = $tglAwal . substr($tanggallahir, 5, 2) . substr($tanggallahir, 2, 2);
    $rand     = str_pad(faker()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT);
    $nik      = $kodeKecamatan . $tglLahir . $rand;

    $penduduk = [
        'config_id'            => $configId,
        'nik'                  => $nik,
        'nama'                 => faker()->name($nameForSex),
        'id_kk'                => null,
        'kk_level'             => $kkLevel,
        'id_rtm'               => 0,
        'rtm_level'            => 0,
        'sex'                  => $sex,
        'tempatlahir'          => faker()->city,
        'tanggallahir'         => $tanggallahir,
        'agama_id'             => faker()->numberBetween(1, Agama::count()),
        'pendidikan_kk_id'     => faker()->numberBetween(1, PendidikanKK::count()),
        'pendidikan_sedang_id' => faker()->numberBetween(1, Pendidikan::count()),
        'pekerjaan_id'         => faker()->numberBetween(1, Pekerjaan::count()),
        'status_kawin'         => $statusKawin,
        'id_cluster'           => DB::table('tweb_wil_clusterdesa')->inRandomOrder()->first()->id,
        'warganegara_id'       => 1,
        'alamat_sekarang'      => faker()->address,
        'ayah_nik'             => $kodeKecamatan . faker()->numberBetween(1_000_000_000, 9_999_999_999),
        'nama_ayah'            => faker()->name('male'),
        'ibu_nik'              => $kodeKecamatan . faker()->numberBetween(1_000_000_000, 9_999_999_999),
        'nama_ibu'             => faker()->name('female'),
        'golongan_darah_id'    => faker()->numberBetween(1, GolonganDarah::count()),
        'status'               => 1,
        'status_dasar'         => 1,
        'created_by'           => 1,
        'updated_by'           => 1,
    ];

    $id = DB::table('tweb_penduduk')->insertGetId($penduduk);

    $logPenduduk = [
        'config_id'      => $configId,
        'id_pend'        => $id,
        'kode_peristiwa' => LogPenduduk::BARU_PINDAH_MASUK,
        'tgl_lapor'      => faker()->dateTimeBetween(configFaker('keluarga')['rentang_awal'] . '-01-01', date('Y') . '-12-31')->format('Y-m-d'),
        'catatan'        => 'Penduduk Baru Pindah Masuk',
    ];

    DB::table('log_penduduk')->insert($logPenduduk);

    $penduduk['id'] = $id;

    return $penduduk;
}

function buatAnggota($configId, string $kodeKecamatan, $urut)
{
    $jumlahPenduduk = 0;

    // urut 4 digit, jika kurang dari 4 digit, tambahkan 0 di depan
    $urut     = str_pad($urut, 4, '0', STR_PAD_LEFT);
    $tglRekam = faker()->dateTimeBetween(configFaker('keluarga')['rentang_awal'] . '-01-01', date('Y') . '-12-31')->format('Y-m-d');

    // No KK diambil dari kode kecamatan + 6 digit tgl rekam + 4 digit nomer urut random
    $tglKK = substr($tglRekam, 8, 2) . substr($tglRekam, 5, 2) . substr($tglRekam, 2, 2);

    $noKk = $kodeKecamatan . $tglKK . $urut;

    if (DB::table('tweb_keluarga')->where('config_id', $configId)->where('no_kk', $noKk)->count() > 0) {
        return buatAnggota($configId, $kodeKecamatan, $urut);
    }

    $anggota = [];
    // Hanya gunakan RT sebagai cluster
    $idCluster = DB::table('tweb_wil_clusterdesa')->whereNotIn('rt', ['-', '0'])->inRandomOrder()->first()->id;

    // Buat kepala keluarga
    $jumlahPenduduk++;
    $kepalaKeluarga = buatIndividu($configId, $kodeKecamatan, SHDKEnum::KEPALA_KELUARGA);
    $anggota[]      = $kepalaKeluarga['id'];
    $keluarga       = [
        'config_id'    => $configId,
        'no_kk'        => $noKk,
        'nik_kepala'   => $kepalaKeluarga['id'],
        'tgl_daftar'   => $tglRekam,
        'kelas_sosial' => faker()->numberBetween(1, DB::table('tweb_keluarga_sejahtera')->count()),
        'alamat'       => $kepalaKeluarga['alamat_sekarang'],
        'id_cluster'   => $idCluster,
        'updated_by'   => 1,
    ];
    $idKk = DB::table('tweb_keluarga')->insertGetId($keluarga);

    $logKeluarga = [
        'config_id'     => $configId,
        'id_kk'         => $idKk,
        'id_peristiwa'  => LogKeluarga::KELUARGA_BARU,
        'tgl_peristiwa' => $keluarga['tgl_daftar'],
    ];

    DB::table('log_keluarga')->insert($logKeluarga);

    if (configFaker('keluarga')['anggota']['min'] > 1) {
        // Apakah ada istri?
        // Berdasarkan status kawin
        if ($kepalaKeluarga['status_kawin'] != 1 && faker()->boolean()) {
            $istri     = buatIndividu($configId, $kodeKecamatan, SHDKEnum::ISTRI);
            $anggota[] = $istri['id'];
            $jumlahPenduduk++;
        }

        // Apakah ada anak?
        if ($kepalaKeluarga['status_kawin'] != 1 && faker()->boolean()) {
            // Berapa anak yang dimiliki?
            $jumlahAnak = faker()->numberBetween(1, 3);

            for ($i = 0; $i < $jumlahAnak; $i++) {
                $anak      = buatIndividu($configId, $kodeKecamatan, SHDKEnum::ANAK);
                $anggota[] = $anak['id'];
                $jumlahPenduduk++;
            }
        }

        // Apakah ada status lainnya?
        if (faker()->boolean()) {
            $jumlahStatusLainnya = faker()->numberBetween(1, 3);

            for ($i = 0; $i < $jumlahStatusLainnya; $i++) {
                $statusLainnya = faker()->randomElement([5, 5, 6, 7, 7, 8, 9, 9, 9, 10, 11, 11]);
                $statusLainnya = buatIndividu($configId, $kodeKecamatan, $statusLainnya);
                $anggota[]     = $statusLainnya['id'];
                $jumlahPenduduk++;
            }
        }
    }

    // Data anggota yang perlu disamakan
    DB::table('tweb_penduduk')
        ->whereIn('id', $anggota)
        ->update([
            'id_kk'           => $idKk,
            'id_cluster'      => $idCluster,
            'alamat_sekarang' => $kepalaKeluarga['alamat'],
        ]);

    return $jumlahPenduduk;
}

// Rumah Tangga
function buatRumahTangga($configId, $kodeDesa): void
{
    // ambil data dari penduduk dengan status kk_level 1 hanya data id, id_kk dan created_at
    $penduduk = DB::table('tweb_penduduk')
        ->where('config_id', $configId)
        ->where('kk_level', 1)
        ->select('id', 'id_kk', 'created_at')
        ->inRandomOrder()
        ->get();

    $noRtm = null;

    foreach ($penduduk as $urut => $pend) {
        // buat baru atau gabungkan? 1= buat baru, 2= gabungkan, kemungkinan buat baru 60 % sisanya digabungkan
        $gabungkanRtm = faker()->randomElement([1, 2, 1, 1, 2, 1, 2, 1, 1, 2]);
        if ($gabungkanRtm == 2 && $noRtm != null) {
            DB::table('tweb_penduduk')
                ->where('config_id', $configId)
                ->where('id_kk', $pend->id_kk)
                ->update([
                    'id_rtm'    => $noRtm,
                    'rtm_level' => HubunganRTMEnum::ANGGOTA,
                ]);
            $noRtm = null;
        } else {
            $rtm = buatAnggotaRtm($configId, $kodeDesa, $urut + 1, $pend->id, $pend->id_kk, $pend->created_at);

            $noRtm = $rtm['no_kk'];
        }
    }
}

// Anggota Rumah Tangga
function buatAnggotaRtm($configId, string $kodeDesa, $urut, $nikKepala, $idKK, $tglDaftar): array
{
    // no_rtm diambil dari kode desa + 4 digit urut, jika urut kurang dari 4 digit, tambahkan 0 di depan
    $noRtm = $kodeDesa . str_pad($urut, 4, '0', STR_PAD_LEFT);

    // apakah ada bdt? kemungkinan mengisi 80%
    if (faker()->randomElement([1, 1, 1, 1, 2, 1, 1, 1, 1, 2]) == 1) {
        // $bdt 16 digit random, jika kurang dari 16 digit, tambahkan 0 di depan
        $bdt = str_pad(faker()->numberBetween(1, 9_999_999_999_999_999), 16, '0', STR_PAD_LEFT);
    } else {
        $bdt = null;
    }

    $data = [
        'config_id'    => $configId,
        'nik_kepala'   => $nikKepala,
        'no_kk'        => $noRtm,
        'tgl_daftar'   => $tglDaftar,
        'kelas_sosial' => faker()->numberBetween(1, DB::table('tweb_keluarga_sejahtera')->count()),
        'bdt'          => $bdt,
    ];

    $idRtm = DB::table('tweb_rtm')->insertGetId($data);

    $data['id'] = $idRtm;

    // Update semua anggota keluarga kk_level = 1
    DB::table('tweb_penduduk')
        ->where('config_id', $configId)
        ->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
        ->where('id_kk', $idKK)
        ->update([
            'id_rtm'    => $noRtm,
            'rtm_level' => HubunganRTMEnum::KEPALA_RUMAH_TANGGA,
        ]);

    // Update semua anggota keluarga selain kk_level = 1
    DB::table('tweb_penduduk')
        ->where('config_id', $configId)
        ->where('kk_level', '!=', SHDKEnum::KEPALA_KELUARGA)
        ->where('id_kk', $idKK)
        ->update([
            'id_rtm'    => $noRtm,
            'rtm_level' => HubunganRTMEnum::ANGGOTA,
        ]);

    return $data;
}

// Bantuan
function buatBantuan($configId): void
{
    $minProgram    = configFaker('bantuan')['program']['min'];
    $maxProgram    = configFaker('bantuan')['program']['max'];
    $jumlahProgram = faker()->numberBetween($minProgram, $maxProgram);

    for ($i = 1; $i <= $jumlahProgram; $i++) {
        buatProgram($configId);
    }
}

// Program Bantuan
function buatProgram($configId): void
{
    $sasaran = faker()->randomElement([1, 1, 1, 1, 2, 2, 2, 3, 3, 4]);
    if ($sasaran == 4 && DB::table('kelompok')->count() == 0) {
        $sasaran = faker()->randomElement([1, 1, 1, 2, 2, 3]);
    }

    $nama     = faker()->randomElement(configFaker('bantuan')['sasaran'][$sasaran]);
    $asalDana = collect(unserialize(ASALDANA))->values()->toArray();

    $data = [
        'config_id' => $configId,
        'nama'      => $nama,
        'sasaran'   => $sasaran,
        'ndesc'     => faker()->paragraph(3),
        'sdate'     => faker()->dateTimeBetween('-2 years', 'now'),
        'edate'     => faker()->dateTimeBetween('now', '+2 years'),
        'status'    => 1,
        'asaldana'  => faker()->randomElement($asalDana),
    ];

    $idProgram = DB::table('program')->insertGetId($data);

    // Peserta
    $minPeserta    = configFaker('bantuan')['peserta']['min'];
    $maxPeserta    = configFaker('bantuan')['peserta']['max'];
    $jumlahPeserta = faker()->numberBetween($minPeserta, $maxPeserta);

    $kecuali = [];

    for ($i = 0; $i < $jumlahPeserta; $i++) {
        $kecuali[] = buatPeserta($configId, $idProgram, $sasaran, $kecuali);
    }
}

// Peserta Bantuan
function buatPeserta($configId, string $idProgram, $sasaran, $kecuali)
{
    switch ($sasaran) {
        case 1:
            $penduduk = DB::table('tweb_penduduk')
                ->select('id', 'nik', 'nama', 'tempatlahir', 'tanggallahir', 'alamat_sekarang')
                ->where('config_id', $configId)
                ->whereNotIn('nik', $kecuali)
                ->inRandomOrder()
                ->first();

            $peserta = $penduduk->nik;
            break;

        case 2:
            $penduduk = DB::table('tweb_penduduk')
                ->select('tweb_penduduk.id', 'tweb_penduduk.nik', 'tweb_penduduk.nama', 'tweb_penduduk.tempatlahir', 'tweb_penduduk.tanggallahir', 'tweb_penduduk.alamat_sekarang', 'tweb_keluarga.no_kk')
                ->join('tweb_keluarga', 'tweb_penduduk.id_kk', '=', 'tweb_keluarga.id')
                ->where('tweb_penduduk.config_id', $configId)
                ->whereIn('tweb_penduduk.kk_level', ['1', '2', '3', '4'])
                ->whereNotIn('tweb_keluarga.no_kk', $kecuali)
                ->inRandomOrder()
                ->first();

            $peserta = $penduduk->no_kk;
            break;

        case 3:
            $penduduk = DB::table('tweb_penduduk')
                ->select('id', 'nik', 'nama', 'tempatlahir', 'tanggallahir', 'alamat_sekarang', 'id_rtm')
                ->where('config_id', $configId)
                ->whereNotNull('id_rtm')
                ->whereNotIn('id_rtm', $kecuali)
                ->inRandomOrder()
                ->first();

            $peserta = $penduduk->id_rtm;
            break;

        default:
            $peserta = null;
            break;
    }

    if ($penduduk) {
        // no_id_kartu = program_id + random 10 digit, jika 10 digit satuan maka tambahkan 0 di depan
        $noIdKartu = $idProgram . str_pad(faker()->numberBetween(1, 9_999_999_999), 10, '0', STR_PAD_LEFT);

        $data = [
            'config_id'           => $configId,
            'peserta'             => $peserta,
            'program_id'          => $idProgram,
            'no_id_kartu'         => $noIdKartu,
            'kartu_nik'           => $penduduk->nik,
            'kartu_nama'          => $penduduk->nama,
            'kartu_tempat_lahir'  => $penduduk->tempatlahir,
            'kartu_tanggal_lahir' => $penduduk->tanggallahir,
            'kartu_alamat'        => $penduduk->alamat_sekarang ?? '',
            'kartu_id_pend'       => $penduduk->id,
        ];

        DB::table('program_peserta')->insert($data);
    }

    return $peserta;
}

// Kelompok
function buatKelompok($configId): void
{
    $minKelompokMaster = configFaker('kelompok')['master']['min'];
    $maxKelompokMaster = configFaker('kelompok')['master']['max'];
    $jumlahKelompok    = faker()->numberBetween($minKelompokMaster, $maxKelompokMaster);

    for ($i = 1; $i <= $jumlahKelompok; $i++) {
        buatMaster($configId);
    }
}

// Kelompok Master
function buatMaster($configId, $tipe = 1)
{
    return [
        'config_id' => $config,
        'kelompok'  => faker()->randomElement(configFaker('kelompok')['master']['tipe'][$tipe]),
        'deskripsi' => faker()->paragraph(3),
        'tipe'      => $tipe,
    ];
}
