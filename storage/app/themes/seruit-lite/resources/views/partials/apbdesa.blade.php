@php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $gradient_lite = 'from-green-500 to-teal-500';
@endphp

<div class="hidden lg:grid grid-cols-1 lg:grid-cols-3 gap-8">
    @foreach ($transparansi['data_widget'] as $subdatas)
        @include('theme::partials.apbdesa_card', ['subdatas' => $subdatas, 'gradient_class' => $gradient_lite])
    @endforeach
</div>

<div class="lg:hidden">
    <div class="swiper apbdes-swiper">
        <div class="swiper-wrapper">
            @foreach ($transparansi['data_widget'] as $subdatas)
                <div class="swiper-slide h-auto pb-4">
                    @include('theme::partials.apbdesa_card', ['subdatas' => $subdatas, 'gradient_class' => $gradient_lite])
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swiper !== 'undefined') {
            new Swiper('.apbdes-swiper', {
                slidesPerView: 1.1,
                spaceBetween: 16,
                centeredSlides: true,
                grabCursor: true,
            });
        }
    });
</script>
@endpush