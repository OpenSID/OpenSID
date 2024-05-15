<div class="box box-widget">
    <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
            <li {{ jecho($selectedNav, 'induk', 'class="active"') }}><a href="{{ ci_route('bumindes_penduduk_induk') }}">Buku Induk Penduduk</a></li>
            <li {{ jecho($selectedNav, 'mutasi', 'class="active"') }}><a href="{{ ci_route('bumindes_penduduk_mutasi') }}">Buku Mutasi Penduduk {{ ucwords(setting('sebutan_desa')) }}</a></li>
            <li {{ jecho($selectedNav, 'rekapitulasi', 'class="active"') }}><a href="{{ ci_route('bumindes_penduduk_rekapitulasi') }}">Buku Rekapitulasi Jumlah Penduduk</a></li>
            <li {{ jecho($selectedNav, 'sementara', 'class="active"') }}><a href="{{ ci_route('bumindes_penduduk_sementara') }}">Buku Penduduk Sementara</a></li>
            <li {{ jecho($selectedNav, 'ktpkk', 'class="active"') }}><a href="{{ ci_route('bumindes_penduduk_ktpkk') }}">Buku KTP dan KK</a></li>
        </ul>
    </div>
</div>

<div class="box box-widget">
    <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
            <li {{ jecho($selectedNav, 'sinkronasi', 'class="active"') }}><a href="{{ ci_route('laporan_penduduk') }}">Sinkronasi Laporan Penduduk</a></li>
        </ul>
    </div>
</div>
