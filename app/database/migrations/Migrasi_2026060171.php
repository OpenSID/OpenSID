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
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {

    use Migrator;

    /**
     * Run the migrations.
     *
     * Memicu pemindaian ulang folder tema agar tema baru (mis. Palanta)
     * yang ditambahkan ke storage/app/themes ikut tercatat di tabel `theme`
     * pada instalasi yang sudah ada (proses update), bukan hanya pada
     * instalasi baru yang menjalankan DataDinamisSeeder.
     */
    public function up(): void
    {
        $this->pindaiTemaBaru();
        $this->tambahTabelBaseLinesDanKolomPekerjaMigran();
        $this->refreshViewPendudukHidup();
        $this->buatTabelWilayah();
        $this->tambahPengaturanHeaderDispenduk();
        $this->tambahKolomWarnaDanBorderConfig();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    private function pindaiTemaBaru(): void
    {
        try {
            theme_scan();
        } catch (Exception $e) {
            // Log error tapi jangan crash migration
            Log::error('Pindai tema gagal pada Migrasi_2026060171: ' . $e->getMessage());
        }
    }

    private function tambahTabelBaseLinesDanKolomPekerjaMigran(): void
    {
        $this->runMigration([
            'install/2025_12_22_080511_create_security_baselines_table',
            'install/2025_12_22_080514_add_foreign_keys_to_security_baselines_table',
        ]);

        if (Schema::hasTable('tweb_penduduk') && ! Schema::hasColumn('tweb_penduduk', 'pekerja_migran')) {
            Schema::table('tweb_penduduk', static function (Blueprint $table) {
                $table->string('pekerja_migran')->nullable()->after('adat');
            });
        }
    }

    /**
     * Buat ulang view `penduduk_hidup` agar memuat kolom `pekerja_migran`.
     *
     * View bersifat statis: pada database hasil upgrade, view sudah dibuat
     * sebelum kolom `pekerja_migran` ada, sehingga query statistik
     * (LaporanPenduduk: SELECT u.pekerja_migran FROM penduduk_hidup) gagal.
     * Drop + create ulang dari definisi install terbaru menyelaraskan view
     * dengan struktur tabel `tweb_penduduk` saat ini.
     */
    private function refreshViewPendudukHidup(): void
    {
        try {
            DB::statement('DROP VIEW IF EXISTS `penduduk_hidup`');
            $this->runMigration([
                'install/2025_12_22_080512_create_penduduk_hidup_view',
            ]);
        } catch (Exception $e) {
            // Log error tapi jangan crash migration
            Log::error('Refresh view penduduk_hidup gagal pada Migrasi_2026060171: ' . $e->getMessage());
        }
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

    private function tambahKolomWarnaDanBorderConfig(): void
    {
        if (Schema::hasTable('config')) {
            Schema::table('config', static function ($table): void {
                if (! Schema::hasColumn('config', 'warna')) {
                    $table->string('warna', 25)->nullable()->after('kantor_desa');
                }
                if (! Schema::hasColumn('config', 'border')) {
                    $table->string('border', 25)->nullable()->after('warna');
                }
            });
        }
    }
};
