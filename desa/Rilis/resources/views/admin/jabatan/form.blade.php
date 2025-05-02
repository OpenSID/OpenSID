@extends('admin.layouts.index')

@section('title')
    <h1>
        Jabatan Pengurus
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pengurus') }}"> Pengurus</a></li>
    <li class="breadcrumb-item"><a href="{{ ci_route('pengurus.jabatan') }}">Daftar Jabatan Pengurus</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div id="umum-sidebar" class="col-sm-3">
            @include('admin.layouts.components.side_bukudesa')
        </div>
        <div id="umum-content" class="col-sm-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ ci_route('pengurus.jabatan') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Jabatan
                    </a>
                </div>
                <div class="box-body">
                    {!! form_open($form_action, 'id="validasi"') !!}
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label">Nama Jabatan</label>
                            <input type="text" class="form-control input-sm nama_terbatas required" id="nama" name="nama" placeholder="Nama Jabatan" value="{{ $jabatan->nama }}" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Tupoksi Jabatan</label>
                            <textarea name="tupoksi" class="form-control input-sm editor required" rows="5" placeholder="Tupoksi Jabatan">{{ $jabatan->tupoksi }}</textarea>
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
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/tinymce-72/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: '.editor',
                promotion: false,
                height: 700,
                theme: 'silver',
                plugins: [
                    'advlist', 'autolink', 'lists', 'charmap', 'hr', 'pagebreak',
                    'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'insertdatetime',
                    'nonbreaking',
                    'table', 'contextmenu', 'directionality', 'emoticons', 'paste', 'textcolor',
                ],
                toolbar1: "removeformat | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | blocks fontfamily fontsizeinput",
                image_advtab: true,
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css'
                ],
                skin: 'tinymce-5',
                relative_urls: false,
                remove_script_host: false
            });
        });
    </script>
@endpush
