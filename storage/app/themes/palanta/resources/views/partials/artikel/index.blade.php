@extends('theme::layouts.right-sidebar')
@php
  $title = (!empty($judul_kategori)) ? $judul_kategori : 'Artikel Terkini';
  $slug = 'terkini';
  if (is_array($title)) {
    $slug = $title['slug'];
    $title = $title['kategori'];
  }
@endphp

@section('content')
<!-- Tampilkan slider hanya di halaman awal. Tidak tampil pada daftar artikel di halaman kategori atau halaman selanjutnya serta halaman hasil pencarian -->
@if (empty($cari) && count($slider_gambar ?? []) > 0 && request()->segment(2) != 'kategori' && (request()->segment(2) !== 'index' && request()->segment(1) !== 'index'))
  @include('theme::partials.slider')
@endif

@if (empty($cari) && count($slider_gambar ?? []) > 0 && request()->segment(2) != 'kategori' && (request()->segment(2) !== 'index' && request()->segment(1) !== 'index'))
  @include('theme::partials.headline')
@endif

@if ($artikel->count() > 0)
  <div class="heading-module l-flex">
    <div class="heading-module-inner l-flex">
    <i class="fa fa-edit"></i><h1>Artikel Terbaru</h1>
    </div>
    <div class="to-arsip"><a href="{{ ci_route('arsip') }}"><i class="fa fa-plus"></i> Arsip</a></div>
  </div>
  @foreach ($artikel as $post)
    @include('theme::partials.artikel.list', ['post' => $post])
  @endforeach
  <div class="card-body c-flex text-center">    
    @include('theme::commons.paging', ['paging_page' => $paging_page])
  </div>
@else
  @include('theme::partials.artikel.empty', ['title' => $title])
@endif
@endsection