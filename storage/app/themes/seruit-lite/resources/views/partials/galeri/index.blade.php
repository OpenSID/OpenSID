@extends('theme::layouts.full-content')

@section('content')
<div class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            @if ($is_detail)
                <li><a href="{{ ci_route('galeri') }}" class="hover:underline hover:text-blue-600">Album Galeri</a></li>
                <li><span class="mx-2">/</span></li>
                <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">{{ $title_galeri }}</li>
            @else
                <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">Album Galeri</li>
            @endif
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase text-center">{{ $title_galeri }}</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>
    
    <div id="galeri-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8">
    </div>

    <div class="mt-8">
        @include('theme::commons.paging')
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        const isDetailView = {{ $parent ? 'true' : 'false' }};
        const parentId = `{{ $parent }}`;
        const galleryList = document.getElementById('galeri-list');
        const paginationContainer = document.querySelector('.pagination-container');
        
        let apiRoute = isDetailView 
            ? `{{ ci_route('internal_api.galeri') }}/${parentId}`
            : `{{ ci_route('internal_api.galeri') }}`;
        
        const pageSize = isDetailView ? 12 : 8;

        function loadGaleri(pageNumber = 1) {
            const fullApiUrl = `${apiRoute}?sort=-tgl_upload&page[number]=${pageNumber}&page[size]=${pageSize}`;

            galleryList.innerHTML = `
                <div class="col-span-full flex flex-col items-center justify-center py-12">
                    <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <p class="mt-2 text-sm font-semibold">Memuat Galeri...</p>
                </div>`;
            
            fetch(fullApiUrl)
                .then(response => response.json())
                .then(data => {
                    galleryList.innerHTML = '';
                    
                    if (!data.data || data.data.length === 0) {
                        const message = isDetailView ? 'Belum ada foto di dalam album ini.' : 'Belum ada album galeri yang dapat ditampilkan.';
                        galleryList.innerHTML = `<div class="col-span-full text-center py-12 text-gray-500">${message}</div>`;
                        if (paginationContainer) paginationContainer.innerHTML = '';
                        return;
                    }

                    data.data.forEach(item => {
                        galleryList.insertAdjacentHTML('beforeend', createCard(item.attributes));
                    });
                    
                    if (isDetailView) {
                        Fancybox.bind('[data-fancybox="gallery"]');
                    }

                    if (paginationContainer && data.links) {
                        paginationContainer.innerHTML = data.links;
                    }
                })
                .catch(error => {
                    console.error("Error fetching gallery data:", error);
                    galleryList.innerHTML = `<div class="col-span-full text-center py-12 text-red-500">Terjadi kesalahan saat memuat galeri.</div>`;
                });
        }

        function createCard(item) {
            const imageUrl = item.src_gambar || `{{ theme_asset('images/placeholder.png') }}`;
            const linkUrl = isDetailView ? item.src_gambar : item.url_detail;
            const fancyboxAttr = isDetailView ? `data-fancybox="gallery" data-caption="${escapeHtml(item.nama)}"` : '';
            const cardHeight = isDetailView ? 'h-48' : 'h-64';

            const detailViewContent = `
                <div class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center text-center p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-8 h-8 text-white mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    <span class="text-white text-xs font-semibold">${escapeHtml(item.nama)}</span>
                </div>
            `;

            const albumViewContent = `
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent flex flex-col justify-end p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <h3 class="font-bold text-lg text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">${escapeHtml(item.nama)}</h3>
                </div>
            `;

            return `
                <a href="${linkUrl}" ${fancyboxAttr} 
                   class="block rounded-none shadow-xl overflow-hidden group transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 p-3 sm:p-4 bg-gradient-to-br from-yellow-400 to-amber-600 dark:from-yellow-500 dark:to-amber-700">
                    <figure class="relative ${cardHeight} w-full overflow-hidden border-4 border-black/20 dark:border-gray-800">
                        <img class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" src="${imageUrl}" alt="${escapeHtml(item.nama)}" loading="lazy">
                        ${isDetailView ? detailViewContent : albumViewContent}
                    </figure>
                </a>
            `;
        }
        
        function escapeHtml(text) {
            var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return text ? text.replace(/[&<>"']/g, function(m) { return map[m]; }) : '';
        }
        
        loadGaleri(1);

        $('body').on('click', '.pagination a', function(e) {
            e.preventDefault();
            const url = new URL($(this).attr('href'));
            const page = url.searchParams.get('page[number]');
            loadGaleri(page);
        });
    });
</script>
@endpush