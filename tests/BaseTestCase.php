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

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class BaseTestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        defined('BASEPATH') || define('BASEPATH', __DIR__ . '/..');

        // load migrasi berdasarkan file, misalkan migrasi-seeder.php

        $this->loadMigrationsFrom([
            '--database' => 'sqlite',
            '--path'     => realpath(__DIR__ . '/../donjo-app/models/migrations/struktur_tabel/2023_12_22_015242_create_config_table.php'),
        ]);

        $this->loadMigrationsFrom([
            '--database' => 'sqlite',
            '--path'     => realpath(__DIR__ . '/../donjo-app/models/migrations/struktur_tabel/2023_12_22_015242_create_setting_aplikasi_table.php'),
        ]);

        $this->loadMigrationsFrom([
            '--database' => 'sqlite',
            '--path'     => realpath(__DIR__ . '/../donjo-app/models/migrations/struktur_tabel/2023_12_22_015242_create_log_login_table.php'),
        ]);

        $this->loadMigrationsFrom([
            '--database' => 'sqlite',
            '--path'     => realpath(__DIR__ . '/../donjo-app/models/migrations/struktur_tabel/2023_12_22_015242_create_artikel_table.php'),
        ]);

        $this->loadMigrationsFrom([
            '--database' => 'sqlite',
            '--path'     => realpath(__DIR__ . '/../donjo-app/models/migrations/struktur_tabel/2023_12_22_015242_create_tweb_wil_clusterdesa_table.php'),
        ]);

        $this->loadMigrationsFrom([
            '--database' => 'sqlite',
            '--path'     => realpath(__DIR__ . '/../donjo-app/models/migrations/struktur_tabel/2023_12_22_015242_create_suplemen_table.php'),
        ]);

        $this->loadMigrationsFrom([
            '--database' => 'sqlite',
            '--path'     => realpath(__DIR__ . '/../donjo-app/models/migrations/struktur_tabel/2023_12_22_015242_create_suplemen_terdata_table.php'),
        ]);

        // $this->loadMigrationsFrom([
        //     '--database' => 'sqlite',
        //     '--path' => realpath(__DIR__ . '/../donjo-app/models/migrations'),
        // ]);
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.asset_url', null);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $this->registerMacrosUserStamps();
    }

    /**
     * Register macro for userstamps columns.
     *
     * @return void
     */
    protected function registerMacrosUserStamps()
    {
        Blueprint::macro('timesWithUserstamps', function () {
            $this->timestamp('created_at')->nullable()->useCurrent();
            $this->integer('created_by')->nullable();
            $this->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
            $this->integer('updated_by')->nullable();
            // $this->timestamp('deleted_at')->nullable();
            // $this->integer('deleted_by')->nullable();
        });
    }
}
