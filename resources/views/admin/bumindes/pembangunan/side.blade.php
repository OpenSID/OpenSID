<div class="box box-widget widget-user-2">
    <div class="box-footer no-padding">
        <ul class="nav nav-stacked">
            <li {{ jecho($selectedNav, 'rencana', 'class="active"') }}><a href="{{ ci_route('bumindes_rencana_pembangunan') }}">Buku Rencana Kerja Pembangunan</a></li>
            <li {{ jecho($selectedNav, 'kegiatan', 'class="active"') }}><a href="{{ ci_route('bumindes_kegiatan_pembangunan') }}">Buku Kegiatan Pembangunan</a></li>
            <li {{ jecho($selectedNav, 'hasil', 'class="active"') }}><a href="{{ ci_route('bumindes_hasil_pembangunan') }}">Buku Inventaris Hasil-Hasil Pembangunan</a></li>
            <li {{ jecho($selectedNav, 'kader', 'class="active"') }}><a href="{{ ci_route('bumindes_kader') }}">Buku Kader Pemberdayaan Masyarakat</a></li>
        </ul>
    </div>
</div>
