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

namespace App\Models;

use App\Enums\PeristiwaKeluargaEnum;
use App\Enums\PeristiwaPendudukEnum;
use App\Enums\PindahEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Traits\Author;
use App\Traits\ConfigId;
use App\Traits\ShortcutCache;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

defined('BASEPATH') || exit('No direct script access allowed');

class LogPenduduk extends BaseModel
{
    use ConfigId;
    use ShortcutCache;
    use Author;

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

    public static function kodePeristiwaAll($index): string
    {
        return PeristiwaPendudukEnum::from($index)->label();
    }

    public static function kodePeristiwa(): array
    {
        return PeristiwaPendudukEnum::labels();
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

    /**
     * Mengambil data rekapitulasi mutasi penduduk per dusun untuk periode bulan/tahun tertentu.
     *
     * Query menghitung saldo awal penduduk (sebelum bulan berjalan), penambahan karena
     * lahir dan datang, pengurangan karena meninggal dan pindah/keluar, serta jumlah
     * dan mutasi Kartu Keluarga — masing-masing dipisah berdasarkan jenis kelamin (L/P)
     * dan status kewarganegaraan (WNI/WNA).
     *
     * Catatan implementasi:
     * - Filter tanggal menggunakan range literal (>= / <) agar kolom tgl_lapor
     *   tetap sargable dan index dapat dimanfaatkan oleh MySQL optimizer.
     * - Jumlah KK (KK_JLH) dan mutasi KK (KK_MASUK_JLH) dihitung via derived table
     *   yang di-JOIN sekali, bukan correlated subquery per baris, untuk menghindari
     *   eksekusi N× sesuai jumlah dusun.
     * - Parameter $filters['bulan'] dan $filters['tahun'] yang berasal dari request
     *   divalidasi ketat sebagai integer dalam rentang logis sebelum diinterpolasi
     *   ke raw SQL, karena leftJoinSub() menerima string mentah sehingga PDO binding
     *   tidak dapat digunakan pada bagian derived table.
     * - Kolom DUSUN yang bernilai NULL dinormalisasi via COALESCE agar tidak
     *   menghasilkan baris tanpa identitas dusun pada hasil GROUP BY.
     * - Ekspresi CASE WHEN dibangun secara programatik melalui closure $buildSelect
     *   untuk menghindari duplikasi dan risiko ketidakkonsistenan antar kolom.
     *
     * @param \Illuminate\Database\Eloquent\Builder         $query
     * @param array{bulan?: int|string, tahun?: int|string} $filters
     *
     * @throws InvalidArgumentException Jika tanggal yang dibangun dari filter tidak valid.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRekapitulasiList($query, $filters = [])
    {
        // Validasi dan normalisasi input bulan.
        // Cast (int) saja tidak cukup karena string seperti "1 OR 1=1" akan menjadi 1.
        // Range check memastikan hanya nilai yang masuk akal secara kalender yang diteruskan.
        $bln = (int) ($filters['bulan'] ?? date('m'));
        $thn = (int) ($filters['tahun'] ?? date('Y'));

        if ($bln < 1 || $bln > 12) {
            $bln = (int) date('m');
        }

        if ($thn < 1000 || $thn > 9999) {
            $thn = (int) date('Y');
        }

        $configId = (int) identitas('id');
        $pad_bln  = str_pad($bln, 2, '0', STR_PAD_LEFT);

        // Batas tanggal dibangun dari integer yang sudah tervalidasi.
        // Format yang dihasilkan selalu 'YYYY-MM-DD' sehingga tidak dapat diinjeksi.
        $bulanMulai = "{$thn}-{$pad_bln}-01";
        $bulanAkhir = date('Y-m-d', strtotime('+1 month', strtotime($bulanMulai)));

        if (! $bulanAkhir) {
            throw new InvalidArgumentException(
                "Tanggal tidak valid: bulan={$bln}, tahun={$thn}"
            );
        }

        // Derived table untuk menghitung jumlah KK di awal bulan per dusun.
        //
        // KK yang dihitung adalah KK yang sudah ada sebelum awal bulan ini,
        // ditentukan dengan mengecualikan KK yang pertama kali muncul di
        // log_keluarga pada atau setelah tanggal $bulanMulai. KK yang sudah
        // tidak aktif (status_dasar != 1) tetap diikutsertakan apabila ada
        // peristiwa keluar (id_peristiwa 2, 3, 4) dalam bulan berjalan,
        // agar saldo awal tidak kehilangan KK yang baru keluar bulan ini.
        //
        // Semua nilai yang diinterpolasi ($configId, $bulanMulai, $bulanAkhir)
        // sudah melewati validasi ketat di atas dan tidak dapat mengandung
        // karakter SQL berbahaya.
        $kkJlhSql = "
            SELECT
                COALESCE(w2.dusun, '__NULL__') AS dusun,
                COUNT(DISTINCT k2.id)          AS KK_JLH
            FROM tweb_keluarga k2
            JOIN tweb_penduduk p2
                ON  k2.nik_kepala = p2.id
                AND p2.config_id  = k2.config_id
            LEFT JOIN tweb_wil_clusterdesa w2
                ON p2.id_cluster = w2.id
            WHERE k2.config_id    = {$configId}
            AND p2.is_historical = 0
            AND p2.kk_level      = 1
            AND (
                p2.status_dasar = 1
                OR k2.id IN (
                    SELECT lk_d.id_kk
                    FROM   log_keluarga lk_d
                    WHERE  lk_d.config_id     = {$configId}
                        AND  lk_d.id_kk         IS NOT NULL
                        AND  lk_d.id_peristiwa  IN (2, 3, 4)
                        AND  lk_d.tgl_peristiwa >= '{$bulanMulai}'
                        AND  lk_d.tgl_peristiwa  < '{$bulanAkhir}'
                )
            )
            AND k2.id NOT IN (
                SELECT lk2.id_kk
                FROM   log_keluarga lk2
                WHERE  lk2.config_id = {$configId}
                    AND  lk2.id_kk     IS NOT NULL
                GROUP BY lk2.id_kk
                HAVING MIN(lk2.tgl_peristiwa) >= '{$bulanMulai}'
            )
            GROUP BY dusun
        ";

        // Derived table untuk menghitung net change KK dalam bulan berjalan per dusun.
        //
        // Dihitung sebagai selisih antara KK masuk (id_peristiwa 1 dan 5) dengan
        // KK keluar (id_peristiwa 2, 3, 4) selama rentang bulan ini. Mencakup kasus
        // tambahKeluargaDariPenduduk() dan pecahKK() yang hanya mencatat ke
        // log_keluarga tanpa menulis ke log_penduduk.
        $kkMasukSql = "
            SELECT
                COALESCE(w3.dusun, '__NULL__') AS dusun,
                (
                    COUNT(DISTINCT CASE WHEN lk.id_peristiwa IN (1, 5)    THEN lk.id_kk END)
                - COUNT(DISTINCT CASE WHEN lk.id_peristiwa IN (2, 3, 4) THEN lk.id_kk END)
                ) AS KK_MASUK_JLH
            FROM log_keluarga lk
            JOIN tweb_keluarga k3
                ON  lk.id_kk     = k3.id
                AND k3.config_id = lk.config_id
            JOIN tweb_penduduk p3
                ON  k3.nik_kepala = p3.id
                AND p3.config_id  = k3.config_id
            LEFT JOIN tweb_wil_clusterdesa w3
                ON p3.id_cluster = w3.id
            WHERE lk.config_id     = {$configId}
            AND lk.tgl_peristiwa >= '{$bulanMulai}'
            AND lk.tgl_peristiwa  < '{$bulanAkhir}'
            AND p3.is_historical  = 0
            GROUP BY dusun
        ";

        /**
         * Membangun ekspresi SELECT COUNT(DISTINCT CASE WHEN ...) untuk satu kombinasi
         * jenis kelamin, status kewarganegaraan, dan kode peristiwa.
         *
         * Semua argumen yang masuk ke closure ini berasal dari kode PHP sendiri,
         * bukan dari input pengguna, sehingga aman diinterpolasi ke raw SQL.
         * Satu-satunya nilai eksternal yang masuk ($bulanMulai, $bulanAkhir)
         * sudah divalidasi ketat sebelum closure ini didefinisikan.
         *
         * Untuk kolom saldo awal ($isAwal = true), ekspresi menghitung selisih antara
         * peristiwa masuk (kode 1 dan 5) dengan peristiwa keluar (kode 2, 3, 4)
         * sebelum awal bulan berjalan. Nilai THEN menggunakan id_pend * 10 + kode_peristiwa
         * sebagai pengganti CONCAT agar deduplication berbasis operasi numerik
         * yang lebih ringan daripada komparasi string.
         *
         * @param int            $sex           Jenis kelamin: 1 = laki-laki, 2 = perempuan.
         * @param bool           $wna           True untuk WNA (warganegara_id = 2), false untuk WNI.
         * @param int|int[]|null $kodePeristiwa Kode peristiwa yang dihitung. Diabaikan saat $isAwal true.
         * @param string         $alias         Nama alias kolom pada hasil SELECT.
         * @param bool           $isAwal        True untuk menghitung saldo sebelum bulan berjalan.
         *
         * @return string Ekspresi SQL siap pakai untuk selectRaw().
         */
        $buildSelect = static function (
            int $sex,
            bool $wna,
            $kodePeristiwa,
            string $alias,
            bool $isAwal = false
        ) use ($bulanMulai, $bulanAkhir): string {
            $wnaExpr = $wna
                ? 'tweb_penduduk.warganegara_id = 2'
                : 'tweb_penduduk.warganegara_id <> 2';

            if ($isAwal) {
                return "(
                    COUNT(DISTINCT CASE
                        WHEN tweb_penduduk.sex = {$sex}
                        AND {$wnaExpr}
                        AND log_penduduk.kode_peristiwa IN (1, 5)
                        AND log_penduduk.tgl_lapor < '{$bulanMulai}'
                        THEN log_penduduk.id_pend * 10 + log_penduduk.kode_peristiwa
                    END)
                - COUNT(DISTINCT CASE
                        WHEN tweb_penduduk.sex = {$sex}
                        AND {$wnaExpr}
                        AND log_penduduk.kode_peristiwa IN (2, 3, 4)
                        AND log_penduduk.tgl_lapor < '{$bulanMulai}'
                        THEN log_penduduk.id_pend * 10 + log_penduduk.kode_peristiwa
                    END)
                ) AS {$alias}";
            }

            // Setiap elemen di-cast ke int untuk memastikan tidak ada karakter SQL
            // yang bisa masuk, meskipun nilai ini berasal dari kode PHP sendiri.
            $kodeIn = implode(', ', array_map(
                'intval',
                is_array($kodePeristiwa) ? $kodePeristiwa : [$kodePeristiwa]
            ));

            return "COUNT(DISTINCT CASE
                WHEN tweb_penduduk.sex = {$sex}
                AND {$wnaExpr}
                AND log_penduduk.kode_peristiwa IN ({$kodeIn})
                AND log_penduduk.tgl_lapor >= '{$bulanMulai}'
                AND log_penduduk.tgl_lapor  < '{$bulanAkhir}'
                THEN log_penduduk.id_pend
            END) AS {$alias}";
        };

        $query
            // Dusun NULL dinormalisasi agar tidak menghasilkan baris tanpa identitas
            // pada hasil GROUP BY. Nilai '__NULL__' digunakan sebagai sentinel string.
            ->selectRaw("COALESCE(tweb_wil_clusterdesa.dusun, '__NULL__') AS DUSUN")

            // Saldo penduduk di awal bulan, dihitung dari semua log sebelum bulan berjalan.
            ->selectRaw($buildSelect(1, false, null, 'WNI_L_AWAL', true))
            ->selectRaw($buildSelect(2, false, null, 'WNI_P_AWAL', true))
            ->selectRaw($buildSelect(1, true, null, 'WNA_L_AWAL', true))
            ->selectRaw($buildSelect(2, true, null, 'WNA_P_AWAL', true))

            // Penambahan penduduk karena kelahiran (kode peristiwa 1).
            ->selectRaw($buildSelect(1, false, 1, 'WNI_L_TAMBAH_LAHIR'))
            ->selectRaw($buildSelect(2, false, 1, 'WNI_P_TAMBAH_LAHIR'))
            ->selectRaw($buildSelect(1, true, 1, 'WNA_L_TAMBAH_LAHIR'))
            ->selectRaw($buildSelect(2, true, 1, 'WNA_P_TAMBAH_LAHIR'))

            // Penambahan penduduk karena datang/pindah masuk (kode peristiwa 5).
            ->selectRaw($buildSelect(1, false, 5, 'WNI_L_TAMBAH_MASUK'))
            ->selectRaw($buildSelect(2, false, 5, 'WNI_P_TAMBAH_MASUK'))
            ->selectRaw($buildSelect(1, true, 5, 'WNA_L_TAMBAH_MASUK'))
            ->selectRaw($buildSelect(2, true, 5, 'WNA_P_TAMBAH_MASUK'))

            // Pengurangan penduduk karena kematian (kode peristiwa 2).
            ->selectRaw($buildSelect(1, false, 2, 'WNI_L_KURANG_MATI'))
            ->selectRaw($buildSelect(2, false, 2, 'WNI_P_KURANG_MATI'))
            ->selectRaw($buildSelect(1, true, 2, 'WNA_L_KURANG_MATI'))
            ->selectRaw($buildSelect(2, true, 2, 'WNA_P_KURANG_MATI'))

            // Pengurangan penduduk karena pindah/keluar (kode peristiwa 3 dan 4).
            ->selectRaw($buildSelect(1, false, [3, 4], 'WNI_L_KURANG_KELUAR'))
            ->selectRaw($buildSelect(2, false, [3, 4], 'WNI_P_KURANG_KELUAR'))
            ->selectRaw($buildSelect(1, true, [3, 4], 'WNA_L_KURANG_KELUAR'))
            ->selectRaw($buildSelect(2, true, [3, 4], 'WNA_P_KURANG_KELUAR'))

            // Jumlah dan mutasi KK diambil dari derived table yang sudah di-GROUP BY dusun.
            // MAX() diperlukan untuk mematuhi sql_mode=only_full_group_by karena
            // kolom ini berasal dari JOIN, bukan dari aggregate query utama.
            // (Dipakai sebagai pengganti ANY_VALUE() agar kompatibel dengan MariaDB
            // versi lama yang belum mendukung ANY_VALUE().)
            // Penggunaannya aman secara semantik karena derived table menjamin
            // tepat satu nilai per dusun.
            ->selectRaw('MAX(COALESCE(kk_jlh_dt.KK_JLH, 0)) AS KK_JLH')
            ->selectRaw('MAX(COALESCE(kk_masuk_dt.KK_MASUK_JLH, 0)) AS KK_MASUK_JLH')

            ->join('tweb_penduduk', 'log_penduduk.id_pend', '=', 'tweb_penduduk.id')
            ->leftJoin('tweb_wil_clusterdesa', 'tweb_penduduk.id_cluster', '=', 'tweb_wil_clusterdesa.id')

            // Derived table KK dihitung sekali dan di-JOIN berdasarkan kecocokan dusun,
            // menggantikan correlated subquery yang sebelumnya dieksekusi per baris hasil GROUP BY.
            ->leftJoinSub($kkJlhSql, 'kk_jlh_dt', static function ($join) {
                $join->on(
                    DB::raw("COALESCE(tweb_wil_clusterdesa.dusun, '__NULL__')"),
                    '=',
                    'kk_jlh_dt.dusun'
                );
            })
            ->leftJoinSub($kkMasukSql, 'kk_masuk_dt', static function ($join) {
                $join->on(
                    DB::raw("COALESCE(tweb_wil_clusterdesa.dusun, '__NULL__')"),
                    '=',
                    'kk_masuk_dt.dusun'
                );
            })
            ->where('tweb_penduduk.config_id', $configId)
            ->where('log_penduduk.config_id', $configId)
            ->groupBy(DB::raw("COALESCE(tweb_wil_clusterdesa.dusun, '__NULL__')"));

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

    public function scopeTahun($query)
    {
        return $query->selectRaw('YEAR(tgl_lapor) as tahun')->distinct()->orderBy('tahun', 'desc')->take(5);
    }

    public function getTujuanPindahAttribute(): string
    {
        return PindahEnum::valueOf($this->attributes['ref_pindah'] ?? null) ?: '';
    }

    public function scopePeristiwaSampaiDengan($query, string $tanggal)
    {
        $configId = identitas('id');
        $subQuery = DB::raw(
            '(SELECT MAX(id) as id, id_pend from log_penduduk where config_id = ' . $configId . ' and tgl_peristiwa <= \'' . $tanggal . ' 23:59:59\' group by id_pend) as logMax'
        );

        return $query->join($subQuery, 'logMax.id', '=', 'log_penduduk.id');
    }

    public function pergiTerakhir()
    {
        return $this->hasOne(LogPenduduk::class, 'id_pend', 'id_pend')->whereIn('kode_peristiwa', [PeristiwaPendudukEnum::PINDAH_KELUAR->value, PeristiwaPendudukEnum::TIDAK_TETAP_PERGI->value])->orderByDesc('id');
    }

    public function isKembaliDatang(): bool
    {
        return $this->tgl_lapor?->endOfMonth()?->isPast() ?? false;
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
     */
    public function kembalikan_status(): void
    {
        // Kembalikan status selain lahir dan masuk
        if (! in_array($this->kode_peristiwa, [PeristiwaPendudukEnum::BARU_LAHIR->value, PeristiwaPendudukEnum::BARU_PINDAH_MASUK->value])) {
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
                        // ubah status Dasar selain $log->id_pend menjadi PeristiwaPendudukEnum::PINDAH_KELUAR->value
                        $pindah->update([
                            'status_dasar' => PeristiwaPendudukEnum::PINDAH_KELUAR->value,
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
                                'id_peristiwa'    => PeristiwaKeluargaEnum::KEPALA_KELUARGA_PINDAH->value,
                                'updated_by'      => ci_auth()->id,
                                'id_log_penduduk' => $pendudukPindah->id,
                            ]);
                        }
                    }
                } catch (Exception $e) {
                    throw new Exception($e->getMessage(), $e->getCode(), $e);
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
     * @param $id_log id log penduduk
     */
    public function kembalikan_status_pergi(mixed $data = []): void
    {
        // Cek tgl lapor
        // tampilkan hanya jika beda tanggal lapor (bulan telah berganti)
        if (! ($this->tgl_lapor?->endOfMonth()->isPast() ?? false)) {
            throw new Exception('Tidak dapat mengubah status dasar penduduk, karena tanggal lapor masih sama dengan tanggal sekarang.');
        }

        // Kembalikan status_dasar hanya jika penduduk pindah keluar (3) atau tidak tetap pergi (6)
        if (in_array($this->kode_peristiwa, [PeristiwaPendudukEnum::PINDAH_KELUAR->value, PeristiwaPendudukEnum::TIDAK_TETAP_PERGI->value])) {
            Penduduk::where('id', $this->id_pend)
                ->update([
                    'status_dasar' => StatusDasarEnum::HIDUP,
                ]);

            // Log Penduduk
            $logPenduduk = [
                'tgl_peristiwa'            => rev_tgl($data['tgl_peristiwa']),
                'kode_peristiwa'           => PeristiwaPendudukEnum::BARU_PINDAH_MASUK->value,
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
                    'id_peristiwa'  => PeristiwaKeluargaEnum::KELUARGA_BARU_DATANG->value,
                    'tgl_peristiwa' => rev_tgl($data['tgl_lapor'], null),
                    'updated_by'    => ci_auth()->id,
                    'config_id'     => $this->config_id,
                ];
                LogKeluarga::upsert($logKeluarga, ['id_kk', ['id_peristiwa', 'tgl_peristiwa', 'config_id']]);
            }
        }
    }
}
