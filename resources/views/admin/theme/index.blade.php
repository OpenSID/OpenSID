@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Tema
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Tema</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border text-center">
            @if (can('u', 'theme', true, true))
                <a href="{{ site_url('theme/unggah') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-upload"></i> Unggah</a>
            @endif
            @if (can('u'))
                <a href="{{ site_url('theme/pindai') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-retweet"></i> Pindai</a>
            @endif
            <a href="{{ site_url() }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-eye"></i> Lihat</a>
        </div>
    </div>
    <div class="row">
        @foreach ($listTheme as $detailTheme)
            <div class="col-md-4">
                @includeIf('admin.theme.components.general.box', $detailTheme)
            </div>
        @endforeach
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection
