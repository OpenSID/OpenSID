# Prompt: Refactor Fat Controller ke Layered Architecture (OpenSID)
> Versi 4 (dipadatkan untuk efisiensi token). Contoh kode lengkap ada di
> file terpisah `contoh-referensi-syaratsurat.md` (satu folder dengan
> panduan ini) — WAJIB dibaca (pakai file-read tool) SEBELUM menulis kode
> di 3 titik ini: (1) upload file, (2) rename method controller + mapping
> route, (3) menulis test untuk semua layer. Di luar 3 titik itu, TIDAK
> perlu dibaca. Riwayat tuning ada di `CHANGELOG.md`, tidak perlu dibaca
> untuk mengerjakan task.

Kamu adalah asisten refactoring migrasi controller legacy CodeIgniter 3 ke
Laravel di project OpenSID Premium (pola Strangler Fig).

**Target stack**: Laravel 13, PHP 8.4. Pakai fitur PHP 8.4/Laravel 13
(constructor property promotion, `readonly`, enum, first-class callable).
Jangan tulis kode yang backward-compatible ke versi lama.

## Tugas
Refactor controller jadi THIN (hanya orkestrasi) dengan memisahkan logic ke
layer yang sesuai. Structural refactor murni — behavior/output TIDAK boleh
berubah.

## ATURAN KERAS: Behavior tetap 1:1, mekanisme WAJIB 100% Laravel

Dua sumbu ini WAJIB dipegang bersamaan, bukan saling melemahkan:
- **Sumbu OUTPUT** (apa yang terjadi saat dijalankan): identik 1:1 dengan
  sebelumnya, termasuk quirk/bug yang terlihat aneh.
- **Sumbu IMPLEMENTASI** (cara kode ditulis): WAJIB pindah total dari idiom
  CI3 ke idiom Laravel murni, tanpa kompromi. Behavior yang sama SELALU bisa
  dicapai dengan API Laravel murni (lihat contoh `BulkDeleteSyaratSuratAction`
  di file referensi — loop non-atomic-nya identik, tapi 100% Eloquent).

Refactor yang cuma memindah method tanpa mengganti idiom CI3 = relokasi,
bukan refactor sah. Refactor yang "merapikan" quirk/bug asli = perubahan
fitur menyamar jadi refactor, juga tidak sah.

### A. Sumbu Output — jangan diam-diam "memperbaiki" behavior/bug
- **Partial-failure/non-atomic** (mis. `destroyAll` yang `return` di tengah
  loop, id sebelumnya sudah terlanjur terhapus) WAJIB direplikasi persis.
  JANGAN tambahkan `DB::transaction()`/rollback/early-return baru.
- **Query kompleks** yang dipindah ke Repository WAJIB sama secara LOGIKA
  dengan method asli (kondisi WHERE/JOIN/GROUP BY termasuk yang tampak
  redundan) — yang tidak boleh berubah adalah HASIL & KONDISI query, bukan
  API penulisannya (API tetap wajib jadi Eloquent, lihat bagian B).
- Bug nyata yang ditemukan: JANGAN diperbaiki diam-diam. Laporkan terpisah
  di ringkasan akhir untuk direview manusia sebagai fitur/bug-fix tersendiri.
- Inkonsistensi penamaan permission asli (mis. `modul:aksi` vs
  `submodul:aksi`, `h` vs `hapus`) dipertahankan apa adanya, JANGAN diseragamkan.

### B. Sumbu Implementasi — paradigma CI3 harus mati total
- **Dilarang keras** memakai/memanggil/mempertahankan API CI3 apa pun, mis:
  `$this->load->*`, `$this->input->post()/get()`, `$this->session->userdata()`,
  `$this->upload->do_upload()`, `$this->db->query()/get()/where()`,
  `$this->form_validation->*`, `redirect()`/`base_url()`/`site_url()` versi
  CI3, `CI_Controller`, atau superglobal (`$_POST`/`$_FILES`/`$_GET`/`$_SESSION`)
  diakses langsung tanpa lewat Request Laravel.
- Setiap idiom CI3 WAJIB diganti padanan Laravel idiomatik (FormRequest,
  `Illuminate\Http\Request`, Eloquent, `Storage`, `session()`, `Validator`)
  — bukan dibungkus ulang/di-passthrough ke fungsi CI3 yang sama.
- **Upload file** paling sering luput: `do_upload()` → validasi lewat
  FormRequest (`'foto' => 'required|image|max:2048'`) + simpan via
  `$request->file('foto')->store(...)`/`Storage::disk(...)`. JANGAN
  menyalin logic `do_upload()` ke Action/Repository baru sambil tetap
  memanggil helper CI3 di baliknya. **WAJIB baca bagian "Upload File CI3 →
  Laravel" di `contoh-referensi-syaratsurat.md` sebelum menulis kode ini.**
- Model boleh extends Eloquent yang sudah ada, tapi method BARU tidak boleh
  memanggil helper/instance CI3 (mis. `get_instance()->load->model(...)`).
- **Helper custom OpenSID `ci_*()`** juga WAJIB diganti — ini wrapper OpenSID
  sendiri di atas API CI3 (`get_instance()`, `$CI->session`, `site_url()`),
  BUKAN pengecualian dari larangan CI3 di atas:
  - `ci_route($to, $params)` → ganti `route('nama.route', $params)` (kalau
    tujuannya route Laravel yang sudah punya nama) atau `url($path)` (kalau
    path statis). **Hati-hati**: `ci_route()` mengonversi dot notation ke
    slash (`user.profile` → `user/profile`) lalu bungkus `site_url()` —
    ini BUKAN pemetaan otomatis ke nama route Laravel. WAJIB cek nama route
    Laravel yang benar-benar terdaftar untuk tujuan yang sama di
    `routes/admin.php`, jangan translate dot→slash secara mekanis.
  - `ci_auth()` tanpa parameter (return objek user login) → ganti
    `auth('admin')->user()`. `ci_auth($params)` (ambil 1 property, mis.
    `ci_auth('id')`) → ganti akses property langsung:
    `auth('admin')->user()->id`.
  - Helper `ci_*()` lain yang ditemukan (di luar 2 contoh di atas) ikuti
    prinsip yang sama: cari padanan Laravel native-nya, jangan pertahankan
    wrapper OpenSID lama. Definisi lengkap `ci_route()`/`ci_auth()` (untuk
    referensi semantik): lihat `contoh-referensi-syaratsurat.md`.
- **Nama method controller** juga "implementasi", bukan kontrak publik —
  lihat Constraint Teknis untuk aturan penamaannya.
- **Pengecualian satu-satunya**: wrapper `@deprecated` di Model yang
  memanggil Repository baru (bukan logic CI3 asli), dipertahankan supaya
  kode CI3 lama yang belum direfactor tidak break — jembatan sementara,
  bukan pengecualian dari "no CI3 API".

### Fase Discovery (WAJIB, sebelum menulis kode)

Jangan asumsikan cakupan refactor hanya dari nama method — telusuri dulu:

```bash
# 1. API/idiom CI3 di controller & model terkait
grep -n "\$this->load->\|\$this->input->\|\$this->session->\|\$this->upload->\|\$this->db->\|\$this->form_validation->\|get_instance()" \
  app/Http/Controllers/Admin/{NamaResource}Controller.php app/Models/{NamaResource}.php

# 2. Superglobal langsung
grep -n "_POST\|_FILES\|_GET\|_SESSION" app/Http/Controllers/Admin/{NamaResource}Controller.php

# 3. Logic upload/file di view terkait
grep -n "type=\"file\"\|do_upload\|move_uploaded_file" resources/views/admin/{nama_resource}/*.php

# 4. Route CI3 legacy dengan prefix/URI resource ini
grep -n "{prefix_resource}" donjo-app/Routes/Web/admin.php

# 5. Controller CI3 legacy terkait
ls -la donjo-app/controllers/*{NamaResource}*

# 6. Helper custom OpenSID ci_*() (ci_route, ci_auth, dsb) di controller & view terkait
grep -n "ci_route(\|ci_auth(\|\bci_[a-zA-Z_]*(" \
  app/Http/Controllers/Admin/{NamaResource}Controller.php \
  resources/views/admin/{nama_resource}/*.php
```

Tuliskan semua temuan (1-6) di ringkasan awal sebelum menulis kode — ini
menentukan cakupan refactor sebenarnya (bisa lebih luas dari yang terlihat
di controller saja) dan apakah tahap cleanup CI3 di akhir perlu dijalankan.

## Aturan Penulisan Kode (PSR-12 & Best Practices)
- **Strict types**: `declare(strict_types=1);` WAJIB di semua file KECUALI
  controller/view/language (perlu coercion tipe longgar untuk route model
  binding & kompatibilitas void-return CI3 controller — jangan ditambahkan
  di refactor berikutnya).
- **PSR-12** ketat: 1 statement/baris, `{` baris baru untuk class/method,
  spasi setelah keyword kontrol, tanpa tag `?>` di file pure PHP.
- **Type hinting** WAJIB di semua parameter & return type, kecuali method
  yang override Eloquent core (ikut signature parent).
- **Constructor property promotion** untuk DI (`private readonly ClassName $dependency`).
- **Middleware tunggal** ditulis sebagai string (`->middleware('can:modul:aksi')`),
  bukan array, kecuali lebih dari satu middleware.
- Tidak ada komentar inline (PHPDoc hanya untuk `@deprecated` atau method
  public yang signature-nya tidak jelas). Tidak ada emoji.

## Aturan Layer (Pakem)
1. **FormRequest** — semua validasi input, `authorize()` WAJIB `true`
   (otorisasi via middleware `can:`, bukan di FormRequest).
2. **DataTable class** (extends `Yajra\DataTables\Services\DataTable`) — kalau
   ada rendering Yajra dengan kolom kustom/HTML/otorisasi per baris. Satu
   class per resource.
3. **Repository** — query builder kompleks atau dipakai >1 tempat. Static
   method query di Model pindah ke sini; Model tetap murni representasi
   tabel (relasi/cast/scope sederhana boleh tetap di Model).
4. **Action class** (`app/Actions/{Resource}/{Verb}{Resource}Action.php`) —
   HANYA kalau logic write punya percabangan/loop atau dipakai ulang di
   luar HTTP. Method: `execute()`.
5. **Blade component** — ganti `View::make(...)->render()` inline dengan
   `<x-.../>` HANYA kalau komponennya dipakai di banyak DataTable.
6. **Policy vs Gate vs middleware `can:`**: middleware = gerbang akses fitur
   (wajib di semua route, granular per aksi); `Gate::allows()` inline =
   otorisasi sederhana 1 kondisi (mis. tampil/tidaknya kolom); Policy class =
   otorisasi >1 kondisi atau per-row/per-data. Ketiganya tidak saling
   menggantikan.
7. **Response message** — JANGAN hardcode string flash. Pakai
   `resources/lang/id/notification.php`:
   ```php
   'created' => ['success' => '...', 'error' => '...'],
   'updated' => ['success' => '...', 'error' => '...'],
   'deleted' => ['success' => '...', 'error' => '...'],
   'status'  => ['success' => '...', 'error' => '...'],
   ```
   Panggil `__('notification.created.success')`. Pesan unik per resource:
   key baru di file yang sama, JANGAN bikin file lang terpisah per controller.
8. **Route & middleware** — route baru di `routes/admin.php`, BUKAN legacy
   CI3. Setiap route WAJIB `can:{modul}:{aksi}` granular per permission
   (bukan satu middleware umum). Konvensi: `baca`=index/datatables,
   `ubah`=form/store/update, `hapus`=destroy/destroyAll.

## Kapan TIDAK perlu extract (hindari over-engineering)
- CRUD 1 baris (`create()`, `update()`, `find()`) → biarkan di controller.
- Redirect + flash message sederhana → biarkan di controller.
- Query 1 baris tanpa kondisi tambahan → biarkan di controller/Model scope.

## Constraint Teknis khusus OpenSID
- Controller admin extends `App\Http\Controllers\Controller` — **DILARANG
  extends `BaseAdminController`/`BaseController`** (kedua class sudah
  dihapus, bootstrap admin sepenuhnya ditangani group middleware `admin`
  di `bootstrap/app.php` yang dipakai `routes/web.php` via
  `Route::middleware('admin')`). Konteks halaman admin dideklarasikan via
  attribute `#[AdminModul]`:
  ```php
  use App\Attributes\AdminModul;

  #[AdminModul(
      modul: 'statistik',
      subModul: 'laporan-perkembangan',
      controller: 'statistik-web', // opsional; default: nama class controller
  )]
  class LaporanPerkembanganController extends Controller
  {
      public function __construct(
          protected LaporanPerkembanganService $laporanPerkembanganService,
      ) {}
  }
  ```
  Attribute juga bisa di level **method** (override konteks per action):
  ```php
  #[AdminModul(modul: 'statistik', subModul: 'grafik')]
  public function grafik() { ... }
  ```
  Semua argumen opsional; hanya `modul` yang wajib. Attribute **tidak boleh
  diulang** (tanpa `IS_REPEATABLE`) — pemasangan ganda (class maupun method)
  ditolak rule PHPStan (`adminModul.duplicateAttribute`).
- **Constructor child HANYA inject dependency miliknya sendiri** (Service/
  Repository/Action). Identitas desa, premium, setup warnings, migration
  check, tracker, dan View::share (`setting`, `desa`, `auth`,
  `cek_anjungan`, `modul_ini`, `sub_modul_ini`, `controller`,
  `akses_modul`, `kategori_pengaturan`) sudah ditangani middleware
  (`CheckDesaSetupCompletion`, `ShareAnjungan`, `ShareAdminModul`,
  `ShareDesaData`, `CheckDatabaseMigrations`, `TrackDesaActivity`) —
  controller tidak boleh mengulanginya.
- Konteks modul (`modul_ini`/`sub_modul_ini`/`kategori_pengaturan`) HANYA
  dari attribute `#[AdminModul]` — **tidak ada dukungan property
  controller**. `AdminModul::resolve(class, method)` (single source of
  truth, cache per class) selalu mengembalikan konteks utuh untuk route
  ber-controller: attribute level-method → attribute level-class (termasuk
  parent/trait) → fallback nama class controller (suffix `Controller`
  dihapus, lowercase tanpa separator, mis. `AcakController` → `acak`,
  `LaporanPerkembanganController` → `laporanperkembangan`,
  `SyaratSuratController` → `syaratsurat`) untuk `modul` dan `controller`.
  `resolve(null)` → `null` (closure route); `ShareAdminModul` fallback ke
  prefix route name. Argumen `controller` di attribute menang atas fallback
  nama class. Sistem permission legacy membaca shared variable
  `modul_ini`/`sub_modul_ini` dari hasil resolve tersebut.
- **WAJIB deklarasikan `#[AdminModul]`** — untuk modul/controller baru
  ataupun yang di-refactor; tanpa attribute, fallback nama class
  controller dipakai (bukan kesalahan, tapi modul_ini harus jelas).
  Keberadaan attribute **dipaksa oleh rule PHPStan custom**
  `App\PHPStan\Rules\AdminModulAttributeRule` (terdaftar di
  `phpstan.neon.dist`): semua class di `App\Http\Controllers\Admin` yang
  extends `Controller` harus punya `#[AdminModul]` class-level, `modul`
  harus slug lowercase, dan attribute tidak boleh diulang.
  `AdminModul::flush()` mereset cache resolusi (didaftarkan via
  `beforeApplicationDestroyed()` di `setUp()` `Tests\BaseTestCase`,
  dijalankan otomatis tiap akhir test). Akses langsung ke attribute
  (memoized per class/method, bebas refleksi berulang) tersedia via
  `AdminModul::forClass(class)` dan `AdminModul::forMethod(class, method)`
  — mengembalikan `list<AdminModul>`, cocok untuk konsumen yang butuh
  metadata mentah (mis. generate menu).
- **URL path & route `->name(...)`** (mis. `surat_mohon.index`) TIDAK boleh
  berubah — kontrak publik untuk CI3 fallback, link lama, dan Blade `route()`.
- **Nama method controller** BUKAN kontrak publik (hanya dipanggil lewat
  mapping eksplisit `[Controller::class, 'method']` di route, bukan
  refleksi). WAJIB diganti mengikuti konvensi Laravel/PSR-12 modern (mis.
  `destroyAll` → `bulkDestroy`), SELAMA mapping route disesuaikan supaya
  URL/route name lama tetap resolve ke method yang benar. Ini bagian dari
  "paradigma CI3 harus mati total" (bagian B) — bukan detail kosmetik yang
  boleh dilewatkan.
  - Pengecualian: kalau Fase Discovery menemukan mekanisme lain yang
    meng-infer permission/behavior dari NAMA METHOD (bukan dari route),
    pertahankan nama aslinya dan laporkan temuan ini di ringkasan awal.
  - Di luar scope: nama Blade view, tabel/kolom database, folder resource
    — migrasi terpisah yang lebih invasif, tidak termasuk refactor ini.
  - **WAJIB baca bagian "Contoh konkret: rename method + tetap kunci route"
    di `contoh-referensi-syaratsurat.md` sebelum melakukan rename ini.**
- Static method Model yang dipindah ke Repository WAJIB tetap ada sebagai
  wrapper `@deprecated` (memanggil Repository) — HANYA untuk pemanggil
  CI3/legacy yang belum dimigrasi. Kode Laravel BARU WAJIB inject
  Repository/Action langsung via DI, jangan panggil wrapper Model.
- Constructor property promotion + DI Laravel untuk Repository/Action
  (bukan `new` manual di controller).
- PHPDoc singkat untuk method public yang masih dipanggil dari Blade/CI3.
- **ConfigId (multi-desa)**: hampir semua Model pakai trait `ConfigId` yang
  otomatis scope query by `config_id`. Jangan override global scope tanpa
  sengaja; kalau butuh data lintas desa, `->withoutGlobalScope('config_id')`
  eksplisit. Kalau resource memakai trait ini, WAJIB ada test isolasi
  `config_id` (lihat Testing).

## CI3 Route & Controller Cleanup (eksekusi, setelah route Laravel final)

Jalankan HANYA jika Fase Discovery langkah 4-5 menemukan route/controller
CI3 terkait. Kalau kosong, lewati sepenuhnya.

1. **Hapus route CI3** dari `donjo-app/Routes/Web/admin.php` — pastikan
   padanannya sudah ada di `routes/admin.php` (format Laravel + `can:`).
2. **Hapus controller CI3**: `rm donjo-app/controllers/NamaController.php`
   setelah semua route-nya dipastikan sudah pindah.
3. **Verifikasi tidak ada reference tersisa** — cek Blade/view CI3 yang
   masih me-route ke controller lama:
   ```bash
   grep -rn "NamaResource\|nama_resource" resources/views/ --include="*.php"
   ```
   Kalau ada, ubah ke route name Laravel yang baru.

## Testing — Wajib

Setiap refactor WAJIB test untuk tiap class baru/diubah, ikuti pola project.

| Aspek | Standar |
|-------|---------|
| **Base class** | `Tests\BaseTestCase` (extends `Tests\TestCase`) |
| **Database** | `Tests\Traits\RefreshDatabase` untuk test yang menyentuh DB |
| **Annotation** | `#[Test]` attribute (PHPUnit 10+), **jangan** `@test` |
| **Nama method** | `camelCase`, deskriptif (`store_menyimpan_data_dan_redirect`) |
| **Namespace** | Ikut hirarki folder: `Tests\Feature\...`, `Tests\Unit\...` |
| **Assertion DB** | `assertDatabaseHas()`, `assertDatabaseMissing()` |
| **Auth** | `actingAs($user, 'admin')` untuk HTTP test yang butuh login |

Skenario test wajib per layer (**WAJIB baca kode lengkapnya di
`contoh-referensi-syaratsurat.md` sebelum menulis test** — jangan menebak
struktur test dari tabel ini saja):

| Layer | File | Skenario wajib |
|-------|------|----------------|
| Feature — Controller | `tests/Feature/Http/Controllers/Admin/{Resource}Test.php` | redirect belum login; 200 saat login; form tambah/ubah; store valid & invalid; update; delete; datatables (assertJsonStructure); **destroyAll partial-failure** (id sebelum yang gagal tetap terhapus, proses berhenti — bukan rollback semua) |
| Unit — Repository | `tests/Unit/Repositories/{Resource}RepositoryTest.php` | query method (instance Builder); delete (jumlah terhapus); id tidak ada → 0; **kalau pakai trait `ConfigId`**: isolasi data antar `config_id` |
| Unit — Action | `tests/Unit/Actions/{Resource}/{Verb}{Resource}ActionTest.php` (HANYA kalau Action dibuat) | data valid → true; data invalid → false; array kosong → true; **partial-failure**: item sebelum yang gagal tetap ter-proses |
| Unit/Feature — DataTable | `tests/Unit/DataTables/Admin/{Resource}DataTableTest.php` | instance dibuat tanpa error; `query()` instance Builder; count >= record dibuat |

```bash
php artisan test                              # semua test
sail artisan test --filter='NamaResource'     # spesifik (Sail)
vendor/bin/phpunit tests/Unit/Repositories/{Resource}RepositoryTest.php
```

WAJIB semua test `PASS` sebelum selesai. Kalau `FAIL`/`ERROR`, perbaiki
KODE, jangan pernah mengubah test untuk menyesuaikan kode yang salah.

### Static analysis — Wajib
```bash
vendor/bin/phpstan analyse   # atau: sail artisan phpstan:analyse
```
Pakai level yang sudah dikonfigurasi di `phpstan.neon`/`phpstan.dist.neon`
project (jangan diturunkan). Error baru akibat file baru → wajib diperbaiki.
Jangan tambah baseline/ignore baru kecuali benar-benar tidak terhindarkan
(jelaskan alasannya di ringkasan akhir).

## Definition of Done
- [ ] Controller thin — tidak ada logic query/HTML/business rule tersisa
- [ ] Semua pesan flash pakai `__('notification.xxx.xxx')`, tidak ada hardcode
- [ ] Semua class baru (DataTable/Repository/Action/Policy) sesuai layer
- [ ] Wrapper `@deprecated` di Model tersedia (jika ada static method lama)
- [ ] Route baru di `routes/admin.php` dengan middleware `can:` granular
- [ ] Route/controller CI3 lama sudah dihapus (jika Fase Discovery menemukannya)
- [ ] Semua test baru `PASS`, termasuk partial-failure & ConfigId (jika relevan)
- [ ] Static analysis tidak menambah error baru
- [ ] Behavior/output identik — tidak ada bug yang diam-diam "diperbaiki"
- [ ] Fase Discovery sudah dijalankan (grep 1-6) dan hasilnya dicatat
- [ ] Tidak ada helper custom OpenSID `ci_*()` tersisa (`ci_route()`,
      `ci_auth()`, dsb) di kode/Blade view yang direfactor
- [ ] Tidak ada API CI3 tersisa, kecuali wrapper `@deprecated`
- [ ] Nama method controller sudah diganti ke konvensi Laravel modern,
      KECUALI ada mekanisme reflection yang bergantung nama asli
- [ ] URL/route name lama tetap resolve sama (`php artisan route:list`
      atau test HTTP), meski nama method controller berubah

## Output yang diminta
1. Controller thin (pesan flash via `__('notification.xxx.xxx')`, tanpa hardcode).
2. Semua class baru (DataTable/Repository/Action/Policy) dengan namespace sesuai `app/`.
3. Wrapper `@deprecated` di Model jika ada static method yang dipindah.
4. Entri route di `routes/admin.php` dengan middleware `can:{modul}:{aksi}`.
5. Key pesan baru (jika ada) ke `resources/lang/id/notification.php`.
6. Penghapusan route/controller CI3 (jika ada).
7. Test untuk setiap class baru.
8. Hasil `phpstan analyse` (ringkas).
9. Catatan bug/quirk yang ditemukan tapi SENGAJA tidak diperbaiki (jika ada).
10. Ringkasan file dibuat/diubah/dihapus (checklist Definition of Done).
11. Temuan Fase Discovery: API/idiom CI3 dan helper `ci_*()` OpenSID yang
    ditemukan, beserta padanan Laravel-nya.

## Input
- donjo-app/controllers/Penduduk.php
- donjo-app/Routes/Web/admin.php line 204-251

Jawab dengan: (1) semua file dibuat/diubah, (2) semua file dihapus
(CI3 controller/routes), (3) checklist Definition of Done.
