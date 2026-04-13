<section class="container mx-auto px-4 sm:px-6 lg:px-8 my-6">
    <div class="relative">
        <div class="owl-carousel owl-theme">
            @foreach ($slider_gambar['gambar'] as $data)
                @if (is_file($slider_gambar['lokasi'] . 'sedang_' . $data['gambar']))
                    <div class="item relative h-56 md:h-96">
                        <img src="{{ AmbilFotoArtikel($data['gambar'], 'sedang') }}" alt="{{ $data['judul'] }}" class="w-full h-full object-cover rounded-lg">
                        @if ($slider_gambar['sumber'] != 3)
                            <div class="absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-black to-transparent rounded-b-lg">
                                <a href="{{ site_url('artikel/' . buat_slug($data)) }}" class="text-white text-lg font-bold hover:underline">{{ $data['judul'] }}</a>
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 10,
                nav: true,
                dots: false,
                autoplay: true,
                responsive:{ 0:{ items:1 }, 600:{ items:1 }, 1000:{ items:1 } }
            });
        });
    </script>
@endpush