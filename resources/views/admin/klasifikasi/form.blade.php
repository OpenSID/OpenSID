@include('admin.layouts.components.datetime_picker')
@extends('admin.layouts.index')

@section('title')
    <h1> Klasifikasi Surat
        <small>{{ empty($data) ? 'Tambah' : 'Ubah' }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('klasifikasi') }}">Klasifikasi Surat </a></li>
    <li class="active">{{ empty($data) ? 'Tambah' : 'Ubah' }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    {!! form_open($form_action, 'id="validasi" class="form-horizontal"') !!}
    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('klasifikasi', $kat) }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Klasifikasi">
                <i class="fa fa-arrow-circle-left "></i>Kembali Ke Klasifikasi Surat
            </a>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label class="control-label col-sm-4" for="kode">Kode</label>
                <div class="col-sm-6">
                    <input name="kode" class="form-control input-sm alfanumerik_titik required" type="text" placeholder="Kode" value="{{ $data['kode'] }}"></input>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Nama</label>
                <div class="col-sm-6">
                    <input name="nama" class="form-control input-sm required" type="text"placeholder="Nama" value="{{ $data['nama'] }}"></input>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="uraian">Keterangan</label>
                <div class="col-sm-6">
                    <textarea name="uraian" class="form-control input-sm required" placeholder="Keterangan">{{ $data['uraian'] }}</textarea>
                </div>
            </div>
        </div>
        <div class='box-footer'>
            <button type='reset' class='btn btn-social btn-danger btn-sm'><i class='fa fa-times'></i> Batal</button>
            <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
        </div>
    </div>
    </form>
@endsection
