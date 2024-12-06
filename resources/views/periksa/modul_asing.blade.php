<div class="panel panel-default">
    <div class="panel-body">
        <strong>Terdeteksi modul asing pada tabel grup_akses<br></strong>
        <hr>
        <table class="table">
            <tr>
                <th>No</th>
                <th>Grup</th>
                <th>Id Modul</th>
            </tr>
            @foreach ($modul_asing as $grupAkses)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $grupAkses['grup']['nama'] }}</td>
                    <td>{{ $grupAkses['id_modul'] }}</td>
                </tr>
            @endforeach
        </table>
        <p>Klik tombol Perbaiki untuk menghapus keluarga tanpa kepala keluarga<br><a
                href="#"
                data-href="{{ ci_route('periksa.perbaiki_sebagian', 'modul_asing') }}"
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
