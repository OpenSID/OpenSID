@extends('theme::layouts.full-content')

@section('content')
    <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
            <li><a href="{{ ci_route() }}">Beranda</a></li>
            @if (isset($parent))
                <li><a href="{{ ci_route('galeri') }}">Galeri</a></li>
                <li aria-current="page">{{ $title }}</li>
            @else
                <li aria-current="page">Galeri</li>
            @endif
        </ol>
    </nav>
    <h1 class="text-h2">
        @if (isset($parent))
            Album Galeri
        @else
            Album
        @endif {{ $title }}
    </h1>

    <div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 lg:gap-5 main-content py-4" id="galeri-list"></div>
        @include('theme::commons.pagination')
    </div>
@endsection

@push('scripts')
    <script src="{{ theme_asset('js/pagination.js') }}"></script>
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
                    url: routeGaleri + `?sort=-tgl_upload&page[number]=${pageNumber}&page[size]=${pageSizes}`, // Gunakan pageSizes
                    type: "GET",
                    beforeSend: function() {
                        const galeriList = document.getElementById('galeri-list');
                    },
                    dataType: 'json',
                    success: function(data) {
                        displayGaleri(data);
                        initPagination(data);
                    }
                });
            };


            const displayGaleri = function(dataGaleri) {
                const galeriList = document.getElementById('galeri-list');
                galeriList.innerHTML = '';
                if (!dataGaleri.data.length) {
                    galeriList.innerHTML = `<div class="alert text-primary-100">Maaf album galeri belum tersedia!</div>`
                    return
                }

                dataGaleri.data.forEach(item => {
                    const card = document.createElement('div');
                    const image = item.attributes.src_gambar ? `<img class="h-44 w-full object-cover object-center" src="${item.attributes.src_gambar}" title="${item.attributes.nama}" alt="${item.attributes.nama}"/>` : ``
                    card.innerHTML = `
					<a @if (isset($parent)) data-fancybox="images" data-src="${item.attributes.src_gambar}" data-caption="${item.attributes.nama}" @else href="${item.attributes.url_detail}" @endif class="w-full bg-gray-100 block relative">
						${image}
						<p class="py-2 text-center block">${item.attributes.nama}</p>
					</a>					
				`;
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
