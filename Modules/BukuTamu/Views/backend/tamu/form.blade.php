@extends('admin.layouts.index')
@include('admin.layouts.components.asset_form_request')

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
            @include('admin.layouts.components.tombol_kembali', ['url' => request()->server('HTTP_REFERER') ?? ci_route('buku_tamu'), 'label' => 'Data Tamu'])

        </div>

        {!! form_open($form_action, 'id="form_validasi"') !!}
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama <span class="text-red">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control input-sm" placeholder="Isi Nama" value="{{ old('nama', $buku_tamu?->nama ?? '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Telepon <span class="text-red">*</span></label>
                        <input type="text" class="form-control input-sm" id="telepon" name="telepon" placeholder="Isi No. Telp./HP" maxlength="20" pattern="[0-9]+" value="{{ old('telepon', $buku_tamu?->telepon ?? '') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Instansi <span class="text-red">*</span></label>
                        <input type="text" name="instansi" id="instansi" class="form-control input-sm" placeholder="Isi Instansi" value="{{ old('instansi', $buku_tamu?->instansi ?? '') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jenis Kelamin <span class="text-red">*</span></label>
                        <select class="form-control select2" id="jenis_kelamin" name="jenis_kelamin">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            @foreach (\App\Enums\JenisKelaminEnum::all() as $key => $value)
                                <option value="{{ $key }}" @selected(old('jenis_kelamin', $buku_tamu?->jenis_kelamin_id) == $key)>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Alamat <span class="text-red">*</span></label>
                <textarea name="alamat" id="alamat" class="form-control input-sm" placeholder="Isi Alamat" rows="5">{{ old('alamat', $buku_tamu?->alamat ?? '') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Bertemu <span class="text-red">*</span></label>
                        <select class="form-control select2" id="bidang" name="bidang">
                            <option value="">-- Pilih Bidang --</option>
                            @foreach ($bertemu as $key => $value)
                                <option value="{{ $key }}" @selected(old('bidang', $buku_tamu?->bidang) == $key)>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Keperluan <span class="text-red">*</span></label>
                <textarea name="keperluan" id="keperluan" class="form-control input-sm" placeholder="Isi Keperluan" rows="5">{{ old('keperluan', $buku_tamu?->keperluan ?? '') }}</textarea>
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
@if(! $form_action)
@push('scripts')
<script>
    $(document).ready(function () {
        $('input, textarea, select').attr('disabled', true);
        $('.box-footer').remove();
        $('form').removeAttr('action').removeAttr('method');
    });
</script>
@endpush
@endif