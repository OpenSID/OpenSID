@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@php $tipe = ucfirst(str_replace('_master', '', $ci->controller)); @endphp

@section('title')
    <h1>Kategori {{ $tipe }}
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="<?= site_url(strtolower($tipe)) ?>"><?= $tipe ?></a></li>
    <li><a href="<?= site_url($ci->controller) ?>"> Ketegori <?= $tipe ?></a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <a href="<?= site_url($ci->controller) ?>" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Kategori <?= $tipe ?></a>
        </div>
        <form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="nama">Klasifikasi/Kategori <?= $tipe ?></label>
                    <div class="col-sm-8">
                        <input id="kelompok" class="form-control input-sm required" type="text" placeholder="Kategori <?= $tipe ?>" name="kelompok" value="<?= $kelompok_master->kelompok ?>"></input>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="Deskripsi">Deskripsi <?= $tipe ?></label>
                    <div class="col-sm-8">
                        <textarea name="deskripsi" class="form-control input-sm" placeholder="Deskripsi <?= $tipe ?>" rows="5"><?= $kelompok_master->deskripsi ?></textarea>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                    Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
            </div>
        </form>
    </div>
@endsection
