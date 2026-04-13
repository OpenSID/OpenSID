@extends('theme::layouts.full-content')

@push('styles')
<style>
    .pagination-link { @apply py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white disabled:opacity-50 disabled:cursor-not-allowed; }
    .pagination-link.active { @apply z-10 text-blue-600 border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white; }
    .pagination-link.dots { @apply cursor-default; }
</style>
@endpush

@section('content')
<div 
    x-data="pemerintahData()" 
    x-init="loadPemerintah(1)"
    class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10"
>
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">{{ ucwords(setting('sebutan_pemerintah_desa')) }}</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">{{ ucwords(setting('sebutan_pemerintah_desa')) }}</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>

    <div class="mt-8">
        <div x-show="isLoading" class="text-center py-12">
            <div class="flex items-center justify-center space-x-2 text-gray-500">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span class="font-semibold">Memuat Data Aparatur...</span>
            </div>
        </div>

        <div x-show="!isLoading && pemerintahList.length === 0" class="text-center py-12 text-gray-500">
            <p>Data aparatur desa tidak tersedia.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <template x-for="pemerintah in pemerintahList" :key="pemerintah.id">
                <div class="bg-gray-50 dark:bg-gray-800/50 shadow-lg overflow-hidden flex flex-col items-center text-center p-6 border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <img :src="pemerintah.attributes.foto || '{{ theme_asset('images/placeholder.png') }}'" :alt="pemerintah.attributes.nama"
                         class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-md mb-4">
                    
                    <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100" x-text="pemerintah.attributes.nama"></h3>
                    <p class="text-sm text-blue-600 dark:text-blue-400 font-semibold" x-text="pemerintah.attributes.nama_jabatan"></p>
                    
                    <template x-if="pemerintah.attributes.kehadiran == 1">
                        <div class="mt-2">
                            <template x-if="pemerintah.attributes.status_kehadiran === 'hadir'">
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">Hadir</span>
                            </template>
                            <template x-if="pemerintah.attributes.status_kehadiran !== 'hadir'">
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300" x-text="pemerintah.attributes.status_kehadiran.charAt(0).toUpperCase() + pemerintah.attributes.status_kehadiran.slice(1)"></span>
                            </template>
                        </div>
                    </template>

                    <div class="flex space-x-3 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <template x-for="platform in socialMediaPlatforms" :key="platform">
                            <template x-if="pemerintah.attributes.media_sosial && pemerintah.attributes.media_sosial[platform]">
                                <a :href="pemerintah.attributes.media_sosial[platform]" target="_blank" rel="noopener noreferrer" :title="platform.charAt(0).toUpperCase() + platform.slice(1)"
                                   class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    <i :class="`fab fa-${platform} fa-lg`"></i>
                                </a>
                            </template>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
    
    <div id="pagination-container" class="mt-8"></div>
</div>
@endsection

@push('scripts')
<script>
    function pemerintahData() {
        return {
            isLoading: true,
            pemerintahList: [],
            socialMediaPlatforms: JSON.parse('{!! json_encode(json_decode(setting('media_sosial_pemerintah_desa')) ?? []) !!}'),

            loadPemerintah(pageNumber) {
                this.isLoading = true;
                const apiUrl = `{{ route('api.pemerintah') }}?page[number]=${pageNumber}&page[size]=8`;

                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        this.pemerintahList = data.data;
                        this.renderPagination(data.links, data.meta.pagination);
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error("Error fetching data:", error);
                        this.isLoading = false;
                        document.querySelector('#pemerintah-list').innerHTML = `<div class="col-span-full text-center py-12 text-red-500">Gagal memuat data aparatur.</div>`;
                    });
            },

            renderPagination(links, meta) {
                const container = document.getElementById('pagination-container');
                if (!links || !meta || meta.total_pages <= 1) {
                    container.innerHTML = '';
                    return;
                }
                
                let paginationHtml = `<nav class="flex items-center justify-between"><div class="hidden sm:block"><p class="text-sm text-gray-700 dark:text-gray-400">Menampilkan <span class="font-medium">${meta.from}</span> sampai <span class="font-medium">${meta.to}</span> dari <span class="font-medium">${meta.total}</span> hasil</p></div><div class="flex-1 flex justify-between sm:justify-end"><ul class="inline-flex items-center -space-x-px">`;

                const prevDisabled = meta.current_page === 1 ? 'disabled' : '';
                paginationHtml += `<li><button @click="loadPemerintah(${meta.current_page - 1})" ${prevDisabled} class="pagination-link rounded-l-none">Sebelumnya</button></li>`;

                for (let i = 1; i <= meta.last_page; i++) {
                     if (i === meta.current_page) {
                        paginationHtml += `<li><button class="pagination-link active">${i}</button></li>`;
                    } else if (i === 1 || i === meta.last_page || Math.abs(i - meta.current_page) < 2) {
                        paginationHtml += `<li><button @click="loadPemerintah(${i})" class="pagination-link">${i}</button></li>`;
                    } else if (Math.abs(i - meta.current_page) === 2) {
                        paginationHtml += `<li><span class="pagination-link dots">...</span></li>`;
                    }
                }
                
                const nextDisabled = meta.current_page === meta.last_page ? 'disabled' : '';
                paginationHtml += `<li><button @click="loadPemerintah(${meta.current_page + 1})" ${nextDisabled} class="pagination-link rounded-r-none">Berikutnya</button></li>`;

                paginationHtml += `</ul></div></nav>`;
                container.innerHTML = paginationHtml;
            }
        };
    }
</script>
@endpush