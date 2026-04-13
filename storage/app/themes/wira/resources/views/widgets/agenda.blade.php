<div class="box box-primary box-solid items-center">
    <div class="bg-green-600 flex items-center justify-center py-3 px-6 mb-1">
        <h3 class="text-md font-semibold text-white text-center">
            {{ strtoupper($judul_widget) }}
        </h3>
    </div>
    <div class="h-1 bg-green-500 mb-2"></div>

    @if (count(array_merge($hari_ini ?? [], $yad ?? [], $lama ?? [])) > 0)
        <!-- Tab Navigation -->
        <div class="flex space-x-1 mb-6 bg-gray-100 rounded-xl p-1">
            @if (count($hari_ini ?? []) > 0)
                <button class="tab-btn flex-1 py-2 px-4 text-sm font-medium rounded-lg transition-all duration-200 text-primary-700 bg-white shadow-sm active" 
                        data-tab="today">
                    Hari Ini
                    <span class="ml-1 bg-primary-100 text-primary-700 text-xs px-2 py-0.5 rounded-full">{{ count($hari_ini) }}</span>
                </button>
            @endif

            @if (count($yad ?? []) > 0)
                <button class="tab-btn flex-1 py-2 px-4 text-sm font-medium rounded-lg transition-all duration-200 text-gray-600 hover:text-primary-700 {{ count($hari_ini ?? []) == 0 ? 'text-primary-700 bg-white shadow-sm active' : '' }}" 
                        data-tab="upcoming">
                    Mendatang
                    <span class="ml-1 bg-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ count($yad) }}</span>
                </button>
            @endif

            @if (count($lama ?? []) > 0)
                <button class="tab-btn flex-1 py-2 px-4 text-sm font-medium rounded-lg transition-all duration-200 text-gray-600 hover:text-primary-700 {{ count(array_merge($hari_ini ?? [], $yad ?? [])) == 0 ? 'text-primary-700 bg-white shadow-sm active' : '' }}" 
                        data-tab="past">
                    Lama
                    <span class="ml-1 bg-gray-200 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ count($lama) }}</span>
                </button>
            @endif
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Hari Ini Tab -->
            @if (count($hari_ini ?? []) > 0)
                <div id="today" class="tab-pane space-y-4 {{ count($hari_ini) > 0 ? 'active' : '' }}">
                    @foreach ($hari_ini as $agenda)
                        <div class="border border-gray-200 rounded-xl p-4 hover:border-primary-300 transition-all duration-200 hover:shadow-md">
                            <h4 class="font-semibold text-gray-900 mb-3">
                                <a href="{{ site_url('artikel/' . buat_slug($agenda)) }}" class="hover:text-primary-700 transition-colors">
                                    {{ $agenda['judul'] }}
                                </a>
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="clock" class="w-4 h-4 text-primary-700"></i>
                                    <span class="text-gray-600">{{ tgl_indo2($agenda['tgl_agenda']) }}</span>
                                </div>
                                @if (!empty($agenda['lokasi_kegiatan']))
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-primary-700"></i>
                                        <span class="text-gray-600">{{ $agenda['lokasi_kegiatan'] }}</span>
                                    </div>
                                @endif
                                @if (!empty($agenda['koordinator_kegiatan']))
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="user" class="w-4 h-4 text-primary-700"></i>
                                        <span class="text-gray-600">{{ $agenda['koordinator_kegiatan'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Yang Akan Datang Tab -->
            @if (count($yad ?? []) > 0)
                <div id="upcoming" class="tab-pane space-y-4 {{ count($hari_ini ?? []) == 0 ? 'active' : '' }}" style="{{ count($hari_ini ?? []) == 0 ? '' : 'display: none;' }}">
                    @foreach ($yad as $agenda)
                        <div class="border border-gray-200 rounded-xl p-4 hover:border-primary-300 transition-all duration-200 hover:shadow-md">
                            <h4 class="font-semibold text-gray-900 mb-3">
                                <a href="{{ site_url('artikel/' . buat_slug($agenda)) }}" class="hover:text-primary-700 transition-colors">
                                    {{ $agenda['judul'] }}
                                </a>
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="clock" class="w-4 h-4 text-primary-700"></i>
                                    <span class="text-gray-600">{{ tgl_indo2($agenda['tgl_agenda']) }}</span>
                                </div>
                                @if (!empty($agenda['lokasi_kegiatan']))
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-primary-700"></i>
                                        <span class="text-gray-600">{{ $agenda['lokasi_kegiatan'] }}</span>
                                    </div>
                                @endif
                                @if (!empty($agenda['koordinator_kegiatan']))
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="user" class="w-4 h-4 text-primary-700"></i>
                                        <span class="text-gray-600">{{ $agenda['koordinator_kegiatan'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Lama Tab -->
            @if (count($lama ?? []) > 0)
                <div id="past" class="tab-pane {{ count(array_merge($hari_ini ?? [], $yad ?? [])) == 0 ? 'active' : '' }}" style="{{ count(array_merge($hari_ini ?? [], $yad ?? [])) == 0 ? '' : 'display: none;' }}">
                    <div class="max-h-80 overflow-y-auto space-y-4 agenda-scroll">
                        @foreach ($lama as $agenda)
                            <div class="border border-gray-200 rounded-xl p-4 hover:border-primary-300 transition-all duration-200 hover:shadow-md opacity-75">
                                <h4 class="font-semibold text-gray-700 mb-3">
                                    <a href="{{ site_url('artikel/' . buat_slug($agenda)) }}" class="hover:text-primary-700 transition-colors">
                                        {{ $agenda['judul'] }}
                                    </a>
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="clock" class="w-4 h-4 text-gray-500"></i>
                                        <span class="text-gray-500">{{ tgl_indo2($agenda['tgl_agenda']) }}</span>
                                    </div>
                                    @if (!empty($agenda['lokasi_kegiatan']))
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="map-pin" class="w-4 h-4 text-gray-500"></i>
                                            <span class="text-gray-500">{{ $agenda['lokasi_kegiatan'] }}</span>
                                        </div>
                                    @endif
                                    @if (!empty($agenda['koordinator_kegiatan']))
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                                            <span class="text-gray-500">{{ $agenda['koordinator_kegiatan'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="calendar-x" class="w-8 h-8 text-gray-400"></i>
            </div>
            <h4 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Agenda</h4>
            <p class="text-gray-600">Tidak ada agenda kegiatan yang tersedia saat ini.</p>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'text-primary-700', 'bg-white', 'shadow-sm');
                btn.classList.add('text-gray-600');
                const badge = btn.querySelector('span');
                if (badge) {
                    badge.classList.remove('bg-primary-100', 'text-primary-700');
                    badge.classList.add('bg-gray-200', 'text-gray-600');
                }
            });
            
            tabPanes.forEach(pane => {
                pane.classList.remove('active');
                pane.style.display = 'none';
            });
            
            // Add active class to clicked button
            this.classList.add('active', 'text-primary-700', 'bg-white', 'shadow-sm');
            this.classList.remove('text-gray-600');
            const activeBadge = this.querySelector('span');
            if (activeBadge) {
                activeBadge.classList.add('bg-primary-100', 'text-primary-700');
                activeBadge.classList.remove('bg-gray-200', 'text-gray-600');
            }
            
            // Show target pane
            const targetPane = document.getElementById(targetTab);
            if (targetPane) {
                targetPane.classList.add('active');
                targetPane.style.display = 'block';
            }
        });
    });
});
</script>

<style>
.agenda-scroll {
    scrollbar-width: thin;
    scrollbar-color: #e5e7eb #f9fafb;
}

.agenda-scroll::-webkit-scrollbar {
    width: 6px;
}

.agenda-scroll::-webkit-scrollbar-track {
    background: #f9fafb;
    border-radius: 3px;
}

.agenda-scroll::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 3px;
}

.agenda-scroll::-webkit-scrollbar-thumb:hover {
    background: #d1d5db;
}
</style>