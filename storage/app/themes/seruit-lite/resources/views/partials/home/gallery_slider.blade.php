@php
    $album_galeri = $w_gal ?? [];
    $active_gradient = 'from-green-500 to-teal-500';
@endphp

@if (count($album_galeri) > 0)
<section class="my-12">
    <div class="flex items-center mb-8">
        <h2 class="px-6 py-2 text-sm font-bold text-white uppercase tracking-wider shadow-md rounded-none bg-gradient-to-r {{ $active_gradient }}" style="clip-path: polygon(0 0, 100% 0, 92% 100%, 0% 100%);">
            Album Galeri
        </h2>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>

    <div class="swiper lite-gallery-swiper h-64 sm:h-80 shadow-xl">
        <div class="swiper-wrapper">
            @foreach ($album_galeri as $album)
                @php
                    $image_url = AmbilGaleri($album['gambar'], 'sedang') ?: theme_asset('images/placeholder.png');
                @endphp
                <div class="swiper-slide relative group overflow-hidden">
                    <img src="{{ $image_url }}" alt="{{ e($album['nama']) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-6">
                        <div class="text-white">
                            <h3 class="font-bold text-lg leading-tight uppercase">{{ e($album['nama']) }}</h3>
                            <a href="{{ site_url('galeri/'.$album['slug']) }}" class="inline-block mt-2 text-[10px] font-bold text-teal-400 hover:text-white uppercase tracking-widest transition-colors">Lihat Album &rarr;</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper-button-next text-white"></div>
        <div class="swiper-button-prev text-white"></div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swiper !== 'undefined') {
            new Swiper('.lite-gallery-swiper', {
                loop: true,
                autoplay: { delay: 5000 },
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            });
        }
    });
</script>
@endpush
@endif