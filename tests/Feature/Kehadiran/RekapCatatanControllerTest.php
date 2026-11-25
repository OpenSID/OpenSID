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
use Modules\Kehadiran\Services\RekapCatatanService;
use Modules\Kehadiran\Enums\StatusCatatan;
use Carbon\Carbon;

class RekapCatatanControllerTest extends TestCase
{
    private RekapCatatanService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(RekapCatatanService::class);
    }

    /** @test */
    public function dapat_get_rekap_data_tanpa_filter()
    {
        CatatanHarian::factory()->createMany(5, [
            'config_id' => 1,
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $data = $this->service->getRekapData(
            configId: 1,
            bulan: null,
            tahun: null,
            pamongId: null
        );

        $this->assertCount(5, $data);
    }

    /** @test */
    public function dapat_filter_by_bulan()
    {
        CatatanHarian::factory()->createMany(3, [
            'config_id' => 1,
            'tanggal' => '2026-04-15',
        ]);
        CatatanHarian::factory()->createMany(2, [
            'config_id' => 1,
            'tanggal' => '2026-05-15',
        ]);

        $data = $this->service->getRekapData(
            configId: 1,
            bulan: '04',
            tahun: '2026',
            pamongId: null
        );

        $this->assertCount(3, $data);
    }

    /** @test */
    public function dapat_filter_by_tahun()
    {
        CatatanHarian::factory()->createMany(4, [
            'config_id' => 1,
            'tanggal' => '2026-04-15',
        ]);
        CatatanHarian::factory()->createMany(2, [
            'config_id' => 1,
            'tanggal' => '2025-04-15',
        ]);

        $data = $this->service->getRekapData(
            configId: 1,
            bulan: null,
            tahun: '2026',
            pamongId: null
        );

        $this->assertCount(4, $data);
    }

    /** @test */
    public function dapat_filter_by_pamong_id()
    {
        $pamong1Id = 1;
        $pamong2Id = 2;

        CatatanHarian::factory()->createMany(3, [
            'config_id' => 1,
            'pamong_id' => $pamong1Id,
        ]);
        CatatanHarian::factory()->createMany(2, [
            'config_id' => 1,
            'pamong_id' => $pamong2Id,
        ]);

        $data = $this->service->getRekapData(
            configId: 1,
            bulan: null,
            tahun: null,
            pamongId: $pamong1Id
        );

        $this->assertCount(3, $data);
    }

    /** @test */
    public function dapat_count_rekap_data()
    {
        CatatanHarian::factory()->createMany(7, [
            'config_id' => 1,
        ]);

        $count = $this->service->countRekapData(
            configId: 1,
            bulan: null,
            tahun: null,
            pamongId: null
        );

        $this->assertEquals(7, $count);
    }

    /** @test */
    public function dapat_generate_pdf_content_dengan_data()
    {
        CatatanHarian::factory()->createMany(5, [
            'config_id' => 1,
            'status' => StatusCatatan::DISETUJUI->value,
            'tanggal' => '2026-04-15',
        ]);

        $htmlContent = $this->service->generatePdfContent(
            configId: 1,
            bulan: '04',
            pamongId: null
        );

        $this->assertNotEmpty($htmlContent);
        $this->assertStringContainsString('Catatan Harian', $htmlContent);
    }

    /** @test */
    public function tidak_dapat_export_pdf_lebih_dari_5000_rows()
    {
        // Mock 6000 records
        for ($i = 0; $i < 6000; $i += 100) {
            CatatanHarian::factory()->createMany(100, [
                'config_id' => 1,
            ]);
        }
        CatatanHarian::factory()->createMany(1, ['config_id' => 1]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Terlalu banyak data');

        $this->service->getDataForPdfExport(
            configId: 1,
            bulan: null,
            tahun: null,
            pamongId: null
        );
    }

    /** @test */
    public function dapat_export_pdf_dengan_data_kurang_dari_5000_rows()
    {
        CatatanHarian::factory()->createMany(100, [
            'config_id' => 1,
        ]);

        $data = $this->service->getDataForPdfExport(
            configId: 1,
            bulan: null,
            tahun: null,
            pamongId: null
        );

        $this->assertCount(100, $data);
    }

    /** @test */
    public function dapat_generate_csv_content_dengan_data()
    {
        $catatan = CatatanHarian::factory()->create([
            'config_id' => 1,
            'uraian_kegiatan' => 'Test Activity',
            'tanggal' => '2026-04-15',
        ]);

        $csvContent = $this->service->generateCsvContent(
            configId: 1,
            bulan: '04',
            pamongId: null
        );

        $this->assertNotEmpty($csvContent);
        $this->assertStringContainsString('Test Activity', $csvContent);
    }

    /** @test */
    public function tidak_dapat_export_csv_lebih_dari_10000_rows()
    {
        // Mock 10001 records
        for ($i = 0; $i < 10010; $i += 100) {
            CatatanHarian::factory()->createMany(100, [
                'config_id' => 1,
            ]);
        }

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Terlalu banyak data');

        $this->service->getDataForCsvExport(
            configId: 1,
            bulan: null,
            tahun: null,
            pamongId: null
        );
    }

    /** @test */
    public function dapat_export_csv_dengan_data_kurang_dari_10000_rows()
    {
        CatatanHarian::factory()->createMany(500, [
            'config_id' => 1,
        ]);

        $data = $this->service->getDataForCsvExport(
            configId: 1,
            bulan: null,
            tahun: null,
            pamongId: null
        );

        $this->assertCount(500, $data);
    }

    /** @test */
    public function mendapatkan_nama_file_pdf_dengan_format_benar()
    {
        $filename = $this->service->getPdfFilename('04');

        $this->assertStringContainsString('April', $filename);
        $this->assertStringContainsString('.pdf', $filename);
    }

    /** @test */
    public function mendapatkan_nama_file_csv_dengan_format_benar()
    {
        $filename = $this->service->getCsvFilename('05');

        $this->assertStringContainsString('Mei', $filename);
        $this->assertStringContainsString('.csv', $filename);
    }

    /** @test */
    public function mendapatkan_nama_bulan_indonesia()
    {
        $this->assertEquals('Januari', $this->service->getBulanIndonesia(1));
        $this->assertEquals('Februari', $this->service->getBulanIndonesia(2));
        $this->assertEquals('Maret', $this->service->getBulanIndonesia(3));
        $this->assertEquals('April', $this->service->getBulanIndonesia(4));
        $this->assertEquals('Mei', $this->service->getBulanIndonesia(5));
        $this->assertEquals('Juni', $this->service->getBulanIndonesia(6));
        $this->assertEquals('Juli', $this->service->getBulanIndonesia(7));
        $this->assertEquals('Agustus', $this->service->getBulanIndonesia(8));
        $this->assertEquals('September', $this->service->getBulanIndonesia(9));
        $this->assertEquals('Oktober', $this->service->getBulanIndonesia(10));
        $this->assertEquals('November', $this->service->getBulanIndonesia(11));
        $this->assertEquals('Desember', $this->service->getBulanIndonesia(12));
    }

    /** @test */
    public function dapat_combine_multiple_filters()
    {
        $pamongId = 1;

        CatatanHarian::factory()->createMany(2, [
            'config_id' => 1,
            'tanggal' => '2026-04-15',
            'pamong_id' => $pamongId,
        ]);
        CatatanHarian::factory()->createMany(3, [
            'config_id' => 1,
            'tanggal' => '2026-05-15',
            'pamong_id' => $pamongId,
        ]);
        CatatanHarian::factory()->createMany(4, [
            'config_id' => 1,
            'tanggal' => '2026-04-15',
            'pamong_id' => 2,
        ]);

        $data = $this->service->getRekapData(
            configId: 1,
            bulan: '04',
            tahun: '2026',
            pamongId: $pamongId
        );

        $this->assertCount(2, $data);
    }

    /** @test */
    public function rekap_data_hanya_untuk_config_id_tertentu()
    {
        CatatanHarian::factory()->createMany(3, [
            'config_id' => 1,
        ]);
        CatatanHarian::factory()->createMany(5, [
            'config_id' => 2,
        ]);

        $data = $this->service->getRekapData(
            configId: 1,
            bulan: null,
            tahun: null,
            pamongId: null
        );

        $this->assertCount(3, $data);
    }

    /** @test */
    public function transform_untuk_table_mengubah_format_data()
    {
        $catatans = CatatanHarian::factory()->createMany(2, [
            'config_id' => 1,
        ]);

        $transformed = $this->service->transformForTable($catatans);

        $this->assertIsArray($transformed);
        $this->assertCount(2, $transformed);
        $this->assertArrayHasKey('id', $transformed[0]);
        $this->assertArrayHasKey('uraian_kegiatan', $transformed[0]);
    }

    /** @test */
    public function dapat_get_hari_indonesia()
    {
        $hari = $this->service->getHariIndonesia('Monday');
        
        $this->assertNotEmpty($hari);
        $this->assertStringContainsString('Senin', $hari);
    }

    /** @test */
    public function pdf_filename_menggunakan_bulan_indonesia()
    {
        $filename = $this->service->getPdfFilename('12');

        $this->assertStringContainsString('Desember', $filename);
        $this->assertNotContains('12', $filename);
    }

    /** @test */
    public function csv_filename_menggunakan_bulan_indonesia()
    {
        $filename = $this->service->getCsvFilename('01');

        $this->assertStringContainsString('Januari', $filename);
        $this->assertNotContains('01', $filename);
    }

    // ============================================
    // HTTP LAYER / CONTROLLER TESTS
    // ============================================

    /** @test */
    public function http_dapat_akses_halaman_rekap()
    {
        $response = $this->get(route('kehadiran_rekap_catatan.index'));

        $response->assertStatus(200);
        $response->assertViewIs('kehadiran::backend.rekap_catatan.index');
    }

    /** @test */
    public function http_datatables_rekap_menampilkan_approved_catatans()
    {
        CatatanHarian::factory()->createMany(5, [
            'config_id' => 1,
            'status' => StatusCatatan::DISETUJUI->value,
        ]);
        CatatanHarian::factory()->createMany(3, [
            'config_id' => 1,
            'status' => StatusCatatan::MENUNGGU->value,
        ]);

        $response = $this->getJson(route('kehadiran_rekap_catatan.datatables'));

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(5, $data);
    }

    /** @test */
    public function http_datatables_rekap_dengan_filter_bulan()
    {
        CatatanHarian::factory()->createMany(3, [
            'config_id' => 1,
            'tanggal' => '2026-04-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);
        CatatanHarian::factory()->createMany(2, [
            'config_id' => 1,
            'tanggal' => '2026-05-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $response = $this->getJson(route('kehadiran_rekap_catatan.datatables'), [
            'bulan' => '04',
            'tahun' => '2026',
        ]);

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(3, $data);
    }

    /** @test */
    public function http_datatables_rekap_dengan_filter_pamong()
    {
        $pamongId = 1;

        CatatanHarian::factory()->createMany(3, [
            'config_id' => 1,
            'pamong_id' => $pamongId,
            'status' => StatusCatatan::DISETUJUI->value,
        ]);
        CatatanHarian::factory()->createMany(2, [
            'config_id' => 1,
            'pamong_id' => 2,
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $response = $this->getJson(route('kehadiran_rekap_catatan.datatables'), [
            'pamong_id' => $pamongId,
        ]);

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(3, $data);
    }

    /** @test */
    public function http_cetak_pdf_dengan_data_valid()
    {
        CatatanHarian::factory()->createMany(5, [
            'config_id' => 1,
            'tanggal' => '2026-04-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $response = $this->get(route('kehadiran_rekap_catatan.cetak'), [
            'bulan' => '04',
            'tahun' => '2026',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /** @test */
    public function http_cetak_pdf_lebih_dari_5000_rows()
    {
        // Create many records
        for ($i = 0; $i < 51; $i++) {
            CatatanHarian::factory()->createMany(100, [
                'config_id' => 1,
                'status' => StatusCatatan::DISETUJUI->value,
            ]);
        }

        $response = $this->get(route('kehadiran_rekap_catatan.cetak'), [
            'bulan' => '04',
        ]);

        $response->assertStatus(500);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function http_ekspor_csv_dengan_data_valid()
    {
        CatatanHarian::factory()->createMany(10, [
            'config_id' => 1,
            'tanggal' => '2026-04-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $response = $this->get(route('kehadiran_rekap_catatan.ekspor'), [
            'bulan' => '04',
            'tahun' => '2026',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv');
        $response->assertHeader('content-disposition');
    }

    /** @test */
    public function http_ekspor_csv_lebih_dari_10000_rows()
    {
        // Create many records (10001)
        for ($i = 0; $i < 101; $i++) {
            CatatanHarian::factory()->createMany(100, [
                'config_id' => 1,
                'status' => StatusCatatan::DISETUJUI->value,
            ]);
        }

        $response = $this->get(route('kehadiran_rekap_catatan.ekspor'), [
            'bulan' => '04',
        ]);

        $response->assertStatus(500);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function http_cetak_pdf_hanya_config_sendiri()
    {
        CatatanHarian::factory()->createMany(5, [
            'config_id' => 1,
            'tanggal' => '2026-04-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);
        CatatanHarian::factory()->createMany(3, [
            'config_id' => 2,
            'tanggal' => '2026-04-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $response = $this->get(route('kehadiran_rekap_catatan.cetak'), [
            'bulan' => '04',
            'tahun' => '2026',
        ]);

        $response->assertStatus(200);
        // Verify only 5 records from config 1
    }

    /** @test */
    public function http_ekspor_csv_dengan_filter_multiple()
    {
        $pamongId = 1;

        CatatanHarian::factory()->createMany(3, [
            'config_id' => 1,
            'pamong_id' => $pamongId,
            'tanggal' => '2026-04-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);
        CatatanHarian::factory()->createMany(2, [
            'config_id' => 1,
            'pamong_id' => 2,
            'tanggal' => '2026-04-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $response = $this->get(route('kehadiran_rekap_catatan.ekspor'), [
            'bulan' => '04',
            'tahun' => '2026',
            'pamong_id' => $pamongId,
        ]);

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv');
    }

    /** @test */
    public function http_cetak_pdf_tanpa_filter()
    {
        CatatanHarian::factory()->createMany(5, [
            'config_id' => 1,
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $response = $this->get(route('kehadiran_rekap_catatan.cetak'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /** @test */
    public function http_csv_filename_sesuai_format()
    {
        CatatanHarian::factory()->createMany(3, [
            'config_id' => 1,
            'tanggal' => '2026-04-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $response = $this->get(route('kehadiran_rekap_catatan.ekspor'), [
            'bulan' => '04',
        ]);

        $response->assertStatus(200);
        $disposition = $response->headers->get('content-disposition');
        
        $this->assertStringContainsString('April', $disposition);
        $this->assertStringContainsString('.csv', $disposition);
    }

    /** @test */
    public function http_pdf_filename_sesuai_format()
    {
        CatatanHarian::factory()->createMany(3, [
            'config_id' => 1,
            'tanggal' => '2026-04-15',
            'status' => StatusCatatan::DISETUJUI->value,
        ]);

        $response = $this->get(route('kehadiran_rekap_catatan.cetak'), [
            'bulan' => '04',
        ]);

        $response->assertStatus(200);
        // PDF header should be set
        $this->assertTrue($response->headers->has('content-disposition'));
    }
}
