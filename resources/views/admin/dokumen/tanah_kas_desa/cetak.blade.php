<table>
    <tbody>
        <tr>
            <td class="text-center">
                <h4>{{ strtoupper('BUKU TANAH KAS ' . setting('sebutan_desa') . ' BULAN ' . getBulan($bulan) . ' TAHUN ' . $tahun) }}</h4>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <table class="border thick">
                    <thead>
                        <tr class="border thick">
                            <th rowspan="3">NOMOR URUT</th>
                            <th rowspan="3">ASAL TANAH KAS DESA</th>
                            <th rowspan="3">NOMOR SERTIFIKAT BUKU LETTER C / PERSIL</th>
                            <th rowspan="3">LUAS (m)</th>
                            <th rowspan="3">KELAS</th>
                            <th colspan="6">PEROLEHAN TKD</th>
                            <th colspan="5">JENIS TKD</th>
                            <th colspan="2">PATOK TANDA BATAS</th>
                            <th colspan="2">PAPAN NAMA</th>
                            <th rowspan="3">LOKASI</th>
                            <th rowspan="3">PERUNTUKAN</th>
                            <th rowspan="3">MUTASI</th>
                            <th rowspan="3">KET</th>
                        </tr>
                        <tr class="border thick">
                            <th rowspan="2">ASLI MILIK DESA</th>
                            <th colspan="3">BANTUAN</th>
                            <th rowspan="2">LAIN - LAIN</th>
                            <th width="100" rowspan="2">TGL PEROLEHAN</th>
                            <th rowspan="2">SAWAH</th>
                            <th rowspan="2">TEGAL</th>
                            <th rowspan="2">KEBUN</th>
                            <th rowspan="2">TAMBAK / KOLAM</th>
                            <th rowspan="2">TANAH KERING / DARAT</th>
                            <th rowspan="2">ADA</th>
                            <th rowspan="2">TIDAK</th>
                            <th rowspan="2">ADA</th>
                            <th rowspan="2">TIDAK</th>
                        </tr>
                        <tr class="border thick">
                            <th>PEMERINTAH</th>
                            <th>PROV</th>
                            <th>KAB/KOTA</th>
                        </tr>
                        <tr class="border thick">
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                            <th>6</th>
                            <th>7</th>
                            <th>8</th>
                            <th>9</th>
                            <th>10</th>
                            <th>11</th>
                            <th>12</th>
                            <th>13</th>
                            <th>14</th>
                            <th>15</th>
                            <th>16</th>
                            <th>17</th>
                            <th>18</th>
                            <th>19</th>
                            <th>20</th>
                            <th>21</th>
                            <th>22</th>
                            <th>23</th>
                            <th>24</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main as $key => $data)
                            <tr>
                                <td class="text-left">{{ $key + 1 }}</td>
                                <td>{{ strtoupper($data['ref_asal_tanah_kas']['nama']) }}</td>
                                <td class="text-left">{{ $data['letter_c'] }}</td>
                                <td>{{ strtoupper($data['luas']) }}</td>
                                <td>{{ strtoupper($data['ref_persil_kelas']['kode']) }}</td>
                                <td class="text-center">{{ $data['asli_milik_desa'] ?: '' }}</td>
                                <td class="text-center">{{ $data['pemerintah'] ?: '' }}</td>
                                <td class="text-center">{{ $data['provinsi'] ?: '' }}</td>
                                <td class="text-center">{{ $data['kabupaten_kota'] ?: '' }}</td>
                                <td class="text-center">{{ $data['lain_lain'] ?: '' }}</td>
                                <td>{{ tgl_indo_out($data['tanggal_perolehan']) }}</td>
                                <td class="text-center">{{ $data['sawah'] ?: '' }}</td>
                                <td class="text-center">{{ $data['tegal'] ?: '' }}</td>
                                <td class="text-center">{{ $data['kebun'] ?: '' }}</td>
                                <td class="text-center">{{ $data['tambak_kolam'] ?: '' }}</td>
                                <td class="text-center">{{ $data['tanah_kering_darat'] ?: '' }}</td>
                                <td class="text-center">{{ $data['ada_patok'] ?: '' }}</td>
                                <td class="text-center">{{ $data['tidak_ada_patok'] ?: '' }}</td>
                                <td class="text-center">{{ $data['ada_papan_nama'] ?: '' }}</td>
                                <td class="text-center">{{ $data['tidak_ada_papan_nama'] ?: '' }}</td>
                                <td>{{ strtoupper($data['lokasi']) }}</td>
                                <td class="text-center">{{ strtoupper($data['ref_peruntukan_tanah_kas']['nama'] ?: '') }}</td>
                                <td>{{ strtoupper($data['mutasi']) }}</td>
                                <td>{{ strtoupper($data['keterangan']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
