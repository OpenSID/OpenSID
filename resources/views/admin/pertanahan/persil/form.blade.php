@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Pengelolaan Data Persil
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('data_persil') }}"> Daftar Persil</a></li>
    <li class="active">Pengelolaan Data Persil</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('data_persil') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Persil
            </a>
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box-body">
                <input type="hidden" name="id_persil" value="{{ $persil['id'] }}">
                <div class="form-group">
                    <label for="no_persil" class="col-sm-3 control-label">No. Persil</label>
                    <div class="col-sm-8">
                        <input name="no_persil" class="form-control input-sm angka required" type="text" placeholder="Nomor Surat Persil" name="nama" value="{{ $persil['nomor'] }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_persil" class="col-sm-3 control-label">No. Urut Bidang</label>
                    <div class="col-sm-8">
                        <input name="nomor_urut_bidang" class="form-control input-sm angka required" type="text" placeholder="Nomor urut untuk bidang tanah dengan nomor persil sama" value="{{ $persil['nomor_urut_bidang'] }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="kelas" class="col-sm-3 control-label">Tipe Tanah</label>
                    <div class="col-sm-4">
                        <select class="form-control input-sm select2" id="tipe" name="tipe">
                            placeholder="Tuliskan Kelas Tanah">
                            <option value>-- Pilih Tipe Tanah--</option>
                            <option value="BASAH" @selected('BASAH' == $tipe_tanah)>
                                Tanah Basah</option>
                            <option value="KERING" @selected('KERING' == $tipe_tanah)>
                                Tanah Kering</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="kelas" class="col-sm-3 control-label">Kelas Tanah</label>
                    <div class="col-sm-4">
                        <select class="form-control input-sm required select2" name="kelas">
                            <option value="">-- Pilih Jenis Kelas--</option>
                            @foreach ($persil_kelas as $key => $group)
                                <optgroup label="{{ $key }}">
                                    @foreach ($group as $item)
                                        <option value="{{ $item['id'] }}" @selected($item['id'] == $persil['kelas'])>{{ $item['kode'] . ' ' . $item['ndesc'] }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="luas_persil" class="col-sm-3 control-label">Luas Persil Keseluruhan (M2)</label>
                    <div class="col-sm-4">
                        <input
                            name="luas_persil"
                            class="form-control input-sm number required"
                            type="number"
                            min="1"
                            max="9999999"
                            placeholder="Luas persil secara keseluruhan (M2)"
                            value="{{ $persil['luas_persil'] }}"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="kelas" class="col-sm-3 control-label">Pemilik Awal</label>
                    <div class="col-sm-4">
                        <select class="form-control input-sm required select2" id="kelas" name="cdesa_awal" type="text" @disabled($persil) placeholder="C-Desa pemilik awal persil ini">
                            <option value="">-- Pilih C-Desa Pemilik Awal --</option>
                            @foreach ($list_cdesa as $cdesa)
                                <option value="{{ $cdesa['id'] }}" @selected(($id_cdesa && $id_cdesa == $cdesa->id) || $cdesa->id == $persil['cdesa_awal'])>{{ $cdesa->nomor . ' - ' . $cdesa->nama_pemilik }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group ">
                    <label for="jenis_lokasi" class="col-sm-3 control-label">Lokasi Tanah</label>
                    <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                        <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($persil ? $persil['lokasi'] : true)">
                            <input type="radio" name="jenis_lokasi" class="form-check-input" value="1" autocomplete="off" onchange="pilih_lokasi(this.value);"> Pilih Lokasi
                        </label>
                        <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($persil ? $persil['lokasi'] : false))">
                            <input type="radio" name="jenis_lokasi" class="form-check-input" value="2" autocomplete="off" onchange="pilih_lokasi(this.value);"> Tulis Manual
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div id="pilih">
                        <div class="col-sm-4">
                            <select class="form-control input-sm select2 required" id="id_wilayah" name="id_wilayah">
                                <option value=''>-- Pilih Lokasi Tanah--</option>
                                @foreach ($persil_lokasi as $item)
                                    <option value="{{ $item['id'] }}" @selected($item['id'] == $persil['id_wilayah'])>
                                        {{ strtoupper($item['dusun']) }}
                                        {{ empty($item['rw']) ? '' : " - RW {$item['rw']}" }}
                                        {{ empty($item['rt']) ? '' : " / RT {$item['rt']}" }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="manual">
                        <div class="col-sm-8">
                            <textarea id="lokasi" class="form-control input-sm required" type="text" placeholder="Lokasi" name="lokasi" rows="5">{{ $persil['lokasi'] }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="area_tanah" class="col-sm-3 control-label">Peta</label>
                    <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                        <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label active">
                            <input type="radio" name="area_tanah" class="form-check-input" value="1" autocomplete="off">
                            Pilih Area
                        </label>
                        <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label">
                            <input type="radio" name="area_tanah" class="form-check-input" value="2" autocomplete="off">
                            Buat Area
                        </label>
                    </div>
                </div>

                <div class="form-group" id="pilih-area">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-4">
                        <select class="form-control input-sm select2" id="id_peta" name="id_peta">
                            <option value=''>-- Pilih Area--</option>
                            @foreach ($peta as $item)
                                <option value="{{ $item['id'] }}" @selected($item['id'] == $persil['id_peta'])>
                                    {{ $item['nama'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Tampilkan di Peta Website</label>
                    <div class="btn-group col-sm-7" data-toggle="buttons">
                        <label id="ss1" class="btn btn-info btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label @active($persil['is_publik'] == '1')">
                            <input
                                type="radio"
                                name="is_publik"
                                class="form-check-input"
                                type="radio"
                                value="1"
                                @checked($persil['is_publik'] == '1')
                                autocomplete="off"
                            > Ya
                        </label>
                        <label id="ss2" class="btn btn-info btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label @active(!$persil['is_publik'])">
                            <input
                                type="radio"
                                name="is_publik"
                                class="form-check-input"
                                type="radio"
                                value="0"
                                @checked(!$persil['is_publik'])
                                autocomplete="off"
                            > Tidak
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" id="path" name="path" value="{{ $persil['path'] }}">
                        <input type="hidden" id="zoom" name="zoom" value="">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @include('admin.layouts.components.asset_peta')
    <script>
        function pilih_lokasi(pilih) {
            if (pilih == 1) {
                $('#lokasi').val('');
                $('#lokasi').removeClass('required');
                $("#manual").hide();
                $("#pilih").show();
                $('#id_wilayah').addClass('required');
            } else {
                $('#id_wilayah').val('');
                $('#id_wilayah').trigger('change', true);
                $('#id_wilayah').removeClass('required');
                $("#manual").show();
                $('#lokasi').addClass('required');
                $("#pilih").hide();
            }
        }

        var infoWindow;
        $(document).ready(function() {
            // tampilkan map
            @if (!empty($desa['lat']) && !empty($desa['lng']))
                var posisi = [{!! $desa['lat'] . ',' . $desa['lng'] !!}];
                var zoom = {{ $desa['zoom'] ?: 18 }};
            @else
                var posisi = [-1.0546279422758742, 116.71875000000001];
                var zoom = 18;
            @endif

            var peta_area = L.map('map', pengaturan_peta).setView(posisi, zoom);

            //1. Menampilkan overlayLayers Peta Semua Wilayah
            var marker_desa = [];
            var marker_dusun = [];
            var marker_rw = [];
            var marker_rt = [];
            var marker_persil = []

            //OVERLAY WILAYAH DESA
            @if (!empty($desa['path']))
                set_marker_desa(marker_desa, {!! json_encode($desa, JSON_THROW_ON_ERROR) !!}, "{{ ucwords(setting('sebutan_desa')) . ' ' . $desa['nama_desa'] }}", "{{ favico_desa() }}");
            @endif

            // OVERLAY WILAYAH DUSUN
            @if (!empty($dusun_gis))
                set_marker_multi(marker_dusun, '{!! addslashes(json_encode($dusun_gis, JSON_THROW_ON_ERROR)) !!}', '{{ ucwords(setting('sebutan_dusun')) }}', 'dusun', "{{ favico_desa() }}");
            @endif

            // OVERLAY WILAYAH RW
            @if (!empty($rw_gis))
                set_marker(marker_rw, '{!! addslashes(json_encode($rw_gis, JSON_THROW_ON_ERROR)) !!}', 'RW', 'rw', "{{ favico_desa() }}");
            @endif

            // OVERLAY WILAYAH RT
            @if (!empty($rt_gis))
                set_marker(marker_rt, '{!! addslashes(json_encode($rt_gis, JSON_THROW_ON_ERROR)) !!}', 'RT', 'rt', "{{ favico_desa() }}");
            @endif

            // Menampilkan overlayLayers Peta Semua Wilayah
            @if (!empty($wil_atas['path']))
                var overlayLayers = overlayWil(marker_desa, marker_dusun, marker_rw, marker_rt, "{{ ucwords(setting('sebutan_desa')) }}", "{{ ucwords(setting('sebutan_dusun')) }}");
            @else
                var overlayLayers = {};
            @endif

            // Menampilkan BaseLayers Peta
            var baseLayers = getBaseLayers(peta_area, MAPBOX_KEY, JENIS_PETA);

            if ($('input[name="path"]').val() !== '') {
                var wilayah = JSON.parse($('input[name="path"]').val());
                showCurrentArea(wilayah, peta_area, TAMPIL_LUAS);
            }

            // Menambahkan zoom scale ke peta
            L.control.scale().addTo(peta_area);

            @if (can('u'))
                // Export/Import Peta dari file GPX
                eximGpxRegion(peta_area);

                // Import Peta dari file SHP
                eximShp(peta_area);
            @endif

            //Geolocation IP Route/GPS
            geoLocation(peta_area);

            //Menambahkan Peta wilayah
            addPetaPoly(peta_area);

            // deklrasi variabel agar mudah di baca
            var all_area = '{!! addslashes(json_encode($all_area, JSON_THROW_ON_ERROR)) !!}';
            var all_garis = '{!! addslashes(json_encode($all_garis, JSON_THROW_ON_ERROR)) !!}';
            var all_lokasi = '{!! addslashes(json_encode($all_lokasi, JSON_THROW_ON_ERROR)) !!}';
            var all_lokasi_pembangunan = '{!! addslashes(json_encode($all_lokasi_pembangunan, JSON_THROW_ON_ERROR)) !!}';
            var all_persil = '{!! addslashes(json_encode($persil, JSON_THROW_ON_ERROR)) !!}';
            var LOKASI_SIMBOL_LOKASI = '{{ base_url() . LOKASI_SIMBOL_LOKASI }}';
            var favico_desa = '{{ favico_desa() }}';
            var LOKASI_FOTO_AREA = '{{ base_url(LOKASI_FOTO_AREA) }}';
            var LOKASI_FOTO_GARIS = '{{ base_url(LOKASI_FOTO_GARIS) }}';
            var LOKASI_FOTO_LOKASI = '{{ base_url(LOKASI_FOTO_LOKASI) }}';
            var LOKASI_GALERI = '{{ base_url(LOKASI_GALERI) }}';
            var info_pembangunan = '{{ ci_route('pembangunan') }}';

            // Menampilkan OverLayer Area, Garis, Lokasi plus Lokasi Pembangunan
            var layerCustom = tampilkan_layer_area_garis_lokasi_plus(peta_area, all_area, all_garis, all_lokasi, all_lokasi_pembangunan, LOKASI_SIMBOL_LOKASI, favico_desa, LOKASI_FOTO_AREA, LOKASI_FOTO_GARIS, LOKASI_FOTO_LOKASI, LOKASI_GALERI, info_pembangunan, all_persil, TAMPIL_LUAS);

            L.control.layers(baseLayers, overlayLayers, {
                position: 'topleft',
                collapsed: true
            }).addTo(peta_area);
            L.control.groupedLayers('', layerCustom, {
                groupCheckboxes: true,
                position: 'topleft',
                collapsed: true
            }).addTo(peta_area);

            // end tampilkan map

            if ($('select[name="id_peta"]').val() == '') {
                $('input[name="area_tanah"][value="2"]').prop("checked", true).trigger('click').trigger('change')
                $('#pilih-area').hide();
                $('#pilih-area').val(null)
                peta_area.pm.addControls(editToolbarPoly());
            }

            $('#tipe').change(function() {
                let _val = $(this).val()
                $('select[name=kelas]').find('optgroup').prop('disabled', 1)
                $('select[name=kelas]').find(`optgroup[label="${_val}"]`).prop('disabled', 0)

            });

            $('input[name="area_tanah"]').change(function() {
                var pilih = $(this).val();
                if (pilih == 1) {
                    $('#pilih-area').show();
                    // tambahkan toolbar edit polyline
                    peta_area.pm.removeControls(editToolbarPoly());
                } else {
                    peta_area.pm.addControls(editToolbarPoly());
                    $('#pilih-area').hide();
                    $('#pilih-area').val(null)

                }
            });

            $('select[name="id_peta"]').change(function() {
                var id_peta = $(this).val();
                $.ajax({
                        url: '{{ ci_route('data_persil.area_map') }}',
                        type: 'GET',
                        data: {
                            id: id_peta
                        },
                    })
                    .done(function(result) {
                        if (result.status == true) {
                            var wilayah = JSON.parse(result.data.path);
                            clearMap(peta_area);
                            showCurrentArea(wilayah, peta_area, TAMPIL_LUAS);
                        }
                    });
            });
            $('#tipe').trigger('change');
            pilih_lokasi({{ empty($persil['lokasi']) ? 1 : 2 }});
        });
    </script>
    <script src="{{ asset('js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('js/togeojson.js') }}"></script>
@endpush
@push('css')
    <style>
        .select2-results__option[aria-disabled=true] {
            display: none;
        }
    </style>
@endpush
