@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@php $pemerintah = ucwords(setting('sebutan_pemerintah_desa')) @endphp
@section('title')
    <h1>
        {{ $pemerintah }}
        <small>Bagan {{ $pemerintah }}</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pengurus') }}">{{ $pemerintah }}</a></li>
    <li class="active">Bagan {{ $pemerintah }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body">
                    <div id="container"></div>
                    <p class="highcharts-description"></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('css/bagan.css') }}">
@endpush

@include('admin.layouts.components.highchartjs')
@include('admin.pengurus.chart_bagan')
