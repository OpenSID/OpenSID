@include('admin.pengaturan_surat.asset_tinymce')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Konsep Surat {{ ucwords($surat->nama) }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('surat') }}">Daftar Cetak Surat</a></li>
    <li class="active"> Surat {{ ucwords($surat->nama) }}</li>
    <li class="active"> Konsep Surat {{ ucwords($surat->nama) }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        {!! form_open(null, 'id="validasi"') !!}
        <div class="box-body">
            <input type="hidden" id="id_surat" value="{{ $id_surat }}">
            <div class="form-group">
                <textarea name="isi_surat" class="form-control input-sm editor required">{{ $isi_surat }}</textarea>
            </div>
        </div>
        <div class="box-footer text-center">
            <a href="{{ route('surat') }}"
                class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Surat
            </a>
            <button type="button" onclick="$('#validasi').attr('action', '{{ $aksi_cetak }}').submit()"
                class="btn btn-social btn-success btn-sm"><i class="fa fa-file-pdf-o"></i> Cetak
                PDF</button>
            <button type="button" onclick="$('#validasi').attr('action', '{{ $aksi_konsep }}').submit()"
                class="btn btn-social btn-warning btn-sm"><i class="fa fa-file-code-o"></i>
                Konsep / Draf</button>
            </center>
        </div>
        </form>
    </div>
@endsection
