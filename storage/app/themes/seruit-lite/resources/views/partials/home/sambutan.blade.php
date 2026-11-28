@php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $gradient_class_sambutan = 'from-green-500 to-teal-500';

    $kepala_desa = null;
    if (isset($aparatur_desa['daftar_perangkat']) && !empty($aparatur_desa['daftar_perangkat'])) {
        $kepala_desa = $aparatur_desa['daftar_perangkat'][0];
    }

    $sambutan_final = '';
    $sambutan_kustom = theme_config('sambutan_isi');

    if (!empty(trim($sambutan_kustom))) {
        $sambutan_final = $sambutan_kustom;
    } elseif ($kepala_desa) {
        $nama_kades = e($kepala_desa['nama']);
        $nama_jabatan = e($kepala_desa['jabatan']);
        $nama_entitas = e($desa['nama_desa']);
        $sebutan_desa = setting('sebutan_desa');
        
        $sambutan_final = "Assalamu'alaikum Wr. Wb. Saya, {$nama_kades}, selaku {$nama_jabatan} {$nama_entitas}, menyambut Anda di situs web resmi kami. Melalui media ini, kami berkomitmen untuk menyajikan informasi yang transparan dan bermanfaat. Mari bersama membangun {$sebutan_desa} yang lebih maju dan sejahtera.";
    }
@endphp

<div class="rounded-none shadow-lg h-full flex flex-col items-center text-center overflow-hidden group"
     :class="darkMode ? 'bg-gray-800' : 'bg-gradient-to-br {{ $gradient_class_sambutan }} text-white'">
    
    @if ($kepala_desa)
        <div class="relative w-full pt-6 lg:pt-8 px-5">

            <h2 class="text-xl lg:text-2xl font-bold text-white mb-6 relative z-10">Sambutan {{ ucwords(setting('sebutan_kepala_desa')) }}</h2>
            <div class="relative w-40 h-44 mx-auto">
                <div class="absolute inset-0 bg-black/20 hexagon-clip transform scale-110"></div>
                <div class="w-full h-full bg-cover bg-center hexagon-clip" style="background-image: url('{{ $kepala_desa['foto'] }}')"></div>
            </div>
        </div>

        <div class="w-full relative z-10 flex-grow flex flex-col">
            <div class="sambutan-info-card p-4 bg-black/20 backdrop-blur-sm lg:transform lg:translate-y-full lg:opacity-0 lg:group-hover:translate-y-0 lg:group-hover:opacity-100 transition-all duration-500">
                <h3 class="text-lg lg:text-xl font-bold text-white">{{ $kepala_desa['nama'] }}</h3>
                <p class="text-sm text-white/80">{{ $kepala_desa['jabatan'] }}</p>
                @if ($kepala_desa['kehadiran'] == 1)
                    <div class="mt-2">
                        @if ($kepala_desa['status_kehadiran'] == 'hadir')
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs bg-green-400/30 text-green-200 border border-green-400/50">Hadir</span>
                        @elseif ($kepala_desa['tanggal'] == date('Y-m-d'))
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs bg-red-400/30 text-red-200 border border-red-400/50">{{ ucwords($kepala_desa['status_kehadiran']) }}</span>
                        @else
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs bg-yellow-400/30 text-yellow-200 border border-yellow-400/50">Belum Rekam</span>
                        @endif
                    </div>
                @endif
            </div>
            
            <div class="p-5 flex-grow flex items-center justify-center lg:-mt-10 lg:group-hover:-mt-32 transition-all duration-500 ease-in-out">
                @if (!empty(trim($sambutan_final)))
                    <p class="italic text-sm text-white/90 dark:text-gray-300 leading-relaxed">
                        "{{ potong_teks(strip_tags($sambutan_final), 250) }}..."
                    </p>
                @endif
            </div>
        </div>
    @endif
</div>