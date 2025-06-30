@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.datetime_picker')

@section('title')
    <h1>
        Pengelolaan Data C-Desa {{ ucwords(setting('sebutan_desa')) }} {{ $desa['nama_desa'] }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('cdesa') }}"> Daftar C-Desa</a></li>
    <li class="active"> Pengelolaan Data C-Desa</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('cdesa.rincian', $cdesa['id']) }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Rincian C-Desa"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke
                Rincian C-Desa</a>
            @if ($persil)
                <a href="{{ ci_route('cdesa.mutasi.' . $cdesa['id'], $persil['id']) }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Rincian C-Desa"><i class="fa fa-arrow-circle-o-left"></i>
                    Kembali Ke
                    Rincian Mutasi C-Desa</a>
            @endif
        </div>
        <div class="box-body">
            <div class="box-header with-border">
                <h3 class="box-title">Rincian C-Desa</h3>
            </div>
            <div class="box-body">
                <div class="form-horizontal">

                    @if ($cdesa->jenis_pemilik == 1)
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nama Pemilik</label>
                            <div class="col-sm-8">
                                <input class="form-control input-sm" type="text" placeholder="Nama Pemilik" value="{{ $cdesa->nama_pemilik }}" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">NIK Pemilik</label>
                            <div class="col-sm-8">
                                <input class="form-control input-sm" type="text" placeholder="NIK Pemilik" value="{{ $cdesa->nik }}" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="alamat" class="col-sm-3 control-label">Alamat Pemilik</label>
                            <div class="col-sm-8">
                                <textarea class="form-control input-sm" placeholder="Alamat Pemilik" rows="" disabled>{{ $cdesa->alamat }}</textarea>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="c_desa" class="col-sm-3 control-label">Nomor C-DESA</label>
                        <div class="col-sm-8">
                            <input class="form-control input-sm angka required" type="text" placeholder="Nomor Surat C-DESA" name="c_desa" value="{{ $cdesa['nomor'] ? sprintf('%04s', $cdesa['nomor']) : null }}" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nama_kepemilikan" class="col-sm-3 control-label">Nama Pemilik Tertulis
                            di C-Desa</label>
                        <div class="col-sm-8">
                            <input class="form-control input-sm nama required" type="text" placeholder="Nama pemilik di Surat C-DESA" name="nama_kepemilikan" value="{{ $cdesa['nama_kepemilikan'] ? sprintf('%04s', $cdesa['nama_kepemilikan']) : null }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="box-header with-border">
                <h3 class="box-title">Tambah Persil</h3>
            </div>
            <div class="panel box box-default">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#persil">Persil</a>
                    </h4>
                </div>
                <div id="persil" class="panel-collapse">
                    <div class="box-body">
                        <form id="main" name="main" method="POST" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="id_pend">Nomor Persil</label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 input-sm" style="width:100%" onchange="change_persil(this);">
                                        <option>-- Pilih Nomor Persil --</option>
                                        @foreach ($list_persil as $data)
                                            <option
                                                value="{{ $data['id'] }}"
                                                @selected($persil['id'] == $data['id'])
                                                data-nopersil="{{ $data['nomor'] . ' : ' . $data['nomor_urut_bidang'] }}"
                                                data-tipetanah="{{ $data['ref_kelas']['tipe'] }}"
                                                data-kelastanah="{{ $data['ref_kelas']['kode'] }}"
                                                data-luaspersil="{{ $data['luas_persil'] }}"
                                                data-alamat="{{ $data['wilayah']['dusun'] }}"
                                                data-path="{{ $data['path'] }}"
                                            >
                                                {{ $data['nomor'] . ' : ' . $data['nomor_urut_bidang'] . ' - ' . $data['wilayah']['dusun'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group @if (empty($persil)) show @else hide @endif" id="belumAdaPersil">
                                <label class="col-sm-3 control-label">Kalau persil belum ada</label>
                                <div class="col-sm-8">
                                    <a href="{{ ci_route('data_persil.form.0', $cdesa['id']) }}"
                                        class="btn btn-social btn-success btn-sm btn-sm visible-xs-block
                                    visible-sm-inline-block visible-md-inline-block
                                    visible-lg-inline-block" title="Tambah Persil"
                                    >
                                        <i class="fa fa-plus"></i>Tambah Persil
                                    </a>
                                </div>
                            </div>
                            <div id="info-persil" class="@if ($persil) show @else hide @endif">
                                <div class="form-group">
                                    <label for="no_persil" class="col-sm-3 control-label">Nomor Persil : Nomor Urut
                                        Bidang</label>
                                    <div class="col-sm-8">
                                        <input name="no_persil" id="no_persil" class="form-control input-sm angka required" type="text" disabled value="{{ $persil['nomor'] . ' : ' . $persil['nomor_urut_bidang'] }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tipe" class="col-sm-3 control-label">Tipe Tanah</label>
                                    <div class="col-sm-8">
                                        <input name="tipe" id="tipe" class="form-control input-sm angka required" type="text" disabled value="{{ $persil->refKelas['tipe'] }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="kelas" class="col-sm-3 control-label">Kelas Tanah</label>
                                    <div class="col-sm-8">
                                        <input name="kelas" id="kelas" class="form-control input-sm required" type="text" disabled value="{{ $persil->refKelas['kode'] }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="luas_persil" class="col-sm-3 control-label">Luas Persil Keseluruhan
                                        (M2)</label>
                                    <div class="col-sm-8">
                                        <input name="luas_persil" id="luas_persil" class="form-control input-sm angka required" type="text" disabled value="{{ $persil['luas_persil'] }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="alamat" class="col-sm-3 control-label">Lokasi Tanah</label>
                                    <div class="col-sm-8">
                                        <input name="alamat" id="alamat" class="form-control input-sm angka required" type="text" disabled value="{{ $persil->wilayah['dusun'] }}">
                                    </div>
                                </div>
                            </div>

                            {{-- hanya untuk edit mutasi, data mutasi sudah ada --}}
                            @if ($mutasi['jenis_mutasi'] == 9)
                                <div class="form-group form-horizontal">
                                    <label for="area_tanah" class="col-sm-3 control-label">Peta</label>
                                    <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                                        <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label active">
                                            <input type="radio" name="area_tanah" class="form-check-input" value="1" autocomplete="off"> Pilih Area
                                        </label>
                                        <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label">
                                            <input type="radio" name="area_tanah" class="form-check-input" value="2" autocomplete="off"> Buat Area
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group" id="pilih-area">
                                    <label class="col-sm-3 control-label"></label>
                                    <div class="col-sm-4">
                                        <select class="form-control input-sm select2" id="id_peta" name="id_peta">
                                            <option value=''>-- Pilih Area--</option>
                                            @foreach ($peta as $item)
                                                <option value="{{ $item['id'] }}" @selected($item['id'] == $mutasi['id_peta'])>
                                                    {{ $item['nama'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-11">
                                        <div id="map_persil"></div>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{-- mutasi --}}
            <div id="info_mutasi" class="@if ($persil) show @else hide @endif">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Mutasi</h3>
                </div>

                <div id="cdesa_awal" class="@if (empty($persil['cdesa_awal']) && empty($mutasi)) show @else hide @endif">
                    <div class="box-body">
                        <a href="{{ site_url('cdesa/awal_persil/' . $cdesa['id'] . '/' . $persil['id']) }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block col-sm-2" title="Kembali Ke Rincian C-Desa"><i
                                class="fa fa-step-backward"
                            ></i>C-Desa Awal</a>
                        <span style="padding-left: 10px;">Catat C-Desa ini sebagai pemilik awal keseluruhan
                            persil
                            {{ $persil['nomor'] }}
                        </span>
                    </div>
                    <div class="box-body">
                        <a class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block col-sm-2" title="Kembali Ke Rincian C-Desa" onclick="tambah_mutasi();"><i class="fa fa-plus"></i>Tambah
                            Mutasi</a>
                        <span style="padding-left: 10px;">Tambah mutasi C-Desa
                            {{ $cdesa['nomor'] }} untuk persil
                            {{ $persil['nomor'] }}
                        </span>
                    </div>
                </div>

                <div id="mutasi_persil" class="@if ($persil['cdesa_awal'] && empty($mutasi)) hide @else show @endif">
                    <form name='mainform' action="{{ route('cdesa.simpan_mutasi', ['id_cdesa' => $cdesa['id'], 'id_mutasi' => $mutasi['id']]) }}" method="POST" id="validasi" class="form-horizontal">
                        <input name="jenis_pemilik" type="hidden" value="1">
                        <input type="hidden" name="nik_lama" value="{{ $pemilik['nik_lama'] }}" />
                        <input type="hidden" name="nik" value="{{ $pemilik['nik'] }}" />
                        <input type="hidden" name="id_pend" value="{{ $pemilik['id'] }}" />
                        <input type="hidden" name="id_persil" id="id_persil" value="{{ $persil['id'] }}" />

                        {{-- mutasi tanah --}}
                        <div id="mutasi-tanah" class="@if ($mutasi['jenis_mutasi'] != 9) show @else hide @endif">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#persil">Mutasi -
                                        Bidang Tanah</a>
                                </h4>
                            </div>
                            <div id="bidang_persil" class="panel-collapse">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="no_bidang_persil" class="col-sm-3 control-label">Nomor Bidang
                                            Mutasi</label>
                                        <div class="col-sm-4">
                                            <input name="no_bidang_persil" type="text" class="form-control input-sm digits" placeholder="Nomor urut pecahan bidang persil hasil mutasi" maxlength="2" value="{{ $mutasi['no_bidang_persil'] }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="luas" class="col-sm-3 control-label">Luas Mutasi (M2)</label>
                                        <div class="col-sm-9">
                                            <input name="luas" type="text" class="form-control input-sm luas required" placeholder="Luas Mutasi (M2)" value="{{ $mutasi['luas'] }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="no_objek_pajak" class="col-sm-3 control-label">Nomor Objek
                                            Pajak</label>
                                        <div class="col-sm-8">
                                            <input class="form-control input-sm angka" type="text" placeholder="Nomor Objek Pajak" name="no_objek_pajak" value="{{ $mutasi['no_objek_pajak'] }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="area_tanah" class="col-sm-3 control-label">Peta</label>
                                        <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                                            <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label active">
                                                <input type="radio" name="area_tanah" class="form-check-input" value="1" autocomplete="off"> Pilih Area
                                            </label>
                                            <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label">
                                                <input type="radio" name="area_tanah" class="form-check-input" value="2" autocomplete="off"> Buat Area
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group" id="pilih-area">
                                        <label class="col-sm-3 control-label"></label>
                                        <div class="col-sm-4">
                                            <select class="form-control input-sm select2" id="id_peta" name="id_peta" style="width:100%">
                                                <option value=''>-- Pilih Area--</option>
                                                @foreach ($peta as $key => $item)
                                                    <option value="{{ $item['id'] }}" @selected($item['id'] == $mutasi['id_peta'])>
                                                        {{ $item['nama'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input type="hidden" id="path" name="path" value="{{ $mutasi['path'] }}">
                                            <input type="hidden" id="zoom" name="zoom" value="">
                                            <div id="map"></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#mutasi">Mutasi - Sebab
                                        Dan Tanggal Perubahan</a>
                                </h4>
                            </div>
                            <div id="mutasi" class="panel-collapse">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="tanggal_mutasi" class="col-sm-3 control-label">Tanggal
                                            Perubahan</label>
                                        <div class="col-sm-4">
                                            <div class="input-group input-group-sm date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input class="form-control input-sm pull-right tgl_indo required" name="tanggal_mutasi" type="text" value="{{ tgl_indo_out($mutasi['tanggal_mutasi']) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jenis_mutasi" class="col-sm-3 control-label required">Sebab
                                            Mutasi</label>
                                        <div class="col-sm-4">
                                            <select class="form-control input-sm required" name="jenis_mutasi">
                                                <option value>-- Pilih Sebab Mutasi--</option>
                                                @foreach ($persil_sebab_mutasi as $key => $item)
                                                    <option value="{{ $item['id'] }}" @selected($item['id'] == $mutasi['jenis_mutasi'])>
                                                        {{ $item['nama'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label"></label>
                                        <div class="col-sm-8">
                                            <p class="help-block">
                                                <code>Gunakan tanda titik (.) untuk bilangan pecahan</code>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cdesa_keluar" class="col-sm-3 control-label">Perolehan
                                            Dari</label>
                                        <div class="col-sm-9">
                                            <select class="form-control select2 input-sm" id="cdesa_keluar" name="cdesa_keluar">
                                                <option value='' selected="selected">-- Pilih C-DESA dari mana
                                                    bidang persil ini dimutasikan --</option>
                                                @foreach ($list_cdesa as $data)
                                                    <option value="{{ $data['id_cdesa'] }}" @selected($mutasi['cdesa_keluar'] == $data['id_cdesa'])>
                                                        {{ $data['nomor'] . ' - ' . $data['namapemilik'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="" class="col-sm-3 control-label"></label>
                                            <div class="form-group">
                                                <div class="col-sm-9">
                                                    <span class="help-block"><code>Jika C-Desa tidak ditemukan, bisa dibuat dan ditambahkan belakangan</code></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan" class="col-sm-3 control-label">Keterangan</label>
                                        <div class="col-sm-9">
                                            <textarea name="keterangan" class="form-control input-sm" type="text" placeholder="Keterangan" name="ket" rows="5">{{ $mutasi['keterangan'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- mutasi pemilik awal persil --}}
                        <div id="mutasi-awal" class="@if ($mutasi['jenis_mutasi'] == 9) show @else hide @endif">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#mutasi">Pemilik Awal
                                        Persil</a>
                                </h4>
                            </div>
                            <input type="hidden" name="jenis_mutasi_mutasi" value="{{ $mutasi['jenis_mutasi'] }}">
                            <input type="hidden" id="path" name="path_mutasi" value="{{ $mutasi['path'] }}">
                            <input type="hidden" id="zoom" name="zoom" value="">

                            <div id="pemilik_awal" class="panel-collapse">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="no_objek_pajak" class="col-sm-3 control-label">Nomor Objek
                                            Pajak</label>
                                        <div class="col-sm-8">
                                            <input class="form-control input-sm angka" type="text" placeholder="Nomor Objek Pajak" name="no_objek_pajak_mutasi" value="{{ $mutasi['no_objek_pajak'] }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan" class="col-sm-3 control-label">Keterangan</label>
                                        <div class="col-sm-9">
                                            <textarea name="keterangan_mutasi" class="form-control input-sm" type="text" placeholder="Keterangan">{{ $mutasi['keterangan'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end mutasi pemilik awal persil --}}
                        {{-- end mutasi --}}

                        <div class="box-footer">
                            <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                                Batal</button>
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                                Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.layouts.components.asset_peta')
    <script>
        var infoWindow;
        $(document).ready(function() {
            // jika ada data mutasi
            if ($('input[name="id_persil"]').val() != '') {
                loadMap();
            }
        });

        function loadMap() {
            @if (!empty($desa['lat']) && !empty($desa['lng']))
                var posisi = [{!! $desa['lat'] . ',' . $desa['lng'] !!}];
                var zoom = {{ $desa['zoom'] ?: 18 }};
            @else
                var posisi = [-1.0546279422758742, 116.71875000000001];
                var zoom = 4;
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
        }

        function change_persil(data) {
            var selectedOption = $(data).find('option:selected');

            if (!selectedOption.data('nopersil')) {
                $('#info-persil').addClass('hide');
                return false;
            }

            $('#no_persil').val(selectedOption.data('nopersil'));
            $('#tipe').val(selectedOption.data('tipetanah'));
            $('#kelas').val(selectedOption.data('kelastanah'));
            $('#luas_persil').val(selectedOption.data('luaspersil'));
            $('#alamat').val(selectedOption.data('alamat'));

            $('#info-persil, #info_mutasi, #mutasi-tanah, #mutasi_persil').removeClass('hide');
            $('#belumAdaPersil').addClass('hide');
            $('#cdesa_awal').addClass('hide');

            $('#id_persil').val(selectedOption.val());

            // reload element map
            loadMap();


            return false;
        }

        function tambah_mutasi() {
            $('#cdesa_awal').hide();
            $('#mutasi_persil').show();
            $('#cdesa_keluar').select2(); // Untuk refresh tampilan
        }

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
    </script>
    <script src="{{ asset('js/leaflet.filelayer.js') }}"></script>
    <script src="{{ asset('js/togeojson.js') }}"></script>
@endpush
