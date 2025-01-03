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

use Composer\InstalledVersions;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class JsonApiPaginateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerMacro();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '../../../config/json-api-paginate.php', 'json-api-paginate');
    }

    protected function registerMacro()
    {
        $config = $this->app['config']['json-api-paginate'];
        $macro  = function (?int $maxResults = null, ?int $defaultSize = null) use ($config) {
            $maxResults ??= $config['max_results'];
            $defaultSize ??= $config['default_size'];
            $numberParameter     = $config['number_parameter'];
            $cursorParameter     = $config['cursor_parameter'];
            $sizeParameter       = $config['size_parameter'];
            $paginationParameter = $config['pagination_parameter'];
            $paginationMethod    = $config['use_cursor_pagination']
                ? 'cursorPaginate'
                : (
                    $config['use_simple_pagination']
                        ? ($config['use_fast_pagination'] ? 'simpleFastPaginate' : 'simplePaginate')
                        : ($config['use_fast_pagination'] ? 'fastPaginate' : 'paginate')
                );

            if ($config['use_fast_pagination'] && ! InstalledVersions::isInstalled('hammerstone/fast-paginate')) {
                abort(500, 'You need to install hammerstone/fast-paginate to use fast pagination.');
            }

            $size   = (int) request()->input($paginationParameter . '.' . $sizeParameter, $defaultSize);
            $cursor = (string) request()->input($paginationParameter . '.' . $cursorParameter);

            if ($size <= 0) {
                $size = $defaultSize;
            }

            if ($size > $maxResults) {
                $size = $maxResults;
            }

            $paginator = $paginationMethod === 'cursorPaginate'
                ? $this->{$paginationMethod}($size, ['*'], $paginationParameter . '[' . $cursorParameter . ']', $cursor)
                    ->appends(Arr::except(request()->input(), $paginationParameter . '.' . $cursorParameter))
                : $this
                    ->{$paginationMethod}($size, ['*'], $paginationParameter . '.' . $numberParameter)
                    ->setPageName($paginationParameter . '[' . $numberParameter . ']')
                    ->appends(Arr::except(request()->input(), $paginationParameter . '.' . $numberParameter));

            if (null !== $config['base_url']) {
                $paginator->setPath($config['base_url']);
            }

            return $paginator;
        };

        EloquentBuilder::macro($config['method_name'], $macro);
        BaseBuilder::macro($config['method_name'], $macro);
        BelongsToMany::macro($config['method_name'], $macro);
        HasManyThrough::macro($config['method_name'], $macro);
    }
}
