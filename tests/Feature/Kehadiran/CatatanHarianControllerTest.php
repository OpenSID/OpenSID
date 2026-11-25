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

namespace Tests\Feature\Kehadiran;

use Tests\TestCase;
use Modules\Kehadiran\Models\CatatanHarian;
use Modules\Kehadiran\Enums\StatusCatatan;
use Modules\Kehadiran\Services\CatatanHarianService;
use App\Models\User;
use Carbon\Carbon;

class CatatanHarianControllerTest extends TestCase
{
    private CatatanHarianService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CatatanHarianService::class);
    }

    /** @test */
    public function dapat_membuat_catatan_harian_baru()
    {
        $catatan = CatatanHarian::factory()->make([
            'status' => StatusCatatan::DRAFT->value,
        ]);

        $this->assertDatabaseMissing('kehadiran_catatan_harian', [
            'uraian_kegiatan' => $catatan->uraian_kegiatan,
        ]);

        $created = $this->service->createCatatan([
            'tanggal' => $catatan->tanggal,
            'uraian_kegiatan' => $catatan->uraian_kegiatan,
            'lokasi_kegiatan' => $catatan->lokasi_kegiatan,
            'hasil_diharapkan' => $catatan->hasil_diharapkan,
            'hari' => $catatan->hari,
            'config_id' => $catatan->config_id,
            'pamong_id' => $catatan->pamong_id,
            'status' => StatusCatatan::DRAFT->value,
        ], []);

        $this->assertDatabaseHas('kehadiran_catatan_harian', [
            'uraian_kegiatan' => $catatan->uraian_kegiatan,
            'status' => StatusCatatan::DRAFT->value,
        ]);
    }

    /** @test */
    public function dapat_update_catatan_draft()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::DRAFT->value,
        ]);

        $updated = $this->service->updateCatatan($catatan, [
            'uraian_kegiatan' => 'Kegiatan Updated',
            'tanggal' => $catatan->tanggal->format('Y-m-d'),
            'hari' => $catatan->hari,
            'lokasi_kegiatan' => 'Updated Location',
            'hasil_diharapkan' => 'Updated Result',
        ], []);

        $this->assertTrue($updated);
        $this->assertDatabaseHas('kehadiran_catatan_harian', [
            'id' => $catatan->id,
            'uraian_kegiatan' => 'Kegiatan Updated',
        ]);
    }

    /** @test */
    public function tidak_dapat_update_catatan_yang_sudah_disetujui()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $originalUraian = $catatan->uraian_kegiatan;

        $this->service->updateCatatan($catatan, [
            'uraian_kegiatan' => 'Kegiatan Updated',
            'tanggal' => $catatan->tanggal,
            'hari' => $catatan->hari,
            'lokasi_kegiatan' => 'Updated Location',
            'hasil_diharapkan' => 'Updated Result',
        ], []);

        $catatan->refresh();
        $this->assertEquals($originalUraian, $catatan->uraian_kegiatan);
    }

    /** @test */
    public function dapat_delete_catatan_draft()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::DRAFT->value,
        ]);

        $id = $catatan->id;

        $deleted = $this->service->deleteCatatan($catatan);

        $this->assertTrue($deleted);
        $this->assertSoftDeleted('kehadiran_catatan_harian', ['id' => $id]);
    }

    /** @test */
    public function tidak_bisa_delete_catatan_yang_disetujui()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $this->expectException(\Exception::class);
        $this->service->deleteCatatan($catatan);
    }

    /** @test */
    public function catatan_memiliki_relasi_dengan_pamong()
    {
        $catatan = CatatanHarian::factory()->create();

        $this->assertNotNull($catatan->pamong);
        $this->assertNotNull($catatan->pamong->penduduk);
    }

    /** @test */
    public function catatan_memiliki_relasi_dengan_fotos()
    {
        $catatan = CatatanHarian::factory()->create();
        $catatan->fotos()->createMany([
            ['file_path' => 'path/to/photo1.jpg', 'config_id' => $catatan->config_id],
            ['file_path' => 'path/to/photo2.jpg', 'config_id' => $catatan->config_id],
        ]);

        $this->assertCount(2, $catatan->fotos);
    }

    /** @test */
    public function foto_dihapus_saat_catatan_dihapus()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::DRAFT->value,
        ]);

        $catatan->fotos()->createMany([
            ['file_path' => 'path/to/photo1.jpg', 'config_id' => $catatan->config_id],
            ['file_path' => 'path/to/photo2.jpg', 'config_id' => $catatan->config_id],
        ]);

        $this->assertCount(2, $catatan->fotos);

        $this->service->deleteCatatan($catatan);

        $this->assertDatabaseMissing('kehadiran_catatan_harian_foto', [
            'catatan_id' => $catatan->id,
        ]);
    }

    /** @test */
    public function catatan_memiliki_scope_byMonth()
    {
        $bulan = '2026-04';
        CatatanHarian::factory()->createMany(3, ['tanggal' => '2026-04-15']);
        CatatanHarian::factory()->createMany(2, ['tanggal' => '2026-05-15']);

        $catatans = CatatanHarian::byMonth($bulan)->get();

        $this->assertCount(3, $catatans);
    }

    /** @test */
    public function catatan_memiliki_scope_byYear()
    {
        CatatanHarian::factory()->createMany(4, ['tanggal' => '2026-04-15']);
        CatatanHarian::factory()->createMany(2, ['tanggal' => '2025-04-15']);

        $catatans = CatatanHarian::byYear('2026')->get();

        $this->assertCount(4, $catatans);
    }

    /** @test */
    public function status_enum_memiliki_label_dan_warna()
    {
        $this->assertEquals('Draft', StatusCatatan::DRAFT->label());
        $this->assertEquals('Menunggu Persetujuan', StatusCatatan::MENUNGGU->label());
        $this->assertEquals('Disetujui', StatusCatatan::DISETUJUI->label());
        $this->assertEquals('Ditolak', StatusCatatan::DITOLAK->label());

        $this->assertNotEmpty(StatusCatatan::DRAFT->badgeColor());
        $this->assertNotEmpty(StatusCatatan::MENUNGGU->badgeColor());
        $this->assertNotEmpty(StatusCatatan::DISETUJUI->badgeColor());
        $this->assertNotEmpty(StatusCatatan::DITOLAK->badgeColor());
    }

    /** @test */
    public function catatan_harian_menggunakan_soft_deletes()
    {
        $catatan = CatatanHarian::factory()->create();

        $catatan->delete();

        $this->assertSoftDeleted($catatan);
    }

    /** @test */
    public function catatan_dengan_konfigurasi_berbeda_terpisah()
    {
        $catatan1 = CatatanHarian::factory()->create(['config_id' => 1]);
        $catatan2 = CatatanHarian::factory()->create(['config_id' => 2]);

        $catatans = CatatanHarian::where('config_id', 1)->get();

        $this->assertCount(1, $catatans);
        $this->assertEquals(1, $catatans->first()->config_id);
    }

    // ============================================
    // HTTP LAYER / CONTROLLER TESTS
    // ============================================

    /** @test */
    public function http_pamong_dapat_akses_index()
    {
        $response = $this->get(route('kehadiran_catatan_harian.index'));

        $response->assertStatus(200);
        $response->assertViewIs('kehadiran::backend.catatan_harian.index');
    }

    /** @test */
    public function http_datatables_menampilkan_data_berdasarkan_config()
    {
        CatatanHarian::factory()->createMany(3, ['config_id' => 1]);
        CatatanHarian::factory()->createMany(2, ['config_id' => 2]);

        $response = $this->getJson(route('kehadiran_catatan_harian.datatables'));

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(3, $data);
    }

    /** @test */
    public function http_dapat_akses_form_create()
    {
        $response = $this->get(route('kehadiran_catatan_harian.form'));

        $response->assertStatus(200);
        $response->assertViewIs('kehadiran::backend.catatan_harian.form');
    }

    /** @test */
    public function http_dapat_akses_form_edit()
    {
        $catatan = CatatanHarian::factory()->create([
            'config_id' => 1,
            'status' => StatusCatatan::DRAFT->value,
        ]);

        $response = $this->get(route('kehadiran_catatan_harian.form', $catatan->id));

        $response->assertStatus(200);
        $response->assertViewHas('catatan');
    }

    /** @test */
    public function http_tidak_dapat_akses_form_edit_orang_lain()
    {
        $catatan = CatatanHarian::factory()->create([
            'config_id' => 2,
            'status' => StatusCatatan::DRAFT->value,
        ]);

        $response = $this->get(route('kehadiran_catatan_harian.form', $catatan->id));

        $response->assertStatus(404);
    }

    /** @test */
    public function http_create_catatan_harian_baru()
    {
        $response = $this->post(route('kehadiran_catatan_harian.create'), [
            'tanggal' => '2026-04-15',
            'hari' => 'Monday',
            'uraian_kegiatan' => 'Sosialisasi pembangunan desa',
            'hasil_diharapkan' => 'Masyarakat memahami program',
            'lokasi_kegiatan' => 'Kantor desa',
            'catatan_kerja' => 'Test note',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('kehadiran_catatan_harian', [
            'uraian_kegiatan' => 'Sosialisasi pembangunan desa',
            'status' => StatusCatatan::DRAFT->value,
            'config_id' => 1,
        ]);
    }

    /** @test */
    public function http_update_catatan_draft()
    {
        $catatan = CatatanHarian::factory()->create([
            'config_id' => 1,
            'status' => StatusCatatan::DRAFT->value,
            'uraian_kegiatan' => 'Original activity',
        ]);

        $response = $this->post(route('kehadiran_catatan_harian.update', $catatan->id), [
            'tanggal' => $catatan->tanggal->format('Y-m-d'),
            'hari' => $catatan->hari,
            'uraian_kegiatan' => 'Updated activity',
            'hasil_diharapkan' => 'Updated result',
            'lokasi_kegiatan' => 'Updated location',
            'catatan_kerja' => 'Updated note',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('kehadiran_catatan_harian', [
            'id' => $catatan->id,
            'uraian_kegiatan' => 'Updated activity',
        ]);
    }

    /** @test */
    public function http_tidak_dapat_update_catatan_disetujui()
    {
        $catatan = CatatanHarian::factory()->create([
            'config_id' => 1,
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $originalUraian = $catatan->uraian_kegiatan;

        $response = $this->post(route('kehadiran_catatan_harian.update', $catatan->id), [
            'tanggal' => $catatan->tanggal->format('Y-m-d'),
            'hari' => $catatan->hari,
            'uraian_kegiatan' => 'UPDATED',
            'hasil_diharapkan' => 'Updated result',
            'lokasi_kegiatan' => 'Updated location',
        ]);

        $catatan->refresh();
        $this->assertEquals($originalUraian, $catatan->uraian_kegiatan);
    }

    /** @test */
    public function http_dapat_lihat_detail_catatan()
    {
        $catatan = CatatanHarian::factory()->create([
            'config_id' => 1,
            'uraian_kegiatan' => 'Test Detail Activity',
        ]);

        $response = $this->get(route('kehadiran_catatan_harian.detail', $catatan->id));

        $response->assertStatus(200);
        $response->assertViewIs('kehadiran::backend.catatan_harian.detail');
        $this->assertEquals('Test Detail Activity', $response->original->getData()['catatan']->uraian_kegiatan);
    }

    /** @test */
    public function http_tidak_dapat_lihat_detail_catatan_orang_lain()
    {
        $catatan = CatatanHarian::factory()->create([
            'config_id' => 2,
        ]);

        $response = $this->get(route('kehadiran_catatan_harian.detail', $catatan->id));

        $response->assertStatus(404);
    }

    /** @test */
    public function http_delete_catatan_draft()
    {
        $catatan = CatatanHarian::factory()->create([
            'config_id' => 1,
            'status' => StatusCatatan::DRAFT->value,
        ]);

        $response = $this->post(route('kehadiran_catatan_harian.delete', $catatan->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('kehadiran_catatan_harian', ['id' => $catatan->id]);
    }

    /** @test */
    public function http_tidak_dapat_delete_catatan_disetujui()
    {
        $catatan = CatatanHarian::factory()->create([
            'config_id' => 1,
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $response = $this->post(route('kehadiran_catatan_harian.delete', $catatan->id));

        $response->assertRedirect();
        $response->assertSessionHasErrors();

        $catatan->refresh();
        $this->assertNull($catatan->deleted_at);
    }

    /** @test */
    public function http_tidak_dapat_delete_catatan_orang_lain()
    {
        $catatan = CatatanHarian::factory()->create([
            'config_id' => 2,
            'status' => StatusCatatan::DRAFT->value,
        ]);

        $response = $this->post(route('kehadiran_catatan_harian.delete', $catatan->id));

        $response->assertStatus(404);
        $catatan->refresh();
        $this->assertNull($catatan->deleted_at);
    }

    /** @test */
    public function http_create_dengan_validasi_required_fields()
    {
        $response = $this->post(route('kehadiran_catatan_harian.create'), [
            // Missing required fields
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function http_datatables_ajax_request_only()
    {
        $response = $this->post(route('kehadiran_catatan_harian.datatables'));

        // Should return 404 if not ajax request
        $response->assertStatus(404);
    }
}
