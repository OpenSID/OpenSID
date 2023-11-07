<div class="col-md-12">
    <div class="row" style="margin-top: 5px">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Peristiwa</th>
                    <th>Tgl Peristiwa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log['id'] }}</td>
                        <td>{{ \App\Models\LogKeluarga::kodePeristiwaAll($log['id_peristiwa']) }}</td>
                        <td>{{ $log['tgl_peristiwa'] }}</td>
                        <td><button type="button" data-log='{{ $log['id'] }}' onclick="hapusLogKeluarga(this)" class="btn btn-sm btn-danger">Hapus Log</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function hapusLogKeluarga(elm) {
        $.post('periksaLogKeluarga/hapusLog', {
            id: $(elm).data('log'),
            {{ $ci->security->get_csrf_token_name() }}: '{{ $ci->security->get_csrf_hash() }}'
        }, function(data) {
            let _message = 'Data keluarga dengan nomer KK {{ $nik }} gagal diperbaiki'
            let _messageClass = 'danger'
            if (data.status) {
                let _modal = $(elm).closest('.modal')
                _modal.find('button.close').click()
                $('tr[data-log-keluarga-ganda={{ $id }}]').find('td:last').html('<button class="btn btn-sm btn-success"><i class="fa fa-check"></i> Sudah diperbaiki</button>')
                _message = 'Data log keluarga dengan nomer {{ $no_kk }} berhasil dihapus'
                _messageClass = 'success'
            }
            $('#info-log-keluarga-ganda').html(`<div class="alert alert-${_messageClass}">${_message}</div>`)
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
