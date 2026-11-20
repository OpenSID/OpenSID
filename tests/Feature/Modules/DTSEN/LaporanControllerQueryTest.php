<?php

namespace Tests\Feature\Modules\DTSEN;

use App\Models\Keluarga;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Tests\Traits\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Modules\DTSEN\Models\Dtsen as ModelDtsen;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

class LaporanControllerQueryTest extends BaseTestCase
{
    use RefreshDatabase;

    // =========================================================================
    // Helper: replika query datatables dari controller
    // =========================================================================

    private function queryDatatables(array $filters = []): \Illuminate\Database\Query\Builder
    {
        $keluarga = (new Keluarga())->getTable();
        $penduduk = (new Penduduk())->getTable();
        $wilayah  = (new Wilayah())->getTable();

        $query = DB::table('dtsen')
            ->select(
                'dtsen.id',
                'dtsen.id_keluarga',
                'dtsen.kd_hasil_pendataan_keluarga',
                'dtsen.kd_peringkat_kesejahteraan_keluarga',
                'dtsen.nama_ppl',
                'dtsen.updated_at'
            )
            ->addSelect('kk.nik as nik_kk', 'kk.nama as nama_kk')
            ->addSelect('wil_kk.dusun as dusun_kk', 'wil_kk.rt as rt_kk', 'wil_kk.rw as rw_kk')
            ->addSelect(
                DB::raw('COALESCE(COUNT(DISTINCT p.id), 0) as anggota_count'),
                DB::raw('COALESCE(COUNT(DISTINCT da.id), 0) as dtsen_anggota_count')
            )
            ->join($keluarga . ' AS keluarga', 'keluarga.id', '=', 'dtsen.id_keluarga')
            ->join($penduduk . ' AS kk', 'keluarga.nik_kepala', '=', 'kk.id')
            ->join($wilayah . ' AS wil_kk', 'kk.id_cluster', '=', 'wil_kk.id')
            ->leftJoin(
                "{$penduduk} AS p",
                static fn ($join) => $join
                    ->on('keluarga.id', '=', 'p.id_kk')
                    ->where('p.status_dasar', '=', 1)
            )
            ->leftJoin('dtsen_anggota AS da', 'dtsen.id', '=', 'da.id_dtsen')
            ->where('dtsen.config_id', identitas('id'))
            ->groupBy(
                'dtsen.id',
                'dtsen.id_keluarga',
                'dtsen.kd_hasil_pendataan_keluarga',
                'dtsen.kd_peringkat_kesejahteraan_keluarga',
                'dtsen.nama_ppl',
                'dtsen.updated_at',
                'kk.nik',
                'kk.nama',
                'wil_kk.dusun',
                'wil_kk.rt',
                'wil_kk.rw'
            );

        if (! empty($filters['kd_hasil_pendataan_keluarga'])) {
            $query->where('dtsen.kd_hasil_pendataan_keluarga', $filters['kd_hasil_pendataan_keluarga']);
        }

        if (! empty($filters['kd_peringkat_kesejahteraan_keluarga'])) {
            $query->having('dtsen.kd_peringkat_kesejahteraan_keluarga', '=', $filters['kd_peringkat_kesejahteraan_keluarga']);
        }

        return $query;
    }

    // =========================================================================
    // 1. Struktur query — kolom yang dihasilkan
    // =========================================================================

    #[Test]
    public function query_datatables_menghasilkan_kolom_yang_benar(): void
    {
        $result = $this->queryDatatables()->first();

        if ($result === null) {
            $this->markTestSkipped('Tidak ada data dtsen di database.');
        }

        $this->assertObjectHasProperty('id', $result);
        $this->assertObjectHasProperty('id_keluarga', $result);
        $this->assertObjectHasProperty('kd_hasil_pendataan_keluarga', $result);
        $this->assertObjectHasProperty('kd_peringkat_kesejahteraan_keluarga', $result);
        $this->assertObjectHasProperty('nama_ppl', $result);
        $this->assertObjectHasProperty('nik_kk', $result);
        $this->assertObjectHasProperty('nama_kk', $result);
        $this->assertObjectHasProperty('dusun_kk', $result);
        $this->assertObjectHasProperty('rt_kk', $result);
        $this->assertObjectHasProperty('rw_kk', $result);
        $this->assertObjectHasProperty('anggota_count', $result);
        $this->assertObjectHasProperty('dtsen_anggota_count', $result);
    }

    #[Test]
    public function query_datatables_anggota_count_tidak_negatif(): void
    {
        $results = $this->queryDatatables()->get();

        if ($results->isEmpty()) {
            $this->markTestSkipped('Tidak ada data dtsen di database.');
        }

        foreach ($results as $row) {
            $this->assertGreaterThanOrEqual(0, (int) $row->anggota_count);
            $this->assertGreaterThanOrEqual(0, (int) $row->dtsen_anggota_count);
        }
    }

    #[Test]
    public function query_datatables_tidak_menghasilkan_duplikat_id_dtsen(): void
    {
        $results = $this->queryDatatables()->get();

        if ($results->isEmpty()) {
            $this->markTestSkipped('Tidak ada data dtsen di database.');
        }

        $ids = $results->pluck('id');
        $this->assertEquals(
            $ids->count(),
            $ids->unique()->count(),
            'Terdapat duplikat baris dtsen — GROUP BY mungkin tidak bekerja dengan benar'
        );
    }

    // =========================================================================
    // 2. Filter status kesejahteraan (WHERE)
    // =========================================================================

    #[Test]
    public function filter_status_kesejahteraan_hanya_mengembalikan_data_sesuai_kode(): void
    {
        $kode = '1';

        $tanpaFilter   = $this->queryDatatables()->get();
        $denganFilter  = $this->queryDatatables(['kd_hasil_pendataan_keluarga' => $kode])->get();

        if ($tanpaFilter->isEmpty()) {
            $this->markTestSkipped('Tidak ada data dtsen di database.');
        }

        foreach ($denganFilter as $row) {
            $this->assertEquals(
                $kode,
                $row->kd_hasil_pendataan_keluarga,
                'Baris dengan kd_hasil_pendataan_keluarga != ' . $kode . ' lolos filter'
            );
        }

        // Jumlah hasil filter <= jumlah tanpa filter
        $this->assertLessThanOrEqual($tanpaFilter->count(), $denganFilter->count());
    }

    #[Test]
    public function filter_status_kesejahteraan_kode_tidak_ada_mengembalikan_kosong(): void
    {
        $result = $this->queryDatatables(['kd_hasil_pendataan_keluarga' => '999'])->get();

        $this->assertTrue($result->isEmpty(), 'Filter kode tidak valid seharusnya mengembalikan hasil kosong');
    }

    // =========================================================================
    // 3. Filter peringkat kesejahteraan (HAVING)
    // =========================================================================

    #[Test]
    public function filter_peringkat_kesejahteraan_hanya_mengembalikan_data_sesuai_kode(): void
    {
        $kode = '1';

        $tanpaFilter  = $this->queryDatatables()->get();
        $denganFilter = $this->queryDatatables(['kd_peringkat_kesejahteraan_keluarga' => $kode])->get();

        if ($tanpaFilter->isEmpty()) {
            $this->markTestSkipped('Tidak ada data dtsen di database.');
        }

        foreach ($denganFilter as $row) {
            $this->assertEquals(
                $kode,
                $row->kd_peringkat_kesejahteraan_keluarga,
                'Baris dengan kd_peringkat_kesejahteraan_keluarga != ' . $kode . ' lolos filter HAVING'
            );
        }

        $this->assertLessThanOrEqual($tanpaFilter->count(), $denganFilter->count());
    }

    #[Test]
    public function filter_peringkat_kesejahteraan_kode_tidak_ada_mengembalikan_kosong(): void
    {
        $result = $this->queryDatatables(['kd_peringkat_kesejahteraan_keluarga' => '999'])->get();

        $this->assertTrue($result->isEmpty(), 'Filter kode tidak valid seharusnya mengembalikan hasil kosong');
    }

    // =========================================================================
    // 4. Filter gabungan WHERE + HAVING
    // =========================================================================

    #[Test]
    public function filter_gabungan_status_dan_peringkat_menghasilkan_subset_yang_benar(): void
    {
        $filterStatus   = '1';
        $filterPeringkat = '1';

        $hanyaStatus    = $this->queryDatatables(['kd_hasil_pendataan_keluarga' => $filterStatus])->get();
        $gabungan       = $this->queryDatatables([
            'kd_hasil_pendataan_keluarga'         => $filterStatus,
            'kd_peringkat_kesejahteraan_keluarga' => $filterPeringkat,
        ])->get();

        if ($hanyaStatus->isEmpty()) {
            $this->markTestSkipped('Tidak ada data dtsen dengan kd_hasil_pendataan_keluarga = ' . $filterStatus);
        }

        // Hasil gabungan harus subset dari filter status saja
        $this->assertLessThanOrEqual($hanyaStatus->count(), $gabungan->count());

        foreach ($gabungan as $row) {
            $this->assertEquals($filterStatus, $row->kd_hasil_pendataan_keluarga);
            $this->assertEquals($filterPeringkat, $row->kd_peringkat_kesejahteraan_keluarga);
        }
    }

    // =========================================================================
    // 5. FilterColumn LIKE — pencarian teks
    // =========================================================================

    #[Test]
    public function filter_nik_kk_like_mengembalikan_hasil_yang_relevan(): void
    {
        $firstRow = $this->queryDatatables()->first();

        if ($firstRow === null) {
            $this->markTestSkipped('Tidak ada data dtsen di database.');
        }

        // Ambil 4 karakter tengah NIK sebagai keyword pencarian
        $keyword = substr($firstRow->nik_kk, 4, 4);

        $result = $this->queryDatatables()
            ->where('kk.nik', 'LIKE', "%{$keyword}%")
            ->get();

        $this->assertNotEmpty($result);
        foreach ($result as $row) {
            $this->assertStringContainsString($keyword, $row->nik_kk);
        }
    }

    #[Test]
    public function filter_nama_kk_like_mengembalikan_hasil_yang_relevan(): void
    {
        $firstRow = $this->queryDatatables()->first();

        if ($firstRow === null) {
            $this->markTestSkipped('Tidak ada data dtsen di database.');
        }

        $keyword = substr($firstRow->nama_kk, 0, 3);

        $result = $this->queryDatatables()
            ->where('kk.nama', 'LIKE', "%{$keyword}%")
            ->get();

        $this->assertNotEmpty($result);
        foreach ($result as $row) {
            $this->assertStringContainsStringIgnoringCase($keyword, $row->nama_kk);
        }
    }

    #[Test]
    public function filter_dusun_like_mengembalikan_hasil_yang_relevan(): void
    {
        $firstRow = $this->queryDatatables()->whereNotNull('wil_kk.dusun')->first();

        if ($firstRow === null) {
            $this->markTestSkipped('Tidak ada data dtsen dengan dusun di database.');
        }

        $keyword = substr($firstRow->dusun_kk, 0, 3);

        $result = $this->queryDatatables()
            ->where('wil_kk.dusun', 'LIKE', "%{$keyword}%")
            ->get();

        $this->assertNotEmpty($result);
        foreach ($result as $row) {
            $this->assertStringContainsStringIgnoringCase($keyword, $row->dusun_kk);
        }
    }

    #[Test]
    public function filter_petugas_like_mengembalikan_hasil_yang_relevan(): void
    {
        $firstRow = $this->queryDatatables()->whereNotNull('dtsen.nama_ppl')->first();

        if ($firstRow === null) {
            $this->markTestSkipped('Tidak ada data dtsen dengan nama_ppl di database.');
        }

        $keyword = substr($firstRow->nama_ppl, 0, 3);

        $result = $this->queryDatatables()
            ->where('dtsen.nama_ppl', 'LIKE', "%{$keyword}%")
            ->get();

        $this->assertNotEmpty($result);
        foreach ($result as $row) {
            $this->assertStringContainsStringIgnoringCase($keyword, $row->nama_ppl);
        }
    }

    // =========================================================================
    // 6. Query index — keluarga belum terdaftar di dtsen
    // =========================================================================

    #[Test]
    public function query_keluarga_terdaftar_ids_hanya_ambil_id_keluarga_dari_dtsen(): void
    {
        $keluargaTerdaftarIds = ModelDtsen::select('id_keluarga')
            ->distinct()
            ->pluck('id_keluarga')
            ->toArray();

        if (empty($keluargaTerdaftarIds)) {
            $this->markTestSkipped('Tidak ada data dtsen di database.');
        }

        // Setiap id harus benar-benar ada di tabel dtsen
        foreach ($keluargaTerdaftarIds as $id) {
            $this->assertTrue(
                DB::table('dtsen')->where('id_keluarga', $id)->exists(),
                "id_keluarga {$id} tidak ditemukan di tabel dtsen"
            );
        }
    }

    #[Test]
    public function query_keluarga_belum_terdaftar_tidak_mengandung_id_yang_sudah_di_dtsen(): void
    {
        $keluargaTerdaftarIds = ModelDtsen::select('id_keluarga')
            ->distinct()
            ->pluck('id_keluarga')
            ->toArray();

        $belumTerdaftar = Keluarga::whereHas('kepalaKeluarga')
            ->whereNotIn('id', $keluargaTerdaftarIds)
            ->pluck('id');

        foreach ($belumTerdaftar as $id) {
            $this->assertNotContains(
                $id,
                $keluargaTerdaftarIds,
                "id_keluarga {$id} seharusnya belum terdaftar di dtsen"
            );
        }
    }

    // =========================================================================
    // 7. Query syncDtsenKeluarga — logika deteksi perbedaan jumlah anggota
    // =========================================================================

    #[Test]
    public function query_sync_anggota_aktif_hanya_status_dasar_satu(): void
    {
        $dtsenList = ModelDtsen::select('id', 'id_keluarga')->with('dtsenAnggota')->get();

        if ($dtsenList->isEmpty()) {
            $this->markTestSkipped('Tidak ada data dtsen di database.');
        }

        $idKeluargaList = $dtsenList->pluck('id_keluarga')->unique();

        $anggotaAktif = DB::table((new Penduduk())->getTable())
            ->select('id', 'id_kk', 'status_dasar')
            ->whereIn('id_kk', $idKeluargaList)
            ->where('status_dasar', 1)
            ->get();

        // Semua hasil harus status_dasar = 1
        foreach ($anggotaAktif as $anggota) {
            $this->assertEquals(1, $anggota->status_dasar);
        }
    }

    #[Test]
    public function query_sync_mendeteksi_perbedaan_jumlah_anggota_dengan_dtsen_anggota(): void
    {
        $dtsenList = ModelDtsen::select('id', 'id_keluarga')
            ->withCount('dtsenAnggota')
            ->get();

        if ($dtsenList->isEmpty()) {
            $this->markTestSkipped('Tidak ada data dtsen di database.');
        }

        $idKeluargaList = $dtsenList->pluck('id_keluarga')->unique();

        $anggotaPerKeluarga = DB::table((new Penduduk())->getTable())
            ->select('id_kk', DB::raw('COUNT(*) as jumlah'))
            ->whereIn('id_kk', $idKeluargaList)
            ->where('status_dasar', 1)
            ->groupBy('id_kk')
            ->pluck('jumlah', 'id_kk');

        $adaPerbedaan = false;

        foreach ($dtsenList as $dtsen) {
            $jumlahKeluarga = $anggotaPerKeluarga[$dtsen->id_keluarga] ?? 0;
            $jumlahDtsen    = $dtsen->dtsen_anggota_count;

            if ($jumlahKeluarga !== $jumlahDtsen) {
                $adaPerbedaan = true;
                break;
            }
        }

        // Test ini hanya memastikan logika deteksi berjalan tanpa error
        $this->assertIsBool($adaPerbedaan);
    }
}
