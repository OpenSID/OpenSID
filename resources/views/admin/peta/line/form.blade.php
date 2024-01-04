@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Pengaturan Tipe Garis
        <small>{{ $aksi }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('line.index') }}">Pengaturan Tipe Garis</a></li>
    <li class="active">{{ $aksi }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-3">
            @include('admin.peta.nav')
        </div>
        <div class="col-md-9">
            {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ ci_route('line') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Tipe Garis
                    </a>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nama Jenis Garis</label>
                        <div class="col-sm-7">
                            <input name="nama" class="form-control input-sm nomor_sk required" maxlength="100" type="text" placeholder="Nama Jenis Garis" value="{{ $line['nama'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Jenis</label>
                        <div class="col-sm-4">
                            <select class="form-control input-sm required" id="jenis" name="jenis">
                                <option value="solid" @selected($line['jenis'] == 'solid')>Solid</option>
                                <option value="dotted" @selected($line['jenis'] == 'dotted')>Dotted</option>
                                <option value="dashed" @selected($line['jenis'] == 'dashed')>Dashed</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Warna Garis</label>
                        <div class="col-sm-4">
                            <div class="input-group my-colorpicker2">
                                <input type="text" id="color" name="color" class="form-control input-sm warna required" placeholder="#FFFFFF" value="{{ $line['color'] }}">
                                <div class="input-group-addon input-sm">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Tebal Garis</label>
                        <div class="col-sm-4">
                            <input
                                name="tebal"
                                class="form-control input-sm nomor_sk required"
                                id="tebal"
                                type="number"
                                value="{{ $line['tebal'] ?? 3 }}"
                                min="1"
                                max="10"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3"></label>
                        <div class="col-sm-7"><br>
                            <p id="showline"></p>
                        </div>
                    </div>
                </div>
                <div class='box-footer'>
                    <div>
                        <button type='reset' class='btn btn-social btn-danger btn-sm'><i class='fa fa-times'></i>
                            Batal</button>
                        <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-colorpicker.min.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('bootstrap/js/bootstrap-colorpicker.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#showline").hide();
            var j = document.getElementById("jenis");
            var t = document.getElementById("tebal");
            var c = document.getElementById("color");

            function show() {
                var isij = document.forms[0].jenis.value;
                var isit = document.forms[0].tebal.value;
                var isic = document.forms[0].color.value;
                $('#showline').css({
                    'display': 'block',
                    'border-bottom': isit + 'px ' + isij + ' ' + isic
                });
            }
            j.onchange = show;
            t.onkeyup = show;
            t.onclick = show;
            c.onchange = show;
            show();
        })
    </script>
@endpush
