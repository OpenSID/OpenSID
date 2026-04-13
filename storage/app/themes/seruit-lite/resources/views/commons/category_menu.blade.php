@php
    $alt_slug = 'artikel';
@endphp

<div class="flex justify-between items-center text-xs border-b border-white/10">
    <ul class="flex items-center divide-x divide-white/10">
        @foreach (array_slice($menu_kiri, 0, 4) as $menu)
            <li class="hidden lg:inline-block">
                <a href="{{ site_url("{$alt_slug}/kategori/{$menu['slug']}") }}" class="px-3 py-2 inline-block hover:bg-white/10 transition-colors">{{ $menu['kategori'] }}</a>
            </li>
        @endforeach
    </ul>

    <div class="flex items-center divide-x divide-white/10">
        @if (setting('layanan_mandiri') == 1)
            <a href="{{ site_url('layanan-mandiri') }}" class="px-3 py-2 inline-block hover:bg-white/10 transition-colors">Layanan Mandiri</a>
        @endif
        <a href="{{ site_url('siteman') }}" class="px-3 py-2 inline-block hover:bg-white/10 transition-colors">Login Admin</a>
    </div>
</div>