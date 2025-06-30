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
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Query</label>
                                <select class="form-control select2 required" id="raw_query" name="raw_query" data-placeholder="Pilih Query">
                                    <option value=""></option>
                                    @foreach ($modules as $key => $value)
                                        <option value="{{ $key }}" @selected($key === $shortcut->raw_query) data-link="{{ $value['link'] }}">Jumlah {{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
                                    <input type="text" class="form-control input-sm required" name="warna" placeholder="Pilih Warna" value="{{ $shortcut->warna }}">
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

            $('#raw_query').on('change', function() {
                link = SITE_URL + $(this).find('option:selected').data('link');

                $('#isi-link').attr('href', link)
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
        });
    </script>
@endpush
