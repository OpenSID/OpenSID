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

namespace App\Console;

use App\Console\Commands\AcakDataCommand;
use App\Console\Commands\ModuleCommand;
use App\Console\Commands\Modules\MigrationMakeCommand;
use App\Console\Commands\Modules\SeedMakeCommand;
use App\Console\Commands\ViewClearCommand;
use App\Exceptions\Handler;
use App\Services\Laravel;
use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Contracts\Console\Kernel as KernelContract;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use RuntimeException;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Throwable;

class Kernel implements KernelContract
{
    /**
     * The Symfony event dispatcher implementation.
     *
     * @var \Symfony\Contracts\EventDispatcher\EventDispatcherInterface|null
     */
    protected $symfonyDispatcher;

    /**
     * The Artisan application instance.
     *
     * @var Artisan
     */
    protected $artisan;

    /**
     * Indicates if facade aliases are enabled for the console.
     *
     * @var bool
     */
    protected $aliases = true;

    /**
     * The Artisan commands provided by the application.
     *
     * @var array
     */
    protected $commands = [
        AcakDataCommand::class,
        ViewClearCommand::class,
        ModuleCommand::class,
        MigrationMakeCommand::class,
        SeedMakeCommand::class,
    ];

    /**
     * Create a new console kernel instance.
     *
     * @return void
     */
    public function __construct(
        /**
         * The application implementation.
         */
        protected Laravel $app
    ) {
        if ($this->app->runningInConsole()) {
            $this->setRequestForConsole($this->app);
        } else {
            $this->rerouteSymfonyCommandEvents();
        }

        $this->app->prepareForConsoleCommand($this->aliases);
        $this->defineConsoleSchedule();
    }

    /**
     * Set the request instance for URL generation.
     *
     * @return void
     */
    protected function setRequestForConsole(Laravel $app)
    {
        $server = $_SERVER;

        $server = array_merge($server, [
            'SCRIPT_FILENAME' => 'artisan',
            'SCRIPT_NAME'     => 'artisan',
            'PHP_SELF'        => 'artisan',
            'PATH_TRANSLATED' => 'artisan',
            'argv'            => Arr::except($server['argv'], 0),
        ]);

        $_SERVER = $server;

        $app->instance('request', Request::create(
            base_url(),
            'GET',
            [],
            [],
            [],
            $server
        ));
    }

    /**
     * Re-route the Symfony command events to their Laravel counterparts.
     *
     * @internal
     *
     * @return $this
     */
    public function rerouteSymfonyCommandEvents()
    {
        if (null === $this->symfonyDispatcher) {
            $this->symfonyDispatcher = new EventDispatcher();

            $this->symfonyDispatcher->addListener(ConsoleEvents::COMMAND, function (ConsoleCommandEvent $event) {
                $this->app[Dispatcher::class]->dispatch(
                    new CommandStarting($event->getCommand()->getName(), $event->getInput(), $event->getOutput())
                );
            });

            $this->symfonyDispatcher->addListener(ConsoleEvents::TERMINATE, function (ConsoleTerminateEvent $event) {
                $this->app[Dispatcher::class]->dispatch(
                    new CommandFinished($event->getCommand()->getName(), $event->getInput(), $event->getOutput(), $event->getExitCode())
                );
            });
        }

        return $this;
    }

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function defineConsoleSchedule()
    {
        $this->app->instance(
            Schedule::class,
            $schedule = new Schedule()
        );

        $this->schedule($schedule);
    }

    /**
     * Run the console application.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function handle($input, $output = null)
    {
        try {
            $this->app->boot();

            return $this->getArtisan()->run($input, $output);
        } catch (Throwable $e) {
            $this->reportException($e);

            $this->renderException($output, $e);

            return 1;
        }
    }

    /**
     * Bootstrap the application for artisan commands.
     */
    public function bootstrap(): void
    {

    }

    /**
     * Terminate the application.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param int                                             $status
     */
    public function terminate($input, $status): void
    {

    }

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

    }

    /**
     * Run an Artisan console command by name.
     *
     * @param string     $command
     * @param mixed|null $outputBuffer
     *
     * @return int
     */
    public function call($command, array $parameters = [], $outputBuffer = null)
    {
        return $this->getArtisan()->call($command, $parameters, $outputBuffer);
    }

    /**
     * Queue the given console command.
     *
     * @param string $command
     */
    public function queue($command, array $parameters = []): void
    {
        throw new RuntimeException('Queueing Artisan commands is not supported.');
    }

    /**
     * Get all of the commands registered with the console.
     *
     * @return array
     */
    public function all()
    {
        return $this->getArtisan()->all();
    }

    /**
     * Get the output for the last run command.
     *
     * @return string
     */
    public function output()
    {
        return $this->getArtisan()->output();
    }

    /**
     * Get the Artisan application instance.
     *
     * @return Artisan
     */
    protected function getArtisan()
    {
        if (null === $this->artisan) {
            $artisan = new Artisan($this->app, $this->app->make('events'), $this->app->version());
            $artisan->setName('OpenSID');
            $artisan->resolveCommands($this->getCommands());
            $artisan->setContainerCommandLoader();

            if ($this->symfonyDispatcher instanceof EventDispatcher) {
                $artisan->setDispatcher($this->symfonyDispatcher);
                $artisan->setSignalsToDispatchEvent();
            }

            return $this->artisan = $artisan;
        }

        return $this->artisan;
    }

    /**
     * Get the commands to add to the application.
     */
    protected function getCommands(): array
    {
        return array_merge($this->commands, [
            ScheduleRunCommand::class,
        ]);
    }

    /**
     * Report the exception to the exception handler.
     *
     * @return void
     */
    protected function reportException(Throwable $e)
    {
        $this->resolveExceptionHandler()->report($e);
    }

    /**
     * Report the exception to the exception handler.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function renderException($output, Throwable $e)
    {
        $this->resolveExceptionHandler()->renderForConsole($output, $e);
    }

    /**
     * Get the exception handler from the container.
     *
     * @return ExceptionHandler
     */
    protected function resolveExceptionHandler()
    {
        if ($this->app->bound(ExceptionHandler::class)) {
            return $this->app->make(ExceptionHandler::class);
        }

        return $this->app->make(Handler::class);
    }
}
