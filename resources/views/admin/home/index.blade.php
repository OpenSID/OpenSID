@extends('admin.layouts.index')

@push('css')
<style>
  .catatan-scroll {
    height: 400px;
    overflow-y: scroll;
  }

  @media (max-width: 576px) {
    .komunikasi-opendk {
      display: none !important;
    }
  }
</style>
@endpush

@section('title')
<h1>
  Tentang OpenSID
</h1>
@endsection

@section('breadcrumb')
<li class="active">Tentang OpenSID</li>
@endsection

@section('content')

@include('admin.layouts.components.notifikasi')

@include('admin.home.saas')

@include('admin.home.rilis')

@include('admin.home.bantuan')

<div class="row">
  @if (can('u', 'sid_core'))
  <div class="col-lg-3 col-sm-6 col-xs-6">
    <div class="small-box bg-purple">
      <div class="inner">
        <h3>{{ $dusun }}</h3>
        <p>{{ SebutanDesa('Wilayah [Desa]')  }}</p>
      </div>
      <div class="icon">
        <i class="ion ion-location"></i>
      </div>
      <a href="{{ route('sid_core') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  @endif
  
  @if (can('u', 'penduduk'))
  <div class="col-lg-3 col-sm-6 col-xs-6">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{ $penduduk }}</h3>
        <p>Penduduk</p>
      </div>
      <div class="icon">
        <i class="ion ion-person"></i>
      </div>
      <a href="{{ route('penduduk.clear') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  @endif
  
  @if (can('u', 'keluarga'))
  <div class="col-lg-3 col-sm-6 col-xs-6">
    <div class="small-box bg-green">
      <div class="inner">
        <h3>{{ $keluarga }}</h3>
        <p>Keluarga</p>
      </div>
      <div class="icon">
        <i class="ion ion-ios-people"></i>
      </div>
      <a href="{{ route('keluarga.clear') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  @endif
  
  @if (can('u', 'keluar'))
  <div class="col-lg-3 col-sm-6 col-xs-6">
    <div class="small-box bg-blue">
      <div class="inner">
        <h3>{{ $surat }}</h3>
        <p>Surat Tercetak</p>
      </div>
      <div class="icon">
        <i class="ion-ios-paper"></i>
      </div>
      <a href="{{ route('keluar.clear') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  @endif
  
  @if (can('u', 'kelompok'))
  <div class="col-lg-3 col-sm-6 col-xs-6">
    <div class="small-box bg-red">
      <div class="inner">
        <h3>{{ $kelompok }}</h3>
        <p>Kelompok</p>
      </div>
      <div class="icon">
        <i class="ion ion-android-people"></i>
      </div>
      <a href="{{ route('kelompok.clear') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  @endif
  
  @if (can('u', 'rtm'))
  <div class="col-lg-3 col-sm-6 col-xs-6">
    <div class="small-box bg-gray">
      <div class="inner">
        <h3>{{ $rtm }}</h3>
        <p>Rumah Tangga</p>
      </div>
      <div class="icon">
        <i class="ion ion-ios-home"></i>
      </div>
      <a href="{{ route('rtm.clear') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  @endif
  
  @if (can('u', 'program_bantuan'))
  <div class="col-lg-3 col-sm-6 col-xs-6">
    <div class="small-box bg-yellow">
      <div class="inner">
        <h3>{{ $bantuan['jumlah'] }}</h3>
        <p>{{ $bantuan['nama'] }}</p>
      </div>
      <div class="icon">
        <i class="ion ion-ios-pie"></i>
      </div>
      <div class="small-box-footer">
        <a href="#" class="inner text-white rilis_pengaturan" data-remote="false" data-toggle="modal" data-target="#pengaturan-bantuan"><i class="fa fa-gear"></i></a>
        <a href="{{ route($bantuan['link_detail']) }}" class="inner text-white">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>
  @endif
  
  @if (can('u', 'mandiri'))
  <div class="col-lg-3 col-sm-6 col-xs-6">
    <div class="small-box" style="background-color: #39CCCC;">
      <div class="inner">
        <h3>{{ $pendaftaran }}</h3>
        <p>Verifikasi Layanan Mandiri</p>
      </div>
      <div class="icon">
        <i class="ion ion-person"></i>
      </div>
      <a href="{{ route('mandiri') }}" class="small-box-footer">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  @endif
</div>
@endsection
