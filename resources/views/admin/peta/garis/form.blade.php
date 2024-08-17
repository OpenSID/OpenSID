@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Pengaturan Garis
        <small>{{ $aksi }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('garis.index') }}"> Pengaturan Garis</a></li>
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
                    <a href="{{ ci_route('garis.index') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Pengaturan Garis
                    </a>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nama Garis / Properti</label>
                        <div class="col-sm-7">
                            <input name="nama" class="form-control input-sm nomor_sk required" maxlength="100" type="text" value="{{ $garis->nama }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Kategori</label>
                        <div class="col-sm-7">
                            <select class="form-control input-sm select2 required" id="ref_line" name="ref_line">
                                <option value="">Pilih Kategori</option>
                                @foreach ($list_line as $data)
                                    <option value="{{ $data->id }}" @selected($data->id == $garis->ref_line)>{{ $data->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <?php if ($garis->foto_garis) : ?>
                    <div class="form-group">
                        <label class="control-label col-sm-3"></label>
                        <div class="col-sm-7">
                            <img class="attachment-img img-responsive img-circle" src="{{ $garis->foto_garis }}" alt="Foto">
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Ganti Foto</label>
                        <div class="col-sm-7">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="file_path">
                                <input id="file" type="file" class="hidden" name="foto" accept=".gif,.jpg,.jpeg,.png">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info " id="file_browser"><i class="fa fa-search"></i> Browse</button>
                                </span>
                            </div>
                            <p class="help-block small text-red">Kosongkan jika tidak ingin mengubah foto.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Keterangan</label>
                        <div class="col-sm-7">
                            <textarea id="desk" name="desk" class="form-control input-sm required" style="height: 200px;white-space: pre-wrap;">{{ $garis->desk }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-lg-3 control-label" for="status">Status</label>
                        <div class="btn-group col-xs-12 col-sm-9" data-toggle="buttons">
                            <label id="sx3" class="btn btn-info  btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label @if ($garis->enabled == '1' || $garis->enabled == null) {{ 'active' }} @endif">
                                <input
                                    id="sx1"
                                    type="radio"
                                    name="enabled"
                                    class="form-check-input"
                                    type="radio"
                                    value="1"
                                    @if ($garis->enabled == '1' || $garis->enabled == null) {{ 'checked' }} @endif
                                    autocomplete="off"
                                > Aktif
                            </label>
                            <label id="sx4" class="btn btn-info  btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label @if ($garis->enabled == '2') {{ 'active' }} @endif">
                                <input
                                    id="sx2"
                                    type="radio"
                                    name="enabled"
                                    class="form-check-input"
                                    type="radio"
                                    value="2"
                                    @if ($garis->enabled == '2') {{ 'checked' }} @endif
                                    autocomplete="off"
                                > Tidak Aktif
                            </label>
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

@push('scripts')
    <script>
        $(document).ready(function() {

        })
    </script>
@endpush
