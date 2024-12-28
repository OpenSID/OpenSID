@extends('theme::layouts.full-content')
@include('theme::commons.asset_peta')

@section('content')
    <div class="single_category wow fadeInDown">
        <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Lapak</span></h2>
    </div>

    <div class="box box-primary">
        <div class="box-body">
            <form id="form-cari" class="form-inline text-center">
                <div class="row">
                    <div class="col-sm-12">
                        <select class="form-control select2" id="id_kategori" name="id_kategori">
                            <option selected value="">Semua Kategori</option>
                        </select>
                        <input type="text" id="search" name="search" maxlength="50" class="form-control" placeholder="Cari Produk">
                        <button type="button" id="btn-cari" class="btn btn-primary">Cari</button>
                        <button type="button" id="btn-semua" class="btn btn-success" style="display: none;">Tampil Semua</button>
                    </div>
                </div>
            </form>
        </div>
        <br />

        <div class="row" id="produk-list">
        </div>
    </div>

    @include('theme::commons.pagination')

    <div class='modal fade' id="modalLokasi" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title'></h4>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
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
                        var fotoHTML = '<div class="slick_slider" style="margin-bottom:5px; max-height: 250px;">';
                        var fotoList = item.attributes.foto;

                        fotoList.forEach(function(fotoItem) {
                            fotoHTML += `
                            <div class="item slick-slide">
                                <div class="single_item">
                                    <img class="tlClogo" src="${fotoItem}" alt="Foto Produk" class="h-44 w-full object-cover object-center bg-gray-300">
                                </div>
                            </div>
                        `;
                        });

                        fotoHTML += '</div>';

                        var hargaDiskon = formatRupiah(item.attributes.harga_diskon, 'Rp ');
                        var hargaAwal = formatRupiah(item.attributes.harga, 'Rp ');
                        var viewDiskon = (hargaAwal === hargaDiskon) ? `` : `<s class="text-xs text-red-500">${hargaAwal}</s>`;

                        var produkHTML = `
                    <div class="col-md-4" style="margin-bottom: 20px;">
                        <div class="card mb-4 box-shadow" style="border: 1px solid #e2e8f0; border-radius: 5px;">
                            ${fotoHTML}
                            <div class="card-body">
                                <h4><b>${item.attributes.nama}</b></h4>
                                <h6><b style="color:green;">Harga: ${hargaDiskon} <small style="color:red; text-decoration: line-through;">${hargaAwal}</small></b></h6>
                                <p class="card-text"><b>Deskripsi:</b><br>${item.attributes.deskripsi}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-success" href="${item.attributes.pesan_wa}" rel="noopener noreferrer" target="_blank" title="WhatsApp">
                                            <i class="fa fa-whatsapp"></i> Beli
                                        </a>
                                        <button class="btn btn-sm btn-primary lokasi-pelapak" data-remote="false" data-toggle="modal"
                                            data-target="#modalLokasi" title="Lokasi" data-lat="${item.attributes.pelapak.lat}"
                                            data-lng="${item.attributes.pelapak.lng}" data-zoom="${item.attributes.pelapak.zoom}"
                                            data-title="Lokasi ${item.attributes.pelapak.penduduk.nama}">
                                            <i class="fa fa-map"></i> Lokasi
                                        </button>
                                    </div>
                                    <small class="text-muted"><b><i class="fa fa-user"></i> ${item.attributes.pelapak.penduduk.nama ?? 'Admin'}</b></small>
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
