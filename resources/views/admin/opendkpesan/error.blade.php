@extends('admin.layouts.index')

@section('title')
<h1>
  Opendk Error
</h1>
@endsection

@section('content')
@include('admin.layouts.components.notifikasi')

<div class="box box-danger">
  <div class="box-header with-border">
    <i class="icon fa fa-ban"></i>
    <h3 class="box-title">Koneksi Ke Opendk Gagal.</h3>
  </div>
  <div class="box-body">
    @if($message)
    <div class="callout callout-danger">
      <h5>Pesan Error : {!! $message  !!}</h5>
    </div>
    @endif
  </div>
</div>
@endsection