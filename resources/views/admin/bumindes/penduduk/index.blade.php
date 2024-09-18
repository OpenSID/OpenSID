@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@section('title')
    <h1>
        Buku Administrasi Penduduk
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $subtitle }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div class="col-md-3">
            @include('admin.bumindes.penduduk.side')
        </div>
        <div class="col-md-9">
            @include($mainContent)
        </div>
    </div>
@endsection
