@extends('theme::layouts.right-sidebar')

@section('content')
    <div class="single_page_area">
        <div style="margin-top:0px;">
            @if (!empty($teks_berjalan))
                <marquee onmouseover="this.stop()" onmouseout="this.start()">
                    @include('theme::layouts.teks_berjalan')
                </marquee>
            @endif
        </div>
        <div class="single_category wow fadeInDown">
            <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Arsip Konten Situs Web {{ $desa['nama_desa'] }}</span> </h2>
        </div>
        <div style="margin-top:50px;">
            <div class="box-body">
                <div class="table-responsive">
                    <table id="arsip-artikel" class="table table-striped">
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
