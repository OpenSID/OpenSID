@extends('theme::layouts.full-content')
@include('theme::commons.asset_peta')


@section('content')
    @include('theme::partials.header')
	<div class="contentpage">
		<div class="margin-page">
			<div class="head-module align-center">
				<h1>Lapak {{ ucwords(setting('sebutan_desa')) }}</h1>
			</div>
		</div>
		<div class="mt-20 margin-page lapak">
			<form id="form-cari" class="text-center">
				 <div class="row">
				 <div class="col-sm-12">
					<div class="form-module flex-center">
						<div class="form-module-inner">
							<select class="form-control select2" id="id_kategori" name="id_kategori">
								<option selected value="">Semua Kategori</option>
							</select>
						</div>
						<div class="form-module-inner flex-center">
							<input type="text" id="search" name="search" maxlength="50" class="form-control" placeholder="Cari Produk">
							<button type="button" id="btn-cari" class="btn btn-primary" style="margin-left:5px;">Cari</button>
						</div>	
						<div class="form-module-inner">
							<button type="button" id="btn-semua" class="btn btn-success" style="display: none;">Tampil Semua</button>
						</div>
					</div>
				</div>
				</div>
            </form>
			<div class="article-grid" id="produk-list"></div>
		@include('theme::commons.pagination')
		</div>
		
		@include('theme::partials.modulepage')
		@include('theme::partials.footer')
	</div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var apiKategori = '{{ route('api.lapak.kategori') }}';
            $.get(apiKategori, function(data) {
                var kategori = data.data;
                var select = $('#id_kategori');
                kategori.forEach(function(item) {
                    select.append('<option value="' + item.id + '">' + item.attributes.kategori + '</option>');
                });
            });

            function loadProduk(params = {}) {

                var apiProduk = '{{ route('api.lapak.produk') }}';

                $('#pagination-container').hide();

                $.get(apiProduk, params, function(data) {
                    var produk = data.data;
                    var produkList = $('#produk-list');

                    produkList.empty();

                    if (!produk.length) {
                        produkList.html('<p class="text-center">Tidak ada produk yang ditemukan.</p>');
                        return;
                    }

                    produk.forEach(function(item) {
                        var fotoHTML = '<div class="image-article" style="border-radius:10px;">';
                        var fotoList = item.attributes.foto;

                        fotoList.forEach(function(fotoItem) {
                            fotoHTML += `
						<div class="imagefull">
                                    <img src="${fotoItem}" alt="Foto Produk">
                                </div>
                        `;
                        });

                        fotoHTML += '</div>';

                        var hargaDiskon = formatRupiah(item.attributes.harga_diskon, 'Rp ');
                        var hargaAwal = formatRupiah(item.attributes.harga, 'Rp ');
                        var viewDiskon = (hargaAwal === hargaDiskon) ? `` : `<s class="text-xs text-red-500">${hargaAwal}</s>`;

                        var produkHTML = `
                    <div class="column3 box-shadow brd-10 mt-20">
						<div style="padding:5px;">
							<div class="carousel" data-flickity='{"pageDots": false, "autoPlay": false, "cellAlign": "left", "wrapAround": true }'>
							<div class="carousel-cell">
                            ${fotoHTML}
							</div>
							</div>
                            <div class="card-body align-center">
							<div class="lapak-detail">
                                <h2>${item.attributes.nama}</h2>
                                <p>${hargaDiskon}<br/><font style="color:red; text-decoration: line-through;">${hargaAwal}</font></p>
                                <h3>Deskripsi:</h3>
								<p>${item.attributes.deskripsi}</p>
                                <div class="d-flex justify-content-between align-items-center" style="margin:10px 0;">
                                    <div class="btn-group flex-center" style="margin:0 0 10px;">
                                        <a class="btn btn-sm btn-success" href="${item.attributes.pesan_wa}" rel="noopener noreferrer" target="_blank" title="WhatsApp" style="margin:0 3px;"><i class="fa fa-whatsapp"></i> Beli</a>
                                        <button class="btn btn-sm btn-primary lokasi-pelapak" data-remote="false" data-toggle="modal" data-target="#modalLokasi" title="Lokasi" data-lat="${item.attributes.pelapak.lat}"data-lng="${item.attributes.pelapak.lng}" data-zoom="${item.attributes.pelapak.zoom}" data-title="Lokasi ${item.attributes.pelapak.penduduk.nama}" style="margin:0 3px;"><i class="fa fa-map"></i> Lokasi</button>
                                    </div>
                                    <div class="pelapak"><i class="fa fa-user"></i> Pelapak :  ${item.attributes.pelapak.penduduk.nama ?? 'Admin'}</div>
                                </div>
                            </div>
							</div>
						</div>	
                    </div>
                    `;

                        produkList.append(produkHTML);
                    });

                    $('.slick_slider').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: true,
                        prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
                        nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>',
                        dots: true,
                        infinite: true,
                        autoplay: true,
                        autoplaySpeed: 2000,
                        responsive: [{
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                                arrows: false
                            }
                        }]
                    });

                    initPagination(data);

                    $('.slick_slider').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: true,
                        dots: true,
                        prevArrow: '<button type="button" class="slick-prev">Previous</button>',
                        nextArrow: '<button type="button" class="slick-next">Next</button>',
                        responsive: [{
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }]
                    });
                });
            }

            $('#btn-cari').on('click', function() {
                var params = {};
                var kategori = $('#id_kategori').val();
                var search = $('#search').val();

                if (kategori) {
                    params['filter[id_produk_kategori]'] = kategori;
                }

                if (search) {
                    params['filter[search]'] = search;
                }

                console.log(params);


                loadProduk(params);

                $('#btn-semua').show();
            });

            $('.pagination').on('click', '.btn-page', function() {
                var params = {};
                var page = $(this).data('page');
                var kategori = $('#id_kategori').val();
                var search = $('#search').val();

                if (kategori) {
                    params['filter[id_produk_kategori]'] = kategori;
                }

                if (search) {
                    params['filter[search]'] = search;
                }

                params['page[number]'] = page;

                loadProduk(params);
            });

            $('#btn-semua').on('click', function() {
                loadProduk();
                $('#btn-semua').hide();
                $('#search').val('');
                $('#id_kategori').val('');
            });

            $('#search').keypress(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    $('#btn-cari').trigger('click');
                }
            });

            loadProduk();

            $('#modalLokasi').on('shown.bs.modal', function(event) {
                const link = $(event.relatedTarget);
                const modal = $(this);

                modal.find('.modal-title').text(link.data('title'));
                modal.find('.modal-body').html("<div id='map' style='width: 100%; height:350px'></div>");

                const posisi = [link.data('lat'), link.data('lng')];
                const zoom = link.data('zoom') || 10;
                const popupContent = link.closest('.this-product').find('.detail').html();

                const mapOptions = {
                    maxZoom: setting.max_zoom_peta,
                    minZoom: setting.min_zoom_peta
                };

                $('#lat').val(posisi[0]);
                $('#lng').val(posisi[1]);

                if (window.pelapak) {
                    window.pelapak.remove();
                }

                window.pelapak = L.map('map', mapOptions).setView(posisi, zoom);
                getBaseLayers(window.pelapak, setting.mapbox_key, setting.jenis_peta);

                const markerIcon = L.icon({
                    iconUrl: setting.icon_lapak_peta
                });

                L.marker(posisi, {
                    icon: markerIcon
                }).addTo(window.pelapak).bindPopup(`
                <div class="card">
                    <div class="text-xs">
                        <div class="py-1 space-y-1/2 text-sm flex flex-col">
                            ${popupContent}
                        </div>
                    </div>
                </div>
            `);

                L.control.scale().addTo(window.pelapak);

                window.pelapak.invalidateSize();
            });
        });
    </script>
@endpush