@extends('theme::layouts.full-content')

@section('content')
    @include('theme::partials.header')
    <div class="contentpage">
		<div class="margin-page">
			<div class="head-module align-center mb-20">
				<h1>
					Galeri Foto
				</h1>
			</div>
		</div>
		<div class="margin-page">
			<div class="box-body">
				<div class="row">
				<div class="col-sm-12 gallerystyle">
					<div id="galeri-list"></div>
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
            var parent = `{{ $parent }}`;
            var routeGaleri = `{{ ci_route('internal_api.galeri') }}`;
            let pageSizes = 6;
            let status = '';

            if (parent) {
                routeGaleri = `{{ ci_route('internal_api.galeri') }}/${parent}`;
                pageSizes = 10;
            }

            const loadGaleri = function(pageNumber) {
                $.ajax({
                    url: routeGaleri + `?sort=-tgl_upload&page[number]=${pageNumber}&page[size]=${pageSizes}`,
                    type: 'POST',
                    beforeSend: function() {
                        const galeriList = document.getElementById('galeri-list');
                    },
                    dataType: 'json',
                    data: {

                    },
                    success: function(data) {
                        displayGaleri(data);
                        initPagination(data);
                    }
                });
            }

            const displayGaleri = function(dataGaleri) {
                const galeriList = document.getElementById('galeri-list');
                galeriList.innerHTML = '';
                if (!dataGaleri.data.length) {
                    galeriList.innerHTML = `<div class="alert alert-info" role="alert">Data tidak tersedia</div>`
                    return
                }
                const ulBlock = document.createElement('div');
                ulBlock.className = 'row';
                dataGaleri.data.forEach(item => {
                    const card = document.createElement('div');
                    const image = item.attributes.src_gambar ? `
					<div class="image-article imagefull brd-10">
					<img src="${item.attributes.src_gambar}" alt="${item.attributes.nama}"/></div>` : ``
                    card.innerHTML = `
					<div class="col-lg-6 hover-effect">
					<a href="${item.attributes.parrent ? item.attributes.src_gambar : item.attributes.url_detail}" class="gallery-thumbnail" ${item.attributes.parrent ? `data-fancybox="images" data-caption="${item.attributes.nama}"`: ''}>
						<div class="box-shadow mt-20 brd-10 trans-def">
						<div class="gallery-box align-center">
							${image}
							<p>${item.attributes.nama}</p>
						</div>
						</div>
					</a>
					</div>
				`;
                    card.onclick = function() {}
                    galeriList.appendChild(card);
                });
            }

            $('.pagination').on('click', '.btn-page', function() {
                var params = {};
                var page = $(this).data('page');
                loadGaleri(page);
            });
            loadGaleri(1);
        });
    </script>
@endpush
