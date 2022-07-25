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
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use Illuminate\Support\Facades\DB;

class Rekap
{
    /**
     * @var CI_Controller
     */
    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function get_data_ibu_hamil($kuartal = null, $tahun = null, $id = null)
    {
        if ($kuartal == 1) {
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
            show_404('Terjadi Kesalahan di kuartal!');
        }

        $ibuHamil = DB::table('ibu_hamil')
            ->join('kia', 'ibu_hamil.kia_id', '=', 'kia.id')
            ->join('tweb_penduduk', 'kia.ibu_id', '=', 'tweb_penduduk.id')
            ->whereMonth('ibu_hamil.created_at', '>=', $batasBulanBawah)
            ->whereMonth('ibu_hamil.created_at', '<=', $batasBulanAtas)
            ->whereYear('ibu_hamil.created_at', $tahun)
            ->orderBy('ibu_hamil.created_at')
            ->select([
                'ibu_hamil.*',
                'kia.no_kia',
                'kia.ibu_id',
                'kia.anak_id',
                'tweb_penduduk.nama',
            ]);

        if ($this->ci->session->userdata('isAdmin')->id_grup !== '1') {
            $ibuHamil = $ibuHamil->where('id', $this->ci->session->userdata('id'));
        } else {
            if ($id != null) {
                $ibuHamil = $ibuHamil->where('posyandu_id', $id);
            }
        }

        $ibuHamil  = $ibuHamil->get()->toArray();
        $dataTahun = DB::table('ibu_hamil')
            ->selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->get();

        if ($ibuHamil) {
            foreach ($ibuHamil as $item) {
                $item                        = (array) $item;
                $dataGrup[$item['kia_id']][] = $item;
            }

            foreach ($dataGrup as $key => $value) {
                $isSudahMelahirkan = false;
                $dataUsiaKehamilan = -1;

                $hitungPeriksaKehamilan  = 0;
                $hitungPilFe             = 0;
                $hitungPeriksaNifas      = 0;
                $hitungKonseling         = 0;
                $hitungKunjunganRumah    = 0;
                $hitungAksesAirBersih    = 0;
                $hitungKepemilikanJamban = 0;
                $hitungJaminanKesehatan  = 0;

                foreach ($value as $item) {
                    // FIND USIA KEHAMILAN : CARI YANG TERBESAR USIA KEHAMILANYA
                    if ($item['tanggal_melahirkan']) {
                        $isSudahMelahirkan  = true;
                        $tanggal_melahirkan = $item['tanggal_melahirkan'];
                    }

                    if ($dataUsiaKehamilan < (int) $item['usia_kehamilan']) {
                        $dataUsiaKehamilan = (int) $item['usia_kehamilan'];
                        if ($dataUsiaKehamilan <= 3) {
                            $dataUsiaKehamilan = '0 - 3 Bulan (Trisemester 1)';
                        } elseif ($dataUsiaKehamilan <= 6) {
                            $dataUsiaKehamilan = '4 - 6 Bulan (Trisemester 2)';
                        } elseif ($dataUsiaKehamilan <= 9) {
                            $dataUsiaKehamilan = '7 - 9 Bulan (Trisemester 3)';
                        } else {
                            $dataUsiaKehamilan = 'Ibu Bersalin';
                        }
                    }

                    //HITUNG PERIKSA KEHAMILAN
                    if ($item['pemeriksaan_kehamilan'] == 1) {
                        $hitungPeriksaKehamilan++;
                    }

                    //HITUNG PIL FE
                    if ($item['konsumsi_pil_fe'] == 1) {
                        $hitungPilFe++;
                    }

                    //HITUNG PERIKSA NIFAS
                    if ($item['pemeriksaan_nifas'] == 1) {
                        $hitungPeriksaNifas++;
                    }

                    //HITUNG KONSELING
                    if ($item['konseling_gizi'] == 1) {
                        $hitungKonseling++;
                    }

                    //HITUNG KUNJUNGAN RUMAH
                    if ($item['kunjungan_rumah'] == 1) {
                        $hitungKunjunganRumah++;
                    }

                    //HITUNG AKSES AIR BERSIH
                    if ($item['akses_air_bersih'] == 1) {
                        $hitungAksesAirBersih++;
                    }

                    //HITUNG KEPEMILIKAN JAMBAN
                    if ($item['kepemilikan_jamban'] == 1) {
                        $hitungKepemilikanJamban++;
                    }

                    //HITUNG JAMINAN KESEHATAN
                    if ($item['jaminan_kesehatan'] == 1) {
                        $hitungJaminanKesehatan++;
                    }

                    // FIND STATUS KEHAMILAN : DATA TERAKHIR STATUS KEHAMILAN
                    $status_kehamilan   = $item['status_kehamilan'];
                    $usia_kehamilan     = $item['usia_kehamilan'];
                    $tanggal_melahirkan = $isSudahMelahirkan ? $tanggal_melahirkan : '-';
                }

                if ($isSudahMelahirkan) {
                    //Ibu Bersalin
                    $periksaKehamilan = 'TS';
                    $pilFe            = 'TS';
                    $periksaNifas     = $hitungPeriksaNifas >= 3 ? 'Y' : 'T';
                    $konseling        = 'TS';
                    $kunjunganRumah   = 'TS';
                } else {
                    if ($dataUsiaKehamilan <= 3) {
                        // 0 - 3 Bulan (Trisemester 1)
                        $periksaKehamilan = $hitungPeriksaKehamilan >= 1 ? 'Y' : 'T';
                        $pilFe            = $hitungPilFe >= 1 ? 'Y' : 'T';
                        $periksaNifas     = 'TS';
                        $konseling        = $hitungKonseling >= 1 ? 'Y' : 'T';
                        if ($status_kehamilan == 'KEK' || $status_kehamilan == 'RISTI') {
                            $kunjunganRumah = $hitungKunjunganRumah >= 1 ? 'Y' : 'T';
                        } else {
                            $kunjunganRumah = 'T';
                        }
                    } elseif ($dataUsiaKehamilan <= 6) {
                        // 4 - 6 Bulan (Trisemester 2)
                        $periksaKehamilan = $hitungPeriksaKehamilan >= 1 ? 'Y' : 'T';
                        $pilFe            = $hitungPilFe >= 1 ? 'Y' : 'T';
                        $periksaNifas     = 'TS';
                        $konseling        = $hitungKonseling >= 1 ? 'Y' : 'T';
                        if ($status_kehamilan == 'KEK' || $status_kehamilan == 'RISTI') {
                            $kunjunganRumah = $hitungKunjunganRumah >= 1 ? 'Y' : 'T';
                        } else {
                            $kunjunganRumah = 'T';
                        }
                    } else {
                        // 7 - 9 Bulan (Trisemester 3) atau lebih
                        $periksaKehamilan = $hitungPeriksaKehamilan >= 2 ? 'Y' : 'T';
                        $pilFe            = $hitungPilFe >= 1 ? 'Y' : 'T';
                        $periksaNifas     = 'TS';
                        $konseling        = $hitungKonseling >= 2 ? 'Y' : 'T';
                        if ($status_kehamilan == 'KEK' || $status_kehamilan == 'RISTI') {
                            $kunjunganRumah = $hitungKunjunganRumah >= 1 ? 'Y' : 'T';
                        } else {
                            $kunjunganRumah = 'T';
                        }
                    }
                }

                $aksesAirBersih    = $hitungAksesAirBersih >= 1 ? 'Y' : 'T';
                $kepemilikanJamban = $hitungKepemilikanJamban >= 1 ? 'Y' : 'T';
                $jaminanKesehatan  = $hitungJaminanKesehatan >= 1 ? 'Y' : 'T';

                $dataFilter[$key]['user'] = [
                    'ket_usia_kehamilan' => $isSudahMelahirkan ? 'Ibu Bersalin' : $dataUsiaKehamilan,
                    'no_kia'             => $item['no_kia'],
                    'nama_ibu'           => $item['nama'],
                    'status_kehamilan'   => $status_kehamilan,
                    'usia_kehamilan'     => $usia_kehamilan,
                    'tanggal_melahirkan' => $tanggal_melahirkan,
                ];

                $dataFilter[$key]['indikator'] = [
                    'periksa_kehamilan'  => $periksaKehamilan,
                    'pil_fe'             => $pilFe,
                    'pemeriksaan_nifas'  => $periksaNifas,
                    'konseling_gizi'     => $konseling,
                    'kunjungan_rumah'    => $kunjunganRumah,
                    'akses_air_bersih'   => $aksesAirBersih,
                    'kepemilikan_jamban' => $kepemilikanJamban,
                    'jaminan_kesehatan'  => $jaminanKesehatan,
                ];

                foreach ($dataFilter as $key => $item) {
                    $jumlahY       = 0;
                    $jumlahT       = 0;
                    $jumlahTS      = 0;
                    $jumlahLayanan = count($item['indikator']);

                    foreach ($item['indikator'] as $indikator) {
                        if ($indikator == 'Y') {
                            $jumlahY++;
                        }

                        if ($indikator == 'T') {
                            $jumlahT++;
                        }

                        if ($indikator == 'TS') {
                            $jumlahTS++;
                        }
                    }

                    $jumlahSeharusnya                          = (int) $jumlahLayanan - (int) $jumlahTS;
                    $dataFilter[$key]['konvergensi_indikator'] = [
                        'jumlah_diterima_lengkap' => $jumlahY,
                        'jumlah_seharusnya'       => $jumlahSeharusnya,
                        'persen'                  => $jumlahSeharusnya == 0 ? '0.00' : number_format($jumlahY / $jumlahSeharusnya * 100, 2),
                    ];
                }
            }

            $capaianKonvergensi = [
                'periksa_kehamilan'  => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'pil_fe'             => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'pemeriksaan_nifas'  => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'konseling_gizi'     => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'kunjungan_rumah'    => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'akses_air_bersih'   => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'kepemilikan_jamban' => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'jaminan_kesehatan'  => ['Y' => 0, 'T' => 0, 'TS' => 0],
            ];

            foreach ($dataFilter as $item) {
                $capaianKonvergensi['periksa_kehamilan'][$item['indikator']['periksa_kehamilan']]++;
                $capaianKonvergensi['pil_fe'][$item['indikator']['pil_fe']]++;
                $capaianKonvergensi['pemeriksaan_nifas'][$item['indikator']['pemeriksaan_nifas']]++;
                $capaianKonvergensi['konseling_gizi'][$item['indikator']['konseling_gizi']]++;
                $capaianKonvergensi['kunjungan_rumah'][$item['indikator']['kunjungan_rumah']]++;
                $capaianKonvergensi['akses_air_bersih'][$item['indikator']['akses_air_bersih']]++;
                $capaianKonvergensi['kepemilikan_jamban'][$item['indikator']['kepemilikan_jamban']]++;
                $capaianKonvergensi['jaminan_kesehatan'][$item['indikator']['jaminan_kesehatan']]++;
            }

            foreach ($capaianKonvergensi as $key => $item) {
                $capaianKonvergensijumlahSeharusnya            = count($dataFilter) - (int) $item['TS'];
                $capaianKonvergensi[$key]['jumlah_seharusnya'] = $capaianKonvergensijumlahSeharusnya;
                $capaianKonvergensi[$key]['persen']            = $capaianKonvergensijumlahSeharusnya == 0 ? '0.00' : number_format($item['Y'] / $capaianKonvergensijumlahSeharusnya * 100, 2);
            }

            $totalIndikator         = count($capaianKonvergensi) * count($dataFilter);
            $tingkatKonvergensiDesa = [
                'jumlah_diterima'   => 0,
                'jumlah_seharusnya' => 0,
                'persen'            => 0,
            ];

            $TotalTS = 0;

            foreach ($capaianKonvergensi as $item) {
                $tingkatKonvergensiDesa['jumlah_diterima'] += $item['Y'];
                $TotalTS += $item['TS'];
            }

            $tingkatKonvergensiDesa['jumlah_seharusnya'] = $totalIndikator - $TotalTS;
            $tingkatKonvergensiDesa['persen']            = $tingkatKonvergensiDesa['jumlah_seharusnya'] == 0 ? '0.00' : number_format($tingkatKonvergensiDesa['jumlah_diterima'] / $tingkatKonvergensiDesa['jumlah_seharusnya'] * 100, 2);
        } else {
            $dataGrup               = null;
            $dataFilter             = null;
            $capaianKonvergensi     = null;
            $tingkatKonvergensiDesa = null;
        }

        $data['dataFilter']             = $dataFilter;
        $data['capaianKonvergensi']     = $capaianKonvergensi;
        $data['tingkatKonvergensiDesa'] = $tingkatKonvergensiDesa;
        $data['dataGrup']               = $dataGrup;

        $data['batasBulanBawah'] = $batasBulanBawah;
        $data['batasBulanAtas']  = $batasBulanAtas;
        $data['_tahun']          = $tahun;
        $data['ibuHamil']        = $ibuHamil;
        $data['dataTahun']       = $dataTahun;
        $data['kuartal']         = $kuartal;

        return $data;
    }

    public function get_data_bulanan_anak($kuartal = null, $tahun = null, $id = null)
    {
        if ($kuartal == 1) {
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
            show_404('Terjadi Kesalahan di kuartal!');
        }

        $bulananAnak = DB::table('bulanan_anak')
            ->join('kia', 'bulanan_anak.kia_id', '=', 'kia.id')
            ->join('tweb_penduduk', 'kia.anak_id', '=', 'tweb_penduduk.id')
            ->whereMonth('bulanan_anak.created_at', '>=', $batasBulanBawah)
            ->whereMonth('bulanan_anak.created_at', '<=', $batasBulanAtas)
            ->whereYear('bulanan_anak.created_at', $tahun)
            ->orderBy('bulanan_anak.created_at')
            ->select([
                'bulanan_anak.*',
                'kia.no_kia',
                'kia.ibu_id',
                'kia.anak_id',
                'tweb_penduduk.nama',
                'tweb_penduduk.sex',
            ]);

        if ($this->ci->session->userdata('isAdmin')->id_grup !== '1') {
            $bulananAnak = $bulananAnak->where('id', $this->ci->session->userdata('id'));
        } else {
            if ($id != null) {
                $bulananAnak = $bulananAnak->where('posyandu_id', $id);
            }
        }

        $bulananAnak = $bulananAnak->get()->toArray();
        $dataTahun   = DB::table('bulanan_anak')
            ->selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->get();

        if ($bulananAnak) {
            foreach ($bulananAnak as $item) {
                $item                        = (array) $item;
                $dataGrup[$item['kia_id']][] = $item;
            }

            // d($dataGrupLengkap);
            foreach ($dataGrup as $key => $value) {
                $umurAnak               = 0;
                $hitungImunisasi        = 0;
                $hitungImunisasiCampak  = 0;
                $hitungKunjunganRumah   = 0;
                $hitungAksesAirBersih   = 0;
                $hitungJambanSehat      = 0;
                $hitungAktaLahir        = 0;
                $hitungJaminanKesehatan = 0;

                foreach ($value as $item) {
                    if ($umurAnak < (int) $item['umur_bulan']) {
                        $umurAnak = (int) $item['umur_bulan'];
                        if ($umurAnak < 6) {
                            $kategoriUmur = 1;
                            $usiaAnak     = '0 - < 6 Bulan';
                        } elseif ($umurAnak <= 12) {
                            $kategoriUmur = 2;
                            $usiaAnak     = '6 - 12 Bulan';
                        } elseif ($umurAnak > 12 && $umurAnak < 18) {
                            $kategoriUmur = 3;
                            $usiaAnak     = '> 12 - < 18 Bulan';
                        } else {
                            $kategoriUmur = 4;
                            $usiaAnak     = '> 18 - 23 Bulan';
                        }
                    }

                    if ($item['pemberian_imunisasi_dasar'] == 1) {
                        $hitungImunisasi++;
                    }

                    if ($item['pemberian_imunisasi_campak'] == 1) {
                        $hitungImunisasiCampak++;
                    }

                    if ($item['kunjungan_rumah'] == 1) {
                        $hitungKunjunganRumah++;
                    }

                    if ($item['air_bersih'] == 1) {
                        $hitungAksesAirBersih++;
                    }

                    if ($item['akta_lahir'] == 1) {
                        $hitungAktaLahir++;
                    }

                    if ($item['jaminan_kesehatan'] == 1) {
                        $hitungJaminanKesehatan++;
                    }

                    if ($item['kepemilikan_jamban'] == 1) {
                        $hitungJambanSehat++;
                    }

                    $statusGizi = $item['status_gizi'];
                }

                // HITUNG PENIMBANGAN DALAM 1 TAHUN
                $hitungPenimbangan = DB::table('bulanan_anak')
                    ->where('kia_id', $key)
                    ->where('pengukuran_berat_badan', '1')
                    ->count();

                //HITUNG KONSELING DALAM 1 TAHUN
                $KonselingGizi = DB::table('bulanan_anak')
                    ->where('kia_id', $key)
                    ->select(['konseling_gizi_ayah', 'konseling_gizi_ibu'])
                    ->get();

                $KGL = 0;
                $KGP = 0;

                foreach ($KonselingGizi as $item) {
                    if ($item->konseling_gizi_ayah == 1) {
                        $KGL++;
                    }
                    if ($item->konseling_gizi_ibu == 1) {
                        $KGP++;
                    }
                }
                $JUMLAH_KG = $KGP;

                //HITUNG PENGASUHAN DALAM 1 TAHUN
                $hitungPengasuhan = DB::table('bulanan_anak')
                    ->where('kia_id', $key)
                    ->where('pengasuhan_paud', '1')
                    ->whereYear('bulanan_anak.created_at', $tahun)
                    ->select('pengasuhan_paud')
                    ->count();

                if ($kategoriUmur == 1) {
                    $imunisasi             = 'TS';
                    $penimbanganBeratBadan = 'TS';
                    $konseling_gizi        = 'TS';
                    $kunjungan_rumah       = $hitungKunjunganRumah >= 2 ? 'Y' : 'T';
                    $air_bersih            = $hitungAksesAirBersih >= 1 ? 'Y' : 'T';
                    $jamban_sehat          = $hitungJambanSehat >= 1 ? 'Y' : 'T';
                    $jaminanKesehatan      = $hitungJaminanKesehatan >= 1 ? 'Y' : 'T';
                    $akta_lahir            = $hitungAktaLahir >= 1 ? 'Y' : 'T';
                    $pengasuhan_paud       = 'TS';
                } elseif ($kategoriUmur == 2) {
                    if ($umurAnak <= 9) {
                        $imunisasi = $hitungImunisasi > 0 ? 'Y' : 'T';
                    } else {
                        $imunisasi = $hitungImunisasi > 0 && $hitungImunisasiCampak > 0 ? 'Y' : 'T';
                    }
                    $penimbanganBeratBadan = $hitungPenimbangan >= 5 ? 'Y' : 'T';
                    $konseling_gizi        = $JUMLAH_KG >= 5 ? 'Y' : 'T';
                    $kunjungan_rumah       = $hitungKunjunganRumah >= 2 ? 'Y' : 'T';
                    $air_bersih            = $hitungAksesAirBersih >= 1 ? 'Y' : 'T';
                    $jamban_sehat          = $hitungJambanSehat >= 1 ? 'Y' : 'T';
                    $jaminanKesehatan      = $hitungJaminanKesehatan >= 1 ? 'Y' : 'T';
                    $akta_lahir            = $hitungAktaLahir >= 1 ? 'Y' : 'T';
                    $pengasuhan_paud       = $hitungPengasuhan >= 5 ? 'Y' : 'T';
                } elseif ($kategoriUmur == 3) {
                    $imunisasi             = $hitungImunisasi > 0 && $hitungImunisasiCampak > 0 ? 'Y' : 'T';
                    $penimbanganBeratBadan = $hitungPenimbangan >= 8 ? 'Y' : 'T';
                    $konseling_gizi        = $JUMLAH_KG >= 8 ? 'Y' : 'T';
                    $kunjungan_rumah       = $hitungKunjunganRumah >= 2 ? 'Y' : 'T';
                    $air_bersih            = $hitungAksesAirBersih >= 1 ? 'Y' : 'T';
                    $jamban_sehat          = $hitungJambanSehat >= 1 ? 'Y' : 'T';
                    $jaminanKesehatan      = $hitungJaminanKesehatan >= 1 ? 'Y' : 'T';
                    $akta_lahir            = $hitungAktaLahir >= 1 ? 'Y' : 'T';
                    $pengasuhan_paud       = $hitungPengasuhan >= 5 ? 'Y' : 'T';
                } elseif ($kategoriUmur == 4) {
                    $imunisasi             = $hitungImunisasi > 0 && $hitungImunisasiCampak > 0 ? 'Y' : 'T';
                    $penimbanganBeratBadan = $hitungPenimbangan >= 15 ? 'Y' : 'T';
                    $konseling_gizi        = $JUMLAH_KG >= 15 ? 'Y' : 'T';
                    $kunjungan_rumah       = $hitungKunjunganRumah >= 2 ? 'Y' : 'T';
                    $air_bersih            = $hitungAksesAirBersih >= 1 ? 'Y' : 'T';
                    $jamban_sehat          = $hitungJambanSehat >= 1 ? 'Y' : 'T';
                    $jaminanKesehatan      = $hitungJaminanKesehatan >= 1 ? 'Y' : 'T';
                    $akta_lahir            = $hitungAktaLahir >= 1 ? 'Y' : 'T';
                    $pengasuhan_paud       = $hitungPengasuhan >= 5 ? 'Y' : 'T';
                } else {
                    show_404('kesalahan di kategori umur!');
                }

                if ($kuartal == 1) {
                    if ($umurAnak <= 3) {
                        $tinggiBadan = 'TS';
                    } else {
                        // CARI TINGGI BADAN DI DATABASE
                        $hitungTinggiBadan = DB::table('bulanan_anak')
                            ->where('kia_id', $key)
                            ->where('pengukuran_tinggi_badan', '1')
                            ->whereMonth('bulanan_anak.created_at', '2') // februari
                            ->whereYear('bulanan_anak.created_at', $tahun)
                            ->select('pengukuran_tinggi_badan')
                            ->count();

                        $tinggiBadan = $hitungTinggiBadan > 0 ? 'Y' : 'T';
                    }
                } elseif ($kuartal == 2) {
                    if ($umurAnak <= 3) {
                        $tinggiBadan = 'TS';
                    } else {
                        // CARI TINGGI BADAN DI DATABASE
                        $hitungTinggiBadan = DB::table('bulanan_anak')
                            ->where('kia_id', $key)
                            ->where('pengukuran_tinggi_badan', '1')
                            ->whereMonth('bulanan_anak.created_at', '2') // februari
                            ->whereYear('bulanan_anak.created_at', $tahun)
                            ->select('pengukuran_tinggi_badan')
                            ->count();

                        $tinggiBadan = $hitungTinggiBadan > 0 ? 'Y' : 'T';
                    }
                } elseif ($kuartal == 3) {
                    if ($umurAnak <= 3) {
                        $tinggiBadan = 'TS';
                    } elseif ($umurAnak <= 8) {
                        // CARI TINGGI BADAN DI DATABASE
                        $hitungTinggiBadan = DB::table('bulanan_anak')
                            ->where('kia_id', $key)
                            ->where('pengukuran_tinggi_badan', '1')
                            ->whereMonth('bulanan_anak.created_at', '8') // agustus
                            ->whereYear('bulanan_anak.created_at', $tahun)
                            ->select('pengukuran_tinggi_badan')
                            ->count();

                        $tinggiBadan = $hitungTinggiBadan > 0 ? 'Y' : 'T';
                    } else {
                        $hitungTinggiBadan = DB::table('bulanan_anak')
                            ->where('kia_id', $key)
                            ->whereMonth('bulanan_anak.created_at', '2') // februari
                            ->orWhereMonth('bulanan_anak.created_at', '8') // agustus
                            ->whereYear('bulanan_anak.created_at', $tahun)
                            ->select('pengukuran_tinggi_badan')
                            ->get();

                        $TB_FEB_AGS = 0;

                        foreach ($hitungTinggiBadan as $item) {
                            if ($item->pengukuran_tinggi_badan == 1) {
                                $TB_FEB_AGS++;
                            }
                        }

                        $tinggiBadan = $TB_FEB_AGS > 1 ? 'Y' : 'T'; //ada di februari atau agustus
                    }
                } elseif ($kuartal == 4) {
                    if ($umurAnak <= 6) {
                        $tinggiBadan = 'TS';
                    } elseif ($umurAnak <= 11) {
                        // CARI TINGGI BADAN DI DATABASE
                        $hitungTinggiBadan = DB::table('bulanan_anak')
                            ->where('kia_id', $key)
                            ->where('pengukuran_tinggi_badan', '1')
                            ->whereMonth('bulanan_anak.created_at', '8') // agustus
                            ->select('pengukuran_tinggi_badan')
                            ->count();

                        $tinggiBadan = $hitungTinggiBadan > 0 ? 'Y' : 'T';
                    } else {
                        $hitungTinggiBadan = DB::table('bulanan_anak')
                            ->where('kia_id', $key)
                            ->whereMonth('bulanan_anak.created_at', '2') // februari
                            ->orWhereMonth('bulanan_anak.created_at', '8') // agustus
                            ->whereYear('bulanan_anak.created_at', $tahun)
                            ->select('pengukuran_tinggi_badan')
                            ->get();

                        $TB_FEB_AGS = 0;

                        foreach ($hitungTinggiBadan as $item) {
                            if ($item->pengukuran_tinggi_badan == 1) {
                                $TB_FEB_AGS++;
                            }
                        }

                        $tinggiBadan = $TB_FEB_AGS > 1 ? 'Y' : 'T'; //ada di februari atau agustus
                    }
                } else {
                    show_404('kesalahan di kuartal!');
                }

                // START--------------------------------------------------------------------------------------------
                //HAPUS KODE DI BAWAH INI JIKA PENGECEKAN TINGGI BADAN HANYA DILAKUKAN DI BULAN FEBRUARI DAN AGUSTUS
                //INI CARINYA DI DALAM 1 KUARTAL MINIMAL 1X
                $hitungTinggiBadan = DB::table('bulanan_anak')
                    ->where('kia_id', $key)
                    ->where('pengukuran_tinggi_badan', '1')
                    ->whereMonth('bulanan_anak.created_at', '>=', $batasBulanBawah)
                    ->whereMonth('bulanan_anak.created_at', '<=', $batasBulanAtas)
                    ->whereYear('bulanan_anak.created_at', $tahun)
                    ->select('pengukuran_tinggi_badan')
                    ->count();
                $tinggiBadan = $hitungTinggiBadan > 0 ? 'Y' : 'T';
                // END ---------------------------------------------------------------------------------------------

                $dataFilter[$key]['user']['no_kia']                       = $dataGrup[$key][0]['no_kia'];
                $dataFilter[$key]['user']['nama']                         = $dataGrup[$key][0]['nama'];
                $dataFilter[$key]['user']['jenis_kelamin']                = $dataGrup[$key][0]['sex'];
                $dataFilter[$key]['umur_dan_gizi']['umur_bulan']          = $umurAnak;
                $dataFilter[$key]['umur_dan_gizi']['status_gizi']         = $statusGizi;
                $dataFilter[$key]['indikator']['imunisasi']               = $imunisasi;
                $dataFilter[$key]['indikator']['pengukuran_berat_badan']  = $penimbanganBeratBadan;
                $dataFilter[$key]['indikator']['pengukuran_tinggi_badan'] = $tinggiBadan;
                $dataFilter[$key]['indikator']['konseling_gizi']          = $konseling_gizi;
                $dataFilter[$key]['indikator']['kunjungan_rumah']         = $kunjungan_rumah;
                $dataFilter[$key]['indikator']['air_bersih']              = $air_bersih;
                $dataFilter[$key]['indikator']['jamban_sehat']            = $jamban_sehat;
                $dataFilter[$key]['indikator']['akta_lahir']              = $akta_lahir;
                $dataFilter[$key]['indikator']['jaminan_kesehatan']       = $jaminanKesehatan;
                $dataFilter[$key]['indikator']['pengasuhan_paud']         = $pengasuhan_paud;

                $jumlahLayanan = count($dataFilter[$key]['indikator']);
                $jumlahY       = 0;
                $jumlahT       = 0;
                $jumlahTS      = 0;

                foreach ($dataFilter[$key]['indikator'] as $indikator) {
                    if ($indikator == 'Y') {
                        $jumlahY++;
                    }

                    if ($indikator == 'T') {
                        $jumlahT++;
                    }

                    if ($indikator == 'TS') {
                        $jumlahTS++;
                    }
                }
                $jumlahSeharusnya            = (int) $jumlahLayanan - (int) $jumlahTS;
                $tingkatKonvergensiIndikator = [
                    'jumlah_diterima_lengkap' => $jumlahY,
                    'jumlah_seharusnya'       => $jumlahSeharusnya,
                    'persen'                  => $jumlahSeharusnya == 0 ? '0.00' : number_format($jumlahY / $jumlahSeharusnya * 100, 2),
                ];
                $dataFilter[$key]['tingkat_konvergensi_indikator'] = $tingkatKonvergensiIndikator;
            }

            // KALKULASI TINGKATAN CAPAIAN KONVERGENSI
            $capaianKonvergensi = [
                'imunisasi'               => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'pengukuran_berat_badan'  => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'pengukuran_tinggi_badan' => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'konseling_gizi'          => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'kunjungan_rumah'         => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'air_bersih'              => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'jamban_sehat'            => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'akta_lahir'              => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'jaminan_kesehatan'       => ['Y' => 0, 'T' => 0, 'TS' => 0],
                'pengasuhan_paud'         => ['Y' => 0, 'T' => 0, 'TS' => 0],
            ];

            foreach ($dataFilter as $item) {
                $capaianKonvergensi['imunisasi'][$item['indikator']['imunisasi']]++;
                $capaianKonvergensi['pengukuran_berat_badan'][$item['indikator']['pengukuran_berat_badan']]++;
                $capaianKonvergensi['pengukuran_tinggi_badan'][$item['indikator']['pengukuran_tinggi_badan']]++;
                $capaianKonvergensi['konseling_gizi'][$item['indikator']['konseling_gizi']]++;
                $capaianKonvergensi['kunjungan_rumah'][$item['indikator']['kunjungan_rumah']]++;
                $capaianKonvergensi['air_bersih'][$item['indikator']['air_bersih']]++;
                $capaianKonvergensi['jamban_sehat'][$item['indikator']['jamban_sehat']]++;
                $capaianKonvergensi['akta_lahir'][$item['indikator']['akta_lahir']]++;
                $capaianKonvergensi['jaminan_kesehatan'][$item['indikator']['jaminan_kesehatan']]++;
                $capaianKonvergensi['pengasuhan_paud'][$item['indikator']['pengasuhan_paud']]++;
            }

            foreach ($capaianKonvergensi as $key => $item) {
                $capaianKonvergensijumlahSeharusnya            = count($dataFilter) - (int) $item['TS'];
                $capaianKonvergensi[$key]['jumlah_diterima']   = $item['Y'];
                $capaianKonvergensi[$key]['jumlah_seharusnya'] = $capaianKonvergensijumlahSeharusnya;
                $capaianKonvergensi[$key]['persen']            = $capaianKonvergensijumlahSeharusnya == 0 ? '0.00' : number_format($item['Y'] / $capaianKonvergensijumlahSeharusnya * 100, 2);
            }

            $totalIndikator         = count($capaianKonvergensi) * count($dataFilter);
            $tingkatKonvergensiDesa = [
                'jumlah_diterima'   => 0,
                'jumlah_seharusnya' => 0,
                'persen'            => 0,
            ];

            $TotalTS = 0;

            foreach ($capaianKonvergensi as $item) {
                $tingkatKonvergensiDesa['jumlah_diterima'] += $item['Y'];
                $TotalTS += $item['TS'];
            }

            $tingkatKonvergensiDesa['jumlah_seharusnya'] = $totalIndikator - $TotalTS;
            $tingkatKonvergensiDesa['persen']            = $tingkatKonvergensiDesa['jumlah_seharusnya'] == 0 ? '0.00' : number_format($tingkatKonvergensiDesa['jumlah_diterima'] / $tingkatKonvergensiDesa['jumlah_seharusnya'] * 100, 2);
        } else {
            $dataGrup               = null;
            $dataFilter             = null;
            $capaianKonvergensi     = null;
            $tingkatKonvergensiDesa = null;
        }

        $data['dataFilter']             = $dataFilter;
        $data['capaianKonvergensi']     = $capaianKonvergensi;
        $data['tingkatKonvergensiDesa'] = $tingkatKonvergensiDesa;
        $data['dataGrup']               = $dataGrup;

        $data['batasBulanBawah'] = $batasBulanBawah;
        $data['batasBulanAtas']  = $batasBulanAtas;

        $data['bulananAnak'] = $bulananAnak;
        $data['dataTahun']   = $dataTahun;

        $data['_tahun']  = $tahun;
        $data['kuartal'] = $kuartal;

        return $data;
    }
}
