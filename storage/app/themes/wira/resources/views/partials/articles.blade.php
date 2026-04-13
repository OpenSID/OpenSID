{{-- resources/views/partials/articles.blade.php --}}

<div class="mt-8" id="articles-section">
    <div class="flex flex-col gap-4 mb-6">

        <div class="flex flex-wrap gap-2">
            <a href="/status-idm/2024" class="px-4 py-1.5 text-sm font-semibold text-gray-900
                     border border-green-700 rounded-full
                      hover:bg-green-700 hover:text-white
                     transition">
                IDM
            </a>
            <a href="/galeri" class="px-4 py-1.5 text-sm font-semibold text-gray-900
                   border border-green-700 rounded-full
                   hover:bg-green-700 hover:text-white
                  transition">
                Galeri
            </a>
            <a href="/peta" class="px-4 py-1.5 text-sm font-semibold text-gray-900
                   border border-green-700 rounded-full
                  hover:bg-green-700 hover:text-white
                  transition">
                Peta
            </a>
            <button id="btn-pemerintah-desa" class="px-4 py-1.5 text-sm font-semibold text-gray-900
                    border border-green-700 rounded-full
                    hover:bg-green-700 hover:text-white
                    transition">
                Pemerintah Desa
            </button>
        </div>
        {{-- TOP ROW: Pills (left) + Paging (right) --}}
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">
                Artikel {{ ucfirst(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }}
            </h2>


            {{-- RIGHT: Paging --}}
            <div class="text-sm">
                @include('theme::commons.paging', ['paging_page' => $paging_page])
            </div>
        </div>

    </div>

    {{-- Pemerintah Desa Popup - COMPLETELY FIXED --}}
    <div id="pemerintah-popup" class="fixed inset-0 z-50 hidden">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-md transition-opacity popup-overlay"
            onclick="closePopup()">
        </div>

        {{-- Popup Container - FIXED: Proper centering and padding --}}
        <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 z-50 pointer-events-none">

            {{-- Popup Content - FIXED: All height constraints properly set --}}
            <div class="pointer-events-auto relative w-full max-w-6xl
                bg-white shadow-2xl
                rounded-2xl sm:rounded-3xl
                flex flex-col
                popup-content" style="max-height: 80vh;" onclick="event.stopPropagation()">

                {{-- Close Button --}}
                <div class="absolute top-3 right-3 sm:top-4 sm:right-4 z-20">
                    <button class="w-10 h-10 rounded-full
                           bg-white/90 backdrop-blur
                           flex items-center justify-center
                           shadow-lg hover:shadow-xl hover:scale-110 active:scale-95 
                           transition-all duration-200" onclick="closePopup()">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="pemerintah-list" class="overflow-y-auto custom-scrollbar w-full"
                    style="max-height: 80vh; min-height: 200px;">

                    {{-- Loading State --}}
                    <div class="flex items-center justify-center py-20">
                        <div class="text-center">
                            <div class="relative inline-block">
                                <div
                                    class="animate-spin w-12 h-12 border-4 border-green-600 border-t-transparent rounded-full">
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-500">Memuat data...</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    @php
        $filteredArtikel = $artikel->reject(fn($post) => $post['kategori'] === 'agenda');
    @endphp

    @if ($filteredArtikel->count() > 0)
        <!-- Mobile Carousel Container (visible on mobile only) -->
        <div class="block sm:hidden">
            <div class="relative">
                <!-- Carousel Wrapper -->
                <div id="mobile-articles-carousel"
                    class="flex gap-4 overflow-x-auto scrollbar-hide pb-4 snap-x snap-mandatory"
                    style="scroll-behavior: smooth; -webkit-overflow-scrolling: touch;">
                    @foreach ($filteredArtikel->take(6) as $index => $post)
                        <div class="flex-shrink-0 w-80 snap-start">
                            <div class="mobile-article-wrapper">
                                @include('theme::partials.artikel.list', ['post' => $post])
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Navigation Arrows -->
                <button id="carousel-prev"
                    class="absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-600 hover:text-green-600 transition-colors z-10 opacity-90 hover:opacity-100"
                    aria-label="Previous article">
                    <svg class="w-5 h-5 text-green-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <button id="carousel-next"
                    class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-600 hover:text-green-600 transition-colors z-10 opacity-90 hover:opacity-100"
                    aria-label="Next article">
                    <svg class="w-5 h-5 text-green-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Dots Indicator -->
                <div class="flex justify-center mt-4 gap-2" id="carousel-indicators" role="tablist"
                    aria-label="Article navigation">
                    @foreach ($filteredArtikel->take(6) as $index => $post)
                        <button class="w-2 h-2 rounded-full transition-all duration-200 carousel-dot" data-slide="{{ $index }}"
                            role="tab" aria-label="Go to article {{ $index + 1 }}"
                            style="background-color: {{ $index === 0 ? '#16a34a' : '#d1d5db' }}"></button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Desktop Grid (hidden on mobile, visible on sm and up) - 2 rows with 3 columns each (6 articles total) -->
        <div class="hidden sm:grid sm:grid-cols-3 gap-6">
            @foreach ($filteredArtikel->take(6) as $post)
                @include('theme::partials.artikel.list', ['post' => $post])
            @endforeach
        </div>
    @else
        @include('theme::partials.artikel.empty', ['title' => $title])
    @endif
</div>

<!-- Load Articles CSS -->
<link rel="stylesheet" href="{{ theme_asset('css/articles.css') }}">

<!-- Articles Configuration -->
<script>
    window.ARTICLES_CONFIG = {
        desaLabel: '{{ ucfirst(setting("sebutan_desa")) }}',
        desaNama: '{{ ucwords($desa["nama_desa"]) }}'
    };
</script>

<!-- Load Articles JS -->
<script src="{{ theme_asset('js/articles.js') }}"></script>
