@if ($jam_kerja)
    <div class="box box-primary box-solid">
        <div class="box-header">
            <h3 class="box-title">
                <i class="fa fa-clock-o mr-1"></i> {{ $judul_widget }}
            </h3>
        </div>
        <div class="box-body">
            <table style="width: 100%;" cellpadding="0" cellspacing="0" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jam_kerja as $value)
                        <tr>
                            <td>{{ $value->nama_hari }}</td>
                            @if ($value->status)
                                <td>{{ $value->jam_masuk }}</td>
                                <td>{{ $value->jam_keluar }}</td>
                            @else
                                <td colspan="2"><span class="label label-danger"> Libur </span></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
