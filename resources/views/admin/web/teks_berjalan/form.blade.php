@extends('admin.layouts.index')

@section('title')
<h1>
    <h1>Teks Berjalan
        <small>{{ $teks ? 'Ubah' : 'tambah' }} Data</small>
    </h1>
</h1>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ ci_route('teks_berjalan') }}">Teks Berjalan</a></li>
{{ $teks ? 'Ubah' : 'tambah' }} Data
@endsection

@section('content')
@include('admin.layouts.components.notifikasi')

{!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
<div class="box box-info">
    <div class="box-header with-border">
        <a href="{{ ci_route('teks_berjalan') }}"
            class="btn btn-social  btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Kembali Ke Teks Berjalan">
            <i class="fa fa-arrow-circle-left "></i>Kembali Ke Teks Berjalan
        </a>
    </div>
    <div class="box-body">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label" for="isi_teks_berjalan">Isi teks berjalan</label>
                <textarea id="teks" class="form-control input-sm required" placeholder="Isi teks berjalan" name="teks"
                    rows="5" style="resize:none;">{{ $teks['teks'] }}</textarea>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="control-label">Tautan ke artikel</label>
                <select class="form-control select2 " id="tautan" name="tautan">
                    <option value="">-- Cari Judul Artikel --</option>
                    @foreach ($list_artikel as $artikel)
                    <option value="{{ $artikel['id'] }}" @selected($artikel['id']==$teks['tautan'])>
                        {{ tgl_indo($artikel['tgl_upload']) . ' | ' . $artikel['judul'] }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12" id="box_judul_tautan" style="display: {{ $teks['tautan'] ? '' : 'none' }}">
            <div class="form-group">
                <label class="control-label">Judul tautan</label>
                <input {{ $teks['tautan'] ? '' : 'disabled' }} class="form-control input-sm required"
                    placeholder="Judul tautan ke artikel atau url" name="judul_tautan" id="input_judul_tautan"
                    value="{{ $teks['judul_tautan'] }}" maxlength="150" />
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="control-label">Status</label>
                <select class="form-control select2" id="status" name="status">
                    @foreach (\App\Enums\StatusEnum::all() as $key => $data)
                    <option value="{{ $key }}" @selected($key==$teks['status'])>
                        {{ $data }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="control-label">Tautan ke artikel</label>
            <select class="form-control select2 " id="tautan" name="tautan">
                <option value="">-- Cari Judul Artikel --</option>
                @foreach ($list_artikel as $artikel)
                <option value="{{ $artikel['id'] }}" @selected($artikel['id']==$teks['tautan'])>
                    {{ tgl_indo($artikel['tgl_upload']) . ' | ' . $artikel['judul'] }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-12" id="box_judul_tautan" style="display: {{ $teks['tautan'] ? '' : 'none' }}">
        <div class="form-group">
            <label class="control-label">Judul tautan</label>
            <input {{ $teks['tautan'] ? '' : 'disabled' }} class="form-control input-sm required"
                placeholder="Judul tautan ke artikel atau url" name="judul_tautan" id="input_judul_tautan"
                value="{{ $teks['judul_tautan'] }}" maxlength="150" />
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="control-label">Status</label>
            <select class="form-control select2" id="status" name="status">
                @foreach (\App\Enums\StatusEnum::all() as $key => $data)
                <option value="{{ $key }}" @selected($key==$teks['status'])>
                    {{ $data }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class='box-footer'>
    <button type='reset' class='btn btn-social  btn-danger btn-sm'><i class='fa fa-times'></i>
        Batal</button>
    <button type='submit' class='btn btn-social  btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i>
        Simpan</button>
</div>
</div>
</form>
@endsection

@push('script')
<script>
    $(document).ready(function() {
            $('#tautan').on('change', function() {
                if (this.value == "") {
                    $('#box_judul_tautan').hide();
                    $('#input_judul_tautan').prop("disabled", true);
                } else {
                    $('#box_judul_tautan').show();
                    $('#input_judul_tautan').prop("disabled", false);
                }
            });
        });
</script>
@endpush