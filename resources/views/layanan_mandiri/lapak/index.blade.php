@extends('layanan_mandiri.layouts.index')

@push('css')
    <style type="text/css">
        .modal-backdrop.in {
            filter: alpha(opacity=50);
            opacity: 0;
        }
    </style>
@endpush

@section('content')
    <div class="box box-solid">
        <div class="box-header with-border bg-aqua">
            <h4 class="box-title">Lapak</h4>
        </div>
        <div class="box-header with-border">
            <form method="get" class="form-inline text-center">
                <select class="form-control" id="id_kategori" name="id_kategori">
                    <option selected value="">Semua Kategori</option>
                    @foreach ($kategori as $kategori_item)
                        <option value="{{ $kategori_item->id }}" {{ $id_kategori == $kategori_item->id ? 'selected' : '' }}>
                            {{ $kategori_item->kategori }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="keyword" maxlength="50" class="form-control" value="{{ $keyword }}" placeholder="Cari Produk">
                <button type="submit" class="btn btn-primary">Cari</button>
                @if ($keyword || $id_kategori)
                    <a href="{{ base_url('layanan-mandiri/lapak') }}" class="btn btn-info">Tampilkan Semua</a>
                @endif
            </form>
        </div>
        <div class="box-body">
            <div class="row" style="padding: 0px 20px;">
                @forelse ($produk as $in => $pro)
                    <?php $foto = json_decode($pro->foto); ?>
                    <div class="col-md-4">
                        <div class="card mb-4 box-shadow">
                            @if ($pro->foto)
                                <div id="carousel-produk{{ $in }}" class="carousel slide" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                        @for ($i = 0; $i < setting('banyak_foto_tiap_produk'); $i++)
                                            @if (!empty($foto[$i]))
                                                <li data-target="#carousel-produk{{ $in }}" data-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}"></li>
                                            @endif
                                        @endfor
                                    </ol>

                                    <div class="carousel-inner">
                                        @for ($i = 0; $i < setting('banyak_foto_tiap_produk'); $i++)
                                            @if (!empty($foto[$i]))
                                                <div class="item {{ $i == 0 ? 'active' : '' }}">
                                                    @if (is_file(LOKASI_PRODUK . $foto[$i]))
                                                        <img class="image-produk card-img-top" src="{{ base_url(LOKASI_PRODUK . $foto[$i]) }}" alt="Produk {{ $i + 1 }}">
                                                    @else
                                                        <img class="card-img-top" style="width: auto; max-height: 170px;" src="{{ asset('images/404-image-not-found.jpg') }}" alt="Foto Produk">
                                                    @endif
                                                </div>
                                            @endif
                                        @endfor
                                    </div>
                                    <a class="left carousel-control" href="#carousel-produk{{ $in }}" data-slide="prev">
                                        <span class="fa fa-angle-left"></span>
                                    </a>
                                    <a class="right carousel-control" href="#carousel-produk{{ $in }}" data-slide="next">
                                        <span class="fa fa-angle-right"></span>
                                    </a>
                                </div>
                            @else
                                <img class="card-img-top" style="width: auto; max-height: 170px;" src="{{ asset('images/404-image-not-found.jpg') }}" alt="Foto Produk">
                            @endif

                            <div class="card-body">
                                <h4><b>{{ $pro->nama }}</b></h4>
                                <?php $harga_potongan = $pro->tipe_potongan == 1 ? $pro->harga * ($pro->potongan / 100) : $pro->potongan; ?>
                                <h6><b style="color:green;">Harga : {{ rupiah($pro->harga - $harga_potongan) }}
                                        @if ($pro->potongan != 0)
                                            &nbsp;&nbsp;<small style="color:red; text-decoration: line-through red;">{{ rupiah($pro->harga) }}</small>
                                        @endif
                                    </b></h6>
                                <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 30px;">
                                    <div class="btn-group">
                                        @if ($pro->telepon)
                                            <?php $pesan = strReplaceArrayRecursive(['[nama_produk]' => $pro->nama, '[link_web]' => base_url('lapak'), '<br />' => '%0A'], nl2br(setting('pesan_singkat_wa'))); ?>
                                            <a class="btn btn-sm btn-success" href="https://api.whatsapp.com/send?phone={{ format_telpon($pro->telepon) }}&amp;text={{ $pesan }}" rel="noopener noreferrer" target="_blank" title="WhatsApp">
                                                <i class="fa fa-whatsapp"></i> Beli
                                            </a>
                                        @endif
                                        <a
                                            class="btn btn-sm btn-warning lokasi-pelapak"
                                            data-remote="false"
                                            data-toggle="modal"
                                            data-target="#map-modal"
                                            title="Lokasi"
                                            data-lat="{{ $pro->lat }}"
                                            data-lng="{{ $pro->lng }}"
                                            data-zoom="{{ $pro->zoom }}"
                                            data-title="Lokasi Pelapak ({{ $pro->pelapak }})"
                                        ><i class="fa fa fa-map"></i> Lokasi</a>
                                        <a class="btn btn-sm btn-primary text-white" data-remote="false" data-toggle="modal" data-target="#descModal{{ $in }}" title="Deskripsi"><i class="fa fa-info-circle"></i> Deskripsi</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Deskripsi -->
                    <div class="modal fade" id="descModal{{ $in }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog box-map">
                            <div class="modal-content-map">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title"><b>{{ $pro->nama }}</b></h4>
                                </div>
                                <div class="modal-body">
                                    <p class="card-text" style="margin-bottom: 25px;">
                                        <b>Deskripsi:</b>
                                        <br>
                                        {!! nl2br(e($pro->deskripsi)) !!}
                                    </p>
                                    <p>
                                        <b><i class="fa fa-user"></i>&nbsp;{{ $pro->pelapak ?? 'ADMIN' }}</b><br>
                                        <b>Kontak:</b>&nbsp;{{ $pro->telepon ?? 'ADMIN' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center">
                        <h5>Belum ada produk yang ditawarkan.</h5>
                    </div>
                @endforelse
            </div>
            <div class="text-center">
                {{ $produk->links('admin.layouts.components.pagination_default') }}
            </div>

            <!-- Modal lokasi -->
            <div class="modal fade" id="map-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog box-map">
                    <div class="modal-content-map">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="<?= asset('js/mapbox-gl.js') ?>"></script>
    <script src="<?= asset('js/leaflet.js') ?>"></script>
    <script src="<?= asset('js/leaflet-providers.js') ?>"></script>
    <script src="<?= asset('js/leaflet-mapbox-gl.js') ?>"></script>
    <script src="<?= asset('js/peta.js') ?>"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var MAPBOX_KEY = '<?= setting('mapbox_key') ?>';
            var JENIS_PETA = '<?= setting('jenis_peta') ?>';

            var options = {
                maxZoom: '<?= setting('max_zoom_peta') ?>',
                minZoom: '<?= setting('min_zoom_peta') ?>',
                fullscreenControl: {
                    position: 'topright' // Menentukan posisi tombol fullscreen
                }
            };

            $(document).on('shown.bs.modal', '#map-modal', function(event) {
                let link = $(event.relatedTarget);
                let title = link.data('title');
                let modal = $(this);
                modal.find('.modal-title').text(title);
                modal.find('.modal-body').html("<div id='map' style='width: 100%; height:300px;'></div>");

                let posisi = [link.data('lat'), link.data('lng')];
                let zoom = link.data('zoom');
                let logo = L.icon({
                    iconUrl: "<?= setting('icon_lapak_peta') ?>",
                });

                $("#lat").val(link.data('lat'));
                $("#lng").val(link.data('lng'));

                pelapak = L.map('map', options).setView(posisi, zoom);
                getBaseLayers(pelapak, MAPBOX_KEY, JENIS_PETA);

                pelapak.addLayer(new L.Marker(posisi, {
                    icon: logo
                }));
            });
        });
    </script>
@endpush
