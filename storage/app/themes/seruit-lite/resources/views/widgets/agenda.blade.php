<?php
defined('BASEPATH') or exit('No direct script access allowed');

$active_gradient = 'from-green-500 to-teal-500';
$total_agenda = count($hari_ini ?? []) + count($yad ?? []) + count($lama ?? []);
?>
<div class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
    <div class="p-4 bg-gradient-to-r {{ $active_gradient }} text-white">
        <h3 class="font-bold text-sm uppercase tracking-wider flex items-center">
            <i class="fas fa-calendar-alt mr-3"></i>{{ $judul_widget }}
        </h3>
    </div>

    @if ($total_agenda > 0)
    @php
    $active_tab = 'hari-ini';
    if (count($hari_ini ?? []) === 0) {
        $active_tab = 'yad';
        if (count($yad ?? []) === 0) {
            $active_tab = 'lama';
        }
    }
    @endphp
    
    <div class="flex flex-col" x-data="{ activeTab: '{{ $active_tab }}' }">
        <nav class="flex border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            @if (count($hari_ini ?? []) > 0)
            <button @click="activeTab = 'hari-ini'" class="flex-1 py-3 px-2 text-[10px] uppercase font-bold transition-all duration-200 border-b-2" :class="activeTab === 'hari-ini' ? 'border-teal-500 text-teal-600 dark:text-teal-400 bg-white dark:bg-gray-800' : 'border-transparent text-gray-400 hover:text-gray-600'">
                Hari Ini
            </button>
            @endif
            
            @if (count($yad ?? []) > 0)
            <button @click="activeTab = 'yad'" class="flex-1 py-3 px-2 text-[10px] uppercase font-bold transition-all duration-200 border-b-2" :class="activeTab === 'yad' ? 'border-teal-500 text-teal-600 dark:text-teal-400 bg-white dark:bg-gray-800' : 'border-transparent text-gray-400 hover:text-gray-600'">
                Mendatang
            </button>
            @endif
            
            @if (count($lama ?? []) > 0)
            <button @click="activeTab = 'lama'" class="flex-1 py-3 px-2 text-[10px] uppercase font-bold transition-all duration-200 border-b-2" :class="activeTab === 'lama' ? 'border-teal-500 text-teal-600 dark:text-teal-400 bg-white dark:bg-gray-800' : 'border-transparent text-gray-400 hover:text-gray-600'">
                Lampau
            </button>
            @endif
        </nav>

        <div class="p-4">
            @if (count($hari_ini ?? []) > 0)
            <div x-show="activeTab === 'hari-ini'" class="space-y-4">
                @foreach ($hari_ini as $agenda)
                @include('theme::widgets.partials.agenda_item', ['agenda' => $agenda])
                @endforeach
            </div>
            @endif
            
            @if (count($yad ?? []) > 0)
            <div x-show="activeTab === 'yad'" class="space-y-4">
                @foreach ($yad as $agenda)
                @include('theme::widgets.partials.agenda_item', ['agenda' => $agenda])
                @endforeach
            </div>
            @endif
            
            @if (count($lama ?? []) > 0)
            <div x-show="activeTab === 'lama'" class="max-h-64 overflow-y-auto space-y-4 custom-scrollbar">
                @foreach ($lama as $agenda)
                @include('theme::widgets.partials.agenda_item', ['agenda' => $agenda])
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="p-8 text-center text-xs text-gray-400 italic">
        Belum ada agenda kegiatan.
    </div>
    @endif
</div>