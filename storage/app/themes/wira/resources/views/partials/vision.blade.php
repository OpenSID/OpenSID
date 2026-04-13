@php $visiMisiFound = false; @endphp {{-- 1. Inisialisasi flag pelacak --}}

@if ($widgetAktif)
        @foreach ($widgetAktif as $widget)
            @if (strtolower($widget['judul']) == "visi misi")
                @php $visiMisiFound = true; @endphp {{-- 2. Set flag jika widget ditemukan --}}

                {{-- BLOK SAAT WIDGET AKTIF (KODE ASLI ANDA) --}}
                <div class="w-full md:w-1/2">
                    <h2 class="text-2xl font-bold">Visi Misi Desa</h2>
                    <h3 class="text-green-600 font-semibold mb-4">Cita Cita Desa</h3>
                    @php
                        
                        $url = '';
                        preg_match_all('/\[(https?:\/\/[^\]]+)\]/', $widget['isi'], $matches);

                        if (!empty($matches[1])) {
                            // Ambil URL pertama
                            $url = $matches[1][0]; 
                        }
                        $cleanText = preg_replace('/\[.*?\]/', '', $widget['isi']);
                        
                        $visimisi = explode(';', $cleanText);
                        
                    @endphp

                    @foreach ($visimisi as $isi)
                        <p class="text-sm text-justify text-gray-700 mb-2">
                            {!! potong_teks(html_entity_decode($isi), 450) !!} 
                            {{ strlen($isi) > 450 ? '...' : '' }}
                        </p>
                    @endforeach
                    
                    {{-- Perbaikan: Hanya tampilkan tombol jika $url ditemukan --}}
                    @if ($url)
                        <a href={{$url}}>
                            <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors mt-3">
                                Lihat Selengkapnya
                            </button>
                        </a>
                    @endif
                </div>
                {{-- AKHIR BLOK WIDGET AKTIF --}}

            @endif
        @endforeach
 @endif


{{-- 3. Tampilkan blok ini HANYA JIKA flag masih false --}}
@if (!$visiMisiFound)
    
    {{-- BLOK INSTRUKSI (MEMPERTAHANKAN STYLE WRAPPER ASLI) --}}
    <div class="w-full md:w-1/2">
        {{-- Style judul dipertahankan sesuai permintaan --}}
        <h2 class="text-2xl font-bold">Visi Misi Desa</h2>
        <h3 class="text-green-600 font-semibold mb-4">Cita Cita Desa</h3>

        {{-- Konten Instruksi untuk Admin --}}
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mt-2 text-sm">
            <div class="flex items-center gap-2 text-gray-700 mb-3">
                <i data-lucide="info" class="w-5 h-5 flex-shrink-0 text-gray-500"></i>
                <h4 class="font-semibold text-gray-800">Instruksi Admin: Widget "visi misi" belum aktif.</h4>
            </div>
            
            <p class="text-gray-600 mb-3">
                Untuk menampilkan Visi & Misi di sini, ikuti langkah berikut:
            </p>
            
            <ul class="text-gray-700 list-decimal list-inside space-y-1.5">
                <li>Login ke panel Admin dan masuk ke menu Widget.</li>
                <li>Buat atau Aktifkan widget yang sesuai.</li>
                <li>Pastikan kolom <strong>Judul Widget</strong> diisi dengan: <strong><code>visi misi</code></strong> (harus sama persis, huruf kecil).</li>
                <li>Pada <strong>Isi Widget</strong>, pisahkan Visi dan Misi dengan tanda titik koma (<strong>;</strong>).</li>
                <li>(Opsional) Untuk tombol "Lihat Selengkapnya", tambahkan URL atikel visi misi di akhir isi, contoh: <strong><code>[https://web-desa.id/visi-misi]</code></strong></li>
            </ul>
        </div>
        {{-- Akhir Konten Instruksi --}}
        
    </div>
    {{-- AKHIR BLOK INSTRUKSI --}}

@endif