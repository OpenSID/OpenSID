@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@php
    $title = ucfirst($ci->controller);
@endphp

@section('title')
    <h1>
        Master {{ $title }}
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url($ci->controller) }}">Daftar {{ $title }}</a></li>
    <li class="active">Master {{ $title }} {{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile preview-img">
                        <img class="penduduk img-responsive" src="{{ gambar_desa($kelompok['logo']) }}" alt="Logo">
                        <br />
                        <p class="text-muted text-center text-red">(Kosongkan, jika logo tidak berubah)</p>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control file-path" readonly name="logo">
                            <input type="file" class="hidden file-input" name="logo" accept=".gif,.jpg,.jpeg,.png">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat file-browser"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <a href="{{ site_url($ci->controller) }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Daftar
                            <?= $title ?>
                        </a>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="nama">Nama {{ $title }}</label>
                            <div class="col-sm-7">
                                <input
                                    id="nama"
                                    class="form-control input-sm nama_terbatas required"
                                    type="text"
                                    placeholder="Nama {{ $title }}"
                                    name="nama"
                                    value="{{ $kelompok['nama'] }}"
                                    maxlength="50"
                                >
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 control-label" for="kode">Kode {{ $title }}</label>
                            <div class="col-sm-7">
                                <input
                                    id="kode"
                                    class="form-control input-sm nomor_sk required"
                                    type="text"
                                    placeholder="Kode {{ $title }}"
                                    name="kode"
                                    value="{{ $kelompok['kode'] }}"
                                    maxlength="16"
                                >
                                <p><code>*Pastikan kode belum pernah dipakai di data lembaga / di data kelompok.</code></p>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 control-label" for="kode">No. SK Pendirian {{ $title }}</label>
                            <div class="col-sm-7">
                                <input
                                    id="no_sk_pendirian"
                                    class="form-control input-sm nomor_sk"
                                    type="text"
                                    placeholder="No. SK Pendirian {{ $title }}"
                                    name="no_sk_pendirian"
                                    value="{{ $kelompok['no_sk_pendirian'] }}"
                                    maxlength="255"
                                >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="id_master">Kategori <?= $title ?></label>
                            <div class="col-sm-7">
                                <select class="form-control input-sm select2 required" id="id_master" name="id_master">
                                    <option value="">-- Silakan Masukkan Kategori {{ $title }}--</option>
                                    @foreach ($list_master as $data)
                                        <option value="{{ $data['id'] }}" @selected($kelompok['id_master'] == $data['id'])>{{ $data['kelompok'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="id_ketua">Ketua <?= $title ?></label>
                            <div class="col-sm-7">
                                <select class="form-control input-sm select2" id="kelompok_penduduk" name="id_ketua" @disabled($kelompok !== null)>
                                    <option value="">-- Silakan Masukkan NIK / Nama--</option>
                                    @foreach ($list_penduduk as $data)
                                        <option value="{{ $data['id'] }}" @selected($data['id'] == $kelompok['id_ketua'])>NIK :{{ $data['nik'] . ' - ' . $data['nama'] . ' - ' . $data['alamat'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="keterangan">Deskripsi <?= $title ?></label>
                            <div class="col-sm-7">
                                <textarea name="keterangan" class="form-control input-sm" placeholder="Deskripsi {{ $title }}" rows="3" maxlength="300">{{ $kelompok['keterangan'] }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                        <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
