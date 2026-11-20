@extends('theme::layouts.right-sidebar')

@section('content')
<div class="heading-module l-flex">
	<div class="heading-module-inner l-flex">
		<i class="fa fa-folder-open"></i><h1>Arsip Konten</h1>
	</div>
</div>

<div>
	<div class="box-body">
		<div class="table-responsive content">
			<table id="arsip-artikel" class="table table-striped">				
				<tbody>

				</tbody>
			</table>
		</div>
	</div>

</div>
@endsection
@push('styles')
<style>
#arsip-artikel thead {
    display: none;
}
</style>
@endpush
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
                method: 'POST',
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
                },                
            ],
            columns: [{
                    data: null,
                    orderable: false
                },                
                {
                    data: "attributes.tgl_upload_local",
                    name: "tgl_upload",
                    visible: false,
                },
                {
                    data: function(data) {
                        return `<a href="${data.attributes.url_slug}"><h3>${data.attributes.judul}</h3></a>
						Diposting tanggal : ${data.attributes.tgl_upload_local}<br/>
						Oleh : ${data.attributes.author.nama}`
                    },
                    class: 'text-wrap',
                    name: "judul",
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
