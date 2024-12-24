<div class="modal-body">
    <table class="table table-hover">
        <tr>
            <th>Nik</th>
            <th>Nama</th>
        </tr>
        @foreach ($anggota as $item)
            <tr>
                <td>{{ $item->penduduk->nik }}</td>
                <td>{{ $item->penduduk->nama }}</td>
            </tr>
        @endforeach
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Keluar</button>
</div>
