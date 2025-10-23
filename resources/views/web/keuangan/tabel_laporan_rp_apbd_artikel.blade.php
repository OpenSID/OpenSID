<table width="100%" style="border: solid 0px black; text-align: center;">
    <tr>
        </td>
        <td>
            <h4>LAPORAN REALISASI PELAKSANAAN</h4>
            <h4>ANGGARAN PENDAPATAN DAN BELANJA DESA</h4>
            <h4>PEMERINTAH {{ strtoupper(ucwords(setting('sebutan_desa'))) }} {{ strtoupper($desa['nama_desa']) }}</h4>
            <h4>TAHUN ANGGARAN {{ $tahun }}</h4>
        </td>
    </tr>
</table>

@include('admin.keuangan.laporan.apbd_isi')
