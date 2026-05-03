@extends('theme::template')
@include('core::admin.layouts.components.asset_numeral')

@section('layout')
    <div class="container mx-auto lg:px-5 px-3 flex flex-col-reverse lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
        <div class="lg:w-1/3 w-full">
            @include('theme::partials.statistik.sidenav')
        </div>
        <main class="lg:w-3/4 w-full space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
            <div class="breadcrumb">
                <ol>
                    <li><a href="{{ ci_route('') }}">Beranda</a></li>
                    <li>Data Vaksin</li>
                </ol>
            </div>
            <h1 class="text-h2">{{ $heading }}</h1>

            <div class="content py-3 table-responsive">
                <table class="w-full text-sm" id="tabel-data">
                    <thead class="bg-gray color-palette">
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
        </main>
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
