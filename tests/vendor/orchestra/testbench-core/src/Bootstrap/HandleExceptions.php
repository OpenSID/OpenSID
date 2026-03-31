<?php

namespace Orchestra\Testbench\Bootstrap;

use Illuminate\Log\LogManager;
use Orchestra\Sidekick\Env;
use Orchestra\Testbench\Exceptions\DeprecatedException;
use PHPUnit\Runner\ErrorHandler;

use function Orchestra\Sidekick\join_paths;

/**
 * @internal
 */
final class HandleExceptions extends \Illuminate\Foundation\Bootstrap\HandleExceptions
{
    /**
     * {@inheritDoc}
     *
     * @throws \Orchestra\Testbench\Exceptions\DeprecatedException
     */
    #[\Override]
    public function handleDeprecationError($message, $file, $line, $level = E_DEPRECATED)
    {
        rescue(function () use ($message, $file, $line, $level) {
            parent::handleDeprecationError($message, $file, $line, $level);
        }, null, false);

        $testbenchConvertDeprecationsToExceptions = (bool) Env::get(
            'TESTBENCH_CONVERT_DEPRECATIONS_TO_EXCEPTIONS', false
        );

        if ($testbenchConvertDeprecationsToExceptions === true) {
            throw new DeprecatedException($message, $level, $file, $line);
        }
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function ensureDeprecationLoggerIsConfigured()
    {
        with(self::$app->make('config'), static function ($config) {
            /** @var \Illuminate\Contracts\Config\Repository $config */
            if ($config->get('logging.channels.deprecations')) {
                return;
            }

            /** @var array{channel?: string, trace?: bool}|string|null $options */
            $options = $config->get('logging.deprecations');
            $trace = Env::get('LOG_DEPRECATIONS_TRACE', false);

            if (\is_array($options)) {
                $driver = $options['channel'] ?? 'null';
                $trace = $options['trace'] ?? true;
            } else {
                $driver = $options ?? 'null';
            }

            if ($driver === 'single') {
                $config->set('logging.channels.deprecations', array_merge($config->get('logging.channels.single'), [
                    'path' => self::$app->storagePath(join_paths('logs', 'deprecations.log')),
                ]));
            } else {
                $config->set('logging.channels.deprecations', $config->get("logging.channels.{$driver}"));
            }

            $config->set('logging.deprecations', [
                'channel' => 'deprecations',
                'trace' => $trace,
            ]);
        });
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function shouldIgnoreDeprecationErrors()
    {
        return ! class_exists(LogManager::class)
            || ! self::$app->hasBeenBootstrapped()
            || ! Env::get('LOG_DEPRECATIONS_WHILE_TESTING', true);
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function forgetApp()
    {
        if (\is_null(self::$app)) {
            return;
        }

        self::flushHandlersState();

        self::$app = null; /** @phpstan-ignore assign.propertyType */
        self::$reservedMemory = null;
    }

    /**
     * Flush the bootstrapper's global handlers state.
     *
     * @return void
     */
    public static function flushHandlersState()
    {
        while (true) {
            $previousHandler = set_exception_handler(static fn () => null);
            restore_exception_handler();

            if ($previousHandler === null) {
                break;
            }

            restore_exception_handler();
        }

        while (true) {
            $previousHandler = set_error_handler(static fn () => null); /** @phpstan-ignore argument.type */
            restore_error_handler();

            if ($previousHandler === null) {
                break;
            }

            restore_error_handler();
        }

        if (class_exists(ErrorHandler::class)) {
            $instance = ErrorHandler::instance();

            if ((fn () => $this->enabled ?? false)->call($instance)) {
                $instance->disable();
                $instance->enable();
            }
        }
    }
}
