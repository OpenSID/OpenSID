@extends('theme::layouts.full-content')
@include('theme::commons.asset_peta')

@section('content')
    <nav role="navigation" aria-label="navigation" class="breadcrumb">
        <ol>
            <li><a href="<?= site_url() ?>">Beranda</a></li>
            <li aria-current="page">Lapak</li>
        </ol>
    </nav>
    <h1 class="text-h2"><i class="fas fa-store mr-1"></i> Lapak</h1>
    <form id="form-cari" class="w-full block py-4">
        <div class="flex gap-3 lg:w-7/12 flex-col lg:flex-row">
            <select class="form-input inline-block select2" id="id_kategori" name="id_kategori" style="min-width: 25%">
                <option selected value="">Semua Kategori</option>
            </select>
            <input
                type="text"
                id="search"
                name="search"
                maxlength="50"
                class="form-input"
                placeholder="Cari Produk"
                style="min-width: 35%"
            >
            <button type="button" id="btn-cari" class="btn btn-primary flex-shrink-0 text-center">Cari</button>
            <button type="button" id="btn-semua" class="btn btn-info flex-shrink-0 text-center" style="display: none;">Tampil Semua</button>
        </div>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 py-1" id="produk-list">
    </div>

    @include('theme::commons.pagination')

    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="modalLokasi" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                    <h5 class="text-h6">Lokasi Penjual</h5>
                    <button type="button" class="btn-close text-black text-sm leading-none focus:outline-none" data-bs-dismiss="modal" aria-label="Close">
                        &times;
                    </button>
                </div>
                <div class="modal-body p-4">
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
                        produkList.html('<p class="py-2">Tidak ada produk yang tersedia</p>');
                        return;
                    }

                    produk.forEach(function(item) {
                        var fotoHTML = '<div class="owl-carousel">';
                        var fotoList = item.attributes.foto;

                        fotoList.forEach(function(fotoItem) {
                            fotoHTML += `<div class="item"><img src="${fotoItem}" alt="Foto Produk" class="h-44 w-full object-cover object-center bg-gray-300"></div>`;
                        });

                        fotoHTML += '</div>';

                        var hargaDiskon = formatRupiah(item.attributes.harga_diskon, 'Rp ');
                        var hargaAwal = formatRupiah(item.attributes.harga, 'Rp ');
                        var viewDiskon = (hargaAwal === hargaDiskon) ? `` : `<s class="text-xs text-red-500">${hargaAwal}</s>`;

                        var produkHTML = `
                        <div class="flex flex-col justify-between space-y-4 this-product">
                            <div class="space-y-3">
                                ${fotoHTML}
                                <div class="space-y-1/2 text-sm flex flex-col detail">
                                    <span class="font-heading font-medium">${item.attributes.nama}</span>
                                    ${viewDiskon}
                                    <span class="text-lg font-bold">${hargaDiskon} <span class="text-xs font-thin">/ ${item.attributes.satuan}</span></span>
                                    <p class="text-xs pt-1">${item.attributes.deskripsi}</p>
                                    <span class="pt-2 text-xs font-bold text-gray-500 dark:text-gray-50">
                                        <i class="fas fa-award mr-1"></i> ${item.attributes.pelapak.penduduk.nama ?? 'Admin'} <i class="fas fa-check text-xs bg-green-500 h-4 w-4 inline-flex items-center justify-center rounded-full text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="group flex items-center space-x-1">
                                <a href="${item.attributes.pesan_wa}" 
                                    rel="noopener noreferrer" target="_blank" class="btn btn-primary text-xs text-center">
                                    <i class="fa fa-shopping-cart mr-1"></i> Beli Sekarang
                                </a>
                                <button type="button" class="btn btn-secondary text-xs text-center rounded-0" data-bs-toggle="modal"
                                    data-bs-target="#modalLokasi" data-bs-remote="false" title="Lokasi" data-lat="${item.attributes.pelapak.lat}"
                                    data-lng="${item.attributes.pelapak.lng}" data-zoom="${item.attributes.pelapak.zoom}" data-title="Lokasi ${item.attributes.pelapak.penduduk.nama}"><i
                                        class="fas fa-map-marker-alt mr-1"></i> Lokasi</button>
                            </div>
                        </div>
                    `;

                        produkList.append(produkHTML);
                    });

                    initPagination(data);

                    $('.owl-carousel').owlCarousel({
                        items: 1,
                        loop: true,
                        margin: 10,
                        nav: false,
                        dots: true,
                        autoplay: true,
                        autoplayTimeout: 3000
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
