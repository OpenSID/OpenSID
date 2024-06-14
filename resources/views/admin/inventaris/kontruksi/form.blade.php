@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.datetime_picker')

@section('title')
    <h1>
        {{ $action }} Inventaris Kontruksi
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $action }} Inventaris Kontruksi</li>
@endsection

@push('css')
    <style type="text/css">
        .disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>
@endpush

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-sm-3">
            @include('admin.inventaris.menu')
        </div>
        <div class="col-sm-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ site_url('inventaris_kontruksi') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Inventaris Konstruksi</a>
                </div>
                {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="nama_barang">Nama Barang / Jenis Barang</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" value="{{ $main->nama_barang }}" class="form-control input-sm required" name="nama_barang" id="nama_barang" type="text" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="fisik_bangunan">Fisik Bangunan</label>
                                <div class="col-sm-4">
                                    <select name="fisik_bangunan" id="fisik_bangunan" class="form-control input-sm required">
                                        <option value="">-- Pilih Fisik Bangunan --</option>
                                        @foreach (['Darurat', 'Permanen', 'Semi Permanen'] as $item)
                                            <option @selected($item == $main->kondisi_bangunan) value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="tingkat">Bangunan Bertingkat</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input value="{{ $main->kontruksi_bertingkat }}" class="form-control input-sm number required" id="tingkat" name="tingkat" type="text" />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">Lantai</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="bahan">Konstruksi Beton</label>
                                <div class="col-sm-4">
                                    <select name="bahan" id="bahan" class="form-control input-sm required">
                                        @if ($main->kontruksi_beton == 0)
                                            <option value='0'>Tidak</option>
                                            <option value='1'>Ya</option>
                                        @else
                                            <option value='1'>Ya</option>
                                            <option value='0'>Tidak</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="luas_bangunan">Luas</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input value="{{ $main->luas_bangunan }}" class="form-control input-sm number required" id="luas_bangunan" name="luas_bangunan" type="text" />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="alamat">Letak / Lokasi </label>
                                <div class="col-sm-8">
                                    <textarea class="form-control input-sm required" name="alamat" id="alamat">{{ $main->letak }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="no_bangunan">Nomor Bangunan</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" value="{{ $main->no_dokument }}" class="form-control input-sm required" name="no_bangunan" id="no_bangunan" type="text" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="tanggal_bangunan">Tanggal Dokumen Bangunan</label>
                                <div class="col-sm-4">
                                    <input maxlength="50" value="{{ $main->tanggal_dokument }}" class="form-control input-sm datepicker required" name="tanggal_bangunan" id="tanggal_bangunan" type="text" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="tanggal_mulai">Tanggal Mulai </label>
                                <div class="col-sm-4">
                                    <input class="form-control input-sm datepicker required" value="{{ $main->tanggal }}" id="tanggal_mulai" name="tanggal_mulai" type="text" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="status_tanah">Status Tanah</label>
                                <div class="col-sm-4">
                                    <select name="status_tanah" id="status_tanah" class="form-control input-sm required">
                                        <option value="">-- Pilih Status Tanah --</option>
                                        <option @selected($main->status_tanah == 'Tanah milik Pemda') value="Tanah milik Pemda">Tanah milik Pemda</option>
                                        <option @selected($main->status_tanah == 'Tanah Negara') value="Tanah Negara">Tanah Negara (Tanah yang dikuasai langsung oleh Negara)</option>
                                        <option @selected($main->status_tanah == 'Tanah Hak Ulayat') value="Tanah Hak Ulayat">Tanah Hak Ulayat (Tanah masyarakat Hukum Adat)</option>
                                        <option @selected($main->status_tanah == 'Tanah Hak') value="Tanah Hak">Tanah Hak (Tanah kepunyaan perorangan atau Badan Hukum)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="kode_tanah">Nomor Kode Tanah</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" value="{{ $main->kode_tanah }}" class="form-control input-sm required" name="kode_tanah" id="kode_tanah" type="text" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="asal_usul">Asal Usul </label>
                                <div class="col-sm-4">
                                    <select name="asal" id="asal" class="form-control input-sm required">
                                        <option value="">-- Pilih Asal Usul --</option>
                                        @foreach (['Bantuan Kabupaten', 'Bantuan Pemerintah', 'Bantuan Provinsi', 'Pembelian Sendiri', 'Sumbangan'] as $item)
                                            <option @selected($item == $main->asal) value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="harga">Harga</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">Rp</span>
                                        <input value="{{ $main->harga }}" class="form-control input-sm number required" id="harga" name="harga" type="text" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
                                <div class="col-sm-8">
                                    <textarea rows="5" class="form-control input-sm required" name="keterangan" id="keterangan">{{ $main->keterangan }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (!$view_mark)
                    <div class="box-footer">
                        <div class="col-xs-12">
                            <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                        </div>
                    </div>
                @endif
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var view = "{{ $view_mark }}";
            if (1 == view) {
                $('#validasi').find('input, select, textarea').attr('disabled', 'disabled');
            }
        });
    </script>
@endpush
