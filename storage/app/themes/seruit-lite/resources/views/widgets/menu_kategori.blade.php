@php
    defined('BASEPATH') OR exit('No direct script access allowed');
@endphp

<div class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
    <div class="p-4 bg-gradient-to-r from-green-500 to-teal-500 text-white">
        <h3 class="font-bold text-sm uppercase tracking-wider flex items-center">
            <i class="fas fa-folder mr-3"></i>{{ $judul_widget }}
        </h3>
    </div>

    <div class="p-2">
        <ul class="divide-y divide-gray-50 dark:divide-gray-700">
            @foreach ($menu_kiri as $data)
                <li>
                    <a href="{{ site_url('artikel/kategori/' . $data['slug']) }}" class="flex items-center justify-between px-3 py-2.5 text-xs font-bold text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors uppercase tracking-tight">
                        <span>{{ e($data['kategori']) }}</span>
                        <i class="fas fa-chevron-right text-[10px] opacity-30"></i>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>