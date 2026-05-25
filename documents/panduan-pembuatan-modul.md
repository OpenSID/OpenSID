# Panduan Teknis Pembuatan Modul OpenSID

> Dokumen ini menjelaskan secara lengkap cara membuat modul bawaan (built-in) maupun modul premium untuk sistem OpenSID. Panduan mencakup struktur direktori, model, controller, routing, migrasi, seeder, service provider, view, dan konvensi penulisan kode.

---

## Daftar Isi

- [1. Gambaran Umum Arsitektur Modul](#1-gambaran-umum-arsitektur-modul)
- [2. Perbedaan Modul Bawaan vs Modul Premium](#2-perbedaan-modul-bawaan-vs-modul-premium)
- [3. Struktur Direktori Modul](#3-struktur-direktori-modul)
- [4. Langkah-Langkah Membuat Modul Baru](#4-langkah-langkah-membuat-modul-baru)
  - [4.1 File Metadata (module.json)](#41-file-metadata-modulejson)
  - [4.2 File Composer (composer.json)](#42-file-composer-composerjson)
  - [4.3 Konfigurasi Modul (Config/config.php)](#43-konfigurasi-modul-configconfigphp)
  - [4.4 Service Provider](#44-service-provider)
  - [4.5 Model](#45-model)
  - [4.6 Migration](#46-migration)
  - [4.7 Seeder](#47-seeder)
  - [4.8 Controller](#48-controller)
  - [4.9 Form Request Validation](#49-form-request-validation)
  - [4.10 Routes](#410-routes)
  - [4.11 Views (Blade Template)](#411-views-blade-template)
  - [4.12 Enums (Opsional)](#412-enums-opsional)
  - [4.13 Helpers (Opsional)](#413-helpers-opsional)
  - [4.14 Services & Repositories (Opsional)](#414-services--repositories-opsional)
- [5. Sistem Multi-Tenant (ConfigId)](#5-sistem-multi-tenant-configid)
- [6. Sistem Hak Akses & Permission](#6-sistem-hak-akses--permission)
- [7. Konvensi Penamaan](#7-konvensi-penamaan)
- [8. Gaya Penulisan Kode](#8-gaya-penulisan-kode)
- [9. Modul yang Memerlukan Lisensi Premium](#9-modul-yang-memerlukan-lisensi-premium)
- [10. Modul yang Sudah Ada (Referensi)](#10-modul-yang-sudah-ada-referensi)
- [11. Checklist Pembuatan Modul](#11-checklist-pembuatan-modul)
- [12. Git Workflow](#12-git-workflow)

---

## 1. Gambaran Umum Arsitektur Modul

OpenSID menggunakan arsitektur **modular** yang memisahkan fitur-fitur ke dalam modul independen di dalam direktori `Modules/`. Setiap modul merupakan unit mandiri yang memiliki:

- **Model** (Eloquent ORM)
- **Controller** (BackEnd dan FrontEnd)
- **Routes** (web dan/atau API)
- **Views** (Blade template)
- **Migrations** (skema database)
- **Seeders** (data awal & registrasi menu)
- **Service Provider** (registrasi modul ke framework)

Sistem ini dibangun di atas **Laravel** dengan beberapa adaptasi kustom dari framework CodeIgniter (hybrid), menggunakan helper seperti `ci_route()`, `redirect_with()`, `setting()`, dan `identitas()`.

---

## 2. Perbedaan Modul Bawaan vs Modul Premium

Pada implementasi repo ini, **modul bawaan** dan **modul premium** sama-sama mengikuti struktur modular di dalam direktori `Modules/`. Perbedaannya bukan pada lokasi fisik atau ada/tidaknya `module.json`, melainkan pada **status distribusi** dan **cara modul tersebut diperlakukan oleh aplikasi**.

| Aspek | Modul Bawaan (Bundled / terdaftar di `MODUL_BAWAAN`) | Modul Premium / Marketplace |
|-------|--------------------------------------------------------|-----------------------------|
| **Lokasi** | `Modules/NamaModul/` | `Modules/NamaModul/` |
| **Metadata** | Dapat memiliki `module.json` | Dapat memiliki `module.json` |
| **Distribusi** | Disertakan dalam paket instalasi / repo utama | Didistribusikan terpisah sebagai add-on / marketplace |
| **Penanda utama** | Nama modul termasuk dalam `MODUL_BAWAAN` | Tidak termasuk `MODUL_BAWAAN`, umumnya dipasang terpisah |
| **Migrasi** | Umumnya berada di `Modules/NamaModul/Database/Migrations/` | Umumnya berada di `Modules/NamaModul/Database/Migrations/` |
| **Service Provider** | Di-scan dan diregistrasikan dari `Modules/` oleh `App\\Providers\\AppServiceProvider::loadModuleServiceProvider()` | Di-scan dan diregistrasikan dari `Modules/` oleh `App\\Providers\\AppServiceProvider::loadModuleServiceProvider()` |
| **Lokasi Provider yang didukung** | `Providers/` atau `App/Providers/` di dalam modul | `Providers/` atau `App/Providers/` di dalam modul |
| **Independensi fitur** | Bundled dengan aplikasi, tetapi tetap berbentuk modul | Lebih cocok untuk fitur opsional/ekstensi terpisah |
| **Penghapusan / penonaktifan** | Tidak dibedakan dari struktur file; perlakuannya mengikuti status bundled aplikasi | Umumnya lebih fleksibel untuk dipasang/dilepas sebagai add-on |

### Kapan Menggunakan Modul Premium?

- Fitur bersifat **opsional** (tidak semua desa membutuhkan)
- Fitur dapat berdiri **mandiri** tanpa mengubah core
- Fitur memiliki **tabel database sendiri**
- Fitur memiliki **halaman admin dan/atau publik** tersendiri
- Fitur direncanakan untuk **didistribusikan terpisah** dari paket bawaan aplikasi

---

## 3. Struktur Direktori Modul

Berikut adalah struktur direktori standar untuk modul di dalam `Modules/`:

```
Modules/
└── NamaModul/
    ├── composer.json                    # Metadata package
    ├── module.json                      # Metadata modul (WAJIB)
    │
    ├── Config/
    │   └── config.php                   # Konfigurasi modul
    │
    ├── Database/
    │   ├── Migrations/
    │   │   ├── YYYY_MM_DD_HHMMSS_create_akses_modul.php      # Registrasi menu & setting
    │   │   ├── YYYY_MM_DD_HHMMSS_create_tabel_utama.php      # Tabel-tabel modul
    │   │   └── YYYY_MM_DD_HHMMSS_modify_tabel_lain.php       # Modifikasi tabel (jika perlu)
    │   └── Seeders/
    │       ├── NamaModulSeeder.php      # Seeder utama (memanggil seeder lain)
    │       ├── ModulSeeder.php          # Registrasi menu sidebar
    │       └── SettingSeeder.php        # Registrasi setting modul
    │
    ├── Enums/                           # (Opsional) PHP Enums untuk konstanta
    │   └── StatusEnum.php
    │
    ├── Helpers/                         # (Opsional) Fungsi helper
    │   └── nama_modul_helper.php
    │
    ├── Http/
    │   ├── Controllers/
    │   │   ├── BackEnd/                 # Controller admin/dashboard
    │   │   │   └── NamaController.php
    │   │   └── FrontEnd/               # Controller halaman publik
    │   │       └── PublikController.php
    │   └── Requests/                    # (Opsional) Form Request Validation
    │       └── NamaRequest.php
    │
    ├── Libraries/                       # (Opsional) Library kompleks
    │   └── NamaLibrary.php
    │
    ├── Models/
    │   └── NamaModel.php               # Eloquent Model
    │
    ├── Providers/
    │   └── NamaModulServiceProvider.php # Service Provider
    │
    ├── Repositories/                    # (Opsional) Repository pattern
    │   └── NamaRepository.php
    │
    ├── Routes/
    │   ├── web.php                      # Route web (WAJIB)
    │   └── api.php                      # (Opsional) Route API
    │
    ├── Services/                        # (Opsional) Business logic layer
    │   └── NamaService.php
    │
    └── Views/
        ├── assets/                      # CSS, JS, images khusus modul
        │   ├── css/
        │   ├── js/
        │   └── images/
        ├── backend/                     # View halaman admin
        │   ├── nama_resource/
        │   │   ├── index.blade.php      # Halaman list/datatables
        │   │   ├── form.blade.php       # Form create/edit
        │   │   ├── cetak.blade.php      # Halaman cetak
        │   │   └── show.blade.php       # Detail view
        │   └── layouts/                 # (Opsional) Layout khusus
        └── frontend/                    # View halaman publik
            └── pages/
```

> **Catatan penting:** struktur di atas adalah **contoh pola legacy** yang masih didukung, tetapi **bukan satu-satunya** struktur modul yang valid di repo ini.
>
> Variasi struktur yang juga didukung dan sudah digunakan di codebase antara lain:
> - **Service provider** dapat ditempatkan di `Providers/` **atau** `App/Providers/`.
> - **Routes** dapat menggunakan `Routes/` **atau** `routes/`.
> - **Views** dapat berada di `Views/`, `resources/Views/`, atau `Resources/views/` sesuai pola modul yang dipakai.
> - Generator `make:module` men-scaffold modul dengan pola berbasis `app/`, `routes/`, dan `resources/Views/`.
>
> Saat membuat modul baru, ikuti **salah satu pola secara konsisten** di dalam modul yang sama agar loader provider, route, dan view dapat ditemukan dengan benar.

---

## 4. Langkah-Langkah Membuat Modul Baru

### 4.1 File Metadata (module.json)

File ini **wajib** ada di root modul. Berisi informasi identitas modul.

```json
{
    "name": "NamaModul",
    "alias": "Modul Nama Modul",
    "description": "Deskripsi singkat tentang modul ini",
    "keywords": [],
    "priority": 0,
    "providers": [],
    "files": [],
    "thumbnail": "",
    "totalInstall": 0,
    "price": "RP. 0",
    "version": "1.0.0"
}
```

**Penjelasan field:**

| Field | Keterangan |
|-------|-----------|
| `name` | Nama modul (PascalCase, harus sama dengan nama folder) |
| `alias` | Nama tampilan modul |
| `description` | Deskripsi singkat modul |
| `keywords` | Kata kunci pencarian (array) |
| `priority` | Urutan prioritas loading (0 = default) |
| `providers` | Service provider tambahan (biasanya kosong) |
| `files` | File tambahan yang perlu di-load |
| `thumbnail` | URL thumbnail/ikon modul |
| `totalInstall` | Jumlah instalasi (otomatis) |
| `price` | Harga modul (format: `"RP. 0"` untuk gratis) |
| `version` | Versi modul (format: `YYMM.0.0` atau `1.0.0`) |

### 4.2 File Composer (composer.json)

```json
{
    "name": "opensid-modules/nama-modul",
    "description": "Deskripsi modul",
    "type": "library",
    "license": "GPL-3.0-or-later",
    "authors": [
        {
            "name": "Tim Pengembang OpenDesa",
            "email": "halo@opendesa.id"
        }
    ],
    "require": {}
}
```

### 4.3 Konfigurasi Modul (Config/config.php)

```php
<?php

return [
    'name' => 'Nama Modul',
];
```

> **Catatan:** Konfigurasi runtime (yang bisa diubah user) sebaiknya disimpan di tabel `settings` melalui `SettingSeeder`, bukan di file config. Akses via `setting('key_setting')`.

### 4.4 Service Provider

File: `Providers/NamaModulServiceProvider.php`

```php
<?php

/*
 * [Header Lisensi GPL V3 - lihat contoh di modul yang sudah ada]
 */

namespace Modules\NamaModul\Providers;

use Illuminate\Support\ServiceProvider;

class NamaModulServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $moduleName = 'NamaModul';

    /**
     * @var string
     */
    protected $moduleNameLower = 'namamodul';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerViews();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $sourcePath = FCPATH . 'Modules' . DIRECTORY_SEPARATOR . $this->moduleName . DIRECTORY_SEPARATOR . 'Views';
        $this->loadViewsFrom($sourcePath, $this->moduleNameLower);
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            $this->moduleNameLower
        );
    }
}
```

**Variasi:** Jika modul memiliki helper functions, tambahkan method `registerHelpers()`:

```php
public function boot(): void
{
    $this->registerConfig();
    $this->registerHelpers();
    $this->registerViews();
}

protected function registerHelpers(): void
{
    $helperPath = FCPATH . "Modules/{$this->moduleName}/Helpers/nama_modul_helper.php";
    if (is_file($helperPath)) {
        require_once $helperPath;
    }
}
```

### 4.5 Model

Semua model modul **wajib**:

1. Meng-extend `App\Models\BaseModel` (bukan `Illuminate\Database\Eloquent\Model`)
2. Menggunakan trait `App\Traits\ConfigId` untuk multi-tenant support
3. Mendeklarasikan `$table` secara eksplisit
4. Mendefinisikan proteksi mass assignment secara eksplisit, menggunakan `$guarded = []` **atau** `$fillable`, sesuai kebutuhan dan standar modul yang dirujuk

File: `Models/ContohModel.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

namespace Modules\NamaModul\Models;

use App\Models\BaseModel;
use App\Traits\ConfigId;

class ContohModel extends BaseModel
{
    use ConfigId;

    // Konstanta status
    public const AKTIF      = 1;
    public const TIDAK_AKTIF = 0;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nama_tabel';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The appends with the model.
     *
     * @var array
     */
    protected $appends = [
        'nama_computed_attribute',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'integer',
        'tanggal' => 'date',
    ];

    // =========================================================================
    // ACCESSORS (Getter)
    // =========================================================================

    /**
     * Getter untuk nama_computed_attribute
     *
     * @return string
     */
    public function getNamaComputedAttributeAttribute(): string
    {
        return strtoupper($this->nama);
    }

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Relasi ke model detail/anak
     */
    public function details()
    {
        return $this->hasMany(DetailModel::class, 'contoh_id');
    }

    /**
     * Relasi ke model induk
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Scope filter untuk DataTables
     */
    public function scopeFilters($query, array $filters = [])
    {
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['tanggal'])) {
            $query->whereDate('created_at', $filters['tanggal']);
        }

        return $query;
    }
}
```

**Pola penamaan model:**

| Pola | Contoh | Keterangan |
|------|--------|-----------|
| PascalCase | `AnalisisMaster` | Standar (direkomendasikan) |
| PascalCase + Suffix Model | `TamuModel` | Alternatif (digunakan di BukuTamu) |

### 4.6 Migration

#### 4.6.1 Migration Registrasi Modul (create_akses_modul)

Migration pertama **selalu** untuk registrasi menu dan setting. File ini menjalankan seeder utama.

File: `Database/Migrations/YYYY_MM_DD_HHMMSS_create_akses_modul.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

use App\Traits\Migrator;
use Modules\NamaModul\Database\Seeders\NamaModulSeeder;

return new class () {
    use Migrator;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        (new NamaModulSeeder())->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $id = identitas('id');
        $this->deleteSetting(['config_id' => $id, 'kategori' => 'nama-modul']);
        $this->deleteModul(['config_id' => $id, 'slug' => 'nama-modul']);
    }
};
```

#### 4.6.2 Migration Tabel Database

File: `Database/Migrations/YYYY_MM_DD_HHMMSS_create_nama_tabel_table.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\NamaModul\Models\ContohModel;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Selalu cek apakah tabel sudah ada
        if (! Schema::hasTable('nama_tabel')) {
            Schema::create('nama_tabel', static function (Blueprint $table) {
                $table->integer('id', true);           // Auto-increment primary key
                $table->configId();                    // Multi-tenant ID (WAJIB)
                $table->string('nama', 100);
                $table->string('kode', 50)->nullable();
                $table->text('deskripsi')->nullable();
                $table->tinyInteger('status')->default(1)->comment('0: Tidak Aktif, 1: Aktif');
                $table->string('foto', 100)->nullable();
                $table->timestamps();                  // created_at, updated_at

                // Unique constraint (dengan config_id untuk multi-tenant)
                $table->unique(['config_id', 'kode']);
            });
        }

        // Menambah kolom ke tabel yang sudah ada (untuk update)
        if (Schema::hasTable('nama_tabel') && ! Schema::hasColumn('nama_tabel', 'kolom_baru')) {
            Schema::table('nama_tabel', static function (Blueprint $table) {
                $table->string('kolom_baru', 100)->nullable()->after('nama');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExistsDBGabungan('nama_tabel', static function () {
            ContohModel::withoutConfigId(identitas('id'))->delete();
        });
    }
};
```

**Pola penting pada migration:**

| Pola | Keterangan |
|------|-----------|
| `$table->configId()` | Macro kustom untuk kolom `config_id` (multi-tenant) |
| `$table->integer('id', true)` | Auto-increment integer (bukan bigIncrements) |
| `Schema::hasTable()` | Selalu cek sebelum create (idempoten) |
| `Schema::hasColumn()` | Selalu cek sebelum tambah kolom |
| `Schema::dropIfExistsDBGabungan()` | Kustom drop yang menghapus data per config |

**Format penamaan file migration:**

```
YYYY_MM_DD_HHMMSS_deskripsi_aksi.php

Contoh:
2025_12_19_011545_create_akses_modul.php
2025_12_21_080511_create_buku_tamu_table.php
2025_12_22_080511_create_produk_kategori_table.php
```

### 4.7 Seeder

#### 4.7.1 Seeder Utama

File: `Database/Seeders/NamaModulSeeder.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

namespace Modules\NamaModul\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class NamaModulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(ModulSeeder::class);
        $this->call(SettingSeeder::class);
    }
}
```

#### 4.7.2 ModulSeeder (Registrasi Menu Sidebar)

File: `Database/Seeders/ModulSeeder.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

namespace Modules\NamaModul\Database\Seeders;

use App\Traits\Migrator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ModulSeeder extends Seeder
{
    use Migrator;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $id = identitas('id');

        // Menu Utama (parent) di sidebar
        $this->createModul([
            'config_id' => $id,
            'modul'     => 'Nama Modul',           // Teks menu
            'slug'      => 'nama-modul',            // URL slug (unik)
            'url'       => '',                      // Kosong untuk parent
            'ikon'      => 'fa-puzzle-piece',       // Font Awesome icon
            'level'     => 2,                       // Level akses: 2=Admin
            'parent'    => 0,                       // 0 = menu utama
        ]);

        // Sub Menu
        $this->createModuls([
            [
                'modul'       => 'Data Utama',
                'slug'        => 'data-utama',
                'url'         => 'nama_modul',          // URL route controller
                'ikon'        => 'fa-database',
                'level'       => 2,
                'parent_slug' => 'nama-modul',          // Slug parent
            ],
            [
                'modul'       => 'Pengaturan',
                'slug'        => 'pengaturan-modul',
                'url'         => 'nama_modul_pengaturan',
                'ikon'        => 'fa-cog',
                'level'       => 2,
                'parent_slug' => 'nama-modul',
            ],
        ]);
    }
}
```

#### 4.7.3 SettingSeeder (Registrasi Pengaturan)

File: `Database/Seeders/SettingSeeder.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

namespace Modules\NamaModul\Database\Seeders;

use App\Enums\StatusEnum;
use App\Traits\Migrator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    use Migrator;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->createSettings([
            [
                'judul'      => 'Fitur Aktif',
                'key'        => 'nama_modul_aktif',
                'value'      => StatusEnum::YA,
                'keterangan' => 'Aktifkan fitur nama modul',
                'jenis'      => 'boolean',
                'kategori'   => 'Nama Modul',
            ],
            [
                'judul'      => 'Jumlah Per Halaman',
                'key'        => 'nama_modul_per_halaman',
                'value'      => '10',
                'keterangan' => 'Jumlah data yang ditampilkan per halaman',
                'jenis'      => 'input',
                'kategori'   => 'Nama Modul',
            ],
            // Tambahkan setting lain sesuai kebutuhan
        ]);
    }
}
```

### 4.8 Controller

#### 4.8.1 BackEnd Controller (Admin)

Controller backend meng-extend `AdminModulController` dan memiliki properti standar.

File: `Http/Controllers/BackEnd/ContohController.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

defined('BASEPATH') || exit('No direct script access allowed');

use Illuminate\Support\Facades\View;
use Modules\NamaModul\Models\ContohModel;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class ContohController extends AdminModulController
{
    // =========================================================================
    // PROPERTI WAJIB
    // =========================================================================

    public $moduleName          = 'NamaModul';          // Nama modul (PascalCase)
    public $modul_ini           = 'nama-modul';          // Slug menu parent
    public $sub_modul_ini       = 'data-utama';          // Slug sub menu aktif
    public $kategori_pengaturan = 'Nama Modul';          // Kategori setting
    public $aliasController     = 'nama_modul';          // Alias untuk URL

    // =========================================================================
    // CONSTRUCTOR
    // =========================================================================

    public function __construct()
    {
        parent::__construct();
        isCan('b'); // Cek permission Browse
    }

    // =========================================================================
    // INDEX (LIST + DATATABLES)
    // =========================================================================

    public function index()
    {
        // Handle AJAX request untuk DataTables
        if (request()->ajax()) {
            $filters = [
                'status'  => request()->get('status'),
                'tanggal' => request()->get('tanggal'),
            ];

            return datatables()->of(ContohModel::query()->filters($filters))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('nama_modul.form', $row->id) . '" '
                            . 'class="btn btn-warning btn-sm" title="Ubah Data">'
                            . '<i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('nama_modul.delete', $row->id) . '" '
                            . 'class="btn bg-maroon btn-sm" title="Hapus Data" '
                            . 'data-toggle="modal" data-target="#confirm-delete">'
                            . '<i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return view('namamodul::backend.contoh.index');
    }

    // =========================================================================
    // FORM (CREATE / EDIT)
    // =========================================================================

    public function form($id = null)
    {
        isCan('u');

        $data['action']      = $id ? 'Ubah' : 'Tambah';
        $data['form_action'] = $id
            ? ci_route('nama_modul.update', $id)
            : ci_route('nama_modul.insert');
        $data['contoh']      = $id ? ContohModel::findOrFail($id) : null;

        return view('namamodul::backend.contoh.form', $data);
    }

    // =========================================================================
    // INSERT (CREATE)
    // =========================================================================

    public function insert()
    {
        isCan('u');

        $data = $this->validated(request(), [
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'status'    => 'required|integer|in:0,1',
        ]);

        if (ContohModel::create($data)) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    // =========================================================================
    // UPDATE
    // =========================================================================

    public function update($id = null)
    {
        isCan('u');

        $contoh = ContohModel::findOrFail($id);

        $data = $this->validated(request(), [
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'status'    => 'required|integer|in:0,1',
        ]);

        if ($contoh->update($data)) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    // =========================================================================
    // DELETE
    // =========================================================================

    public function delete($id = null): void
    {
        isCan('h');

        if (ContohModel::destroy($this->request['id_cb'] ?? $id) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    // =========================================================================
    // CETAK & EKSPOR
    // =========================================================================

    public function cetak()
    {
        return view('namamodul::backend.contoh.cetak', [
            'data' => ContohModel::all(),
        ]);
    }

    public function ekspor()
    {
        // Export ke XLSX menggunakan OpenSpout
        $writer = new Writer();
        // ... implementasi ekspor
    }
}
```

#### 4.8.2 FrontEnd Controller (Publik)

Controller frontend meng-extend `WebModulController` untuk halaman publik.

File: `Http/Controllers/FrontEnd/PublikController.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

defined('BASEPATH') || exit('No direct script access allowed');

use Modules\NamaModul\Models\ContohModel;

class PublikController extends WebModulController
{
    public $moduleName = 'NamaModul';

    public function index()
    {
        $data['items'] = ContohModel::where('status', ContohModel::AKTIF)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('namamodul::frontend.pages.index', $data);
    }

    public function show($id)
    {
        $data['item'] = ContohModel::findOrFail($id);

        return view('namamodul::frontend.pages.show', $data);
    }
}
```

#### 4.8.3 API Controller (Opsional)

File: `Http/Controllers/API/ApiController.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

defined('BASEPATH') || exit('No direct script access allowed');

use Modules\NamaModul\Models\ContohModel;

class ApiController extends AdminModulController
{
    public $moduleName = 'NamaModul';

    public function list()
    {
        $data = ContohModel::where('status', ContohModel::AKTIF)->get();

        return json([
            'status' => true,
            'data'   => $data,
        ]);
    }
}
```

### 4.9 Form Request Validation

File: `Http/Requests/ContohRequest.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

namespace Modules\NamaModul\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContohRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama'      => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:500',
            'status'    => 'required|integer|in:0,1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi',
            'nama.max'      => 'Nama maksimal 100 karakter',
        ];
    }
}
```

> **Catatan penting:** OpenSID menggunakan framework hybrid CodeIgniter/Laravel, sehingga **dependency injection via type hint di parameter method controller** (pola Laravel murni seperti `public function insert(ContohRequest $request)`) **tidak berfungsi**. Form Request harus diinstansiasi secara manual di dalam method controller:
>
> ```php
> public function insert()
> {
>     isCan('u');
>     $request = new ContohRequest(); // ← instansiasi manual
>     ContohModel::create($request->validated());
>     redirect_with('success', 'Berhasil Tambah Data');
> }
> ```
>
> Lihat contoh nyata di `Modules/BukuTamu/Http/Controllers/BackEnd/KeperluanController.php`.

### 4.10 Routes

#### 4.10.1 Web Routes

File: `Routes/web.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

// =========================================================================
// FRONTEND (Halaman Publik)
// =========================================================================
Route::group('nama-modul', ['namespace' => 'NamaModul/FrontEnd'], static function (): void {
    Route::get('/', 'PublikController@index')->name('fweb.nama_modul.index');
    Route::get('/show/{id}', 'PublikController@show')->name('fweb.nama_modul.show');
    Route::post('/submit', 'PublikController@submit')->name('fweb.nama_modul.submit');
});

// =========================================================================
// BACKEND (Halaman Admin)
// =========================================================================
Route::group('nama_modul', ['namespace' => 'NamaModul/BackEnd'], static function (): void {
    // CRUD Routes
    Route::get('/', 'ContohController@index')->name('nama_modul.index');
    Route::get('/form/{id?}', 'ContohController@form')->name('nama_modul.form');
    Route::post('/insert', 'ContohController@insert')->name('nama_modul.insert');
    Route::post('/update/{id}', 'ContohController@update')->name('nama_modul.update');
    Route::get('/delete/{id?}', 'ContohController@delete')->name('nama_modul.delete');
    Route::post('/deleteAll', 'ContohController@delete')->name('nama_modul.delete-all');

    // Cetak & Ekspor
    Route::get('/cetak', 'ContohController@cetak')->name('nama_modul.cetak');
    Route::get('/ekspor', 'ContohController@ekspor')->name('nama_modul.ekspor');
});

// Sub-resource (jika ada resource bertingkat)
Route::group('nama_modul_kategori', ['namespace' => 'NamaModul/BackEnd'], static function (): void {
    Route::get('/', 'KategoriController@index')->name('nama_modul_kategori.index');
    Route::get('/form/{id?}', 'KategoriController@form')->name('nama_modul_kategori.form');
    Route::post('/insert', 'KategoriController@insert')->name('nama_modul_kategori.insert');
    Route::post('/update/{id}', 'KategoriController@update')->name('nama_modul_kategori.update');
    Route::get('/delete/{id?}', 'KategoriController@delete')->name('nama_modul_kategori.delete');
    Route::post('/delete', 'KategoriController@delete')->name('nama_modul_kategori.delete-all');
});
```

#### 4.10.2 API Routes (Opsional)

File: `Routes/api.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

Route::group('internal_api/nama_modul', ['namespace' => 'NamaModul/API'], static function (): void {
    Route::get('list', 'ApiController@list')->name('api.nama_modul.list');
    Route::get('detail/{id}', 'ApiController@detail')->name('api.nama_modul.detail');
});
```

**Pola penting pada routing:**

| Pola | Keterangan |
|------|-----------|
| Frontend: `nama-modul` (dash) | URL publik menggunakan kebab-case |
| Backend: `nama_modul` (underscore) | URL admin menggunakan snake_case |
| `['namespace' => 'NamaModul/BackEnd']` | Namespace mengarah ke folder controller |
| `static function (): void` | Arrow function untuk callback group |
| `ci_route('nama_modul.action', $id)` | Helper untuk generate URL di controller/view |

### 4.11 Views (Blade Template)

#### Konvensi Pemanggilan View

Views dipanggil dengan prefix namespace modul (lowercase, tanpa separator):

```php
// Format: namamodul::path.ke.view
return view('namamodul::backend.contoh.index', $data);
return view('namamodul::frontend.pages.index', $data);
```

#### 4.11.1 Index View (DataTables)

File: `Views/backend/contoh/index.blade.php`

```blade
@extends('admin.layouts.index')

@section('title')
    <h1>Data Contoh</h1>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a href="{{ ci_route('nama_modul.form') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                    <i class="fa fa-plus"></i> Tambah
                </a>
            @endif

            @if (can('h'))
                <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('nama_modul.delete-all') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih">
                    <i class="fa fa-trash-o"></i> Hapus
                </a>
            @endif

            <a href="{{ ci_route('nama_modul.cetak') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank">
                <i class="fa fa-print"></i> Cetak
            </a>

            <a href="{{ ci_route('nama_modul.ekspor') }}" class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank">
                <i class="fa fa-download"></i> Ekspor
            </a>
        </div>

        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabel-data">
                    <thead>
                        <tr>
                            <th class="padat"><input type="checkbox" id="checkall"/></th>
                            <th class="padat">No</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th class="padat">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
            {!! form_close() !!}
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var TableData = $('#tabel-data').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ordering: false,
            ajax: {
                url: '{{ ci_route('nama_modul') }}',
                data: function(req) {
                    req.status = $('#status').val();
                }
            },
            columns: [
                { data: 'ceklist', class: 'padat', searchable: false, orderable: false },
                { data: 'DT_RowIndex', class: 'padat', searchable: false, orderable: false },
                { data: 'nama' },
                { data: 'deskripsi' },
                { data: 'status' },
                { data: 'aksi', class: 'aksi', searchable: false, orderable: false },
            ]
        });
    });
</script>
@endpush
```

#### 4.11.2 Form View (Create/Edit)

File: `Views/backend/contoh/form.blade.php`

```blade
@extends('admin.layouts.index')

@section('title')
    <h1>{{ $action }} Data Contoh</h1>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('nama_modul') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left"></i> Kembali
            </a>
        </div>

        {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="nama">Nama</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control input-sm required" name="nama" id="nama"
                        value="{{ $contoh->nama ?? '' }}" placeholder="Masukkan nama" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="deskripsi">Deskripsi</label>
                <div class="col-sm-8">
                    <textarea class="form-control input-sm" name="deskripsi" id="deskripsi"
                        placeholder="Masukkan deskripsi">{{ $contoh->deskripsi ?? '' }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="status">Status</label>
                <div class="col-sm-4">
                    <select class="form-control input-sm required" name="status" id="status">
                        <option value="">-- Pilih Status --</option>
                        <option value="1" {{ ($contoh->status ?? '') == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ ($contoh->status ?? '') == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm">
                <i class="fa fa-times"></i> Batal
            </button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right">
                <i class="fa fa-check"></i> Simpan
            </button>
        </div>
        {!! form_close() !!}
    </div>
@endsection
```

### 4.12 Enums (Opsional)

Gunakan PHP Enums untuk konstanta yang terstruktur.

File: `Enums/ContohStatusEnum.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

namespace Modules\NamaModul\Enums;

enum ContohStatusEnum: int
{
    case AKTIF      = 1;
    case TIDAK_AKTIF = 0;
    case PENDING    = 2;

    /**
     * Daftar label untuk ditampilkan di UI
     */
    public function label(): string
    {
        return match ($this) {
            self::AKTIF       => 'Aktif',
            self::TIDAK_AKTIF => 'Tidak Aktif',
            self::PENDING     => 'Pending',
        };
    }
}
```

### 4.13 Helpers (Opsional)

Jika perlu fungsi utilitas global yang khusus modul.

File: `Helpers/nama_modul_helper.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

if (! function_exists('nama_modul_format_tanggal')) {
    /**
     * Format tanggal khusus modul
     */
    function nama_modul_format_tanggal(string $tanggal): string
    {
        return \Carbon\Carbon::parse($tanggal)->format('d/m/Y');
    }
}
```

> **Penting:** Helper harus didaftarkan di Service Provider melalui `registerHelpers()`.

### 4.14 Services & Repositories (Opsional)

Gunakan **Service** untuk business logic yang kompleks dan **Repository** untuk abstraksi data access.

#### Service

File: `Services/ContohService.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

namespace Modules\NamaModul\Services;

use Modules\NamaModul\Models\ContohModel;

class ContohService
{
    public function prosesDataKompleks(array $data): bool
    {
        // Business logic yang kompleks
        return true;
    }
}
```

#### Repository

File: `Repositories/ContohRepository.php`

```php
<?php

/*
 * [Header Lisensi GPL V3]
 */

namespace Modules\NamaModul\Repositories;

use Modules\NamaModul\Models\ContohModel;

class ContohRepository
{
    public function getByStatus(int $status)
    {
        return ContohModel::where('status', $status)->get();
    }
}
```

---

## 5. Sistem Multi-Tenant (ConfigId)

OpenSID mendukung multi-tenant (multi-desa). Setiap data difilter berdasarkan `config_id`.

### Cara Kerja

1. **Trait `ConfigId`** secara otomatis menambahkan filter `WHERE config_id = ?` pada setiap query
2. `config_id` diisi otomatis saat create data
3. Fungsi `identitas('id')` mengembalikan config_id desa aktif

### Pola Penggunaan

```php
// Data otomatis difilter per desa (config_id)
$data = ContohModel::all(); // Hanya data desa saat ini

// Bypass filter config_id (hanya untuk admin global)
$allData = ContohModel::withoutConfigId($configId)->get();

// Di migration, kolom config_id ditambahkan via macro:
$table->configId();
```

### Aturan Penting

- **Semua model** yang menyimpan data per desa WAJIB menggunakan trait `ConfigId`
- **Semua tabel** yang terkait desa WAJIB punya kolom `config_id` (via `$table->configId()`)
- **Unique constraint** harus menyertakan `config_id`: `$table->unique(['config_id', 'kode'])`

---

## 6. Sistem Hak Akses & Permission

### Permission Check

```php
// Di constructor controller
isCan('b');  // Browse - wajib ada akses lihat

// Di method spesifik
isCan('u');  // Update - bisa tambah/ubah
isCan('h');  // Hapus/Delete
isCan('c');  // Create (jarang digunakan, biasanya pakai 'u')
```

### Permission di View

```blade
@if (can('u'))
    {{-- Tombol edit/tambah --}}
@endif

@if (can('h'))
    {{-- Tombol hapus --}}
@endif
```

### Level Akses

| Level | Keterangan |
|-------|-----------|
| `1` | Super Admin |
| `2` | Admin |
| `3` | Editor |
| `4` | Contributor |
| `5` | Operator |

---

## 7. Konvensi Penamaan

### PHP

| Elemen | Konvensi | Contoh |
|--------|---------|--------|
| Nama Modul (folder) | PascalCase | `BukuTamu`, `Lapak`, `DTSEN` |
| Namespace | `Modules\NamaModul\...` | `Modules\BukuTamu\Models\TamuModel` |
| Class | PascalCase | `AnalisisMaster`, `TamuModel` |
| Method | camelCase | `index()`, `datatables()`, `formChild()` |
| Property | camelCase | `$moduleName`, `$modul_ini` |
| Konstanta | UPPER_CASE | `const AKTIF = 1` |
| Tabel database | snake_case | `buku_tamu`, `analisis_master` |
| Kolom database | snake_case | `config_id`, `created_at`, `jenis_kelamin` |
| Route name | snake_case + dot | `buku_tamu.index`, `fweb.nama_modul.show` |
| Slug (menu) | kebab-case | `buku-tamu`, `data-kepuasan` |
| URL backend | snake_case | `buku_tamu`, `buku_keperluan` |
| URL frontend | kebab-case | `buku-tamu`, `nama-modul` |
| View namespace | lowercase (tanpa separator) | `bukutamu::`, `namamodul::` |
| File migration | snake_case dengan timestamp | `2025_12_21_080511_create_buku_tamu_table.php` |
| File helper | snake_case | `nama_modul_helper.php` |
| Setting key | snake_case | `buku_tamu_kamera` |
| Variabel lokal | camelCase | `$userId`, `$totalHarga` |
| File view (partial) | kebab-case diawali underscore | `_tabel-pegawai.blade.php` |
| Resource Controller | StudlyCase + sufiks Controller | `UserController`, `BukuTamuController` |
| Single Action Controller | StudlyCase kata kerja + sufiks Controller | `ClearCacheController`, `DownloadLaporanHarianController` |
| Parameter route | camelCase | `{lowonganKerja}`, `{namaModul}` |
| File konfigurasi | kebab-case | `config/dynamic-form.php` |
| Key konfigurasi | snake_case | `allowed_types`, `per_halaman` |
| Artisan command | kebab-case | `php artisan generate-laporan` |

### Blade/View

| Elemen | Konvensi | Contoh |
|--------|---------|--------|
| Folder view | snake_case/kebab-case | `backend/tamu/`, `frontend/pages/` |
| File view | snake_case | `index.blade.php`, `form.blade.php` |
| Section name | kebab-case | `@section('content')` |

---

## 8. Gaya Penulisan Kode

### 8.1 Standar Umum

- **PSR-12** sebagai standar coding style
- **PHP-CS-Fixer** untuk auto-fix format
- **Rector** untuk modernisasi kode
- **Prettier** untuk format Blade template
- **[Pint](https://github.com/laravel/pint)** — auto-fix coding style Laravel (PHP ≥ 8.1)
- **[PHPStan/Larastan](https://phpstan.org/)** — static analysis untuk mendeteksi error sebelum runtime
- **[SonarLint](https://www.sonarlint.org/)** — analisis cognitive complexity dan code smell

### 8.2 Header Lisensi

Setiap file PHP **WAJIB** memiliki header lisensi GPL V3:

```php
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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */
```

### 8.3 PHPDoc Comments

```php
/**
 * The table associated with the model.
 *
 * @var string
 */
protected $table = 'nama_tabel';

/**
 * Getter untuk url_foto
 *
 * @return string
 */
public function getUrlFotoAttribute(): string
```

### 8.4 Arrow Function & Closure

```php
// Arrow function untuk callback sederhana
->addColumn('aksi', static fn ($row): string => '<a href="...">Aksi</a>')

// Static closure untuk callback route group
Route::group('path', static function (): void {
    // routes
});
```

### 8.5 Teks UI dalam Bahasa Indonesia

```php
// Variabel & method → English
public function index() { ... }
protected $table = 'buku_tamu';

// Teks yang ditampilkan ke user → Bahasa Indonesia
redirect_with('success', 'Berhasil Tambah Data');
redirect_with('error', 'Gagal Hapus Data');
$data['action'] = 'Ubah';
```

### 8.6 Import & Use Statement

```php
// Urutkan: PHP built-in → Framework → App → Modules
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Enums\StatusEnum;
use App\Models\BaseModel;
use App\Traits\ConfigId;
use Modules\NamaModul\Models\ContohModel;
```

### 8.7 Controller di Luar Namespace

Controller backend dan frontend di OpenSID menggunakan pola hybrid (CodeIgniter style):

```php
// Controller TIDAK menggunakan namespace
// Diawali dengan:
defined('BASEPATH') || exit('No direct script access allowed');

// Require manual jika perlu base class:
require_once FCPATH . 'Modules/NamaModul/Http/Controllers/BackEnd/BaseController.php';

// Tapi Model, Request, dll TETAP pakai namespace
use Modules\NamaModul\Models\ContohModel;
use Modules\NamaModul\Http\Requests\ContohRequest;
```

### 8.8 Aturan Controller

Controller hanya bertindak sebagai **pengatur alur** — bukan tempat meletakkan business logic.

**Yang boleh dilakukan controller:**
- Memanggil Model atau Service untuk mendapatkan data
- Melakukan authorization (`isCan`, `can`)
- Melakukan redirect
- Mengeset flash message
- Aksi lain yang berhubungan dengan HTTP

**Yang TIDAK boleh dilakukan controller:**
- Mengandung business logic berat
- Melakukan validasi form manual — gunakan **Form Request** sebagai gantinya

```php
// ❌ SALAH — validasi manual dan logic berat di controller
public function insert()
{
    $this->validate->set_rules('nama', 'Nama', 'required|min_length[3]');
    if ($this->validate->run() === false) {
        return redirect_with('error', 'Validasi gagal');
    }
    // puluhan baris business logic...
}

// ✅ BENAR — instansiasi Form Request secara manual, delegasikan logic ke Service
public function insert()
{
    $request = new ContohRequest();
    $this->contohService->simpan($request->validated());

    return redirect_with('success', 'Data berhasil disimpan');
}
```

Wajib menerapkan prinsip **Resource Controller**. Jika ada aksi di luar CRUD standar, buat **Single Action Controller** terpisah.

### 8.9 Aturan Route (7 Kata Ajaib)

Semua route CRUD wajib mengikuti konvensi **tujuh kata ajaib**:

| Method HTTP | Kata Ajaib | Aksi |
|-------------|-----------|------|
| GET | `index` | Daftar resource |
| GET | `create` / `form` | Form tambah |
| POST | `store` / `insert` | Simpan data baru |
| GET | `show` | Detail resource |
| GET | `edit` / `form` | Form edit |
| PUT/PATCH | `update` | Perbarui data |
| DELETE | `destroy` / `delete` | Hapus data |

> **Catatan:** OpenSID menggunakan `insert`, `form`, dan `delete` sebagai alias yang lazim di codebase yang ada. Untuk modul baru, ikuti konvensi yang sudah digunakan di modul referensi.

Jika diperlukan aksi di luar 7 kata ajaib (misalnya: ekspor laporan, kirim notifikasi), buat **Single Action Controller** baru alih-alih menambah method di resource controller yang sudah ada:

```php
// ❌ SALAH — tambah method non-CRUD di resource controller
Route::get('/buku_tamu/export', 'BackEnd\BukuTamuController@export');

// ✅ BENAR — buat Single Action Controller baru
Route::get('/buku_tamu/export', 'BackEnd\ExportBukuTamuController');
```

---

## 9. Modul yang Memerlukan Lisensi Premium

### 9.1 Cara Kerja Pengecekan Lisensi

OpenSID memiliki sistem proteksi lisensi berbasis langganan. Setiap modul di `Modules/` yang controller-nya extend `AdminModulController` akan **otomatis** dicek lisensinya saat konstruktor dijalankan.

Alur pengecekan di `AdminModulController`:

```
__construct()
  └─ $this->activate()                      ← dari trait ModulTrait
        └─ isModulePremiumActive()
              ├─ in_array($moduleName, MODUL_BAWAAN) → true (bebas lisensi)
              ├─ ENVIRONMENT === 'development' → true (lokal/dev bypass)
              ├─ demo_mode aktif → true (demo bypass)
              └─ in_array($moduleName, getLayananModul()) → cek ke API langganan
```

Jika pengecekan **gagal** (modul tidak dilisensikan), user di-redirect ke halaman plugin dengan pesan:

> *"Modul X belum bisa digunakan karena belum diaktivasi atau langganan Premium telah berakhir. Silakan aktifkan atau perpanjang langganan untuk menggunakan fitur ini."*

### 9.2 Dua Kategori Modul

| Kategori | Penjelasan | Contoh |
|----------|-----------|--------|
| **`MODUL_BAWAAN`** | Terdaftar di konstanta `MODUL_BAWAAN`, selalu bisa dijalankan tanpa cek lisensi | Anjungan, Analisis, BukuTamu, Kehadiran, Pelanggan, Lapak, DTSEN |
| **Modul Berbayar** | Tidak ada di `MODUL_BAWAAN`, **wajib** memiliki lisensi aktif dari server langganan | Modul pihak ketiga / modul berbayar baru |

Konstanta `MODUL_BAWAAN` didefinisikan di `donjo-app/helpers/core_helper.php`:

```php
define('MODUL_BAWAAN', [
    'Anjungan',
    'Analisis',
    'BukuTamu',
    'Kehadiran',
    'Pelanggan',
    'Lapak',
    'DTSEN',
]);
```

### 9.3 Modul Baru sebagai Modul Berbayar (Tidak di MODUL_BAWAAN)

Jika modul baru **tidak didaftarkan** ke `MODUL_BAWAAN`, maka secara otomatis akan dilindungi oleh pengecekan lisensi. Tidak ada kode tambahan yang diperlukan — cukup pastikan:

1. Controller meng-extend `AdminModulController`
2. Properti `$moduleName` sesuai dengan nama di server langganan
3. Nama `$moduleName` **tidak ada** di konstanta `MODUL_BAWAAN`

```php
// Controller modul berbayar — TIDAK perlu kode lisensi manual
class ContohController extends AdminModulController
{
    public $moduleName = 'NamaModulBerbayar'; // Nama ini dicek ke API langganan

    public function __construct()
    {
        parent::__construct(); // activate() dipanggil di sini secara otomatis
        isCan('b');
    }
}
```

`AdminModulController::__construct()` sudah memanggil `$this->activate()` secara otomatis, sehingga **tidak perlu** memanggil `activate()` secara manual.

### 9.4 Mendaftarkan Modul Baru ke MODUL_BAWAAN (Gratis/Bundled)

Jika modul harus **bebas lisensi** (bundled bersama instalasi premium), tambahkan nama modul ke konstanta `MODUL_BAWAAN` di `donjo-app/helpers/core_helper.php`:

```php
define('MODUL_BAWAAN', [
    'Anjungan',
    'Analisis',
    'BukuTamu',
    'Kehadiran',
    'Pelanggan',
    'Lapak',
    'DTSEN',
    'NamaModulBaru', // ← tambahkan di sini
]);
```

> **Catatan:** Nama harus persis sama (case-sensitive) dengan nilai `$moduleName` di controller dan field `name` di `module.json`.

### 9.5 Cara Kerja Validasi Lisensi (`getLayananModul`)

Untuk modul berbayar, `getLayananModul()` membaca cache `status_langganan` dari API `layanan.opendesa.id`:

```php
protected function getLayananModul(): array
{
    return cache()->rememberForever('modul_aktif', static function () {
        $cache = app('ci')->cache->file->get('status_langganan');

        return collect($cache->body->pemesanan)
            ->filter(static fn ($data): bool => $data->status_pemesanan === 'aktif')
            ->map(
                static fn ($data) => collect($data->layanan)
                    ->filter(static fn ($layanan) => $layanan->nama_kategori === 'Modul')
                    ->map(static fn ($layanan) => trim(str_replace('Modul', '', $layanan->nama)))
                    ->toArray()
            )
            ->flatten()
            ->toArray();
    });
}
```

Cache `status_langganan` diperbarui secara periodik oleh `PelangganService::perbaruiLangganan()` yang dipanggil di setiap request admin.

### 9.6 Environment Bypass (Development & Demo)

Pengecekan lisensi **otomatis dilewati** pada kondisi berikut:

| Kondisi | Keterangan |
|---------|-----------|
| `ENVIRONMENT === 'development'` | Mode development lokal |
| `config_item('demo_mode')` aktif **dan** domain terdaftar di `WEBSITE_DEMO` | Mode demo pada domain demo yang diizinkan |

```php
protected function isModulePremiumActive(): bool
{
    // Modul bawaan tidak perlu lisensi
    if (in_array($this->moduleName, MODUL_BAWAAN)) {
        return true;
    }

    // Development & demo bypass
    if (ENVIRONMENT === 'development' || (config_item('demo_mode') && in_array(get_domain(APP_URL), WEBSITE_DEMO))) {
        return true;
    }

    // Cek ke API langganan
    return in_array($this->moduleName, $this->getLayananModul());
}
```

### 9.7 Ringkasan: Kapan Modul Memerlukan Lisensi?

```
Modul di Modules/
├── Ada di MODUL_BAWAAN? 
│   └── YA  → Bebas lisensi (selalu bisa diakses)
│   └── TIDAK ↓
│
├── Development / Demo mode?
│   └── YA  → Bypass (selalu bisa diakses)
│   └── TIDAK ↓
│
└── Nama modul ada di list langganan aktif (API)?
    └── YA  → Bisa diakses
    └── TIDAK → Redirect ke halaman plugin + pesan warning
```

---

## 10. Modul yang Sudah Ada (Referensi)

> **Catatan:** Semua modul di tabel ini sudah terdaftar di `MODUL_BAWAAN`, sehingga bebas diakses tanpa lisensi terpisah.

Gunakan modul yang sudah ada sebagai referensi saat membuat modul baru:

| Modul | Versi | Kompleksitas | Cocok untuk Referensi |
|-------|-------|-------------|----------------------|
| **BukuTamu** | 1.0.0 | Sedang | Modul CRUD standar dengan frontend & backend, request validation |
| **Kehadiran** | 1.0.1 | Tinggi | Modul dengan banyak sub-resource, enums, helpers |
| **Lapak** | 2412.0.0 | Tinggi | Modul dengan API, repository pattern, transformers |
| **Analisis** | 2410.0.0 | Sangat Tinggi | Modul dengan nested routes, libraries, import/export, 10 controller |
| **DTSEN** | 1.0.0 | Sedang | Modul modern dengan services, helpers, PHP 8 features |
| **Anjungan** | 2410.0.0 | Rendah | Modul sederhana (kiosk config) |
| **FolderDesa** | 2601.0.0 | Rendah | Modul keamanan (security scan) |
| **Pelanggan** | 2410.0.0 | Rendah | Modul minimal (customer data) |

### Rekomendasi Modul Referensi

- **Modul sederhana (CRUD standar):** Gunakan **BukuTamu** sebagai template
- **Modul dengan API:** Gunakan **Lapak** sebagai template
- **Modul dengan business logic kompleks:** Gunakan **DTSEN** sebagai template
- **Modul dengan banyak sub-resource:** Gunakan **Kehadiran** sebagai template

---

## 11. Checklist Pembuatan Modul

### Persiapan

- [ ] Tentukan nama modul (PascalCase)
- [ ] Identifikasi tabel database yang dibutuhkan
- [ ] Rancang menu sidebar (parent + sub menu)
- [ ] Tentukan setting/pengaturan yang diperlukan
- [ ] Tentukan level akses yang diperlukan

### File Wajib

- [ ] `module.json` — Metadata modul
- [ ] `composer.json` — Package metadata
- [ ] `Config/config.php` — Konfigurasi dasar
- [ ] `Providers/NamaModulServiceProvider.php` — Service provider
- [ ] `Routes/web.php` — Definisi route
- [ ] Minimal 1 Model di `Models/`
- [ ] Minimal 1 Controller di `Http/Controllers/BackEnd/`
- [ ] Minimal 1 View di `Views/backend/`

### Database

- [ ] Migration `create_akses_modul.php` (registrasi menu & setting)
- [ ] Migration tabel database
- [ ] `Database/Seeders/NamaModulSeeder.php`
- [ ] `Database/Seeders/ModulSeeder.php`
- [ ] `Database/Seeders/SettingSeeder.php`

### Model

- [ ] Extend `App\Models\BaseModel`
- [ ] Gunakan trait `ConfigId`
- [ ] Deklarasikan `$table` eksplisit
- [ ] Definisikan mass assignment protection (`$guarded = []` atau `$fillable`)
- [ ] Definisikan relationships jika ada
- [ ] Definisikan scopes untuk filtering

### Controller

- [ ] Set properti: `$moduleName`, `$modul_ini`, `$sub_modul_ini`, `$kategori_pengaturan`, `$aliasController`
- [ ] Panggil `isCan('b')` di constructor
- [ ] Implementasi method: `index()`, `form()`, `insert()`, `update()`, `delete()`
- [ ] Implementasi DataTables di `index()` untuk AJAX
- [ ] Validasi input di `insert()` dan `update()`

### View

- [ ] `index.blade.php` — DataTables list
- [ ] `form.blade.php` — Create/edit form
- [ ] Gunakan `@extends('admin.layouts.index')`
- [ ] Gunakan `@include('admin.layouts.components.notifikasi')`
- [ ] Gunakan `@include('admin.layouts.components.konfirmasi_hapus')`

### Quality

- [ ] Header lisensi GPL V3 di semua file PHP
- [ ] Mengikuti PSR-12 coding standard
- [ ] Teks UI dalam Bahasa Indonesia
- [ ] Permission check (`isCan`, `can`) di semua action
- [ ] Multi-tenant support (`ConfigId`) di semua model
- [ ] Migration idempoten (`Schema::hasTable()`, `Schema::hasColumn()`)

---

## 12. Git Workflow

### 12.1 Strategi Branch

| Branch | Kegunaan |
|--------|----------|
| `main` | Branch untuk rilis stabil |
| `rilis-dev` | Branch untuk pengembangan fitur baru |
| `bug-fix` | Branch untuk perbaikan bug |

> Selalu buat branch baru dari `rilis-dev` untuk fitur atau perbaikan. Jangan langsung commit ke `rilis-dev`.

### 12.2 Format Pesan Commit

Format standar pesan commit:

```
[JENIS]: Subject

Deskripsi (opsional)
```

| Jenis | Kegunaan |
|-------|----------|
| `[feat]` | Fitur baru |
| `[fix]` | Perbaikan bug |
| `[docs]` | Perubahan dokumentasi |
| `[test]` | Penambahan atau perbaikan testing |
| `[style]` | Perubahan gaya kode (tanpa mempengaruhi logika) |
| `[refactor]` | Refactor kode (tanpa menambah fitur atau memperbaiki bug) |
| `[chore]` | Perubahan minor (misalnya: update dependensi) |
| `[ci_skip]` | Skip GitHub Actions (misalnya: catatan rilis) |

**Contoh:**

```bash
git commit -m "feat: tambah modul buku tamu"
git commit -m "fix: perbaikan query filter di lapak"
git commit -m "docs: perbarui panduan pembuatan modul"
```

### 12.3 Konvensi Penamaan Branch

| Template | Kegunaan | Contoh |
|----------|----------|--------|
| `fitur/<nama-fitur>` | Branch untuk fitur baru | `fitur/modul-buku-tamu` |
| `perbaikan/<deskripsi-bug>` | Branch untuk perbaikan bug | `perbaikan/bug-filter-lapak` |
| `rilis/<versi>` | Branch untuk persiapan rilis | `rilis/2601.0.0` |
| `perbaikan-rilis/<deskripsi>` | Branch untuk perbaikan mendesak (hotfix) | `perbaikan-rilis/xss-controller` |

---

> **Catatan:** Dokumen ini dibuat berdasarkan analisis mendalam terhadap 8 modul yang sudah ada di sistem OpenSID Premium. Selalu merujuk ke kode modul yang sudah ada untuk contoh implementasi terkini.

> **Lisensi:** Modul yang tidak terdaftar di `MODUL_BAWAAN` secara otomatis memerlukan lisensi premium aktif. Lihat [Seksi 9](#9-modul-yang-memerlukan-lisensi-premium) untuk detail penuh.
