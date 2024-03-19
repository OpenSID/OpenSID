@extends('admin.layouts.index')

@section('title')
    <h1>
        Buku Administrasi Pembangunan
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $subtitle }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div class="col-md-3">
            @include('admin.bumindes.pembangunan.side')
        </div>
        <div class="col-md-9">
            @include($mainContent)
        </div>
    </div>
    @include('admin.layouts.components.konfirmasi_hapus')
@endsection
