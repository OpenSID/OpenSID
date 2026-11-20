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
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Feature\Services;

use App\Services\ArsipFisikSurat;
use Database\Factories\SuratMasukFactory;
use Exception;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Fase 6 (lanjutan 🟢) — `ArsipFisikSurat` (service arsip fisik surat). Bersih dari CI3.
 */
class ArsipFisikSuratTest extends BaseTestCase
{
    use RefreshDatabase;

    private ArsipFisikSurat $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ArsipFisikSurat();
    }

    public function test_arsip_desa_query_mengembalikan_builder(): void
    {
        $query = $this->service->arsipDesaQuery();

        $this->assertInstanceOf(QueryBuilder::class, $query);
        $this->assertIsInt($query->count());
    }

    public function test_total_data_tiap_kategori(): void
    {
        foreach (['dokumen_desa', 'surat_masuk', 'surat_keluar', 'kependudukan', 'layanan_surat'] as $kategori) {
            $total = $this->service->totalData($kategori);
            $this->assertIsInt($total, "totalData({$kategori}) harus int");
            $this->assertGreaterThanOrEqual(0, $total);
        }
    }

    public function test_total_data_kategori_tidak_dikenal_melempar(): void
    {
        $this->expectException(Exception::class);
        $this->service->totalData('kategori-ngawur');
    }

    public function test_semua_filter(): void
    {
        $filter = $this->service->semuaFilter();

        $this->assertArrayHasKey('jenis', $filter);
        $this->assertArrayHasKey('tahun', $filter);

        // lima jenis dasar selalu ada
        $this->assertSame('Surat Masuk', $filter['jenis']['2-1']);
        $this->assertSame('Peraturan Desa', $filter['jenis']['1-3']);

        // tahun: range 2015..sekarang, urut menurun
        $this->assertContains((int) date('Y'), $filter['tahun']);
        $this->assertSame((int) date('Y'), $filter['tahun'][0]);
        $this->assertContains(2015, $filter['tahun']);
    }

    public function test_update_dan_baca_lokasi_arsip(): void
    {
        $surat = SuratMasukFactory::new()->create([
            'berkas_scan'  => 'scan_arsip.pdf',
            'lokasi_arsip' => 'Rak Lama',
        ]);

        $this->service->updateLokasi('surat_masuk', $surat->id, 'Rak A-1');

        $this->assertSame('Rak A-1', $this->service->getLokasiArsip('surat_masuk', $surat->id));
        $this->assertSame('scan_arsip.pdf', $this->service->getNamaBerkas('surat_masuk', $surat->id));
    }

    public function test_get_model_table_tidak_dikenal_melempar(): void
    {
        $this->expectException(Exception::class);
        $this->service->getLokasiArsip('tabel-ngawur', 1);
    }
}
