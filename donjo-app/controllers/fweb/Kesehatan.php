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

use App\Libraries\Rekap;
use App\Models\Anak;
use App\Models\IbuHamil;
use App\Models\Pamong;
use App\Models\Posyandu;
use App\Models\SasaranPaud;

defined('BASEPATH') || exit('No direct script access allowed');

class Kesehatan extends Web_Controller
{
    private $rekap;

    public function __construct()
    {
        parent::__construct();
        $this->rekap = new Rekap();
        $this->load->helper('tglindo_helper');
    }

    public function cetak($aksi = 'cetak')
    {
        $kuartal = $this->input->get('kuartal');
        $tahun   = $this->input->get('tahun');
        $id      = $this->input->get('id');

        $data                   = $this->sumber_data($kuartal, $tahun, $id);
        $data['aksi']           = $aksi;
        $data['config']         = identitas();
        $data['pamong_ttd']     = Pamong::sekretarisDesa()->first();
        $data['pamong_ketahui'] = Pamong::kepalaDesa()->first();
        $data['file']           = 'Data Scorecard Konvergensi';
        $data['isi']            = 'web.kesehatan.cetak';
        $data['letak_ttd']      = ['1', '1', '1'];
        $data['judul']          = 'DATA SCORECARD KONVERGENSI KUARTAL ' . $kuartal . ' (' . strtoupper((string) get_kuartal($kuartal)['bulan']) . ') TAHUN ' . $tahun;

        view('theme::admin.layouts.components.format_cetak', $data);
    }

    private function sumber_data($kuartal = null, $tahun = null, $id = null)
    {
        if ($kuartal < 1 || $kuartal > 4) {
            $kuartal = null;
        }

        if ($kuartal == null) {
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
        } elseif ($kuartal == 1) {
            $batasBulanBawah = 1;
            $batasBulanAtas  = 3;
        } elseif ($kuartal == 2) {
            $batasBulanBawah = 4;
            $batasBulanAtas  = 6;
        } elseif ($kuartal == 3) {
            $batasBulanBawah = 7;
            $batasBulanAtas  = 9;
        } elseif ($kuartal == 4) {
            $batasBulanBawah = 10;
            $batasBulanAtas  = 12;
        } else {
            exit('Terjadi Kesalahan di kuartal!');
        }

        $JTRT_IbuHamil = IbuHamil::query()
            ->distinct()
            ->join('kia', 'ibu_hamil.kia_id', '=', 'kia.id')
            ->whereMonth('ibu_hamil.created_at', '>=', $batasBulanBawah)
            ->whereMonth('ibu_hamil.created_at', '<=', $batasBulanAtas)
            ->whereYear('ibu_hamil.created_at', $tahun)
            ->selectRaw('ibu_hamil.kia_id as kia_id')
            ->get();

        $JTRT_BulananAnak = Anak::query()
            ->distinct()
            ->join('kia', 'bulanan_anak.kia_id', '=', 'kia.id')
            ->whereMonth('bulanan_anak.created_at', '>=', $batasBulanBawah)
            ->whereMonth('bulanan_anak.created_at', '<=', $batasBulanAtas)
            ->whereYear('bulanan_anak.created_at', $tahun)
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

        $ibu_hamil    = $this->rekap->get_data_ibu_hamil($kuartal, $tahun, $id);
        $bulanan_anak = $this->rekap->get_data_bulanan_anak($kuartal, $tahun, $id);

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
        $anak2sd6->whereYear('sasaran_paud.created_at', $tahun)->get();

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
        if ($kuartal == 1) {
            $jmlAnk = $totalAnak['januari']['total'] + $totalAnak['februari']['total'] + $totalAnak['maret']['total'];
            $jmlV   = $totalAnak['januari']['v'] + $totalAnak['februari']['v'] + $totalAnak['maret']['v'];
        } elseif ($kuartal == 2) {
            $jmlAnk = $totalAnak['april']['total'] + $totalAnak['mei']['total'] + $totalAnak['juni']['total'];
            $jmlV   = $totalAnak['april']['v'] + $totalAnak['mei']['v'] + $totalAnak['juni']['v'];
        } elseif ($kuartal == 3) {
            $jmlAnk = $totalAnak['agustus']['total'];
            $jmlV   = $totalAnak['agustus']['v'];
        } elseif ($kuartal == 4) {
            $jmlAnk = $totalAnak['oktober']['total'] + $totalAnak['november']['total'] + $totalAnak['desember']['total'];
            $jmlV   = $totalAnak['oktober']['v'] + $totalAnak['november']['v'] + $totalAnak['desember']['v'];
        }
        $dataAnak0sd2Tahun['jumlah'] = $jmlV;
        $dataAnak0sd2Tahun['persen'] = $jmlAnk !== 0 ? number_format($jmlV / $jmlAnk * 100, 2) : 0;

        //END ANAK PAUD------------------------------------------------------------

        $data                          = $this->widget();
        $data['navigasi']              = 'scorcard-konvergensi';
        $data['dataAnak0sd2Tahun']     = $dataAnak0sd2Tahun;
        $data['id']                    = $id;
        $data['posyandu']              = Posyandu::get();
        $data['JTRT']                  = count($dataNoKia);
        $data['jumlahKekRisti']        = $jumlahKekRisti;
        $data['jumlahGiziBukanNormal'] = $jumlahGiziBukanNormal;
        $data['tikar']                 = $tikar;
        $data['ibu_hamil']             = $ibu_hamil;
        $data['bulanan_anak']          = $bulanan_anak;
        $data['dataTahun']             = $data['ibu_hamil']['dataTahun'];
        $data['kuartal']               = $kuartal;
        $data['_tahun']                = $tahun;
        $data['aktif']                 = 'scorcard';

        return $data;
    }

    public function detail($slug = null)
    {
        $this->hak_akses_menu('data-kesehatan/' . $slug);
        $idPosyandu = $this->input->get('id_posyandu');
        $kuartal    = $this->input->get('kuartal');
        $tahun      = $this->input->get('tahun') ?? date('Y');
        if ($kuartal == null) {
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

            $kuartal = $_kuartal;
        }
        $dataTahun = IbuHamil::selectRaw('YEAR(created_at) as tahun')->distinct()->get();
        if ($dataTahun->isEmpty()) {
            $defaultIbuHamilTahun        = new IbuHamil();
            $defaultIbuHamilTahun->tahun = date('Y');
            $dataTahun                   = collect([$defaultIbuHamilTahun]);
        }
        $data['title']      = 'e-' . ucwords($slug);
        $data['idPosyandu'] = $idPosyandu;
        $data['dataTahun']  = $dataTahun;
        $data['kuartal']    = $kuartal;
        $data['tahun']      = $tahun;
        $data['posyandu']   = Posyandu::select(['id', 'nama'])->get();

        return view('theme::partials.kesehatan.index', $data);
    }

    public function scorecard()
    {
        $scorecard = request()->get('scorecard');

        return view('theme::partials.kesehatan.scorecard', $scorecard);
    }

    private function widget(): array
    {
        return [
            [
                'title'    => 'Ibu Hamil Periksa Bulan ini',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-blue',
                'bg-icon'  => 'ion-stats-bars',
                'total'    => IbuHamil::whereMonth('created_at', date('m'))->count(),
            ],
            [
                'title'    => 'Anak Periksa Bulan ini',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-gray',
                'bg-icon'  => 'ion-stats-bars',
                'total'    => Anak::whereMonth('created_at', date('m'))->count(),
            ],
            [
                'title'    => 'Ibu Hamil & Anak 0-23 Bulan',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-green',
                'bg-icon'  => 'ion-stats-bars',
                'total'    => IbuHamil::count() + Anak::count(),
            ],
            [
                'title'    => 'Anak 0-23 Bulan Normal',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-green',
                'bg-icon'  => 'ion-stats-bars',
                'total'    => Anak::normal()->count(),
            ],
            [
                'title'    => 'Anak 0-23 Bulan Resiko Stunting',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-yellow',
                'bg-icon'  => 'ion-stats-bars',
                'total'    => Anak::resikoStunting()->count(),
            ],
            [
                'title'    => 'Anak 0-23 Bulan Stunting',
                'icon'     => 'ion-woman',
                'bg-color' => 'bg-red',
                'bg-icon'  => 'ion-stats-bars',
                'total'    => Anak::stunting()->count(),
            ],
        ];
    }
}
