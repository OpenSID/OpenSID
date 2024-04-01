@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.datetime_picker')
@section('title')
    <h1>Pengaturan
        {{ $kat_nama }} Di
        {{ ucwords(setting('sebutan_desa')) }}
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="#" onclick="window.history.back()"> Daftar
            {{ $kat_nama }}
        </a></li>
    <li class="active">Pengaturan
        {{ $kat_nama }}
    </li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
        <div class="box-header with-border">
            <a href="#" onclick="window.history.back()" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Artikel">
                <i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar
                {{ $kat_nama }} Di
                {{ ucwords(setting('sebutan_desa')) }}
            </a>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Judul Dokumen</label>
                <div class="col-sm-6">
                    <input name="nama" class="form-control input-sm nomor_sk required" type="text" maxlength="200" value="{{ $dokumen['nama'] }}"></input>
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
            <div id="d-dokumen" style="display: {{ $dokumen['tipe'] == 2 ? 'none' : '' }};">
                @if ($dokumen['satuan'])
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Dokumen</label>
                        <div class="col-sm-4">
                            <input type="hidden" name="old_file" value="">
                            <i class="fa fa-file-pdf-o pop-up-pdf" aria-hidden="true" style="font-size: 60px;" data-title="Berkas {{ $dokumen['nomor_surat'] }}" data-url="{{ site_url("{$controller}/berkas/{$dokumen['id']}/1/1") }}"></i>

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
            <input name="kategori" type="hidden" value="{{ $dokumen['kategori'] ?: $kat }}">

            @include($isi)

        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                Simpan</button>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/custom-select2.js') }}"></script>
    <script>
        $('#tipe').on('change', function() {
            if (this.value == 1) {
                $('#d-dokumen').show();
                $('#d-url').hide();
                $("#file_path").addClass("required");
                $("#url").removeClass("required");
            } else {
                $('#d-dokumen').hide();
                $('#d-url').show();
                $("#file_path").removeClass("required");
                $("#url").addClass("required");
            }
        });
    </script>
@endpush
