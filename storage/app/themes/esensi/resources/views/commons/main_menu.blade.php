<style>
    .width-full {
        width: max-content;
    }
</style>
<nav class="bg-primary-100 text-white hidden lg:block" role="navigation">
    <ul>
        <li class="inline-block">
            <a href="{{ site_url('/') }}" class="inline-block py-3 px-4 hover:bg-primary-200"><i class="fa fa-home"></i></a>
        </li>
        @if (menu_tema())
            @foreach (menu_tema() as $menu)
                @php $has_dropdown = count($menu['childrens'] ?? []) > 0 @endphp
                <li class="inline-block relative" @if ($has_dropdown) x-data="{dropdown: false}" @endif>

                    @php $menu_link = $has_dropdown ? '#!' : $menu['link_url'] @endphp

                    <a href="{{ $menu_link }}" class="p-3 inline-block hover:bg-primary-200" @mouseover="dropdown = true" @mouseleave="dropdown = false" @click="dropdown = !dropdown" @if ($has_dropdown) aria-expanded="false"
        aria-haspopup="true" @endif>
                        {{ $menu['nama'] }}

                        @if ($has_dropdown)
                            <i class="fas fa-chevron-down text-xs ml-1 inline-block transition duration-300" :class="{ 'transform rotate-180': dropdown }"></i>
                        @endif
                    </a>

                    @if ($has_dropdown)
                        <ul class="absolute top-full width-full bg-white text-gray-700 shadow-lg invisible transform transition duration-200 origin-top" :class="{ 'opacity-0 invisible z-[-10] scale-y-50': !dropdown, 'opacity-100 visible z-[9999] scale-y-100': dropdown }" x-transition
                            @mouseover="dropdown = true" @mouseleave="dropdown = false"
                        >

                            @foreach ($menu['childrens'] as $childrens)
                                @if ($childrens['childrens'])
                                    <li class="inline-block relative"><a href="{{ $childrens['link_url'] }}" class="block py-3 pl-5 pr-4 hover:bg-primary-200 hover:text-white">{{ $childrens['nama'] }}
                                            @if ($has_dropdown)
                                                <i class="fas fa-chevron-left text-xs ml-1 inline-block transition duration-300" :class="{ 'transform rotate-180': dropdown }"></i>
                                            @endif
                                        </a></li>

                                    @foreach ($childrens['childrens'] as $bmenu)
                                        @php $bhas_dropdown = count($bmenu['childrens'] ?? []) > 0 @endphp
                                        <li class="inline-block relative" @if ($bhas_dropdown) x-data="{dropdown: false}" @endif>

                                            @php $bmenu_link = $bhas_dropdown ? '#!' : $bmenu['link_url'] @endphp

                                            <a href="{{ $bmenu_link }}" class="p-3 inline-block hover:bg-primary-200" @mouseover="dropdown = true" @mouseleave="dropdown = false" @click="dropdown = !dropdown"
                                                @if ($bhas_dropdown) aria-expanded="false"
            aria-haspopup="true" @endif
                                            >
                                                {{ $bmenu['nama'] }}

                                                @if ($bhas_dropdown)
                                                    <i class="fas fa-chevron-down text-xs ml-1 inline-block transition duration-300" :class="{ 'transform rotate-180': dropdown }"></i>
                                                @endif
                                            </a>

                                            @if ($bhas_dropdown)
                                                <ul class="absolute top-full width-full bg-white text-gray-700 shadow-lg invisible transform transition duration-200 origin-top" :class="{ 'opacity-0 invisible z-[-10] scale-y-50': !dropdown, 'opacity-100 visible z-[9999] scale-y-100': dropdown }"
                                                    x-transition @mouseover="dropdown = true" @mouseleave="dropdown = false"
                                                >

                                                    @foreach ($bmenu['childrens'] as $bchildrens)
                                                        <li><a href="{{ $bchildrens['link_url'] }}" class="block py-3 pl-5 pr-4 hover:bg-primary-200 hover:text-white">{{ $bchildrens['nama'] }}</a></li>
                                                    @endforeach

                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                @else
                                    <li><a href="{{ $childrens['link_url'] }}" class="block py-3 pl-5 pr-4 hover:bg-primary-200 hover:text-white">{{ $childrens['nama'] }}</a></li>
                                @endif
                            @endforeach

                        </ul>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
</nav>
