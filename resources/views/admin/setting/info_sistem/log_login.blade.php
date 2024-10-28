    <div class="box box-info">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabel-loglogin">
                    <thead>
                        <tr>
                            <th class="padat">No</th>
                            <th>Username</th>
                            <th>IP</th>
                            <th>Perambah</th>
                            <th>Data</th>
                            <th class="padat">Tanggal Akses</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            function loadDatatable() {
                if (!$.fn.dataTable.isDataTable('#tabel-loglogin')) {
                    var TableData = $('#tabel-loglogin').DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        ajax: "{{ ci_route('info_sistem.datatables') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                class: 'padat',
                                searchable: false,
                                orderable: false
                            },
                            {
                                data: 'username',
                                name: 'username'
                            },
                            {
                                data: 'ip_address',
                                name: 'ip_address',
                            },
                            {
                                data: 'user_agent',
                                name: 'user_agent',
                            },
                            {
                                data: 'lainnya',
                                name: 'lainnya',
                            },
                            {
                                data: 'created_at',
                                name: 'created_at',
                            },
                        ],
                        order: [
                            [5, 'desc']
                        ]
                    });
                }
            }
        </script>
    @endpush
