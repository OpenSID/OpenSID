# Contoh Referensi Lengkap — Refactor SyaratSuratController

> File referensi. TIDAK perlu dibaca di setiap task refactor — cukup buka
> kalau butuh melihat pola konkret (before/after code, contoh test
> lengkap). Rules yang mengikat ada di `panduan-prompt-refaktor.md`, bukan
> di file ini. Kalau ada perbedaan penekanan antara file ini dan rules
> inti, rules inti yang berlaku.

## Contoh Referensi: Upload File CI3 → Laravel

Contoh ini ilustrasi kasus paling sering luput saat refactor: logic upload
file yang masih pakai API CI3. Kalau Fase Eksplorasi Codebase menemukan pola
seperti di bawah, ganti TOTAL — jangan dipindah lokasinya saja.

### Sebelum (CI3, `do_upload()`)

```php
public function store()
{
    $config['upload_path']   = './uploads/persyaratan/';
    $config['allowed_types'] = 'jpg|jpeg|png|pdf';
    $config['max_size']      = 2048;
    $config['file_name']     = uniqid('syarat_');

    $this->load->library('upload', $config);

    if (! $this->upload->do_upload('lampiran')) {
        $error = $this->upload->display_errors();

        return redirect()->back()->with('error', $error);
    }

    $upload_data = $this->upload->data();

    $this->db->insert('ref_syarat_surat', [
        'ref_syarat_nama' => $this->input->post('ref_syarat_nama'),
        'lampiran'        => $upload_data['file_name'],
    ]);

    redirect('surat_mohon');
}
```

### Sesudah (Laravel, FormRequest + Storage facade)

`app/Http/Requests/Admin/SyaratSuratRequest.php` — tambahkan rule file:

```php
public function rules(): array
{
    return [
        'ref_syarat_nama' => ['required', 'string', 'max:255'],
        'lampiran'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
    ];
}
```

Controller — tidak ada `$this->load`, `$this->upload`, atau `$this->db`
sama sekali, murni memakai `Illuminate\Http\UploadedFile` dan `Storage`:

```php
public function store(SyaratSuratRequest $request): RedirectResponse
{
    $data = $request->validated();

    if ($request->hasFile('lampiran')) {
        $data['lampiran'] = $request->file('lampiran')
            ->store('persyaratan', 'public');
    }

    SyaratSurat::create($data);

    return redirect()->route('surat_mohon.index')
        ->with('success', __('notification.created.success'));
}
```

Kalau logic penyimpanan file cukup kompleks (mis. resize gambar, generate
beberapa ukuran thumbnail, atau dipakai di banyak controller), baru extract
ke Action tersendiri (`app/Actions/SyaratSurat/StoreLampiranSyaratSuratAction.php`)
yang menerima `UploadedFile` dan mengembalikan path — tetap 100% API Laravel,
tidak ada pemanggilan balik ke CI3.

---

## Contoh Referensi (Sebelum → Sesudah)

Contoh ini memakai kasus `SyaratSuratController` sebagai ilustrasi penerapan
pakem di atas. Gunakan sebagai referensi pola, bukan untuk disalin mentah.
Perhatikan bagian `destroyAll` — ini contoh nyata partial-failure behavior
yang harus direplikasi persis (lihat ATURAN KERAS di atas).

### Sebelum (fat controller)

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Attributes\AdminModul;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SyaratSuratRequest;
use App\Models\SyaratSurat;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

#[AdminModul(modul: 'layanan-surat', subModul: 'daftar-persyaratan')]
class SyaratSuratController extends Controller
{
    public function index()
    {
        return view('admin.syaratan_surat.index');
    }

    public function datatables(): JsonResponse
    {
        $query = SyaratSurat::formatSuratExist();

        return DataTables::of($query)
            ->addColumn('ceklist', static function ($row) {
                if (Gate::allows('layanan-surat:h')) {
                    return '<input type="checkbox" name="id_cb[]" value="' . $row->ref_syarat_id . '"/>';
                }
            })
            ->addIndexColumn()
            ->addColumn('aksi', static function ($row): string {
                $aksi = View::make('admin.layouts.components.buttons.edit', [
                    'url' => "surat_mohon/form/{$row->ref_syarat_id}",
                ])->render();

                if ($row->jumlah_format_surat == '0') {
                    $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => "surat_mohon/delete/{$row->ref_syarat_id}",
                        'confirmDelete' => true,
                    ])->render();
                }

                return $aksi;
            })
            ->rawColumns(['ceklist', 'aksi'])
            ->make();
    }

    public function form(?SyaratSurat $syaratSurat = null)
    {
        if ($syaratSurat) {
            $action = 'Ubah';
            $formAction = route('surat_mohon.update', $syaratSurat->ref_syarat_id);
        } else {
            $action = 'Tambah';
            $formAction = route('surat_mohon.insert');
        }

        return view('admin.syaratan_surat.form', [
            'action'           => $action,
            'form_action'      => $formAction,
            'ref_syarat_surat' => $syaratSurat,
        ]);
    }

    public function store(SyaratSuratRequest $request)
    {
        SyaratSurat::create($request->validated());

        return redirect()->route('surat_mohon.index')
            ->with('success', 'Berhasil Tambah Data');
    }

    public function update(SyaratSuratRequest $request, SyaratSurat $syaratSurat)
    {
        $syaratSurat->update($request->validated());

        return redirect()->route('surat_mohon.index')
            ->with('success', 'Berhasil Ubah Data');
    }

    public function destroy(SyaratSurat $syaratSurat)
    {
        SyaratSurat::deleteFormatSuratExist($syaratSurat->ref_syarat_id);

        return redirect()->route('surat_mohon.index')
            ->with('success', 'Berhasil Hapus Data');
    }

    public function destroyAll()
    {
        $ids = request('id_cb', []);

        foreach ($ids as $id) {
            $deleted = SyaratSurat::deleteFormatSuratExist($id);

            if ($deleted === 0) {
                return redirect()->route('surat_mohon.index')
                    ->with('error', 'Gagal Hapus Data');
            }
        }

        return redirect()->route('surat_mohon.index')
            ->with('success', 'Berhasil Hapus Data');
    }
}
```

### Sesudah — Controller (thin)

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Actions\SyaratSurat\BulkDeleteSyaratSuratAction;
use App\Attributes\AdminModul;
use App\DataTables\Admin\SyaratSuratDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SyaratSuratRequest;
use App\Models\SyaratSurat;
use App\Repositories\SyaratSuratRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

#[AdminModul(modul: 'layanan-surat', subModul: 'daftar-persyaratan')]
class SyaratSuratController extends Controller
{
    public function index()
    {
        return view('admin.syaratan_surat.index');
    }

    public function datatables(SyaratSuratDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function form(?SyaratSurat $syaratSurat = null)
    {
        $action = $syaratSurat ? 'Ubah' : 'Tambah';
        $formAction = $syaratSurat
            ? route('surat_mohon.update', $syaratSurat->ref_syarat_id)
            : route('surat_mohon.insert');

        return view('admin.syaratan_surat.form', [
            'action'           => $action,
            'form_action'      => $formAction,
            'ref_syarat_surat' => $syaratSurat,
        ]);
    }

    public function store(SyaratSuratRequest $request): RedirectResponse
    {
        SyaratSurat::create($request->validated());

        return redirect()->route('surat_mohon.index')
            ->with('success', __('notification.created.success'));
    }

    public function update(SyaratSuratRequest $request, SyaratSurat $syaratSurat): RedirectResponse
    {
        $syaratSurat->update($request->validated());

        return redirect()->route('surat_mohon.index')
            ->with('success', __('notification.updated.success'));
    }

    public function destroy(SyaratSurat $syaratSurat, SyaratSuratRepository $repository): RedirectResponse
    {
        $repository->deleteWithFormatSuratExist($syaratSurat->ref_syarat_id);

        return redirect()->route('surat_mohon.index')
            ->with('success', __('notification.deleted.success'));
    }

    public function destroyAll(Request $request, BulkDeleteSyaratSuratAction $action): RedirectResponse
    {
        $success = $action->execute($request->input('id_cb', []));

        return redirect()->route('surat_mohon.index')
            ->with(
                $success ? 'success' : 'error',
                $success ? __('notification.deleted.success') : __('notification.deleted.error'),
            );
    }
}
```

### Sesudah — File pesan terpusat (`resources/lang/id/notification.php`)

> File ini SUDAH ADA di project. JANGAN membuat ulang. Cukup tambahkan key
> baru jika ada pesan spesifik yang belum tercakup.

```php
<?php

declare(strict_types=1);

return [
    'created' => [
        'success' => 'Berhasil menambah data',
        'error'   => 'Gagal menambah data',
    ],
    'updated' => [
        'success' => 'Berhasil mengubah data',
        'error'   => 'Gagal mengubah data',
    ],
    'deleted' => [
        'success' => 'Berhasil menghapus data',
        'error'   => 'Gagal menghapus data',
    ],
    'status' => [
        'success' => 'Berhasil mengubah status data',
        'error'   => 'Gagal mengubah status data',
    ],

    // Lainnya
    'mode_demo' => 'Fitur ini tidak tersedia dalam mode demo.',
];
```

> Catatan: jika resource butuh pesan spesifik yang tidak masuk kategori
> created/updated/deleted/status generik (mis. "Nomor surat sudah dipakai"),
> tambahkan sebagai key baru di file yang sama, misal `'syarat_surat' => ['nomor_dipakai' => '...']`,
> jangan buat file `lang` terpisah per controller. Lokasi file adalah
> `resources/lang/id/notification.php`, BUKAN `lang/id/notification.php`.

### Sesudah — Route dengan middleware (`routes/admin.php`)

```php
<?php

use App\Http\Controllers\Admin\SyaratSuratController;
use Illuminate\Support\Facades\Route;

Route::prefix('surat_mohon')->group(static function (): void {
    Route::get('/', [SyaratSuratController::class, 'index'])
        ->middleware('can:daftar-persyaratan:baca')
        ->name('surat_mohon.index');

    Route::post('/datatables', [SyaratSuratController::class, 'datatables'])
        ->middleware('can:daftar-persyaratan:baca')
        ->name('surat_mohon.datatables');

    Route::get('/form/{syaratSurat?}', [SyaratSuratController::class, 'form'])
        ->middleware('can:daftar-persyaratan:ubah')
        ->name('surat_mohon.form');

    Route::post('/insert', [SyaratSuratController::class, 'store'])
        ->middleware('can:daftar-persyaratan:ubah')
        ->name('surat_mohon.insert');

    Route::post('/update/{syaratSurat}', [SyaratSuratController::class, 'update'])
        ->middleware('can:daftar-persyaratan:ubah')
        ->name('surat_mohon.update');

    Route::get('/delete/{syaratSurat}', [SyaratSuratController::class, 'destroy'])
        ->middleware('can:daftar-persyaratan:hapus')
        ->name('surat_mohon.delete');

    Route::post('/deleteAll', [SyaratSuratController::class, 'destroyAll'])
        ->middleware('can:daftar-persyaratan:hapus')
        ->name('surat_mohon.delete_all');
});
```

Konvensi middleware: `baca` untuk endpoint read-only (`index`, `datatables`),
`ubah` untuk endpoint yang membuat/mengubah data (`form`, `store`, `update`),
`hapus` untuk endpoint penghapusan (`destroy`, `destroyAll`). Nama modul di
`can:{modul}:{aksi}` mengikuti `$subModulIni` pada controller. Catatan:
`Gate::allows('layanan-surat:h')` di DataTable memakai `$modulIni` (level
lebih tinggi) dan singkatan aksi `h` — ini pola asli yang dipertahankan apa
adanya (lihat ATURAN KERAS di atas), bukan berarti pola baru harus meniru
inkonsistensi ini secara sengaja di resource lain.

Perhatikan juga: route name `surat_mohon.insert` (baris `Route::post('/insert', ...)`)
tetap dipetakan ke method controller `store`, dan `surat_mohon.delete_all`
dipetakan ke method `destroyAll`. Ini contoh konkret bahwa nama route/URL
(yang dikunci demi kompatibilitas CI3 fallback) dan nama method PHP
(yang bebas mengikuti konvensi Laravel) memang dua hal yang independen —
lihat Constraint teknis untuk aturan lengkapnya.

> **Contoh konkret: rename method + tetap kunci route**
>
> Dokumen ini sengaja mempertahankan nama `destroyAll` di seluruh contoh
> supaya konsisten dengan Repository/Action/Test yang mereferensikannya.
> Tapi kalau kamu memutuskan rename (WAJIB dipertimbangkan sesuai rule di
> Constraint teknis), begini bentuknya — URL dan route name TIDAK ikut
> berubah, hanya method PHP dan referensi ke method itu di file route:
>
> ```php
> // routes/admin.php — URL & route name TETAP, method di-reference berubah
> Route::post('/deleteAll', [SyaratSuratController::class, 'bulkDestroy'])
>     ->middleware('can:daftar-persyaratan:hapus')
>     ->name('surat_mohon.delete_all'); // <- tidak berubah
> ```
> ```php
> // SyaratSuratController.php — method di-rename, signature & isi sama persis
> public function bulkDestroy(Request $request, BulkDeleteSyaratSuratAction $action): RedirectResponse
> {
>     // isi method IDENTIK dengan destroyAll() sebelumnya — hanya nama yang berubah
> }
> ```
> Kelas Action/Repository (`BulkDeleteSyaratSuratAction`, dsb) TIDAK perlu
> ikut di-rename hanya karena method controllernya di-rename — itu class
> terpisah yang penamaannya sudah lepas dari nama method CI3 lama sejak
> awal dibuat.

### Sesudah — DataTable class

```php
<?php

declare(strict_types=1);

namespace App\DataTables\Admin;

use App\Models\SyaratSurat;
use App\Repositories\SyaratSuratRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Services\DataTable;

class SyaratSuratDataTable extends DataTable
{
    public function __construct(
        private readonly SyaratSuratRepository $repository,
    ) {
    }

    /**
     * Parameter $model wajib sesuai signature Yajra, walau tidak dipakai
     * karena query diambil dari Repository.
     */
    public function query(SyaratSurat $model)
    {
        return $this->repository->queryWithFormatSuratExist();
    }

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('ceklist', static fn ($row) => $this->renderCeklist($row))
            ->addIndexColumn()
            ->addColumn('aksi', static fn ($row) => $this->renderAksi($row))
            ->rawColumns(['ceklist', 'aksi']);
    }

    private function renderCeklist(SyaratSurat $row): ?string
    {
        return Gate::allows('layanan-surat:h')
            ? '<input type="checkbox" name="id_cb[]" value="' . $row->ref_syarat_id . '"/>'
            : null;
    }

    /**
     * Masih pakai View::make(...)->render() karena tombol edit/hapus ini
     * SUDAH dipakai lintas banyak DataTable lain di project — kandidat kuat
     * untuk dikonversi ke Blade component <x-.../> (rule 5), tapi konversi
     * itu di luar scope refactor SATU resource ini karena menyentuh
     * DataTable lain juga. Kalau resource yang kamu refactor TIDAK berbagi
     * komponen tombol ini dengan resource lain, konversi ke <x-.../> di
     * refactor ini juga boleh dilakukan.
     */
    private function renderAksi(SyaratSurat $row): string
    {
        $aksi = View::make('admin.layouts.components.buttons.edit', [
            'url' => "surat_mohon/form/{$row->ref_syarat_id}",
        ])->render();

        if ($row->jumlah_format_surat == '0') {
            $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                'url'           => "surat_mohon/delete/{$row->ref_syarat_id}",
                'confirmDelete' => true,
            ])->render();
        }

        return $aksi;
    }
}
```

### Sesudah — Repository

```php
<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\SyaratSurat;
use Illuminate\Database\Eloquent\Builder;

class SyaratSuratRepository
{
    /**
     * Query disalin PERSIS dari SyaratSurat::formatSuratExist() asli.
     * Jangan disederhanakan meski JOIN/GROUP BY tampak bisa dioptimasi —
     * di luar scope refactor structural ini.
     */
    public function queryWithFormatSuratExist(): Builder
    {
        return SyaratSurat::query()
            ->selectRaw('ref_syarat_surat.*, COUNT(format_surat_id) as jumlah_format_surat')
            ->leftJoin('format_surat', 'format_surat.ref_syarat_id', '=', 'ref_syarat_surat.ref_syarat_id')
            ->groupBy('ref_syarat_surat.ref_syarat_id');
    }

    /**
     * Disalin PERSIS dari SyaratSurat::deleteFormatSuratExist() asli.
     */
    public function deleteWithFormatSuratExist(string $id): int
    {
        return SyaratSurat::where('ref_syarat_id', $id)
            ->whereDoesntHave('formatSurat')
            ->delete();
    }
}
```

### Sesudah — Action

```php
<?php

declare(strict_types=1);

namespace App\Actions\SyaratSurat;

use App\Repositories\SyaratSuratRepository;

class BulkDeleteSyaratSuratAction
{
    public function __construct(
        private readonly SyaratSuratRepository $repository,
    ) {
    }

    /**
     * PENTING: non-atomic secara sengaja, mereplikasi behavior asli.
     * Begitu satu id gagal dihapus, method berhenti dan return false —
     * id-id SEBELUM id yang gagal SUDAH terlanjur terhapus. Ini BUKAN
     * bug yang perlu diperbaiki dalam refactor ini (lihat ATURAN KERAS
     * di prompt).
     */
    public function execute(array $ids): bool
    {
        foreach ($ids as $id) {
            if ($this->repository->deleteWithFormatSuratExist($id) === 0) {
                return false;
            }
        }

        return true;
    }
}
```

### Sesudah — Wrapper deprecated di Model

```php
/**
 * @deprecated Pindah ke SyaratSuratRepository::queryWithFormatSuratExist().
 *             Wrapper ini dipertahankan untuk kompatibilitas CI3 fallback.
 */
public static function formatSuratExist()
{
    return app(\App\Repositories\SyaratSuratRepository::class)->queryWithFormatSuratExist();
}

/**
 * @deprecated Pindah ke SyaratSuratRepository::deleteWithFormatSuratExist().
 */
public static function deleteFormatSuratExist(string $id): int
{
    return app(\App\Repositories\SyaratSuratRepository::class)->deleteWithFormatSuratExist($id);
}
```

### Sesudah — Feature Test Controller

```php
<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\SyaratSurat;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

class SyaratSuratControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::first();
    }

    #[Test]
    public function index_redirect_ke_siteman_saat_belum_login(): void
    {
        $this->get(route('surat_mohon.index'))
            ->assertRedirect('siteman');
    }

    #[Test]
    public function index_status_200_saat_login(): void
    {
        $this->actingAs($this->user, 'admin')
            ->get(route('surat_mohon.index'))
            ->assertStatus(200);
    }

    #[Test]
    public function form_tanpa_id_menampilkan_form_tambah(): void
    {
        $this->actingAs($this->user, 'admin')
            ->get(route('surat_mohon.form'))
            ->assertStatus(200)
            ->assertSee('Tambah');
    }

    #[Test]
    public function form_dengan_id_menampilkan_form_ubah(): void
    {
        $syarat = SyaratSurat::create(['ref_syarat_nama' => 'Test Syarat']);

        $this->actingAs($this->user, 'admin')
            ->get(route('surat_mohon.form', $syarat->ref_syarat_id))
            ->assertStatus(200)
            ->assertSee('Ubah');
    }

    #[Test]
    public function store_menyimpan_data_dan_redirect(): void
    {
        $this->actingAs($this->user, 'admin')
            ->from(route('surat_mohon.index'))
            ->post(route('surat_mohon.insert'), ['ref_syarat_nama' => 'Syarat Baru'])
            ->assertRedirect(route('surat_mohon.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('ref_syarat_surat', ['ref_syarat_nama' => 'Syarat Baru']);
    }

    #[Test]
    public function store_gagal_saat_validasi_error(): void
    {
        $this->actingAs($this->user, 'admin')
            ->from(route('surat_mohon.form'))
            ->post(route('surat_mohon.insert'), ['ref_syarat_nama' => ''])
            ->assertSessionHasErrors('ref_syarat_nama');
    }

    #[Test]
    public function update_mengubah_data_dan_redirect(): void
    {
        $syarat = SyaratSurat::create(['ref_syarat_nama' => 'Lama']);

        $this->actingAs($this->user, 'admin')
            ->from(route('surat_mohon.index'))
            ->post(route('surat_mohon.update', $syarat->ref_syarat_id), ['ref_syarat_nama' => 'Baru'])
            ->assertRedirect(route('surat_mohon.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('ref_syarat_surat', ['ref_syarat_nama' => 'Baru']);
    }

    #[Test]
    public function delete_menghapus_data_dan_redirect(): void
    {
        $syarat = SyaratSurat::create(['ref_syarat_nama' => 'Akan Dihapus']);

        $this->actingAs($this->user, 'admin')
            ->get(route('surat_mohon.delete', $syarat->ref_syarat_id))
            ->assertRedirect(route('surat_mohon.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('ref_syarat_surat', ['ref_syarat_id' => $syarat->ref_syarat_id]);
    }

    #[Test]
    public function datatables_mengembalikan_json_saat_login(): void
    {
        SyaratSurat::create(['ref_syarat_nama' => 'Syarat 1']);
        SyaratSurat::create(['ref_syarat_nama' => 'Syarat 2']);

        $this->actingAs($this->user, 'admin')
            ->post(route('surat_mohon.datatables'))
            ->assertOk()
            ->assertJsonStructure([
                'draw',
                'recordsTotal',
                'recordsFiltered',
                'data',
            ]);
    }

    #[Test]
    public function datatables_redirect_ke_siteman_saat_belum_login(): void
    {
        $this->post(route('surat_mohon.datatables'))
            ->assertRedirect('siteman');
    }

    #[Test]
    public function destroy_all_menghapus_banyak_data_dan_redirect(): void
    {
        $syarat1 = SyaratSurat::create(['ref_syarat_nama' => 'Satu']);
        $syarat2 = SyaratSurat::create(['ref_syarat_nama' => 'Dua']);

        $this->actingAs($this->user, 'admin')
            ->from(route('surat_mohon.index'))
            ->post(route('surat_mohon.delete_all'), ['id_cb' => [$syarat1->ref_syarat_id, $syarat2->ref_syarat_id]])
            ->assertRedirect(route('surat_mohon.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('ref_syarat_surat', ['ref_syarat_id' => $syarat1->ref_syarat_id]);
        $this->assertDatabaseMissing('ref_syarat_surat', ['ref_syarat_id' => $syarat2->ref_syarat_id]);
    }

    #[Test]
    public function destroy_all_menangani_id_tidak_valid(): void
    {
        $this->actingAs($this->user, 'admin')
            ->from(route('surat_mohon.index'))
            ->post(route('surat_mohon.delete_all'), ['id_cb' => ['id_tidak_ada']])
            ->assertRedirect(route('surat_mohon.index'))
            ->assertSessionHas('error');
    }

    #[Test]
    public function destroy_all_menghentikan_proses_di_id_yang_gagal_dan_menyisakan_penghapusan_sebelumnya(): void
    {
        $syaratValid = SyaratSurat::create(['ref_syarat_nama' => 'Valid']);

        $this->actingAs($this->user, 'admin')
            ->from(route('surat_mohon.index'))
            ->post(route('surat_mohon.delete_all'), [
                'id_cb' => [$syaratValid->ref_syarat_id, 'id_tidak_ada'],
            ])
            ->assertRedirect(route('surat_mohon.index'))
            ->assertSessionHas('error');

        // Replikasi behavior asli: id yang valid (diproses sebelum id
        // yang gagal) tetap terhapus walau keseluruhan proses "gagal".
        $this->assertDatabaseMissing('ref_syarat_surat', ['ref_syarat_id' => $syaratValid->ref_syarat_id]);
    }

    #[Test]
    public function destroy_all_menangani_array_kosong(): void
    {
        $this->actingAs($this->user, 'admin')
            ->from(route('surat_mohon.index'))
            ->post(route('surat_mohon.delete_all'), ['id_cb' => []])
            ->assertRedirect(route('surat_mohon.index'))
            ->assertSessionHas('success');
    }
}
```

### Sesudah — Unit Test Repository

```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Repositories;

use App\Models\SyaratSurat;
use App\Repositories\SyaratSuratRepository;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

class SyaratSuratRepositoryTest extends BaseTestCase
{
    use RefreshDatabase;

    private SyaratSuratRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new SyaratSuratRepository();
    }

    #[Test]
    public function query_with_format_surat_exist_mengembalikan_builder(): void
    {
        SyaratSurat::create(['ref_syarat_nama' => 'Syarat A']);
        SyaratSurat::create(['ref_syarat_nama' => 'Syarat B']);

        $query = $this->repository->queryWithFormatSuratExist();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $query);
        $this->assertGreaterThanOrEqual(2, $query->count());
    }

    #[Test]
    public function delete_with_format_surat_exist_menghapus_data_tanpa_relasi(): void
    {
        $syarat = SyaratSurat::create(['ref_syarat_nama' => 'Akan Dihapus']);

        $deleted = $this->repository->deleteWithFormatSuratExist($syarat->ref_syarat_id);

        $this->assertSame(1, $deleted);
        $this->assertDatabaseMissing('ref_syarat_surat', ['ref_syarat_id' => $syarat->ref_syarat_id]);
    }

    #[Test]
    public function delete_with_format_surat_exist_mengembalikan_nol_untuk_id_tidak_ada(): void
    {
        $deleted = $this->repository->deleteWithFormatSuratExist('id_tidak_ada');

        $this->assertSame(0, $deleted);
    }

    // Jika Model resource ini memakai trait ConfigId, tambahkan test berikut
    // (contoh pola, sesuaikan nama field config_id dengan resource nyata):
    //
    // #[Test]
    // public function query_tidak_mengembalikan_data_dari_config_id_lain(): void
    // {
    //     SyaratSurat::create(['ref_syarat_nama' => 'Milik Desa A', 'config_id' => 1]);
    //     SyaratSurat::create(['ref_syarat_nama' => 'Milik Desa B', 'config_id' => 2]);
    //
    //     // asumsikan test berjalan dalam konteks config_id = 1
    //     $result = $this->repository->queryWithFormatSuratExist()->get();
    //
    //     $this->assertTrue($result->every(fn ($row) => $row->config_id === 1));
    // }
}
```

### Sesudah — Unit Test Action

```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\SyaratSurat;

use App\Actions\SyaratSurat\BulkDeleteSyaratSuratAction;
use App\Models\SyaratSurat;
use App\Repositories\SyaratSuratRepository;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

class BulkDeleteSyaratSuratActionTest extends BaseTestCase
{
    use RefreshDatabase;

    private BulkDeleteSyaratSuratAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $repository = new SyaratSuratRepository();
        $this->action = new BulkDeleteSyaratSuratAction($repository);
    }

    #[Test]
    public function execute_menghapus_semua_id_yang_valid(): void
    {
        $syarat1 = SyaratSurat::create(['ref_syarat_nama' => 'Satu']);
        $syarat2 = SyaratSurat::create(['ref_syarat_nama' => 'Dua']);

        $result = $this->action->execute([$syarat1->ref_syarat_id, $syarat2->ref_syarat_id]);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('ref_syarat_surat', ['ref_syarat_id' => $syarat1->ref_syarat_id]);
        $this->assertDatabaseMissing('ref_syarat_surat', ['ref_syarat_id' => $syarat2->ref_syarat_id]);
    }

    #[Test]
    public function execute_mengembalikan_false_jika_ada_id_tidak_valid(): void
    {
        $result = $this->action->execute(['id_tidak_ada']);

        $this->assertFalse($result);
    }

    #[Test]
    public function execute_berhenti_di_id_gagal_tapi_id_sebelumnya_tetap_terhapus(): void
    {
        $syaratValid = SyaratSurat::create(['ref_syarat_nama' => 'Valid']);

        $result = $this->action->execute([$syaratValid->ref_syarat_id, 'id_tidak_ada']);

        $this->assertFalse($result);
        $this->assertDatabaseMissing('ref_syarat_surat', ['ref_syarat_id' => $syaratValid->ref_syarat_id]);
    }

    #[Test]
    public function execute_menangani_array_kosong(): void
    {
        $result = $this->action->execute([]);

        $this->assertTrue($result);
    }
}
```

---


## Contoh Referensi: Helper Custom OpenSID `ci_*()` → Laravel

Definisi asli (untuk referensi semantik — JANGAN dipertahankan/disalin ke
kode baru, ini yang harus diganti):

```php
if (! function_exists('ci_route')) {
    /**
     * Mengkonversi dot notation menjadi path URL dan mendukung parameter tambahan.
     */
    function ci_route($to = null, $params = null)
    {
        if (in_array($to, [null, '', '/'])) {
            return site_url();
        }

        if (strpos($to, 'index.php') !== false) {
            return site_url($to);
        }

        $to = str_replace('.', '/', $to);

        if ($params !== null) {
            if (is_array($params)) {
                $params = implode('/', $params);
            }
            $to .= '/' . $params;
        }

        return site_url($to);
    }
}
```

```php
if (! function_exists('ci_auth')) {
    /**
     * Ambil data user login
     */
    function ci_auth($params = null)
    {
        $CI = &get_instance();

        if (null !== $params) {
            return $CI->session->isAdmin->{$params};
        }

        return $CI->session->isAdmin;
    }
}
```

### Sebelum (controller/Blade masih pakai `ci_*()`)

```php
$formAction = ci_route('surat_mohon.update', $syaratSurat->ref_syarat_id);
$namaUser   = ci_auth('nama');
$userLogin  = ci_auth();
```

### Sesudah (Laravel native)

```php
// ci_route('surat_mohon.update', $id) — nama route sudah terdaftar di
// routes/admin.php, jadi tinggal panggil langsung dengan route().
$formAction = route('surat_mohon.update', $syaratSurat->ref_syarat_id);

// ci_auth('nama') dan ci_auth() — akses langsung via auth() facade.
$namaUser  = auth('admin')->user()->nama;
$userLogin = auth('admin')->user();
```

Catatan: kalau tujuan `ci_route()` di kode asli TIDAK punya route Laravel
bernama sama persis (mis. path lama yang belum sepenuhnya dimigrasi), jangan
memaksakan `route()` — pakai `url($path)` sebagai fallback sementara, dan
catat di ringkasan awal bahwa path tsb butuh route Laravel resmi.
