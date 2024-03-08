@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.jquery_ui')
@include('admin.layouts.components.tokenfield')

@section('title')
    <h1>
        Buku Kader Pemberdayaan
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('bumindes_kader') }}">Daftar Buku Kader Pemberdayaan</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('bumindes_kader') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Buku Kader Pemberdayaan</a>
        </div>
        {!! form_open($formAction, 'class="form-horizontal" id="validasi"') !!}
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="penduduk_id">NIK / Nama Kader</label>
                <div class="col-sm-6">
                    <select class="form-control select2 required" id="penduduk_id" name="penduduk_id">
                        <option value="" selected="selected">-- Silakan Masukkan NIK / Nama Kader --</option>
                        @foreach ($daftar_penduduk as $penduduk)
                            <option value="{{ $penduduk->id }}" @selected($main->penduduk_id == $penduduk->id)>NIK : {{ $penduduk->nik . ' | Nama : ' . $penduduk->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="kursus">Kursus</label>
                <div class="col-sm-6">
                    <input type="text" name="kursus" id="kursus" class="form-control ui-autocomplete required" placeholder="Pilih Kursus" value="{{ $main->kursus }}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="bidang">Bidang Keahlian</label>
                <div class="col-sm-6">
                    <input type="text" name="bidang" id="bidang" class="form-control ui-autocomplete required" placeholder="Pilih Bidang Keahlian" value="{{ $main->bidang }}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
                <div class="col-sm-6">
                    <textarea name="keterangan" id="keterangan" class="form-control input-sm required" maxlength="100" placeholder="Keterangan" rows="5">{{ $main->keterangan }}</textarea>
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

@push('scripts')
    <script>
        $(document).ready(function() {

            var url = SITE_URL + '/bumindes_kader/';

            $('#kursus').tokenfield({
                autocomplete: {
                    source: function(request, response) {
                        jQuery.get(url + 'get_kursus', {
                            nama: request.term
                        }, function(data) {
                            data = $.parseJSON(data);
                            response(data);
                        });
                    },
                    delay: 100
                },
                showAutocompleteOnFocus: true
            });

            const kursus = $('#kursus').val();
            $('#kursus').tokenfield('setTokens', kursus ? JSON.parse(kursus) : null);

            $('#bidang').tokenfield({
                autocomplete: {
                    source: function(request, response) {
                        jQuery.get(url + 'get_bidang', {
                            nama: request.term
                        }, function(data) {
                            data = $.parseJSON(data);
                            response(data);
                        });
                    },
                    delay: 100
                },
                showAutocompleteOnFocus: true
            });

            const bidang = $('#bidang').val();
            $('#bidang').tokenfield('setTokens', bidang ? JSON.parse(bidang) : null);
        });
    </script>
@endpush
