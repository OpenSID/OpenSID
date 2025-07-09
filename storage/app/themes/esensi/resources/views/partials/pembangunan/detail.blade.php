@extends('theme::layouts.full-content')
@include('theme::commons.asset_peta')

@section('content')
    <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
            <li><a href="{{ site_url('/') }}">Beranda</a></li>
            <li><a href="{{ site_url('pembangunan') }}">Pembangunan</a></li>
            <li aria-current="page" class="judul-pembangunan">

            </li>
        </ol>
    </nav>
    <h1 class="text-h2 judul-pembangunan"></h1>
    <div class="flex flex-col lg:flex-row justify-between gap-3 lg:gap-5 py-5" id="detail-pembangunan">
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var slug = '{{ $slug }}';
            var notFound = '{{ asset('images/404-image-not-found.jpg') }}';

            function loadPembangunan() {
                const apiPembangunan = '{{ route('api.pembangunan') }}';
                const params = {
                    'filter[slug]': slug
                };

                $('#detail-pembangunan').html('<p class="text-center">Memuat data pembangunan...</p>');

                $.get(apiPembangunan, params, function(response) {
                    var detailPembangunan = $('#detail-pembangunan');

                    detailPembangunan.empty();

                    if (response.data.length !== 1) {
                        detailPembangunan.html('<p class="py-2 text-center">Data pembangunan tidak ditemukan.</p>');
                        return;
                    }

                    const pembangunan = response.data[0].attributes;
                    const dokumentasi = pembangunan.pembangunan_dokumentasi;

                    $('.judul-pembangunan').text('Detail Pembangunan ' + pembangunan.judul);

                    var pembangunanHTML = '';
                    var anggaran = formatRupiah(pembangunan.anggaran, 'Rp ');

                    // Detail Pembangunan
                    pembangunanHTML += `
                    <div class="w-full px-2">
                        <img class="h-auto w-full" src="${pembangunan.foto ?? notFound}" alt="Foto Pembangunan" />
                        <div class="table-responsive py-5 content">
                            <table class="w-full">
                                <tr><th width="150px">Nama Kegiatan</th><td width="20px">:</td><td>${pembangunan.judul}</td></tr>
                                <tr><th>Alamat</th><td>:</td><td>${pembangunan.alamat}</td></tr>
                                <tr><th>Sumber Dana</th><td>:</td><td>${pembangunan.sumber_dana}</td></tr>
                                <tr><th>Anggaran</th><td>:</td><td>${anggaran}</td></tr>
                                <tr><th>Volume</th><td>:</td><td>${pembangunan.volume}</td></tr>
                                <tr><th>Pelaksana</th><td>:</td><td>${pembangunan.pelaksana_kegiatan}</td></tr>
                                <tr><th>Tahun</th><td>:</td><td>${pembangunan.tahun_anggaran}</td></tr>
                                <tr><th>Keterangan</th><td>:</td><td>${pembangunan.keterangan}</td></tr>
                            </table>
                        </div>
                    </div>
                `;

                    // Dokumentasi Pembangunan
                    pembangunanHTML += `
                    <div class="w-full px-2 space-y-3">
                        <h2 class="text-h4">Progres Pembangunan</h2>
                        <hr>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                            `;

                    if (dokumentasi && dokumentasi.length > 0) {
                        dokumentasi.forEach((dok) => {
                            pembangunanHTML += `
                            <div class="w-full text-center py-2">
                                <img width="auto" class="h-auto w-full" src="${dok.gambar ?? notFound}" alt="Foto Pembangunan ${dok.persentase}%">
                                <b>Foto Pembangunan ${dok.persentase}</b>
                            </div>
                        `;
                        });
                    } else {
                        pembangunanHTML += `
                        <div class="w-full text-center">
                            <p>Belum ada dokumentasi pembangunan yang tersedia.</p>
                        </div>
                    `;
                    }

                    pembangunanHTML += `
                        </div>
                        <div class="mt-5 space-y-3">
                            <h2 class="text-h4">Progres Pembangunan</h2>
                            <hr>
                            <div id="map-pembangunan" style="height: 340px"></div>
                        </div>
                    </div>
                `;

                    detailPembangunan.append(pembangunanHTML);

                    loadMap(pembangunan);
                });
            }

            function loadMap(pembangunan) {
                if (pembangunan.lat && pembangunan.lng) {

                    let lat = pembangunan.lat || config.lat;
                    let lng = pembangunan.lng || config.lng;
                    let posisi = [lat, lng];
                    let zoom = setting.default_zoom || 15;

                    let logo = L.icon({
                        iconUrl: setting.icon_pembangunan_peta,
                        iconSize: [30, 40],
                        iconAnchor: [15, 40]
                    });

                    let options = {
                        maxZoom: setting.max_zoom_peta || 18,
                        minZoom: setting.min_zoom_peta || 5,
                        attributionControl: true
                    };

                    let map = L.map('map-pembangunan', options).setView(posisi, zoom);

                    getBaseLayers(map, setting.mapbox_key, setting.jenis_peta);

                    L.marker(posisi, {
                        icon: logo
                    }).addTo(map);
                }
            }

            loadPembangunan();
        });
    </script>
@endpush
