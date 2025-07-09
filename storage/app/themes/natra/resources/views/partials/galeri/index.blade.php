@extends('theme::layouts.full-content')

@push('styles')
    <style>
        #galeri-list .card {
            height: 400px;
            padding: 5px;
            margin: 5px
        }

        #galeri-list .card img {
            height: 360px;
            background-size: cover;
            margin: 0 auto;
            width: 100%;
        }
    </style>
@endpush

@section('content')
    <div class="single_category wow fadeInDown">
        <h2><span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">
                @if ($is_detail)
                    <a href="{{ ci_route('galeri') }}">Album Galeri</a>
                @else
                    Album
                @endif {{ $title_galeri }}
            </span></h2>
    </div>

    <div style="content_left">
        <div class="col-md-12 col-lg-12" id="galeri-list"></div>
        @include('theme::commons.pagination')
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
                    type: "GET",
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
                    const image = item.attributes.src_gambar ? `<img class="img-fluid img-thumbnail" src="${item.attributes.src_gambar}" alt="${item.attributes.nama}"/>` : ``
                    card.innerHTML = `
					<a href="${item.attributes.url_detail}">
						<div class="col-sm-6">
							<div class="card">
								${image}
								<p align="center"><b>Album : ${item.attributes.nama}</b></p>
								<hr/>
							</div>
						</div>
					</a>
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
