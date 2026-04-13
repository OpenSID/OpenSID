<div class="w-full md:w-1/2">
    <h2 class="text-2xl font-bold mb-3">Lokasi Kami</h2>
    <p class="text-sm text-gray-700 mb-3">
        {{ ucwords(setting('sebutan_kecamatan')) }} {{ $desa['nama_kecamatan'] }}
        {{ ucwords(setting('sebutan_kabupaten')) }} {{ $desa['nama_kabupaten'] }} Provinsi {{ $desa['nama_propinsi'] }}
    </p>

    <div class="relative w-full h-[250px] bg-gray-200 z-[9] rounded-lg overflow-hidden">
        <div id="map_canvas" class="w-full h-full"></div>

        <div class="absolute bottom-4 right-4 bg-white p-3 rounded-lg shadow-lg z-[999] pointer-events-auto">
            <div class="flex items-center gap-2">
                <div class="bg-green-100 rounded-full p-1">
                    <i data-lucide="users" class="h-5 w-5 text-green-700"></i>
                </div>
                <div>
                    <a href="https://www.openstreetmap.org/#map=15/{{ $data_config['lat'] }}/{{ $data_config['lng'] }}"
                        target="_blank" class="text-xs font-bold">Kantor {{ ucwords(setting('sebutan_desa')) }}
                        {{ $desa['nama_desa'] }}</a>
                    <p class="text-xs text-gray-500">{{ $desa['alamat_kantor'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Modal for Video Popup -->
<div id="videoModal" class="fixed inset-0 bg-black bg-opacity-50 z-[10000] flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4 relative">
        <!-- Close Button -->
        <button id="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Modal Content -->
        <div class="p-2">
            <div class="text-center">
                <div class="bg-green-600 flex items-center justify-center py-3 px-6 mb-1">
                    <h3 class="text-md font-semibold text-white text-center">
                        CCTV Pelayanan Desa
                    </h3>
                </div>
                <div class="h-1 bg-green-500 mb-2"></div>
                <div class="video-container">
                    <video id="cctvVideo" controls muted playsinline
                        class="w-full h-auto max-h-80 rounded-lg shadow-lg">
                        <source src="{{ theme_config('url_cctv') }} type=" application/x-mpegURL">
                        Browser tidak mendukung video.
                    </video>
                    <p id="videoError" class="text-red-600 text-sm mt-2 hidden">Gagal memuat video CCTV</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #map_canvas {
        width: 100%;
        height: 100%;
    }

    .leaflet-popup-content {
        width: 400px !important;
        margin: 8px 12px;
    }

    .video-popup {
        text-align: center;
        padding: 10px;
    }

    .video-popup video {
        width: 100%;
        height: auto;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .video-popup strong {
        display: block;
        margin-bottom: 10px;
        color: #2d5a27;
        font-size: 16px;
    }

    /* Custom popup styling */
    .leaflet-popup-content-wrapper {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .leaflet-popup-tip {
        background: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Custom Location Pin Marker Styles */
    .custom-location-marker {
        background: none !important;
        border: none !important;
    }

    .location-pin {
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .location-pin:hover {
        transform: scale(1.1);
    }

    .pin-head {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        border: 3px solid white;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
        position: relative;
        z-index: 2;
        animation: bounce 2s infinite;
    }

    .pin-head svg {
        transform: rotate(45deg);
        color: white;
        width: 16px;
        height: 16px;
    }

    .pin-shadow {
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 16px;
        height: 6px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 50%;
        filter: blur(2px);
        animation: shadowPulse 2s infinite;
    }

    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-8px);
        }

        60% {
            transform: translateY(-4px);
        }
    }

    @keyframes shadowPulse {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateX(-50%) scale(1);
            opacity: 0.2;
        }

        40% {
            transform: translateX(-50%) scale(0.8);
            opacity: 0.3;
        }

        60% {
            transform: translateX(-50%) scale(0.9);
            opacity: 0.25;
        }
    }

    #closeModal {
        color: #000000;
        transition: color 0.2s ease;
    }

    #closeModal:hover {
        color: #ffffff;
    }
</style>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- HLS.js for video streaming -->
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<script>
    // Jika posisi kantor desa belum ada, tampilkan seluruh Indonesia
    @if (!empty($data_config['lat']) && !empty($data_config['lng']))
        var posisi = [{{ $data_config['lat'] }}, {{ $data_config['lng'] }}];
        var zoom = {{ $data_config['zoom'] ?: 10 }};
    @else
                var posisi = [-7.3983118, 109.5432662]; // default center
        var zoom = 15;
    @endif

    var options = {
        maxZoom: {{ setting('max_zoom_peta') }},
        minZoom: {{ setting('min_zoom_peta') }},
    };

    // Init map
    var lokasi_kantor = L.map('map_canvas', options).setView(posisi, zoom);

    // Base layer (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(lokasi_kantor);

    // Custom icon
    var customIcon = L.divIcon({
        className: 'custom-location-marker',
        html: `
            <div class="location-pin">
                <div class="pin-head">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                </div>
                <div class="pin-shadow"></div>
            </div>
        `,
        iconSize: [32, 40],
        iconAnchor: [16, 40],
        popupAnchor: [0, -40]
    });

    // Add marker
    var marker = L.marker(posisi, { icon: customIcon }).addTo(lokasi_kantor);

    // Ambil host saat ini
    var currentHost = window.location.hostname;

    // Elemen modal
    var modal = document.getElementById('videoModal');
    var video = document.getElementById('cctvVideo');
    var closeBtn = document.getElementById('closeModal');
    var videoError = document.getElementById('videoError');

    // Global HLS instance
    var hlsInstance = null;

    // Fungsi buka modal dengan HLS.js
    function openVideoModal() {
        modal.classList.remove('hidden');
        videoError.classList.add('hidden');

        var videoUrl = '{{ theme_config('url_cctv') }}';

        // Cek apakah browser support HLS.js
        if (Hls.isSupported()) {
            console.log('Using HLS.js');

            // Destroy previous instance jika ada
            if (hlsInstance) {
                hlsInstance.destroy();
            }

            hlsInstance = new Hls({
                enableWorker: true,
                lowLatencyMode: true,
                maxBufferLength: 30,
                maxMaxBufferLength: 60,
                manifestLoadingTimeOut: 10000,
                manifestLoadingMaxRetry: 3,
                levelLoadingTimeOut: 10000,
                levelLoadingMaxRetry: 3,
                fragLoadingTimeOut: 20000,
                fragLoadingMaxRetry: 3,
            });

            hlsInstance.loadSource(videoUrl);
            hlsInstance.attachMedia(video);

            hlsInstance.on(Hls.Events.MANIFEST_PARSED, function () {
                console.log('Manifest parsed, playing video...');
                video.play().catch(function (err) {
                    console.log('Autoplay prevented:', err);
                });
            });

            // Handle errors
            hlsInstance.on(Hls.Events.ERROR, function (event, data) {
                console.error('HLS Error:', data);

                if (data.fatal) {
                    switch (data.type) {
                        case Hls.ErrorTypes.NETWORK_ERROR:
                            console.error('Network error - attempting recovery');
                            hlsInstance.startLoad();
                            break;
                        case Hls.ErrorTypes.MEDIA_ERROR:
                            console.error('Media error - attempting recovery');
                            hlsInstance.recoverMediaError();
                            break;
                        default:
                            console.error('Fatal error - destroying player');
                            videoError.classList.remove('hidden');
                            hlsInstance.destroy();
                            hlsInstance = null;
                            break;
                    }
                }
            });
        }
        // Safari native HLS support
        else if (video.canPlayType('application/vnd.apple.mpegurl')) {
            console.log('Using native HLS (Safari)');
            video.src = videoUrl;
            video.addEventListener('loadedmetadata', function () {
                video.play().catch(function (err) {
                    console.log('Autoplay prevented:', err);
                });
            });

            video.addEventListener('error', function () {
                console.error('Video error');
                videoError.classList.remove('hidden');
            });
        }
        // Browser tidak support HLS
        else {
            console.error('Browser does not support HLS');
            videoError.textContent = 'Browser Anda tidak mendukung streaming HLS';
            videoError.classList.remove('hidden');
        }
    }

    // Fungsi tutup modal
    function closeVideoModal() {
        video.pause();
        video.currentTime = 0;

        // Destroy HLS instance
        if (hlsInstance) {
            hlsInstance.destroy();
            hlsInstance = null;
        }

        // Reset video source
        video.src = '';

        modal.classList.add('hidden');
    }

    // Jalankan hanya jika host == timbang-purbalingga.digidesa.id
    if (currentHost === 'timbang-purbalingga.digidesa.id') {
        marker.on('click', function () {
            openVideoModal();
        });

        // Tooltip aktif
        marker.bindTooltip("Klik untuk melihat CCTV Pelayanan", {
            permanent: false,
            direction: "top",
            offset: [0, -10]
        });
    } else {
        // Tooltip pasif (tanpa klik)
        marker.bindTooltip("Lokasi Kantor {{ ucfirst(setting('sebutan_desa')) }}", {
            permanent: false,
            direction: "top",
            offset: [0, -10]
        });
    }

    // Event listeners untuk tutup modal
    closeBtn.addEventListener('click', closeVideoModal);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            closeVideoModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeVideoModal();
        }
    });

    // Cleanup saat page unload
    window.addEventListener('beforeunload', function () {
        if (hlsInstance) {
            hlsInstance.destroy();
        }
    });
</script>