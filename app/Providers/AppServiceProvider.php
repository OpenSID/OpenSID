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

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadModuleServiceProvider();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMacros();
        $this->registerCoreViews();
        if (ENVIRONMENT == 'development') {
            $this->logQuery();
        }
    }

    /**
     * Register custom macros.
     *
     * @return void
     */
    protected function registerMacros()
    {
        $this->registerMacrosConfigId();
        $this->registerMacrosUserStamps();
        $this->registerMacrosStatus();
        $this->registerMacrosUrut();
        $this->registerMacrosSlug();
        $this->registerMacrosDropIfExistsDBGabungan();
        $this->registerMacroConvertToBytes();
        $this->registerMacroHeaderKawinCerai();
        $this->registerMacroGroupByLabel();
    }

    protected function registerMacroGroupByLabel()
    {
        Collection::macro('groupByLabel', function () {
            return $this->groupBy(static function ($item): string {
                $label = $item->label ?? '';
                if (empty($label)) {
                    $label = underscore($item->nama, false);
                }

                return ucwords($label);
            });
        });
    }

    protected function registerMacroConvertToBytes()
    {
        Str::macro('convertToBytes', static function (string $value): int {
            $value = trim($value);
    
            // Jika bernilai -1, berarti tidak terbatas
            if ($value === '-1') {
                return PHP_INT_MAX;
            }
    
            // Ambil angka dan unit secara lebih akurat
            if (preg_match('/^(\d+)([KMG]?)$/i', $value, $matches)) {
                $number = (int) $matches[1];
                $unit   = strtolower($matches[2] ?? '');
    
                return match ($unit) {
                    'g' => $number * 1024 * 1024 * 1024,
                    'm' => $number * 1024 * 1024,
                    'k' => $number * 1024,
                    default => $number,
                };
            }
    
            return 0; // Jika format tidak sesuai
        });
    }

    protected function registerMacroHeaderKawinCerai()
    {
        Str::macro('headerKawinCerai', static function (Collection|array $statuses): string {
            $hasKawin = collect($statuses)->contains(static fn ($status) => Str::contains($status, 'KAWIN'));
            $hasCerai = collect($statuses)->contains(static fn ($status) => Str::contains($status, 'CERAI'));

            return match (true) {
                $hasKawin && $hasCerai => 'Tanggal Perkawinan / Perceraian',
                $hasCerai              => 'Tanggal Perceraian',
                default                => 'Tanggal Perkawinan',
            };
        });
    }

    /**
     * Register macro for config_id column.
     *
     * @return void
     */
    protected function registerMacrosConfigId()
    {
        Blueprint::macro('configId', function () {
            $columns = $this->getColumns();
            if (in_array('id', $columns)) {
                $this->integer('config_id')->nullable()->after('id');
            } elseif (in_array('uuid', $columns)) {
                $this->integer('config_id')->nullable()->after('uuid');
            } else {
                $this->integer('config_id')->nullable();
            }
            $this->foreign('config_id')->references('id')->on('config')->onUpdate('cascade')->onDelete('cascade');
        });
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

    /**
     * Register macro for status column.
     *
     * @return void
     */
    protected function registerMacrosStatus()
    {
        Blueprint::macro('status', function () {
            $this->tinyInteger('status')->default(0);
        });
    }

    /**
     * Register macro for urut column.
     *
     * @return void
     */
    protected function registerMacrosUrut()
    {
        Blueprint::macro('urut', function () {
            $this->integer('urut')->default(0);
        });
    }

    /**
     * Register macro for slug column.
     *
     * @param mixed $uniqueColumns
     *
     * @return void
     */
    protected function registerMacrosSlug($uniqueColumns = ['config_id', 'slug'])
    {
        Blueprint::macro('slug', function () use ($uniqueColumns) {
            $this->string('slug')->nullable();
            $this->unique($uniqueColumns);
        });
    }

    /**
     * Register macro for dropIfExistsDBGabungan.
     *
     * @param mixed|null $table
     * @param mixed|null $model
     *
     * @return void
     */
    protected function registerMacrosDropIfExistsDBGabungan($table = null, $model = null)
    {
        Schema::macro('dropIfExistsDBGabungan', function ($table, $model) {
            if (DB::table('config')->count() === 1) {
                Schema::dropIfExists($table);
            } else {
                if (Schema::hasTable($table)) {
                    $model::withoutConfigId(identitas('id'))->delete();
                }
            }
        });
    }

    /**
     * Log query to file.
     *
     * @return void
     */
    private function logQuery()
    {
        DB::listen(static function (\Illuminate\Database\Events\QueryExecuted $query) {
            $sql = Str::replaceArray('?', collect($query->bindings)->map(static fn ($binding) => is_numeric($binding) ? $binding : "'{$binding}'")->toArray(), $query->sql);

            $log = sprintf(
                '[%s] %s [Time: %sms]%s',
                Carbon::now()->toDateTimeString(),
                $sql,
                $query->time,
                PHP_EOL
            );

            File::append(storage_path('logs/query.log'), $log);
        });
    }

    /**
     * Register core views.
     */
    public function registerCoreViews(): void
    {
        $sourcePath = FCPATH . 'resources/views';

        $this->loadViewsFrom($sourcePath, 'core');
    }

    /**
     * Load service providers from modules.
     *
     * @return void
     */
    private function loadModuleServiceProvider()
    {
        $modulesPath = $this->app->basePath('Modules');

        $modules = File::directories($modulesPath);

        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);

            $providerClass = "Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider";

            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }
}
