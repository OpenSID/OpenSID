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
    <li class="breadcrumb-item"><a href="{{ ci_route('surat_dinas') }}">Daftar Surat</a></li>
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
            <li><a href="#kode-isian" data-toggle="tab">Kode Isian Alias</a></li>
            <li><a href="#lainnya" data-toggle="tab">Lainnya</a></li>
        </ul>
        <div class="tab-content">
            @include('admin.surat_dinas.pengaturan.kembali')
            @include('admin.surat_dinas.pengaturan.partials.pengaturan_header')
            @include('admin.surat_dinas.pengaturan.partials.pengaturan_footer')
            @include('admin.surat_dinas.pengaturan.partials.pengaturan_alur')
            @include('admin.surat_dinas.pengaturan.partials.pengaturan_kodeisian')
            @include('admin.surat_dinas.pengaturan.partials.pengaturan_lainnya')
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
