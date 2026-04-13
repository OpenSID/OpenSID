@php
    defined('BASEPATH') OR exit('No direct script access allowed');
    $gradient_lite = 'from-green-500 to-teal-500';
@endphp

<div class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
    <div class="p-4 bg-gradient-to-r {{ $gradient_lite }} text-white">
        <h3 class="font-bold text-sm uppercase tracking-wider flex items-center">
            <i class="fas fa-chart-line mr-3"></i>{{ $judul_widget }}
        </h3>
    </div>

    <div class="p-4">
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center text-gray-600 dark:text-gray-400">
                    <i class="fas fa-user-clock w-5 text-teal-500"></i>
                    <span class="text-xs font-bold ml-2">HARI INI</span>
                </div>
                <span class="text-sm font-mono font-bold text-gray-800 dark:text-white">{{ e($statistik_pengunjung['hari_ini']) }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center text-gray-600 dark:text-gray-400">
                    <i class="fas fa-calendar-day w-5 text-teal-500"></i>
                    <span class="text-xs font-bold ml-2">KEMARIN</span>
                </div>
                <span class="text-sm font-mono font-bold text-gray-800 dark:text-white">{{ e($statistik_pengunjung['kemarin'] ?? 0) }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center text-gray-600 dark:text-gray-400">
                    <i class="fas fa-users w-5 text-teal-500"></i>
                    <span class="text-xs font-bold ml-2">TOTAL</span>
                </div>
                <span class="text-sm font-mono font-bold text-gray-800 dark:text-white">{{ e($statistik_pengunjung['total']) }}</span>
            </div>
        </div>
    </div>
</div>