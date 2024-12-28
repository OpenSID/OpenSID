<div class="row" style="display: flex; justify-content: flex-end;">
    <a href="{{ ci_route('data-kesehatan.cetak.cetak') }} {{ '?kuartal=' . $kuartal . '&tahun=' . $_tahun . '&id=' . $id }}" class="btn btn-info visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" style="margin: 10px;" target="_blank"><i class="fa fa-print"></i>
        Cetak</a>
    <a href="{{ ci_route('data-kesehatan.cetak.unduh') }}{{ '?kuartal=' . $kuartal . '&tahun=' . $_tahun . '&id=' . $id }}" class="btn btn-primary visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" style="margin: 10px;" target="_blank"><i
            class="fa fa-download"></i>
        Unduh</a>
</div>
<div class="row">
    <div class="box box-success">
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="table-datas" class="table table-bordered table-striped table-responsive">
                <thead>
                    <tr>
                        <th colspan="9" style="background-color:#efefef;">TABEL 1. JUMLAH SASARAN 1.000 HPK (IBU
                            HAMIL DAN ANAK 0-23 BULAN)</th>
                    </tr>
                    <tr>
                        <th width="15%" rowspan="2" colspan="3" class="text-center" style="vertical-align: middle;">
                            Sasaran</th>
                        <th width="45%" rowspan="2" colspan="2" class="text-center" style="vertical-align: middle;">
                            JML TOTAL RUMAH TANGGA 1.000 HPK </th>
                        <th width="20%" colspan="2" class="text-center" style="vertical-align: middle;">IBU HAMIL
                        </th>
                        <th width="20%" colspan="2" class="text-center" style="vertical-align: middle;">ANAK 0 –
                            23 BULAN</th>
                    </tr>
                    <tr>
                        <th width="10%" class="text-center" style="vertical-align: middle;">TOTAL</th>
                        <th width="10%" class="text-center" style="vertical-align: middle;">KEK/RESTI</th>
                        <th width="10%" class="text-center" style="vertical-align: middle;">TOTAL</th>
                        <th width="10%" class="text-center" style="vertical-align: middle;">GIZI KURANG/ GIZI
                            BURUK/STUNTING</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-center" style="vertical-align: middle;">Jumlah</th>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">{{ $JTRT }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['dataFilter'] == null ? '0' : sizeof($ibu_hamil['dataFilter']) }}</td>
                        <td class="text-center" style="vertical-align: middle;">{{ $jumlahKekRisti }}</td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['dataFilter'] == null ? '0' : sizeof($bulanan_anak['dataFilter']) }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">{{ $jumlahGiziBukanNormal }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="9" style="background-color:#efefef;">TABEL 2. HASIL PENGUKURAN TIKAR
                            PERTUMBUHAN (DETEKSI DINI STUNTING) </th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-center" style="vertical-align: middle;">Sasaran</th>
                        <th colspan="1" width="22%" class="text-center" style="vertical-align: middle;">JUMLAH
                            TOTAL ANAK USIA 0 – 23 BULAN </th>
                        <th colspan="1" width="23%" class="text-center" style="vertical-align: middle;">HIJAU
                            (NORMAL)</th>
                        <th colspan="2" class="text-center" style="vertical-align: middle;">Kuning (Resiko
                            Stunting)</th>
                        <th colspan="2" class="text-center" style="vertical-align: middle;">Merah Terindikasi
                            Stunting</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-center" style="vertical-align: middle;">Jumlah</th>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['dataFilter'] == null ? '0' : sizeof($bulanan_anak['dataFilter']) }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">{{ $tikar['H'] }}
                        </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">{{ $tikar['K'] }}
                        </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">{{ $tikar['M'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="9" style="background-color:#efefef;">TABEL 3. KELENGKAPAN KONVERGENSI PAKET
                            LAYANAN PENCEGAHAN STUNTING BAGI 1.000 HPK </th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center" style="vertical-align: middle;">Sasaran</th>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">No</th>
                        <th colspan="3" class="text-center" style="vertical-align: middle;">Indikator</th>
                        <th colspan="2" class="text-center" style="vertical-align: middle;">Jumlah</th>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">%</th>
                    </tr>
                    <tr>
                        <th colspan="2" rowspan="8" class="text-center" style="vertical-align: middle;">Ibu Hamil
                        </th>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">1</th>
                        <td colspan="3" style="vertical-align: middle;">Ibu hamil periksa kehamilan paling sedikit 4
                            kali selama kehamilan kehamilan.</td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['periksa_kehamilan']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['periksa_kehamilan']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">2</th>
                        <td colspan="3" style="vertical-align: middle;">Ibu hamil mendapatkan dan minum 1 tablet
                            tambah darah (pil FE) setiap hari minimal selama 90 hari </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['pil_fe']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['pil_fe']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">3</th>
                        <td colspan="3" style="vertical-align: middle;">Ibu bersalin mendapatkan layanan nifas oleh
                            nakes dilaksanakan minimal 3 kali </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['pemeriksaan_nifas']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['pemeriksaan_nifas']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">4</th>
                        <td colspan="3" style="vertical-align: middle;">Ibu hamil mengikuti kegiatan konseling gizi
                            atau kelas ibu hamil minimal 4 kali selama kehamilan </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['konseling_gizi']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['konseling_gizi']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">5</th>
                        <td colspan="3" style="vertical-align: middle;">Ibu hamil dengan kondisi resiko tinggi
                            dan/atau Kekurangan Energi Kronis (KEK) mendapat kunjungan ke rumah oleh bidan Desa
                            secara terpadu minimal 1 bulan sekali </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['kunjungan_rumah']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['kunjungan_rumah']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">6</th>
                        <td colspan="3" style="vertical-align: middle;">Rumah Tangga Ibu hamil memiliki sarana akses
                            air minum yang aman</td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['akses_air_bersih']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['akses_air_bersih']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">7</th>
                        <td colspan="3" style="vertical-align: middle;">Rumah Tangga Ibu hamil memiliki sarana
                            jamban keluarga yang layak</td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['kepemilikan_jamban']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['kepemilikan_jamban']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">8</th>
                        <td colspan="3" style="vertical-align: middle;">Ibu hamil memiliki jaminan layanan kesehatan
                        </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['jaminan_kesehatan']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $ibu_hamil['capaianKonvergensi'] == null ? '0' : $ibu_hamil['capaianKonvergensi']['jaminan_kesehatan']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2" rowspan="11" class="text-center" style="vertical-align: middle;">Anak 0
                            sd 23 Bulan (0 sd 2 Tahun)</th>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">1</th>
                        <td colspan="3" style="vertical-align: middle;">Bayi usia 12 bulan ke bawah mendapatkan
                            imunisasi dasar lengkap</td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['imunisasi']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['imunisasi']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">2</th>
                        <td colspan="3" style="vertical-align: middle;">Anak usia 0-23 bulan diukur berat badannya
                            di posyandu secara rutin setiap bulan </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['pengukuran_berat_badan']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['pengukuran_berat_badan']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">3</th>
                        <td colspan="3" style="vertical-align: middle;">Anak usia 0-23 bulan diukur panjang/tinggi
                            badannya oleh tenaga kesehatan terlatih minimal 2 kali dalam setahun </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['pengukuran_tinggi_badan']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['pengukuran_tinggi_badan']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" rowspan="2" class="text-center" style="vertical-align: middle;">4</th>
                        <td colspan="3" rowspan="2" style="vertical-align: middle;">Orang tua/pengasuh yang memiliki
                            anak usia 0-23 bulan mengikuti kegiatan konseling gizi secara rutin minimal sebulan
                            sekali. </td>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">Laki</th>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">Jumlah</th>
                        <td colspan="1" class="text-center" style="vertical-align: middle;"></td>
                    </tr>
                    <tr>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">0</td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">0</td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">0.00</td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">5</th>
                        <td colspan="3" style="vertical-align: middle;">Anak usia 0-23 bulan dengan status gizi
                            buruk, gizi kurang, dan stunting mendapat kunjungan ke rumah secara terpadu minimal 1
                            bulan sekali </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['kunjungan_rumah']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['kunjungan_rumah']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">6</th>
                        <td colspan="3" style="vertical-align: middle;">Rumah Tangga anak usia 0-23 bulan memiliki
                            sarana akses air minum yang aman</td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['air_bersih']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['air_bersih']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">7</th>
                        <td colspan="3" style="vertical-align: middle;">Rumah Tangga anak usia 0-23 bulan memiliki
                            sarana jamban yang layak</td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['jamban_sehat']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['jamban_sehat']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">8</th>
                        <td colspan="3" style="vertical-align: middle;">Anak usia 0-23 bulan memiliki akte kelahiran
                        </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['akta_lahir']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['akta_lahir']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">9</th>
                        <td colspan="3" style="vertical-align: middle;">Anak usia 0-23 bulan memiliki jaminan
                            layanan kesehatan</td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['jaminan_kesehatan']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['jaminan_kesehatan']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">10</th>
                        <td colspan="3" style="vertical-align: middle;">Orang tua/pengasuh yang memiliki anak usia
                            0-23 bulan mengikuti Kelas Pengasuhan minimal sebulan sekali </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['pengasuhan_paud']['Y'] }}
                        </td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $bulanan_anak['capaianKonvergensi'] == null ? '0' : $bulanan_anak['capaianKonvergensi']['pengasuhan_paud']['persen'] }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center" style="vertical-align: middle;">Anak 2 sd 6 Tahun
                        </th>
                        <th width="5%" colspan="1" class="text-center" style="vertical-align: middle;">1</th>
                        <td colspan="3" style="vertical-align: middle;">Anak usia 2-6 tahun terdaftar dan aktif
                            mengikuti kegiatan layanan PAUD</td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $dataAnak0sd2Tahun['jumlah'] }}</td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $dataAnak0sd2Tahun['persen'] }}</td>
                    </tr>
                    <tr>
                        <th colspan="9" style="background-color:#efefef;">TABEL 4. TINGKAT KONVERGENSI DESA </th>
                    </tr>
                    <tr>
                        <th width="5%" colspan="1" rowspan="2" class="text-center" style="vertical-align: middle;">
                            No</th>
                        <th colspan="3" rowspan="2" class="text-center" style="vertical-align: middle;">SASARAN
                        </th>
                        <th colspan="3" rowspan="1" class="text-center" style="vertical-align: middle;">JUMLAH
                            INDIKATOR</th>
                        <th colspan="2" rowspan="2" class="text-center" style="vertical-align: middle;">TINGKAT
                            KONVERGENSI (%)</th>
                    </tr>
                    <tr>
                        <th colspan="1" rowspan="1" class="text-center" style="vertical-align: middle;">YANG
                            DITERIMA</th>
                        <th colspan="2" rowspan="1" class="text-center" style="vertical-align: middle;">
                            SEHARUSNYA DITERIMA</th>
                    </tr>
                    @php
                        $JLD_IbuHamil = $ibu_hamil['tingkatKonvergensiDesa'] == null ? '0' : $ibu_hamil['tingkatKonvergensiDesa']['jumlah_diterima'];
                        $JLD_Anak = $bulanan_anak['tingkatKonvergensiDesa'] == null ? '0' : $bulanan_anak['tingkatKonvergensiDesa']['jumlah_diterima'];

                        $JYSD_IbuHamil = $ibu_hamil['tingkatKonvergensiDesa'] == null ? '0' : $ibu_hamil['tingkatKonvergensiDesa']['jumlah_seharusnya'];
                        $JYSD_Anak = $bulanan_anak['tingkatKonvergensiDesa'] == null ? '0' : $bulanan_anak['tingkatKonvergensiDesa']['jumlah_seharusnya'];

                        $PERSEN_IbuHamil = $ibu_hamil['tingkatKonvergensiDesa'] == null ? '0' : $ibu_hamil['tingkatKonvergensiDesa']['persen'];
                        $PERSEN_Anak = $bulanan_anak['tingkatKonvergensiDesa'] == null ? '0' : $bulanan_anak['tingkatKonvergensiDesa']['persen'];

                        $JLD_TOTAL = (int) $JLD_IbuHamil + (int) $JLD_Anak;
                        $JYSD_TOTAL = (int) $JYSD_IbuHamil + (int) $JYSD_Anak;

                        if ($JYSD_TOTAL != 0) {
                            $KONV_TOTAL = number_format(($JLD_TOTAL / $JYSD_TOTAL) * 100, 2);
                        } else {
                            $KONV_TOTAL = number_format(0, 2);
                        }

                    @endphp
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">1</th>
                        <td colspan="3" style="vertical-align: middle;">Ibu Hamil</td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">
                            {{ $JLD_IbuHamil }}</td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $JYSD_IbuHamil }}</td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $PERSEN_IbuHamil }}</td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">2</th>
                        <td colspan="3" style="vertical-align: middle;">Anak 0 - 23 Bulan</td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">{{ $JLD_Anak }}
                        </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">{{ $JYSD_Anak }}
                        </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">
                            {{ $PERSEN_Anak }}</td>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-center" style="vertical-align: middle;">TOTAL TINGKAT
                            KONVERGENSI DESA</th>
                        <td colspan="1" class="text-center" style="vertical-align: middle;">{{ $JLD_TOTAL }}
                        </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">{{ $JYSD_TOTAL }}
                        </td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">{{ $KONV_TOTAL }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="9" style="background-color:#efefef;">TABEL 5. PENGGUNAAN DANA DESA DALAM
                            PENCEGAHAN STUNTING</th>
                    </tr>
                    <tr>
                        <th width="5%" colspan="1" rowspan="2" class="text-center" style="vertical-align: middle;">
                            No</th>
                        <th colspan="3" rowspan="2" class="text-center" style="vertical-align: middle;">
                            BIDANG/KEGIATAN </th>
                        <th colspan="1" rowspan="2" class="text-center" style="vertical-align: middle;">TOTAL
                            ALOKASI DANA</th>
                        <th colspan="4" rowspan="1" class="text-center" style="vertical-align: middle;">KEGIATAN
                            KHUSUS PENCEGAHAN STUNTING</th>
                    </tr>
                    <tr>
                        <th colspan="2" rowspan="1" class="text-center" style="vertical-align: middle;">ALOKASI
                            DANA</th>
                        <th colspan="2" rowspan="1" class="text-center" style="vertical-align: middle;">%
                            (PERSEN) </th>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">1</th>
                        <td colspan="3" style="vertical-align: middle;">Bidang Pembangunan Desa</td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;"></td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;"></td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">%</td>
                    </tr>
                    <tr>
                        <th colspan="1" class="text-center" style="vertical-align: middle;">2</th>
                        <td colspan="3" style="vertical-align: middle;">Bidang Pemberdayaan Masyarakat Desa</td>
                        <td colspan="1" class="text-center" style="vertical-align: middle;"></td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;"></td>
                        <td colspan="2" class="text-center" style="vertical-align: middle;">%</td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
</div>
