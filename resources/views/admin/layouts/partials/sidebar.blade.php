<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ gambar_desa($desa['logo']) }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <strong>{{ ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']) }}</strong>
                <br>

                @php
                    $sebKec  = setting('sebutan_kecamatan');
                    $namKec  = $desa['nama_kecamatan'];
                    $sebKab  = setting('sebutan_kabupaten');
                    $namKab  = $desa['nama_kabupaten'];
                    $ringkas = strlen($namKec) > 12 || strlen($namKab) > 12;
                @endphp

                @if (! $ringkas)
                    {{ ucwords($sebKec . ' ' . $namKec) }}
                    <br>
                    {{ ucwords($sebKab . ' ' . $namKab) }}
                @else
                    {{ ucwords(Str::limit($sebKec, 3, '.') . ' ' . $namKec) }}
                    <br>
                    {{ ucwords(Str::limit($sebKab, 3, '.') . ' ' . $namKab) }}
                @endif
            </div>
        </div>

        <div class="sidebar-form">
            <div class="input-group mb-0">
                <input type="text" id="cari-menu" class="form-control" placeholder="Pencarian...">
                <span class="input-group-btn">
                    <button type="button" name="search" id="search-btn" class="btn btn-sm"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU UTAMA</li>

            @foreach (admin_menu() as $mod)
                @if (! empty($mod['childrens']) && is_array($mod['childrens']))
                    <li @class(['treeview', 'active' => $modul_ini == $mod['slug']])>
                        <a href="{{ url($mod['url']) }}">
                            <i @class(['fa', $mod['ikon'], 'text-aqua' => $modul_ini == $mod['slug']])></i>
                            <span>{{ $mod['modul'] }}</span>
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        </a>
                        <ul @class(['treeview-menu', 'active' => $modul_ini == $mod['slug']])>
                            @foreach ($mod['childrens'] as $submod)
                                <li @class(['active' => $sub_modul_ini == $submod['slug']])>
                                    <a href="{{ url($submod['url']) }}">
                                        <i @class(['fa', $submod['ikon'] ?? 'fa-circle-o', 'text-red' => $sub_modul_ini == $submod['slug']])></i>
                                        {{ $submod['modul'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @elseif (! empty($mod['url']))
                    <li @class(['active' => $modul_ini == $mod['slug']])>
                        <a href="{{ url($mod['url']) }}">
                            <i @class(['fa', $mod['ikon'], 'text-aqua' => $modul_ini == $mod['slug']])></i>
                            <span>{{ $mod['modul'] }}</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </section>
</aside>
