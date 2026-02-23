@extends('admin.layouts.index')
@include('admin.layouts.components.asset_form_request')

@section('title')
    <h1>
        Data Pertanyaan
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('buku_pertanyaan') }}">Data Pertanyaan</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('buku_pertanyaan'), 'label' => 'Data Pertanyaan'])

        </div>

        {!! form_open($form_action, 'id="form_validasi"') !!}
        <div class="box-body">
            <div class="form-group">
                <label>Pertanyaan <span class="text-red">*</span></label>
                <textarea name="pertanyaan" id="pertanyaan" class="form-control input-sm" placeholder="Isi Pertanyaan" rows="5">{{ old('pertanyaan', $data_pertanyaan?->pertanyaan ?? '') }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tampil <span class="text-red">*</span></label>
                        <select class="form-control select2" id="status" name="status">
                            <option value="">-- Pilih Status --</option>
                            @foreach (\App\Enums\StatusEnum::all() as $key => $value)
                                <option value="{{ $key }}" @selected(old('status', $data_pertanyaan?->status) == $key)>{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
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
