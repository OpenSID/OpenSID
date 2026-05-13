@extends('theme::layouts.full-content')

@section('content')
<div class="heading-module l-flex">
	<div class="heading-module-inner l-flex">
		<i class="fa fa-shopping-basket"></i><h1>Lapak {{ ucwords(setting('sebutan_desa')) }} </h1>
	</div>
</div>
<div class="lapak box-def">
    <div class="box-def-inner">        
        <form method="get" class="form-inline text-center" id="form-cari">
			<div class="row">
				<div class="col-sm-12">
					<select class="form-control select2" id="id_kategori" name="id_kategori">
						<option selected value="">Semua Kategori</option>						
					</select>
					<input type="text" id="search" name="keyword" maxlength="50" class="form-control" placeholder="Cari Produk">
					<button type="button" id="btn-cari" class="btn btn-primary btn-sm">Cari</button>
                    <button type="button" id="btn-semua" class="btn btn-info btn-sm"
                    style="display: none;">Tampil Semua</button>					
				</div>
			</div>
		</form>

        <div class="row-custom mlr-min5 mt-20" id="produk-list">
        </div>

        @include('theme::commons.pagination')

        <div class='modal fade' id="modalLokasi" tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
            aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                        <h4 class='modal-title'></h4>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        var apiKategori = '{{ route("api.lapak.kategori") }}';
        $.get(apiKategori, function (data) {
            var kategori = data.data;
            var select = $('#id_kategori');
            kategori.forEach(function (item) {
                select.append('<option value="' + item.id + '">' + item.attributes.kategori + '</option>');
            });
        });

        function loadProduk(params = {}) {
            
            var apiProduk = '{{ route("api.lapak.produk") }}';

            $('#pagination-container').hide();

            $.get(apiProduk, params, function (data) {
                var produk = data.data;
                var produkList = $('#produk-list');

                produkList.empty();

                if (!produk.length) {
                    produkList.html(`<div class="box-def hoverstyle">
                                        <div class="emptydata c-flex">
                                            <div>
                                            <svg viewBox="0 0 24 24"><path d="M13 13H11V7H13M11 15H13V17H11M15.73 3H8.27L3 8.27V15.73L8.27 21H15.73L21 15.73V8.27L15.73 3Z" /></svg>
                                            <p>Mohon maaf, untuk saat ini data belum tersedia...!</p>
                                            </div>
                                        </div>
                                    </div>`);
                    return;
                }

                produk.forEach(function (item) {
                    var fotoHTML = `<div class="carousel js-flickity" data-flickity='{ "autoPlay": false, "cellAlign": "left"}'>`;
                    var fotoList = item.attributes.foto;

                    fotoList.forEach(function (fotoItem, index) {
                        fotoHTML += `<div class="carousel-col">
                                        <div class="item ${index == 0 ? 'active': ''}">
                                            <div class="image-slider2">                                                
                                                <img src="${fotoItem}" alt="Foto ${index+1}">                                                
                                            </div>
                                        </div>                                    
                                    </div>`;                                                
                    });
                    fotoHTML += '</div>';
                    if(fotoList.length <= 0){
                        fotoHTML = `<div class="image-slider2"><img src="{{ theme_asset("images/pengganti.jpg")}}"/></div>`;
                    }
                    var hargaDiskon = formatRupiah(item.attributes.harga_diskon, 'Rp ');
                    var hargaAwal = formatRupiah(item.attributes.harga, 'Rp ');
                    var viewDiskon = (hargaAwal === hargaDiskon) ? `` : `<h3 style="text-decoration: line-through red;color:#ff0000;">${hargaAwal}</h3>`;

                    var produkHTML = `
                        <div class="column-4 box-def">
                            <div class="box-def-inner2">
                                ${fotoHTML}
                                <div class="lapak-detail">
                                    <h2>${item.attributes.nama}</h2>                                    
                                    <div class="c-flex">
                                        <div>
                                            ${viewDiskon}
                                        <h3 style="color:#009a0a;"><b>${hargaDiskon}</b></h3>
                                        </div>
                                    </div>
                                    <p style="margin:10px 0;">${item.attributes.deskripsi}</p>
                                    <div class="c-flex"><i class="fa fa-user" style="margin:0 5px 0 0;opacity:0.6;"></i><p>${item.attributes.pelapak.penduduk.nama ?? 'Admin'}</p></div>
                                    <div class="c-flex" style="margin:10px 0 5px;">
                                        <a style="margin:0 3px;" class="btn btn-sm btn-success" href="${item.attributes.pesan_wa}" rel="noopener noreferrer" target="_blank" title="WhatsApp"><i class="fa fa-whatsapp"></i> Beli</a>                                    
                                        <a style="margin:0 3px;" class="btn btn-sm btn-primary" data-remote="false" data-toggle="modal" data-target="#modalLokasi" title="Lokasi" data-lat="${item.attributes.pelapak.lat}" data-lng="${item.attributes.pelapak.lng}" data-zoom="${item.attributes.pelapak.zoom}" data-title="Lokasi Pelapak (${item.attributes.pelapak.penduduk.nama})"><i class="fa fa fa-map"></i> Lokasi</a>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    `;

                    produkList.append(produkHTML);
                });
                
                $('.carousel.js-flickity').flickity({
                    "autoPlay": false, "cellAlign": "left"
                });
                initPagination(data);                
            });
        }

        $('#btn-cari').on('click', function () {
            var params = {};
            var kategori = $('#id_kategori').val();
            var search = $('#search').val();

            if (kategori) {
                params['filter[id_produk_kategori]'] = kategori;
            }

            if (search) {
                params['filter[search]'] = search;
            }
            
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

        $('#btn-semua').on('click', function () {
            loadProduk();
            $('#btn-semua').hide();
            $('#search').val('');
            $('#id_kategori').val('');
        });

        $('#search').keypress(function (e) {
            if (e.which == 13) {
                e.preventDefault();
                $('#btn-cari').trigger('click');
            }
        });

        loadProduk();

        $('#modalLokasi').on('shown.bs.modal', function (event) {
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

            L.marker(posisi, { icon: markerIcon }).addTo(window.pelapak);

            L.control.scale().addTo(window.pelapak);

            window.pelapak.invalidateSize();
        });
    });
</script>
@endpush