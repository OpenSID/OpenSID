
<div class="tab-pane active">
    <div class="row">
        <div class="col-md-12">

            <div class="box box-info">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabeldata">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Modul</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    var TableData = $('#tabeldata').DataTable({
        responsive: true,
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ config_item('server_layanan') . '/api/v1/pemesanan' }}",
            type: 'GET',
            headers: {
                'Authorization': 'Bearer {{ $token_layanan }}',
                'Accept': 'application/json'
            },
            dataFilter: function(response) {
                const json = JSON.parse(response);

                console.log(json.messages)

                const filteredMessages = (json.messages || []).filter(item => {
                    const layanan = item.pemesanan_layanan || [];
                    return layanan.some(l => typeof l?.detail?.nama === 'string' && l.detail.nama.startsWith('Modul'));
                });

                filteredMessages.forEach((item, index) => {
                    const layanan = item.pemesanan_layanan || [];

                    item.no = index + 1;
                    item.harga = layanan
                            .filter(l => typeof l?.detail?.nama === 'string' && l.detail.nama.startsWith('Modul'))
                            .map(l => l?.detail?.harga)
                            .join('<br>') || '-';
                        item.modul_nama = layanan
                            .map(l => l?.detail?.nama)
                            .filter(n => typeof n === 'string' && n.startsWith('Modul'))
                            .join('<br>') || '-';
                });

                return JSON.stringify({
                    data: filteredMessages,
                    recordsTotal: filteredMessages.length,
                    recordsFiltered: filteredMessages.length
                });
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
                class: 'padat',
                orderable: false,
                searchable: false
            },
            {
                data: 'modul_nama',
                name: 'modul_nama',
                orderable: false,
                searchable: false
            },
            {
                data: 'harga',
                name: 'harga',
                orderable: false,
                searchable: false
            }
        ],
        pageLength: 10,
        aaSorting: []
    });
});
</script>
@endpush


