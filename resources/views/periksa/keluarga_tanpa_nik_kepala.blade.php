<div class="panel panel-default">
    <div class="panel-body">
        <strong>Terdeteksi keluarga tanpa kepala keluarga<br></strong>
        <hr>
        <table class="table">
            <tr>
                <th>No</th>
                <th>No KK</th>
                <th>Dusun</th>
                <th>RW</th>
                <th>RT</th>
                <th>Keterangan</th>
            </tr>
            @foreach ($keluarga_tanpa_nik_kepala as $keluarga)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $keluarga['no_kk'] }}</td>
                    <td>{{ $keluarga['wilayah']['dusun'] ?? '' }}</td>
                    <td>{{ $keluarga['wilayah']['rw'] ?? '' }}</td>
                    <td>{{ $keluarga['wilayah']['rt'] ?? '' }}</td>
                    <td>{{ App\Models\LogKeluarga::kodePeristiwaAll($keluarga['id_peristiwa']) ?? '-' }}</td>
                </tr>
            @endforeach
        </table>
        <p>Klik tombol Perbaiki untuk menghapus keluarga tanpa kepala keluarga<br><a
                href="#"
                data-href="{{ ci_route('periksa.perbaiki_sebagian', 'keluarga_tanpa_nik_kepala') }}"
                class="btn btn-sm btn-social btn-danger"
                role="button"
                title="Perbaiki masalah data"
                data-toggle="modal"
                data-target="#confirm-backup"
                data-body="Apakah sudah melakukan backup database/folder desa?"
            ><i class="fa fa fa-wrench"></i>Perbaiki Data</a>
        </p>
    </div>
</div>
