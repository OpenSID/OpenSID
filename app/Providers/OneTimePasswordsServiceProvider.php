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

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\OneTimePasswords\Support\Config;
use Spatie\OneTimePasswords\Support\OriginInspector\OriginEnforcer;
use Spatie\OneTimePasswords\Support\PasswordGenerators\OneTimePasswordGenerator;

class OneTimePasswordsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->configure('one-time-passwords');

        $this->app->bind(OriginEnforcer::class, $this->app['config']['one-time-passwords.origin_enforcer']);

        $this->app->bind(OneTimePasswordGenerator::class, function () {
            $generator = Config::getPasswordGenerator();

            $generator->numberOfCharacters($this->app['config']['one-time-passwords.password_length']);

            return $generator;
        });
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../vendor/spatie/laravel-one-time-passwords/resources/views', 'one-time-passwords');
        $this->loadTranslationsFrom(__DIR__ . '/../../vendor/spatie/laravel-one-time-passwords/resources/lang', 'one-time-passwords');
        $this->loadMigrationsFrom(__DIR__ . '/../../vendor/spatie/laravel-one-time-passwords/database/migrations');
    }
}
