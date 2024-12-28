@extends('theme::layouts.full-content')
@include('theme::commons.asset_peta')

@section('content')
    <div class="single_category wow fadeInDown">
        <h2>
            <span class="bold_line"><span></span></span> <span class="solid_line"></span>
            <span class="title_text judul-pembangunan"></span>
        </h2>
    </div>

    <div class="box box-primary">
        <div class="box-body">
            <div class="row" id="detail-pembangunan">
            </div>
        </div>
    </div>

    @include('theme::commons.share', [
        'link' => site_url('pembangunan/' . $pembangunan->slug),
        'judul' => $pembangunan->judul,
    ])
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

                $.get(apiPembangunan, params, function(response) {
                    var detailPembangunan = $('#detail-pembangunan');

                    detailPembangunan.empty();

                    if (response.data.length !== 1) {
                        detailPembangunan.html('<p class="text-center">Tidak ada produk yang ditemukan.</p>');
                        return;
                    }

                    const pembangunan = response.data[0].attributes;
                    const dokumentasi = pembangunan.pembangunan_dokumentasi;

                    $('.judul-pembangunan').text('Detail Pembangunan ' + pembangunan.judul);

                    var pembangunanHTML = '';
                    var anggaran = formatRupiah(pembangunan.anggaran, 'Rp ');

                    // Detail Pembangunan
                    pembangunanHTML += `
                    <div class="col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Data Pembangunan</div>
                            <div class="panel-body">
                                <center>
                                    <img width="auto" class="img-fluid img-thumbnail" src="${pembangunan.foto ?? notFound}" alt="${pembangunan.slug}"/>
                                </center>
                                <br/>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="150px">Nama Kegiatan</th>
                                        <td width="20px">:</td>
                                        <td>${pembangunan.judul}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td width="20px">:</td>
                                        <td>${pembangunan.alamat}</td>
                                    </tr>
                                    <tr>
                                        <th>Sumber Dana</th>
                                        <td width="20px">:</td>
                                        <td>${pembangunan.sumber_dana}</td>
                                    </tr>
                                    <tr>
                                        <th>Anggaran</th>
                                        <td width="20px">:</td>
                                        <td>${anggaran}</td>
                                    </tr>
                                    <tr>
                                        <th>Volume</th>
                                        <td width="20px">:</td>
                                        <td>${pembangunan.volume}</td>
                                    </tr>
                                    <tr>
                                        <th>Pelaksana</th>
                                        <td width="20px">:</td>
                                        <td>${pembangunan.pelaksana_kegiatan}</td>
                                    </tr>
                                    <tr>
                                        <th>Tahun</th>
                                        <td width="20px">:</td>
                                        <td>${pembangunan.tahun_anggaran}</td>
                                    </tr>
                                    <tr>
                                        <th>Keterangan</th>
                                        <td width="20px">:</td>
                                        <td>${pembangunan.keterangan}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                `;

                    // Dokumentasi Pembangunan
                    var gambarDokumentasi = '';

                    if (dokumentasi && dokumentasi.length > 0) {
                        dokumentasi.forEach((dok) => {
                            gambarDokumentasi += `
                            <div class="col-sm-6 text-center">
                                <img width="auto" class="img-fluid img-thumbnail" src="${dok.gambar ?? notFound}" alt="Foto Pembangunan ${dok.persentase}" />
                                <b>Foto Pembangunan ${dok.persentase}</b>
                            </div>`;
                        });
                    } else {
                        gambarDokumentasi += `
                        <div class="col-sm-6 text-center">
                            <p>Belum ada dokumentasi pembangunan yang tersedia.</p>
                        </div>`;
                    }

                    pembangunanHTML += `
                <div class="col-sm-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Progres Pembangunan</div>
                        <div class="panel-body">
                            <div class="row">
                                ${gambarDokumentasi}
                            </div>
                        </div>
                    </div>
                </div>`;

                    pembangunanHTML += `
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Lokasi Pembangunan</div>
                        <div class="panel-body" id="map-pembangunan" style="height:400px;">
                        </div>
                    </div>
                </div>`;

                    detailPembangunan.append(pembangunanHTML);

                    loadMap(pembangunan);
                });
            }

            function loadMap(pembangunan) {
                if (pembangunan.lat && pembangunan.lng) {

                    // Tentukan posisi dan zoom default
                    let lat = pembangunan.lat || config.lat;
                    let lng = pembangunan.lng || config.lng;
                    let posisi = [lat, lng];
                    let zoom = setting.default_zoom || 15;

                    // Tambahkan ikon ke peta
                    let logo = L.icon({
                        iconUrl: setting.icon_pembangunan_peta,
                        iconSize: [30, 40], // Ukuran ikon
                        iconAnchor: [15, 40] // Posisi anchor
                    });

                    // Konfigurasi opsi peta
                    let options = {
                        maxZoom: setting.max_zoom_peta || 18,
                        minZoom: setting.min_zoom_peta || 5,
                        attributionControl: true
                    };

                    // Inisialisasi peta
                    let map = L.map('map-pembangunan', options).setView(posisi, zoom);

                    // Tambahkan layer dasar ke peta
                    getBaseLayers(map, setting.mapbox_key, setting.jenis_peta);

                    // Tambahkan marker ke peta
                    L.marker(posisi, {
                        icon: logo
                    }).addTo(map);
                }
            }

            loadPembangunan();
        });
    </script>
@endpush
