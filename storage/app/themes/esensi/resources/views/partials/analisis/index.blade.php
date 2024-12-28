@extends('theme::layouts.right-sidebar')
@include('theme::commons.asset_sweetalert')

@section('content')
    <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
            <li><a href="<?= site_url() ?>">Beranda</a></li>
            <li aria-current="page">Analisis</li>
        </ol>
    </nav>
    <h1 class="text-h2">Daftar Agregasi Data Analisis Desa</h1>
    <div class="space-y-1 flex gap-3 items-center">
        <label for="master" class="block text-sm">Analisis:</label>
        <select class="form-input inline-block w-auto" id="master" name="master"></select>
    </div>
    <div class="table-responsive content py-2">
        <table>
            <tbody>
                <tr>
                    <td width="200">Pendataan </td>
                    <td width="20"> :</td>
                    <td id="pendataan-detail"></td>
                </tr>
                <tr>
                    <td>Subjek </td>
                    <td> : </td>
                    <td id="subjek-detail"></td>
                </tr>
                <tr>
                    <td>Tahun </td>
                    <td> :</td>
                    <td id="tahun-detail"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <h4 class="text-h4 py-2">Indikator</h4>
    <div class="table-responsive content">
        <table class="table table-striped table-bordered" id="table-indikator">
            <thead>
                <tr>
                    <td width="3%"><b>No.</b></td>
                    <td width="93%"><b>Indikator</b></td>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var routeApiMaster = '{{ route('api.analisis.master') }}';

            $.get(routeApiMaster, function(data) {
                const $masterSelect = $('#master');

                data.data.forEach(item => {
                    $masterSelect.append(`<option value="${item.id}">${item.attributes.master} (${item.attributes.tahun})</option>`);
                });

                $masterSelect.on('change', function() {
                    const selectedId = $(this).val();
                    const selectedItem = data.data.find(item => item.id === selectedId);

                    if (selectedItem) {
                        $('#pendataan-detail').text(selectedItem.attributes.master || 'N/A');
                        $('#subjek-detail').text(selectedItem.attributes.subjek || 'N/A');
                        $('#tahun-detail').text(selectedItem.attributes.tahun || 'N/A');
                    }
                });

                const firstItem = data.data[0];
                if (firstItem) {
                    $('#pendataan-detail').text(firstItem.attributes.master || 'N/A');
                    $('#subjek-detail').text(firstItem.attributes.subjek || 'N/A');
                    $('#tahun-detail').text(firstItem.attributes.tahun || 'N/A');

                    $masterSelect.val(firstItem.id).trigger('change');
                }
            });

            var routeApiIndikator = '{{ route('api.analisis.indikator') }}';

            var tabelData = $('#table-indikator').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ordering: false,
                searching: false,
                ajax: {
                    url: routeApiIndikator,
                    method: 'GET',
                    data: row => ({
                        "filter[id_master]": $('#master').val(),
                        "page[size]": row.length,
                        "page[number]": (row.start / row.length) + 1,
                    }),
                    dataSrc: json => {
                        json.recordsTotal = json.meta.pagination.total;
                        json.recordsFiltered = json.meta.pagination.total;
                        return json.data;
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                        Swal.fire('Error', 'Terjadi kesalahan saat memuat data.', 'error');
                    }
                },
                columnDefs: [{
                    targets: '_all',
                    className: 'text-nowrap'
                }, ],
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'attributes.indikator',
                        name: 'attributes.indikator',
                        render: (data, type, row) => {
                            const url = `/jawaban_analisis?filter[id_indikator]=${row.id}&filter[subjek_tipe]=${row.attributes.subjek_tipe}&filter[id_periode]=${row.attributes.id_periode}`;
                            return `<a href="${url}" class="font-semibold">${row.attributes.indikator}</a>`;
                        }
                    }
                ],
                drawCallback: function(settings) {
                    var api = this.api();
                    api.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = api.page.info().start + i + 1;
                    });
                }
            });

            $('#master').on('change', function() {
                tabelData.ajax.reload();
            });
        });
    </script>
@endpush
