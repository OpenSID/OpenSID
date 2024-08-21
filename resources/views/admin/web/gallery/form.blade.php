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
                        <p><label class="control-label">Batas maksimal pengunggahan berkas <strong>2 MB.</strong></label></p>
                    </div>
                </div>
            </div>
            <div id="jenis-url" class="form-group">
                <label class="control-label col-sm-4" for="url">Link/URL</label>
                <div class="col-sm-6">
                    <div class="input-group input-group-sm">
                        <input id="url" name="url" class="form-control input-sm" type="url" value="{{ $gallery['gambar'] }}" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info btn-danger btn-sm" id="kosongkan"><i class="fa fa-refresh" title="Kosongkan"></i>&nbsp;</button>
                            <button type="button" class="btn btn-info btn-info btn-sm" id="file_browser2" data-toggle="modal" data-target="#FileManager"><i class="fa fa-search"></i>&nbsp;</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            {!! batal() !!}
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right confirm"><i class="fa fa-check"></i> Simpan</button>
        </div>
    </div>
    </form>

    <!-- File Manager -->
    <div class="modal fade" id="FileManager" role="dialog" aria-labelledby="FileManagerLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title' id='FileManagerLabel'>File Manager</h4>
                </div>
                <div class="modal-body">
                    <iframe width="100%" height="400px" src="{{ base_url('assets/kelola_file/dialog.php?type=1&lang=id&field_id=url&fldr=&akey=' . $session->fm_key) }}" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>
                </div>
            </div>
        </div>
    </div>
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

            $('#kosongkan').on('click', function() {
                $('#url').val('');
            });
        });
    </script>
@endpush
