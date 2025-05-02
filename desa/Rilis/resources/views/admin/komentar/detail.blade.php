@include('admin.layouts.components.asset_validasi')

@extends('admin.layouts.index')

@push('css')
    <style>
        .direct-chat-messages {
            height: auto;
        }
    </style>
@endpush

@section('title')
    <h1>
        Komentar
        <small>Detail Komentar</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('komentar') }}"> Daftar Komentar</a></li>
    <li class="active">Detail Komentar</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.layouts.components.konfirmasi_hapus')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info direct-chat direct-chat-warning">
                <div class="box-header with-border">
                    <a href="{{ site_url('komentar') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Artikel">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Komentar
                    </a>
                    <a href="{{ $komentar['url_artikel'] }}" target="_blank" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Artikel">
                        <i class="fa fa-eye"></i>Lihat Komentar Artikel
                    </a>
                </div>

                <div class="box-body">
                    <div class="direct-chat-messages">
                        @include('admin.komentar.message', $komentar)
                        @foreach ($komentar['children'] as $child)
                            @include('admin.komentar.message', $child)
                        @endforeach
                    </div>
                </div>
                <div class="box-footer">
                    <form id="validasi" action="{{ $form_action }}" method="POST" class="form-horizontal">
                        <div class="input-group">
                            <input type="text" name="komentar" placeholder="Isi Komentar" class="form-control required">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-warning btn-flat">Kirim</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
