@php
    $alt_slug = 'artikel';
    $is_homepage = in_array(request()->segment(1), ['', 'index', 'first']);
    $is_single_artikel = isset($single_artikel);
    $is_generic_page = !$is_homepage && !$is_single_artikel;
    $bg_header = !empty($latar_website) ? e($latar_website) : e(theme_asset('images/placeholder.png'));

    if ($is_single_artikel && !empty($single_artikel['gambar']) && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $single_artikel['gambar'])) {
        $bg_header = AmbilFotoArtikel($single_artikel['gambar'], 'sedang');
    } elseif (isset($judul_kategori['gambar']) && is_file(LOKASI_GAMBAR_KATEGORI . $judul_kategori['gambar'])) {
        $bg_header = base_url(LOKASI_GAMBAR_KATEGORI . $judul_kategori['gambar']);
    }

    $hero_height_class = $is_homepage ? 'min-h-[500px] lg:min-h-[600px]' : 'min-h-[350px] lg:min-h-[400px]';
    $blur_class = $is_homepage ? 'backdrop-blur-sm' : '';
    $title_desktop = 'SISTEM INFORMASI ' . strtoupper(setting('sebutan_desa') . ' ' . $desa['nama_desa']);
    $title_mobile = strtoupper($desa['nama_desa']);
    
    $gradient_class = 'from-green-500 to-teal-500';
    $gradient_class_hover = 'hover:from-green-600 hover:to-teal-600';
@endphp

@include('theme::commons.main_menu', ['gradient_class' => $gradient_class])

<div class="relative bg-cover bg-center text-white" style="background-image: url('{{ $bg_header }}')">
    <div class="absolute inset-0 bg-black/60 {{ $blur_class }}"></div>
    <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center text-center h-auto {{ $hero_height_class }} pt-28 lg:pt-32 pb-16">
        <div 
            x-data="{ textDesktop: '{{ e($title_desktop) }}', textMobile: '{{ e($title_mobile) }}', displayedText: '', charIndex: 0, isDeleting: false, isMobile: window.innerWidth < 1024, currentText() { return this.isMobile ? this.textMobile : this.textDesktop; }, type() { if (this.isMobile !== (window.innerWidth < 1024)) { this.isMobile = window.innerWidth < 1024; this.charIndex = 0; this.displayedText = ''; this.isDeleting = false; } const text = this.currentText(); const typeSpeed = this.isDeleting ? 75 : 150; if (!this.isDeleting && this.charIndex < text.length) { this.displayedText += text.charAt(this.charIndex); this.charIndex++; setTimeout(() => this.type(), typeSpeed); } else if (this.isDeleting && this.charIndex > 0) { this.displayedText = text.substring(0, this.charIndex - 1); this.charIndex--; setTimeout(() => this.type(), typeSpeed); } else { this.isDeleting = !this.isDeleting; setTimeout(() => this.type(), this.isDeleting ? 2000 : 500); } } }"
            x-init="type(); window.addEventListener('resize', () => { isMobile = window.innerWidth < 1024; })"
            class="w-full flex flex-col items-center"
        >
            <h1 class="text-2xl lg:text-4xl font-extrabold text-white tracking-wider uppercase drop-shadow-lg h-10 lg:h-12">
                <span x-text="displayedText"></span><span class="animate-blink border-r-2 border-white"></span>
            </h1>
            <p class="text-sm lg:text-lg text-gray-200 mt-2 drop-shadow">Kec. {{ ucwords($desa['nama_kecamatan']) }}, Kab. {{ ucwords($desa['nama_kabupaten']) }}</p>
        </div>

        @if ($is_single_artikel)
            <div class="mt-8 w-full">
                <nav role="navigation" aria-label="navigation" class="breadcrumb text-sm text-white/80">
                    <ol class="flex items-center justify-center space-x-2">
                        <li><a href="{{ site_url() }}" class="hover:text-white">Beranda</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li>
                            @if ($single_artikel['kategori'])
                                <a href="{{ site_url("{$alt_slug}/kategori/{$single_artikel['kat_slug']}") }}" class="hover:text-white">{{ $single_artikel['kategori'] }}</a>
                            @else
                                <a href="{{ site_url('arsip') }}" class="hover:text-white">Artikel</a>
                            @endif
                        </li>
                    </ol>
                </nav>
                <h2 class="mt-4 text-3xl lg:text-4xl font-bold tracking-tight drop-shadow-lg">{{ $single_artikel['judul'] }}</h2>
                <div class="mt-6 flex flex-wrap gap-x-6 gap-y-2 text-sm text-white/80 items-center justify-center">
                    <span><i class="fas fa-user fa-fw mr-1"></i> {{ $single_artikel['owner'] }}</span>
                    <span><i class="fas fa-calendar-alt fa-fw mr-1"></i> {{ tgl_indo(date('Y-m-d', strtotime($single_artikel['tgl_upload']))) }}</span>
                    <span><i class="fas fa-eye fa-fw mr-1"></i> Dibaca {{ hit($single_artikel['hit']) }}</span>
                </div>
                <div x-data="{ showShare: false }" class="mt-8">
                    <button @click="showShare = !showShare" class="px-6 py-3 border border-white/50 rounded-none text-white font-semibold hover:bg-white/10 transition">
                        Bagikan Artikel
                    </button>
                    <div x-show="showShare" x-transition @click.away="showShare = false" class="mt-2 flex space-x-2 p-2 bg-black/30 rounded-lg justify-center">
                        @php
                            $shareUrl = e(current_url());
                            $shareTitle = e($single_artikel['judul']);
                        @endphp
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 hover:bg-blue-700"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" class="w-10 h-10 flex items-center justify-center rounded-full bg-sky-500 hover:bg-sky-600"><i class="fab fa-twitter"></i></a>
                        <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener" class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 hover:bg-green-600"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" class="w-10 h-10 flex items-center justify-center rounded-full bg-sky-400 hover:bg-sky-500"><i class="fab fa-telegram-plane"></i></a>
                    </div>
                </div>
            </div>
        @else
            <form action="{{ site_url('/') }}" method="get" class="mt-8 w-full max-w-2xl {{ $is_homepage ? 'hidden lg:block' : 'block' }}">
                <input type="hidden" name="{{ get_instance()->security->get_csrf_token_name() }}" value="{{ get_instance()->security->get_csrf_hash() }}">
                <div class="relative">
                    <input type="text" name="cari" class="w-full py-3 pl-5 pr-28 rounded-none border-none text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-yellow-300" placeholder="Cari apa saja di sini...">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center justify-center w-24 rounded-none text-white font-bold bg-gradient-to-r {{ $gradient_class }} {{ $gradient_class_hover }} transition-all duration-300 dark:bg-none dark:bg-gray-700 dark:hover:bg-gray-600">
                        Cari
                    </button>
                </div>
            </form>

            @if ($is_generic_page)
                <div x-data="{ showShare: false }" class="mt-6 mb-8">
                    <button @click="showShare = !showShare" class="px-6 py-3 border border-white/50 rounded-none text-white font-semibold hover:bg-white/10 transition">
                        Bagikan Halaman Ini
                    </button>
                    <div x-show="showShare" x-transition @click.away="showShare = false" class="mt-2 flex space-x-2 p-2 bg-black/30 rounded-lg justify-center">
                        @php
                            $shareUrl = e(current_url());
                            $shareTitle = e($heading ?? ($judul_kategori['kategori'] ?? setting('website_title')));
                        @endphp
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 hover:bg-blue-700"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" class="w-10 h-10 flex items-center justify-center rounded-full bg-sky-500 hover:bg-sky-600"><i class="fab fa-twitter"></i></a>
                        <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener" class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 hover:bg-green-600"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" class="w-10 h-10 flex items-center justify-center rounded-full bg-sky-400 hover:bg-sky-500"><i class="fab fa-telegram-plane"></i></a>
                    </div>
                </div>
            @endif

            @if ($is_homepage)
                @include('theme::partials.icon-menu')
            @endif
        @endif
    </div>
</div>