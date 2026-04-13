@php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $gradient_lite = 'from-green-500 to-teal-500';
@endphp

<div class="rounded-none shadow-lg p-5 sm:p-6 h-full flex flex-col justify-between"
     :class="darkMode ? 'bg-gray-800' : 'bg-gradient-to-br {{ $gradient_lite }}'">

    <h2 class="text-xl lg:text-2xl font-bold text-white mb-6 text-center relative z-10">Aparatur {{ ucwords(setting('sebutan_desa')) }}</h2>

    @if (isset($aparatur_desa['daftar_perangkat']) && count($aparatur_desa['daftar_perangkat']) > 0)
        
        <script type="application/json" id="aparatur-data">
            <?= json_encode(array_values($aparatur_desa['daftar_perangkat'])); ?>
        </script>
        
        <div
            x-data="aparaturSliderLite()"
            class="relative flex-grow flex flex-col items-center justify-center min-h-[280px] z-10"
            @mouseenter="stop()"
            @mouseleave="start()">
            
            <div class="relative w-40 h-56 lg:w-48 lg:h-64 flex-shrink-0 overflow-hidden shadow-2xl border-4 border-white/20">
                <template x-for="(item, index) in aparatur" :key="index">
                    <div
                        x-show="currentIndex === index"
                        x-transition:enter="transition opacity ease-out duration-500"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition opacity ease-in duration-500"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute inset-0">
                        <img :src="item.foto" :alt="item.nama" class="w-full h-full object-cover object-top">
                        <div class="absolute bottom-0 left-0 right-0 bg-black/50 backdrop-blur-sm p-2 text-center">
                            <h3 class="text-xs lg:text-sm font-bold text-white truncate" x-text="item.nama"></h3>
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="relative text-left w-64 mt-6">
                <template x-for="(item, index) in aparatur" :key="index">
                    <div
                        x-show="currentIndex === index"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="w-full bg-black/20 backdrop-blur-sm rounded-none p-3 border border-white/10">
                        
                        <div class="flex items-center gap-3 text-white">
                            <img src="{{ gambar_desa($desa['logo']) }}" alt="Logo" class="h-8 w-8 flex-shrink-0">
                            <div class="overflow-hidden">
                                <p class="font-bold text-xs truncate" x-text="item.jabatan"></p>
                                <template x-if="item.kehadiran == 1">
                                    <div class="mt-1">
                                        <template x-if="item.status_kehadiran == 'hadir'">
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] bg-green-500/40 text-white border border-green-400">Hadir</span>
                                        </template>
                                        <template x-if="item.tanggal == '<?= date('Y-m-d') ?>' && item.status_kehadiran != 'hadir'">
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] bg-red-500/40 text-white border border-red-400" x-text="item.status_kehadiran.charAt(0).toUpperCase() + item.status_kehadiran.slice(1)"></span>
                                        </template>
                                        <template x-if="item.tanggal != '<?= date('Y-m-d') ?>'">
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] bg-yellow-500/40 text-white border border-yellow-400">Belum Rekam</span>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex space-x-2 mt-4">
                <template x-for="(item, index) in aparatur" :key="index">
                    <button @click="currentIndex = index" 
                            class="w-2 h-2 rounded-full transition-all duration-300"
                            :class="currentIndex === index ? 'bg-white w-4' : 'bg-white/40'"></button>
                </template>
            </div>
        </div>
    @else
        <div class="flex-grow flex items-center justify-center text-center italic text-white/80">
            Data aparatur belum tersedia.
        </div>
    @endif
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('aparaturSliderLite', () => ({
        aparatur: JSON.parse(document.getElementById('aparatur-data')?.textContent || '[]'),
        currentIndex: 0,
        timer: null,
        init() { this.start(); },
        start() {
            if (this.aparatur.length > 1) {
                this.timer = setInterval(() => { 
                    this.currentIndex = (this.currentIndex + 1) % this.aparatur.length; 
                }, 4000);
            }
        },
        stop() { clearInterval(this.timer); }
    }));
});
</script>