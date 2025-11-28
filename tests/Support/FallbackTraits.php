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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

/**
 * Fallback Traits, Classes, dan Functions untuk Testing
 *
 * File ini menyediakan fallback untuk package-package dan helper functions
 * yang tidak diinstall atau tidak tersedia di environment testing terpisah.
 *
 * DAFTAR FALLBACK:
 * 1. Helper Functions (CodeIgniter & OpenSID)
 * 2. Spatie Activitylog
 * 3. Spatie Eloquent Sortable
 * 4. Spatie One Time Passwords
 * 5. Spatie Image (jika dibutuhkan)
 */

// ============================================================================
// 1. FALLBACK HELPER FUNCTIONS (CodeIgniter & OpenSID)
// ============================================================================

/**
 * Fallback untuk function identitas()
 * Digunakan di: ConfigIdScope, ConfigIdObserver, dan banyak tempat lain
 */
if (! function_exists('identitas')) {
    function identitas($key = null)
    {
        $identitas = [
            'id'         => 1,
            'nama_desa'  => 'Desa Test',
            'kode_desa'  => '0000000000',
            'config_id'  => 1,
        ];

        if ($key === null) {
            return (object) $identitas;
        }

        return $identitas[$key] ?? null;
    }
}

/**
 * Fallback untuk function ci_auth()
 * Digunakan di: AccessWilayahScope, AuthorObserver, dan banyak tempat lain
 * Return object dengan properties: id, batasi_wilayah, akses_wilayah
 */
if (! function_exists('ci_auth')) {
    function ci_auth($params = null)
    {
        $user = new class {
            public $id = 1;
            public $batasi_wilayah = false; // false = tidak membatasi wilayah
            public $akses_wilayah = [];
            public $nama = 'Test User';
            public $username = 'testuser';
            public $active = 1;
            public $config_id = 1;
            public $id_grup = 1;

            public function id() { return $this->id; }
        };

        if ($params !== null) {
            return $user->{$params} ?? null;
        }

        return $user;
    }
}

/**
 * Fallback untuk function show_404() - CodeIgniter
 * Digunakan di: BaseModel::findOrFail, BaseModel::firstOrFail
 */
if (! function_exists('show_404')) {
    function show_404($page = '', $log_error = true)
    {
        throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Model not found: {$page}");
    }
}

/**
 * Fallback untuk function config_item() - CodeIgniter
 */
if (! function_exists('config_item')) {
    function config_item($item)
    {
        $config = [
            'sess_driver'     => 'files',
            'sess_save_path'  => '/tmp',
            'base_url'        => 'http://localhost/',
            'index_page'      => '',
        ];
        return $config[$item] ?? null;
    }
}

/**
 * Fallback untuk function get_instance() - CodeIgniter
 */
if (! function_exists('get_instance')) {
    function &get_instance()
    {
        static $instance;
        if ($instance === null) {
            $instance = new class {
                public $session;
                public $db;
                public $load;
                public $input;

                public function __construct() {
                    $this->session = new class {
                        public $isAdmin;
                        public function __construct() {
                            $this->isAdmin = ci_auth();
                        }
                        public function userdata($key = null) { return null; }
                        public function set_userdata($key, $value = null) {}
                    };
                }

                public function __call($name, $args) { return $this; }
                public function __get($name) { return null; }
            };
        }
        return $instance;
    }
}

/**
 * Fallback untuk function log_message() - CodeIgniter
 */
if (! function_exists('log_message')) {
    function log_message($level, $message)
    {
        // Do nothing in test environment
    }
}

/**
 * Fallback untuk function max_upload() - OpenSID
 * Digunakan di: Upload trait
 */
if (! function_exists('max_upload')) {
    function max_upload(bool $byteFormat = false)
    {
        return $byteFormat ? '10M' : 10;
    }
}

/**
 * Fallback untuk function setting() - OpenSID
 * Digunakan di berbagai tempat untuk mengambil setting aplikasi
 */
if (! function_exists('setting')) {
    function setting($key = null)
    {
        $settings = [
            'sebutan_desa'    => 'Desa',
            'sebutan_kecamatan' => 'Kecamatan',
            'sebutan_kabupaten' => 'Kabupaten',
        ];

        if ($key === null) {
            return (object) $settings;
        }

        return $settings[$key] ?? null;
    }
}

/**
 * Fallback untuk function rev_tgl() - OpenSID
 */
if (! function_exists('rev_tgl')) {
    function rev_tgl($tgl, $default = null)
    {
        if (empty($tgl)) {
            return $default;
        }
        return date('Y-m-d', strtotime($tgl));
    }
}

/**
 * NOTE: bilangan() sudah tersedia di donjo-app/helpers/opensid_helper.php
 * Helper produksi dimuat melalui autoload testbench, jadi fallback tidak diperlukan.
 * Menghapus ini untuk menghindari "Cannot redeclare" error.
 */

/**
 * Fallback untuk function desa_storage() - OpenSID
 */
if (! function_exists('desa_storage')) {
    function desa_storage($path = '')
    {
        $baseDir = DESAPATH ?? dirname(__DIR__, 2) . '/desa/';
        if (empty($path)) {
            return rtrim($baseDir, '/\\');
        }
        return rtrim($baseDir, '/\\') . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
    }
}

/**
 * Fallback untuk function cek_anjungan() - OpenSID
 * Pada test environment, selalu return true untuk memudahkan testing
 */
if (! function_exists('cek_anjungan')) {
    function cek_anjungan(): bool
    {
        // Return true di test environment
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'testing') {
            return true;
        }
        return false;
    }
}

/**
 * Fallback untuk defined constants
 */
if (! defined('LOKASI_USER_PICT')) {
    define('LOKASI_USER_PICT', '/tmp/');
}

if (! defined('FCPATH')) {
    define('FCPATH', dirname(__DIR__, 2) . '/');
}

if (! defined('APPPATH')) {
    define('APPPATH', dirname(__DIR__, 2) . '/donjo-app/');
}

// ============================================================================
// 2. FALLBACK SPATIE ACTIVITYLOG
// ============================================================================

/**
 * Fallback untuk trait Spatie\Activitylog\Traits\LogsActivity
 */
if (! trait_exists('Spatie\Activitylog\Traits\LogsActivity')) {
    eval('
        namespace Spatie\Activitylog\Traits;
        trait LogsActivity {
            public static function bootLogsActivity() {}
            protected static function logChanges($model) {}
            public function tapActivity($activity, $eventName) {}
            public function getActivitylogOptions() {
                return \Spatie\Activitylog\LogOptions::defaults();
            }
            public function shouldLogUnguarded(): bool {
                return false;
            }
        }
    ');
}

/**
 * Fallback untuk class Spatie\Activitylog\LogOptions
 */
if (! class_exists('Spatie\Activitylog\LogOptions')) {
    eval('
        namespace Spatie\Activitylog;
        class LogOptions {
            protected array $logAttributes = [];
            protected bool $logOnlyDirty = false;
            protected bool $submitEmptyLogs = true;
            protected string $logName = "default";

            public static function defaults(): self {
                return new static;
            }
            public function logOnly(array $attributes): self {
                $this->logAttributes = $attributes;
                return $this;
            }
            public function logOnlyDirty(): self {
                $this->logOnlyDirty = true;
                return $this;
            }
            public function dontSubmitEmptyLogs(): self {
                $this->submitEmptyLogs = false;
                return $this;
            }
            public function useLogName(string $name): self {
                $this->logName = $name;
                return $this;
            }
            public function logFillable(): self {
                return $this;
            }
            public function logUnguarded(): self {
                return $this;
            }
            public function logExcept(array $attributes): self {
                return $this;
            }
        }
    ');
}

/**
 * Fallback untuk trait Spatie\Activitylog\Traits\CausesActivity
 */
if (! trait_exists('Spatie\Activitylog\Traits\CausesActivity')) {
    eval('
        namespace Spatie\Activitylog\Traits;
        trait CausesActivity {
            public function actions() {
                return collect([]);
            }
        }
    ');
}

// ============================================================================
// 3. FALLBACK SPATIE ELOQUENT SORTABLE
// ============================================================================

/**
 * Fallback untuk interface Spatie\EloquentSortable\Sortable
 */
if (! interface_exists('Spatie\EloquentSortable\Sortable')) {
    eval('
        namespace Spatie\EloquentSortable;
        interface Sortable {
            public function buildSortQuery();
        }
    ');
}

/**
 * Fallback untuk trait Spatie\EloquentSortable\SortableTrait
 */
if (! trait_exists('Spatie\EloquentSortable\SortableTrait')) {
    eval('
        namespace Spatie\EloquentSortable;
        trait SortableTrait {
            public static function bootSortableTrait(): void {}

            public function buildSortQuery() {
                return static::query();
            }

            public function moveOrderUp(): self {
                return $this;
            }

            public function moveOrderDown(): self {
                return $this;
            }

            public function moveToStart(): self {
                return $this;
            }

            public function moveToEnd(): self {
                return $this;
            }

            public static function setNewOrder($ids, int $startOrder = 1, ?string $primaryKeyColumn = null): void {
            }

            public function shouldSortWhenCreating(): bool {
                return $this->sortable["sort_when_creating"] ?? true;
            }

            public function getHighestOrderNumber(): int {
                return 0;
            }

            public function getLowestOrderNumber(): int {
                return 0;
            }

            public function scopeOrdered($query, string $direction = "asc") {
                return $query->orderBy($this->determineOrderColumnName(), $direction);
            }

            public function determineOrderColumnName(): string {
                return $this->sortable["order_column_name"] ?? "order_column";
            }
        }
    ');
}

// ============================================================================
// 4. FALLBACK SPATIE ONE TIME PASSWORDS
// ============================================================================

/**
 * Fallback untuk trait Spatie\OneTimePasswords\Models\Concerns\HasOneTimePasswords
 */
if (! trait_exists('Spatie\OneTimePasswords\Models\Concerns\HasOneTimePasswords')) {
    eval('
        namespace Spatie\OneTimePasswords\Models\Concerns;
        trait HasOneTimePasswords {
            public function oneTimePasswords() {
                return $this->morphMany(\Spatie\OneTimePasswords\Models\OneTimePassword::class ?? \stdClass::class, "authenticatable");
            }
            public function createOneTimePassword(): ?object {
                return null;
            }
            public function verifyOneTimePassword(string $password): bool {
                return false;
            }
        }
    ');
}

/**
 * Fallback untuk class Spatie\OneTimePasswords\Models\OneTimePassword
 */
if (! class_exists('Spatie\OneTimePasswords\Models\OneTimePassword')) {
    eval('
        namespace Spatie\OneTimePasswords\Models;
        class OneTimePassword extends \Illuminate\Database\Eloquent\Model {
            protected $table = "one_time_passwords";
        }
    ');
}

// ============================================================================
// 5. FALLBACK SPATIE IMAGE (jika diperlukan)
// ============================================================================

/**
 * Fallback untuk class Spatie\Image\Image
 */
if (! class_exists('Spatie\Image\Image')) {
    eval('
        namespace Spatie\Image;
        class Image {
            protected string $path = "";

            public static function load(string $path): self {
                $instance = new static;
                $instance->path = $path;
                return $instance;
            }

            public function width(int $width): self {
                return $this;
            }

            public function height(int $height): self {
                return $this;
            }

            public function save(string $outputPath = ""): self {
                // Just copy file in test environment
                if (!empty($outputPath) && file_exists($this->path)) {
                    @copy($this->path, $outputPath);
                }
                return $this;
            }

            public function format(string $format): self {
                return $this;
            }

            public function quality(int $quality): self {
                return $this;
            }

            public function fit(string $fit, int $width, int $height): self {
                return $this;
            }
        }
    ');
}

/**
 * Fallback untuk class Spatie\Image\Manipulations
 */
if (! class_exists('Spatie\Image\Manipulations')) {
    eval('
        namespace Spatie\Image;
        class Manipulations {
            public const FIT_CONTAIN = "contain";
            public const FIT_MAX = "max";
            public const FIT_FILL = "fill";
            public const FIT_STRETCH = "stretch";
            public const FIT_CROP = "crop";

            public const BORDER_OVERLAY = "overlay";
            public const BORDER_SHRINK = "shrink";
            public const BORDER_EXPAND = "expand";

            public const ORIENTATION_AUTO = "auto";
            public const ORIENTATION_90 = 90;
            public const ORIENTATION_180 = 180;
            public const ORIENTATION_270 = 270;

            public const FLIP_HORIZONTALLY = "h";
            public const FLIP_VERTICALLY = "v";
            public const FLIP_BOTH = "both";

            public const FORMAT_JPG = "jpg";
            public const FORMAT_PJPG = "pjpg";
            public const FORMAT_PNG = "png";
            public const FORMAT_GIF = "gif";
            public const FORMAT_WEBP = "webp";
            public const FORMAT_AVIF = "avif";
            public const FORMAT_TIFF = "tiff";

            public const FILTER_GREYSCALE = "greyscale";
            public const FILTER_SEPIA = "sepia";
        }
    ');
}

// ============================================================================
// 6. FALLBACK RENNOKKI LARAVEL QUERY CACHE
// ============================================================================

/**
 * Fallback untuk trait Rennokki\QueryCache\Traits\QueryCacheable
 */
if (! trait_exists('Rennokki\QueryCache\Traits\QueryCacheable')) {
    eval('
        namespace Rennokki\QueryCache\Traits;
        trait QueryCacheable {
            public static function bootQueryCacheable() {}
            
            public function getQueryCacheTagsArray() {
                return [];
            }
            
            public function getQueryCacheDuration() {
                return null;
            }
            
            public function withQueryCacheOptions(array $options = []) {
                return $this;
            }
            
            public function disableQueryCache(bool $disable = true) {
                return $this;
            }
            
            public function flushQueryCache() {
                return true;
            }
        }
    ');
}
