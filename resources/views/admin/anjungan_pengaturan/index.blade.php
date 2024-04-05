@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Pengaturan Anjungan
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('anjungan_pengaturan') }}">Pengaturan Anjungan</a></li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="sebutan_anjungan">Sebutan Anjungan</label>
                    <div class="col-sm-9">
                        <input class="form-control input-sm" type="text" placeholder="Masukkan sebutan anjungan" name="sebutan_anjungan_mandiri" value="{{ $pengaturan['sebutan_anjungan_mandiri'] }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="layar">Jenis Layar</label>
                    <div class="col-sm-9">
                        <select class="form-control input-sm" name="layar">
                            @foreach ([1 => 'Lanskap', 2 => 'Potret'] as $key => $value)
                                <option {{ selected(setting('anjungan_layar'), $key) }} value="{{ $key }}">
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="kategori_id">Kategori Artikel</label>
                    <div class="col-sm-9">
                        <select class="form-control input-sm artikel-multiple" name="artikel[]" multiple="multiple">
                            @foreach ($daftar_kategori as $item)
                                <option value="{{ $item->id }}" {{ in_array($item->id, $anjungan_artikel ?? []) ? 'selected' : '' }}>{{ $item->kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="teks_berjalan">Teks Berjalan</label>
                    <div class="col-sm-9">
                        <input class="form-control input-sm" type="text" placeholder="Masukkan teks berjalan" name="teks_berjalan" value="{{ $pengaturan['anjungan_teks_berjalan'] }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="profil">Tampilan Profil</label>
                    <div class="col-sm-9">
                        <select class="form-control input-sm" name="tampilan_profil">
                            @foreach ([1 => 'Slider', 2 => 'Video', 3 => 'Youtube'] as $key => $value)
                                <option {{ selected(setting('anjungan_profil'), $key) }} value="{{ $key }}">
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group" id="slide" style="display: {{ setting('anjungan_profil') == 1 ? '' : 'none' }}">
                    <label class="col-sm-3 control-label" for="video">Galeri Gambar</label>
                    <div class="col-sm-9">
                        <select class="form-control input-sm" name="slide">
                            @foreach ($slides as $item)
                                <option {{ selected(setting('anjungan_slide'), $item->id) }} value="{{ $item->id }}">
                                    {{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group" id="video" style="display: {{ setting('anjungan_profil') == 2 ? '' : 'none' }}">
                    <label class="col-sm-3 control-label" for="video">URL Video (.mp4)</label>
                    <div class="col-sm-9">
                        <input class="form-control input-sm {{ setting('anjungan_profil') == 2 ? 'required' : '' }}" type="text" placeholder="Masukkan url video" name="video" value="{{ setting('anjungan_video') }}">
                    </div>
                </div>
                <div class="form-group" id="youtube" style="display: {{ setting('anjungan_profil') == 3 ? '' : 'none' }}">
                    <label class="col-sm-3 control-label" for="youtube">URL Youtube</label>
                    <div class="col-sm-9">
                        <input class="form-control input-sm {{ setting('anjungan_profil') == 3 ? 'required' : '' }}" type="text" placeholder="Masukkan url youtube" name="youtube" value="{{ setting('anjungan_youtube') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="profil">Screensaver</label>
                    <div class="col-sm-9">
                        <select class="form-control input-sm" name="screensaver">
                            @foreach ([0 => 'Tidak Aktif', 1 => 'Slider', 2 => 'Video'] as $key => $value)
                                <option {{ selected(setting('tampilan_anjungan'), $key) }} value="{{ $key }}">
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group" id="screensaver_waktu" style="display: {{ setting('tampilan_anjungan') != 0 ? '' : 'none' }}">
                    <label class="col-sm-3 control-label" for="waktu">Waktu Muncul</label>
                    <div class="col-sm-9">
                        <input class="form-control input-sm {{ setting('tampilan_anjungan') != 0 ? 'required' : '' }}" type="text" placeholder="Masukkan waktu muncul screensaver" name="screensaver_waktu" value="{{ setting('tampilan_anjungan_waktu') }}">
                    </div>
                </div>
                <div class="form-group" id="screensaver_slide" style="display: {{ setting('tampilan_anjungan') == 1 ? '' : 'none' }}">
                    <label class="col-sm-3 control-label" for="slide">Galeri Gambar</label>
                    <div class="col-sm-9">
                        <select class="form-control input-sm" name="screensaver_slide">
                            @foreach ($slides as $item)
                                <option {{ selected(setting('tampilan_anjungan_slider'), $item->id) }} value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group" id="screensaver_video" style="display: {{ setting('tampilan_anjungan') == 2 ? '' : 'none' }}">
                    <label class="col-sm-3 control-label" for="video">URL Video (.mp4)</label>
                    <div class="col-sm-9">
                        <input class="form-control input-sm {{ setting('tampilan_anjungan') == 2 ? 'required' : '' }}" type="text" placeholder="Masukkan url video" name="screensaver_video" value="{{ setting('tampilan_anjungan_video') }}">
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
                @if (can('u'))
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                        Simpan</button>
                @endif
            </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.artikel-multiple').select2();
        });

        $('select[name="tampilan_profil"]').on('change', function() {
            var id = this.value;
            if (id == 1) {
                $("#slide").show();
                $("#video").hide();
                $('input[name="video"]').removeClass('required');
                $("#youtube").hide();
                $('input[name="youtube"]').removeClass('required');
            } else if (id == 2) {
                $("#slide").hide();
                $("#video").show();
                $('input[name="video"]').addClass('required');
                $("#youtube").hide();
                $('input[name="youtube"]').removeClass('required');
            } else {
                $("#slide").hide();
                $("#video").hide();
                $('input[name="video"]').removeClass('required');
                $("#youtube").show();
                $('input[name="youtube"]').addClass('required');
            }
        });

        $('select[name="screensaver"]').on('change', function() {
            var id = this.value;
            if (id == 1) {
                $("#screensaver_slide").show();
                $("#screensaver_video").hide();
                $('input[name="screensaver_video"]').removeClass('required');
                $("#screensaver_waktu").show();
                $('input[name="screensaver_waktu"]').addClass('required');
            } else if (id == 2) {
                $("#screensaver_slide").hide();
                $("#screensaver_video").show();
                $('input[name="screensaver_video"]').addClass('required');
                $("#screensaver_waktu").show();
                $('input[name="screensaver_waktu"]').addClass('required');
            } else {
                $("#screensaver_slide").hide();
                $("#screensaver_video").hide();
                $('input[name="screensaver_video"]').removeClass('required');
                $("#screensaver_waktu").hide();
                $('input[name="screensaver_waktu"]').removeClass('required');
            }
        });
    </script>
@endpush
