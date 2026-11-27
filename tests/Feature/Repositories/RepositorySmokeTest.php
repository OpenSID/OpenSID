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

namespace Tests\Feature\Repositories;

use App\Repositories\AnalisisRepository;
use App\Repositories\BantuanPesertaRepository;
use App\Repositories\KelompokRepository;
use App\Repositories\WilayahRepository;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Fase 6D — repository BERSIH (tanpa helper CI3). Eksekusi query atas schema+seed.
 *
 * `LaporanPendudukRepository` & `StuntingRepository`/`ProdukHukumRepository` melibatkan
 * `LaporanPenduduk`(persen2/CI3) / model modul kompleks → tidak disertakan di sini.
 */
class RepositorySmokeTest extends BaseTestCase
{
    use RefreshDatabase;

    public function test_wilayah_repository_list(): void
    {
        $hasil = (new WilayahRepository())->list();

        // jsonPaginate → paginator; minimal punya method items/total
        $this->assertNotNull($hasil);
        $this->assertIsIterable($hasil->items());
    }

    public function test_kelompok_repository_detail_dan_anggota(): void
    {
        $repo = new KelompokRepository();

        // slug yang pasti tidak ada → null / koleksi kosong, tetapi body scope tereksekusi
        $this->assertNull($repo->detail('slug-tidak-ada-xyz'));

        $anggota = $repo->anggota('slug-tidak-ada-xyz');
        $this->assertNotNull($anggota);
        $this->assertCount(0, $anggota);
    }

    public function test_bantuan_peserta_repository_list(): void
    {
        foreach (['bantuan_penduduk', 'bantuan_keluarga'] as $jenis) {
            $hasil = (new BantuanPesertaRepository($jenis))->list();
            $this->assertNotNull($hasil);
        }
    }

    public function test_analisis_repository(): void
    {
        $repo = new AnalisisRepository();

        $this->assertNotNull($repo->analisisMaster());
        $this->assertNotNull($repo->analisisIndikator());
        $this->assertNotNull($repo->jumlahAnalisisJawaban());
    }
}
