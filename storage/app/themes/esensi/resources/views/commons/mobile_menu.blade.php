<nav class="bg-primary-100 text-white lg:hidden block" x-data="{ menuOpen: false }" role="navigation">
    <!-- Tombol toggle -->
    <button type="button" class="w-full block text-center uppercase p-3" @click="menuOpen = !menuOpen">
        <i class="fas mr-1" :class="{ 'fa-bars': !menuOpen, 'fa-times': menuOpen }"></i>
        Menu
    </button>

    <!-- Menu utama -->
    <ul x-show="menuOpen" x-transition class="divide-y divide-primary-200">
        @php $menu_atas = menu_tema() @endphp
        @if ($menu_atas)
            @foreach ($menu_atas as $menu)
                @php $has_dropdown = count($menu['childrens'] ?? []) > 0 @endphp
                <li class="block relative" @if ($has_dropdown) x-data="{dropdownMain: false}" @endif>
                    @php $menu_link = $has_dropdown ? '#!' : $menu['link_url'] @endphp

                    <!-- LEVEL 1 -->
                    <a href="{{ $menu_link }}"
                        class="flex w-full items-center justify-between p-3 hover:bg-secondary-100"
                        @click="dropdownMain = !dropdownMain">
                        <span class="flex-1">{!! $menu['nama'] !!}</span>
                        @if ($has_dropdown)
                            <i class="fas fa-chevron-down fa-xs ml-2 shrink-0 transition-transform duration-300"
                                :class="{ 'rotate-180': dropdownMain }"></i>
                        @endif
                    </a>

                    @if ($has_dropdown)
                        <!-- LEVEL 2 -->
                        <ul class="divide-y divide-primary-200"
                            :class="{
                                'opacity-0 invisible z-[-10] scale-y-75 h-0': !dropdownMain,
                                'opacity-100 visible z-30 scale-y-100 h-auto': dropdownMain
                            }"
                            x-transition.opacity>

                            @foreach ($menu['childrens'] as $childrens)
                                @php $has_dropdown2 = count($childrens['childrens'] ?? []) > 0 @endphp
                                <li @if ($has_dropdown2) x-data="{dropdownSub: false}" @endif>
                                    @php $menu_link2 = $has_dropdown2 ? '#!' : $childrens['link_url'] @endphp

                                    <a href="{{ $menu_link2 }}"
                                        class="flex w-full items-center justify-between py-3 pr-4 pl-6 hover:bg-primary-200 hover:text-white"
                                        @click="dropdownSub = !dropdownSub">
                                        {!! $childrens['nama'] !!}
                                        @if ($has_dropdown2)
                                            <i class="fas fa-chevron-right fa-xs ml-auto shrink-0 transition-transform duration-300"
                                                :class="{ 'rotate-90': dropdownSub }"></i>
                                        @endif
                                    </a>

                                    @if ($has_dropdown2)
                                        <!-- LEVEL 3 -->
                                        <ul class="divide-y divide-primary-200"
                                            :class="{
                                                'opacity-0 invisible z-[-10] scale-y-75 h-0': !dropdownSub,
                                                'opacity-100 visible z-30 scale-y-100 h-auto': dropdownSub
                                            }"
                                            x-transition.opacity>
                                            @foreach ($childrens['childrens'] as $children)
                                                @php $has_dropdown3 = count($children['childrens'] ?? []) > 0 @endphp
                                                <li
                                                    @if ($has_dropdown3) x-data="{dropdownSub2: false}" @endif>
                                                    <a href="{{ $menu_link3 }}"
                                                        class="flex w-full items-center justify-between py-3 pr-4 pl-6 hover:bg-primary-200 hover:text-white"
                                                        @click="dropdownSub2 = !dropdownSub2">
                                                        {!! $children['nama'] !!}
                                                        @if ($has_dropdown3)
                                                            <i class="fas fa-chevron-right fa-xs ml-auto shrink-0 transition-transform duration-300"
                                                                :class="{ 'rotate-90': dropdownSub2 }"></i>
                                                        @endif
                                                    </a>


                                                    @if ($has_dropdown3)
                                                        <!-- LEVEL 4 -->
                                                        <ul class="divide-y divide-primary-200"
                                                            :class="{
                                                                'opacity-0 invisible z-[-10] scale-y-75 h-0': !
                                                                    dropdownSub2,
                                                                'opacity-100 visible z-30 scale-y-100 h-auto': dropdownSub2
                                                            }"
                                                            x-transition.opacity>
                                                            @foreach ($children['childrens'] as $ggchild)
                                                                <li>
                                                                    <a href="{{ $menu_link4 }}"
                                                                        class="block py-3 pr-4 pl-6 hover:bg-primary-200 hover:text-white">
                                                                        {!! $ggchild['nama'] !!}
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

                        </ul>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
</nav>
