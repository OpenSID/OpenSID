<?php

namespace App\Services;

use App\Enums\PeristiwaKeluargaEnum;
use App\Enums\PeristiwaPendudukEnum;
use App\Models\LogKeluarga;
use App\Models\LogPenduduk;
use App\Repositories\LaporanPendudukRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanPerkembanganService
{
    public function kolom(): array
    {
        return [
            'awal'      => 'Awal',
            'lahir'     => 'Lahir',
            'datang'    => 'Datang',
            'mati'      => 'Meninggal',
            'pindah'    => 'Pindah',
            'hilang'    => 'Hilang',
            'akhir'     => 'Akhir',
            'perubahan' => 'Perubahan',
        ];
    }

    public function filter(array $input): array
    {
        $filter = [
            'jenis'      => ($input['jenis'] ?? null) === 'keluarga' ? 'keluarga' : 'penduduk',
            'periode'    => ($input['periode'] ?? null) === 'tahun' ? 'tahun' : 'bulan',
            'tahun'      => $this->tahun($input['tahun'] ?? null),
            'tahunAwal'  => $this->tahun($input['tahun_awal'] ?? null, max(1945, (int) date('Y') - 1)),
            'tahunAkhir' => $this->tahun($input['tahun_akhir'] ?? null),
            'bulanAwal'  => $this->bulan($input['bulan_awal'] ?? null, 1),
            'bulanAkhir' => $this->bulan($input['bulan_akhir'] ?? null),
        ];

        if ($filter['tahunAwal'] > $filter['tahunAkhir']) {
            $filter['tahunAkhir'] = $filter['tahunAwal'];
        }

        if ($filter['bulanAwal'] > $filter['bulanAkhir']) {
            $filter['bulanAkhir'] = $filter['bulanAwal'];
        }

        return $filter;
    }

    public function payload(array $filter): array
    {
        $main = $this->dataPerkembangan(
            $filter['jenis'],
            $filter['periode'],
            $filter['tahun'],
            $filter['tahunAwal'],
            $filter['tahunAkhir'],
            $filter['bulanAwal'],
            $filter['bulanAkhir']
        );

        return [
            'judul'  => 'Statistik Perkembangan ' . ucfirst($filter['jenis']),
            'sumbuY' => 'Jumlah ' . ucfirst($filter['jenis']),
            'labels' => array_column($main, 'label'),
            'main'   => $main,
            'series' => $this->series($main, array_keys($this->kolom())),
        ];
    }

    private function dataPerkembangan(string $jenis, string $periode, int $tahun, int $tahunAwal, int $tahunAkhir, int $bulanAwal, int $bulanAkhir): array
    {
        if ($periode === 'tahun') {
            $awal  = Carbon::create($tahunAwal, 1, 1)->startOfYear();
            $akhir = Carbon::create($tahunAkhir, 1, 1)->startOfYear();
        } else {
            $awal  = Carbon::create($tahun, $bulanAwal, 1)->startOfMonth();
            $akhir = Carbon::create($tahun, $bulanAkhir, 1)->startOfMonth();
        }

        $data = [];

        for ($tanggal = $awal->copy(); $tanggal->lessThanOrEqualTo($akhir); $periode === 'tahun' ? $tanggal->addYear() : $tanggal->addMonth()) {
            $rekap = $periode === 'tahun'
                ? $this->rekapTahunan((int) $tanggal->format('Y'), $jenis)
                : LaporanPendudukRepository::dataPenduduk((int) $tanggal->format('Y'), (int) $tanggal->format('m'));

            $data[] = [
                'label'     => $periode === 'tahun' ? $tanggal->format('Y') : getBulan((int) $tanggal->format('m')) . ' ' . $tanggal->format('Y'),
                'awal'      => $this->jumlah($rekap['penduduk_awal'], $jenis),
                'lahir'     => $this->jumlah($rekap['kelahiran'], $jenis),
                'datang'    => $this->jumlah($rekap['pendatang'], $jenis),
                'mati'      => $this->jumlah($rekap['kematian'], $jenis),
                'pindah'    => $this->jumlah($rekap['pindah'], $jenis),
                'hilang'    => $this->jumlah($rekap['hilang'], $jenis),
                'akhir'     => $this->jumlah($rekap['penduduk_akhir'], $jenis),
                'perubahan' => $this->jumlah($rekap['penduduk_akhir'], $jenis) - $this->jumlah($rekap['penduduk_awal'], $jenis),
            ];
        }

        return $data;
    }

    private function rekapTahunan(int $tahun, string $jenis): array
    {
        $rekapJanuari  = LaporanPendudukRepository::dataPenduduk($tahun, 1);
        $rekapDesember = LaporanPendudukRepository::dataPenduduk($tahun, 12);

        if ($jenis === 'keluarga') {
            $peristiwa = $this->jumlahPeristiwaKeluargaTahunan($tahun);

            return [
                'penduduk_awal'  => $rekapJanuari['penduduk_awal'],
                'penduduk_akhir' => $rekapDesember['penduduk_akhir'],
                'kelahiran'      => ['KK' => $peristiwa[PeristiwaKeluargaEnum::KELUARGA_BARU->value] ?? 0],
                'pendatang'      => ['KK' => $peristiwa[PeristiwaKeluargaEnum::KELUARGA_BARU_DATANG->value] ?? 0],
                'kematian'       => ['KK' => $peristiwa[PeristiwaKeluargaEnum::KEPALA_KELUARGA_MATI->value] ?? 0],
                'pindah'         => ['KK' => $peristiwa[PeristiwaKeluargaEnum::KEPALA_KELUARGA_PINDAH->value] ?? 0],
                'hilang'         => ['KK' => $peristiwa[PeristiwaKeluargaEnum::KEPALA_KELUARGA_HILANG->value] ?? 0],
            ];
        }

        $peristiwa = $this->jumlahPeristiwaPendudukTahunan($tahun);

        return [
            'penduduk_awal'  => $rekapJanuari['penduduk_awal'],
            'penduduk_akhir' => $rekapDesember['penduduk_akhir'],
            'kelahiran'      => ['JML' => $peristiwa[PeristiwaPendudukEnum::BARU_LAHIR->value] ?? 0],
            'pendatang'      => ['JML' => $peristiwa[PeristiwaPendudukEnum::BARU_PINDAH_MASUK->value] ?? 0],
            'kematian'       => ['JML' => $peristiwa[PeristiwaPendudukEnum::MATI->value] ?? 0],
            'pindah'         => ['JML' => $peristiwa[PeristiwaPendudukEnum::PINDAH_KELUAR->value] ?? 0],
            'hilang'         => ['JML' => $peristiwa[PeristiwaPendudukEnum::HILANG->value] ?? 0],
        ];
    }

    private function jumlahPeristiwaPendudukTahunan(int $tahun): array
    {
        return LogPenduduk::whereBetween('tgl_lapor', $this->rentangTahun($tahun))
            ->whereIn('kode_peristiwa', [
                PeristiwaPendudukEnum::BARU_LAHIR->value,
                PeristiwaPendudukEnum::BARU_PINDAH_MASUK->value,
                PeristiwaPendudukEnum::MATI->value,
                PeristiwaPendudukEnum::PINDAH_KELUAR->value,
                PeristiwaPendudukEnum::HILANG->value,
            ])
            ->select('kode_peristiwa', DB::raw('COUNT(*) as total'))
            ->groupBy('kode_peristiwa')
            ->pluck('total', 'kode_peristiwa')
            ->map(static fn ($total): int => (int) $total)
            ->toArray();
    }

    private function jumlahPeristiwaKeluargaTahunan(int $tahun): array
    {
        return LogKeluarga::whereBetween('tgl_peristiwa', $this->rentangTahun($tahun))
            ->whereIn('id_peristiwa', [
                PeristiwaKeluargaEnum::KELUARGA_BARU->value,
                PeristiwaKeluargaEnum::KELUARGA_BARU_DATANG->value,
                PeristiwaKeluargaEnum::KEPALA_KELUARGA_MATI->value,
                PeristiwaKeluargaEnum::KEPALA_KELUARGA_PINDAH->value,
                PeristiwaKeluargaEnum::KEPALA_KELUARGA_HILANG->value,
            ])
            ->select('id_peristiwa', DB::raw('COUNT(*) as total'))
            ->groupBy('id_peristiwa')
            ->pluck('total', 'id_peristiwa')
            ->map(static fn ($total): int => (int) $total)
            ->toArray();
    }

    private function rentangTahun(int $tahun): array
    {
        return [
            Carbon::create($tahun, 1, 1)->startOfDay()->format('Y-m-d H:i:s'),
            Carbon::create($tahun, 12, 31)->endOfDay()->format('Y-m-d H:i:s'),
        ];
    }

    private function jumlah(array $data, string $jenis): int
    {
        if ($jenis === 'keluarga') {
            return (int) ($data['KK'] ?? 0);
        }

        return (int) ($data['WNI_L'] ?? 0)
            + (int) ($data['WNI_P'] ?? 0)
            + (int) ($data['WNA_L'] ?? 0)
            + (int) ($data['WNA_P'] ?? 0)
            + (int) ($data['JML'] ?? 0);
    }

    private function tahun(mixed $tahun, ?int $default = null): int
    {
        $tahunSaatIni = (int) date('Y');
        $default ??= $tahunSaatIni;
        $tahun     = (int) $tahun;

        return $tahun >= 1945 && $tahun <= $tahunSaatIni ? $tahun : $default;
    }

    private function bulan(mixed $bulan, ?int $default = null): int
    {
        $default ??= (int) date('n');
        $bulan     = (int) $bulan;

        return $bulan >= 1 && $bulan <= 12 ? $bulan : $default;
    }

    private function series(array $main, array $tampil): array
    {
        $kolom = $this->kolom();

        return collect($tampil)
            ->map(fn ($key): array => [
                'id'      => $key,
                'name'    => $kolom[$key],
                'data'    => array_column($main, $key),
                'visible' => in_array($key, ['awal', 'akhir']),
            ])
            ->values()
            ->toArray();
    }
}
