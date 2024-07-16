@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Kotak Pesan
        <small>{{ $pesan ? 'Ubah' : 'Tambah' }} Kotak Pesan</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('komentar') }}"> Daftar Kotak Pesan</a></li>
    <li class="active">{{ $pesan ? 'Ubah' : 'Tambah' }} Kotak Pesan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <a href="{{ ci_route('mailbox') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Artikel">
                            <i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Kotak Pesan
                        </a>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="owner">{{ $labelPengirim }}</label>
                            <div class="col-sm-9">
                                <input name="owner" @disabled($readonly) class="form-control input-sm required" type="text" maxlength="50" value="{{ $pesan['owner'] }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="penduduk_id">NIK</label>
                            <div class="col-sm-9">
                                <input @disabled($readonly) class="form-control input-sm bilangan" type="text" value="{{ $pesan['penduduk']['nik'] }}" />
                                <input name="penduduk_id" class="form-control input-sm " type="hidden" value="{{ $pesan['penduduk_id'] }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="subjek">Subjek</label>
                            <div class="col-sm-9">
                                <input class="form-control input-sm required" @disabled($readonly) id="subjek" name="subjek" value="{{ $pesan['subjek'] }}">
                                <input class="form-control input-sm" type="hidden" name="subjek" value="{{ $pesan['subjek'] }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="komentar">Pesan </label>
                            <div class="col-sm-9">
                                <textarea id="komentar" name="komentar" @disabled($readonly) class="form-control input-sm required" placeholder="Isi Kotak " style="height: 200px;">{{ $pesan['komentar'] }}</textarea>
                            </div>
                        </div>
                    </div>
                    @if ($readonly)
                        <div class='box-footer'>
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right confirm"><i class="fa fa-reply"></i> Balas Pesan</button>
                        </div>
                    @else
                        <div class='box-footer'>
                            <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>Batal</button>
                            <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </form>
@endsection
