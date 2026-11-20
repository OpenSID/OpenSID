@php
    defined('BASEPATH') OR exit('No direct script access allowed');

    if (empty($aparatur_desa['daftar_perangkat'])) {
        return;
    }

    $active_gradient = 'from-green-500 to-teal-500';
@endphp

<div class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
    <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
        <h3 class="font-bold text-sm uppercase tracking-wider text-gray-700 dark:text-gray-200">
            <i class="fas fa-users mr-3 text-teal-500"></i>{{ $judul_widget }}
        </h3>
    </div>

    <div class="p-6" x-data="aparaturSliderLite()" x-init="start()">
        <div class="relative h-64">
            @foreach ($aparatur_desa['daftar_perangkat'] as $index => $data)
                <div 
                    x-show="currentIndex === {{ $index }}"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="absolute inset-0 flex flex-col items-center text-center"
                >
                    <div class="relative mb-4">
                        <div class="w-28 h-28 rounded-full border-4 border-teal-500/20 p-1">
                            <img src="{{ e($data['foto']) }}" alt="{{ e($data['nama']) }}" class="w-full h-full object-cover rounded-full shadow-md">
                        </div>
                        
                        @if ($data['kehadiran'] == 1)
                            <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 whitespace-nowrap">
                                @if ($data['status_kehadiran'] == 'hadir')
                                    <span class="px-2 py-0.5 bg-green-500 text-white text-[9px] font-bold uppercase rounded-sm shadow-sm border border-white/20">Hadir</span>
                                @elseif ($data['tanggal'] == date('Y-m-d'))
                                    <span class="px-2 py-0.5 bg-red-500 text-white text-[9px] font-bold uppercase rounded-sm shadow-sm border border-white/20">{{ e($data['status_kehadiran']) }}</span>
                                @else
                                    <span class="px-2 py-0.5 bg-yellow-500 text-white text-[9px] font-bold uppercase rounded-sm shadow-sm border border-white/20">Belum Rekam</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="mt-2">
                        <h4 class="font-bold text-gray-800 dark:text-gray-100 text-base leading-tight">{{ e($data['nama']) }}</h4>
                        <p class="text-xs font-bold text-teal-600 dark:text-teal-400 mt-1 uppercase tracking-wide">{{ e($data['jabatan']) }}</p>
                        @if ($data['nip'])
                            <p class="text-[10px] text-gray-400 mt-1">NIP. {{ $data['nip'] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-center items-center space-x-2 mt-4">
            @foreach ($aparatur_desa['daftar_perangkat'] as $index => $data)
                <button 
                    @click="currentIndex = {{ $index }}; resetTimer()"
                    class="h-1.5 transition-all duration-300 rounded-full"
                    :class="currentIndex === {{ $index }} ? 'w-6 bg-teal-500' : 'w-2 bg-gray-200 dark:bg-gray-700'"
                ></button>
            @endforeach
        </div>
    </div>

    <div class="p-2 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 text-center">
        <a href="{{ site_url('pemerintah-desa') }}" class="text-[10px] font-bold text-gray-400 hover:text-teal-500 uppercase tracking-widest transition-colors">
            Lihat Semua Perangkat
        </a>
    </div>
</div>

<script>
    function aparaturSliderLite() {
        return {
            currentIndex: 0,
            total: {{ count($aparatur_desa['daftar_perangkat']) }},
            timer: null,
            start() {
                this.resetTimer();
            },
            resetTimer() {
                if (this.timer) clearInterval(this.timer);
                if (this.total > 1) {
                    this.timer = setInterval(() => {
                        this.currentIndex = (this.currentIndex + 1) % this.total;
                    }, 5000);
                }
            }
        }
    }
</script>