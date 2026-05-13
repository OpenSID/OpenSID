@extends('theme::layouts.full-content')
@include('theme::commons.asset_peta')
@section('content')
<div id="printableArea">
<div class="single_category wow fadeInDown">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Detail Pembangunan</span></h2>
</div>

<div class="box box-primary">
	<div class="box-body">
		<div class="row">
				<div class="col-sm-6">
					<div class="panel panel-primary">
						<div class="panel-heading">Data Pembangunan</div>
						<div class="panel-body" id="data-pembangunan">
							
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="panel panel-primary">
						<div class="panel-heading">Progres Pembangunan</div>
						<div class="panel-body" id="detail-pembangunan">
							
						</div>
					</div>
				</div>

				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading">Lokasi Pembangunan</div>
						<div class="panel-body" id="map-pembangunan" style="height:300px;max-height:400px;">
						</div>
					</div>
				</div>
			</div>
        </div>
        <div class="share" id="block-share">
            
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        var slug = '{{ $slug }}';
        var link = SITE_URL + 'pembangunan/{{ $slug }}';
        var notFound = '{{ asset("images/404-image-not-found.jpg") }}';

        function loadPembangunan() {
            const apiPembangunan = '{{ route("api.pembangunan") }}';
            const params = { 'filter[slug]': slug };

            $('#detail-pembangunan').html('<p class="text-center">Memuat data pembangunan...</p>');

            $.get(apiPembangunan, params, function (response) {
                var detailPembangunan = $('#detail-pembangunan');
                var dataPembangunan = $('#data-pembangunan')
                detailPembangunan.empty();
                dataPembangunan.empty()

                if (response.data.length !== 1) {
                    detailPembangunan.html('<p">Belum ada progress</p>');
                    dataPembangunan.html('<p>Data pembangunan tidak ditemukan</p>')
                    return;
                }

                const pembangunan = response.data[0].attributes;
                const dokumentasi = pembangunan.pembangunan_dokumentasi;

                $('.judul-pembangunan').text('Detail Pembangunan ' + pembangunan.judul);
                var anggaran = formatRupiah(pembangunan.anggaran, 'Rp ');
                var pembangunanHTML = `
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
                `;                                
                dataPembangunan.html(pembangunanHTML)

                $('#block-share').html(`
                <div class="btn-list">
                    <a name="fb_share" href="http://www.facebook.com/sharer.php?u=${link}" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Facebook'><button type="button" class="btn btn-icon btn-fb"><i class="fa fa-facebook"></i></button></a>
                    <a href="http://twitter.com/share?source=sharethiscom&text=${pembangunan.judul}%0A&url={{ $link .'&via=opensid' }}" class="twitter-share-button" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Twitter'><button type="button" class="btn btn-icon btn-twit"><i class="fa fa-twitter"></i></button></a>
                    <a href="mailto:?subject=${pembangunan.judul}&body=${pembangunan.keterangan} ${link}" title='Email'><button type="button" class="btn btn-icon btn-danger"><i class="fa fa-envelope"></i></button></a>
                    <a href="https://telegram.me/share/url?url=${link}&text=${pembangunan.judul}%0A" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Telegram'><button type="button" class="btn btn-icon btn-telegram"><i class="fa fa-telegram"></i></button></a>
                    <a href="https://api.whatsapp.com/send?text=${pembangunan.judul}%0A${link}" onclick='window.open(this.href,"popupwindow","status=0,height=500,width=500,resizable=0,top=50,left=100");return false;' rel='noopener noreferrer' target='_blank' title='Whatsapp'><button type="button" class="btn btn-icon btn-wa"><i class="fa fa-whatsapp"></i></button></a>
                    <a href="#" onclick="printDiv('printableArea')" title='Cetak Artikel'><button type="button" class="btn btn-icon btn-print"><i class="fa fa-print"></i></button></a>
                </div>`)
                // Dokumentasi Pembangunan
                let dokumentasiHTML = ``;
                
                if (dokumentasi && dokumentasi.length > 0) {
                    dokumentasiHTML += `<div class="row">`;
                    dokumentasi.forEach((dok) => {
                        dokumentasiHTML += `<div class="col-sm-6 text-center">
                                                <img width="auto" class="img-fluid img-thumbnail" src="${dok.gambar ?? notFound}" alt="${pembangunan.slug} - ${dok.persentase}"/>
                                                <b>Foto Pembangunan ${dok.persentase}</b>
                                            </div>`;
                    });
                } 
                dokumentasiHTML += `</div>`;                

                detailPembangunan.html(dokumentasiHTML);

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

                L.marker(posisi, { icon: logo }).addTo(map);
            }
        }

        loadPembangunan();
    });
</script>
@endpush