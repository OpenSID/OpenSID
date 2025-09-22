@extends('theme::layouts.right-sidebar')
@include('core::admin.layouts.components.asset_numeral')

@section('layout')
<div class="single_page_area">
        <h2 class="post_titile">{{ $heading }}</h2>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="tabel-data">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama</th>
                            <th rowspan="2">Alamat Dusun</th>
                            <th rowspan="2">Tanggal</th>
                            <th colspan="3">Vaksin</th>
                        </tr>
                        <tr>
                            <th>I</th>
                            <th>II</th>
                            <th>III</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $nomor = 1; @endphp
                        @foreach ($main as $data)
                            @if ($data->vaksin_1 == 1 || $data->vaksin_2 == 1 || $data->vaksin_3 == 1)
                                <tr>
                                    <td class="text-center">{{ $nomor++ }}</td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->dusun }}</td>
                                    <td>
                                        @if ($data->vaksin_1 == 1 && $data->vaksin_2 == 0 && $data->vaksin_3 == 0)
                                            {{ $data->tgl_vaksin_1 }}
                                        @endif

                                        @if ($data->vaksin_1 == 1 && $data->vaksin_2 == 1 && $data->vaksin_3 == 0)
                                            {{ $data->tgl_vaksin_2 }}
                                        @endif

                                        @if ($data->vaksin_1 == 1 && $data->vaksin_2 == 1 && $data->vaksin_3 == 1)
                                            {{ $data->tgl_vaksin_3 }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($data->vaksin_1 == 1 && $data->tunda == 0)
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($data->vaksin_2 == 1 && $data->tunda == 0)
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($data->vaksin_3 == 1 && $data->tunda == 0)
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
		var tabelData = $('#tabel-data').DataTable({
			'processing': false,
			'order': [[1, 'desc']],
			'pageLength': 10,
            'lengthMenu': [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Semua"]
            ],
			'columnDefs': [
				{
					'searchable': false,
					'targets': [0, 4, 5, 6]
				},
				{
					'orderable': false,
					'targets': [0, 4, 5, 6]
				}
			],
			'language': {
				'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
			},
		});

        tabelData.on( 'order.dt search.dt', function () {
            tabelData.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1;
            } );
        } ).draw();
	});
    </script>
@endpush
