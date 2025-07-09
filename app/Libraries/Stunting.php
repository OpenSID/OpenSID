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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries;

use App\Models\Anak;
use App\Models\IbuHamil;
use App\Models\Posyandu;
use App\Models\SasaranPaud;

class Stunting
{
    private $kuartal;
    private $tahun;
    private $idPosyandu;
    private $batasBulanAtas;
    private $batasBulanBawah;

    public function __construct(?array $default)
    {
        $this->kuartal    = $default['kuartal'] ?? null;
        $this->tahun      = $default['tahun'] ?? null;
        $this->idPosyandu = $default['idPosyandu'] ?? null;

        if ($this->kuartal < 1 || $this->kuartal > 4) {
            $this->kuartal = null;
        }

        if ($this->kuartal == null) {
            $bulanSekarang = date('m');
            if ($bulanSekarang <= 3) {
                $_kuartal = 1;
            } elseif ($bulanSekarang <= 6) {
                $_kuartal = 2;
            } elseif ($bulanSekarang <= 9) {
                $_kuartal = 3;
            } elseif ($bulanSekarang <= 12) {
                $_kuartal = 4;
            }
            $this->kuartal = $_kuartal;
        }

        if ($this->tahun == null) {
            $this->tahun = date('Y');
        }

        if ($this->kuartal == 1) {
            $this->batasBulanBawah = 1;
            $this->batasBulanAtas  = 3;
        } elseif ($this->kuartal == 2) {
            $this->batasBulanBawah = 4;
            $this->batasBulanAtas  = 6;
        } elseif ($this->kuartal == 3) {
            $this->batasBulanBawah = 7;
            $this->batasBulanAtas  = 9;
        } elseif ($this->kuartal == 4) {
            $this->batasBulanBawah = 10;
            $this->batasBulanAtas  = 12;
        }
    }

    public function chartStuntingUmurData()
    {
        $summary = collect([
            [
                'range_1' => [Anak::TB_PENDEK => 0, Anak::TB_SANGAT_PENDEK => 0],
                'range_2' => [Anak::TB_PENDEK => 0, Anak::TB_SANGAT_PENDEK => 0],
                'range_3' => [Anak::TB_PENDEK => 0, Anak::TB_SANGAT_PENDEK => 0],
            ],
        ]);
        $stuntingObj = Anak::selectRaw('status_tikar')
            ->selectRaw('sum(case when umur_bulan between 0 and 5 then 1 else 0 end) as range_1')
            ->selectRaw('sum(case when umur_bulan between 6 and 11 then 1 else 0 end) as range_2')
            ->selectRaw('sum(case when umur_bulan between 12 and 23 then 1 else 0 end) as range_3')
            ->stuntingPendek()
            ->whereMonth('created_at', '>=', $this->batasBulanBawah)
            ->whereMonth('created_at', '<=', $this->batasBulanAtas)
            ->whereYear('created_at', $this->tahun)
            ->groupBy(['status_tikar']);

        if ($this->idPosyandu) {
            $stuntingObj->where('posyandu_id', $this->idPosyandu);
        }
        $stunting = $stuntingObj->get();
        if (! $stunting->isEmpty()) {
            $obj         = $stunting->keyBy('status_tikar');
            $totalRange1 = $obj[Anak::TB_SANGAT_PENDEK]->range_1 + $obj[Anak::TB_PENDEK]->range_1;
            $totalRange2 = $obj[Anak::TB_SANGAT_PENDEK]->range_2 + $obj[Anak::TB_PENDEK]->range_2;
            $totalRange3 = $obj[Anak::TB_SANGAT_PENDEK]->range_3 + $obj[Anak::TB_PENDEK]->range_3;
            $summary     = collect([
                'range_1' => [Anak::TB_PENDEK => $this->conversiPercent($obj[Anak::TB_PENDEK]->range_1, $totalRange1), Anak::TB_SANGAT_PENDEK => $this->conversiPercent($obj[Anak::TB_SANGAT_PENDEK]->range_1, $totalRange1)],
                'range_2' => [Anak::TB_PENDEK => $this->conversiPercent($obj[Anak::TB_PENDEK]->range_2, $totalRange2), Anak::TB_SANGAT_PENDEK => $this->conversiPercent($obj[Anak::TB_SANGAT_PENDEK]->range_2, $totalRange2)],
                'range_3' => [Anak::TB_PENDEK => $this->conversiPercent($obj[Anak::TB_PENDEK]->range_3, $totalRange3), Anak::TB_SANGAT_PENDEK => $this->conversiPercent($obj[Anak::TB_SANGAT_PENDEK]->range_3, $totalRange3)],
            ]);
        }

        return [
            ['id' => 'chart_0_5', 'title' => 'Jumlah Per Gol Umur 0-5 Bulan', 'data' => [['name' => 'Pendek (Stunting)', 'y' => $summary['range_1'][Anak::TB_PENDEK]], ['name' => 'Sangat Pendek (Severity Stunting)', 'y' => $summary['range_1'][Anak::TB_SANGAT_PENDEK]]]],
            ['id' => 'chart_6_11', 'title' => 'Jumlah Per Gol Umur 6-11 Bulan', 'data' => [['name' => 'Pendek (Stunting)', 'y' => $summary['range_2'][Anak::TB_PENDEK]], ['name' => 'Sangat Pendek (Severity Stunting)', 'y' => $summary['range_2'][Anak::TB_SANGAT_PENDEK]]]],
            ['id' => 'chart_12_23', 'title' => 'Jumlah Per Gol Umur 12-23 Bulan', 'data' => [['name' => 'Pendek (Stunting)', 'y' => $summary['range_3'][Anak::TB_PENDEK]], ['name' => 'Sangat Pendek (Severity Stunting)', 'y' => $summary['range_3'][Anak::TB_SANGAT_PENDEK]]]],
        ];
    }

    public function chartPosyanduData()
    {
        $giziAnakObj = Anak::selectRaw('status_gizi, posyandu_id, count(*) as total')
            ->whereMonth('created_at', '>=', $this->batasBulanBawah)
            ->whereMonth('created_at', '<=', $this->batasBulanAtas)
            ->whereYear('created_at', $this->tahun)
            ->groupBy(['posyandu_id', 'status_gizi']);
        $posyanduObj = Posyandu::query();
        if ($this->idPosyandu) {
            $giziAnakObj->wherePosyanduId($this->idPosyandu);
            $posyanduObj->whereId($this->idPosyandu);
        }
        $posyandu = $posyanduObj->get();

        $giziAnak = $giziAnakObj->get();
        $summary  = collect([
            [
                'normal'          => [],
                'resiko_stunting' => [],
                'stunting'        => [],
            ],
        ]);
        if (! $giziAnak->isEmpty()) {
            $summary = $giziAnak->groupBy('posyandu_id')->map(static function ($item) {
                return [
                    'normal'          => $item->sum(static fn ($q) => $q->isNormal() ? $q->total : 0),
                    'resiko_stunting' => $item->sum(static fn ($q) => $q->isResikoStunting() ? $q->total : 0),
                    'stunting'        => $item->sum(static fn ($q) => $q->isStunting() ? $q->total : 0),
                ];
            });

        }

        return [
            'categories' => $posyandu->pluck('nama')->toArray(),
            'data'       => [
                ['name' => 'Normal', 'data' => $summary->pluck('normal')->toArray()],
                ['name' => 'Resiko Stunting', 'data' => $summary->pluck('resiko_stunting')->toArray()],
                ['name' => 'Terindikasi Stunting', 'data' => $summary->pluck('stunting')->toArray()],
            ],
        ];
    }

    public function scoreCard()
    {
        $rekap = new Rekap();

        $JTRT_IbuHamil = IbuHamil::query()
            ->distinct()
            ->join('kia', 'ibu_hamil.kia_id', '=', 'kia.id')
            ->whereMonth('ibu_hamil.created_at', '>=', $this->batasBulanBawah)
            ->whereMonth('ibu_hamil.created_at', '<=', $this->batasBulanAtas)
            ->whereYear('ibu_hamil.created_at', $this->tahun)
            ->selectRaw('ibu_hamil.kia_id as kia_id')
            ->get();

        $JTRT_BulananAnak = Anak::query()
            ->distinct()
            ->join('kia', 'bulanan_anak.kia_id', '=', 'kia.id')
            ->whereMonth('bulanan_anak.created_at', '>=', $this->batasBulanBawah)
            ->whereMonth('bulanan_anak.created_at', '<=', $this->batasBulanAtas)
            ->whereYear('bulanan_anak.created_at', $this->tahun)
            ->selectRaw('bulanan_anak.kia_id as kia_id')
            ->get();

        foreach ($JTRT_IbuHamil as $item_ibuHamil) {
            $dataNoKia[] = $item_ibuHamil;

            foreach ($JTRT_BulananAnak as $item_bulananAnak) {
                if (! in_array($item_bulananAnak, $dataNoKia)) {
                    $dataNoKia[] = $item_bulananAnak;
                }
            }
        }

        $ibu_hamil    = $rekap->get_data_ibu_hamil($this->kuartal, $this->tahun, $this->idPosyandu);
        $bulanan_anak = $rekap->get_data_bulanan_anak($this->kuartal, $this->tahun, $this->idPosyandu);

        //HITUNG KEK ATAU RISTI
        $jumlahKekRisti = 0;

        foreach ($ibu_hamil['dataFilter'] as $item) {
            if (! in_array($item['user']['status_kehamilan'], [null, '1'])) {
                $jumlahKekRisti++;
            }
        }

        //HITUNG HASIL PENGUKURAN TIKAR PERTUMBUHAN
        $status_tikar = collect(Anak::STATUS_TIKAR_ANAK)->pluck('simbol', 'id');
        $tikar        = ['TD' => 0, 'M' => 0, 'K' => 0, 'H' => 0];

        if ($bulanan_anak['dataGrup'] != null) {
            foreach ($bulanan_anak['dataGrup'] as $detail) {
                $totalItem = count($detail);
                $i         = 0;

                foreach ($detail as $item) {
                    if (++$i === $totalItem) {
                        $tikar[$status_tikar[$item['status_tikar']]]++;
                    }
                }
            }

            $jumlahGiziBukanNormal = 0;

            foreach ($bulanan_anak['dataFilter'] as $item) {
                // N = 1
                if ($item['umur_dan_gizi']['status_gizi'] != 'N') {
                    $jumlahGiziBukanNormal++;
                }
            }
        } else {
            $dataNoKia             = [];
            $jumlahGiziBukanNormal = 0;
        }

        //START ANAK PAUD------------------------------------------------------------
        $totalAnak = [
            'januari'   => ['total' => 0, 'v' => 0],
            'februari'  => ['total' => 0, 'v' => 0],
            'maret'     => ['total' => 0, 'v' => 0],
            'april'     => ['total' => 0, 'v' => 0],
            'mei'       => ['total' => 0, 'v' => 0],
            'juni'      => ['total' => 0, 'v' => 0],
            'juli'      => ['total' => 0, 'v' => 0],
            'agustus'   => ['total' => 0, 'v' => 0],
            'september' => ['total' => 0, 'v' => 0],
            'oktober'   => ['total' => 0, 'v' => 0],
            'november'  => ['total' => 0, 'v' => 0],
            'desember'  => ['total' => 0, 'v' => 0],
        ];

        $anak2sd6 = SasaranPaud::query();
        $anak2sd6->whereYear('sasaran_paud.created_at', $this->tahun)->get();

        foreach ($anak2sd6 as $datax) {
            if ($datax->januari != 'belum') {
                $totalAnak['januari']['total']++;
            }
            if ($datax->februari != 'belum') {
                $totalAnak['februari']['total']++;
            }
            if ($datax->maret != 'belum') {
                $totalAnak['maret']['total']++;
            }
            if ($datax->april != 'belum') {
                $totalAnak['april']['total']++;
            }
            if ($datax->mei != 'belum') {
                $totalAnak['mei']['total']++;
            }
            if ($datax->juni != 'belum') {
                $totalAnak['juni']['total']++;
            }
            if ($datax->juli != 'belum') {
                $totalAnak['juni']['total']++;
            }
            if ($datax->agustus != 'belum') {
                $totalAnak['agustus']['total']++;
            }
            if ($datax->september != 'belum') {
                $totalAnak['juni']['total']++;
            }
            if ($datax->oktober != 'belum') {
                $totalAnak['oktober']['total']++;
            }
            if ($datax->november != 'belum') {
                $totalAnak['november']['total']++;
            }
            if ($datax->desember != 'belum') {
                $totalAnak['desember']['total']++;
            }

            if ($datax->januari == 'v') {
                $totalAnak['januari']['v']++;
            }
            if ($datax->februari == 'v') {
                $totalAnak['februari']['v']++;
            }
            if ($datax->maret == 'v') {
                $totalAnak['maret']['v']++;
            }
            if ($datax->april == 'v') {
                $totalAnak['april']['v']++;
            }
            if ($datax->mei == 'v') {
                $totalAnak['mei']['v']++;
            }
            if ($datax->juni == 'v') {
                $totalAnak['juni']['v']++;
            }
            if ($datax->juli == 'v') {
                $totalAnak['juni']['v']++;
            }
            if ($datax->agustus == 'v') {
                $totalAnak['agustus']['v']++;
            }
            if ($datax->september == 'v') {
                $totalAnak['juni']['v']++;
            }
            if ($datax->oktober == 'v') {
                $totalAnak['oktober']['v']++;
            }
            if ($datax->november == 'v') {
                $totalAnak['november']['v']++;
            }
            if ($datax->desember == 'v') {
                $totalAnak['desember']['v']++;
            }
        }

        $dataAnak0sd2Tahun = ['jumlah' => 0, 'persen' => 0];
        if ($this->kuartal == 1) {
            $jmlAnk = $totalAnak['januari']['total'] + $totalAnak['februari']['total'] + $totalAnak['maret']['total'];
            $jmlV   = $totalAnak['januari']['v'] + $totalAnak['februari']['v'] + $totalAnak['maret']['v'];
        } elseif ($this->kuartal == 2) {
            $jmlAnk = $totalAnak['april']['total'] + $totalAnak['mei']['total'] + $totalAnak['juni']['total'];
            $jmlV   = $totalAnak['april']['v'] + $totalAnak['mei']['v'] + $totalAnak['juni']['v'];
        } elseif ($this->kuartal == 3) {
            $jmlAnk = $totalAnak['agustus']['total'];
            $jmlV   = $totalAnak['agustus']['v'];
        } elseif ($this->kuartal == 4) {
            $jmlAnk = $totalAnak['oktober']['total'] + $totalAnak['november']['total'] + $totalAnak['desember']['total'];
            $jmlV   = $totalAnak['oktober']['v'] + $totalAnak['november']['v'] + $totalAnak['desember']['v'];
        }
        $dataAnak0sd2Tahun['jumlah'] = $jmlV;
        $dataAnak0sd2Tahun['persen'] = $jmlAnk !== 0 ? number_format($jmlV / $jmlAnk * 100, 2) : 0;

        //END ANAK PAUD------------------------------------------------------------
        $data['dataAnak0sd2Tahun']     = $dataAnak0sd2Tahun;
        $data['id']                    = $this->idPosyandu;
        $data['posyandu']              = Posyandu::get();
        $data['JTRT']                  = count($dataNoKia);
        $data['jumlahKekRisti']        = $jumlahKekRisti;
        $data['jumlahGiziBukanNormal'] = $jumlahGiziBukanNormal;
        $data['tikar']                 = $tikar;
        $data['ibu_hamil']             = $ibu_hamil;
        $data['bulanan_anak']          = $bulanan_anak;
        $data['dataTahun']             = $data['ibu_hamil']['dataTahun'];
        $data['kuartal']               = $this->kuartal;
        $data['_tahun']                = $this->tahun;

        return $data;
    }

    private function conversiPercent($number, $total)
    {
        return (int) (str_replace('%', '', persen3($number, $total)));
    }
}
