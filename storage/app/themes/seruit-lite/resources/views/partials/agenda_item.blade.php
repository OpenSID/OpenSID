<div class="border-b border-white/10 dark:border-gray-700 pb-3 last:border-b-0 last:pb-0">
    <a href="{{ site_url('artikel/' . buat_slug($agenda)) }}" class="font-semibold text-sm hover:text-white transition-colors duration-200">
        {{ e($agenda['judul']) }}
    </a>
    <div class="mt-2 space-y-1 text-xs" :class="darkMode ? 'text-gray-400' : 'text-white/70'">
        <div class="flex items-center">
            <i class="fas fa-calendar-alt fa-fw w-5 text-center mr-2"></i>
            <span>{{ tgl_indo2($agenda['tgl_agenda']) }}</span>
        </div>
        <div class="flex items-center">
            <i class="fas fa-map-marker-alt fa-fw w-5 text-center mr-2"></i>
            <span>{{ e($agenda['lokasi_kegiatan']) }}</span>
        </div>
        <div class="flex items-center">
            <i class="fas fa-user-tie fa-fw w-5 text-center mr-2"></i>
            <span>{{ e($agenda['koordinator_kegiatan']) }}</span>
        </div>
    </div>
</div>