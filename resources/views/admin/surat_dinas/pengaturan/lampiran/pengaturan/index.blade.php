@extends('admin.layouts.index')
@section('title')
    <h1>
        Daftar Lampiran
        <small>{{ $action }} Pengaturan Lampiran</small>
    </h1>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('lampiran') }}">Daftar Lampiran</a></li>
    <li class="active">{{ $action }} Pengaturan Lampiran</li>
@endsection
@section('content')
    @include('admin.layouts.components.notifikasi')
    {!! form_open($formAksi, ['id' => 'validasi', 'enctype' => 'multipart/form-data']) !!}
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#kertas" data-toggle="tab">Kertas</a></li>
            <li><a href="#kotak" data-toggle="tab">Kotak</a></li>
        </ul>
        <div class="tab-content">
            @include('admin.surat_dinas.pengaturan.lampiran.pengaturan.kembali')
            @include('admin.surat_dinas.pengaturan.lampiran.pengaturan.kertas')
            @include('admin.surat_dinas.pengaturan.lampiran.pengaturan.kotak')

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
