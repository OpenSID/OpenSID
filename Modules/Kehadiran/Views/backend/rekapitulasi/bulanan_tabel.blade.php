<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th rowspan="2" class="text-center padat" style="vertical-align: middle;">NO</th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">NAMA</th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">JABATAN</th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">HARI KERJA</th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">HADIR</th>
                <th colspan="5" class="text-center">KETIDAKHADIRAN</th>
                <th rowspan="2" class="text-center" style="vertical-align: middle;">JML ABSEN</th>
            </tr>
            <tr>
                <th class="text-center" title="Izin">I</th>
                <th class="text-center" title="Sakit">S</th>
                <th class="text-center" title="Cuti">CT</th>
                <th class="text-center" title="Dinas Luar">DL</th>
                <th class="text-center" title="Tanpa Keterangan">TK</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekap as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td>{{ $row['jabatan'] }}</td>
                    <td class="text-center">{{ $row['hari_kerja'] }}</td>
                    <td class="text-center">{{ $row['hadir'] }}</td>
                    <td class="text-center">{{ $row['izin'] }}</td>
                    <td class="text-center">{{ $row['sakit'] }}</td>
                    <td class="text-center">{{ $row['cuti'] }}</td>
                    <td class="text-center">{{ $row['dinas_luar'] }}</td>
                    <td class="text-center">{{ $row['tanpa_keterangan'] }}</td>
                    <td class="text-center font-weight-bold" style="font-weight: bold;">{{ $row['jumlah_absen'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data untuk periode ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="row" style="margin-top: 15px;">
    <div class="col-md-12">
        <strong>Keterangan Ketidakhadiran:</strong>
        <span class="label label-info" style="margin-left: 10px;">I : Izin</span>
        <span class="label label-danger" style="margin-left: 10px;">S : Sakit</span>
        <span class="label label-success" style="margin-left: 10px;">CT : Cuti</span>
        <span class="label label-warning" style="margin-left: 10px;">DL : Dinas Luar</span>
        <span class="label label-default" style="margin-left: 10px;">TK : Tanpa Keterangan</span>
    </div>
</div>
