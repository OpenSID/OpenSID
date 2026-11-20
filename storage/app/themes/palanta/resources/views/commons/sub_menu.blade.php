@push('styles')
<style>
.submenu {
    height: max-content;
    width: max-content;
}
.submenu-link{
    display: inline-flex;
}
.subsub-link>a{
    padding: 8px;
}
.subsub-link {
    display: none;
}
</style>
@endpush
@if(count($data['childrens'] ?? []) > 0)
    <li class="with-submenu">
        <a href="{{ $data['link_url'] }}">{{ $data['nama'] }}
            @if(count($data['childrens'] ?? []) > 0)
                <i class="fa fa-caret-down"></i>
            @endif
        </a>
        <ul class="submenu">
            @foreach($data['childrens'] as $submenu)
                @if(count($submenu['childrens'] ?? []) > 0)
                    <li class="submenu-link">
                        <a href="{{ $submenu['link_url'] }}">{{ $submenu['nama'] }}
                        @if(count($submenu['childrens'] ?? []) > 0)
                            <i class="fa fa-caret-down"></i>
                        @endif
                        </a>
                        <ul class="">
                            @foreach($submenu['childrens'] as $submenub)
                                @if(count($submenub['childrens'] ?? []) > 0)
                                    <li class="with-submenu subsub-link">
                                        <a href="{{ $submenub['link_url'] }}">{{ $submenub['nama'] }}
                                        @if(count($submenub['childrens'] ?? []) > 0)
                                            <i class="fa fa-caret-down"></i>
                                        @endif
                                        </a>
                                        <ul class="">
                                            @foreach($submenub['childrens'] as $submenubb)
                                                <li class="subsub-link"><a href="{{ $submenubb['link_url'] }}">{{ $submenubb['nama'] }}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li class="subsub-link"><a href="{{ $submenub['link_url'] }}">{{ $submenub['nama'] }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li class="submenu-link"><a href="{{ $submenu['link_url'] }}">{{ $submenu['nama'] }}</a></li>
                @endif
            @endforeach
        </ul>
    </li>
@else
    <li><a href="{{ $data['link_url'] }}">{{ $data['nama'] }}</a></li>
@endif