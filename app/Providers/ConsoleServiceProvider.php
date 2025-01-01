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

use Illuminate\Cache\Console\CacheTableCommand;
use Illuminate\Cache\Console\ClearCommand as CacheClearCommand;
use Illuminate\Cache\Console\ForgetCommand as CacheForgetCommand;
use Illuminate\Console\Scheduling\ScheduleFinishCommand;
use Illuminate\Console\Scheduling\ScheduleListCommand;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Console\Scheduling\ScheduleWorkCommand;
use Illuminate\Database\Console\DumpCommand;
use Illuminate\Database\Console\Migrations\FreshCommand as MigrateFreshCommand;
use Illuminate\Database\Console\Migrations\InstallCommand as MigrateInstallCommand;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Console\Migrations\RefreshCommand as MigrateRefreshCommand;
use Illuminate\Database\Console\Migrations\ResetCommand as MigrateResetCommand;
use Illuminate\Database\Console\Migrations\RollbackCommand as MigrateRollbackCommand;
use Illuminate\Database\Console\Migrations\StatusCommand as MigrateStatusCommand;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Illuminate\Database\Console\WipeCommand;
use Illuminate\Queue\Console\BatchesTableCommand;
use Illuminate\Queue\Console\ClearCommand as ClearQueueCommand;
use Illuminate\Queue\Console\FailedTableCommand;
use Illuminate\Queue\Console\FlushFailedCommand as FlushFailedQueueCommand;
use Illuminate\Queue\Console\ForgetFailedCommand as ForgetFailedQueueCommand;
use Illuminate\Queue\Console\ListenCommand as QueueListenCommand;
use Illuminate\Queue\Console\ListFailedCommand as ListFailedQueueCommand;
use Illuminate\Queue\Console\RestartCommand as QueueRestartCommand;
use Illuminate\Queue\Console\RetryCommand as QueueRetryCommand;
use Illuminate\Queue\Console\TableCommand;
use Illuminate\Queue\Console\WorkCommand as QueueWorkCommand;
use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'CacheClear'      => 'command.cache.clear',
        'CacheForget'     => 'command.cache.forget',
        'Migrate'         => 'command.migrate',
        'MigrateInstall'  => 'command.migrate.install',
        'MigrateFresh'    => 'command.migrate.fresh',
        'MigrateRefresh'  => 'command.migrate.refresh',
        'MigrateReset'    => 'command.migrate.reset',
        'MigrateRollback' => 'command.migrate.rollback',
        'MigrateStatus'   => 'command.migrate.status',
        'QueueClear'      => 'command.queue.clear',
        'QueueFailed'     => 'command.queue.failed',
        'QueueFlush'      => 'command.queue.flush',
        'QueueForget'     => 'command.queue.forget',
        'QueueListen'     => 'command.queue.listen',
        'QueueRestart'    => 'command.queue.restart',
        'QueueRetry'      => 'command.queue.retry',
        'QueueWork'       => 'command.queue.work',
        'Seed'            => 'command.seed',
        'Wipe'            => 'command.wipe',
        'ScheduleFinish'  => 'command.schedule.finish',
        'ScheduleRun'     => 'command.schedule.run',
        'ScheduleList'    => 'command.schedule.list',
        'ScheduleWork'    => 'command.schedule.work',
        'SchemaDump'      => 'command.schema.dump',
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [
        'CacheTable'        => 'command.cache.table',
        'MigrateMake'       => 'command.migrate.make',
        'QueueFailedTable'  => 'command.queue.failed-table',
        'QueueBatchesTable' => 'command.queue.batches-table',
        'QueueTable'        => 'command.queue.table',
        'SeederMake'        => 'command.seeder.make',
    ];

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerCommands(array_merge(
            $this->commands,
            $this->devCommands
        ));
    }

    /**
     * Register the given commands.
     *
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach (array_keys($commands) as $command) {
            $this->{"register{$command}Command"}();
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerCacheClearCommand()
    {
        $this->app->singleton('command.cache.clear', static fn ($app): \Illuminate\Cache\Console\ClearCommand => new CacheClearCommand($app['cache'], $app['files']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerCacheForgetCommand()
    {
        $this->app->singleton('command.cache.forget', static fn ($app): \Illuminate\Cache\Console\ForgetCommand => new CacheForgetCommand($app['cache']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerCacheTableCommand()
    {
        $this->app->singleton('command.cache.table', static fn ($app): \Illuminate\Cache\Console\CacheTableCommand => new CacheTableCommand($app['files'], $app['composer']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateCommand()
    {
        $this->app->singleton('command.migrate', static fn ($app): \Illuminate\Database\Console\Migrations\MigrateCommand => new MigrateCommand($app['migrator'], $app['events']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateInstallCommand()
    {
        $this->app->singleton('command.migrate.install', static fn ($app): \Illuminate\Database\Console\Migrations\InstallCommand => new MigrateInstallCommand($app['migration.repository']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateMakeCommand()
    {
        $this->app->singleton('command.migrate.make', static function ($app): \Illuminate\Database\Console\Migrations\MigrateMakeCommand {
            // Once we have the migration creator registered, we will create the command
            // and inject the creator. The creator is responsible for the actual file
            // creation of the migrations, and may be extended by these developers.
            $creator = $app['migration.creator'];

            $composer = $app['composer'];

            return new \Illuminate\Database\Console\Migrations\MigrateMakeCommand($creator, $composer);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateFreshCommand()
    {
        $this->app->singleton('command.migrate.fresh', static fn (): \Illuminate\Database\Console\Migrations\FreshCommand => new MigrateFreshCommand());
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateRefreshCommand()
    {
        $this->app->singleton('command.migrate.refresh', static fn (): \Illuminate\Database\Console\Migrations\RefreshCommand => new MigrateRefreshCommand());
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateResetCommand()
    {
        $this->app->singleton('command.migrate.reset', static fn ($app): \Illuminate\Database\Console\Migrations\ResetCommand => new MigrateResetCommand($app['migrator']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateRollbackCommand()
    {
        $this->app->singleton('command.migrate.rollback', static fn ($app): \Illuminate\Database\Console\Migrations\RollbackCommand => new MigrateRollbackCommand($app['migrator']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateStatusCommand()
    {
        $this->app->singleton('command.migrate.status', static fn ($app): \Illuminate\Database\Console\Migrations\StatusCommand => new MigrateStatusCommand($app['migrator']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerQueueClearCommand()
    {
        $this->app->singleton('command.queue.clear', static fn () => new ClearQueueCommand());
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerQueueFailedCommand()
    {
        $this->app->singleton('command.queue.failed', static fn (): \Illuminate\Queue\Console\ListFailedCommand => new ListFailedQueueCommand());
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerQueueForgetCommand()
    {
        $this->app->singleton('command.queue.forget', static fn (): \Illuminate\Queue\Console\ForgetFailedCommand => new ForgetFailedQueueCommand());
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerQueueFlushCommand()
    {
        $this->app->singleton('command.queue.flush', static fn (): \Illuminate\Queue\Console\FlushFailedCommand => new FlushFailedQueueCommand());
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerQueueListenCommand()
    {
        $this->app->singleton('command.queue.listen', static fn ($app): \Illuminate\Queue\Console\ListenCommand => new QueueListenCommand($app['queue.listener']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerQueueRestartCommand()
    {
        $this->app->singleton('command.queue.restart', static fn ($app): \Illuminate\Queue\Console\RestartCommand => new QueueRestartCommand($app['cache.store']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerQueueRetryCommand()
    {
        $this->app->singleton('command.queue.retry', static fn (): \Illuminate\Queue\Console\RetryCommand => new QueueRetryCommand());
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerQueueWorkCommand()
    {
        $this->app->singleton('command.queue.work', static fn ($app): \Illuminate\Queue\Console\WorkCommand => new QueueWorkCommand($app['queue.worker'], $app['cache.store']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerQueueFailedTableCommand()
    {
        $this->app->singleton('command.queue.failed-table', static fn ($app): \Illuminate\Queue\Console\FailedTableCommand => new FailedTableCommand($app['files'], $app['composer']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerQueueBatchesTableCommand()
    {
        $this->app->singleton('command.queue.batches-table', static fn ($app): \Illuminate\Queue\Console\BatchesTableCommand => new BatchesTableCommand($app['files'], $app['composer']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerQueueTableCommand()
    {
        $this->app->singleton('command.queue.table', static fn ($app): \Illuminate\Queue\Console\TableCommand => new TableCommand($app['files'], $app['composer']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerSeederMakeCommand()
    {
        $this->app->singleton('command.seeder.make', static fn ($app): \Illuminate\Database\Console\Seeds\SeederMakeCommand => new SeederMakeCommand($app['files'], $app['composer']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerSeedCommand()
    {
        $this->app->singleton('command.seed', static fn ($app): \Illuminate\Database\Console\Seeds\SeedCommand => new SeedCommand($app['db']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerWipeCommand()
    {
        $this->app->singleton('command.wipe', static fn ($app): \Illuminate\Database\Console\WipeCommand => new WipeCommand($app['db']));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerScheduleFinishCommand()
    {
        $this->app->singleton('command.schedule.finish', static fn () => new ScheduleFinishCommand());
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerScheduleRunCommand()
    {
        $this->app->singleton('command.schedule.run', static fn () => new ScheduleRunCommand());
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerScheduleWorkCommand()
    {
        $this->app->singleton('command.schedule.work', static fn () => new ScheduleWorkCommand());
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerScheduleListCommand()
    {
        $this->app->singleton('command.schedule.list', static fn () => new ScheduleListCommand());
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerSchemaDumpCommand()
    {
        $this->app->singleton('command.schema.dump', static fn (): \Illuminate\Database\Console\DumpCommand => new DumpCommand());
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return array_merge(array_values($this->commands), array_values($this->devCommands));
    }
}
