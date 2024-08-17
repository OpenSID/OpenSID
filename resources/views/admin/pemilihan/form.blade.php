@include('admin.layouts.components.datetime_picker')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Pemilihan
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pemilihan') }}"> Pemilihan</a></li>
    <li class="breadcrumb-item"><a href="{{ ci_route('pemilihan.pemilihan') }}">Daftar Pemilihan</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('pemilihan') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Pemilihan
            </a>
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'id="validasi"') !!}
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <label class="control-label">Judul</label>
                            <input type="text" class="form-control input-sm nama_terbatas required" id="judul" name="judul" placeholder="Judul" value="{{ $pemilihan->judul }}" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label">Tanggal </label>
                            <input type="date" class="form-control input-sm required" name="tanggal" placeholder="Tanggal" value="{{ $pemilihan->tanggal }}" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control input-sm editor" rows="5" placeholder="Keterangan">{{ $pemilihan->keterangan }}</textarea>
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
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: '.editor',
                height: 700,
                theme: 'silver',
                plugins: [
                    "advlist autolink lists charmap hr pagebreak",
                    "searchreplace wordcount visualblocks visualchars insertdatetime nonbreaking",
                    "table contextmenu directionality emoticons paste textcolor code",
                ],
                toolbar1: "bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | code | fontselect fontsizeselect",
                image_advtab: true,
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css'
                ],
                relative_urls: false,
                remove_script_host: false
            });
        });
    </script>
@endpush
