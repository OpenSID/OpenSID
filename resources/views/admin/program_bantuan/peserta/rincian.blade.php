<h5><b>Rincian Program</b></h5>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover tabel-rincian">
        <tbody>
            <tr>
                <td width="20%">Nama Program</td>
                <td width="1">:</td>
                <td>{{ strtoupper($detail['nama']) }}</td>
            </tr>
            <tr>
                <td>Sasaran Peserta</td>
                <td> : </td>
                <td>{{ $list_sasaran[$detail['sasaran']] }}</td>
            </tr>
            <tr>
                <td>Masa Berlaku</td>
                <td> : </td>
                <td>{{ fTampilTgl($detail['sdate'], $detail['edate']) }}</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td> : </td>
                <td>{{ $detail['ndesc'] }}</td>
            </tr>
        </tbody>
    </table>
</div>
<br>
