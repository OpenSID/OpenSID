@php $has_dropdown = count($menu['childrens'] ?? []) > 0 @endphp
<li @if($has_dropdown) x-data="{ subMenuOpen: false }" @endif>
    <a href="{{ $has_dropdown ? '#!' : $menu['link_url'] }}" @if($has_dropdown) @click.prevent="subMenuOpen = !subMenuOpen" @endif
       class="flex justify-between items-center p-3 w-full text-base font-semibold text-white hover:bg-white/10 rounded-md">
        <span>{{ $menu['nama'] }}</span>
        @if ($has_dropdown)
            <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': subMenuOpen }"></i>
        @endif
    </a>
    @if ($has_dropdown)
        <ul x-show="subMenuOpen" class="pl-4 mt-2 space-y-1" x-transition>
            @foreach ($menu['childrens'] as $children)
                <li><a href="{{ $children['link_url'] }}" class="block py-2 px-3 text-white/80 hover:bg-white/10 rounded-md">{{ $children['nama'] }}</a></li>
            @endforeach
        </ul>
    @endif
</li>