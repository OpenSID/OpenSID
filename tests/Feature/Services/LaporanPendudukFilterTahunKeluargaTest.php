<?php

namespace Tests\Feature\Services;

use App\Models\Keluarga;
use App\Models\User;
use App\Services\LaporanPenduduk;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;
use Tests\Traits\RefreshDatabase;

/**
 * Regresi issue #11898 — Filter tahun pada statistik keluarga -> kelas sosial tidak berfungsi.
 *
 * Menguji filter tahun kumulatif pada statistik kependudukan keluarga
 * menggunakan data demo yang sudah ada di database/schema.sql.
 */
class LaporanPendudukFilterTahunKeluargaTest extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Autentikasi admin untuk memastikan akses scope & identitas desa aktif
        $this->actingAs(User::where('batasi_wilayah', 0)->firstOrFail(), 'admin');

        // Reset semua kelas_sosial data demo agar tidak mengganggu assertion pengujian
        Keluarga::where('config_id', 1)->update(['kelas_sosial' => null]);

        // Ambil 2 keluarga aktif (status_dasar kepala = 1) yang ada di database demo
        $keluargas = Keluarga::where('config_id', 1)
            ->whereHas('kepalaKeluarga', static fn ($q) => $q->where('status_dasar', 1))
            ->take(2)
            ->get();

        $this->assertCount(2, $keluargas, 'Harus ada minimal 2 keluarga aktif di data demo.');

        // Keluarga 1: Terdaftar 2022, Kelas Sosial = 1 (Pra Sejahtera)
        $keluargas[0]->update([
            'tgl_daftar'   => '2022-06-01 08:00:00',
            'kelas_sosial' => 1,
        ]);

        // Keluarga 2: Terdaftar 2024, Kelas Sosial = 1 (Pra Sejahtera)
        $keluargas[1]->update([
            'tgl_daftar'   => '2024-06-01 08:00:00',
            'kelas_sosial' => 1,
        ]);
    }

    #[Test]
    public function filter_tahun_membatasi_data_per_kelas_dan_baris_total(): void
    {
        // 2021: Belum ada keluarga kelas 1 yang terdaftar
        $data2021 = $this->kelasSosial('2021');
        $this->assertSame(0, $this->kelas1($data2021)['jumlah']);

        // 2023: Hanya keluarga 1 (terdaftar 2022) yang terhitung
        $data2023 = $this->kelasSosial('2023');
        $this->assertSame(1, $this->kelas1($data2023)['jumlah']);

        // 2025: Kedua keluarga (2022 & 2024) terhitung
        $data2025 = $this->kelasSosial('2025');
        $this->assertSame(2, $this->kelas1($data2025)['jumlah']);
    }

    #[Test]
    public function tanpa_filter_tahun_semua_keluarga_terhitung(): void
    {
        $data = $this->kelasSosial(null);

        // Tanpa filter: seluruh keluarga kelas 1 terhitung
        $this->assertSame(2, $this->kelas1($data)['jumlah']);
    }

    private function kelasSosial(?string $tahun): array
    {
        $hasil = (new LaporanPenduduk())->listData('kelas_sosial', [
            'tahun'     => $tahun,
            'status'    => null,
            'dusun'     => null,
            'rw'        => null,
            'rt'        => null,
            'idCluster' => null,
        ]);

        return collect($hasil)->toArray();
    }

    private function kelas1(array $data): array
    {
        foreach ($data as $row) {
            if (is_array($row) && ($row['id'] ?? null) === 1) {
                return $row;
            }
        }

        $this->fail('Baris "Keluarga Pra Sejahtera" (id 1) tidak ditemukan pada hasil statistik.');
    }
}

