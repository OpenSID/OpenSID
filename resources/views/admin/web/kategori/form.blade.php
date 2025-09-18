@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Kategori
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('kategori') }}"> Kategori</a></li>
    <li class="active">Daftar Kategori</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('kategori'), 'label' => 'Daftar Kategori'])

        </div>
        {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
        <div class="box-body">
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Nama Kategori</label>
                <div class="col-sm-6">
                    <input name="kategori" class="form-control input-sm required nomor_sk" maxlength="50" type="text" value="{{ $kategori->kategori ?? '' }}">
                </div>
            </div>
        </div>
        <div class="box-footer">
            {!! batal() !!}
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
        </div>
        </form>
    </div>
@endsection
