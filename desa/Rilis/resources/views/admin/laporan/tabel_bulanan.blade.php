@push('css')
    <style type="text/css">
        .italic {
            font-style: italic;
        }
    </style>
@endpush
<div class="table-responsive">
    <table id="tfhover" class="table table-bordered table-hover tftable lap-bulanan">
        <thead class="bg-gray">
            <tr>
                <th rowspan="3" width='2%' class="text-center">No</th>
                <th rowspan="3" colspan="2" width='30%' class="text-center">Perincian</th>
                <th colspan="7" width='45%' class="text-center">Penduduk</th>
                <th colspan="3" rowspan="2" width='23%'class="text-center">Keluarga (KK)</th>
            </tr>
            <tr>
                <th colspan="2" class="text-center">WNI</th>
                <th colspan="2" class="text-center">WNA</th>
                <th colspan="3" class="text-center">Jumlah</th>
            </tr>
            <tr>
                <th class="text-center">L</th>
                <th class="text-center">P</th>
                <th class="text-center">L</th>
                <th class="text-center">P</th>
                <th class="text-center">L</th>
                <th class="text-center">P</th>
                <th class="text-center">L+P</th>
                <th class="text-center">L</th>
                <th class="text-center">P</th>
                <th class="text-center">L+P</th>
            </tr>
            <tr>
                <th class="text-center italic">1</th>
                <th class="text-center italic" colspan="2">2</th>
                <th class="text-center italic">3</th>
                <th class="text-center italic">4</th>
                <th class="text-center italic">5</th>
                <th class="text-center italic">6</th>
                <th class="text-center italic">7</th>
                <th class="text-center italic">8</th>
                <th class="text-center italic">9</th>
                <th class="text-center italic">10</th>
                <th class="text-center italic">11</th>
                <th class="text-center italic">12</th>
            </tr </thead>
        <tbody>
            <tr>
                <td class="no_urut">1</td>
                <td colspan="2">Penduduk/Keluarga awal bulan ini</td>
                <td class="bilangan">{!! $penduduk_awal['WNI_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.awal.wni_l') . '">' . $penduduk_awal['WNI_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_awal['WNI_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.awal.wni_p') . '">' . $penduduk_awal['WNI_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_awal['WNA_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.awal.wna_l') . '">' . $penduduk_awal['WNA_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_awal['WNA_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.awal.wna_p') . '">' . $penduduk_awal['WNA_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_awal['WNI_L'] + $penduduk_awal['WNA_L'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.awal.jml_l') . '">' . ($penduduk_awal['WNI_L'] + $penduduk_awal['WNA_L']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_awal['WNI_P'] + $penduduk_awal['WNA_P'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.awal.jml_p') . '">' . ($penduduk_awal['WNI_P'] + $penduduk_awal['WNA_P']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_awal['WNI_L'] + $penduduk_awal['WNA_L'] + ($penduduk_awal['WNI_P'] + $penduduk_awal['WNA_P']) != 0
                    ? '<a href="' . ci_route('laporan.detail_penduduk.awal.jml') . '">' . ($penduduk_awal['WNI_L'] + $penduduk_awal['WNA_L'] + ($penduduk_awal['WNI_P'] + $penduduk_awal['WNA_P'])) . '</a>'
                    : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_awal['KK_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.awal.kk_l') . '">' . $penduduk_awal['KK_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_awal['KK_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.awal.kk_p') . '">' . $penduduk_awal['KK_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_awal['KK'] ? '<a href="' . ci_route('laporan.detail_penduduk.awal.kk') . '">' . $penduduk_awal['KK'] . '</a>' : '-' !!}</td>
            </tr>
            <tr>
                <td class="no_urut">2</td>
                <td colspan="2">Kelahiran/Keluarga baru bulan ini</td>
                <td class="bilangan">{!! $kelahiran['WNI_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.lahir.wni_l') . '">' . $kelahiran['WNI_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kelahiran['WNI_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.lahir.wni_p') . '">' . $kelahiran['WNI_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kelahiran['WNA_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.lahir.wna_l') . '">' . $kelahiran['WNA_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kelahiran['WNA_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.lahir.wna_p') . '">' . $kelahiran['WNA_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kelahiran['WNI_L'] + $kelahiran['WNA_L'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.lahir.jml_l') . '">' . ($kelahiran['WNI_L'] + $kelahiran['WNA_L']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kelahiran['WNI_P'] + $kelahiran['WNA_P'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.lahir.jml_p') . '">' . ($kelahiran['WNI_P'] + $kelahiran['WNA_P']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kelahiran['WNI_L'] + $kelahiran['WNA_L'] + ($kelahiran['WNI_P'] + $kelahiran['WNA_P']) != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.lahir.jml') . '">' . ($kelahiran['WNI_L'] + $kelahiran['WNA_L'] + ($kelahiran['WNI_P'] + $kelahiran['WNA_P'])) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kelahiran['KK_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.lahir.kk_l') . '">' . $kelahiran['KK_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kelahiran['KK_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.lahir.kk_p') . '">' . $kelahiran['KK_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kelahiran['KK'] ? '<a href="' . ci_route('laporan.detail_penduduk.lahir.kk') . '">' . $kelahiran['KK'] . '</a>' : '-' !!}</td>
            </tr>
            <tr>
                <td class="no_urut">3</td>
                <td colspan="2">Kematian bulan ini</td>
                <td class="bilangan">{!! $kematian['WNI_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.mati.wni_l') . '">' . $kematian['WNI_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kematian['WNI_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.mati.wni_p') . '">' . $kematian['WNI_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kematian['WNA_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.mati.wna_l') . '">' . $kematian['WNA_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kematian['WNA_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.mati.wna_p') . '">' . $kematian['WNA_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kematian['WNI_L'] + $kematian['WNA_L'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.mati.jml_l') . '">' . ($kematian['WNI_L'] + $kematian['WNA_L']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kematian['WNI_P'] + $kematian['WNA_P'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.mati.jml_p') . '">' . ($kematian['WNI_P'] + $kematian['WNA_P']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kematian['WNI_L'] + $kematian['WNA_L'] + ($kematian['WNI_P'] + $kematian['WNA_P']) != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.mati.jml') . '">' . ($kematian['WNI_L'] + $kematian['WNA_L'] + ($kematian['WNI_P'] + $kematian['WNA_P'])) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kematian['KK_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.mati.kk_l') . '">' . $kematian['KK_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kematian['KK_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.mati.kk_p') . '">' . $kematian['KK_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $kematian['KK'] ? '<a href="' . ci_route('laporan.detail_penduduk.mati.kk') . '">' . $kematian['KK'] . '</a>' : '-' !!}</td>
            </tr>
            <tr>
                <td class="no_urut">4</td>
                <td colspan="2">Pendatang bulan ini</td>
                <td class="bilangan">{!! $pendatang['WNI_L'] ? '<a href="' . ci_route('laporan.detail_penduduk/datang/wni_l') . '">' . $pendatang['WNI_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pendatang['WNI_P'] ? '<a href="' . ci_route('laporan.detail_penduduk/datang/wni_p') . '">' . $pendatang['WNI_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pendatang['WNA_L'] ? '<a href="' . ci_route('laporan.detail_penduduk/datang/wna_l') . '">' . $pendatang['WNA_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pendatang['WNA_P'] ? '<a href="' . ci_route('laporan.detail_penduduk/datang/wna_p') . '">' . $pendatang['WNA_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pendatang['WNI_L'] + $pendatang['WNA_L'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk/datang/jml_l') . '">' . ($pendatang['WNI_L'] + $pendatang['WNA_L']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pendatang['WNI_P'] + $pendatang['WNA_P'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk/datang/jml_p') . '">' . ($pendatang['WNI_P'] + $pendatang['WNA_P']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pendatang['WNI_L'] + $pendatang['WNA_L'] + ($pendatang['WNI_P'] + $pendatang['WNA_P']) != 0 ? '<a href="' . ci_route('laporan.detail_penduduk/datang/jml') . '">' . ($pendatang['WNI_L'] + $pendatang['WNA_L'] + ($pendatang['WNI_P'] + $pendatang['WNA_P'])) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pendatang['KK_L'] ? '<a href="' . ci_route('laporan.detail_penduduk/datang/kk_l') . '">' . $pendatang['KK_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pendatang['KK_P'] ? '<a href="' . ci_route('laporan.detail_penduduk/datang/kk_p') . '">' . $pendatang['KK_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pendatang['KK'] ? '<a href="' . ci_route('laporan.detail_penduduk/datang/kk') . '">' . $pendatang['KK'] . '</a>' : '-' !!}</td>
            </tr>
            <tr>
                <td class="no_urut">5</td>
                <td colspan="2">Pindah/Keluarga pergi bulan ini</td>
                <td class="bilangan">{!! $pindah['WNI_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.pindah.wni_l') . '">' . $pindah['WNI_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pindah['WNI_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.pindah.wni_p') . '">' . $pindah['WNI_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pindah['WNA_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.pindah.wna_l') . '">' . $pindah['WNA_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pindah['WNA_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.pindah.wna_p') . '">' . $pindah['WNA_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pindah['WNI_L'] + $pindah['WNA_L'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.pindah.jml_l') . '">' . ($pindah['WNI_L'] + $pindah['WNA_L']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pindah['WNI_P'] + $pindah['WNA_P'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.pindah.jml_p') . '">' . ($pindah['WNI_P'] + $pindah['WNA_P']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pindah['WNI_L'] + $pindah['WNA_L'] + ($pindah['WNI_P'] + $pindah['WNA_P']) != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.pindah.jml') . '">' . ($pindah['WNI_L'] + $pindah['WNA_L'] + ($pindah['WNI_P'] + $pindah['WNA_P'])) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pindah['KK_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.pindah.kk_l') . '">' . $pindah['KK_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pindah['KK_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.pindah.kk_p') . '">' . $pindah['KK_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $pindah['KK'] ? '<a href="' . ci_route('laporan.detail_penduduk.pindah.kk') . '">' . $pindah['KK'] . '</a>' : '-' !!}</td>
            </tr>
            <tr>
                <td class="no_urut">6</td>
                <td colspan="2">Penduduk hilang bulan ini</td>
                <td class="bilangan">{!! $hilang['WNI_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.hilang.wni_l') . '">' . $hilang['WNI_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $hilang['WNI_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.hilang.wni_p') . '">' . $hilang['WNI_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $hilang['WNA_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.hilang.wna_l') . '">' . $hilang['WNA_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $hilang['WNA_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.hilang.wna_p') . '">' . $hilang['WNA_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $hilang['WNI_L'] + $hilang['WNA_L'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.hilang.jml_l') . '">' . ($hilang['WNI_L'] + $hilang['WNA_L']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $hilang['WNI_P'] + $hilang['WNA_P'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.hilang.jml_p') . '">' . ($hilang['WNI_P'] + $hilang['WNA_P']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $hilang['WNI_L'] + $hilang['WNA_L'] + ($hilang['WNI_P'] + $hilang['WNA_P']) != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.hilang.jml') . '">' . ($hilang['WNI_L'] + $hilang['WNA_L'] + ($hilang['WNI_P'] + $hilang['WNA_P'])) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $hilang['KK_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.hilang.kk_l') . '">' . $hilang['KK_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $hilang['KK_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.hilang.kk_p') . '">' . $hilang['KK_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $hilang['KK'] ? '<a href="' . ci_route('laporan.detail_penduduk.hilang.kk') . '">' . $hilang['KK'] . '</a>' : '-' !!}</td>
            </tr>
            <tr>
                <td class="no_urut">7</td>
                <td colspan="2">Penduduk/Keluarga akhir bulan ini</td>
                <td class="bilangan">{!! $penduduk_akhir['WNI_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.akhir.wni_l') . '">' . $penduduk_akhir['WNI_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_akhir['WNI_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.akhir.wni_p') . '">' . $penduduk_akhir['WNI_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_akhir['WNA_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.akhir.wna_l') . '">' . $penduduk_akhir['WNA_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_akhir['WNA_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.akhir.wna_p') . '">' . $penduduk_akhir['WNA_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_akhir['WNI_L'] + $penduduk_akhir['WNA_L'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.akhir.jml_l') . '">' . ($penduduk_akhir['WNI_L'] + $penduduk_akhir['WNA_L']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_akhir['WNI_P'] + $penduduk_akhir['WNA_P'] != 0 ? '<a href="' . ci_route('laporan.detail_penduduk.akhir.jml_p') . '">' . ($penduduk_akhir['WNI_P'] + $penduduk_akhir['WNA_P']) . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_akhir['WNI_L'] + $penduduk_akhir['WNA_L'] + ($penduduk_akhir['WNI_P'] + $penduduk_akhir['WNA_P']) != 0
                    ? '<a href="' . ci_route('laporan.detail_penduduk.akhir.jml') . '">' . ($penduduk_akhir['WNI_L'] + $penduduk_akhir['WNA_L'] + ($penduduk_akhir['WNI_P'] + $penduduk_akhir['WNA_P'])) . '</a>'
                    : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_akhir['KK_L'] ? '<a href="' . ci_route('laporan.detail_penduduk.akhir.kk_l') . '">' . $penduduk_akhir['KK_L'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_akhir['KK_P'] ? '<a href="' . ci_route('laporan.detail_penduduk.akhir.kk_p') . '">' . $penduduk_akhir['KK_P'] . '</a>' : '-' !!}</td>
                <td class="bilangan">{!! $penduduk_akhir['KK'] ? '<a href="' . ci_route('laporan.detail_penduduk.akhir.kk') . '">' . $penduduk_akhir['KK'] . '</a>' : '-' !!}</td>
            </tr>
        </tbody>
    </table>
</div>
