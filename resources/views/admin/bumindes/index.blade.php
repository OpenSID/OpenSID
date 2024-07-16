@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@section('title')
    <h1>
        Buku Administrasi Umum - {{ $subtitle }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $subtitle }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div id="umum-sidebar" class="col-sm-3">
            @include('admin.layouts.components.side_bukudesa')
        </div>
        <div id="umum-content" class="col-sm-9">
            @include($main_content)
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection
