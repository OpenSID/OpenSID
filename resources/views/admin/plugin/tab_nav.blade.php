<ul class="nav nav-tabs">
    <li {!! $act_tab == 1 ? 'class="active"' : '' !!}><a href="{{ ci_route('plugin') }}">Paket Tersedia</a></li>
    @if (can('u'))
        <li {!! $act_tab == 2 ? 'class="active"' : '' !!}><a href="{{ ci_route('plugin.installed') }}">Paket Terpasang</a></li>
        @if (! config_item('demo_mode'))
            <li {!! $act_tab == 3 ? 'class="active"' : '' !!}><a href="{{ ci_route('plugin.pendaftaran') }}">Form Pendaftaran</a></li>
            <li {!! $act_tab == 4 ? 'class="active"' : '' !!}><a href="{{ ci_route('plugin.pemesanan') }}">Riwayat Pemesanan</a></li>
        @endif
    @endif
    @if ((defined('ENVIRONMENT') ? constant('ENVIRONMENT') : null) === 'development' && app()->bound('bursa.lokal'))
        <li {!! $act_tab == 5 ? 'class="active"' : '' !!}>
            <a href="{{ site_url('dev-modul') }}">
                <i class="fa fa-flask"></i> Sumber
                @if (app('bursa.lokal')::aktif())
                    <span class="label label-warning">Lokal</span>
                @else
                    <span class="label label-default">Layanan</span>
                @endif
            </a>
        </li>
    @endif
</ul>
