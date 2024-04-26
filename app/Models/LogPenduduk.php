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

namespace App\Models;

use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

class LogPenduduk extends BaseModel
{
    use ConfigId;

    /**
     * KETERANGAN kode_peristiwa di log_penduduk
     * 1 = insert penduduk baru dengan status lahir
     * 2 = penduduk mati
     * 3 = penduduk pindah keluar
     * 4 = penduduk hilang
     * 5 = insert penduduk baru pindah masuk
     * 6 = penduduk tidak tetap pergi
     */
    public const BARU_LAHIR = 1;

    public const MATI              = 2;
    public const PINDAH_KELUAR     = 3;
    public const HILANG            = 4;
    public const BARU_PINDAH_MASUK = 5;
    public const TIDAK_TETAP_PERGI = 6;
    public const PERISTIWA         = [1, 2, 3, 4];

    /**
     * Static data penolong mati.
     *
     * @var array
     */
    public const PENOLONG_MATI = [
        1 => 'Dokter',
        2 => 'Tenaga Kesehatan',
        3 => 'Kepolisian',
        4 => 'Lainnya',
    ];

    /**
     * Static data penyebab kematian.
     *
     * @var array
     */
    public const PENYEBAB_KEMATIAN = [
        1 => 'Sakit biasa / tua',
        2 => 'Wabah Penyakit',
        3 => 'Kecelakaan',
        4 => 'Kriminalitas',
        5 => 'Bunuh Diri',
        6 => 'Lainnya',
    ];

    /**
     * The table associated with the models.
     *
     * @var string
     */
    protected $table = 'log_penduduk';

    /**
     * The guarded with the models.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'tgl_lapor'     => 'datetime:Y-m-d',
        'tgl_peristiwa' => 'datetime:Y-m-d',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_pend', 'id');
    }

    /**
     * Getter penolong mati.
     *
     * @return string
     */
    public function getYangMenerangkanAttribute()
    {
        return static::PENOLONG_MATI[$this->penolong_mati] ?? '';
    }

    public function scopeRekapitulasiList($query, $filters = [])
    {
        $bln     = $filters['bulan'] ?? date('m');
        $thn     = $filters['tahun'] ?? date('Y');
        $pad_bln = str_pad($bln, 2, '0', STR_PAD_LEFT); // Untuk membandingkan dengan tgl mysql

        // log_penduduk.
        $query
        // ->select(['tgl_lapor', 'kode_peristiwa', 'id_pend'])
            ->selectRaw('tweb_wil_clusterdesa.dusun as DUSUN')
            ->selectRaw("(sum(case when tweb_penduduk.sex = 1 and tweb_penduduk.warganegara_id <> 2 and log_penduduk.kode_peristiwa in (1,5) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end) - sum(case when tweb_penduduk.sex = 1 and tweb_penduduk.warganegara_id <> 2 and log_penduduk.kode_peristiwa in (2,3,4) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end)) AS WNI_L_AWAL")
            ->selectRaw("(sum(case when tweb_penduduk.sex = 2 and tweb_penduduk.warganegara_id <> 2 and log_penduduk.kode_peristiwa in (1,5) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end) - sum(case when tweb_penduduk.sex = 2 and tweb_penduduk.warganegara_id <> 2 and log_penduduk.kode_peristiwa in (2,3,4) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end)) AS WNI_P_AWAL")
            ->selectRaw("(sum(case when tweb_penduduk.sex = 1 and tweb_penduduk.warganegara_id = 2 and log_penduduk.kode_peristiwa in (1,5) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end) - sum(case when tweb_penduduk.sex = 1 and tweb_penduduk.warganegara_id = 2 and log_penduduk.kode_peristiwa in (2,3,4) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end)) AS WNA_L_AWAL")
            ->selectRaw("(sum(case when tweb_penduduk.sex = 2 and tweb_penduduk.warganegara_id = 2 and log_penduduk.kode_peristiwa in (1,5) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end) - sum(case when tweb_penduduk.sex = 2 and tweb_penduduk.warganegara_id = 2 and log_penduduk.kode_peristiwa in (2,3,4) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end)) AS WNA_P_AWAL")
            ->selectRaw("sum(case when tweb_penduduk.sex = 1 and tweb_penduduk.warganegara_id <> 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 1 then 1 else 0 end) AS WNI_L_TAMBAH_LAHIR")
            ->selectRaw("sum(case when tweb_penduduk.sex = 2 and tweb_penduduk.warganegara_id <> 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 1 then 1 else 0 end) AS WNI_P_TAMBAH_LAHIR")
            ->selectRaw("sum(case when tweb_penduduk.sex = 1 and tweb_penduduk.warganegara_id = 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 1 then 1 else 0 end) AS WNA_L_TAMBAH_LAHIR")
            ->selectRaw("sum(case when tweb_penduduk.sex = 2 and tweb_penduduk.warganegara_id = 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 1 then 1 else 0 end) AS WNA_P_TAMBAH_LAHIR")
            ->selectRaw("sum(case when tweb_penduduk.sex = 1 and tweb_penduduk.warganegara_id <> 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 5 then 1 else 0 end) AS WNI_L_TAMBAH_MASUK")
            ->selectRaw("sum(case when tweb_penduduk.sex = 2 and tweb_penduduk.warganegara_id <> 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 5 then 1 else 0 end) AS WNI_P_TAMBAH_MASUK")
            ->selectRaw("sum(case when tweb_penduduk.sex = 1 and tweb_penduduk.warganegara_id = 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 5 then 1 else 0 end) AS WNA_L_TAMBAH_MASUK")
            ->selectRaw("sum(case when tweb_penduduk.sex = 2 and tweb_penduduk.warganegara_id = 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 5 then 1 else 0 end) AS WNA_P_TAMBAH_MASUK")
            ->selectRaw("sum(case when tweb_penduduk.sex = 1 and tweb_penduduk.warganegara_id <> 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 2 then 1 else 0 end) AS WNI_L_KURANG_MATI")
            ->selectRaw("sum(case when tweb_penduduk.sex = 2 and tweb_penduduk.warganegara_id <> 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 2 then 1 else 0 end) AS WNI_P_KURANG_MATI")
            ->selectRaw("sum(case when tweb_penduduk.sex = 1 and tweb_penduduk.warganegara_id = 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 2 then 1 else 0 end) AS WNA_L_KURANG_MATI")
            ->selectRaw("sum(case when tweb_penduduk.sex = 2 and tweb_penduduk.warganegara_id = 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 2 then 1 else 0 end) AS WNA_P_KURANG_MATI")
            ->selectRaw("sum(case when tweb_penduduk.sex = 1 and tweb_penduduk.warganegara_id <> 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 3 then 1 else 0 end) AS WNI_L_KURANG_KELUAR")
            ->selectRaw("sum(case when tweb_penduduk.sex = 2 and tweb_penduduk.warganegara_id <> 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 3 then 1 else 0 end) AS WNI_P_KURANG_KELUAR")
            ->selectRaw("sum(case when tweb_penduduk.sex = 1 and tweb_penduduk.warganegara_id = 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 3 then 1 else 0 end) AS WNA_L_KURANG_KELUAR")
            ->selectRaw("sum(case when tweb_penduduk.sex = 2 and tweb_penduduk.warganegara_id = 2 and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} and log_penduduk.kode_peristiwa = 3 then 1 else 0 end) AS WNA_P_KURANG_KELUAR")
            ->selectRaw("(sum(case when tweb_penduduk.kk_level = 1 and log_penduduk.kode_peristiwa in (1,5) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end) - sum(case when tweb_penduduk.kk_level = 1 and log_penduduk.kode_peristiwa in (2,3,4) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end)) AS KK_JLH")
            ->selectRaw("(sum(case when tweb_penduduk.kk_level != 1 and log_penduduk.kode_peristiwa in (1,5) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end) - sum(case when tweb_penduduk.kk_level != 1 and log_penduduk.kode_peristiwa in (2,3,4) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end)) AS KK_ANG_KEL")
            ->selectRaw("(sum(case when tweb_penduduk.kk_level = 1 and log_penduduk.kode_peristiwa in (1,5) and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} then 1 else 0 end) - sum(case when tweb_penduduk.kk_level = 1 and log_penduduk.kode_peristiwa in (2,3,4) and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} then 1 else 0 end)) AS KK_MASUK_JLH")
            ->selectRaw("(sum(case when tweb_penduduk.kk_level != 1 and log_penduduk.kode_peristiwa in (1,5) and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} then 1 else 0 end) - sum(case when tweb_penduduk.kk_level != 1 and log_penduduk.kode_peristiwa in (2,3,4) and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} then 1 else 0 end)) AS KK_MASUK_ANG_KEL")
            ->join('tweb_penduduk', 'log_penduduk.id_pend', '=', 'tweb_penduduk.id')
            ->join('tweb_keluarga', 'tweb_penduduk.id_kk', '=', 'tweb_keluarga.id')
            ->leftJoin('tweb_wil_clusterdesa', 'tweb_keluarga.id_cluster', '=', 'tweb_wil_clusterdesa.id')
            ->groupBy('DUSUN');

        return $query;
    }

    /**
     * Getter penolong mati.
     *
     * @return string
     */
    public function getPenyebabKematianAttribute()
    {
        return static::PENYEBAB_KEMATIAN[$this->sebab] ?? '';
    }

    public static function kodePeristiwaAll($index): string
    {
        $result = [
            self::BARU_LAHIR        => 'Baru Lahir',
            self::MATI              => 'Mati',
            self::PINDAH_KELUAR     => 'Pindah Keluar',
            self::HILANG            => 'Hilang',
            self::BARU_PINDAH_MASUK => 'Baru Pindah Masuk',
            self::TIDAK_TETAP_PERGI => 'Tidak Tetap Pergi',
        ];

        return $result[$index] ?? '-';
    }

    public function scopeTahun($query)
    {
        return $query->selectRaw('YEAR(tgl_lapor) as tahun')->distinct()->orderBy('tahun', 'desc')->take(5);
    }

    public function refPeristiwa()
    {
        return $this->belongsTo(RefPeristiwa::class, 'kode_peristiwa', 'id')->withDefault();
    }

    public function refPindah()
    {
        return $this->belongsTo(RefPindah::class, 'ref_pindah', 'id')->withDefault();
    }
}
