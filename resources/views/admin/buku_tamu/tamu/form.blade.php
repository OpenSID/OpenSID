@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Data Tamu
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">

        <a href="{{ ci_route('buku_tamu') }}">Data Tamu</a>
    </li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('buku_tamu') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left "></i>Kembali ke Data Tamu
            </a>
        </div>

        {!! form_open($form_action, 'id="validasi"') !!}
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control input-sm nama required" placeholder="Isi Nama" value="{{ $buku_tamu->nama }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Telepon</label>
                        <input
                            type="text"
                            class="form-control input-sm bilangan telepon required"
                            name="telepon"
                            placeholder="Isi No. Telp./HP"
                            maxlength="20"
                            pattern="[0-9]+"
                            value="{{ $buku_tamu->telepon }}"
                        >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Instansi</label>
                        <input type="text" name="instansi" class="form-control input-sm required" placeholder="Isi Instansi" value="{{ $buku_tamu->instansi }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select class="form-control select2" name="jenis_kelamin">
                            @foreach (\App\Enums\JenisKelaminEnum::all() as $key => $value)
                                <option value="{{ $key }}" @selected($key == $buku_tamu->jenis_kelamin)>{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control input-sm required" placeholder="Isi Alamat" rows="5">{{ $buku_tamu->alamat }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Bertemu</label>
                        <select class="form-control select2 required" name="id_bidang">
                            @foreach ($bertemu as $key => $value)
                                <option value="{{ $key }}" @selected($key == $buku_tamu->bidang)>{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Keperluan</label>
                <textarea name="keperluan" class="form-control input-sm required" placeholder="Isi Keperluan" rows="5">{{ $buku_tamu->keperluan }}</textarea>
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
