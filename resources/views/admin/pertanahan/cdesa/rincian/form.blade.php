@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Daftar C-Desa
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('cdesa') }}"> Daftar C-Desa</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('surat_mohon') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar C-Desa
            </a>
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box-body">
                <div class="form-group @error('jenis_pemilik') has-error @enderror">
                    <label class="col-sm-3 control-label">Nama Dokumen</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control input-sm nomor_sk" id="jenis_pemilik" name="jenis_pemilik" placeholder="Nama Dokumen" value="{{ old('jenis_pemilik', $ref_syarat_surat->jenis_pemilik) }}" />
                        @error('jenis_pemilik')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group @error('nomor') has-error @enderror">
                    <label class="col-sm-3 control-label">No. C-Desa</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control input-sm nomor_sk" id="nomor" name="nomor" placeholder="Nama Dokumen" value="{{ old('nomor', $ref_syarat_surat->jenis_pemilik) }}" />
                        @error('jenis_pemilik')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group @error('nomor') has-error @enderror">
                    <label class="col-sm-3 control-label">Nama Pemilik Tertulis di C-Desa</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control input-sm nomor_sk" id="nomor" name="nomor" placeholder="Nama Dokumen" value="{{ old('nomor', $ref_syarat_surat->jenis_pemilik) }}" />
                        @error('jenis_pemilik')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
            </form>
        </div>
    </div>
@endsection
