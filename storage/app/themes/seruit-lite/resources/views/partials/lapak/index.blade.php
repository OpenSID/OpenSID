@extends('theme::layouts.full-content')

@push('styles')
<style>
    .pagination-link { @apply py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white disabled:opacity-50 disabled:cursor-not-allowed; border-radius: 0 !important; }
    .pagination-link.active { @apply z-10 text-blue-600 border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white; }
    .swiper-container { z-index: 0; } 
    .swiper-button-next, .swiper-button-prev { color: white !important; transform: scale(0.7); text-shadow: 0 1px 3px rgba(0,0,0,0.5); }
</style>
@endpush

@section('content')
<div x-data="lapakData()" x-init="init()" class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">Lapak</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">Lapak Produk Desa</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>

    <div class="mt-8 mb-6 p-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/3">
            <label for="filter-kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori Produk</label>
            <select x-model="filterKategori" @change="applyFilter" id="filter-kategori" class="form-input w-full dark:bg-gray-700 dark:border-gray-600">
                <option value="">Semua Kategori</option>
                <template x-for="kategori in kategoriList" :key="kategori.id">
                    <option :value="kategori.id" x-text="kategori.attributes.kategori"></option>
                </template>
            </select>
        </div>
        <div class="relative w-full md:w-2/3">
            <label for="filter-search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Produk</label>
            <input type="text" x-model="filterSearch" @keydown.enter.prevent="applyFilter" id="filter-search" placeholder="Masukkan nama produk..." class="form-input w-full pr-10 dark:bg-gray-700 dark:border-gray-600">
            <button @click="applyFilter" class="absolute inset-y-0 right-0 top-6 flex items-center justify-center w-10 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>

    <div id="produk-list-container">
        <div x-show="isLoading" class="col-span-full flex flex-col items-center justify-center py-12">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <p class="mt-2 text-sm font-semibold">Memuat Produk...</p>
        </div>
        
        <div x-show="!isLoading && errorMessage" class="col-span-full text-center py-12">
            <p class="text-red-500 font-bold" x-text="errorMessage"></p>
            <p class="text-xs text-gray-500 mt-2">Silakan cek log server OpenSID atau hubungi admin.</p>
        </div>

        <div x-show="!isLoading && !errorMessage && produkList.length === 0" class="col-span-full text-center py-12 text-gray-500">
            Tidak ada produk yang sesuai dengan kriteria pencarian.
        </div>
        
        <div x-show="!isLoading && produkList.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <template x-for="produk in produkList" :key="produk.id">
                <div class="bg-white dark:bg-gray-800/50 shadow-lg overflow-hidden flex flex-col group border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    <div class="relative">
                        <div class="swiper swiper-container h-48 w-full">
                            <div class="swiper-wrapper">
                                <template x-if="produk.attributes.foto && produk.attributes.foto.length > 0">
                                    <template x-for="foto in produk.attributes.foto" :key="foto">
                                        <div class="swiper-slide"><img :src="foto" :alt="`Foto ${produk.attributes.nama}`" class="w-full h-full object-cover"></div>
                                    </template>
                                </template>
                                <template x-if="!produk.attributes.foto || produk.attributes.foto.length === 0">
                                    <div class="swiper-slide"><img src="{{ theme_asset('images/placeholder.png') }}" alt="Tidak ada foto" class="w-full h-full object-cover"></div>
                                </template>
                            </div>
                            <div class="swiper-button-next"></div><div class="swiper-button-prev"></div>
                        </div>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100 line-clamp-2" x-text="produk.attributes.nama"></h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Oleh: <span x-text="produk.attributes.pelapak.penduduk ? produk.attributes.pelapak.penduduk.nama : 'Admin Desa'"></span></p>
                        <div class="text-sm text-gray-600 dark:text-gray-300 mt-2 line-clamp-3 flex-grow" x-html="produk.attributes.deskripsi"></div>
                        <div class="mt-4">
                            <span class="text-xl font-bold text-green-600 dark:text-green-400" x-text="formatMoney(produk.attributes.harga_diskon)"></span>
                            <span x-show="produk.attributes.harga_diskon < produk.attributes.harga" class="ml-2 text-sm text-red-500 line-through" x-text="formatMoney(produk.attributes.harga)"></span>
                        </div>
                        <div class="mt-4 flex flex-col sm:flex-row gap-2">
                            <a :href="produk.attributes.pesan_wa" target="_blank" rel="noopener noreferrer" class="flex-1 btn bg-green-500 text-white hover:bg-green-600 text-sm flex items-center justify-center"><i class="fab fa-whatsapp mr-2"></i> Beli</a>
                            <button @click="openPeta(produk.attributes.pelapak)" class="flex-1 btn bg-blue-500 text-white hover:bg-blue-600 text-sm flex items-center justify-center"><i class="fas fa-map-marker-alt mr-2"></i> Lokasi</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
    
    <div id="pagination-container" class="mt-8"></div>

    <div x-show="modalPetaOpen" x-cloak class="fixed inset-0 z-[999] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="modalPetaOpen" @click="modalPetaOpen = false" x-transition class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div x-show="modalPetaOpen" x-transition class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-lg">
                <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-bold" x-text="`Lokasi Pelapak: ${modalPetaTitle}`"></h3>
                    <button @click="modalPetaOpen = false" class="p-2 -m-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">&times;</button>
                </div>
                <div class="p-1 relative">
                    <div id="map-pelapak" class="w-full h-96 z-0"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function lapakData() {
        return {
            produkList: [], kategoriList: [], isLoading: true, errorMessage: '',
            modalPetaOpen: false, modalPetaTitle: '', mapPelapak: null,
            filterKategori: '', filterSearch: '', pagination: {},
            init() { 
                this.loadKategori(); 
                this.loadProduk(1); 
            },
            formatMoney(angka) {
                if (angka == null || isNaN(angka)) return 'Rp 0';
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
            },
            loadKategori() {
                fetch('{{ route('api.lapak.kategori') }}')
                    .then(res => res.ok ? res.json() : Promise.resolve({ data: [] }))
                    .then(data => { this.kategoriList = data.data; })
                    .catch(() => {});
            },
            loadProduk(pageNumber) {
                this.isLoading = true;
                this.errorMessage = '';
                const params = new URLSearchParams({ 
                    'page[number]': pageNumber, 
                    'page[size]': 6 
                });
                if (this.filterKategori) params.append('filter[id_produk_kategori]', this.filterKategori);
                if (this.filterSearch) params.append('filter[search]', this.filterSearch);
                fetch(`{{ route('api.lapak.produk') }}?${params.toString()}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Server Error (' + response.status + ')');
                        return response.json();
                    })
                    .then(data => {
                        this.produkList = data.data;
                        this.pagination = { links: data.links, meta: data.meta.pagination };
                        this.$nextTick(() => { this.initSwipers(); this.renderPagination(); });
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching lapak:', error);
                        this.errorMessage = 'Gagal memuat data dari server. Kemungkinan ada kesalahan pada data produk di database.';
                        this.isLoading = false;
                    });
            },
            applyFilter() { this.loadProduk(1); },
            initSwipers() {
                document.querySelectorAll('.swiper-container').forEach(el => {
                    if (el.swiper) el.swiper.destroy(); 
                    new Swiper(el, {
                        loop: true,
                        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                        autoplay: { delay: 4000, disableOnInteraction: false },
                    });
                });
            },
            openPeta(pelapak) {
                this.modalPetaTitle = pelapak.penduduk ? pelapak.penduduk.nama : 'Admin Desa';
                this.modalPetaOpen = true;
                this.$nextTick(() => {
                    if (this.mapPelapak) { this.mapPelapak.remove(); this.mapPelapak = null; }
                    const mapContainer = document.getElementById('map-pelapak');
                    if (pelapak.lat && pelapak.lng) {
                        const posisi = [pelapak.lat, pelapak.lng];
                        const zoom = pelapak.zoom || 15;
                        this.mapPelapak = L.map(mapContainer).setView(posisi, zoom);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.mapPelapak);
                        const markerIconUrl = (typeof setting !== 'undefined' && setting.icon_lapak_peta) ? setting.icon_lapak_peta : '{{ asset("assets/images/gis/point/shop.png") }}';
                        const markerIcon = L.icon({ iconUrl: markerIconUrl, iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -28] });
                        L.marker(posisi, {icon: markerIcon}).addTo(this.mapPelapak).bindPopup(`<b>${this.modalPetaTitle}</b>`).openPopup();
                        setTimeout(() => { this.mapPelapak.invalidateSize(); }, 200);
                    } else {
                        mapContainer.innerHTML = '<div class="w-full h-full flex items-center justify-center text-gray-500 bg-gray-100">Lokasi tidak tersedia.</div>';
                    }
                });
            },
            renderPagination() {
                const container = document.getElementById('pagination-container');
                const { links, meta } = this.pagination;
                if (!links || !meta || meta.total_pages <= 1) { container.innerHTML = ''; return; }
                let html = `<nav class="flex items-center justify-between"><div class="hidden sm:block"><p class="text-sm text-gray-700 dark:text-gray-400">Menampilkan <span class="font-medium">${meta.from}</span> sampai <span class="font-medium">${meta.to}</span> dari <span class="font-medium">${meta.total}</span> hasil</p></div><div class="flex-1 flex justify-between sm:justify-end"><ul class="inline-flex items-center -space-x-px">`;
                html += `<li><button @click="loadProduk(${meta.current_page - 1})" ${meta.current_page === 1 ? 'disabled' : ''} class="pagination-link rounded-l-md">Sebelumnya</button></li>`;
                for (let i = 1; i <= meta.last_page; i++) {
                    if (i === meta.current_page) { html += `<li><button class="pagination-link active">${i}</button></li>`; } 
                    else if (i === 1 || i === meta.last_page || Math.abs(i - meta.current_page) < 2) { html += `<li><button @click="loadProduk(${i})" class="pagination-link">${i}</button></li>`; } 
                    else if (Math.abs(i - meta.current_page) === 2) { html += `<li><span class="pagination-link dots">...</span></li>`; }
                }
                html += `<li><button @click="loadProduk(${meta.current_page + 1})" ${meta.current_page === meta.last_page ? 'disabled' : ''} class="pagination-link rounded-r-md">Berikutnya</button></li>`;
                html += `</ul></div></nav>`;
                container.innerHTML = html;
            }
        };
    }
</script>
@endpush