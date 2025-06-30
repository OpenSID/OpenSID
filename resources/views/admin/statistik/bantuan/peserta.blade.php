<div class="box box-info">
    <div class="box-header with-border" style="margin-bottom: 15px;">
        <h3 class="box-title">Daftar {{ $heading }}</h3>
    </div>
    <div style="margin-right: 1rem; margin-left: 1rem;">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="peserta_program">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Program</th>
                        <th>Nama Peserta</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var url = "{{ ci_route('statistik.bantuan.' . $lap . '.peserta_datatables') }}";
            const pesertaTable = $('#peserta_program').DataTable({
                'processing': true,
                'serverSide': true,
                "pageLength": 10,
                'order': [],
                "ajax": {
                    "url": url,
                    "type": "get",
                    data: function(req) {
                        req.tahun = $('#tahun').val();
                        req.status = $('#status').val();
                        req.dusun = $('#dusun').val();
                        req.rw = $('#rw').val();
                        req.rt = $('#rt').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'kartu_nama',
                        name: 'kartu_nama'
                    },
                    {
                        data: 'kartu_alamat',
                        name: 'kartu_alamat',
                        orderable: false,
                        searchable: false
                    },
                ],
                aaSorting: []
            });

            $('#tahun, #status, #dusun, #rw, #rt').change(function() {
                pesertaTable.draw()
            })
        });
    </script>
@endpush
