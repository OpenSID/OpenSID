@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.asset_colorpicker')

@section('title')
    <h1>
        Pengaturan Tema
        <small>Ubah Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ site_url('theme') }}">Tema</a></li>
    <li class="active">Ubah Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ site_url('theme') }}"
                class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left "></i>Kembali ke Tema
            </a>
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'id="validasi"') !!}
            <div class="row">
                @foreach($tema->config as $key => $value)
                    @if (view()->exists("admin.theme.components.form.{$value['type']}"))
                        <div class="form-group">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label for="input{{ $value['key'] }}" class="col-sm-2 control-label">{{ $value['judul'] }}</label>
                                    <div class="col-sm-6">
                                        @php
                                            $value['default']  =  $tema->opsi[$value['key']] ?? $value['value'];
                                            $value['readonly'] = ($value['readonly'] == true) ? 'readonly' : '';
                                            $value['class']    = $value['attributes']['class'];
                                            unset($value['attributes']['class'], $value['attributes']['readonly']);
                                            $value['attributes'] = implode(' ', array_map(function ($key, $value) {
                                                return "$key=\"$value\"";
                                            }, array_keys($value['attributes']), $value['attributes']));
                                        @endphp

                                        @include("admin.theme.components.form.{$value['type']}", ['value' => $value])

                                    </div>
                                    <label class="col-sm-4 control-label">{{ $value['keterangan'] }}</label>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
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