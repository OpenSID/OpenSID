@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Hubung Warga
        <small>Kirim Pesan Grup</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('sms') }}">Hubung Warga</a></li>
    <li class="active">Kirim Pesan Grup</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div class="col-md-3">
            @include('admin.sms.navigasi')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ ci_route('sms/arsip') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Hubung Warga
                    </a>
                </div>
                {!! form_open($formAction, 'id="validasi"') !!}
                <div class="box-body">
                    <div class="form-group">
                        <label for="nama">Grup Kontak</label>
                        <select class="form-control input-sm select2 required" id="id_grup" name="id_grup">
                            <option value="">Pilih Grup Kontak</option>
                            @foreach ($grupKontak as $grup)
                                <option value="{{ $grup->id_grup }}">{{ $grup->nama_grup }} ( {{ $grup->anggota_count }} Anggota )</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nama">Subjek Pesan</label>
                        <input name="subjek" class="form-control input-sm required" placeholder="Subjek Pesan" maxlength="100" />
                    </div>
                    <div class="form-group">
                        <label for="isi">Isi Pesan</label>
                        <textarea name="isi" class="form-control input-sm required" placeholder="Isi Pesan" rows="5"></textarea>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
