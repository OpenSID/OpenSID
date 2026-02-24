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

namespace App\Repositories;

use App\Libraries\Stunting;
use App\Models\Anak;
use App\Models\IbuHamil;

class StuntingRepository
{
    public function list($tahun, $kuartal, $idPosyandu)
    {
        $stunting  = new Stunting(['idPosyandu' => $idPosyandu, 'kuartal' => $kuartal, 'tahun' => $tahun]);
        $scoreCard = $stunting->scoreCard();

        return [
            'scorecard'                 => $scoreCard,
            'widgets'                   => $this->widget($tahun, $kuartal, $idPosyandu),
            'chartStuntingUmurData'     => $stunting->chartStuntingUmurData(),
            'chartStuntingPosyanduData' => $stunting->chartPosyanduData(),
            'chartKelompokUmurData'     => $this->chartKelompokUmurData($tahun, $kuartal, $idPosyandu), // BARU
            'chartStatusPerUmurData'    => $this->chartStatusPerUmurData($tahun, $kuartal, $idPosyandu), // BARU
        ];
    }

    private function rangeKuartal($tahun, $kuartal): array
    {
        return match ((int) $kuartal) {
            1       => ["{$tahun}-01-01", "{$tahun}-03-31"],
            2       => ["{$tahun}-04-01", "{$tahun}-06-30"],
            3       => ["{$tahun}-07-01", "{$tahun}-09-30"],
            4       => ["{$tahun}-10-01", "{$tahun}-12-31"],
            default => [],
        };
    }

    private function widget($tahun, $kuartal, $idPosyandu): array
    {
        [$start, $end] = $this->rangeKuartal($tahun, $kuartal);

        $ibuHamil = IbuHamil::whereBetween('created_at', [$start, $end]);
        $anak     = Anak::whereMonth('created_at', '>=', substr($start, 5, 2))
            ->whereMonth('created_at', '<=', substr($end, 5, 2))
            ->whereYear('created_at', $tahun);

        if ($idPosyandu) {
            $ibuHamil->where('posyandu_id', $idPosyandu);
            $anak->where('posyandu_id', $idPosyandu);
        }

        return [
            [
                'title'    => 'Ibu Hamil Periksa',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-blue',
                'total'    => $ibuHamil->count(),
            ],
            [
                'title'    => 'Anak Periksa',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-gray',
                'total'    => $anak->count(),
            ],
            [
                'title'    => 'Ibu Hamil & Anak 0-23 Bulan',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-green',
                'total'    => $ibuHamil->count() + $anak->count(),
            ],
            [
                'title'    => 'Anak 0-23 Bulan Normal',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-green',
                'total'    => (clone $anak)->normal()->count(),
            ],
            [
                'title'    => 'Anak 0-23 Bulan Risiko Stunting',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-yellow',
                'total'    => (clone $anak)->resikoStunting()->count(),
            ],
            [
                'title'    => 'Anak 0-23 Bulan Stunting',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-red',
                'total'    => (clone $anak)->stunting()->count(),
            ],
        ];
    }

    /**
     * Chart untuk menampilkan jumlah anak per kelompok umur
     * FITUR BARU: Issue #10805
     *
     * @param mixed $tahun
     * @param mixed $kuartal
     * @param mixed $idPosyandu
     */
    private function chartKelompokUmurData($tahun, $kuartal, $idPosyandu): array
    {
        [$start, $end] = $this->rangeKuartal($tahun, $kuartal);

        // Menggunakan pola yang sama dengan chartStuntingUmurData
        $stuntingObj = Anak::selectRaw('sum(case when umur_bulan between 0 and 5 then 1 else 0 end) as range_1')
            ->selectRaw('sum(case when umur_bulan between 6 and 11 then 1 else 0 end) as range_2')
            ->selectRaw('sum(case when umur_bulan between 12 and 23 then 1 else 0 end) as range_3')
            ->whereMonth('created_at', '>=', substr($start, 5, 2))
            ->whereMonth('created_at', '<=', substr($end, 5, 2))
            ->whereYear('created_at', $tahun);

        if ($idPosyandu) {
            $stuntingObj->where('posyandu_id', $idPosyandu);
        }

        $result = $stuntingObj->first();

        $range1 = $result->range_1 ?? 0;
        $range2 = $result->range_2 ?? 0;
        $range3 = $result->range_3 ?? 0;

        $total = $range1 + $range2 + $range3;

        $data = [
            [
                'name'   => '0-5 Bulan',
                'y'      => $total > 0 ? round(($range1 / $total) * 100, 1) : 0,
                'jumlah' => $range1,
            ],
            [
                'name'   => '6-11 Bulan',
                'y'      => $total > 0 ? round(($range2 / $total) * 100, 1) : 0,
                'jumlah' => $range2,
            ],
            [
                'name'   => '12-23 Bulan',
                'y'      => $total > 0 ? round(($range3 / $total) * 100, 1) : 0,
                'jumlah' => $range3,
            ],
        ];

        return [
            'id'    => 'chart_kelompok_umur',
            'title' => 'Distribusi Anak Berdasarkan Kelompok Umur',
            'data'  => $data,
        ];
    }

    /**
     * Chart untuk menampilkan status stunting per kelompok umur
     * FITUR BARU: Issue #10805 - Alternatif
     *
     * @param mixed $tahun
     * @param mixed $kuartal
     * @param mixed $idPosyandu
     */
    private function chartStatusPerUmurData($tahun, $kuartal, $idPosyandu): array
    {
        [$start, $end] = $this->rangeKuartal($tahun, $kuartal);

        // Query untuk masing-masing kelompok umur dengan status gizi
        $giziAnakObj = Anak::selectRaw('status_gizi')
            ->selectRaw('sum(case when umur_bulan between 0 and 5 then 1 else 0 end) as range_1')
            ->selectRaw('sum(case when umur_bulan between 6 and 11 then 1 else 0 end) as range_2')
            ->selectRaw('sum(case when umur_bulan between 12 and 23 then 1 else 0 end) as range_3')
            ->whereMonth('created_at', '>=', substr($start, 5, 2))
            ->whereMonth('created_at', '<=', substr($end, 5, 2))
            ->whereYear('created_at', $tahun)
            ->groupBy('status_gizi');

        if ($idPosyandu) {
            $giziAnakObj->where('posyandu_id', $idPosyandu);
        }

        $giziAnak = $giziAnakObj->get();

        // Inisialisasi data per kelompok umur
        $kelompokUmur = [
            '0-5'   => ['normal' => 0, 'risiko' => 0, 'stunting' => 0],
            '6-11'  => ['normal' => 0, 'risiko' => 0, 'stunting' => 0],
            '12-23' => ['normal' => 0, 'risiko' => 0, 'stunting' => 0],
        ];

        // Kelompokkan data berdasarkan status gizi
        foreach ($giziAnak as $item) {
            $model = new Anak(['status_gizi' => $item->status_gizi]);

            if ($model->isNormal()) {
                $kelompokUmur['0-5']['normal'] += $item->range_1;
                $kelompokUmur['6-11']['normal'] += $item->range_2;
                $kelompokUmur['12-23']['normal'] += $item->range_3;
            } elseif ($model->isResikoStunting()) {
                $kelompokUmur['0-5']['risiko'] += $item->range_1;
                $kelompokUmur['6-11']['risiko'] += $item->range_2;
                $kelompokUmur['12-23']['risiko'] += $item->range_3;
            } elseif ($model->isStunting()) {
                $kelompokUmur['0-5']['stunting'] += $item->range_1;
                $kelompokUmur['6-11']['stunting'] += $item->range_2;
                $kelompokUmur['12-23']['stunting'] += $item->range_3;
            }
        }

        // Format data untuk chart
        $charts         = [];
        $kelompokConfig = [
            '0-5'   => '0-5 Bulan',
            '6-11'  => '6-11 Bulan',
            '12-23' => '12-23 Bulan',
        ];

        foreach ($kelompokConfig as $key => $label) {
            $normal   = $kelompokUmur[$key]['normal'];
            $risiko   = $kelompokUmur[$key]['risiko'];
            $stunting = $kelompokUmur[$key]['stunting'];
            $total    = $normal + $risiko + $stunting;

            if ($total > 0) {
                $data = [
                    [
                        'name'   => 'Normal',
                        'y'      => round(($normal / $total) * 100, 1),
                        'jumlah' => $normal,
                    ],
                    [
                        'name'   => 'Risiko Stunting',
                        'y'      => round(($risiko / $total) * 100, 1),
                        'jumlah' => $risiko,
                    ],
                    [
                        'name'   => 'Stunting',
                        'y'      => round(($stunting / $total) * 100, 1),
                        'jumlah' => $stunting,
                    ],
                ];
            } else {
                $data = [
                    [
                        'name'   => 'Tidak Ada Data',
                        'y'      => 100,
                        'jumlah' => 0,
                    ],
                ];
            }

            $charts[] = [
                'id'    => 'chart_status_umur_' . str_replace('-', '_', $key),
                'title' => 'Status Stunting Anak ' . $label,
                'data'  => $data,
                'total' => $total,
            ];
        }

        return $charts;
    }
}
