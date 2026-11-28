@php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $gradient_lite = 'from-green-500 to-teal-500';
    $tabs = [
        'terkini' => ['label' => 'Terbaru', 'data' => $arsip_terkini],
        'populer' => ['label' => 'Populer', 'data' => $arsip_populer]
    ];
@endphp

<div class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
    <div class="p-4 bg-gradient-to-r {{ $gradient_lite }} text-white">
        <h3 class="font-bold text-sm uppercase tracking-wider flex items-center">
            <i class="fas fa-archive mr-3"></i>{{ $judul_widget }}
        </h3>
    </div>

    <div x-data="{ activeTab: 'terkini' }">
        <nav class="flex border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            @foreach ($tabs as $key => $tab)
                <button @click="activeTab = '{{ $key }}'"
                        class="flex-1 py-3 px-2 text-[10px] uppercase font-bold transition-all duration-200 border-b-2"
                        :class="activeTab === '{{ $key }}' ? 'border-teal-500 text-teal-600 dark:text-teal-400 bg-white dark:bg-gray-800' : 'border-transparent text-gray-400 hover:text-gray-600'">
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </nav>

        <div class="p-4">
            @foreach ($tabs as $key => $tab)
                <div x-show="activeTab === '{{ $key }}'" x-transition class="space-y-4">
                    @forelse ($tab['data'] as $arsip)
                        <div class="flex items-start gap-3 group">
                            <div class="flex-shrink-0 w-12 h-12 border border-gray-100 dark:border-gray-700 overflow-hidden">
                                <img src="{{ ($arsip['gambar'] && is_file(LOKASI_FOTO_ARTIKEL . 'kecil_' . $arsip['gambar'])) ? AmbilFotoArtikel($arsip['gambar'], 'kecil') : theme_asset('images/placeholder.png') }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ site_url('artikel/' . buat_slug($arsip)) }}" class="block text-xs font-bold text-gray-700 dark:text-gray-200 hover:text-teal-500 leading-tight line-clamp-2">
                                    {{ e($arsip['judul']) }}
                                </a>
                                <span class="text-[10px] text-gray-400 mt-1 block">{{ tgl_indo($arsip['tgl_upload']) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-xs text-gray-400 py-4 italic">Tidak ada artikel.</p>
                    @endforelse
                </div>
            @endforeach
        </div>
    </div>
</div>