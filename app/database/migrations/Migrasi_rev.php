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

use App\Enums\PekerjaanEnum;
use App\Traits\Migrator;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->updatePekerjaan();
        $this->updateSyaratSurat();
        $this->refreshArtikelKategoriForeignKey();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }

    private function updatePekerjaan(): void
    {
        if (Schema::hasTable('tweb_penduduk_pekerjaan')) {
            DB::table('tweb_penduduk_pekerjaan')
                ->where('id', 5)
                ->update(['nama' => PekerjaanEnum::APARATUR_SIPIL_NEGARA_ASN]);
        }
    }

    private function updateSyaratSurat(): void
    {
        if (Schema::hasTable('ref_syarat_surat')) {
            DB::table('ref_syarat_surat')
                ->where('ref_syarat_nama', 'LIKE', 'SK. PNS/KARIP/SK. TNI%POLRI')
                ->update(['ref_syarat_nama' => 'SK. ASN/KARIP/SK. TNI - POLRI']);
        }
    }

    private function refreshArtikelKategoriForeignKey(): void
    {
        // Drop FK lama sebelum menambahkan yang baru
        $this->hapusForeignKey('artikel_kategori_2026_fk', 'artikel', 'kategori');

        if (! $this->foreignKeyExists('artikel', 'artikel_kategori_2026_04_15_fk')) {
            Schema::table('artikel', static function (Blueprint $table): void {
                $table->foreign(['id_kategori'], 'artikel_kategori_2026_04_15_fk')->references(['id'])->on('kategori')->onUpdate('cascade')->onDelete('set null');
            });
        }
    }

    /**
     * Normalisasi relasi tweb_penduduk_mandiri.id_pend ke tweb_penduduk.id.
     * - Data id_pend yang tidak punya pasangan di tweb_penduduk akan di-set null.
     * - Foreign key ditambahkan jika belum ada menggunakan helper Migrator.
     */
    private function normalisasiRelasiPendudukMandiri(): void
    {
        if (! Schema::hasTable('tweb_penduduk_mandiri') || ! Schema::hasTable('tweb_penduduk')) {
            return;
        }

        if (! Schema::hasColumn('tweb_penduduk_mandiri', 'id_pend')) {
            return;
        }

        $this->tambahForeignKey(
            'tweb_penduduk_mandiri_id_pend_fk_2026',
            'tweb_penduduk_mandiri',
            'id_pend',
            'tweb_penduduk',
            'id',
            true,
            false,
            'SET NULL',
            'CASCADE'
        );
    }
};
