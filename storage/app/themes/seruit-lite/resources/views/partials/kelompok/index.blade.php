@extends('theme::layouts.full-content')

@section('content')
<div x-data="kelompokIndex()" class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4">
        <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
            <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300">Kelompok Masyarakat</li>
        </ol>
    </nav>
    <div class="flex items-center mt-6">
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
        <h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase">Kelompok Masyarakat</h1>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>

    <div id="kelompok-list" class="mt-8">
        <div x-show="isLoading" class="col-span-full flex flex-col items-center justify-center py-12">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <p class="mt-2 text-sm font-semibold">Memuat Kategori Kelompok...</p>
        </div>
        
        <div x-show="!isLoading && kategoriList.length === 0" class="col-span-full text-center py-12 text-gray-500">
            Tidak ada data kelompok untuk ditampilkan.
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="(kategori, index) in kategoriList" :key="kategori.id">
                <a :href="kategori.attributes.url" class="block shadow-lg overflow-hidden group transform transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl border border-gray-200 dark:border-gray-700">
                    <div class="p-5 text-white" :class="getGradientClass(index)">
                        <div class="flex justify-between items-start">
                            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-users text-3xl"></i>
                            </div>
                            <span class="text-sm font-bold bg-white/20 px-3 py-1 rounded-full" x-text="`${kategori.attributes.jumlah} Kelompok`"></span>
                        </div>
                        <h3 class="font-bold text-2xl mt-4" x-text="kategori.attributes.kelompok"></h3>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 text-sm text-gray-600 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                        Lihat Daftar Kelompok &rarr;
                    </div>
                </a>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function kelompokIndex() {
        return {
            kategoriList: [],
            isLoading: true,
            gradients: [
                'bg-gradient-to-br from-blue-500 to-cyan-400',
                'bg-gradient-to-br from-emerald-500 to-green-400',
                'bg-gradient-to-br from-purple-500 to-fuchsia-400',
                'bg-gradient-to-br from-amber-500 to-yellow-400',
                'bg-gradient-to-br from-rose-500 to-red-400',
                'bg-gradient-to-br from-sky-500 to-cyan-400'
            ],
            init() {
                fetch('{{ route('api.kelompok') }}')
                    .then(response => response.json())
                    .then(data => {
                        this.kategoriList = data.data;
                        this.isLoading = false;
                    })
                    .catch(() => {
                        this.isLoading = false;
                        document.getElementById('kelompok-list').innerHTML = '<div class="alert alert-danger">Gagal memuat data.</div>';
                    });
            },
            getGradientClass(index) {
                return this.gradients[index % this.gradients.length];
            }
        };
    }
</script>
@endpush