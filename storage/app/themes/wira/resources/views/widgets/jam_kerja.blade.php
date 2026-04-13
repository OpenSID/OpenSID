@if ($jam_kerja)
    <div class="box box-primary box-solid items-center">
        <div class="bg-green-600 flex items-center justify-center py-3 px-6 mb-1">
            <h3 class="text-md font-semibold text-white text-center">
                {{ strtoupper($judul_widget) }}
            </h3>
        </div>
        <div class="h-1 bg-green-500 mb-2"></div>

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
                                <td class="text-center">{{ $value->jam_masuk }}</td>
                                <td class="text-center">{{ $value->jam_keluar }}</td>
                            @else
                                <td colspan="2"><span class="label label-danger text-center"> Libur </span></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
