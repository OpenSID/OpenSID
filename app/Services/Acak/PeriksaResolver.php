<?php

declare(strict_types=1);

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

namespace App\Services\Acak;

use App\Enums\SHDKEnum;
use App\Libraries\Periksa;
use App\Models\SuplemenTerdata;
use App\Models\User;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Menyelesaikan otomatis masalah yang dideteksi halaman /periksa, sebagai bagian
 * dari proses Acak agar database demo lolos pemeriksaan.
 *
 * Dua kelompok:
 *  1. Masalah "penghakiman" yang tak di-auto-fix pustaka Periksa (data_null,
 *     kepala_keluarga_ganda, log_keluarga_ganda) — diselesaikan deterministik
 *     di kelas ini.
 *  2. Perbaikan aman/generik yang sudah ada di App\Libraries\Periksa::perbaiki()
 *     (collation, view, log_penduduk, nik_kepala_bukan_kepala_keluarga, relasi
 *     artikel, dll.) — cukup dipicu.
 *
 * perbaiki() dapat memunculkan kembali dua masalah kelompok 1, jadi keduanya
 * dijalankan ulang setelahnya (konvergensi).
 *
 * Bergantung pada Periksa yang butuh CI3 (identitas()/ci_auth()/kades()); guard
 * konteks web ada di PeriksaResolverSanitizer.
 */
class PeriksaResolver
{
    private int $configId;

    public function __construct(?int $configId = null)
    {
        $this->configId = $configId ?? (int) identitas('id');
    }

    /**
     * @return array{resolved: array<string, int>, residual: list<string>}
     */
    public function resolve(): array
    {
        $resolved = [];

        // Kelompok 1: masalah "penghakiman" yang tidak di-auto-fix pustaka Periksa.
        $this->step($resolved, 'data_null', fn (): int               => $this->resolveDataNull());
        $this->step($resolved, 'kepala_keluarga_ganda', fn (): int   => $this->resolveKepalaKeluargaGanda());
        $this->step($resolved, 'klasifikasi_surat_ganda', fn (): int => $this->resolveKlasifikasiSuratGanda());
        $this->step($resolved, 'kepala_rtm_ganda', fn (): int        => $this->resolveKepalaRtmGanda());
        $this->step($resolved, 'log_keluarga_ganda', fn (): int      => $this->resolveLogKeluargaGanda());
        $this->step($resolved, 'data_duplikatartikel', fn (): int    => $this->resolveDuplikatArtikel());
        $this->step($resolved, 'artikel_orphan', fn (): int          => $this->resolveArtikelOrphan());
        $this->step($resolved, 'data_cluster', fn (): int            => $this->resolveDataCluster());
        $this->step($resolved, 'menu_tanpa_parent', fn (): int       => $this->resolveMenuTanpaParent());
        $this->step($resolved, 'suplemen_terdata_kosong', fn (): int => $this->resolveSuplemenKosong());

        // Kelompok 2: perbaikan aman/generik dari pustaka Periksa.
        try {
            (new Periksa())->perbaiki();
        } catch (Throwable $e) {
            Log::warning('[PeriksaResolver] perbaiki pustaka gagal: ' . $e->getMessage());
        }

        // Konvergensi: perbaiki() bisa memunculkan kembali kedua masalah ini.
        $this->step($resolved, 'kepala_keluarga_ganda_pass2', fn (): int => $this->resolveKepalaKeluargaGanda());
        $this->step($resolved, 'log_keluarga_ganda_pass2', fn (): int    => $this->resolveLogKeluargaGanda());

        return ['resolved' => $resolved, 'residual' => $this->residual()];
    }

    /**
     * @param array<string, int> $resolved
     * @param Closure(): int      $fn
     */
    private function step(array &$resolved, string $key, Closure $fn): void
    {
        try {
            $n = $fn();
            if ($n > 0) {
                $resolved[$key] = $n;
            }
        } catch (Throwable $e) {
            Log::warning("[PeriksaResolver] resolve {$key} gagal: " . $e->getMessage());
        }
    }

    /**
     * @return list<string>
     */
    private function residual(): array
    {
        try {
            return array_values(array_unique((new Periksa())->getPeriksa()['masalah'] ?? []));
        } catch (Throwable $e) {
            Log::warning('[PeriksaResolver] verifikasi gagal: ' . $e->getMessage());

            return [];
        }
    }

    /**
     * Isi kolom wajib penduduk yang NULL: kolom referensi → nilai modus/fallback,
     * kolom teks → placeholder, kk_level → peran keluarga.
     */
    public function resolveDataNull(): int
    {
        $count = 0;

        $refColumns = [
            'agama_id'          => 1,
            'pendidikan_kk_id'  => 1,
            'pekerjaan_id'      => 1,
            'golongan_darah_id' => 13,
            'status_kawin'      => 1,
            'warganegara_id'    => 1,
            'sex'               => 1,
        ];

        foreach ($refColumns as $col => $fallback) {
            if (! $this->hasColumn($col)) {
                continue;
            }

            $mode = DB::table('tweb_penduduk')
                ->where('config_id', $this->configId)
                ->whereNotNull($col)
                ->groupBy($col)
                ->orderByRaw('COUNT(*) DESC')
                ->value($col);

            $count += DB::table('tweb_penduduk')
                ->where('config_id', $this->configId)
                ->whereNull($col)
                ->update([$col => $mode ?? $fallback]);
        }

        $textColumns = [
            'nama'            => '-',
            'tempatlahir'     => '-',
            'nama_ayah'       => '',
            'nama_ibu'        => '',
            'dokumen_pasport' => '-',
            'dokumen_kitas'   => '-',
        ];

        foreach ($textColumns as $col => $val) {
            if (! $this->hasColumn($col)) {
                continue;
            }

            $count += DB::table('tweb_penduduk')
                ->where('config_id', $this->configId)
                ->whereNull($col)
                ->update([$col => $val]);
        }

        $count += DB::table('tweb_penduduk')
            ->where('config_id', $this->configId)
            ->where(static function ($q): void {
                $q->whereNull('tanggallahir')->orWhereRaw("CAST(tanggallahir AS CHAR) = '0000-00-00'");
            })
            ->update(['tanggallahir' => '1990-01-01']);

        return $count + $this->backfillKkLevel();
    }

    /**
     * Isi kk_level (SHDK) yang NULL: perempuan pada KK berkepala laki-laki tanpa
     * istri → ISTRI (maks satu per KK); sisanya → FAMILI_LAIN.
     */
    private function backfillKkLevel(): int
    {
        $count = 0;

        $candidates = DB::table('tweb_penduduk as p')
            ->where('p.config_id', $this->configId)
            ->whereNull('p.kk_level')
            ->where('p.sex', 2)
            ->whereNotNull('p.id_kk')
            ->whereExists(function ($q): void {
                $q->select(DB::raw(1))->from('tweb_penduduk as h')
                    ->whereColumn('h.id_kk', 'p.id_kk')
                    ->where('h.config_id', $this->configId)
                    ->where('h.kk_level', SHDKEnum::KEPALA_KELUARGA)
                    ->where('h.sex', 1);
            })
            ->whereNotExists(function ($q): void {
                $q->select(DB::raw(1))->from('tweb_penduduk as i')
                    ->whereColumn('i.id_kk', 'p.id_kk')
                    ->where('i.config_id', $this->configId)
                    ->where('i.kk_level', SHDKEnum::ISTRI);
            })
            ->orderBy('p.id_kk')
            ->orderBy('p.id')
            ->get(['p.id', 'p.id_kk']);

        $seenKk   = [];
        $istriIds = [];

        foreach ($candidates as $r) {
            if (isset($seenKk[$r->id_kk])) {
                continue;
            }
            $seenKk[$r->id_kk] = true;
            $istriIds[]        = $r->id;
        }

        if ($istriIds !== []) {
            $count += DB::table('tweb_penduduk')->whereIn('id', $istriIds)->update(['kk_level' => SHDKEnum::ISTRI]);
        }

        return $count + DB::table('tweb_penduduk')
            ->where('config_id', $this->configId)
            ->whereNull('kk_level')
            ->update(['kk_level' => SHDKEnum::FAMILI_LAIN]);
    }

    /**
     * Tiap KK dengan >1 kepala: pilih kepala laki-laki tertua (fallback tertua),
     * demosikan sisanya, dan arahkan ulang tweb_keluarga.nik_kepala ke survivor.
     */
    public function resolveKepalaKeluargaGanda(): int
    {
        $dupeKkIds = DB::table('tweb_penduduk')
            ->where('config_id', $this->configId)
            ->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
            ->whereNotNull('id_kk')
            ->select('id_kk')
            ->groupBy('id_kk')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('id_kk');

        $count = 0;

        foreach ($dupeKkIds as $idKk) {
            $heads = DB::table('tweb_penduduk')
                ->where('config_id', $this->configId)
                ->where('id_kk', $idKk)
                ->where('kk_level', SHDKEnum::KEPALA_KELUARGA)
                ->get(['id', 'sex', 'tanggallahir']);

            $survivor = $this->pickHead($heads);
            $hasIstri = DB::table('tweb_penduduk')
                ->where('config_id', $this->configId)
                ->where('id_kk', $idKk)
                ->where('kk_level', SHDKEnum::ISTRI)
                ->exists();

            foreach ($heads as $h) {
                if ((int) $h->id === (int) $survivor->id) {
                    continue;
                }

                if ((int) $h->sex === 2 && ! $hasIstri) {
                    $newLevel = SHDKEnum::ISTRI;
                    $hasIstri = true;
                } else {
                    $newLevel = SHDKEnum::FAMILI_LAIN;
                }

                DB::table('tweb_penduduk')->where('id', $h->id)->update(['kk_level' => $newLevel]);
                $count++;
            }

            DB::table('tweb_keluarga')
                ->where('config_id', $this->configId)
                ->where('id', $idKk)
                ->update(['nik_kepala' => $survivor->id]);
        }

        return $count;
    }

    /**
     * Tiap (id_kk, tgl_peristiwa) log_keluarga ganda: simpan satu (utamakan baris
     * daftar id_peristiwa=1, lalu id terkecil), hapus sisanya.
     */
    public function resolveLogKeluargaGanda(): int
    {
        $dupes = DB::table('log_keluarga')
            ->where('config_id', $this->configId)
            ->whereNotNull('id_kk')
            ->select('id_kk', 'tgl_peristiwa')
            ->groupBy('id_kk', 'tgl_peristiwa')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $count = 0;

        foreach ($dupes as $d) {
            $ids = DB::table('log_keluarga')
                ->where('config_id', $this->configId)
                ->where('id_kk', $d->id_kk)
                ->where('tgl_peristiwa', $d->tgl_peristiwa)
                ->orderByRaw('(id_peristiwa = 1) DESC')
                ->orderBy('id')
                ->pluck('id');

            $losers = $ids->slice(1)->values()->all();

            if ($losers !== []) {
                $count += DB::table('log_keluarga')->whereIn('id', $losers)->delete();
            }
        }

        return $count;
    }

    /**
     * Tiap kode klasifikasi surat ganda: simpan id terkecil, arahkan ulang FK,
     * hapus duplikat.
     */
    public function resolveKlasifikasiSuratGanda(): int
    {
        $dupeKodes = DB::table('klasifikasi_surat')
            ->where('config_id', $this->configId)
            ->select('kode')
            ->groupBy('kode')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('kode');

        $count = 0;

        foreach ($dupeKodes as $kode) {
            $ids = DB::table('klasifikasi_surat')
                ->where('config_id', $this->configId)
                ->where('kode', $kode)
                ->orderBy('id')
                ->pluck('id');

            $survivor = $ids->first();
            $losers   = $ids->slice(1)->values()->all();

            if ($losers === []) {
                continue;
            }

            $this->repointReferences('klasifikasi_surat', 'id', $survivor, $losers);
            $count += DB::table('klasifikasi_surat')->whereIn('id', $losers)->delete();
        }

        return $count;
    }

    /**
     * Tiap nik_kepala RTM ganda: simpan RTM yang cocok dengan id_rtm kepala
     * (fallback id terkecil), arahkan ulang FK, hapus sisanya.
     */
    public function resolveKepalaRtmGanda(): int
    {
        $dupeNiks = DB::table('tweb_rtm')
            ->where('config_id', $this->configId)
            ->whereNotNull('nik_kepala')
            ->select('nik_kepala')
            ->groupBy('nik_kepala')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('nik_kepala');

        $count = 0;

        foreach ($dupeNiks as $nik) {
            $rows = DB::table('tweb_rtm')
                ->where('config_id', $this->configId)
                ->where('nik_kepala', $nik)
                ->orderBy('id')
                ->get(['id', 'no_kk']);

            // tweb_rtm.nik_kepala menyimpan id penduduk kepala.
            $headIdRtm = DB::table('tweb_penduduk')->where('id', $nik)->value('id_rtm');
            $survivor  = $rows->firstWhere('no_kk', $headIdRtm) ?? $rows->first();
            $losers    = $rows->where('id', '!=', $survivor->id)->pluck('id')->all();

            if ($losers === []) {
                continue;
            }

            $this->repointReferences('tweb_rtm', 'id', $survivor->id, $losers);
            $count += DB::table('tweb_rtm')->whereIn('id', $losers)->delete();
        }

        return $count;
    }

    /**
     * Tiap slug artikel ganda: simpan id terkecil. Duplikat berkonten identik
     * (judul + isi) dihapus; yang berbeda dipertahankan dengan slug unik baru.
     */
    public function resolveDuplikatArtikel(): int
    {
        $dupeSlugs = DB::table('artikel')
            ->where('config_id', $this->configId)
            ->select('slug')
            ->groupBy('slug')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('slug');

        $count = 0;

        foreach ($dupeSlugs as $slug) {
            $rows = DB::table('artikel')
                ->where('config_id', $this->configId)
                ->where('slug', $slug)
                ->orderBy('id')
                ->get(['id', 'judul', 'isi']);

            $keep = $rows->first();

            foreach ($rows->slice(1) as $r) {
                $identical = trim((string) $r->judul) === trim((string) $keep->judul)
                    && trim((string) $r->isi) === trim((string) $keep->isi);

                if ($identical) {
                    DB::table('artikel')->where('id', $r->id)->delete();
                } else {
                    DB::table('artikel')->where('id', $r->id)->update(['slug' => $this->uniqueSlug((string) $slug)]);
                }

                $count++;
            }
        }

        return $count;
    }

    /**
     * Bersihkan artikel dengan id_kategori/id_user menggantung (FK ditambahkan
     * pustaka Periksa setelah orphan bersih).
     */
    public function resolveArtikelOrphan(): int
    {
        $count = 0;

        $count += DB::table('artikel')
            ->where('config_id', $this->configId)
            ->where(static function ($q): void {
                $q->where('id_kategori', 0)
                    ->orWhere(static function ($q2): void {
                        $q2->whereNotNull('id_kategori')
                            ->whereNotIn('id_kategori', static fn ($sub) => $sub->from('kategori')->select('id'));
                    });
            })
            ->update(['id_kategori' => null, 'tipe' => 'dinamis']);

        $adminId = User::withoutGlobalScopes()
            ->where('config_id', $this->configId)
            ->where('active', 1)
            ->orderBy('id')
            ->value('id');

        $count += DB::table('artikel')
            ->where('config_id', $this->configId)
            ->whereNotNull('id_user')
            ->whereNotIn('id_user', static fn ($sub) => $sub->from('user')->select('id'))
            ->update(['id_user' => $adminId]);

        return $count;
    }

    /**
     * Samakan ejaan dusun yang hanya beda huruf besar/kecil ke bentuk kanonik
     * (paling sering muncul).
     */
    public function resolveDataCluster(): int
    {
        $dusunList = DB::table('tweb_wil_clusterdesa')
            ->where('config_id', $this->configId)
            ->whereNotNull('dusun')
            ->where('dusun', '!=', '')
            ->pluck('dusun');

        $groups = [];

        foreach ($dusunList as $d) {
            $key            = mb_strtolower(trim((string) $d));
            $groups[$key][] = (string) $d;
        }

        $count = 0;

        foreach ($groups as $variants) {
            if (count(array_unique($variants)) < 2) {
                continue;
            }

            $freq = array_count_values($variants);
            arsort($freq);
            $canonical = (string) array_key_first($freq);

            foreach (array_unique($variants) as $variant) {
                if ($variant === $canonical) {
                    continue;
                }

                $count += DB::table('tweb_wil_clusterdesa')
                    ->where('config_id', $this->configId)
                    ->where('dusun', $variant)
                    ->update(['dusun' => $canonical]);
            }
        }

        return $count;
    }

    /**
     * Naikkan menu yang parent-nya hilang menjadi menu tingkat atas (parrent=0).
     */
    public function resolveMenuTanpaParent(): int
    {
        $orphanIds = DB::table('menu')
            ->where('parrent', '>', 0)
            ->whereNotIn('parrent', static fn ($sub) => $sub->from('menu')->select('id'))
            ->pluck('id');

        if ($orphanIds->isEmpty()) {
            return 0;
        }

        return DB::table('menu')->whereIn('id', $orphanIds->all())->update(['parrent' => 0]);
    }

    /**
     * Hapus suplemen_terdata yang sasarannya (penduduk/keluarga) sudah tidak ada.
     */
    public function resolveSuplemenKosong(): int
    {
        $count = 0;

        $count += DB::table('suplemen_terdata')
            ->where('config_id', $this->configId)
            ->where('sasaran', SuplemenTerdata::PENDUDUK)
            ->where(static function ($q): void {
                $q->whereNull('penduduk_id')
                    ->orWhereNotIn('penduduk_id', static fn ($sub) => $sub->from('tweb_penduduk')->select('id'));
            })
            ->delete();

        $count += DB::table('suplemen_terdata')
            ->where('config_id', $this->configId)
            ->where('sasaran', SuplemenTerdata::KELUARGA)
            ->where(static function ($q): void {
                $q->whereNull('keluarga_id')
                    ->orWhereNotIn('keluarga_id', static fn ($sub) => $sub->from('tweb_keluarga')->select('id'));
            })
            ->delete();

        return $count;
    }

    private function uniqueSlug(string $base): string
    {
        $i = 2;

        do {
            $candidate = $base . '-' . $i;
            $exists    = DB::table('artikel')
                ->where('config_id', $this->configId)
                ->where('slug', $candidate)
                ->exists();
            $i++;
        } while ($exists);

        return $candidate;
    }

    /**
     * Arahkan ulang seluruh kolom FK yang mereferensikan $refTable.$refColumn dari
     * $loserIds ke $survivorId. Aman bila tidak ada FK (no-op).
     *
     * @param list<mixed> $loserIds
     */
    private function repointReferences(string $refTable, string $refColumn, mixed $survivorId, array $loserIds): void
    {
        if ($loserIds === []) {
            return;
        }

        $fks = DB::table('INFORMATION_SCHEMA.KEY_COLUMN_USAGE')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('REFERENCED_TABLE_NAME', $refTable)
            ->where('REFERENCED_COLUMN_NAME', $refColumn)
            ->get(['TABLE_NAME', 'COLUMN_NAME']);

        foreach ($fks as $fk) {
            DB::table($fk->TABLE_NAME)
                ->whereIn($fk->COLUMN_NAME, $loserIds)
                ->update([$fk->COLUMN_NAME => $survivorId]);
        }
    }

    /**
     * @param Collection<int, \stdClass> $heads
     */
    private function pickHead(Collection $heads): object
    {
        $males = $heads->filter(static fn ($h): bool => (int) $h->sex === 1 && ! empty($h->tanggallahir));
        $pool  = $males->isNotEmpty() ? $males : $heads;

        return $pool->sort(static function ($a, $b): int {
            $da = $a->tanggallahir ?: '9999-12-31';
            $db = $b->tanggallahir ?: '9999-12-31';

            return [$da, (int) $a->id] <=> [$db, (int) $b->id];
        })->first();
    }

    private function hasColumn(string $column): bool
    {
        return Schema::hasColumn('tweb_penduduk', $column);
    }
}
