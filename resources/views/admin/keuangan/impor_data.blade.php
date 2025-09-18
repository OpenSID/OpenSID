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
            @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('keuangan_manual'), 'label' => 'Keuangan Manual'])
        </div>
        <div class="box-body">
            <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
                <div class="row  col-sm-6">
                    <div class="form-group">
                        <label for="file" class="control-label">Berkas Database Siskuedes :</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="file_path2">
                            <input type="file" class="hidden required" id="file2" name="keuangan" accept=".zip">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info" id="file_browser2"><i class="fa fa-search"></i>
                                    Browse</button>
                            </span>
                        </div>
                        <p class="help-block small">Pastikan format berkas .zip berisi data Siskeudes dalam format .csv</p>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
