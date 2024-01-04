@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Wilayah Administratif {{ $wilayahLabel }}
        <small>{{ $aksi ? 'Ubah' : 'Tambah' }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('wilayah.index') }}"> Wilayah Administratif {{ $wilayahLabel }}</a></li>
    <li class="active">{{ $aksi ? 'Ubah' : 'Tambah' }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a onclick="window.history.back()" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left "></i>Kembali ke Wilayah Administratif {{ $wilayahLabel }}
            </a>
        </div>
        {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="nama">Nama {{ $wilayahLabel }}</label>
                <div class="col-sm-7">
                    <input
                        id="nama"
                        class="form-control input-sm {{ $level == 'dusun' ? 'nama_terbatas' : 'digits' }} required"
                        maxlength="50"
                        type="text"
                        placeholder="Nama {{ $wilayahLabel }}"
                        name="{{ $level }}"
                        value="{{ $wilayah->$level }}"
                    >
                </div>
            </div>
            @if ($wilayah->kepala)
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="kepala_lama">Kepala {{ $wilayahLabel }} Sebelumnya</label>
                    <div class="col-sm-7">
                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            <strong>{{ $wilayah->kepala->nama }}</strong>
                            <br />NIK - {{ $wilayah->kepala->nik }}
                        </p>
                    </div>
                </div>
            @endif
            <div class="form-group">
                <label class="col-sm-3 control-label" for="id_kepala">NIK / Nama Kepala {{ $wilayahLabel }}</label>
                <div class="col-sm-7">
                    <select class="form-control select2 select2-infinite" data-url="wilayah/apipendudukwilayah" style="width: 100%;" id="id_kepala" name="id_kepala">
                        <option selected="selected">-- Silakan Masukan NIK / Nama--</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/custom-select2.js') }}"></script>
    <script>
        $(document).ready(function() {

        })
    </script>
@endpush
