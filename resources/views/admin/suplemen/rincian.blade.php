<div class="box-body">
    <h5><b>Rincian Suplemen</b></h5>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover tabel-rincian">
            <tbody>
                <tr>
                    <td width="20%">Nama Data</td>
                    <td width="1%">:</td>
                    <td>{{ strtoupper($suplemen->nama) }}</td>
                </tr>
                <tr>
                    <td>Sasaran Terdata</td>
                    <td>:</td>
                    <td>{{ $sasaran[$suplemen->sasaran] }}</td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td>:</td>
                    <td>{{ $suplemen->keterangan }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
