@php
    defined('BASEPATH') OR exit('No direct script access allowed');
@endphp

<div class="bg-white dark:bg-gray-800 shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
    <div class="p-4 bg-gradient-to-r from-green-500 to-teal-500 text-white">
        <h3 class="font-bold text-sm uppercase tracking-wider flex items-center">
            <i class="fas fa-comments mr-3"></i>{{ $judul_widget }}
        </h3>
    </div>

    <div class="p-4">
        @if (count($komen ?? []) > 0)
            <div class="space-y-4">
                @foreach ($komen as $data)
                    <div class="border-l-2 border-teal-500 pl-3 py-1">
                        <p class="text-xs text-gray-600 dark:text-gray-400 italic leading-relaxed">
                            "{{ potong_teks($data['komentar'], 80) }}..."
                        </p>
                        <div class="mt-2 flex items-center justify-between text-[10px] font-bold uppercase tracking-tighter">
                            <span class="text-teal-600">{{ e($data['owner']) }}</span>
                            <a href="{{ site_url('artikel/' . buat_slug($data)) }}" class="text-gray-400 hover:text-teal-500 underline">Detail</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-xs text-gray-400 py-4 italic">Belum ada komentar.</p>
        @endif
    </div>
</div>