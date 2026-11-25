@extends('theme::layouts.full-content')

@section('content')
<div class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">Pembangunan</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">Galeri Pembangunan</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>
    
    <div id="pembangunan-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
    </div>

    <div class="mt-8">
        @include('theme::commons.paging')
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        const pembangunanList = document.getElementById('pembangunan-list');
        const paginationContainer = document.querySelector('.pagination-container');
        const apiPembangunan = '{{ ci_route('internal_api.pembangunan') }}';
        const pageSize = 6;

        function formatRupiah(angka) {
            if (angka == null) return 'Rp 0';
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        }

        function loadPembangunan(pageNumber = 1) {
            const apiUrl = `${apiPembangunan}?sort=-created_at&page[number]=${pageNumber}&page[size]=${pageSize}`;
            
            pembangunanList.innerHTML = `<div class="col-span-full flex flex-col items-center justify-center py-12"><svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><p class="mt-2 text-sm font-semibold">Memuat Data Pembangunan...</p></div>`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    pembangunanList.innerHTML = '';
                    if (!data.data || data.data.length === 0) {
                        pembangunanList.innerHTML = `<div class="col-span-full text-center py-12 text-gray-500">Tidak ada data pembangunan untuk ditampilkan.</div>`;
                        if (paginationContainer) paginationContainer.innerHTML = '';
                        return;
                    }

                    data.data.forEach(item => {
                        pembangunanList.insertAdjacentHTML('beforeend', createCard(item.attributes));
                    });
                    
                    if (paginationContainer && data.links) {
                        paginationContainer.innerHTML = data.links;
                    }
                })
                .catch(error => {
                    console.error("Error fetching data:", error);
                    pembangunanList.innerHTML = `<div class="col-span-full text-center py-12 text-red-500">Terjadi kesalahan saat memuat data.</div>`;
                });
        }

        function createCard(itemAttributes) {
            const imageUrl = itemAttributes.foto || `{{ theme_asset('images/placeholder.png') }}`;
            const anggaran = formatRupiah(itemAttributes.anggaran);
            const slug = itemAttributes.slug;
            const url = SITE_URL + 'pembangunan/' + slug;
            const judul = itemAttributes.judul;
            const lokasi = itemAttributes.lokasi;
            const tahun = itemAttributes.tahun_anggaran;

            return `
                <div class="bg-white dark:bg-gray-800/50 shadow-lg overflow-hidden flex flex-col group border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <a href="${url}" class="block overflow-hidden h-48">
                        <img src="${imageUrl}" alt="${escapeHtml(judul)}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    </a>
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100 line-clamp-2 flex-grow">
                            <a href="${url}" class="hover:text-blue-600">${escapeHtml(judul)}</a>
                        </h3>
                        <div class="mt-3 space-y-2 text-xs text-gray-600 dark:text-gray-400">
                            <div class="flex items-center"><i class="fas fa-map-marker-alt fa-fw mr-2"></i><span>${escapeHtml(lokasi)}</span></div>
                            <div class="flex items-center"><i class="fas fa-money-bill-wave fa-fw mr-2"></i><span>${anggaran}</span></div>
                            <div class="flex items-center"><i class="fas fa-calendar-alt fa-fw mr-2"></i><span>Tahun ${escapeHtml(tahun)}</span></div>
                        </div>
                        <a href="${url}" class="btn btn-primary w-full mt-4 text-sm">Lihat Detail</a>
                    </div>
                </div>
            `;
        }
        
        function escapeHtml(text) {
             if (typeof text !== 'string') return '';
            return text.replace(/[&<>"']/g, m => ({'&': '&amp;','<': '&lt;','>': '&gt;','"': '&quot;',"'": '&#039;'})[m]);
        }
        
        loadPembangunan(1);

        $('body').on('click', '.pagination a', function(e) {
            e.preventDefault();
            const url = new URL($(this).attr('href'));
            const page = url.searchParams.get('page[number]');
            loadPembangunan(page);
        });
    });
</script>
@endpush