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

namespace Database\Seeders;

use App\Models\Config;
use App\Traits\Migrator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrukturAwalSeeder extends Seeder
{
    use Migrator;

    public function __construct()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(5400);
    }

    /**
     * {@inheritDoc}
     */
    public function run(): void
    {
        $databaseName = DB::connection()->getDatabaseName();
        $charset      = config('database.connections.default.charset', 'utf8mb4');
        $collation    = config('database.connections.default.collation', 'utf8mb4_unicode_ci');

        DB::statement("ALTER DATABASE `{$databaseName}` CHARACTER SET {$charset} COLLATE {$collation};");

        $this->runMigrations('app/database/migrations/install');

        $this->call(DataStatisSeeder::class);

        $this->defaultConfig();
    }

    private function defaultConfig()
    {
        Config::create([
            'app_key'           => get_app_key(),
            'nama_desa'         => '',
            'kode_desa'         => '',
            'nama_kecamatan'    => '',
            'kode_kecamatan'    => '',
            'nama_kabupaten'    => '',
            'kode_kabupaten'    => '',
            'nama_propinsi'     => '',
            'kode_propinsi'     => '',
            'nama_kepala_camat' => '',
            'nip_kepala_camat'  => '',
        ]);
    }
}
