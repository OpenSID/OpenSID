@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')


@section('title')
<h1>
    Database
  <small>{{ $action }} Backup Inkremental</small>
</h1>
@endsection

@section('breadcrumb')
 
<li class="breadcrumb-item"><a href="{{ route('database') }}">Pengaturan Database</a></li>
<li class="active">{{ $action }} Backup Inkremental</li>
@endsection

@section('content')

@include('admin.layouts.components.notifikasi')

<div class="box box-info">
	<div class="box-header with-border">
        <a href="{{ route('database') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
          <i class="fa fa-arrow-circle-left "></i>Kembali Pengaturan Database
        </a>
      </div>
	<div class="box-body">
		<div class="table-responsive">
			<table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="tabel-data">
				<thead class="bg-gray disabled color-palette">
					<tr>
 						<th>No</th>
						<th>Ukuran (MB)</th>
						<th>Tanggal Backup</th>
						<th>Tanggal Terakhir Download</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
@endsection

@push('scripts')

<script>
$(document).ready(function() {
	$('#tabel-data').DataTable({
		'processing': true,
		'serverSide': true,
		'autoWidth': false,
		'pageLength': 10,
		'ajax': {
			'url': "{{ route('database.desa_inkremental') }}",
			'method': 'get',
			'data': function(d) {
				d.tahun= $('#tahun').val();
			}
		},
		'columns': [
			{ 'data': 'DT_RowIndex', class: 'padat', searchable: false, orderable: false },
			{ 'data': 'ukuran' },
			{ 'data': 'created_at' },
			{ 'data': 'downloaded_at' },
		],
		'order': [
			[2, 'desc'],
		],
		'language': {
			'url': "<?= base_url('/assets/bootstrap/js/dataTables.indonesian.lang') ?>"
		}
	});

	 
});
</script>
@endpush
