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
use Modules\Kehadiran\Services\ApprovalCatatanService;
use Modules\Kehadiran\Enums\StatusCatatan;
use App\Models\User;

class ApprovalCatatanControllerTest extends TestCase
{
    private ApprovalCatatanService $service;
    private ?User $approver = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ApprovalCatatanService::class);
        $this->setupApprover();
    }

    /**
     * Setup approver user for HTTP tests
     */
    private function setupApprover(): void
    {
        $this->approver = User::factory()->create([
            'status' => 1,
        ]);
    }

    /** @test */
    public function dapat_approve_catatan_menunggu()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        $this->service->approveCatatan($catatan, 1);

        $catatan->refresh();
        $this->assertEquals(StatusCatatan::DISETUJUI->value, $catatan->status);
        $this->assertEquals(1, $catatan->approved_by);
        $this->assertNotNull($catatan->approved_at);
    }

    /** @test */
    public function tidak_dapat_approve_catatan_draft()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::DRAFT->value,
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->service->approveCatatan($catatan, 1);
    }

    /** @test */
    public function tidak_dapat_approve_catatan_sudah_disetujui()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::DISETUJUI->value,
            'approved_by' => 1,
            'approved_at' => now(),
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->service->approveCatatan($catatan, 2);
    }

    /** @test */
    public function dapat_reject_catatan_menunggu_dengan_alasan()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
        ]);

        $alasan = 'Kegiatan tidak sesuai dengan rencana kerja';
        $this->service->rejectCatatan($catatan, $alasan, 1);

        $catatan->refresh();
        $this->assertEquals(StatusCatatan::DITOLAK->value, $catatan->status);
        $this->assertEquals($alasan, $catatan->alasan_penolakan);
    }

    /** @test */
    public function tidak_dapat_reject_dengan_alasan_terlalu_pendek()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->service->rejectCatatan($catatan, 'Bad', 1);
    }

    /** @test */
    public function tidak_dapat_reject_dengan_alasan_terlalu_panjang()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
        ]);

        $alasan = str_repeat('a', 1001);

        $this->expectException(\InvalidArgumentException::class);
        $this->service->rejectCatatan($catatan, $alasan, 1);
    }

    /** @test */
    public function tidak_dapat_reject_catatan_draft()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::DRAFT->value,
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->service->rejectCatatan($catatan, 'Not suitable', 1);
    }

    /** @test */
    public function dapat_bulk_approve_multiple_catatans()
    {
        $catatans = CatatanHarian::factory()->createMany(3, [
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);

        $ids = $catatans->pluck('id')->all();
        $updated = $this->service->bulkApproveCatatan($ids, 1);

        $this->assertEquals(3, $updated);

        foreach ($catatans as $catatan) {
            $catatan->refresh();
            $this->assertEquals(StatusCatatan::DISETUJUI->value, $catatan->status);
            $this->assertEquals(1, $catatan->approved_by);
        }
    }

    /** @test */
    public function bulk_approve_mengabaikan_catatan_tidak_menunggu()
    {
        $menunggu = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);
        $disetujui = CatatanHarian::factory()->create([
            'status' => StatusCatatan::DISETUJUI->value,
            'config_id' => 1,
        ]);

        $ids = [$menunggu->id, $disetujui->id];
        $updated = $this->service->bulkApproveCatatan($ids, 1);

        $this->assertEquals(1, $updated);

        $menunggu->refresh();
        $disetujui->refresh();

        $this->assertEquals(StatusCatatan::DISETUJUI->value, $menunggu->status);
        $this->assertEquals(StatusCatatan::DISETUJUI->value, $disetujui->status);
    }

    /** @test */
    public function bulk_approve_hanya_untuk_config_id_yang_sama()
    {
        $catatan1 = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);
        $catatan2 = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 2,
        ]);

        $ids = [$catatan1->id, $catatan2->id];
        $updated = $this->service->bulkApproveCatatan($ids, 1);

        $this->assertEquals(1, $updated);

        $catatan1->refresh();
        $catatan2->refresh();

        $this->assertEquals(StatusCatatan::DISETUJUI->value, $catatan1->status);
        $this->assertEquals(StatusCatatan::MENUNGGU->value, $catatan2->status);
    }

    /** @test */
    public function dapat_get_approval_stats()
    {
        CatatanHarian::factory()->createMany(2, [
            'status' => StatusCatatan::DRAFT->value,
            'config_id' => 1,
        ]);
        CatatanHarian::factory()->createMany(3, [
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);
        CatatanHarian::factory()->createMany(1, [
            'status' => StatusCatatan::DISETUJUI->value,
            'config_id' => 1,
        ]);
        CatatanHarian::factory()->createMany(1, [
            'status' => StatusCatatan::DITOLAK->value,
            'config_id' => 1,
        ]);

        $stats = $this->service->getApprovalStats();

        $this->assertEquals(2, $stats['draft']);
        $this->assertEquals(3, $stats['menunggu']);
        $this->assertEquals(1, $stats['disetujui']);
        $this->assertEquals(1, $stats['ditolak']);
    }

    /** @test */
    public function approval_stats_only_for_specific_config()
    {
        CatatanHarian::factory()->createMany(3, [
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);
        CatatanHarian::factory()->createMany(5, [
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 2,
        ]);

        $stats = $this->service->getApprovalStats();

        $this->assertEquals(3, $stats['menunggu']);
        $this->assertEquals(0, $stats['disetujui']);
    }

    /** @test */
    public function approved_catatan_menyimpan_approved_by_dan_approved_at()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
        ]);

        $pamongId = 5;
        $this->service->approveCatatan($catatan, $pamongId);

        $catatan->refresh();

        $this->assertEquals($pamongId, $catatan->approved_by);
        $this->assertNotNull($catatan->approved_at);
        $this->assertTrue($catatan->approved_at->isSameDay(now()));
    }

    /** @test */
    public function rejected_catatan_dapat_di_approve_kembali()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::DITOLAK->value,
            'alasan_penolakan' => 'Kegiatan tidak sesuai',
        ]);

        // Ubah status ke MENUNGGU (diperlukan perubahan status manual)
        $catatan->update(['status' => StatusCatatan::MENUNGGU->value]);

        $this->service->approveCatatan($catatan, 1);

        $catatan->refresh();

        $this->assertEquals(StatusCatatan::DISETUJUI->value, $catatan->status);
        $this->assertNotNull($catatan->approved_at);
    }

    // ============================================
    // HTTP LAYER / CONTROLLER TESTS
    // ============================================

    /** @test */
    public function http_approver_dapat_akses_halaman_approval()
    {
        $response = $this->get(route('kehadiran_approval_catatan.index'));

        $response->assertStatus(200);
        $response->assertViewIs('kehadiran::backend.approval_catatan.index');
    }

    /** @test */
    public function http_datatables_menampilkan_catatan_menunggu()
    {
        $menunggu = CatatanHarian::factory()->createMany(3, [
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);
        CatatanHarian::factory()->createMany(2, [
            'status' => StatusCatatan::DISETUJUI->value,
            'config_id' => 1,
        ]);

        $response = $this->getJson(route('kehadiran_approval_catatan.datatables'));

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(3, $data);
    }

    /** @test */
    public function http_dapat_lihat_detail_catatan_menunggu()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
            'uraian_kegiatan' => 'Test Activity Detail',
        ]);

        $response = $this->get(route('kehadiran_approval_catatan.detail', $catatan->id));

        $response->assertStatus(200);
        $response->assertViewIs('kehadiran::backend.approval_catatan.detail');
        $response->assertViewHas('catatan');
        $this->assertEquals('Test Activity Detail', $response->original->getData()['catatan']->uraian_kegiatan);
    }

    /** @test */
    public function http_tidak_dapat_lihat_detail_catatan_dari_config_berbeda()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 2,
        ]);

        $response = $this->get(route('kehadiran_approval_catatan.detail', $catatan->id));

        $response->assertStatus(404);
    }

    /** @test */
    public function http_approve_mengubah_status_dan_redirect()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);

        $response = $this->post(route('kehadiran_approval_catatan.approve', $catatan->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Catatan berhasil disetujui');

        $catatan->refresh();
        $this->assertEquals(StatusCatatan::DISETUJUI->value, $catatan->status);
        $this->assertNotNull($catatan->approved_at);
    }

    /** @test */
    public function http_tidak_dapat_approve_catatan_sudah_disetujui()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::DISETUJUI->value,
            'config_id' => 1,
        ]);

        $response = $this->post(route('kehadiran_approval_catatan.approve', $catatan->id));

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function http_reject_dengan_alasan_valid()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);

        $alasan = 'Kegiatan belum sesuai dengan rencana kerja yang telah disepakati';

        $response = $this->post(route('kehadiran_approval_catatan.reject', $catatan->id), [
            'alasan_penolakan' => $alasan,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Catatan berhasil ditolak');

        $catatan->refresh();
        $this->assertEquals(StatusCatatan::DITOLAK->value, $catatan->status);
        $this->assertEquals($alasan, $catatan->alasan_penolakan);
    }

    /** @test */
    public function http_reject_dengan_alasan_terlalu_pendek()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);

        $response = $this->post(route('kehadiran_approval_catatan.reject', $catatan->id), [
            'alasan_penolakan' => 'Bad',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();

        $catatan->refresh();
        $this->assertEquals(StatusCatatan::MENUNGGU->value, $catatan->status);
    }

    /** @test */
    public function http_reject_dengan_alasan_terlalu_panjang()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);

        $alasan = str_repeat('a', 1001);

        $response = $this->post(route('kehadiran_approval_catatan.reject', $catatan->id), [
            'alasan_penolakan' => $alasan,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function http_bulk_approve_multiple_catatans()
    {
        $menunggu1 = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);
        $menunggu2 = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);
        $menunggu3 = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);

        $response = $this->post(route('kehadiran_approval_catatan.bulk_approve'), [
            'id_cb' => [$menunggu1->id, $menunggu2->id, $menunggu3->id],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Sebanyak 3 catatan berhasil disetujui');

        foreach ([$menunggu1, $menunggu2, $menunggu3] as $catatan) {
            $catatan->refresh();
            $this->assertEquals(StatusCatatan::DISETUJUI->value, $catatan->status);
        }
    }

    /** @test */
    public function http_bulk_approve_mengabaikan_yang_tidak_menunggu()
    {
        $menunggu1 = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);
        $menunggu2 = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);
        $disetujui = CatatanHarian::factory()->create([
            'status' => StatusCatatan::DISETUJUI->value,
            'config_id' => 1,
        ]);

        $response = $this->post(route('kehadiran_approval_catatan.bulk_approve'), [
            'id_cb' => [$menunggu1->id, $menunggu2->id, $disetujui->id],
        ]);

        $response->assertSessionHas('success', 'Sebanyak 2 catatan berhasil disetujui');

        $menunggu1->refresh();
        $menunggu2->refresh();
        $disetujui->refresh();

        $this->assertEquals(StatusCatatan::DISETUJUI->value, $menunggu1->status);
        $this->assertEquals(StatusCatatan::DISETUJUI->value, $menunggu2->status);
        $this->assertEquals(StatusCatatan::DISETUJUI->value, $disetujui->status); // Tetap disetujui
    }

    /** @test */
    public function http_bulk_approve_hanya_untuk_config_sama()
    {
        $config1 = CatatanHarian::factory()->createMany(2, [
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);
        $config2 = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 2,
        ]);

        $ids = array_merge(
            $config1->pluck('id')->all(),
            [$config2->id]
        );

        $response = $this->post(route('kehadiran_approval_catatan.bulk_approve'), [
            'id_cb' => $ids,
        ]);

        $response->assertSessionHas('success', 'Sebanyak 2 catatan berhasil disetujui');

        foreach ($config1 as $catatan) {
            $catatan->refresh();
            $this->assertEquals(StatusCatatan::DISETUJUI->value, $catatan->status);
        }

        $config2->refresh();
        $this->assertEquals(StatusCatatan::MENUNGGU->value, $config2->status);
    }

    /** @test */
    public function http_tidak_dapat_approve_catatan_orang_lain()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 2, // Different config
        ]);

        $response = $this->post(route('kehadiran_approval_catatan.approve', $catatan->id));

        $response->assertStatus(404);
        $catatan->refresh();
        $this->assertEquals(StatusCatatan::MENUNGGU->value, $catatan->status);
    }

    /** @test */
    public function http_approval_menyimpan_approved_by()
    {
        $catatan = CatatanHarian::factory()->create([
            'status' => StatusCatatan::MENUNGGU->value,
            'config_id' => 1,
        ]);

        $this->post(route('kehadiran_approval_catatan.approve', $catatan->id));

        $catatan->refresh();

        $this->assertNotNull($catatan->approved_by);
        $this->assertNotNull($catatan->approved_at);
        $this->assertTrue($catatan->approved_at->isSameDay(now()));
    }
}
