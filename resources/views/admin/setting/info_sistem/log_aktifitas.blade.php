@include('admin.layouts.components.jsondiffpatch')
<div class="box box-info">
    <div class="box-body">
        <div class="row mepet">
            <div class="col-sm-2">
                <select class="form-control input-sm select2" id="log_name" name="log_name">
                    <option value="">Pilih Kategori</option>
                    @foreach ($nama_log as $key => $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control input-sm select2" id="username" name="username">
                    <option value="">Pilih Pengguna</option>
                    @foreach ($pengguna_log as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <hr class="batas">
        <div class="table-responsive">
            <table class="table table-bordered table-hover tabel-daftar" id="tabel-logaktifitas">
                <thead class="bg-gray judul-besar">
                    <tr>
                        <th class="padat">No</th>
                        <th class="padat">Aksi</th>
                        <th class="padat">Kategori</th>
                        <th class="padat">Peristiwa</th>
                        <th class="padat">Subjek Tipe</th>
                        <th class="padat">Penyebab Tipe</th>
                        <th class="padat">Pengguna</th>
                        <th>Deskripsi</th>
                        <th class="padat">Dibuat Pada</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="logDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><i class="fa fa-exclamation-triangle text-red"></i> &nbsp;Detail Perubahan</h4>
            </div>
            <div class="modal-body">
                <div id="json-diff-output"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var TableData = $('#tabel-logaktifitas').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            order: [
                [8, 'desc']
            ],
            ajax: {
                url: "{{ route('info_sistem.datatables-log') }}",
                data: function(d) {
                    d.log_name = $('#log_name').val();
                    d.username = $('#username').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    class: 'padat',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    class: 'padat',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'log_name',
                    name: 'log_name',
                    class: 'padat'
                },
                {
                    data: function (data, type, row) {
                        switch (data.event) {
                            case 'created':
                                return '<h6><span class="label label-success">Dibuat</span></h6>';
                            case 'updated':
                                return '<h6><span class="label label-warning">Diubah</span></h6>';
                            case 'deleted':
                                return '<h6><span class="label label-danger">Dihapus</span></h6>';
                            default:
                                return data.event;
                        }
                    },
                    name: 'event',
                    class: 'padat',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'subject_type',
                    name: 'subject_type',
                    class: 'padat'
                },
                {
                    data: 'causer_type',
                    name: 'causer_type',
                    class: 'padat'
                },
                {
                    data: 'username',
                    name: 'causer',
                    class: 'padat'
                },
                {
                    data: 'description',
                    name: 'description',
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    class: 'padat'
                },
            ],
        });

        $('#log_name').on('select2:select', function(e) {
            TableData.draw();
        });

        $('#username').on('select2:select', function(e) {
            TableData.draw();
        });

        $(document).on('click', '.btn-detail-log', function (e) {
            e.preventDefault();

            const row = TableData.row($(this).closest('tr')).data();
            const changes = row.properties;

            if (!changes || !changes.old || !changes.attributes) {
                $('#json-diff-output').html('<div class="text-danger">Data tidak valid atau kosong.</div>');
                $('#logDetailModal').modal('show');
                return;
            }

            const delta = jsondiffpatch.diff(changes.old, changes.attributes);

            // Format diff dalam bentuk teks JSON seperti konsol
            const textDiff = jsondiffpatch.formatters.html.format(delta, changes.old);

            // Tampilkan di dalam elemen <pre> agar rapi
            $('#json-diff-output').html(`<pre>${textDiff}</pre>`);
            $('#logDetailModal').modal('show');
        });
    </script>
@endpush
