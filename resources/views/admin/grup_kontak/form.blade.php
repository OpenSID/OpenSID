@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Grup Kontak
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('daftar_kontak') }}">Grup Kontak</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div class="col-md-3">
            @include('admin.daftar_kontak.navigasi')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ ci_route('grup_kontak') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Grup Kontak
                    </a>
                </div>
                {!! form_open($formAction, 'class="form-horizontal" id="validasi"') !!}
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Grup</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-sm nama required" name="nama_grup" placeholder="OpenDesa" value="{{ $grupKontak->nama_grup }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Keterangan</label>
                        <div class="col-sm-8">
                            <textarea name="keterangan" class="form-control input-sm" rows="5" placeholder="Keterangan lainnya...">{{ $grupKontak->keterangan }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
