    <div class="box box-info">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabel-loglogin">
                    <thead class="bg-gray judul-besar">
                        <tr>
                            <th class="padat">No</th>
                            <th>Username</th>
                            <th>IP</th>
                            <th class="text-center">Perambah</th>
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
                                name: 'username',
                                class: 'padat'
                            },
                            {
                                data: 'ip_address',
                                name: 'ip_address',
                                class: 'padat'
                            },
                            {
                                data: 'user_agent',
                                name: 'user_agent',
                            },
                            {
                                data: 'lainnya',
                                name: 'lainnya',
                                class: 'padat'
                            },
                            {
                                data: 'created_at',
                                name: 'created_at',
                                class: 'padat'
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
