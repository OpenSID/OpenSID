@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="single_bottom_rightbar">
    <h2><i class="fa fa-tags"></i>&ensp;
        {{ $judul_widget }}
    </h2>
    <ul id="ul-menu" class="sidebar-latest">
        @foreach ($menu_kiri as $data)
            <li>
                <a href="{{ ci_route('artikel/kategori/' . $data['slug']) }}">
                    {{ $data['kategori'] }}
                    @if (count($data['submenu'] ?? []) > 0)
                        <span class="caret"></span>
                    @endif
                </a>
                @if (count($data['submenu'] ?? []) > 0)
                    <ul class="nav submenu">
                        @foreach ($data['submenu'] as $submenu)
                            <li><a href="{{ ci_route('artikel/kategori/' . $submenu['slug']) }}">
                                    {{ $submenu['kategori'] }}
                                </a></li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</div>
