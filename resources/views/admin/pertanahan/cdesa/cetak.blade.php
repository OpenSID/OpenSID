<div class="header" align="center">
    <label align="left">
        {!! get_identitas() !!}
    </label>
    <h3> DATA C-DESA </h3>
</div>
<br>
<table class="border thick">
    <thead>
        <tr class="border thick">
            <th colspan="2">NOMOR</th>
            <th colspan="3">PEMILIK</th>
            <th colspan="4">LUAS TANAH</th>
            <th rowspan="3">TANGGAL TERDAFTAR</th>
        </tr>
        <tr>
            <th rowspan="2">URUT</th>
            <th rowspan="2">C-DESA</th>
            <th rowspan="2">NAMA</th>
            <th rowspan="2">NIK</th>
            <th rowspan="2">ALAMAT</th>
            <th colspan="2"> TANAH BASAH</th>
            <th colspan="2"> TANAH KERING</th>
        </tr>
        <tr>
            <th width="100">Ha</th>
            <th width="100">m<sup>2</sup></th>
            <th width="100">Ha</th>
            <th width="100">m<sup>2</sup></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($main as $no => $cdesa)
            <tr>
                <td>
                    {{ $no + 1 }}
                </td>
                <td class="textx">
                    {{ sprintf('%04s', $cdesa['nomor']) }}
                </td>
                <td>
                    {{ strtoupper($cdesa['nama_pemilik']) }}
                </td>
                <td class="textx">
                    {{ $cdesa['nik_pemilik'] }}
                </td>
                <td>
                    {{ $cdesa['alamat'] }}
                </td>
                <td align="right">
                    {{ luas($cdesa['basah'], 'ha') }}
                </td>
                <td align="right">
                    {{ luas($cdesa['basah'], 'meter') }}
                </td>
                <td align="right">
                    {{ luas($cdesa['kering'], 'ha') }}
                </td>
                <td align="right">
                    {{ luas($cdesa['kering'], 'meter') }}
                </td>
                <td>
                    {{ tgl_indo($cdesa['tanggal_daftar']) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<label>Tanggal cetak : &nbsp; </label>
{{ tgl_indo(date('Y m d')) }}
