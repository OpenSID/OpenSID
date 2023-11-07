<div class="col-md-12">
    <div class="row" style="margin-top: 5px">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Peristiwa</th>
                    <th>Tgl Lapor</th>
                    <th>Tgl Peristiwa</th>
                    <th>Tanggal Buat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log['id'] }}</td>
                        <td>{{ \App\Models\LogPenduduk::kodePeristiwaAll($log['kode_peristiwa']) }}</td>
                        <td>{{ $log['tgl_lapor'] }}</td>
                        <td>{{ $log['tgl_peristiwa'] }}</td>
                        <td>{{ $log['created_at'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row" style="padding: 5px">
        <button type="button" data-log='{{ $log['id'] }}' onclick="hapusLogPenduduk(this)" class="btn btn-sm btn-warning col-sm-12" style="margin-right:5px;margin-bottom:5px">Hapus Log Penduduk Terakhir ( {{ \App\Models\LogPenduduk::kodePeristiwaAll($kodePeristiwa) }} )</button>
        <button type="button" data-log='{{ $log['id'] }}' onclick="updateStatusPenduduk(this)" class="btn btn-sm btn-danger col-sm-12">Update Status Penduduk Saat Ini Mengikuti Log Terakhir ( {{ \App\Models\LogPenduduk::kodePeristiwaAll($kodePeristiwa) }})</button>
    </div>
</div>

<script type="text/javascript">
    function hapusLogPenduduk(elm) {
        $.post('periksaLogPenduduk/hapusLog', {
            id: $(elm).data('log'),
            {{ $ci->security->get_csrf_token_name() }}: '{{ $ci->security->get_csrf_hash() }}'
        }, function(data) {
            let _message = 'Data penduduk dengan nik {{ $nik }} gagal diperbaiki'
            let _messageClass = 'danger'
            if (data.status) {
                let _modal = $(elm).closest('.modal')
                _modal.find('button.close').click()
                $('tr[data-log-tidak-sinkron={{ $nik }}]').find('td:last').html('<button class="btn btn-sm btn-success"><i class="fa fa-check"></i> Sudah diperbaiki</button>')
                _message = 'Data penduduk dengan nik {{ $nik }} berhasil diperbaiki'
                _messageClass = 'success'
            }
            $('#info-log-penduduk-tidak-sinkron').html(`<div class="alert alert-${_messageClass}">${_message}</div>`)
        }, 'json')
    }

    function updateStatusPenduduk(elm) {
        $.post('periksaLogPenduduk/updateStatusDasar', {
            id: $(elm).data('log'),
            {{ $ci->security->get_csrf_token_name() }}: '{{ $ci->security->get_csrf_hash() }}'
        }, function(data) {
            let _message = 'Data penduduk dengan nik {{ $nik }} gagal diperbaiki'
            let _messageClass = 'danger'
            if (data.status) {
                let _modal = $(elm).closest('.modal')
                _modal.find('button.close').click()
                $('tr[data-log-tidak-sinkron={{ $nik }}]').find('td:last').html('<button class="btn btn-sm btn-success"><i class="fa fa-check"></i> Sudah diperbaiki</button>')
                _message = 'Data penduduk dengan nik {{ $nik }} berhasil diperbaiki'
                _messageClass = 'success'
            }
            $('#info-log-penduduk-tidak-sinkron').html(`<div class="alert alert-${_messageClass}">${_message}</div>`)
        }, 'json')
    }
</script>
