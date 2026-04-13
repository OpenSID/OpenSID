@php
    $social_media = [
        'facebook' => ['color' => 'bg-blue-600', 'icon' => 'fa-facebook-f', 'link' => 'https://facebook.com/sharer.php?u='],
        'twitter' => ['color' => 'bg-blue-400', 'icon' => 'fa-twitter', 'link' => 'https://twitter.com/share?url='],
        'whatsapp' => ['color' => 'bg-green-500', 'icon' => 'fa-whatsapp', 'link' => 'https://api.whatsapp.com/send?text='],
        'telegram' => ['color' => 'bg-blue-500', 'icon' => 'fa-telegram', 'link' => 'https://telegram.me/share/url?url='],
    ];
@endphp

<div class="fixed bottom-5 left-5 z-40 lg:top-1/2 lg:bottom-auto lg:-translate-y-1/2" 
     x-data="{ show: false }" 
     @scroll.window="show = (window.scrollY > 300)"
     x-show="show"
     x-transition>
    <div class="flex flex-col space-y-2">
        @foreach ($social_media as $key => $data)
            <a href="{{ $data['link'] . current_url() }}" target="_blank" rel="noopener noreferrer" 
               class="w-10 h-10 rounded-full flex items-center justify-center text-white {{ $data['color'] }} hover:opacity-80 transition"
               aria-label="Bagikan ke {{ ucfirst($key) }}" title="Bagikan ke {{ ucfirst($key) }}">
                <i class="fab {{ $data['icon'] }}"></i>
            </a>
        @endforeach
    </div>
</div>