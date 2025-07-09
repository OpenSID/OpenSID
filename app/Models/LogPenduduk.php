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

namespace App\Models;

use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Traits\Author;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class LogPenduduk extends BaseModel
{
    use ConfigId;
    use ShortcutCache;
    use Author;

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
     * The "booted" method of the model.
     */
    public static function boot(): void
    {
        parent::boot();

        static::updating(static function ($model): void {
            static::deleteFile($model, 'file_akta_mati');
        });

        static::deleting(static function ($model): void {
            static::deleteFile($model, 'file_akta_mati', true);
        });
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $logo = LOKASI_DOKUMEN . $model->getOriginal($file);
            if (file_exists($logo)) {
                unlink($logo);
            }
        }
    }

    /**
     * Get the post that owns the comment.
     */
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_pend', 'id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function keluarga()
    {
        return $this->hasOneThrough(Keluarga::class, Penduduk::class, 'id', 'id', 'id_pend', 'id_kk');
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
            ->selectRaw("(sum(case when (tweb_penduduk.kk_level != 1 or tweb_penduduk.kk_level is null) and log_penduduk.kode_peristiwa in (1,5) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end) - sum(case when (tweb_penduduk.kk_level != 1 or tweb_penduduk.kk_level is null) and log_penduduk.kode_peristiwa in (2,3,4) and DATE_FORMAT(log_penduduk.tgl_lapor, '%Y-%m') < '{$thn}-{$pad_bln}' then 1 else 0 end)) AS KK_ANG_KEL")
            ->selectRaw("(sum(case when tweb_penduduk.kk_level = 1 and log_penduduk.kode_peristiwa in (1,5) and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} then 1 else 0 end) - sum(case when tweb_penduduk.kk_level = 1 and log_penduduk.kode_peristiwa in (2,3,4) and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} then 1 else 0 end)) AS KK_MASUK_JLH")
            ->selectRaw("(sum(case when tweb_penduduk.kk_level != 1 and log_penduduk.kode_peristiwa in (1,5) and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} then 1 else 0 end) - sum(case when tweb_penduduk.kk_level != 1 and log_penduduk.kode_peristiwa in (2,3,4) and month(log_penduduk.tgl_lapor) = {$bln} and year(log_penduduk.tgl_lapor) = {$thn} then 1 else 0 end)) AS KK_MASUK_ANG_KEL")
            ->join('tweb_penduduk', 'log_penduduk.id_pend', '=', 'tweb_penduduk.id')
            ->leftJoin('tweb_wil_clusterdesa', 'tweb_penduduk.id_cluster', '=', 'tweb_wil_clusterdesa.id')
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
        return self::kodePeristiwa()[$index] ?? '-';
    }

    public static function kodePeristiwa(): array
    {
        return [
            self::BARU_LAHIR        => 'Baru Lahir',
            self::MATI              => 'Mati',
            self::PINDAH_KELUAR     => 'Pindah Keluar',
            self::HILANG            => 'Hilang',
            self::BARU_PINDAH_MASUK => 'Baru Pindah Masuk',
            self::TIDAK_TETAP_PERGI => 'Tidak Tetap Pergi',
        ];
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

    public function scopePeristiwaSampaiDengan($query, string $tanggal)
    {
        $configId = identitas('id');
        $subQuery = DB::raw(
            '(SELECT MAX(id) as id, id_pend from log_penduduk where config_id = ' . $configId . ' and tgl_lapor <= \'' . $tanggal . ' 23:59:59\' group by id_pend) as logMax'
        );

        return $query->join($subQuery, 'logMax.id', '=', 'log_penduduk.id');
    }

    public function pergiTerakhir()
    {
        return $this->hasOne(LogPenduduk::class, 'id_pend', 'id_pend')->whereIn('kode_peristiwa', [LogPenduduk::PINDAH_KELUAR, LogPenduduk::TIDAK_TETAP_PERGI])->orderByDesc('id');
    }

    public function isKembaliDatang()
    {
        $tgl_lapor    = Carbon::parse($this->tgl_lapor)->format('m-Y');
        $tgl_sekarang = Carbon::now()->format('m-Y');

        return $tgl_lapor < $tgl_sekarang;
    }

    public function isLogPergiTerakhir()
    {
        if (! $this->pergiTerakhir) {
            return false;
        }

        return $this->id == $this->pergiTerakhir->id;
    }

    /**
     * Kembalikan status dasar penduduk ke hidup
     *
     * @param $id_log id log penduduk
     *
     * @return void
     */
    public function kembalikan_status()
    {
        // Kembalikan status selain lahir dan masuk
        if (! in_array($this->kode_peristiwa, [LogPenduduk::BARU_LAHIR, LogPenduduk::BARU_PINDAH_MASUK])) {
            Penduduk::where('id', $this->id_pend)
                ->update([
                    'status_dasar' => StatusDasarEnum::HIDUP,
                ]);
            $penduduk = Penduduk::where('nik', $this->penduduk->nik)->where('id', '!=', $this->id_pend)->where('status_dasar', StatusDasarEnum::HIDUP)->get();

            if (! $penduduk->isEmpty()) {
                try {
                    // tambah log penduduk datang
                    LogPenduduk::create([
                        'id_pend'        => $this->id_pend,
                        'kode_peristiwa' => 1,
                        'tgl_lapor'      => date('Y-m-d'),
                        'tgl_peristiwa'  => date('Y-m-d'),
                        'ref_pindah'     => $this->ref_pindah,
                    ]);

                    foreach ($penduduk as $pindah) {
                        // ubah status Dasar selain $log->id_pend menjadi LogPenduduk::PINDAH_KELUAR
                        $pindah->update([
                            'status_dasar' => LogPenduduk::PINDAH_KELUAR,
                        ]);

                        // tambah log penduduk pindah
                        $pendudukPindah = LogPenduduk::create([
                            'id_pend'        => $pindah->id,
                            'kode_peristiwa' => 3,
                            'tgl_lapor'      => date('Y-m-d'),
                            'tgl_peristiwa'  => date('Y-m-d'),
                            'ref_pindah'     => $this->ref_pindah,
                        ]);

                        if ($pindah->id_kk) {
                            LogKeluarga::create([
                                'id_kk'           => $pindah->id_kk,
                                'id_peristiwa'    => 3,
                                'updated_by'      => ci_auth()->id,
                                'id_log_penduduk' => $pendudukPindah->id,
                            ]);
                        }
                    }
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }
            } else {
                // Hapus log_keluarga, jika terkait
                $logKeluarga = LogKeluarga::where('id_log_penduduk', $this->id)->first();
                if ($logKeluarga) {
                    $logKeluarga->delete();
                }

                // Hapus log penduduk
                $this->delete();
            }
        } else {
            throw new Exception('tidak dapat mengubah status dasar.');
        }
    }

    /**
     * Kembalikan status dasar penduduk dari PERGI ke HIDUP
     *
     * @param       $id_log id log penduduk
     * @param mixed $data
     */
    public function kembalikan_status_pergi($data = []): void
    {
        // Cek tgl lapor
        // tampilkan hanya jika beda tanggal lapor
        $tgl_lapor    = Carbon::parse($this->tgl_lapor)->format('m-Y');
        $tgl_sekarang = Carbon::now()->format('m-Y');
        if ($tgl_lapor >= $tgl_sekarang) {
            throw new Exception('Tidak dapat mengubah status dasar penduduk, karena tanggal lapor masih sama dengan tanggal sekarang.');
        }

        // Kembalikan status_dasar hanya jika penduduk pindah keluar (3) atau tidak tetap pergi (6)
        if (in_array($this->kode_peristiwa, [LogPenduduk::PINDAH_KELUAR, LogPenduduk::TIDAK_TETAP_PERGI])) {
            Penduduk::where('id', $this->id_pend)
                ->update([
                    'status_dasar' => StatusDasarEnum::HIDUP,
                ]);

            // Log Penduduk
            $logPenduduk = [
                'tgl_peristiwa'            => rev_tgl($data['tgl_peristiwa']),
                'kode_peristiwa'           => LogPenduduk::BARU_PINDAH_MASUK,
                'tgl_lapor'                => rev_tgl($data['tgl_lapor'], null),
                'id_pend'                  => $this->id_pend,
                'created_by'               => ci_auth()->id,
                'maksud_tujuan_kedatangan' => $data['maksud_tujuan'],
                'config_id'                => $this->config_id,
            ];
            LogPenduduk::upsert($logPenduduk, ['tgl_peristiwa', 'tgl_peristiwa', 'kode_peristiwa', 'id_pend', 'config_id']);

            // Log Keluarga jika kepala keluarga
            $penduduk = Penduduk::select(['id', 'id_kk', 'kk_level'])->find($this->id_pend);
            if ($penduduk->kk_level == SHDKEnum::KEPALA_KELUARGA) {
                $logKeluarga = [
                    'id_kk'         => $penduduk->id_kk,
                    'id_peristiwa'  => LogKeluarga::KELUARGA_BARU_DATANG,
                    'tgl_peristiwa' => rev_tgl($data['tgl_lapor'], null),
                    'updated_by'    => ci_auth()->id,
                    'config_id'     => $this->config_id,
                ];
                LogKeluarga::upsert($logKeluarga, ['id_kk', ['id_peristiwa', 'tgl_peristiwa', 'config_id']]);
            }
        }
    }
}
