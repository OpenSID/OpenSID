@php
    // Helper function to display data for print vs web
    function displayData($value, $url = null, $aksi = null) {
        if (isset($aksi) && $aksi == 'cetak') {
            return show_zero_as($value, '-');
        } else {
            return $value ? '<a href="' . $url . '">' . $value . '</a>' : '-';
        }
    }
@endphp

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
                <td class="bilangan">{!! displayData($penduduk_awal['WNI_L'], ci_route('laporan.detail_penduduk.awal.wni_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['WNI_P'], ci_route('laporan.detail_penduduk.awal.wni_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['WNA_L'], ci_route('laporan.detail_penduduk.awal.wna_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['WNA_P'], ci_route('laporan.detail_penduduk.awal.wna_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['WNI_L'] + $penduduk_awal['WNA_L'], ci_route('laporan.detail_penduduk.awal.jml_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['WNI_P'] + $penduduk_awal['WNA_P'], ci_route('laporan.detail_penduduk.awal.jml_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['WNI_L'] + $penduduk_awal['WNA_L'] + ($penduduk_awal['WNI_P'] + $penduduk_awal['WNA_P']), ci_route('laporan.detail_penduduk.awal.jml'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['KK_L'], ci_route('laporan.detail_penduduk.awal.kk_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['KK_P'], ci_route('laporan.detail_penduduk.awal.kk_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_awal['KK'], ci_route('laporan.detail_penduduk.awal.kk'), $aksi ?? null) !!}</td>
            </tr>
            <tr>
                <td class="no_urut">2</td>
                <td colspan="2">Kelahiran/Keluarga baru bulan ini</td>
                <td class="bilangan">{!! displayData($kelahiran['WNI_L'], ci_route('laporan.detail_penduduk.lahir.wni_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['WNI_P'], ci_route('laporan.detail_penduduk.lahir.wni_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['WNA_L'], ci_route('laporan.detail_penduduk.lahir.wna_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['WNA_P'], ci_route('laporan.detail_penduduk.lahir.wna_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['WNI_L'] + $kelahiran['WNA_L'], ci_route('laporan.detail_penduduk.lahir.jml_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['WNI_P'] + $kelahiran['WNA_P'], ci_route('laporan.detail_penduduk.lahir.jml_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['WNI_L'] + $kelahiran['WNA_L'] + ($kelahiran['WNI_P'] + $kelahiran['WNA_P']), ci_route('laporan.detail_penduduk.lahir.jml'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['KK_L'], ci_route('laporan.detail_penduduk.lahir.kk_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['KK_P'], ci_route('laporan.detail_penduduk.lahir.kk_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kelahiran['KK'], ci_route('laporan.detail_penduduk.lahir.kk'), $aksi ?? null) !!}</td>
            </tr>
            <tr>
                <td class="no_urut">3</td>
                <td colspan="2">Kematian bulan ini</td>
                <td class="bilangan">{!! displayData($kematian['WNI_L'], ci_route('laporan.detail_penduduk.mati.wni_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['WNI_P'], ci_route('laporan.detail_penduduk.mati.wni_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['WNA_L'], ci_route('laporan.detail_penduduk.mati.wna_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['WNA_P'], ci_route('laporan.detail_penduduk.mati.wna_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['WNI_L'] + $kematian['WNA_L'], ci_route('laporan.detail_penduduk.mati.jml_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['WNI_P'] + $kematian['WNA_P'], ci_route('laporan.detail_penduduk.mati.jml_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['WNI_L'] + $kematian['WNA_L'] + ($kematian['WNI_P'] + $kematian['WNA_P']), ci_route('laporan.detail_penduduk.mati.jml'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['KK_L'], ci_route('laporan.detail_penduduk.mati.kk_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['KK_P'], ci_route('laporan.detail_penduduk.mati.kk_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($kematian['KK'], ci_route('laporan.detail_penduduk.mati.kk'), $aksi ?? null) !!}</td>
            </tr>
            <tr>
                <td class="no_urut">4</td>
                <td colspan="2">Pendatang bulan ini</td>
                <td class="bilangan">{!! displayData($pendatang['WNI_L'], ci_route('laporan.detail_penduduk/datang/wni_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['WNI_P'], ci_route('laporan.detail_penduduk/datang/wni_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['WNA_L'], ci_route('laporan.detail_penduduk/datang/wna_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['WNA_P'], ci_route('laporan.detail_penduduk/datang/wna_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['WNI_L'] + $pendatang['WNA_L'], ci_route('laporan.detail_penduduk/datang/jml_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['WNI_P'] + $pendatang['WNA_P'], ci_route('laporan.detail_penduduk/datang/jml_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['WNI_L'] + $pendatang['WNA_L'] + ($pendatang['WNI_P'] + $pendatang['WNA_P']), ci_route('laporan.detail_penduduk/datang/jml'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['KK_L'], ci_route('laporan.detail_penduduk/datang/kk_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['KK_P'], ci_route('laporan.detail_penduduk/datang/kk_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pendatang['KK'], ci_route('laporan.detail_penduduk/datang/kk'), $aksi ?? null) !!}</td>
            </tr>
            <tr>
                <td class="no_urut">5</td>
                <td colspan="2">Pindah/Keluarga pergi bulan ini</td>
                <td class="bilangan">{!! displayData($pindah['WNI_L'], ci_route('laporan.detail_penduduk.pindah.wni_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['WNI_P'], ci_route('laporan.detail_penduduk.pindah.wni_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['WNA_L'], ci_route('laporan.detail_penduduk.pindah.wna_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['WNA_P'], ci_route('laporan.detail_penduduk.pindah.wna_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['WNI_L'] + $pindah['WNA_L'], ci_route('laporan.detail_penduduk.pindah.jml_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['WNI_P'] + $pindah['WNA_P'], ci_route('laporan.detail_penduduk.pindah.jml_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['WNI_L'] + $pindah['WNA_L'] + ($pindah['WNI_P'] + $pindah['WNA_P']), ci_route('laporan.detail_penduduk.pindah.jml'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['KK_L'], ci_route('laporan.detail_penduduk.pindah.kk_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['KK_P'], ci_route('laporan.detail_penduduk.pindah.kk_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($pindah['KK'], ci_route('laporan.detail_penduduk.pindah.kk'), $aksi ?? null) !!}</td>
            </tr>
            <tr>
                <td class="no_urut">6</td>
                <td colspan="2">Penduduk hilang bulan ini</td>
                <td class="bilangan">{!! displayData($hilang['WNI_L'], ci_route('laporan.detail_penduduk.hilang.wni_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['WNI_P'], ci_route('laporan.detail_penduduk.hilang.wni_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['WNA_L'], ci_route('laporan.detail_penduduk.hilang.wna_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['WNA_P'], ci_route('laporan.detail_penduduk.hilang.wna_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['WNI_L'] + $hilang['WNA_L'], ci_route('laporan.detail_penduduk.hilang.jml_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['WNI_P'] + $hilang['WNA_P'], ci_route('laporan.detail_penduduk.hilang.jml_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['WNI_L'] + $hilang['WNA_L'] + ($hilang['WNI_P'] + $hilang['WNA_P']), ci_route('laporan.detail_penduduk.hilang.jml'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['KK_L'], ci_route('laporan.detail_penduduk.hilang.kk_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['KK_P'], ci_route('laporan.detail_penduduk.hilang.kk_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($hilang['KK'], ci_route('laporan.detail_penduduk.hilang.kk'), $aksi ?? null) !!}</td>
            </tr>
            <tr>
                <td class="no_urut">7</td>
                <td colspan="2">Penduduk/Keluarga akhir bulan ini</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNI_L'], ci_route('laporan.detail_penduduk.akhir.wni_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNI_P'], ci_route('laporan.detail_penduduk.akhir.wni_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNA_L'], ci_route('laporan.detail_penduduk.akhir.wna_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNA_P'], ci_route('laporan.detail_penduduk.akhir.wna_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNI_L'] + $penduduk_akhir['WNA_L'], ci_route('laporan.detail_penduduk.akhir.jml_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNI_P'] + $penduduk_akhir['WNA_P'], ci_route('laporan.detail_penduduk.akhir.jml_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['WNI_L'] + $penduduk_akhir['WNA_L'] + ($penduduk_akhir['WNI_P'] + $penduduk_akhir['WNA_P']), ci_route('laporan.detail_penduduk.akhir.jml'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['KK_L'], ci_route('laporan.detail_penduduk.akhir.kk_l'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['KK_P'], ci_route('laporan.detail_penduduk.akhir.kk_p'), $aksi ?? null) !!}</td>
                <td class="bilangan">{!! displayData($penduduk_akhir['KK'], ci_route('laporan.detail_penduduk.akhir.kk'), $aksi ?? null) !!}</td>
            </tr>
        </tbody>
    </table>
</div>
