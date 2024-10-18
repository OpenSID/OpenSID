@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Media Sosial
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ site_url('sosmed') }}">Media Sosial</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ site_url('sosmed') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Media Sosial</a>
        </div>
        {!! form_open_multipart($form_action, 'id="validasi"') !!}
        <div class="box-body">
            <div class="form-group">
                <label>Nama</label>
                <input name="nama" class="form-control input-sm required judul" maxlength="50" type="text" value="{{ $sosmed->nama }}">
            </div>
            <div class="form-group">
                <label>Link</label>
                <input name="link" class="form-control input-sm required" maxlength="200" type="url" value="{{ $sosmed->new_link }}">
            </div>
            <div class="form-group">
                <label>Icon</label>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control {{ $sosmed->gambar ? '' : 'required' }}" id="file_path" name="gambar">
                            <input type="file" class="hidden" id="file" name="gambar" accept=".gif,.jpg,.jpeg,.png">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i>&nbsp;Browse</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tampil</label>
                        <select class="form-control select2" name="enabled">
                            @foreach (\App\Enums\StatusEnum::all() as $key => $value)
                                <option value="{{ $key }}" @selected($key == $sosmed->enabled)>{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
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
@endsection
