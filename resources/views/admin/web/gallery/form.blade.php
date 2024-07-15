@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        <h1>Pengaturan Album</h1>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('gallery') }}"> Daftar Album</a></li>
    <li class="active">{{ $aksi }} Pengaturan Album</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('gallery') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah">
                <i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Album
            </a>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Nama Album</label>
                <div class="col-sm-6">
                    <input name="nama" class="form-control input-sm nomor_sk required" maxlength="50" type="text" value="{{ $gallery['nama'] }}"></input>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-4" for="nama">Jenis</label>
                <div class="col-sm-6">
                    <select name="jenis" id="jenis" class="form-control input-sm required">
                        <option value="1" @selected($gallery['jenis'] == 1)>File</option>
                        <option value="2" @selected($gallery['jenis'] == 2)>URL</option>
                    </select>
                </div>
            </div>
            <div id="jenis-file">
                @if ($gallery['gambar'])
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="nama"></label>
                        <div class="col-sm-6">
                            <img class="attachment-img img-responsive img-circle" src="{{ AmbilGaleri($gallery['gambar'], 'sedang') }}" alt="Gambar Album">
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <label class="control-label col-sm-4" for="upload">Unggah Gambar</label>
                    <div class="col-sm-6">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control {{ jecho($gallery['gambar'], false, 'required') }}" id="file_path">
                            <input id="file" type="file" class="hidden" name="gambar" accept=".gif,.jpg,.png,.jpeg">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                            </span>
                        </div>
                        <p><label class="control-label">Batas maksimal pengunggahan berkas <strong>{{ max_upload() }} MB.</strong></label></p>
                    </div>
                </div>
            </div>
            <div id="jenis-url" class="form-group">
                <label class="control-label col-sm-4" for="nama">Link/URL</label>
                <div class="col-sm-6">
                    <input id="url" name="url" class="form-control input-sm" type="url" value="{{ $gallery['gambar'] }}" />
                </div>
            </div>
        </div>
        <div class="box-footer">
            {!! batal() !!}
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right confirm"><i class="fa fa-check"></i> Simpan</button>
        </div>
    </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const file_path_required = {{ $file_path_required ? 1 : 0 }}
            $('#jenis').on('change', function() {
                jenis(this.value);
            });

            $('#jenis').trigger('change')

            function jenis(params) {
                if (params == 1) {
                    $('#jenis-file').show();
                    $('#jenis-url').hide();
                    if (file_path_required) {
                        $("#file_path").addClass("required");
                    }
                    $("#url").removeClass("required");
                    $("#url").val('');
                } else {
                    $('#jenis-file').hide();
                    $('#jenis-url').show();
                    $("#file_path").removeClass("required");
                    $("#url").addClass("required");
                }
            }
        });
    </script>
@endpush
