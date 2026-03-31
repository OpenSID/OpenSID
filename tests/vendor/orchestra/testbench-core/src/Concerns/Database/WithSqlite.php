<?php

namespace Orchestra\Testbench\Concerns\Database;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\SQLiteBuilder;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Fluent;

/**
 * @api
 *
 * @codeCoverageIgnore
 */
trait WithSqlite
{
    /**
     * Add support for SQLite drop foreign.
     *
     * @return void
     */
    protected function hotfixForSqliteSchemaBuilder(): void
    {
        Connection::resolverFor('sqlite', static function ($connection, $database, $prefix, $config) {
            return new class($connection, $database, $prefix, $config) extends SQLiteConnection
            {
                public function getSchemaBuilder()
                {
                    if ($this->schemaGrammar === null) {
                        $this->useDefaultSchemaGrammar();
                    }

                    return new class($this) extends SQLiteBuilder
                    {
                        protected function createBlueprint($table, ?Closure $callback = null)
                        {
                            return new class($table, $callback) extends Blueprint
                            {
                                public function dropForeign($index)
                                {
                                    return new Fluent;
                                }
                            };
                        }
                    };
                }
            };
        });
    }
}
