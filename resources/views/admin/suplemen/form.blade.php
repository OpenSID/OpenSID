@extends('admin.layouts.index')

@section('title')
    <h1>
        Data Suplemen
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('suplemen') }}">Daftar Data Suplemen</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('suplemen') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Data Suplemen</a>
        </div>
        {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="nama">Sasaran Data</label>
                <div class="col-sm-9">
                    <select class="form-control input-sm required" {{ $suplemen->sasaran && $suplemen->terdata->count() > 0 ? 'disabled' : '' }} required name="sasaran">
                        <option value="">Pilih Sasaran</option>
                        @foreach ($list_sasaran as $key => $sasaran)
                            @if (in_array($key, ['1', '2']))
                                <option value="{{ $key }}" {{ selected($key, $suplemen->sasaran) }}>{{ $sasaran }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="nama">Nama Data Suplemen</label>
                <div class="col-sm-9">
                    <input class="form-control input-sm required" placeholder="Nama Data Suplemen" type="text" name="nama" value="{{ $suplemen->nama }}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
                <div class="col-sm-9">
                    <textarea name="keterangan" class="form-control input-sm" maxlength="300" placeholder="Keterangan" rows="3" style="resize:none;">{{ $suplemen->keterangan }}</textarea>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
        </div>
        </form>
    </div>
@endsection
