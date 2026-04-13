<div class="lg:hidden items-center" x-data="{ menuOpen: false }" @keydown.escape.window="menuOpen = false">
    <!-- Mobile Menu Toggle Button -->
    <button type="button" 
            class="flex items-center space-x-1 text-green hover:text-gray-200 focus:outline-none md:hidden"
            @click="menuOpen = !menuOpen; $nextTick(() => { document.body.style.overflow = menuOpen ? 'hidden' : 'auto'; })">
        <i class="fas text-white transition-transform duration-300" :class="{ 'fa-bars': !menuOpen, 'fa-times': menuOpen }"></i>
        <span class="text-white text-sm uppercase">Menu</span>
    </button>

    <!-- Mobile Menu Overlay -->
    <div x-show="menuOpen" 
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="menuOpen = false; document.body.style.overflow = 'auto'"
         class="fixed inset-0 bg-black bg-opacity-50 mobile-menu-overlay"
         style="z-index: 999998;">
    </div>

    <!-- Mobile Menu Drawer -->
    <nav x-show="menuOpen" 
         x-transition:enter="transform transition ease-in-out duration-500"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed top-0 right-0 w-80 max-w-sm bg-primary-100 text-white shadow-2xl overflow-y-auto mobile-menu-nav"
         style="z-index: 999999; height: 100vh;"
         role="navigation">
        
        <!-- Drawer Header -->
        <div class="flex items-center justify-between p-4 border-b border-primary-200 bg-primary-200">
            <h3 class="text-lg font-semibold">Menu Navigasi</h3>
            <button @click="menuOpen = false; document.body.style.overflow = 'auto'" 
                    class="text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <!-- Menu Items -->
        <ul class="py-2">
            @php $menu_atas = menu_tema() @endphp
            @if ($menu_atas)
                @foreach ($menu_atas as $menu)
                    @php $has_dropdown = count($menu['childrens'] ?? []) > 0 @endphp
                    <li class="border-b border-primary-200/30" @if ($has_dropdown) x-data="{dropdownMain: false}" @endif>

                        @php $menu_link = $has_dropdown ? '#!' : $menu['link_url'] @endphp

                        <a href="{{ $menu_link }}" 
                           class="flex items-center justify-between px-4 py-4 hover:bg-primary-200 transition-colors duration-200 text-base" 
                           @if($has_dropdown) @click.prevent="dropdownMain = !dropdownMain" @else @click="menuOpen = false; document.body.style.overflow = 'auto'" @endif>
                            <span class="font-medium">{{ $menu['nama'] }}</span>

                            @if ($has_dropdown)
                                <i class="fas fa-chevron-down text-sm transition-transform duration-300" 
                                   :class="{ 'transform rotate-180': dropdownMain }"></i>
                            @endif
                        </a>

                        @if ($has_dropdown)
                            <ul class="bg-primary-200/50" 
                                x-show="dropdownMain"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 max-h-0"
                                x-transition:enter-end="opacity-100 max-h-96"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 max-h-96"
                                x-transition:leave-end="opacity-0 max-h-0"
                                style="overflow: hidden;">

                                @foreach ($menu['childrens'] as $childrens)
                                    @php $has_dropdown2 = count($childrens['childrens'] ?? []) > 0 @endphp

                                    <li class="border-b border-primary-300/20 last:border-b-0" @if ($has_dropdown2) x-data="{dropdownSub: false}" @endif>
                                        @php $menu_link2 = $has_dropdown2 ? '#!' : $childrens['link_url'] @endphp
                                        <a href="{{ $menu_link2 }}" 
                                           class="flex items-center justify-between px-6 py-3 hover:bg-primary-300/50 transition-colors duration-200" 
                                           @if($has_dropdown2) @click.prevent="dropdownSub = !dropdownSub" @else @click="menuOpen = false; document.body.style.overflow = 'auto'" @endif>
                                            <span>{{ $childrens['nama'] }}</span>
                                            @if ($has_dropdown2)
                                                <i class="fas fa-chevron-down text-xs transition-transform duration-300" 
                                                   :class="{ 'transform rotate-180': dropdownSub }"></i>
                                            @endif
                                        </a>

                                        @if ($has_dropdown2)
                                            <ul class="bg-primary-300/50" 
                                                x-show="dropdownSub"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 max-h-0"
                                                x-transition:enter-end="opacity-100 max-h-64"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100 max-h-64"
                                                x-transition:leave-end="opacity-0 max-h-0"
                                                style="overflow: hidden;">
                                                @foreach ($childrens['childrens'] as $children)
                                                    <li class="border-b border-primary-400/20 last:border-b-0">
                                                        <a href="{{ $children['link_url'] }}" 
                                                           class="block px-8 py-3 hover:bg-primary-400/50 transition-colors duration-200"
                                                           @click="menuOpen = false; document.body.style.overflow = 'auto'">
                                                            {{ $children['nama'] }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                    </li>
                                @endforeach

                            </ul>
                        @endif
                    </li>
                @endforeach
            @endif
        </ul>
    </nav>
</div>