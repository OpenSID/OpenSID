@include('admin.pengaturan_surat.asset_tinymce', ['height' => 350])
@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Daftar Surat
        <small>{{ $action }} Pengaturan Surat</small>
    </h1>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('surat_master') }}">Daftar Surat</a></li>
    <li class="active">{{ $action }} Pengaturan Surat</li>
@endsection
@section('content')
    @include('admin.layouts.components.notifikasi')
    {!! form_open($formAksi, ['id' => 'validasi', 'enctype' => 'multipart/form-data']) !!}
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#header" data-toggle="tab">Header</a></li>
            <li><a href="#footer" data-toggle="tab">Footer</a></li>
            <li><a href="#alur" data-toggle="tab">Alur Surat</a></li>
            <li><a href="#tte" data-toggle="tab">Pengaturan TTE</a></li>
            <li><a href="#sumber-penduduk" data-toggle="tab">Form Penduduk Luar</a></li>
            <li><a href="#kode-isian" data-toggle="tab">Kode Isian Alias</a></li>
            <li><a href="#lainnya" data-toggle="tab">Lainnya</a></li>
        </ul>
        <div class="tab-content">
            @include('admin.pengaturan_surat.kembali')
            @include('admin.pengaturan_surat.partials.pengaturan_header')
            @include('admin.pengaturan_surat.partials.pengaturan_footer')
            @include('admin.pengaturan_surat.partials.pengaturan_alur')
            @include('admin.pengaturan_surat.partials.pengaturan_tte')
            @include('admin.pengaturan_surat.partials.pengaturan_sumber_penduduk')
            @include('admin.pengaturan_surat.partials.pengaturan_kodeisian')
            @include('admin.pengaturan_surat.partials.pengaturan_lainnya')
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                    Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
        </div>
    </div>
    </form>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            ganti_tte();
            ganti_visual();
            $('input[name="tte"]').on('change', function(e) {
                ganti_tte()
            });

            function ganti_tte() {
                var tte_password = "{{ setting('tte_password') }}";
                if ($('input[name="tte"]').filter(':checked').val() == 1) {
                    $('input[name="tte_api"]');
                    if (tte_password == "") {
                        $('input[name="tte_password"]').attr("required", true);
                        $('#info-tte-password').hide();
                    } else {
                        $('#info-tte-password').show();
                    }
                    $('input[name="tte_username"]').attr("required", true);
                    $('#modul-tte').show();
                } else {
                    $('input[name="tte_api"]').attr("required", false);
                    $('input[name="tte_password"]').attr("required", false);
                    $('input[name="tte_username"]').attr("required", false);
                    $('#modul-tte').hide();
                }
            }
            $('input[name="visual_tte"]').change(function(e) {
                ganti_visual();
            })

            function ganti_visual() {
                if ($('input[name="visual_tte"]').filter(':checked').val() == 1) {
                    $('#visual-tte-form').show();
                } else {
                    $('#visual-tte-form').hide();
                }
            }
        });
    </script>
@endpush
