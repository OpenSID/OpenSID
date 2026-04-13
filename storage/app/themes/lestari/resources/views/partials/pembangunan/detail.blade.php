@extends('theme::layouts.full-content')
@include('theme::commons.asset_peta')

@section('content')
    	@include('theme::partials.header')
    <div class="contentpage">
		<div class="margin-page">
			<div class="head-module align-center">
				<h1><span class="title_text judul-pembangunan"></span></h1>
			</div>
		</div>
		<div class="mt-20 margin-page pembangunan">
			<div class="article-grid" id="detail-pembangunan"></div>
			<div class="mt-20 flex-center">
			@include('theme::commons.share', [
				'link' => site_url('pembangunan/' . $pembangunan->slug),
				'judul' => $pembangunan->judul,
			])
			</div>
		</div>
		
		@include('theme::partials.modulepage')
		@include('theme::partials.footer')
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
                    <div class="column2 box-shadow brd-10">
                            <div class="head-block">Data Pembangunan</div>
                            <div class="panel-body">
                                <div class="image-box mb-20">
								<div class="image-default">
                                    <img src="${pembangunan.foto ?? notFound}" alt="${pembangunan.slug}"/>
                                </div>
                                 </div>
                                <table class="table table-striped table-data">
                                    <tr>
                                        <td width="150px">Nama Kegiatan</td>
                                        <td style="text-align:center;width:20px;">:</td>
                                        <td>${pembangunan.judul}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td style="text-align:center;width:20px;">:</td>
                                        <td>${pembangunan.alamat}</td>
                                    </tr>
                                    <tr>
                                        <td>Sumber Dana</td>
                                        <td style="text-align:center;width:20px;">:</td>
                                        <td>${pembangunan.sumber_dana}</td>
                                    </tr>
                                    <tr>
                                        <td>Anggaran</td>
                                        <td style="text-align:center;width:20px;">:</td>
                                        <td>${anggaran}</td>
                                    </tr>
                                    <tr>
                                        <td>Volume</td>
                                        <td style="text-align:center;width:20px;">:</td>
                                        <td>${pembangunan.volume}</td>
                                    </tr>
                                    <tr>
                                        <td>Pelaksana</td>
                                        <td style="text-align:center;width:20px;">:</td>
                                        <td>${pembangunan.pelaksana_kegiatan}</td>
                                    </tr>
                                    <tr>
                                        <td>Tahun</td>
                                        <td style="text-align:center;width:20px;">:</td>
                                        <td>${pembangunan.tahun_anggaran}</td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan</td>
                                        <td style="text-align:center;width:20px;">:</td>
                                        <td>${pembangunan.keterangan}</td>
                                    </tr>
                                </table>
                            </div>
                     
                    </div>
                `;

                    // Dokumentasi Pembangunan
                    var gambarDokumentasi = '';

                    if (dokumentasi && dokumentasi.length > 0) {
                        dokumentasi.forEach((dok) => {
                            gambarDokumentasi += `
                            <div class="column2 align-center mt-20 doc-pemb">
								<a href="${dok.gambar ?? notFound}"  data-fancybox="images">
								<div class="image-article imagefull brd-10">
                                <img src="${dok.gambar ?? notFound}" alt="Foto Pembangunan ${dok.persentase}" />
								</div>
								</a>
                                <p>Foto Pembangunan ${dok.persentase}</p>
                            </div>`;
                        });
                    } else {
                        gambarDokumentasi += `
                        <div class="col-sm-6 text-center">
                            <p>Belum ada dokumentasi pembangunan yang tersedia.</p>
                        </div>`;
                    }

                    pembangunanHTML += `
                <div class="column2 box-shadow brd-10">
                        <div class="head-block">Progres Pembangunan</div>
                        <div style="margin:0 25px!important;">
                            <div class="article-grid">
                                ${gambarDokumentasi}
                            </div>
                        </div>
						<div class="head-block mt-20" style="border-radius:0!important;">Lokasi Pembangunan</div>
                        <div class="panel-body" id="map-pembangunan">
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
