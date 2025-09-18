@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaturan Indikator {{ $analisis_master['nama'] }}
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('analisis_master') }}"> Master Analisis</a></li>
    <li class="active">{{ $analisis_master['nama'] }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-4 col-lg-3">
            @include('analisis::master.menu')
        </div>
        <div class="col-md-8 col-lg-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('analisis_master'), 'label' => 'Master Analisis'])
                </div>
                <div class="box-body">
                    <div class="col-sm-12">
                        <div class="row">
                            <h4 class="box-title"><b>{{ $analisis_master['nama'] }}</b></h4>
                            <div class="box-footer box-comments"> {!! $analisis_master['deskripsi'] !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
