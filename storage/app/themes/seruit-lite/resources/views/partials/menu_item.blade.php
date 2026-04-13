@php $has_dropdown = count($menu['childrens'] ?? []) > 0 @endphp
<div class="relative" @if ($has_dropdown) x-data="{dropdown: false}" @endif>
    <a href="{{ $has_dropdown ? '#!' : $menu['link_url'] }}" 
       class="px-3 py-2 rounded-md text-sm font-medium hover:bg-white/10 flex items-center" 
       @if($has_dropdown) @click.prevent="dropdown = !dropdown" @mouseleave="dropdown = false" @endif>
        <span>{{ $menu['nama'] }}</span>
        @if ($has_dropdown)
            <i class="fas fa-chevron-down text-xs ml-2 transition-transform duration-300" :class="{ 'transform rotate-180': dropdown }"></i>
        @endif
    </a>
    @if ($has_dropdown)
        <div x-show="dropdown" @click.away="dropdown = false" @mouseover="dropdown = true" @mouseleave="dropdown = false"
             x-transition class="origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50" style="display:none;">
            <div class="py-1">
                @foreach ($menu['childrens'] as $children)
                    <a href="{{ $children['link_url'] }}" class="text-gray-700 dark:text-gray-200 block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">{{ $children['nama'] }}</a>
                @endforeach
            </div>
        </div>
    @endif
</div>