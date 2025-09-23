<style>
    .navbar-menu ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .navbar-menu li {
        position: relative;
    }

    /* Default sembunyi */
    .navbar-menu ul ul {
        display: none;
    }

    /* Hover tampilkan anak */
    .navbar-menu li:hover > ul {
        display: block;
    }

    /* Dropdown level 1 */
    .navbar-menu > ul > li > ul {
        top: 100%;
        left: 0;
    }

    /* Dropdown level 2 & seterusnya */
    .navbar-menu ul ul {
        top: 0;
        left: 100%;
        min-width: 180px;
    }

    /* Pastikan dropdown selalu di atas */
    .navbar-menu ul ul {
        z-index: 9999;
    }
</style>

<nav class="navbar-menu bg-primary-100 text-white hidden lg:block" role="navigation">
    <ul>
        <!-- Home -->
        <li class="inline-block">
            <a href="{{ site_url('/') }}" class="inline-block py-3 px-4 hover:bg-primary-200">
                <i class="fa fa-home"></i>
            </a>
        </li>

        <!-- Menu Dinamis -->
        @if (menu_tema())
            @foreach (menu_tema() as $menu)
                @php $has_dropdown = count($menu['childrens'] ?? []) > 0 @endphp
                <li class="inline-block relative group">
                    <a href="{{ $has_dropdown ? '#!' : $menu['link_url'] }}"
                       class="p-3 inline-block hover:bg-primary-200">
                        {!! $menu['nama'] !!}
                        @if ($has_dropdown)
                            <i class="fas fa-chevron-down text-xs ml-1"></i>
                        @endif
                    </a>

                    @if ($has_dropdown)
                        <!-- LEVEL 1 dropdown -->
                        <ul class="absolute bg-primary-100 text-white shadow-lg min-w-[180px] z-50">
                            @foreach ($menu['childrens'] as $child)
                                @php $child_has_dropdown = count($child['childrens'] ?? []) > 0 @endphp
                                <li class="relative group">
                                    <a href="{{ $child_has_dropdown ? '#!' : $child['link_url'] }}"
                                       class="flex items-center justify-between py-3 pl-5 pr-5 hover:bg-primary-200 hover:text-white">
                                        <span class="flex-1">{!! $child['nama'] !!}</span>
                                        @if ($child_has_dropdown)
                                            <i class="fas fa-chevron-right fa-xs ml-2 mr-5 shrink-0"></i>
                                        @endif
                                    </a>

                                    @if ($child_has_dropdown)
                                        <!-- LEVEL 2 dropdown -->
                                        <ul class="absolute top-0 left-full bg-primary-100 text-white shadow-lg min-w-[180px] z-50">
                                            @foreach ($child['childrens'] as $grandchild)
                                                @php $grandchild_has_dropdown = count($grandchild['childrens'] ?? []) > 0 @endphp
                                                <li class="relative group">
                                                    <a href="{{ $grandchild_has_dropdown ? '#!' : $grandchild['link_url'] }}"
                                                       class="flex items-center justify-between py-3 pl-5 pr-5 hover:bg-primary-200 hover:text-white">
                                                        <span class="flex-1">{!! $grandchild['nama'] !!}</span>
                                                        @if ($grandchild_has_dropdown)
                                                            <i class="fas fa-chevron-right fa-xs ml-2 mr-5 shrink-0"></i>
                                                        @endif
                                                    </a>

                                                    @if ($grandchild_has_dropdown)
                                                        <!-- LEVEL 3 dropdown -->
                                                        <ul class="absolute top-0 left-full bg-primary-100 text-white shadow-lg min-w-[180px] z-50">
                                                            @foreach ($grandchild['childrens'] as $greatgrandchild)
                                                                <li>
                                                                    <a href="{{ $greatgrandchild['link_url'] }}"
                                                                       class="block py-3 pl-5 pr-5 hover:bg-primary-200 hover:text-white">
                                                                        {!! $greatgrandchild['nama'] !!}
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
