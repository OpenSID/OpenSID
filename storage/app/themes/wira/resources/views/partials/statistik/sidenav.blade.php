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

<div class="sticky top-5 w-full shadow-lg">
    <div class="space-y-2">
        @foreach ($s_links as $index => $statistik)
            @php $is_active = in_array($slug_aktif, array_column($statistik['submenu'], 'slug')) @endphp
            <div class="bg-white border border-gray-200 rounded-xs overflow-hidden">
                <!-- Accordion Header -->
                <button 
                    type="button"
                    class="w-full flex items-center justify-between py-4 px-5 text-left bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset transition-colors duration-200"
                    onclick="toggleAccordion('{{ $statistik['target'] }}', this)"
                    aria-expanded="{{ $is_active ? 'true' : 'false' }}"
                    aria-controls="{{ $statistik['target'] }}"
                >
                    <div class="flex items-center">
                        <i class="fas {{ $statistik['icon'] }} mr-3 text-gray-600"></i>
                        <span class="text-base font-medium text-gray-900">{{ $statistik['label'] }}</span>
                    </div>
                    <svg 
                        class="w-5 h-5 text-gray-500 transform transition-transform duration-200 {{ $is_active ? 'rotate-180' : '' }}"
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Accordion Content -->
                <div 
                    id="{{ $statistik['target'] }}" 
                    class="accordion-content {{ $is_active ? 'max-h-screen' : 'max-h-0' }} overflow-hidden transition-all duration-300 ease-in-out"
                >
                    <div class="border-t border-gray-200">
                        <ul class="divide-y divide-gray-100">
                            @foreach ($statistik['submenu'] as $submenu)
                                @php
                                    $stat_slug = in_array($statistik['target'], ['statistikBantuan', 'statistikLainnya']) ? str_replace('first/', '', $submenu['url']) : 'statistik/' . $submenu['key'];
                                    if ($stat_slug == 'data-dpt') {
                                        $stat_slug = 'dpt';
                                    }
                                @endphp
                                @if (isset($statistik_aktif[$stat_slug]))
                                    <li>
                                        <a 
                                            href="{{ site_url($submenu['url']) }}" 
                                            class="block px-6 py-3 text-sm {{ $submenu['slug'] == $slug_aktif ? 'bg-green-50 text-green-700 border-r-4 border-green-500 font-medium' : 'text-gray-700 hover:bg-gray-50 hover:text-blue-600' }} transition-colors duration-150"
                                        >
                                            {{ $submenu['label'] }}
                                        </a>
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

<script>
function toggleAccordion(targetId, button) {
    const content = document.getElementById(targetId);
    const chevron = button.querySelector('svg');
    const isExpanded = button.getAttribute('aria-expanded') === 'true';
    
    // Close all other accordions
    document.querySelectorAll('.accordion-content').forEach(accordion => {
        if (accordion.id !== targetId) {
            accordion.classList.remove('max-h-screen');
            accordion.classList.add('max-h-0');
        }
    });
    
    // Reset all chevrons
    document.querySelectorAll('[onclick^="toggleAccordion"] svg').forEach(svg => {
        if (svg !== chevron) {
            svg.classList.remove('rotate-180');
        }
    });
    
    // Reset all aria-expanded
    document.querySelectorAll('[onclick^="toggleAccordion"]').forEach(btn => {
        if (btn !== button) {
            btn.setAttribute('aria-expanded', 'false');
        }
    });
    
    // Toggle current accordion
    if (isExpanded) {
        content.classList.remove('max-h-screen');
        content.classList.add('max-h-0');
        chevron.classList.remove('rotate-180');
        button.setAttribute('aria-expanded', 'false');
    } else {
        content.classList.remove('max-h-0');
        content.classList.add('max-h-screen');
        chevron.classList.add('rotate-180');
        button.setAttribute('aria-expanded', 'true');
    }
}

// Initialize accordions on page load
document.addEventListener('DOMContentLoaded', function() {
    // Ensure active accordions are properly expanded
    document.querySelectorAll('[aria-expanded="true"]').forEach(button => {
        const targetId = button.getAttribute('aria-controls');
        const content = document.getElementById(targetId);
        const chevron = button.querySelector('svg');
        
        if (content) {
            content.classList.remove('max-h-0');
            content.classList.add('max-h-screen');
        }
        if (chevron) {
            chevron.classList.add('rotate-180');
        }
    });
});
</script>