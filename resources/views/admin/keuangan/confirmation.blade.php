@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Impor Data Siskeudes
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Impor Data Siskeudes</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box">
        <div class="box-header with-border">
            <a href="{{ ci_route('keuangan_manual') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Keuangan Manual"><i class="fa fa-arrow-circle-o-left"></i>Kembali Ke Keuangan Manual</a>
        </div>
        <div class="box-body">
            <form id="validasi" action="{{ $form_action }}" method="POST">
                <div class="row col-sm-12">
                    <div class="form-group">
                        <input type="hidden" name="tahun" value="{{ $tahun }}">
                        <input type="hidden" name="confirmation" value="{{ $confirmation }}">
                        <input type="hidden" name="nama_file" value="{{ $nama_file }}">
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-circle"></i> Anda akan melakukan impor data keuangan tahun
                            {{ $tahun }}
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i>
                            Lanjutkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
