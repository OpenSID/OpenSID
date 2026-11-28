<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\SyaratSurat;
use App\Models\User;
use Database\Factories\DatabaseNotificationFactory;
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
    public function index_menampilkan_notifikasi_saat_ada_data(): void
    {
        $notifikasi = DatabaseNotificationFactory::new()
            ->forUser($this->user)
            ->belumDibaca()
            ->state(['data' => [
                'category' => 'surat',
                'title' => 'Permohonan Surat Baru',
                'message' => 'Ada permohonan surat baru dari penduduk',
                'icon' => 'fa-print',
                'color' => '#3498db',
            ]])
            ->create();

        $this->actingAs($this->user, 'admin')
            ->get(route('surat_mohon.index'))
            ->assertStatus(200)
            ->assertSee('Permohonan Surat Baru')
            ->assertSee('Ada permohonan surat baru dari penduduk')
            ->assertSee(url("notifikasi/show/{$notifikasi->id}"));
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
            ->get(route('surat_mohon.delete_all').'?id_cb[]='.$syarat1->ref_syarat_id.'&id_cb[]='.$syarat2->ref_syarat_id)
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
            ->get(route('surat_mohon.delete_all').'?id_cb[]=id_tidak_ada')
            ->assertRedirect(route('surat_mohon.index'))
            ->assertSessionHas('error');
    }

    #[Test]
    public function destroy_all_menangani_array_kosong(): void
    {
        $this->actingAs($this->user, 'admin')
            ->from(route('surat_mohon.index'))
            ->get(route('surat_mohon.delete_all'))
            ->assertRedirect(route('surat_mohon.index'))
            ->assertSessionHas('success');
    }
}
