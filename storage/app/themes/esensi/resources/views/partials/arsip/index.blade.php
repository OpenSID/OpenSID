@extends('theme::layouts.right-sidebar')

@section('content')
    <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
            <li><a href="{{ ci_route('') }}">Beranda</a></li>
            <li aria-current="page">Arsip Artikel</li>
        </ol>
    </nav>
    <h1 class="text-h2">Arsip Situs Web</h1>
    <div>
        <div class="box-body">
            <div class="table-responsive content">
                <table id="arsip-artikel" class="w-full text-sm">
                    <thead>
                        <tr>
                            <td width="3%"><b>No.</b></td>
                            <td width="20%"><b>Tanggal Artikel</b></td>
                            <td width="57"><b>Judul Artikel</b></td>
                            <td width="10%"><b>Penulis</b></td>
                            <td width="10%"><b>Dibaca</b></td>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function(event) {
            var arsip = $('#arsip-artikel').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ordering: true,
                ajax: {
                    url: `{{ ci_route('internal_api.arsip') }}`,
                    method: 'get',
                    data: function(row) {
                        return {
                            "page[size]": row.length,
                            "page[number]": (row.start / row.length) + 1,
                            "filter[search]": row.search.value,
                            "sort": (row.order[0]?.dir === "asc" ? "" : "-") + row.columns[row.order[0]?.column]
                                ?.name,
                        };
                    },
                    dataSrc: function(json) {
                        json.recordsTotal = json.meta.pagination.total
                        json.recordsFiltered = json.meta.pagination.total

                        return json.data
                    },
                },
                columnDefs: [{
                    targets: '_all',
                    className: 'text-nowrap',
                }, ],
                columns: [{
                        data: null,
                        orderable: false
                    },
                    {
                        data: "attributes.tgl_upload_local",
                        name: "tgl_upload"
                    },
                    {
                        data: function(data) {
                            return `<a href="${data.attributes.url_slug}">
                                    ${data.attributes.judul}
                                </a>`
                        },
                        name: "judul",
                        orderable: false
                    },
                    {
                        data: "attributes.author.nama",
                        name: "id_user",
                        defaultContent: '',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: "attributes.hit",
                        name: "hit",
                        searchable: false,
                    },
                ],
                order: [
                    [1, 'desc']
                ]
            })

            arsip.on('draw.dt', function() {
                var PageInfo = $('#arsip-artikel').DataTable().page.info();
                arsip.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });
        });
    </script>
@endpush
