@extends('admin.layouts.index')
@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Biodata Anggota Keluarga
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('keluarga') }}"> Daftar Keluarga</a></li>
    <li class="active">Biodata Anggota Keluarga</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="">
        <form id="mainform" name="mainform" action="{{ $form_action }}" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-3">
                    @include('admin.layouts.components.ambil_foto', [
                        'id_sex' => $penduduk['id_sex'],
                        'foto' => $penduduk['foto'],
                    ])
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class='box box-primary'>
                                <div class="box-header with-border">
                                    <a href="{{ ci_route('keluarga') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Keluarga">
                                        <i class="fa fa-arrow-circle-left"></i>Kembali Ke Daftar Keluarga
                                    </a>
                                    <a href="{{ ci_route("keluarga.anggota.{$id_kk}") }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Keluarga">
                                        <i class="fa fa-arrow-circle-left"></i>Kembali Ke Daftar Anggota Keluarga
                                    </a>
                                </div>
                                <div class='box-body'>
                                    <div class="row">
                                        <div class='col-sm-12'>
                                            <div class="form-group subtitle_head">
                                                <label class="text-right"><strong>DATA KELUARGA :</strong></label>
                                            </div>
                                        </div>
                                        <div class='col-sm-4'>
                                            <div class='form-group'>
                                                <label>No. KK </label>
                                                <input class="form-control input-sm" name="no_kk_keluarga" type="text" value="{{ $kk['no_kk'] }}" readonly></input>
                                                <input name="id_kk" type="hidden" value="{{ $id_kk }}" id="id_kk">
                                                <input name="kk_level" type="hidden" value="0">
                                                <input name="jenis_peristiwa" type="hidden" value="{{ $jenis_peristiwa }}">
                                                <input name="id_cluster" type="hidden" value="{{ $kk['id_cluster'] }}">
                                            </div>
                                        </div>
                                        <div class='col-sm-8'>
                                            <div class='form-group'>
                                                <label>Kepala KK</label>
                                                <input class="form-control input-sm" type="text" value="{{ $kk['nama'] }}" disabled></input>
                                            </div>
                                        </div>
                                        <div class='col-sm-12'>
                                            <div class='form-group'>
                                                <label>Alamat </label>
                                                <input class="form-control input-sm" type="text" value="{{ $kk['alamat'] }} Dusun {{ $kk['dusun'] }} - RW {{ $kk['rw'] }} - RT {{ $kk['rt'] }}" disabled></input>
                                            </div>
                                        </div>
                                        <div class='col-sm-12'>
                                            <div class="form-group subtitle_head">
                                                <label class="text-right"><strong>DATA ANGGOTA :</strong></label>
                                            </div>
                                        </div>
                                        <div class='col-sm-12'>
                                            @include('admin.penduduk.penduduk_form_isian_bersama')
                                        </div>
                                    </div>
                                </div>
                                <div class='box-footer'>
                                    <div class='col-xs-12'>
                                        {!! batal() !!}
                                        <button type='submit' class='btn btn-social btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
