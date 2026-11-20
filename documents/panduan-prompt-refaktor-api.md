# Prompt Refaktor: Controller API CI3 → Native Laravel (Template Umum)

## Cara Pakai

ISI `{{...}}` sesuai controller target, berikan ke agent. Referensi implementasi paling mutakhir: refaktor **Analisis** (branch `teknis-refaktor-api-analisis`, merge ke `rilis-dev`) — semua pola di bawah diambil dari sana.

## Konteks

Controller `{{NAMA_CONTROLLER}}` (`{{DESKRIPSI_SINGKAT_FUNGSI}}`) masih hybrid CI3. File terkait:

- `donjo-app/controllers/internal_api/{{NAMA_CONTROLLER}}.php` — extends `Api_Controller` (`donjo-app/core/Api_Controller.php`), output via `json($this->fractal($data, $transformer, $resourceName))` (JsonApiSerializer). **Akan dihapus.**
- `app/Repositories/{{NAMA_CONTROLLER}}Repository.php` — sudah native Laravel, pertahankan & perbaiki.
- `donjo-app/Routes/api.php` — grup `Route::group('internal_api', ['namespace' => 'internal_api'], ...)` berisi sub-grup modul. **Sub-grup modul akan dihapus.**
- `app/Http/Transformers/*{{NAMA}}*Transformer.php` — lama, cuma `return $data->toArray()` (attributes = kolom mentah hasil SELECT). **Akan dihapus, diganti API Resource.**

### Stack (wajib)

PHP `^8.4` · Laravel `^13.8` (punya `JsonApiResource` bawaan) · `spatie/laravel-query-builder` `^7.3` · `spatie/laravel-json-api-paginate` `^2.0` (`->jsonPaginate()` baca `page[size]`/`page[number]`) · larastan `^3.0` (PHPStan level 5, `phpstan.neon.dist`).

Helper native = `app/Helpers/*` (`identitas()` di `core_helper.php` — BOLEH dipakai). `application/helpers/*` = CI3, terlarang.

## Kontrak Lama vs Baru (verifikasi kompatibilitas!)

Output lama (Fractal JsonApiSerializer): `{"meta": {...}, "data": [{"type": "...", "id": "...", "attributes": {...}}]}` — `attributes` = kolom mentah SELECT repository.

```bash
# Bandingkan perilaku lama sebelum menulis kode:
git show rilis-dev:donjo-app/controllers/internal_api/{{NAMA_CONTROLLER}}.php
git show rilis-dev:app/Repositories/{{NAMA_CONTROLLER}}Repository.php
git show rilis-dev:app/Http/Transformers/{{...}}Transformer.php
```

Wajib: **key `attributes` baru = key lama** (jangan ada key hilang/berubah). Resource baru menulis `attributes` eksplisit (bukan `toArray()`), jadi selisih key langsung terlihat.

## Tujuan Akhir

Semua endpoint `{{NAMA_ROUTE_GROUP}}` 100% Laravel native, tanpa CI3, nama route lama tetap tersedia.

## Batasan & Requirement Wajib

1. No `extends Api_Controller`, `fractal()`, `json()`, `set_session()`, `config_item()`, `ci_route()`, `show_error()`, dll.
2. Filter/sort/pagination via `spatie/laravel-query-builder`.
3. Resource via `JsonApiResource` (`toType()`, `toId()`, `toAttributes()`), bukan `toArray()` datar.
4. Namespace: `App\Http\Controllers\Api\Internal\{{NAMA_CONTROLLER}}Controller`.
5. Route baru di `routes/internal-api.php` + daftarkan di `bootstrap/app.php` (lihat bawah).
6. **Nama route lama wajib dipertahankan** — gali dari `donjo-app/Routes/api.php`:
   ```php
   // contoh blok lama yang akan dihapus:
   Route::group('analisis', static function (): void {
       Route::get('master', 'Analisis@master')->name('api.analisis.master');
       Route::match(['GET', 'POST'], 'indikator', 'Analisis@indikator')->name('api.analisis.indikator');
   });
   ```
   Tema lama memanggil via `route()` dan `GET`/`POST` — pertahankan metode (`Route::match(['GET','POST'], ...)` untuk endpoint yang dulu POST).
7. Hapus CI3 lama hanya setelah test hijau & konfirmasi.

### Verifikasi konsumen endpoint (sebelum & sesudah)

Cari pemakai endpoint, cross-check kontrak di `storage/app/public/OpenSID API Tema.postman_collection.json` (folder modul):
```bash
grep -rn "api.{{nama_modul}}\|{{nama_modul}}/" resources/ donjo-app/views/ --include="*.js" --include="*.php"
```
Konsumen tema bisa di luar repo (tema premium) — jaga kompatibilitas (GET+POST, nama route, legacy pagination, key attributes).

## Arsitektur Target

```
app/Http/Controllers/Api/Internal/{{NAMA_CONTROLLER}}Controller.php
app/Http/Requests/{{NAMA_MODUL}}/{{...}}Request.php
app/Http/Resources/{{NAMA_MODUL}}/{{...}}Resource.php
app/Repositories/{{NAMA_CONTROLLER}}Repository.php
app/database/factories/Modules/{{NAMA_MODUL}}/Models/*.php
routes/internal-api.php
```

Controller tipis: constructor DI repository → panggil method → bungkus Resource (tanpa `extends Controller`).

Contoh resource (identik `AnalisisMasterResource`):

```php
class AnalisisMasterResource extends JsonApiResource
{
    public function toId(Request $request): string { return (string) $this->resource->id; }
    public function toType(Request $request): string { return 'analisis-master'; }
    public function toAttributes(Request $request): array
    {
        return [
            'id' => (int) $this->resource->id,
            'master' => $this->resource->master,
            'subjek_tipe' => (int) $this->resource->subjek_tipe,
            // dst — akses properti = alias SELECT repository
        ];
    }
}
```

`ConfigId` trait: global scope `ConfigIdScope` (`config_id = identitas('id')`) + auto-set saat `creating` (`ConfigIdObserver`).

## Registrasi Route (bootstrap/app.php)

`routes/internal-api.php` tidak dimuat otomatis — daftarkan via callback `then`:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
    then: function () {
        Route::middleware('api')->prefix('internal_api')
            ->group(base_path('routes/internal-api.php'));
    },
)
```

Isi `routes/internal-api.php` (import `Illuminate\Support\Facades\Route`):

```php
Route::prefix('{{nama_modul}}')->group(function () {
    Route::get('master', [\App\Http\Controllers\Api\Internal\{{NAMA_CONTROLLER}}Controller::class, 'master'])->name('api.{{nama_modul}}.master');
    Route::match(['GET', 'POST'], 'indikator', [\App\Http\Controllers\Api\Internal\{{NAMA_CONTROLLER}}Controller::class, 'indikator'])->name('api.{{nama_modul}}.indikator');
});
```

## Repository Query Patterns

### 1. Exact filter ber-join — WAJIB map kolom tabel asli

```php
AllowedFilter::exact('id', 'm.id'),                   // bukan id tabel join / ambiguous
AllowedFilter::exact('id_master'),                    // kolom tabel utama → aman tanpa mapping
AllowedFilter::exact('tahun', 'p.tahun_pelaksanaan'), // BUKAN alias SELECT 'tahun'
```

> Bug nyata yang pernah terjadi: `filter[tahun]` & `filter[id]` unmapped → `Unknown column`/`ambiguous column` (jointable). Fixed + diuji feature test.

### 2. Search — alias kolom tidak tersedia di WHERE

```php
AllowedFilter::callback('search', static function ($query, $value): void {
    $query->where(static function ($subQuery) use ($value): void {
        $subQuery->where('m.nama', 'like', "%{$value}%")
            ->orWhere('p.tahun_pelaksanaan', 'like', "%{$value}%");
    });
});
// SALAH: ->where('master', 'like', ...) — 'master' adalah alias SELECT, Unknown column
```

### 3. Sort — map ke kolom asli

```php
->allowedSorts(...['master', AllowedSort::field('tahun', 'p.tahun_pelaksanaan')])
// use Spatie\QueryBuilder\AllowedSort;
```

### 4. `DB::raw()` — parameter binding wajib (anti SQL injection)

```php
AllowedFilter::callback('id_periode', static function ($query, $value): void {
    $query->addSelect([
        DB::raw('(select count(...) from analisis_respon where ... id_periode = ?) as jml'),
    ]);
    $query->addBinding($value, 'select');   // JANGAN interpolasi $value ke string SQL
});
```

### 5. Select + join + scoping

```php
->select(['m.id', 'm.nama AS master', 'p.tahun_pelaksanaan AS tahun', ...])
->distinct()
->leftJoin('analisis_master as m', 'analisis_indikator.id_master', '=', 'm.id')
->where('analisis_indikator.is_publik', 1)   // jangan hilang saat migrasi
->where('p.aktif', 1)
->where('analisis_indikator.config_id', identitas('id'))  // eksplisit di query join
->jsonPaginate();   // endpoint paginated
// non-paginated: ->get() — tanpa links/meta (contoh: analisisMaster)
```

## Pagination — Dual Format (traits/Concerns/WithPaginationFormat.php)

Pakai trait `WithPaginationFormat` **hanya untuk endpoint paginated** (override `paginationInformation()` → anonymous collection). Output:

```json
{
  "data": [...],
  "links": { "first": "...", "last": "...", "prev": null, "next": null },
  "meta": {
    "current_page": 1, "from": 1, "last_page": 1, "per_page": 50, "to": 2, "total": 2,
    "pagination": { "total": 2, "count": 2, "per_page": 50, "current_page": 1, "total_pages": 1 },
    "deprecated": { "pagination": "Use meta.current_page, meta.last_page, meta.per_page, meta.total, and links.first/last/prev/next instead." }
  }
}
```

Resource non-paginated: `JsonApiResource` langsung (tanpa trait).

## FormRequest & Validasi

Sesuai `app/Http/Requests/Analisis/AnalisisRequest.php`:

```php
public function rules(): array
{
    return [
        'filter'              => 'nullable|array',
        'filter.id'           => 'nullable|integer',
        'filter.id_master'    => 'nullable|integer',
        'filter.tahun'        => 'nullable|string',
        'filter.id_periode'   => 'nullable|integer',
        'filter.id_indikator' => 'nullable|integer',
        'filter.search'       => 'nullable|string',
        'filter.subjek_tipe'  => 'nullable|integer|in:1,2,3,4',
        'sort'                => 'nullable|string',
        'page'                => 'nullable|array',
        'page.size'           => 'nullable|integer|min:1|max:100',
        'page.number'         => 'nullable|integer|min:1',
        'per_page'            => 'nullable|integer|min:1|max:100',
        'fields'              => 'nullable|array', // JSON:API sparse fieldsets
    ];
}
```

Validasi = lapisan keamanan kedua: payload `1' OR '1'='1` di parameter integer → `422` sebelum query.

## Testing

### Setup (sekali saja, .env.testing di-gitignore)

```bash
cp .env.testing.example .env.testing   # isi: DB_USERNAME, DB_PASSWORD sesuai DB lokal
```

Jalankan test dengan cara apa pun yang tersedia — semuanya setara (`.env.testing` dimuat otomatis saat `APP_ENV=testing` oleh phpunit.xml.dist):

```bash
./vendor/bin/sail artisan test --filter={{NAMA_MODUL}}   # pakai Docker/Sail (container bin-laravel.test-1, DB bin-mariadb-1: user opensid/password)
php artisan test --filter={{NAMA_MODUL}}                 # mesin dengan PHP+DB lokal
./vendor/bin/phpunit --filter={{NAMA_MODUL}}             # tanpa artisan wrapper
```

### Factory
Path `app/database/factories/Modules/{{NAMA_MODUL}}/Models/*.php`, namespace `Database\Factories\Modules\{{NAMA_MODUL}}\Models`, `$model` eksplisit. Model pakai `use HasFactory;`. `config_id` otomatis (observer).

### Feature (`tests/Feature/Api/{{NAMA_MODUL}}/*Test.php` — extends `Tests\BaseTestCase`, trait `Tests\Traits\RefreshDatabase`)
- Panggil via `route('api.{{nama_modul}}.{{aksi}}', [...])` — sekaligus memverifikasi nama route lama.
- Wajib diuji: happy path (struktur `data[].{type,id,attributes}`), **setiap filter**, search, sorting, pagination, empty result, metode POST (bila `match`), `meta.deprecated.pagination` (array, bukan boolean), links JSON:API.
- SQL injection: `filter[id_periode] = "1' OR '1'='1"` → `assertStatus(422)`.
- Cleanup seed schema: `Model::where('id', '>', 0)->delete()` sebelum test pagination/sorting.
- Tes filter `id` pakai id entitas benar (mis. id master, bukan id tabel join) + assert `data.0.id == (string) $id`.
- Sparse fieldsets: TIDAK diuji (leftJoin+select aliased tidak kompatibel `fields[]`).

### Unit
- Repository (`tests/Unit/Repositories/`): panggil method langsung setelah `request()->merge(['filter' => [...], 'page' => [...]])`; buktikan scoping `config_id` (data config lain tidak bocor); binding aman dari payload injeksi.
- Resource (`tests/Unit/Resources/{{NAMA_MODUL}}/`): `$resource->resolve($request)` → verifikasi `type`, `id` (string), `attributes`. Attributes diakses sebagai `stdClass`.

## Workflow

1. Branch: `teknis-refaktor-api-{{nama_modul}}` dari `rilis-dev`.
2. Commit: `teknis: refaktor internal api {{nama_modul}}`.
3. Setelah hijau: update `storage/app/public/OpenSID API Tema.postman_collection.json` (folder modul: deskripsi, query params, contoh respons JSON:API) + buat PR description (template di `docs/pr-konversi-api.md`).
4. Target PR: `rilis-dev`.

## Definisi Selesai
- [ ] No CI3 references (`donjo-app/controllers/internal_api/{{NAMA}}`, sub-grup di `donjo-app/Routes/api.php`, transformer lama dihapus).
- [ ] Route di `routes/internal-api.php` + `bootstrap/app.php`, nama route lama & metode (GET/POST) tersedia.
- [ ] Key `attributes` identik dengan output lama; `exact` filter/search/sort tidak memakai alias SELECT.
- [ ] Tidak ada interpolasi input di `DB::raw()` (wajib binding); scoping `config_id`/`is_publik`/`aktif` tidak hilang.
- [ ] Feature + Unit test hijau: `php artisan test --filter={{NAMA_MODUL}}` (atau `./vendor/bin/phpunit`, atau `./vendor/bin/sail artisan test` bila pakai Sail).
- [ ] PHPStan lulus tanpa error baru: `./vendor/bin/phpstan analyze` (level 5).
- [ ] Postman collection & PR description diperbarui; commit sesuai konvensi `teknis: refaktor internal api ...`.
- [ ] PSR-12, tanpa komentar generik; error message via `__('notification.xxx')` bila ada kasus error.
