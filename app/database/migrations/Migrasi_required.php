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

use App\Actions\Setting\ImportSetting;
use App\Models\SettingAplikasi;
use App\Traits\Migrator;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->tambah_ubah_surat_bawaan();
        $this->tambah_ulang_pengaturan();
        $this->versionBuild();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }

    public function tambah_ubah_surat_bawaan()
    {
        $id = identitas('id');
        restoreSuratBawaanTinyMCE($id);
        restoreSuratBawaanDinasTinyMCE($id);
    }

    public function tambah_ulang_pengaturan()
    {
        (new ImportSetting())->handle();

        DB::table('setting_aplikasi')->whereIn('key', [
            'sebutan_pemerintah_desa',
            'compatible_version_general',
        ])->delete();

        cache()->flush();
    }

    // Menentukan build version
    protected function versionBuild()
    {
        $setting = SettingAplikasi::firstOrCreate(
            ['key' => 'version_build_script'],
            [
                'judul'      => 'Version Build Script',
                'value'      => '-',
                'jenis'      => 'input-text',
                'keterangan' => 'Versi Build Script',
                'option'     => null,
                'attribute'  => json_encode([
                    'disable' => 'true',
                ]),
                'kategori' => 'sistem',
            ]
        );

        (new SettingAplikasi())->flushQueryCache();

        if (null === $setting->value) {
            $version = match (true) {
                Schema::hasColumn('program', 'publikasi') && Schema::hasColumn('tweb_penduduk', 'is_historical') => '2026.02.01',
                Schema::hasTable('notifications') && Schema::hasColumn('anjungan', 'uuid')                       => '2026.01.01',
                Schema::hasColumn('buku_tamu', 'status') && ! Schema::hasTable('notifications')                  => '2025.12.01',
                default                                                                                          => $setting->value
            };

            SettingAplikasi::where('key', 'version_build_script')->update(['value' => $version]);

            (new SettingAplikasi())->flushQueryCache();
        }
    }
};
