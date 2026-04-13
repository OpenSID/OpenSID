@php $pengembanganFound = false; @endphp {{-- 1. Inisialisasi flag pelacak --}}

@if ($widgetAktif)
    @foreach ($widgetAktif as $widget)
        @php
            $judul_widget = [
                'judul_widget' => str_replace(
                    'Desa',
                    ucwords(setting('sebutan_desa')),
                    strip_tags($widget['judul'])
                ),
            ];
        @endphp

        @if (strtolower($widget['judul']) == "pengembangan")
            @php $pengembanganFound = true; @endphp {{-- 2. Set flag jika widget ditemukan --}}
            
            {{-- BLOK SAAT WIDGET AKTIF (KODE ASLI ANDA) --}}
            <div class="w-full md:w-1/2 relative">
                <div class="border-2 border-dashed border-green-600 rounded-lg p-6 relative">
                    <div class="absolute -top-3 left-4 bg-white px-2">
                        <h2 class="text-xl font-bold">Arah Pengembangan Desa</h2>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">
                        {{ ucfirst(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }} akan membangun desa yang berkelanjutan, maju dan sejahtera
                    </p>

                    @php
                        $items = explode(",", $widget['isi']);
                    @endphp

                    <div class="space-y-2">
                        @foreach($items as $item)
                            <div class="flex items-start gap-2">
                                <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mt-0.5"></i>
                                <p class="text-sm">{{ trim($item) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-around mt-8">
                        <div class="flex flex-col items-center">
                            <div class="bg-green-600 p-2 rounded-lg">
                                <i data-lucide="home" class="h-6 w-6 text-white"></i>
                            </div>
                            <p class="text-xs mt-1">Infrastruktur</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="bg-green-600 p-2 rounded-lg">
                                <i data-lucide="heart-handshake" class="h-6 w-6 text-white"></i>
                            </div>
                            <p class="text-xs mt-1">Ekonomi</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="bg-green-600 p-2 rounded-lg">
                                <i data-lucide="tree-pine" class="h-6 w-6 text-white"></i>
                            </div>
                            <p class="text-xs mt-1">Lingkungan</p>
                        </div>
                        
                        <div class="flex flex-col items-center">
                            <div class="bg-green-600 p-2 rounded-lg">
                                <i data-lucide="book-open" class="h-6 w-6 text-white"></i>
                            </div>
                            <p class="text-xs mt-1">Pendidikan</p>
                        </div>
                        
                    </div>
                </div>
            </div>
            {{-- AKHIR BLOK WIDGET AKTIF --}}

        @endif
    @endforeach
@endif

{{-- 3. Tampilkan blok ini HANYA JIKA flag masih false --}}
@if (!$pengembanganFound)
    
    {{-- BLOK INSTRUKSI SAAT WIDGET TIDAK AKTIF/TIDAK ADA --}}
    <div class="w-full md:w-1/2 relative">
        {{-- Style dipertahankan sesuai permintaan, menggunakan border abu-abu --}}
        <div class="border-2 border-dashed border-gray-400 rounded-lg p-6 relative min-h-[200px] flex items-center">
            <div class="absolute -top-3 left-4 bg-white px-2">
                <h2 class="text-xl font-bold">Arah Pengembangan Desa</h2>
            </div>

            {{-- Konten Instruksi untuk Admin --}}
            <div class="w-full text-center text-gray-500">
                <i data-lucide="info" class="w-10 h-10 mx-auto text-gray-400 mb-3"></i>
                <h3 class="font-semibold text-gray-800">Widget "pengembangan" Tidak Aktif</h3>
                <p class="text-sm mt-2 mb-4">
                    Untuk menampilkan Arah Pengembangan Desa di halaman depan, ikuti langkah berikut:
                </p>
                
                {{-- Instruksi per-poin untuk admin --}}
                <ul class="text-sm text-left list-decimal list-inside space-y-1.5 bg-gray-50 p-4 rounded-md border border-gray-200">
                    <li>Login ke panel Admin dan masuk ke menu Widget.</li>
                    <li>Buat atau Aktifkan widget yang sesuai.</li>
                    <li>Pastikan kolom <strong>Judul Widget</strong> diisi dengan: <strong><code>pengembangan</code></strong> (harus sama persis dan huruf kecil semua).</li>
                    <li>Pada <strong>Isi Widget</strong>, masukkan poin-poin pengembangan.</li>
                    <li>Pisahkan setiap poin menggunakan tanda koma (<strong>,</strong>).</li>
                    <li>Disarankan maksimal <strong>5 poin</strong> agar tampilan tetap konsisten.</li>
                </ul>
            </div>
            {{-- Akhir Konten Instruksi --}}
        </div>
    </div>
    {{-- AKHIR BLOK INSTRUKSI --}}

@endif