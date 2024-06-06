@extends('admin.layouts.index')

@section('title')
    <h1>
        <h1>Pengaturan Widget {{ $pemerintah }}</h1>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Pengaturan Widget {{ $pemerintah }}</li>
@endsection

@section('content')
    {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ site_url('web_widget') }}" class="btn btn-social  btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali ke Widget">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Widget
                    </a>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="container-fluid">
                                <div class="jumbotron">
                                    <p>Widget {{ $pemerintah }} menampilkan foto staf {{ $pemerintah }}. Klik tombol berikut
                                        untuk mengubah data dan foto staf {{ $pemerintah }}</p>
                                    <a class="btn btn-primary btn-large" href="{{ site_url('pengurus') }}">{{ $pemerintah }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <form id="mainform" name="mainform" action="" method="post">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-xs-12 col-md-3 col-lg-2" style="margin-left: 19px;">Tampilkan nama/jabatan</label>
                                            <div class="col-xs-12 col-sm-2">
                                                <select class="form-control input-sm" name="setting[overlay]">
                                                    <option value="1" @selected($settings['overlay'] == '1')>Ya</option>
                                                    <option value="0" @selected($settings['overlay'] == '0')>Tidak</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="col-xs-12">
                        <button type="reset" class="btn btn-social  btn-danger btn-sm"><i class="fa fa-times"></i>
                            Batal</button>
                        @if (can('u'))
                            <button type="submit" class="btn btn-social  btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                                Simpan</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection
