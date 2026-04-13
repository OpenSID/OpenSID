@foreach ($teks_berjalan as $teks)
    <span class="teks" style="font-family: Oswald; padding-right: 50px;">
        {{ $teks['teks'] }}
        @if ($teks['tautan'])
            <a href="{{ $teks['tautan'] }}" rel="noopener noreferrer" title="Baca Selengkapnya">{{ $teks['judul_tautan'] }}</a>
        @endif
    </span>
@endforeach
