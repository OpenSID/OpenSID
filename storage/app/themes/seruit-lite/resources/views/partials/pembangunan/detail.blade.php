@extends('theme::layouts.full-content')

@section('content')
<div id="detail-pembangunan-container" class="bg-[var(--bg-color-card)] p-6 shadow-xl border border-[var(--border-color)] -mt-16 relative z-10">
    <nav id="breadcrumb-container" role="navigation" aria-label="navigation" class="breadcrumb text-sm mb-4"></nav>
    
    <div id="title-container" class="flex items-center mt-6 mb-8"></div>

    <div id="pembangunan-content">
        <div class="col-span-full flex flex-col items-center justify-center py-12">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <p class="mt-2 text-sm font-semibold">Memuat Detail Pembangunan...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ theme_asset('js/helper.js') }}"></script>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        const slug = '{{ $slug }}';
        const apiPembangunan = `{{ ci_route('internal_api.pembangunan') }}?filter[slug]=${slug}`;

        fetch(apiPembangunan)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (!data.data || data.data.length === 0) {
                    document.getElementById('pembangunan-content').innerHTML = `<div class="alert alert-danger text-center">Data pembangunan tidak ditemukan.</div>`;
                    return;
                }
                const pembangunan = data.data[0].attributes;
                renderContent(pembangunan);
                
                if (pembangunan.lat && pembangunan.lng) {
                    loadMap(pembangunan);
                }
                Fancybox.bind('[data-fancybox="gallery"]');
            })
            .catch(error => {
                console.error("Error fetching detail:", error);
                document.getElementById('pembangunan-content').innerHTML = `<div class="alert alert-danger text-center">Terjadi kesalahan saat memuat data detail pembangunan.</div>`;
            });
        
        function renderContent(p) {
            document.getElementById('breadcrumb-container').innerHTML = `
                <ol class="flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400">
                    <li><a href="{{ site_url() }}" class="hover:underline hover:text-blue-600">Beranda</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ site_url('pembangunan') }}" class="hover:underline hover:text-blue-600">Pembangunan</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li aria-current="page" class="font-medium text-gray-700 dark:text-gray-300 line-clamp-1">${escapeHtml(p.judul)}</li>
                </ol>`;
            document.getElementById('title-container').innerHTML = `<div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div><h1 class="flex-shrink px-4 text-2xl lg:text-3xl font-bold uppercase text-center">${escapeHtml(p.judul)}</h1><div class="flex-grow border-t border-gray-300 dark:border-gray-700"></div>`;

            const contentContainer = document.getElementById('pembangunan-content');
            
            let dokumentasiHtml = '';
            if (p.pembangunan_dokumentasi && p.pembangunan_dokumentasi.length > 0) {
                p.pembangunan_dokumentasi.forEach(doc => {
                    dokumentasiHtml += `
                        <div class="text-center">
                            <a href="${doc.gambar || '{{ theme_asset('images/placeholder.png') }}'}" data-fancybox="gallery" data-caption="Dokumentasi ${escapeHtml(doc.persentase)}">
                                <img src="${doc.gambar || '{{ theme_asset('images/placeholder.png') }}'}" alt="Dokumentasi ${escapeHtml(doc.persentase)}" class="w-full h-48 object-cover shadow-md mb-2">
                            </a>
                            <p class="text-sm font-semibold">${escapeHtml(doc.persentase)}</p>
                        </div>
                    `;
                });
            } else {
                dokumentasiHtml = '<p class="text-sm text-center text-gray-500 col-span-full">Belum ada dokumentasi progres.</p>';
            }

            contentContainer.innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                    <div class="lg:col-span-3 space-y-4">
                        <img src="${p.foto || '{{ theme_asset('images/placeholder.png') }}'}" alt="${escapeHtml(p.judul)}" class="w-full h-auto shadow-lg">
                        <div class="prose dark:prose-invert max-w-none">
                           <h4>Keterangan Proyek</h4>
                           <p>${p.keterangan ? escapeHtml(p.keterangan).replace(/\\n/g, '<br>') : 'Tidak ada keterangan.'}</p>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="sticky top-20">
                            <h3 class="font-bold text-xl mb-4">Detail Proyek</h3>
                            <table class="w-full text-sm">
                                <tbody class="divide-y dark:divide-gray-700">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50"><td class="p-2 font-semibold w-1/3">Lokasi</td><td class="p-2">${escapeHtml(p.lokasi)}</td></tr>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50"><td class="p-2 font-semibold">Anggaran</td><td class="p-2">${formatRupiah(p.anggaran)}</td></tr>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50"><td class="p-2 font-semibold">Sumber Dana</td><td class="p-2">${escapeHtml(p.sumber_dana)}</td></tr>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50"><td class="p-2 font-semibold">Volume</td><td class="p-2">${escapeHtml(p.volume)}</td></tr>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50"><td class="p-2 font-semibold">Pelaksana</td><td class="p-2">${escapeHtml(p.pelaksana_kegiatan)}</td></tr>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50"><td class="p-2 font-semibold">Tahun</td><td class="p-2">${escapeHtml(p.tahun_anggaran)}</td></tr>
                                </tbody>
                            </table>
                            <div class="mt-6" id="share-buttons-container"></div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-12">
                    <h3 class="font-bold text-xl text-center mb-6">Dokumentasi Progres Pembangunan</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">${dokumentasiHtml}</div>
                </div>

                ${(p.lat && p.lng) ? `
                    <div class="mt-12">
                        <h3 class="font-bold text-xl text-center mb-6">Lokasi Pembangunan di Peta</h3>
                        <div id="map-pembangunan" class="w-full h-96 shadow-md border dark:border-gray-700"></div>
                    </div>
                ` : ''}
            `;
            
            renderShareButtons(p);
        }
        
        function renderShareButtons(p) {
            const container = document.getElementById('share-buttons-container');
            const url = SITE_URL + 'pembangunan/' + p.slug;
            const title = encodeURIComponent(p.judul);
            const shareLinks = {
                'facebook': `https://www.facebook.com/sharer.php?u=${url}`,
                'twitter': `https://twitter.com/intent/tweet?url=${url}&text=${title}`,
                'whatsapp': `https://api.whatsapp.com/send?text=${title} ${url}`,
                'telegram': `https://telegram.me/share/url?url=${url}&text=${title}`
            };
            const icons = {
                'facebook': 'fab fa-facebook-f', 'twitter': 'fab fa-twitter', 'whatsapp': 'fab fa-whatsapp', 'telegram': 'fab fa-telegram'
            };
            
            let buttonsHtml = '<h4 class="font-bold mb-2">Bagikan:</h4><div class="flex space-x-2">';
            for (const key in shareLinks) {
                buttonsHtml += `<a href="${shareLinks[key]}" target="_blank" rel="noopener noreferrer" class="w-8 h-8 rounded-full flex items-center justify-center text-white bg-gray-600 hover:bg-gray-700"><i class="${icons[key]}"></i></a>`;
            }
            buttonsHtml += '</div>';
            container.innerHTML = buttonsHtml;
        }

        function loadMap(p) {
            const posisi = [p.lat, p.lng];
            const zoom = p.zoom || 16;
            const map = L.map('map-pembangunan', {
                maxZoom: setting.max_zoom_peta,
                minZoom: setting.min_zoom_peta
            }).setView(posisi, zoom);

            getBaseLayers(map, setting.mapbox_key, setting.jenis_peta);
            
            const markerIcon = L.icon({
                iconUrl: setting.icon_pembangunan_peta || `{{ base_url('/assets/images/gis/point/construction.png') }}`,
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -28]
            });

            L.marker(posisi, { icon: markerIcon }).addTo(map)
                .bindPopup(`<b>${escapeHtml(p.judul)}</b><br>${escapeHtml(p.lokasi)}`);
        }
    });
</script>
@endpush