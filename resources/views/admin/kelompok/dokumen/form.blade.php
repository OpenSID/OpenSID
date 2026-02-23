@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.datetime_picker')

@section('title')
    <h1>Pengaturan {{ $kat_nama }} Di {{ ucwords(setting('sebutan_desa')) }}</h1>
@endsection

@section('breadcrumb')
    <li><a href="#" onclick="window.history.back()">Daftar {{ $kat_nama }}</a></li>
    <li class="active">Pengaturan {{ $kat_nama }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}

        {{-- simpan id_kelompok sebagai hidden input --}}
        <input type="hidden" name="id_kelompok" value="{{ $id_kelompok }}">

        <div class="box-header with-border">
            <x-kembali-button
                judul="Kembali ke Daftar {{ $kat_nama }} Di {{ ucwords(setting('sebutan_desa')) }}"
                url="#"
                onclick="window.history.back()"
            />
        </div>
        <div class="box-body">
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Judul Dokumen</label>
                <div class="col-sm-6">
                    <input name="nama" class="form-control input-sm required" type="text" maxlength="200"
                        value="{{ $dokumen['nama'] ?? '' }}">
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Tipe Dokumen</label>
                <div class="col-sm-6">
                    <select name="tipe" id="tipe" class="form-control input-sm required">
                        <option value="1" @selected($dokumen['tipe'] == 1)>File</option>
                        <option value="2" @selected($dokumen['tipe'] == 2)>URL</option>
                    </select>
                </div>
            </div>
            <div id="d-unggahn" style="display: {{ $dokumen['tipe'] == 2 ? 'none' : '' }};">
                @if ($dokumen['satuan'])
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Dokumen</label>
                        <div class="col-sm-4">
                            <i class="fa fa-file-pdf-o pop-up-pdf" aria-hidden="true" style="font-size: 60px;" data-title="Berkas {{ $dokumen['nomor_surat'] }}" data-url="{{ ci_route('dokumen.tampilkan_berkas', [$dokumen['id'], 0, 1]) }}"></i>

                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <label class="control-label col-sm-4" for="upload">Unggah Dokumen</label>
                    <div class="col-sm-6">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control {{ $dokumen['tipe'] == 2 || $dokumen['tipe'] ? '' : 'required' }}" id="file_path" name="satuan">
                            <input id="file" type="file" class="hidden" name="satuan" accept=".jpg,.jpeg,.png,.pdf" />
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i>
                                    Browse</button>
                            </span>
                        </div>
                        @if ($dokumen)
                            <p class="small">(Kosongkan jika tidak ingin mengubah dokumen)</p>
                        @endif
                    </div>
                </div>
            </div>
            <div id="d-url" class="form-group" style="display: {{ $dokumen['tipe'] == 2 ? '' : 'none' }};">
                <label class="control-label col-sm-4" for="nama">Link/URL Dokumen</label>
                <div class="col-sm-6">
                    <input id="url" name="url" class="form-control input-sm {{ $dokumen['tipe'] == 2 ? 'required' : '' }}" type="text" value="{{ $dokumen['url'] }}"></input>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="keterangan">Keterangan</label>
                <div class="col-sm-6">
                    <textarea name="keterangan" class="form-control input-sm" maxlength="300"
                        placeholder="Keterangan" rows="3" style="resize:none;">{{ $dokumen['keterangan'] ?? '' }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="tahun">Tahun</label>
                <div class="col-sm-6">
                    <input name="tahun" maxlength="4" class="form-control input-sm number required" type="text"
                        placeholder="Contoh: 2024" value="{{ $dokumen['tahun'] ?? date('Y') }}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="published_at">Tanggal Terbit</label>
                <div class="col-sm-6">
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon" style="border-radius: 5px 0 0 5px">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="published_at"
                            value="{{ !empty($dokumen['published_at']) ? tgl_indo_out($dokumen['published_at']) : date('d-m-Y') }}"
                            class="form-control input-sm datepicker required"
                            placeholder="Masukan Tanggal Terbit"
                            style="border-radius: 0 5px 5px 0">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label" for="status">Status Terbit</label>
                <div class="btn-group col-sm-6" data-toggle="buttons">
                    <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 @active(($dokumen['status'] ?? 1) == 1)">
                        <input type="radio" name="status" value="1" @checked(($dokumen['status'] ?? 1) == 1)> Ya
                    </label>
                    <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 @active(($dokumen['status'] ?? 1) == 0)">
                        <input type="radio" name="status" value="0" @checked(($dokumen['status'] ?? 1) == 0)> Tidak
                    </label>
                </div>
            </div>
        </div>
        <div class="box-footer">
            {!! batal() !!}
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right">
                <i class="fa fa-check"></i> Simpan
            </button>
        </div>
        {!! form_close() !!}
    </div>
@endsection

@push('scripts')
    <script>
        $('#tipe').on('change', function() {
            if (this.value == 1) {
                $('#d-unggahn').show();
                $('#d-url').hide();
                $("#url").removeClass("required");
            } else {
                $('#d-unggahn').hide();
                $('#d-url').show();
                $("#url").addClass("required");
            }
        });
    </script>
@endpush