<?php

namespace App\Http\Controllers\Admin;

use App\Attributes\AdminModul;
use App\Http\Controllers\Controller;
use App\Services\LaporanPerkembanganService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AdminModul(modul: 'statistik', subModul: 'laporan-perkembangan')]
class LaporanPerkembanganController extends Controller
{
    public function __construct(
        protected LaporanPerkembanganService $laporanPerkembanganService,
    ) {}

    public function index(Request $request): View
    {
        $filter  = $this->laporanPerkembanganService->filter($this->input($request));
        $payload = $this->laporanPerkembanganService->payload($filter);

        return view('admin.statistik.laporan_perkembangan.index', [
            'jenis'      => $filter['jenis'],
            'periode'    => $filter['periode'],
            'tahun'      => $filter['tahun'],
            'tahunAwal'  => $filter['tahunAwal'],
            'tahunAkhir' => $filter['tahunAkhir'],
            'bulanAwal'  => $filter['bulanAwal'],
            'bulanAkhir' => $filter['bulanAkhir'],
            'kolom'      => $this->laporanPerkembanganService->kolom(),
            'main'       => $payload['main'],
            'series'     => $payload['series'],
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $filter = $this->laporanPerkembanganService->filter($this->input($request));

        return response()->json($this->laporanPerkembanganService->payload($filter));
    }

    private function input(Request $request): array
    {
        return [
            'jenis'       => $request->query('jenis'),
            'periode'     => $request->query('periode'),
            'tahun'       => $request->query('tahun'),
            'tahun_awal'  => $request->query('tahun_awal'),
            'tahun_akhir' => $request->query('tahun_akhir'),
            'bulan_awal'  => $request->query('bulan_awal'),
            'bulan_akhir' => $request->query('bulan_akhir'),
        ];
    }
}
