<section class="sliderx w-full relative group transition-all duration-300 overflow-hidden">
    <div class="owl-carousel rounded-lg h-48 lg:h-[400px] z-10 relative w-full">
        @foreach ($slider_gambar['gambar'] as $data)
            @php
                $img = $slider_gambar['lokasi'] . 'sedang_' . $data['gambar'];
            @endphp
            @if (is_file($img))
                <figure class="h-48 lg:h-[400px] w-full">
                    <img src="{{ base_url($img) }}" alt="{{ $data['judul'] }}" class="max-w-full w-full h-48 lg:h-[400px] object-cover">

                    @if ($slider_gambar['sumber'] != 3)
                        <div class="absolute bg-black bg-opacity-60 bottom-0 left-0 right-0 text-white group">
                            <a href="{{ site_url('artikel/' . buat_slug($data)) }}" class="font-bold text-h5 block py-4 px-5 group-hover:py-5 transition-all duration-300">{{ $data['judul'] }}</a>
                        </div>
                    @endif
                </figure>
            @endif
        @endforeach
    </div>
    <div class="slider-nav">
        <span class="slider-nav-prev px-1 py-2 cursor-pointer transition-all duration-300 opacity-0 group-hover:opacity-100 group-hover:bg-primary-100 shadow absolute top-1/2 left-0 transform -translate-y-1/2 z-[99]" title="Sebelumnya"><i
                class="fas fa-chevron-left text-lg text-white px-3"></i></span>
        <span class="slider-nav-next px-1 py-2 cursor-pointer transition-all duration-300 opacity-0 group-hover:opacity-100 group-hover:bg-primary-100 shadow absolute top-1/2 right-0 transform -translate-y-1/2 z-[99]" title="Selanjutnya"><i
                class="fas fa-chevron-right text-lg text-white px-3"></i></span>
    </div>
</section>
