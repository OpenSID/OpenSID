# ADR-009: Shared Database Configuration Strategy

## Status
**Accepted** - Implemented

### Implementation Status
- **Verification Date**: 2025-12-26
- **Current Implementation**: 80% Complete
- **Production Ready**: ⚠️ Needs Migration Tool
- **Issues Found**: 1 High (No migration tracking)
- **Verdict**: ⚠️ VERIFIED - Working but incomplete

## Context

Aplikasi hibrid membutuhkan **satu database** yang accessible dari kedua framework:

**Laravel Database Config:**
```php
// config/database.php
'connections' => [
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST'),
        'database' => env('DB_DATABASE'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
    ]
]
```

**CodeIgniter Database Config:**
```php
// desa/config/database.php
$db['default'] = [
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => encrypt_decrypt('decrypt', $password),
    'database' => 'opensid',
    'dbdriver' => 'mysqli',
];
```

**Problems:**

1. **Dual Configuration** - Perlu maintain config di dua tempat
2. **Credential Sync** - Password beda format (encrypted vs plain)
3. **Connection Reuse** - Tidak bisa share connection pool
4. **Migration Confusion** - Laravel migrations vs CI migrations
5. **Environment Variables** - CI tidak native support `.env`

**Requirements:**

- Single source of truth untuk database credentials
- Share connection jika possible
- Support CI's encrypted password
- Seamless untuk developer
- Migration strategy clear

## Decision

**Use Laravel's .env as single source of truth**, sync to CI config during bootstrap.

### Architecture

```
.env file (Single Source of Truth)
  ↓
Laravel config/database.php loads .env
  ↓
CodeIgniterBootstrap syncs to CI config
  ↓
Both frameworks use same credentials
  ↓
Shared MySQL connection pool (at MySQL level)
```

### Implementation

#### 1. Environment Configuration

```env
# .env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=opensid
DB_USERNAME=root
DB_PASSWORD=
DB_STRICT_MODE=true
DB_ENGINE=InnoDB
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

#### 2. Laravel Database Config

```php
// config/database.php

'connections' => [
    'mysql' => [
        'driver' => 'mysql',
        'url' => env('DATABASE_URL'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'opensid'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
        'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => env('DB_STRICT_MODE', true),
        'engine' => env('DB_ENGINE', 'InnoDB'),
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],
],
```

#### 3. CI Database Config (Synced)

```php
// desa/config/database.php

defined('BASEPATH') or exit('No direct script access allowed');

// Load from Laravel config
$laravelConfig = config('database.connections.mysql');

$db['default'] = [
    'dsn' => '',
    'hostname' => $laravelConfig['host'] ?? 'localhost',
    'port' => $laravelConfig['port'] ?? 3306,
    'username' => $laravelConfig['username'] ?? 'root',
    'password' => $laravelConfig['password'] ?? '',
    'database' => $laravelConfig['database'] ?? 'opensid',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => false,
    'db_debug' => ENVIRONMENT !== 'production',
    'cache_on' => false,
    'cachedir' => '',
    'char_set' => $laravelConfig['charset'] ?? 'utf8mb4',
    'dbcollat' => $laravelConfig['collation'] ?? 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt' => false,
    'compress' => false,
    'stricton' => $laravelConfig['strict'] ?? true,
    'failover' => [],
    'save_queries' => ENVIRONMENT === 'development',
];

return $db;
```

#### 4. Bootstrap Synchronization

```php
// app/Bootstrap/CodeIgniterBootstrap.php

protected function configureConfig(Application $app): void
{
    if (!isset($GLOBALS['CFG']) || !$GLOBALS['CFG'] instanceof \CI_Config) {
        return;
    }
    
    // Load CI database config
    $GLOBALS['CFG']->load('database', true);
    
    $config = $app['config'];
    $configCI = $GLOBALS['CFG']->config;
    
    // Sync database configuration FROM Laravel TO CI
    if (isset($configCI['database']['connections'])) {
        $config->set('database.connections', array_merge(
            $config->get('database.connections', []),
            $configCI['database']['connections']
        ));
    }
    
    // Also sync FROM CI TO Laravel if needed (for custom CI configs)
    $config->set('database.default', $configCI['database']['default'] ?? 'default');
}
```

## Consequences

### Positive

✅ **Single Source of Truth**
- `.env` file adalah master config
- No duplication
- Easy to update

✅ **Environment Parity**
- Development, staging, production use same pattern
- No config drift
- Easy deployment

✅ **Laravel Migration Support**
```bash
php artisan migrate
php artisan migrate:rollback
php artisan db:seed
```

✅ **Eloquent & Query Builder**
```php
// Laravel
DB::table('penduduk')->where('status', 1)->get();

// CI (still works)
$this->db->get_where('penduduk', ['status' => 1]);
```

✅ **Connection Pooling**
- MySQL reuses connections
- Better performance
- Lower resource usage

### Negative

⚠️ **CI Password Encryption Challenge**
```php
// Legacy CI config used encryption
$password = encrypt_decrypt('decrypt', $encryptedPassword);

// Now stored in plain .env (must be secured)
```

⚠️ **Migration Tool Confusion**
- Laravel migrations vs CI migrations
- Need to choose one
- Legacy CI migrations may exist

⚠️ **Testing Database**
```php
// Laravel expects testing database
'testing' => [
    'database' => 'opensid_testing',
]

// CI tidak punya testing config bawaan
```

⚠️ **Artisan Commands Access**
- CI code cannot easily use `php artisan db:*`
- Need to understand both ecosystems

## Alternatives Considered

### Alternative 1: Separate Databases

```env
DB_DATABASE=opensid_laravel
CI_DB_DATABASE=opensid_ci
```

**Rejected** karena:
- Data duplication nightmare
- Synchronization overhead
- Defeats purpose of shared app
- Complex joins impossible

### Alternative 2: CI as Master Config

```php
// Load CI config first, sync to Laravel
$ciConfig = include 'desa/config/database.php';
config(['database.connections.mysql' => $ciConfig]);
```

**Rejected** karena:
- Against Laravel conventions
- .env pattern broken
- Artisan commands broken
- Migrations don't work

### Alternative 3: Database Config Service

```php
class DatabaseConfigService
{
    public function getConfig() {
        // Centralized config logic
    }
}
```

**Rejected** karena:
- Over-engineering
- Complexity without benefit
- Still need sync logic

### Alternative 4: Runtime Database Switch

```php
// Switch database per request
if ($isLaravelRoute) {
    DB::setDefaultConnection('laravel');
} else {
    DB::setDefaultConnection('codeigniter');
}
```

**Rejected** karena:
- Same database anyway
- Unnecessary complexity
- Performance overhead

## Implementation Details

### Database Credentials Flow

```
Developer updates .env
  ↓
DB_PASSWORD=secretpass
  ↓
Laravel loads config/database.php
  ↓
config('database.connections.mysql.password') = 'secretpass'
  ↓
CodeIgniterBootstrap syncs
  ↓
CI $db['default']['password'] = 'secretpass'
  ↓
Both frameworks connect with same credentials ✅
```

### Migration Strategy

**Chosen: Laravel Migrations**

```bash
# Create new migration
php artisan make:migration create_penduduk_table

# Run migrations
php artisan migrate

# Check status
php artisan migrate:status
```

**Legacy CI Migrations:**
```php
// donjo-app/migrations/001_create_penduduk.php

// Still exist but not actively used
// Converted to Laravel migrations when possible
```

**Migration Conversion Example:**

CI Migration:
```php
// donjo-app/migrations/001_add_column.php
$this->dbforge->add_column('penduduk', [
    'new_field' => ['type' => 'VARCHAR', 'constraint' => 100]
]);
```

Laravel Migration:
```php
// database/migrations/2024_01_01_000001_add_column_to_penduduk.php
Schema::table('penduduk', function (Blueprint $table) {
    $table->string('new_field', 100)->nullable();
});
```

### Query Builder Compatibility

**Laravel Eloquent:**
```php
use App\Models\Penduduk;

$penduduk = Penduduk::where('status', 1)->get();
$penduduk = Penduduk::find(123);
```

**Laravel Query Builder:**
```php
use Illuminate\Support\Facades\DB;

$penduduk = DB::table('penduduk')->where('status', 1)->get();
```

**CI Query Builder:**
```php
$this->db->where('status', 1);
$penduduk = $this->db->get('penduduk')->result();

$penduduk = $this->db->get_where('penduduk', ['id' => 123])->row();
```

**All hit same database!**

### Testing Database Configuration

```php
// config/database.php

'testing' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'database' => env('DB_DATABASE') . '_testing', // opensid_testing
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
],
```

```php
// phpunit.xml
<env name="DB_CONNECTION" value="testing"/>
<env name="DB_DATABASE" value="opensid_testing"/>
```

```php
// tests/TestCase.php
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends BaseTestCase
{
    use RefreshDatabase; // Reset database setiap test
}
```

## Security Considerations

### Encrypt Database Password in .env

**Option 1: Use encrypted .env**
```bash
# Encrypt entire .env
php artisan env:encrypt

# Decrypt for deployment
php artisan env:decrypt --key=base64:...
```

**Option 2: Use encrypted value**
```env
DB_PASSWORD="encrypted:eyJpdiI6Ij..."
```

```php
// config/database.php
'password' => decrypt(env('DB_PASSWORD')),
```

### Restrict Database Permissions

```sql
-- Create limited user for application
CREATE USER 'opensid_app'@'localhost' IDENTIFIED BY 'secretpass';

-- Grant only necessary permissions
GRANT SELECT, INSERT, UPDATE, DELETE ON opensid.* TO 'opensid_app'@'localhost';

-- No DROP, CREATE, ALTER permissions
```

### Environment File Protection

```nginx
# nginx config
location ~ /\.env {
    deny all;
}
```

```apache
# .htaccess
<Files .env>
    Order allow,deny
    Deny from all
</Files>
```

## Monitoring & Debugging

### Log Database Queries

**Laravel:**
```php
// In AppServiceProvider
if (app()->environment('local')) {
    DB::listen(function ($query) {
        Log::debug('Query', [
            'sql' => $query->sql,
            'bindings' => $query->bindings,
            'time' => $query->time . 'ms',
        ]);
    });
}
```

**CodeIgniter:**
```php
// Enable query logging in database config
'save_queries' => ENVIRONMENT === 'development',

// View queries
$queries = $this->db->queries;
log_message('debug', print_r($queries, true));
```

### Monitor Connection Pool

```bash
# MySQL connections
mysql> SHOW PROCESSLIST;

# Active connections
mysql> SHOW STATUS LIKE 'Threads_connected';

# Max connections reached
mysql> SHOW STATUS LIKE 'Max_used_connections';
```

### Database Health Check

```php
Route::get('/health/database', function() {
    try {
        // Laravel connection
        DB::connection()->getPdo();
        $laravelOk = true;
    } catch (\Exception $e) {
        $laravelOk = false;
    }
    
    try {
        // CI connection
        $CI =& get_instance();
        $CI->load->database();
        $ciOk = $CI->db->conn_id ? true : false;
    } catch (\Exception $e) {
        $ciOk = false;
    }
    
    return [
        'laravel_db' => $laravelOk ? 'ok' : 'failed',
        'ci_db' => $ciOk ? 'ok' : 'failed',
        'database' => config('database.connections.mysql.database'),
    ];
});
```

## Performance Optimization

### Connection Pooling

MySQL automatically pools connections:
```
Request 1: Connect → Query → Disconnect (connection pooled)
Request 2: Reuse pooled connection → Query → Disconnect
```

Laravel persistent connections:
```php
'mysql' => [
    'options' => [
        PDO::ATTR_PERSISTENT => true, // Persistent connections
    ],
]
```

CI persistent connections:
```php
$db['default'] = [
    'pconnect' => true, // Persistent connections
];
```

### Query Caching

**Laravel:**
```php
$penduduk = Cache::remember('penduduk.all', 3600, function() {
    return DB::table('penduduk')->get();
});
```

**CI:**
```php
$this->db->cache_on();
$query = $this->db->get('penduduk');
$this->db->cache_off();
```

### Indexing Strategy

```php
// database/migrations/xxxx_add_indexes.php

Schema::table('penduduk', function (Blueprint $table) {
    $table->index('status'); // Single column
    $table->index(['id_cluster', 'status']); // Composite
    $table->fullText('nama'); // Full text search
});
```

## Backup Strategy

### Automatic Backups

```bash
# Using Laravel Backup package
composer require spatie/laravel-backup

php artisan backup:run
```

```php
// config/backup.php
'backup' => [
    'source' => [
        'databases' => ['mysql'],
    ],
    'destination' => [
        'disks' => ['s3', 'local'],
    ],
],
```

### Manual Backup

```bash
# MySQL dump
mysqldump -u root opensid > backup.sql

# Restore
mysql -u root opensid < backup.sql

# Artisan command
php artisan db:backup
```

## Related ADRs

- [ADR-003: Custom Bootstrap Process](./ADR-003-custom-bootstrap-process.md)
- [ADR-010: Dynamic Config Loading](./ADR-010-dynamic-config-loading.md)
- [ADR-001: Hybrid Architecture](./ADR-001-hybrid-laravel-codeigniter.md)

## References

- [Laravel Database Configuration](https://laravel.com/docs/10.x/database)
- [Eloquent ORM](https://laravel.com/docs/10.x/eloquent)
- [CodeIgniter Database](https://codeigniter.com/userguide3/database/index.html)
- [MySQL Connection Pooling](https://dev.mysql.com/doc/refman/8.0/en/connection-pooling.html)

---

**Decision Maker**: Tim Arsitektur OpenSID  
**Date**: Januari 2024  
**Last Updated**: Desember 2025 (Verified - Needs Migration Tool)

## Verification Report

### ⚠️ Working but Missing Migration Tracking

**Verification Date**: 2025-12-26

#### Current Implementation:
- ✅ Single database connection working
- ✅ Config synchronization working
- ✅ Schema sharing functional
- ✅ Transactions working
- ❌ **NO MIGRATION TRACKING** (CRITICAL GAP)

#### Critical Issue: No Migration Tracking
```
Problem: No way to track which tables migrated from CI3 to Laravel
Risk: Data inconsistency during module migration
Impact: Cannot safely migrate modules

Current State:
├─ CI3 tables: penduduk, keluarga, pendapatan, ...
├─ Laravel tables: users, articles, ...
└─ Unknown: Which are being migrated NOW?
```

#### Recommended Solution:
Create `MigrationTracker` service to track module migrations:
- Which modules migrated
- Which tables belong to which module
- Migration status per module
- Rollback capability

#### Timeline:
- **Effort**: 4 hours (design + implementation)
- **Priority**: CRITICAL
- **Status**: PENDING - MUST DO BEFORE PRODUCTION

---
