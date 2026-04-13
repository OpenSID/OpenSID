@extends('theme::layouts.full-content')

@section('content')
    @include('theme::partials.header')
    <div class="contentpage">
		<div class="margin-page">
			<div class="head-module align-center mb-20">
				<h1>{{ ucwords(setting('sebutan_pemerintah_desa')) }}</h1>
			</div>
		</div>
		<div class="margin-page">
			<div class="box-body">
				<div class="row">
				<div class="col-sm-12 gallerystyle">
					<div class="article-grid" id="pemerintah-list"></div>
					<div class="flex-center mt-20">@include('theme::commons.pagination')</div>
				</div>
				</div>
			</div>
		</div>
		
		@include('theme::partials.modulepage')
		@include('theme::partials.footer')
	</div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            function loadPemerintah(params = {}) {
                var apiPemerintah = '{{ route('api.pemerintah') }}';

                $('#pagination-container').hide();
                $('#pemerintah-list').html('<p class="text-center">Memuat...</p>');

                $.get(apiPemerintah, params, function(data) {
                    var pemerintah = data.data;
                    var pemerintahList = $('#pemerintah-list');
                    pemerintahList.empty();

                    if (!pemerintah.length) {
                        pemerintahList.html(`<p class="py-2"> ${setting.sebutan_pemerintah_desa} tidak tersedia.</p>`);
                        return;
                    }

                    var mediaSosialPlatforms = JSON.parse(setting.media_sosial_pemerintah_desa);

                    pemerintah.forEach(function(item) {
                        var mediaSosial = '';
                        var mediaSosialPengurus = item.attributes.media_sosial || {};

                        mediaSosialPlatforms.forEach((platform) => {
                            var link = mediaSosialPengurus[platform];
                            mediaSosial += `
                            <a href="${link}" target="_blank" style="padding: 5px;">
                                <span style="color:#fff;"><i class="fa fa-${platform} fa-2x"></i></span>
                            </a>
                        `;
                        });

                        var pemerintahHTML = `
                        <div class="column4 box-shadow brd-10 mt-20 align-center">
                            <div class="aparatur-page brd-10">
							<a href="${item.attributes.foto}" data-fancybox="images">
								<div class="image-aparatur imagefull brd-10">
                                <img src="${item.attributes.foto}" alt="Foto ${item.attributes.nama}">
								</div>
							</a>	
								<h2>${item.attributes.nama}</h2>
                                <p>${item.attributes.nama_jabatan}</p>
							</div>	
                            <div class="aparatur-bottom">
                                   
                                    ${item.attributes.kehadiran == 1 ? `
									<span class="label label-${item.attributes.status_kehadiran === 'hadir' ? 'primary' : 'danger'}" style="color:#fff;border-radius:4px;">
										${item.attributes.status_kehadiran === 'hadir' ? 'Hadir' : item.attributes.status_kehadiran}
									</span>` : ''}
                                    <div class="flex-center media-sosial">
                                        ${mediaSosial}
                                    </div>
                            </div>
                            
                        </div>
                        `;

                        pemerintahList.append(pemerintahHTML);
                    });

                    initPagination(data);
                });
            }

            $('.pagination').on('click', '.btn-page', function() {
                var params = {};
                var page = $(this).data('page');

                params['page[number]'] = page;

                loadPemerintah(params);
            });

            loadPemerintah();
        });
    </script>
@endpush
