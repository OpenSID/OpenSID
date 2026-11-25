@php
    defined('BASEPATH') OR exit('No direct script access allowed');

    $gradient_lite = 'from-green-500 to-teal-500';

    $stats = [
        ['url' => site_url('data-statistik/pendidikan-dalam-kk'), 'icon' => 'fa-user-graduate', 'title' => 'Pendidikan'],
        ['url' => site_url('data-statistik/pekerjaan'), 'icon' => 'fa-briefcase', 'title' => 'Pekerjaan'],
        ['url' => site_url('data-statistik/agama'), 'icon' => 'fa-mosque', 'title' => 'Agama'],
        ['url' => site_url('data-statistik/jenis-kelamin'), 'icon' => 'fa-venus-mars', 'title' => 'Jenis Kelamin'],
        ['url' => site_url('data-statistik/rentang-umur'), 'icon' => 'fa-chart-pie', 'title' => 'Rentang Umur'],
        ['url' => site_url('data-wilayah'), 'icon' => 'fa-sitemap', 'title' => 'Wilayah']
    ];
@endphp

<section class="my-12">
    <div class="flex items-center mb-8">
        <h2 class="px-6 py-2 text-sm font-bold text-white uppercase tracking-wider shadow-md rounded-none"
            style="clip-path: polygon(0 0, 100% 0, 92% 100%, 0% 100%);"
            :class="darkMode ? 'bg-gray-700' : 'bg-gradient-to-r {{ $gradient_lite }}'">
            Statistik Penduduk
        </h2>
        <div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
        <div class="grid grid-cols-1 gap-4">
            @foreach (array_slice($stats, 0, 3) as $item)
                <a href="{{ $item['url'] }}" class="block p-4 rounded-none shadow-md border border-black/5 transition-colors"
                   :class="darkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-gradient-to-br {{ $gradient_lite }} text-white'">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-black/10 rounded-none text-xl">
                            <i class="fas {{ $item['icon'] }}"></i>
                        </div>
                        <h4 class="font-bold text-sm uppercase tracking-tight">{{ $item['title'] }}</h4>
                    </div>
                </a>
            @endforeach
        </div>

        <div x-data="liteClock()" x-init="init()" class="p-6 shadow-md rounded-none border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex flex-col items-center justify-center text-center">
            <div class="w-full py-2 mb-4 border-b border-gray-100 dark:border-gray-700">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400" x-text="dateString"></p>
            </div>
            <div class="flex items-baseline gap-1 mb-4">
                <span class="text-5xl font-mono font-extrabold text-teal-600 dark:text-teal-400" x-text="timeString"></span>
            </div>
            <div class="w-full bg-gray-50 dark:bg-gray-900/50 p-3 rounded-none flex items-center justify-center space-x-3">
                <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-teal-500 text-white rounded-full">
                    <i class="fas fa-users text-xs"></i>
                </div>
                <div class="text-left">
                    <p class="text-[10px] uppercase font-bold text-gray-400 leading-none">Kunjungan Hari Ini</p>
                    <p class="text-lg font-bold text-gray-700 dark:text-white leading-tight">{{ e($statistik_pengunjung['hari_ini']) }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @foreach (array_slice($stats, 3, 3) as $item)
                <a href="{{ $item['url'] }}" class="block p-4 rounded-none shadow-md border border-black/5 transition-colors"
                   :class="darkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-gradient-to-br {{ $gradient_lite }} text-white'">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-black/10 rounded-none text-xl">
                            <i class="fas {{ $item['icon'] }}"></i>
                        </div>
                        <h4 class="font-bold text-sm uppercase tracking-tight">{{ $item['title'] }}</h4>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('liteClock', () => ({
            timeString: '00:00',
            dateString: '',
            init() {
                this.update();
                setInterval(() => this.update(), 1000);
            },
            update() {
                const now = new Date();
                this.timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false }).replace('.', ':');
                this.dateString = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long' });
            }
        }));
    });
</script>
@endpush