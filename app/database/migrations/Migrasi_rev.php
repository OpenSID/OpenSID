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

use App\Traits\Migrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->hapusDuplikatSurat();
        $this->tambahKolomLuarDesaKelompokAnggota();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    public function hapusDuplikatSurat()
    {
        // Hapus surat TinyMCE lama dengan url_surat format 'surat-*'
        // yang dihasilkan oleh tambah_surat_tinymce() versi lama (sebelum fix).
        // Daftar url_surat legacy dibangun dari nama surat di JSON (getSuratBawaanTinyMCE),
        // sehingga hanya menghapus yang memang punya padanan bawaan, bukan semua 'surat-*'.
        $legacyUrls = getSuratBawaanTinyMCE()
            ->map(fn ($surat) => 'surat-' . url_title($surat['nama'], '-', true))
            ->values()
            ->all();

        if (! empty($legacyUrls)) {
            FormatSurat::withoutGlobalScope(RemoveRtfScope::class)
                ->whereIn('jenis', FormatSurat::RTF)
                ->whereIn('url_surat', $legacyUrls)
                ->delete();
        }
    }

    public function tambahKolomLuarDesaKelompokAnggota(): void
    {
        if (! Schema::hasTable('kelompok_anggota') || Schema::hasColumn('kelompok_anggota', 'nama_luar')) {
            return;
        }

        // Drop FK id_penduduk agar bisa diubah menjadi nullable
        $this->hapusForeignKey('kelompok_anggota_penduduk_fk', 'kelompok_anggota', 'tweb_penduduk');

        Schema::table('kelompok_anggota', static function (Blueprint $table) {
            $table->integer('id_penduduk')->nullable()->change();
            $table->string('nama_luar', 100)->nullable()->after('id_penduduk');
            $table->string('nik_luar', 20)->nullable()->after('nama_luar');
            $table->tinyInteger('sex_luar')->nullable()->after('nik_luar');
            $table->string('tempatlahir_luar', 100)->nullable()->after('sex_luar');
            $table->date('tanggallahir_luar')->nullable()->after('tempatlahir_luar');
            $table->text('alamat_luar')->nullable()->after('tanggallahir_luar');
            $table->tinyInteger('agama_luar')->nullable()->after('alamat_luar');
            $table->tinyInteger('pendidikan_luar')->nullable()->after('agama_luar');
        });

        // Re-add FK dengan nullable support
        if (! $this->foreignKeyExists('kelompok_anggota', 'kelompok_anggota_penduduk_fk')) {
            Schema::table('kelompok_anggota', static function (Blueprint $table) {
                $table->foreign(['id_penduduk'], 'kelompok_anggota_penduduk_fk')
                    ->references(['id'])
                    ->on('tweb_penduduk')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });
        }
};
