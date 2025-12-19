<?php

declare(strict_types=1);

/**
 * MySQL database dump.
 *
 * @author     David Grudl (http://davidgrudl.com)
 * @copyright  Copyright (c) 2008 David Grudl
 * @license    New BSD License
 */
class MySQLDump
{
    public const NONE = 0;
    public const DROP = 1;
    public const CREATE = 2;
    public const DATA = 4;
    public const TRIGGERS = 8;
    public const ALL = 15; // DROP | CREATE | DATA | TRIGGERS

    private const MAX_SQL_SIZE = 1e6;

    /** @var array */
    public $tables = [
        '*' => self::ALL,
    ];

    /** @var mysqli */
    private $connection;

    private $collation = 'utf8mb4_general_ci';

    private $charset = 'utf8mb4';

    private $configId = null;

    private $configIdColumn = 'config_id';

    private $columnsWithConfigId = [];

    /**
     * Tabel master yang harus di-filter dengan primary key (id).
     * Contoh: ['config' => 'id'] berarti tabel config akan difilter WHERE id = $configId
     * @var array
     */
    private $masterTables = [];

    /**
     * Connects to database.
     *
     * @param mysqli $connection Database connection
     * @param string $charset Character set (default: utf8mb4)
     * @param int|null $configId Optional tenant/config ID untuk filtering data per desa
     */
    public function __construct(mysqli $connection, string $charset = 'utf8mb4', ?int $configId = null)
    {
        $this->connection = $connection;
        $this->configId = $configId;

        if ($connection->connect_errno) {
            throw new Exception($connection->connect_error);
        } elseif (!$connection->set_charset($charset)) { // was added in MySQL 5.0.7 and PHP 5.0.5, fixed in PHP 5.1.5)
            throw new Exception($connection->error);
        }

        // Deteksi kolom config_id di semua table
        if ($this->configId !== null) {
            $this->detectConfigIdColumns();
        }
    }


    /**
     * Mendeteksi kolom config_id di semua table.
     * Disimpan dalam property $columnsWithConfigId untuk mempercepat lookup.
     */
    private function detectConfigIdColumns(): void
    {
        $this->columnsWithConfigId = [];

        $res = $this->connection->query('SHOW FULL TABLES');
        if (!$res) {
            return;
        }

        while ($row = $res->fetch_row()) {
            $tableName = $row[0];
            $tableType = $row[1];

            // Lewati VIEW saat deteksi kolom
            if ($tableType === 'VIEW') {
                continue;
            }

            // Periksa apakah table memiliki kolom config_id
            if ($this->hasColumn($tableName, $this->configIdColumn)) {
                $this->columnsWithConfigId[$tableName] = true;
            }
        }
        $res->close();
    }

    /**
     * Periksa apakah table memiliki kolom tertentu.
     *
     * @param string $table Nama table
     * @param string $column Nama kolom
     * @return bool
     */
    private function hasColumn(string $table, string $column): bool
    {
        $delTable = $this->delimite($table);
        $delColumn = $this->delimite($column);

        $res = $this->connection->query("SHOW COLUMNS FROM $delTable LIKE '$column'");
        if (!$res) {
            return false;
        }

        $found = $res->num_rows > 0;
        $res->close();

        return $found;
    }

    /**
     * Dapatkan WHERE clause untuk filtering config_id jika ada.
     *
     * @param string $table Nama table
     * @return string WHERE clause atau string kosong
     */
    private function getWhereClauseForConfigId(string $table): string
    {
        if ($this->configId === null) {
            return '';
        }

        // Cek apakah tabel adalah master table
        if (isset($this->masterTables[$table])) {
            $columnName = $this->masterTables[$table];
            return " WHERE `$columnName` = " . (int) $this->configId;
        }

        // Cek apakah tabel memiliki kolom config_id
        if (isset($this->columnsWithConfigId[$table])) {
            return " WHERE `$this->configIdColumn` = " . (int) $this->configId;
        }

        return '';
    }

    /**
     * Saves dump to the file.
     */
    public function save(string $file): void
    {
        $handle = strcasecmp(substr($file, -3), '.gz') ? fopen($file, 'wb') : gzopen($file, 'wb');
        if (!$handle) {
            throw new Exception("ERROR: Cannot write file '$file'.");
        }
        $this->write($handle);
    }

    /**
     * Set config_id untuk filtering per tenant/desa.
     *
     * @param int $configId ID konfigurasi/desa yang akan di-backup
     * @return self
     */
    public function setConfigId(int $configId): self
    {
        $this->configId = $configId;
        $this->detectConfigIdColumns();

        return $this;
    }

    /**
     * Set nama kolom yang digunakan untuk filtering tenant (default: config_id).
     *
     * @param string $columnName Nama kolom
     * @return self
     */
    public function setConfigIdColumn(string $columnName): self
    {
        $this->configIdColumn = $columnName;
        if ($this->configId !== null) {
            $this->detectConfigIdColumns();
        }

        return $this;
    }

    /**
     * Tambahkan tabel master yang harus di-filter dengan primary key.
     * Untuk tabel config yang berisi master data desa/tenant.
     *
     * @param string $tableName Nama tabel (contoh: 'config')
     * @param string $columnName Nama kolom primary key (contoh: 'id')
     * @return self
     */
    public function addMasterTable(string $tableName, string $columnName = 'id'): self
    {
        $this->masterTables[$tableName] = $columnName;

        return $this;
    }

    /**
     * Hapus tabel dari master table list.
     *
     * @param string $tableName Nama tabel
     * @return self
     */
    public function removeMasterTable(string $tableName): self
    {
        unset($this->masterTables[$tableName]);

        return $this;
    }


    /**
     * Writes dump to logical file.
     * @param  resource
     */
    public function write($handle = null): void
    {
        if ($handle === null) {
            $handle = fopen('php://output', 'wb');
        } elseif (!is_resource($handle) || get_resource_type($handle) !== 'stream') {
            throw new Exception('Argument must be stream resource.');
        }

        $tables = $views = [];

        $res = $this->connection->query('SHOW FULL TABLES');
        while ($row = $res->fetch_row()) {
            if ($row[1] === 'VIEW') {
                $views[] = $row[0];
            } else {
                $tables[] = $row[0];
            }
        }
        $res->close();

        $tables = array_merge($tables, $views); // views must be last

        $this->connection->query('LOCK TABLES `' . implode('` READ, `', $tables) . '` READ');

        $db = $this->connection->query('SELECT DATABASE()')->fetch_row();
        fwrite(
            $handle,
            '-- Created at ' . date('j.n.Y G:i') . " using David Grudl MySQL Dump Utility\n"
                . (isset($_SERVER['HTTP_HOST']) ? "-- Host: $_SERVER[HTTP_HOST]\n" : '')
                . '-- MySQL Server: ' . $this->connection->server_info . "\n"
                . '-- Database: ' . $db[0] . "\n"
                . "\n"
                . "SET NAMES utf8mb4;\n"
                . "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n"
                . "SET FOREIGN_KEY_CHECKS=0;\n"
                . "SET UNIQUE_CHECKS=0;\n"
                . "SET AUTOCOMMIT=0;\n"
        );

        foreach ($tables as $table) {
            $this->dumpTable($handle, $table);
        }

        fwrite($handle, "COMMIT;\n");
        fwrite($handle, "-- THE END\n");

        $this->connection->query('UNLOCK TABLES');
    }


    /**
     * Dumps table to logical file.
     * @param  resource
     */
    public function dumpTable($handle, $table): void
    {
        $mode = isset($this->tables[$table]) ? $this->tables[$table] : $this->tables['*'];
        if ($mode === self::NONE) {
            return;
        }

        $delTable = $this->delimite($table);
        $res = $this->connection->query("SHOW CREATE TABLE $delTable");
        $row = $res->fetch_assoc();
        $res->close();

        fwrite($handle, "-- --------------------------------------------------------\n\n");

        $view = isset($row['Create View']);

        if ($mode & self::DROP) {
            fwrite($handle, 'DROP ' . ($view ? 'VIEW' : 'TABLE') . " IF EXISTS $delTable;\n\n");
        }

        if ($mode & self::CREATE) {
            $createSql = $view ? $this->removeDefiner($row['Create View']) : $this->resetEngine($row['Create Table']);
            fwrite($handle, $createSql . ";\n\n");
        }

        if (!$view && ($mode & self::DATA)) {
            fwrite($handle, 'ALTER ' . ($view ? 'VIEW' : 'TABLE') . ' ' . $delTable . " DISABLE KEYS;\n\n");
            $numeric = [];
            $res = $this->connection->query("SHOW COLUMNS FROM $delTable");
            $cols = [];
            while ($row = $res->fetch_assoc()) {
                $col = $row['Field'];
                $cols[] = $this->delimite($col);
                $numeric[$col] = (bool) preg_match('#^[^(]*(BYTE|COUNTER|SERIAL|INT|LONG$|CURRENCY|REAL|MONEY|FLOAT|DOUBLE|DECIMAL|NUMERIC|NUMBER)#i', $row['Type']);
            }
            $cols = '(' . implode(', ', $cols) . ')';
            $res->close();

            $size = 0;

            // Gunakan WHERE clause jika table memiliki kolom config_id
            $selectQuery = "SELECT * FROM {$delTable} {$this->getWhereClauseForConfigId($table)}";

            $res = $this->connection->query($selectQuery, MYSQLI_USE_RESULT);
            while ($row = $res->fetch_assoc()) {
                $s = '(';
                foreach ($row as $key => $value) {
                    if ($value === null) {
                        $s .= "NULL,\t";
                    } elseif ($numeric[$key]) {
                        $s .= $value . ",\t";
                    } else {
                        $s .= "'" . $this->connection->real_escape_string($value) . "',\t";
                    }
                }

                if ($size == 0) {
                    $s = "INSERT INTO $delTable $cols VALUES\n$s";
                } else {
                    $s = ",\n$s";
                }

                $len = strlen($s) - 1;
                $s[$len - 1] = ')';
                fwrite($handle, $s, $len);

                $size += $len;
                if ($size > self::MAX_SQL_SIZE) {
                    fwrite($handle, ";\n");
                    $size = 0;
                }
            }

            $res->close();
            if ($size) {
                fwrite($handle, ";\n");
            }
            fwrite($handle, 'ALTER ' . ($view ? 'VIEW' : 'TABLE') . ' ' . $delTable . " ENABLE KEYS;\n\n");
            fwrite($handle, "\n");
        }

        if ($mode & self::TRIGGERS) {
            $res = $this->connection->query("SHOW TRIGGERS LIKE '" . $this->connection->real_escape_string($table) . "'");
            if ($res->num_rows) {
                fwrite($handle, "DELIMITER ;;\n\n");
                while ($row = $res->fetch_assoc()) {
                    fwrite($handle, "CREATE TRIGGER {$this->delimite($row['Trigger'])} $row[Timing] $row[Event] ON $delTable FOR EACH ROW\n$row[Statement];;\n\n");
                }
                fwrite($handle, "DELIMITER ;\n\n");
            }
            $res->close();
        }

        fwrite($handle, "\n");
    }


    private function delimite(string $s): string
    {
        return '`' . str_replace('`', '``', $s) . '`';
    }

    private function removeDefiner($code)
    {
        return preg_replace('/ALGORITHM=UNDEFINED DEFINER=.+SQL SECURITY DEFINER /', '', $code);
    }

    /**
     * Reset ENGINE ke InnoDB, sesuaikan charset dan collation pada SQL.
     *
     * @param string $sql
     * @return string
     */
    private function resetEngine($sql)
    {
        // Ganti ENGINE ke InnoDB
        $sql = preg_replace(
            '/ENGINE=(MyISAM|MEMORY|CSV|ARCHIVE|MRG_MYISAM|BLACKHOLE|FEDERATED)/i',
            'ENGINE=InnoDB',
            $sql
        );

        // Ubah CHARSET dan COLLATE pada definisi tabel
        $sql = preg_replace_callback(
            '/(CHARSET\s*=\s*)([a-zA-Z0-9_]+)((?:\s+COLLATE\s*=\s*[a-zA-Z0-9_]+)?)/i',
            function ($matches) {
                // $matches[1] = CHARSET=
                // $matches[2] = charset lama
                // $matches[3] = (optional) COLLATE dan spasi
                // Selalu ganti CHARSET dan COLLATE, lalu tambahkan lagi bagian lain setelahnya (ROW_FORMAT dsb)
                return "{$matches[1]}{$this->charset} COLLATE={$this->collation}";
            },
            $sql
        );

        // Jika tidak ada COLLATE, tambahkan setelah CHARSET (tapi sebelum ROW_FORMAT)
        $sql = preg_replace(
            '/(DEFAULT CHARSET\s*=\s*' . preg_quote($this->charset, '/') . ')(?!.*COLLATE)/i',
            '$1 COLLATE=' . $this->collation,
            $sql
        );

        return $sql;
    }
}
