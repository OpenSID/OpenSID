@php
$gradient_class = 'from-green-500 to-teal-500';
$menu_utama = menu_tema() ?? [];
$menu_kiri = array_slice($menu_utama, 0, 4);
$sisa_setelah_kiri = array_slice($menu_utama, 4);
$menu_kanan = array_slice($sisa_setelah_kiri, 0, 3);
$menu_overflow = array_slice($sisa_setelah_kiri, 3);

$social_icons = [
    'facebook'  => 'fab fa-facebook-f',
    'twitter'   => 'fab fa-twitter',
    'instagram' => 'fab fa-instagram',
    'youtube'   => 'fab fa-youtube',
    'whatsapp'  => 'fab fa-whatsapp',
    'telegram'  => 'fab fa-telegram',
    'tiktok'    => 'fab fa-tiktok'
];
@endphp

<nav class="fixed top-0 left-0 right-0 z-[1001] bg-gradient-to-r text-white shadow-md dark:bg-none dark:bg-gray-800/80 dark:backdrop-blur-sm"
:class="darkMode ? '' : '{{ $gradient_class }}'"
x-data="{ socialsOpen: false }">

<div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
    <div class="flex items-center justify-between h-16">
        <div class="flex-1 flex justify-start items-center">
            <div class="lg:hidden relative">
                <button @click="socialsOpen = !socialsOpen" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-white/20 focus:outline-none" 
                        aria-label="Buka Media Sosial">
                    <i class="fas fa-share-alt"></i>
                </button>
                @if ($sosmed)
                    <div x-show="socialsOpen" 
                         @click.away="socialsOpen = false" 
                         x-cloak 
                         x-transition 
                         class="origin-top-left absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 ring-1 ring-black ring-opacity-5">
                        @foreach ($sosmed as $data)
                            @if (!empty($data["link"]))
                                <a href="{{ $data['link'] }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="{{ $social_icons[strtolower($data['nama'])] ?? 'fas fa-link' }} w-5 h-5 mr-3"></i>
                                    <span>{{ $data['nama'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="hidden lg:flex items-center">
                <a href="{{ site_url('/') }}" 
                   class="px-3 py-2 rounded-md text-sm font-medium hover:bg-white/10" 
                   aria-label="Beranda">
                    <i class="fa fa-home fa-lg"></i>
                </a>
                @foreach ($menu_kiri as $menu)
                    @include('theme::partials.menu_item', ['menu' => $menu])
                @endforeach
            </div>
        </div>

        <div class="hidden lg:block flex-shrink-0 w-24"></div>

        <div class="flex-1 flex justify-end items-center space-x-2">
            <div class="hidden lg:flex items-center">
                @foreach ($menu_kanan as $menu)
                    @include('theme::partials.menu_item', ['menu' => $menu])
                @endforeach

                @if (count($menu_overflow) > 0)
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-white/10 flex items-center transition-colors">
                            <i class="fas fa-bars fa-lg"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                @foreach ($menu_overflow as $item)
                                    <a href="{{ $item['link_url'] }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        {{ $item['nama'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <button @click="toggleDarkMode" 
                    type="button" 
                    class="dark-mode-toggle" 
                    aria-label="Toggle Dark Mode">
            </button>
             
            <div class="hidden lg:block relative">
                <button @click="socialsOpen = !socialsOpen" 
                        class="p-2 rounded-full text-white hover:bg-white/20 transition" 
                        aria-label="Buka Media Sosial">
                    <i class="fas fa-share-alt"></i>
                </button>
                @if ($sosmed)
                    <div x-show="socialsOpen" 
                         @click.away="socialsOpen = false" 
                         x-cloak 
                         x-transition 
                         class="origin-top-right absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 ring-1 ring-black ring-opacity-5">
                        @foreach ($sosmed as $data)
                            @if (!empty($data["link"]))
                                <a href="{{ $data['link'] }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="{{ $social_icons[strtolower($data['nama'])] ?? 'fas fa-link' }} w-5 h-5 mr-3"></i>
                                    <span>{{ $data['nama'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <button @click="navOpen = true" 
                    class="hidden lg:block p-2 rounded-full text-white hover:bg-white/10 transition" 
                    aria-label="Buka Menu Utama">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
            </button>
        </div>
    </div>

    <div class="absolute left-1/2 top-full -translate-x-1/2 -translate-y-1/2">
        <a href="{{ site_url('/') }}" 
           class="block w-20 h-20 bg-white dark:bg-gray-700 p-1 rounded-full shadow-lg border-2 border-white dark:border-gray-600 group">
            <img class="h-full w-full object-contain rounded-full transition-transform duration-300 group-hover:scale-110" 
                 src="{{ gambar_desa($desa['logo']) }}" 
                 alt="Logo {{ ucfirst(setting('sebutan_desa')) . ' ' . ucwords($desa['nama_desa']) }}">
        </a>
    </div>
</div>
</nav>