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

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Utilities\Config;
use Yajra\DataTables\Utilities\Request;

class DataTablesServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->alias('datatables', DataTables::class);
        $this->app->singleton('datatables', static fn (): \Yajra\DataTables\DataTables => new DataTables());

        $this->app->singleton('datatables.request', static fn (): \Yajra\DataTables\Utilities\Request => new Request());

        $this->app->singleton('datatables.config', Config::class);
    }

    /**
     * Boot the instance, add macros for datatable engines.
     */
    public function boot(): void
    {
        $engines = $this->app['config']['datatables.engines'];

        foreach ($engines as $engine => $class) {
            $engine = Str::camel($engine);
            if (method_exists(DataTables::class, $engine)) {
                continue;
            }
            if (DataTables::hasMacro($engine)) {
                continue;
            }
            DataTables::macro($engine, static function () use ($class) {
                if (! call_user_func_array([$class, 'canCreate'], func_get_args())) {
                    throw new InvalidArgumentException();
                }

                return call_user_func_array([$class, 'create'], func_get_args());
            });
        }
    }
}
