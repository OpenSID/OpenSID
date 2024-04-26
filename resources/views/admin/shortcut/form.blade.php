@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.asset_colorpicker')

@push('css')
    <style>
        select {
            font-family: fontAwesome
        }
    </style>
@endpush

@section('title')
    <h1>
        Shortcut
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('shortcut') }}">Shortcut</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div class="col-md-8">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ ci_route('shortcut') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Shortcut
                    </a>
                </div>
                {!! form_open($form_action, 'id="validasi"') !!}
                <div class="box-body">
                    <div class="form-group">
                        <label>Judul</label>
                        <input name="judul" class="form-control input-sm required judul" maxlength="50" type="text" value="{{ $shortcut->judul }}">
                        <label class="error">Isi dengan [Desa] untuk menyesuaikan sebutan desa berdasarkan pengaturan
                            aplikasi.</label>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Akses Modul</label>
                                <select class="form-control select2" id="akses" name="akses" data-placeholder="Pilih Akses Modul">
                                    <option value=""></option>
                                    @foreach ($moduls as $kslug => $mdl)
                                        <option value="{{ $kslug }}" @selected($kslug === $shortcut->akses)>
                                            {{ SebutanDesa($mdl) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Link</label>
                                <input name="link" class="form-control input-sm required" maxlength="200" type="text" value="{{ $shortcut->link }}">
                                <code id="url_hasil">{{ site_url($shortcut->link) }}</code>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Icon</label>
                                <select class="form-control select2-ikon required" id="icon" name="icon" data-placeholder="Pilih Icon">
                                    @foreach ($icons as $icon)
                                        <option value="{{ $icon }}" @selected($icon === $shortcut->icon)>
                                            {{ $icon }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Warna</label>
                                <div class="input-group my-colorpicker2">
                                    <input type="text" class="form-control input-sm" name="warna" placeholder="Pilih Warna" value="{{ $shortcut->warna }}">
                                    <div class="input-group-addon input-sm"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tampil</label>
                                <select class="form-control select2" name="status">
                                    @foreach (\App\Enums\StatusEnum::all() as $key => $value)
                                        <option value="{{ $key }}" @selected($key == $shortcut->status)>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jenis Query</label>
                                <select id="jenis_query" class="form-control select2 required" name="jenis_query" data-placeholder="Pilih Jenis Query">
                                    <option value="0" @selected(0 == $shortcut->jenis_query)>Otomatis</option>
                                    <option value="1" @selected(1 == $shortcut->jenis_query)>Manual</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8" id="query_otomatis" {!! in_array($shortcut->jenis_query, [1]) ? 'style="display: none;"' : '' !!}>
                            <div class="form-group">
                                <label>Query Otomatis</label>
                                <select class="form-control select2" name="query_otomatis" data-placeholder="Pilih Query">
                                    <option value=""></option>
                                    @foreach ($querys as $key => $query)
                                        <option value="{{ $key }}" @selected($key === $shortcut->raw_query)>Jumlah
                                            {{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="query_manual" {!! in_array($shortcut->jenis_query, [null, 0]) ? 'style="display: none;"' : '' !!}>
                        <label>Query Manual</label>
                        <textarea name="query_manual" class="form-control input-sm" rows="5" placeholder="Contoh :
- SELECT COUNT(*) as Jumlah FROM surat WHERE status='1'
- DB::table('produk')->count()
- Penduduk::count()">{{ $shortcut->raw_query }}</textarea>
                    </div>

                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                        Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                        Simpan</button>
                </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h4 class="box-title">Preview</h4>
                </div>
            </div>
            <div id="isi-warna" class="small-box {{ $shortcut->status == 1 ? '' : 'tp02' }}" style="background-color: {{ $shortcut->warna ?? '#00c0ef' }}; border-radius: 5px;">
                <div class="inner">
                    <h3 id="isi-count" class="text-white">{{ $shortcut->count ?? '0' }}</h3>
                    <p id="isi-judul" class="text-white">{{ $shortcut->judul ?? 'Judul' }}</p>
                </div>
                <div class="icon">
                    <i id="isi-icon" class="faa {{ $shortcut->icon ?? 'fa-user' }}"></i>
                </div>
                <a id="isi-link" href="{{ site_url($shortcut->link ?? 'shortcut') }}" class="small-box-footer text-white" style="border-radius:  0 0 5px 5px">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="<?= asset('js/custom-select2.js') ?>"></script>
    <script>
        $(document).ready(function() {
            $('.judul').on('keyup', function() {
                var judul = $(this).val();
                $('#isi-judul').text(judul);
            });

            $('input[name="link"]').on('keyup', function() {
                $('#url_hasil').text('Hasil : ' + SITE_URL + $(this).val());
                $('#isi-link').attr('href', SITE_URL + $(this).val());
            });

            $('#icon').on('change', function() {
                var icon = $(this).val();
                $('#isi-icon').removeClass().addClass('faa ' + icon);
            });

            $('input[name="warna"]').on('change', function() {
                var warna = $(this).val();
                $('#isi-warna').css('background-color', warna);
            });

            $('select[name="status"]').on('change', function() {
                var status = $(this).val();
                if (status == 1) {
                    $('#isi-warna').removeClass('tp02');
                } else {
                    $('#isi-warna').addClass('tp02');
                }
            });

            $('#jenis_query').on('change', function() {
                var jenis_query = $(this).val();
                if (jenis_query == '0') {
                    $('#query_manual').hide();
                    $('#query_otomatis').show();
                } else {
                    $('#query_manual').show();
                    $('#query_otomatis').hide();
                }
            });
        });
    </script>
@endpush
