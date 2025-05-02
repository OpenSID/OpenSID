@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Buku Administrasi Umum - {{ $subtitle }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $subtitle }}</li>
@endsection

@push('css')
    <style type="text/css">
        .disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>
@endpush

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div id="umum-sidebar" class="col-sm-3">
            @include('admin.layouts.components.side_bukudesa')
        </div>
        <div id="umum-content" class="col-sm-9">
            @include($main_content)
        </div>
        </section>
        @include('admin.layouts.components.konfirmasi_hapus')
    @endsection
