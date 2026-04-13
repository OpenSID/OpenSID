@php
    $is_sholat_active = theme_config('tampilkan_sholat', '1') == '1';
    $active_gradient = 'from-green-500 to-teal-500';
@endphp

@if ($is_sholat_active)
<section class="my-12">
    <div class="rounded-none shadow-xl overflow-hidden text-white bg-gradient-to-r {{ $active_gradient }} dark:bg-none dark:bg-gray-800 border border-black/10 dark:border-gray-700">
        <div class="flex flex-col md:flex-row">
            <div class="p-6 md:w-1/4 flex flex-col justify-center items-center text-center bg-black/10 border-b md:border-b-0 md:border-r border-white/10">
                <i class="fas fa-mosque text-4xl mb-3 opacity-50"></i>
                <h3 class="font-bold text-xl uppercase tracking-tighter">Jadwal Sholat</h3>
                <p id="sholat-lokasi" class="text-xs font-semibold mt-1 opacity-80 uppercase tracking-widest">Memuat...</p>
            </div>

            <div class="md:w-3/4 grid grid-cols-3 md:grid-cols-5">
                @php $waktu = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya']; @endphp
                @foreach($waktu as $w)
                <div class="p-4 flex flex-col items-center justify-center border-r border-b md:border-b-0 border-white/10 hover:bg-white/5 transition-colors">
                    <span class="text-[10px] uppercase font-bold opacity-70 mb-1">{{ $w }}</span>
                    <span class="text-xl font-mono font-extrabold" id="sholat-{{ strtolower($w) }}">--:--</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        fetch(`https://api.myquran.com/v2/sholat/jadwal/{{ theme_config('kode_kota_sholat', '1301') }}/{{ date('Y-m-d') }}`)
            .then(res => res.json())
            .then(data => {
                if(data.status && data.data) {
                    const j = data.data.jadwal;
                    document.getElementById('sholat-lokasi').textContent = data.data.lokasi;
                    ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'].forEach(w => {
                        document.getElementById(`sholat-${w}`).textContent = j[w];
                    });
                }
            }).catch(() => {});
    });
</script>
@endpush
@endif