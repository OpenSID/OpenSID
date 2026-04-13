@extends('theme::layouts.full-content')
@include('theme::commons.asset_peta')

@section('content')
	@include('theme::partials.header')
    <div class="contentpage">
		<div class="margin-page">
			<div class="head-module align-center">
				<h1>Pembangunan</h1>
			</div>
		</div>
		<div class="mt-20 margin-page pembangunan">
		<div class="article-grid" id="pembangunan-list"></div>
		@include('theme::commons.pagination')
		</div>
		
		@include('theme::partials.modulepage')
		@include('theme::partials.footer')
	</div>	
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            function loadPembangunan(params = {}) {

                var apiPembangunan = '{{ route('api.pembangunan') }}';

                $('#pagination-container').hide();

                $.get(apiPembangunan, params, function(data) {
                    var pembangunan = data.data;
                    var pembangunanList = $('#pembangunan-list');

                    pembangunanList.empty();

                    if (!pembangunan.length) {
                        pembangunanList.html('<p class="text-center">Tidak ada pembangunan yang ditemukan.</p>');
                        return;
                    }

                    pembangunan.forEach(function(item) {
                        var url = SITE_URL + 'pembangunan/' + item.attributes.slug;
                        var fotoHTML = `
						<div class="image-article imagefull">
						<img src="${item.attributes.foto}" alt="Foto Pembangunan"/>
						</div>
						`;

                        var pembangunanHTML = `
                        <div class="articlecol">
                                ${fotoHTML}
                                <div class="pemb-info">
									<h2>${item.attributes.judul}</h2>
                                    <table class="table-mini mb-20">
                                        <tbody>
                                            <tr>
                                                <td>Alamat</td>
                                                <td style="text-align:center;width:15px;">:</td>
                                                <td>${item.attributes.lokasi}</td>
                                            </tr>
                                            <tr>
                                                <td>Tahun</td>
                                                <td style="text-align:center;width:15px;">:</td>
                                                <td>${item.attributes.tahun_anggaran}</td>
                                            </tr>
                                        </tbody>
                                    </table>
									<div class="flex-left">
									<a href="${url}" class="btn btn-primary">Selengkapnya</a>
									</div>
                            </div>
                    `;

                        pembangunanList.append(pembangunanHTML);
                    });

                    initPagination(data);
                });
            }

            $('.pagination').on('click', '.btn-page', function() {
                var params = {};
                var page = $(this).data('page');

                params['page[number]'] = page;

                loadPembangunan(params);
            });

            loadPembangunan();
        });
    </script>
@endpush
