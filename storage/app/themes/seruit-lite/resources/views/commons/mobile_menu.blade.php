<nav class="bg-gray-700 text-white lg:hidden" x-data="{ menuOpen: false }" role="navigation">
    <button @click="menuOpen = !menuOpen" class="w-full text-center p-3 focus:outline-none">
        <i class="fas" :class="{ 'fa-bars': !menuOpen, 'fa-times': menuOpen }"></i>
        <span class="ml-2">Menu</span>
    </button>
    <div x-show="menuOpen" x-transition class="border-t border-gray-600">
        <ul class="divide-y divide-gray-600">
            @if (menu_tema())
                @foreach (menu_tema() as $menu)
                    @php $has_dropdown = count($menu['childrens'] ?? []) > 0 @endphp
                    <li @if($has_dropdown) x-data="{ subMenuOpen: false }" @endif>
                        <a href="{{ $has_dropdown ? '#' : $menu['link_url'] }}" @if($has_dropdown) @click.prevent="subMenuOpen = !subMenuOpen" @endif
                           class="flex justify-between items-center p-3 w-full hover:bg-gray-600">
                            <span>{{ $menu['nama'] }}</span>
                            @if ($has_dropdown)
                                <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': subMenuOpen }"></i>
                            @endif
                        </a>
                        @if ($has_dropdown)
                            <ul x-show="subMenuOpen" class="bg-gray-800" x-transition>
                                @foreach ($menu['childrens'] as $children)
                                    <li><a href="{{ $children['link_url'] }}" class="block py-2 px-6 hover:bg-gray-600">{{ $children['nama'] }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</nav>