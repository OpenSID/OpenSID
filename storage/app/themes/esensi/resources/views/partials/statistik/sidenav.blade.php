@php
    $daftar_statistik = daftar_statistik();
    $slug_aktif = str_replace('_', '-', $slug_aktif);
    $s_links = [
        [
            'target' => 'statistikPenduduk',
            'label' => 'Statistik Penduduk',
            'icon' => 'fa-chart-pie',
            'submenu' => $daftar_statistik['penduduk'],
        ],
        [
            'target' => 'statistikKeluarga',
            'label' => 'Statistik Keluarga',
            'icon' => 'fa-chart-bar',
            'submenu' => $daftar_statistik['keluarga'],
        ],
        [
            'target' => 'statistikBantuan',
            'label' => 'Statistik Bantuan',
            'icon' => 'fa-chart-line',
            'submenu' => $daftar_statistik['bantuan'],
        ],
        [
            'target' => 'statistikLainnya',
            'label' => 'Statistik Lainnya',
            'icon' => 'fa-chart-area',
            'submenu' => $daftar_statistik['lainnya'],
        ],
    ];
@endphp

<div class="sticky top-5 w-full shadow">
    <div class="accordion" id="statistikNavigation">
        @foreach ($s_links as $statistik)
            @php $is_active = in_array($slug_aktif, array_column($statistik['submenu'], 'slug')) @endphp
            <div class="accordion-item bg-white border border-gray-200 overflow-hidden">
                <h4 class="accordion-header mb-0" id="heading-{{ $statistik['label'] }}">
                    <button class="accordion-button relative flex items-center w-full py-4 px-5 text-base text-left bg-white border-0 rounded-none transition focus:outline-none text-h5" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $statistik['target'] }}"
                        aria-expanded="{{ $is_active ? 'true' : 'false' }}" aria-controls="{{ $statistik['target'] }}"
                    >
                        <i class="fas {{ $statistik['icon'] }} mr-2"></i> {{ $statistik['label'] }}
                    </button>
                </h4>
                <div id="{{ $statistik['target'] }}" class="accordion-collapse collapse {{ $is_active ? 'show' : '' }}" data-bs-parent="#statistikNavigation" aria-labelledby="heading-{{ $statistik['target'] }}">
                    <div class="accordion-body">
                        <ul class="divide-y-2">
                            @foreach ($statistik['submenu'] as $submenu)
                                @php
                                    $stat_slug = in_array($statistik['target'], ['statistikBantuan', 'statistikLainnya']) ? str_replace('first/', '', $submenu['url']) : 'statistik/' . $submenu['key'];
                                    if ($stat_slug == 'data-dpt') {
                                        $stat_slug = 'dpt';
                                    }
                                @endphp
                                @if (isset($statistik_aktif[$stat_slug]))
                                    <li id="statistik_13">
                                        <a href="{{ site_url($submenu['url']) }}" class="px-5 py-2 block {{ $submenu['slug'] == $slug_aktif ? 'bg-primary-100 text-white' : 'hover:cursor-pointer hover:text-primary-100' }}">{{ $submenu['label'] }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
