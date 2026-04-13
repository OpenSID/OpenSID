<div class="py-3 border-b border-white/20 dark:border-gray-700 last:border-b-0 last:pb-0 first:pt-0">
    <a href="{{ site_url('artikel/' . buat_slug($agenda)) }}" class="block font-bold text-white hover:underline mb-2 text-base">
        {{ $agenda['judul'] }}
    </a>

    <div class="space-y-1 text-sm text-white/80 dark:text-gray-300">
        <div class="flex items-start">
            <div class="w-8 text-center flex-shrink-0 pt-1"><i class="fas fa-calendar-alt fa-fw"></i></div>
            <div class="flex-grow">
                <span class="font-semibold block">Waktu</span>
                <span>{{ tgl_indo2($agenda['tgl_agenda']) }}</span>
            </div>
        </div>

        <div class="flex items-start">
            <div class="w-8 text-center flex-shrink-0 pt-1"><i class="fas fa-map-marker-alt fa-fw"></i></div>
            <div class="flex-grow">
                <span class="font-semibold block">Lokasi</span>
                <span>{{ $agenda['lokasi_kegiatan'] }}</span>
            </div>
        </div>

        <div class="flex items-start">
            <div class="w-8 text-center flex-shrink-0 pt-1"><i class="fas fa-user-tie fa-fw"></i></div>
            <div class="flex-grow">
                <span class="font-semibold block">Koordinator</span>
                <span>{{ $agenda['koordinator_kegiatan'] }}</span>
            </div>
        </div>
    </div>
</div>