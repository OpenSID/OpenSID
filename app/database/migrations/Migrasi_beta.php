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

use App\Models\ArtikelWilayah;
use App\Models\KategoriWilayah;
use App\Traits\Migrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->buatTabelWilayah();
        $this->backfillArtikelDanKategoriWilayah();
        $this->tambahPengaturanHeaderDispenduk();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikel_wilayah');
        Schema::dropIfExists('kategori_wilayah');
    }

    public function tambahPengaturanHeaderDispenduk(): void
    {
        $this->createSetting([
            'judul'      => 'Header Surat Dispenduk',
            'key'        => 'header_surat_dispenduk',
            'value'      => '',
            'keterangan' => 'Header surat untuk Dinas Kependudukan dan Pencatatan Sipil',
            'jenis'      => 'input-text',
            'option'     => null,
            'kategori'   => 'format_surat',
            'attribute'  => json_encode([]),
        ]);
    }

    /**
     * Tahap 1: Buat tabel artikel_wilayah dan kategori_wilayah.
     */
    private function buatTabelWilayah(): void
    {
        $this->runMigration([
            'install/2026_05_20_000001_create_artikel_wilayah_table',
            'install/2026_05_20_000002_create_kategori_wilayah_table',
        ]);
    }

    /**
     * Tahap 2: Backfill data eksisting config_id IS NULL ke tabel jembatan.
     *
     * Strategi: untuk setiap artikel/kategori config_id IS NULL yang BELUM punya
     * entry di tabel jembatan, buat mapping ke semua kabupaten & provinsi yang
     * terdaftar di tabel config. Ini menjaga perilaku lama (semua desa tetap
     * melihat semua konten OpenKab/OpenProvinsi yang sudah ada).
     *
     * Idempotent: menggunakan insertOrIgnore + filter NOT IN subquery.
     */
    private function backfillArtikelDanKategoriWilayah(): void
    {
        if (! Schema::hasTable('artikel_wilayah') || ! Schema::hasTable('kategori_wilayah')) {
            $msg = 'Backfill wilayah dilewati: tabel jembatan belum ada.';
            Log::warning($msg);
            log_message('error', $msg);

            return;
        }

        $kodeKabupaten = DB::table('config')
            ->whereNotNull('kode_kabupaten')
            ->where('kode_kabupaten', '!=', '')
            ->distinct()
            ->pluck('kode_kabupaten');

        $kodePropinsi = DB::table('config')
            ->whereNotNull('kode_propinsi')
            ->where('kode_propinsi', '!=', '')
            ->distinct()
            ->pluck('kode_propinsi');

        // Jika tidak ada kode wilayah, skip backfill
        if ($kodeKabupaten->isEmpty() && $kodePropinsi->isEmpty()) {
            $msg = 'Backfill wilayah dilewati: tidak ada kode wilayah di tabel config.';
            Log::info($msg);
            log_message('notice', $msg);

            return;
        }

        $now = now();

        // Backfill artikel_wilayah
        $this->backfillTabel(
            'artikel',
            'artikel_wilayah',
            'artikel_id',
            $kodeKabupaten,
            $kodePropinsi,
            $now
        );

        // Backfill kategori_wilayah
        $this->backfillTabel(
            'kategori',
            'kategori_wilayah',
            'kategori_id',
            $kodeKabupaten,
            $kodePropinsi,
            $now
        );
    }

    /**
     * Backfill satu tabel sumber ke tabel jembatan.
     *
     * Hanya memproses baris config_id IS NULL yang belum punya entry
     * di tabel jembatan (idempotent — aman diulang-ulang).
     *
     * @param mixed $kodeKabupaten
     * @param mixed $kodePropinsi
     * @param mixed $now
     */
    private function backfillTabel(
        string $sumberTable,
        string $pivotTable,
        string $foreignKey,
        $kodeKabupaten,
        $kodePropinsi,
        $now
    ): void {
        $tingkatKabupaten = ($pivotTable === 'artikel_wilayah')
            ? ArtikelWilayah::TINGKAT_KABUPATEN
            : KategoriWilayah::TINGKAT_KABUPATEN;
        $tingkatProvinsi = ($pivotTable === 'artikel_wilayah')
            ? ArtikelWilayah::TINGKAT_PROVINSI
            : KategoriWilayah::TINGKAT_PROVINSI;

        DB::table($sumberTable)
            ->whereNull('config_id')
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, static function ($rows) use ($pivotTable, $foreignKey, $kodeKabupaten, $kodePropinsi, $now, $tingkatKabupaten, $tingkatProvinsi) {
                $insert = [];

                foreach ($rows as $row) {
                    foreach ($kodeKabupaten as $kk) {
                        $insert[] = [
                            $foreignKey       => $row->id,
                            'tingkat_wilayah' => $tingkatKabupaten,
                            'kode_wilayah'    => $kk,
                            'created_at'      => $now,
                            'updated_at'      => $now,
                        ];
                    }

                    foreach ($kodePropinsi as $kp) {
                        $insert[] = [
                            $foreignKey       => $row->id,
                            'tingkat_wilayah' => $tingkatProvinsi,
                            'kode_wilayah'    => $kp,
                            'created_at'      => $now,
                            'updated_at'      => $now,
                        ];
                    }
                }

                if (! empty($insert)) {
                    // insertOrIgnore untuk idempoten jika UNIQUE constraint sudah ada
                    foreach (array_chunk($insert, 500) as $batch) {
                        DB::table($pivotTable)->insertOrIgnore($batch);
                    }
                }
            });

        $count = DB::table($pivotTable)->count();
        $msg   = "Backfill {$pivotTable} selesai. Total row: {$count}";
        Log::info($msg);
        log_message('notice', $msg);
    }
};
