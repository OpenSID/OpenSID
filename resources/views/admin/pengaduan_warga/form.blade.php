@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaduan
        <small>{{ $action }}</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pengaduan_admin') }}">Daftar Pengaduan</a></li>
    <li class="active">{{ $action }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('pengaduan_admin') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Pengaduan</a>
        </div>
        {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="status">Status</label>
                <div class="col-sm-9">
                    <select class="form-control input-sm required" required name="status" {{ $pengaduan_warga->status == '3' ? 'disabled' : '' }}>
                        <option value="">Pilih Status Pengaduan</option>
                        <option value="2" {{ selected(2, $pengaduan_warga->status) }}>Sedang Diproses</option>
                        <option value="3" {{ selected(3, $pengaduan_warga->status) }}>Selesai Diproses</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="isi">Tanggapan</label>
                <div class="col-sm-9">
                    <textarea
                        name="isi"
                        class="form-control input-sm required"
                        required
                        maxlength="300"
                        placeholder="Masukkan Tanggapan"
                        rows="3"
                        style="resize:none;"
                    ></textarea>
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
