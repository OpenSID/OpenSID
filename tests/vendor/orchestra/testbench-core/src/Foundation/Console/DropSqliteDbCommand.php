<?php

namespace Orchestra\Testbench\Foundation\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

use function Orchestra\Sidekick\join_paths;

#[AsCommand(name: 'package:drop-sqlite-db', description: 'Drop sqlite database file')]
class DropSqliteDbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:drop-sqlite-db
                                {--database=database.sqlite : Set the database name}
                                {--all : Delete all SQLite databases}';

    /**
     * Execute the console command.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $filesystem
     * @return int
     */
    public function handle(Filesystem $filesystem)
    {
        $databasePath = $this->laravel->databasePath();

        (new Actions\DeleteFiles(
            filesystem: $filesystem,
            components: $this->components,
        ))->handle(
            match ($this->option('all')) {
                true => [...$filesystem->glob(join_paths($databasePath, '*.sqlite'))],
                default => [join_paths($databasePath, $this->databaseName())],
            }
        );

        return Command::SUCCESS;
    }

    /**
     * Resolve the database name.
     *
     * @return string
     */
    protected function databaseName(): string
    {
        /** @var string|null $database */
        $database = $this->option('database');

        if (empty($database)) {
            $database = 'database';
        }

        return \sprintf('%s.sqlite', Str::before((string) $database, '.sqlite'));
    }
}
