@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.asset_datatables')
@include('admin.pengaturan_surat.asset_tinymce')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Daftar Lampiran Surat
        <small>{{ $action }} Lampiran Surat</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('lampiran') }}">Daftar Lampiran Surat</a></li>
    <li class="active">{{ $action }} Lampiran Surat</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    {!! form_open($formAction, 'id="validasi" enctype="multipart/form-data"') !!}
    <input type="hidden" id="id_surat" name="id_surat" value="{{ $lampiranSurat->id }}">
    <div class="nav-tabs-custom">
        <div class="container-fluid identitas-surat">
            <h4>Lampiran {{ $lampiranSurat->nama ?? '' }}</h4>
        </div>
        <ul class="nav nav-tabs" id="tabs">
            <li class="active"><a href="#pengaturan-umum" data-toggle="tab">Umum</a></li>
            <li><a href="#template-lampiran" data-toggle="tab">Template</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="pengaturan-umum">
                <div class="box-header with-border">
                    <a href="{{ ci_route('lampiran') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Lampiran
                    </a>
                </div>

                <div class="box-body form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Lampiran</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <span class="input-group-addon input-sm">Lampiran</span>
                                <input type="text" class="form-control input-sm nama_desa required" id="nama" name="nama" placeholder="Nama lampiran" value="{{ $lampiranSurat->nama }}" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="status">Status Lampiran</label>
                        <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                            <label id="lm1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($lampiranSurat->status)">
                                <input
                                    id="im1"
                                    type="radio"
                                    name="status"
                                    class="form-check-input"
                                    type="radio"
                                    value="1"
                                    @checked($lampiranSurat->status)
                                    autocomplete="off"
                                >Aktif
                            </label>
                            <label id="lm2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$lampiranSurat->status)">
                                <input
                                    id="im2"
                                    type="radio"
                                    name="status"
                                    class="form-check-input"
                                    type="radio"
                                    value="0"
                                    @checked(!$lampiranSurat->status)
                                    autocomplete="off"
                                >Tidak Aktif
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Gunakan Margin Kertas Global</label>
                        <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="margin: 0 0 5px 0">
                            <label id="lmg1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($margin_global)">
                                <input
                                    id="img1"
                                    type="radio"
                                    name="margin_global"
                                    @checked($margin_global)
                                    class="form-check-input"
                                    type="radio"
                                    value="1"
                                    autocomplete="off"
                                >Ya
                            </label>
                            <label id="lmg2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$margin_global)">
                                <input
                                    id="img2"
                                    type="radio"
                                    name="margin_global"
                                    class="form-check-input"
                                    @checked(!$margin_global)
                                    type="radio"
                                    value="0"
                                    autocomplete="off"
                                >Tidak
                            </label>
                        </div>
                        <div id="manual_margin" style="display: none;">
                            <div class="col-sm-7 col-sm-offset-3">
                                <div class="row">
                                    @foreach ($margins as $key => $value)
                                        <div class="col-sm-6">
                                            <div class="input-group" style="margin-top: 3px; margin-bottom: 3px">
                                                <span class="input-group-addon input-sm">{{ ucwords($key) }}</span>
                                                <input
                                                    type="number"
                                                    class="form-control input-sm required"
                                                    min="0"
                                                    name="margin[{{ $key }}]"
                                                    min="0"
                                                    max="10"
                                                    step="0.01"
                                                    style="text-align:right;"
                                                    value="{{ $value }}"
                                                >
                                                <span class="input-group-addon input-sm">cm</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Orientasi Kertas</label>
                        <div class="col-sm-7">
                            <select class="form-control input-sm select2-tags required" name="orientasi">
                                @foreach ($orientations as $value)
                                    <option value="{{ $value }}" @selected(($lampiranSurat->orientasi ?? $default_orientations) === $value)>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Ukuran Kertas</label>
                        <div class="col-sm-7">
                            <select class="form-control input-sm select2-tags required" name="ukuran">
                                @foreach ($sizes as $value)
                                    <option value="{{ $value }}" @selected(($lampiranSurat->ukuran ?? $default_sizes) === $value)>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="template-lampiran">
                <div class="box-header with-border">
                    <a href="{{ ci_route('lampiran') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Lampiran
                    </a>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <textarea name="template_desa" data-filemanager='{!! json_encode(['external_filemanager_path' => base_url() . 'assets/filemanager/', 'filemanager_title' => 'Responsive Filemanager', 'filemanager_access_key' => $session->fm_key], JSON_THROW_ON_ERROR) !!}' data-salintemplate="isi" class="form-control input-sm editor required">{{ $lampiranSurat->template_desa ?? $lampiranSurat->template }}</textarea>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());">
                    <i class="fa fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>Simpan</button>
            </div>
        </div>
    </div>
    </form>
@endsection

@push('scripts')
    <script>
        $('#validasi').submit(function() {
            tinymce.triggerSave()
        });

        function reset_form() {
            $(".tipe").removeClass("active");

            var status = "{{ $lampiranSurat->status }}";
            if (status == 1) {
                $("#lm1").addClass('active');
                $("#im1").prop("checked", true);
            } else {
                $("#lm2").addClass('active');
                $("#im2").prop("checked", true);
            }
        };

        $(document).ready(function() {
            var x = $("[name='margin_global']:checked").val()
            if (x == 0) {
                $('#manual_margin').show()
            }

            $("[name='margin_global']").change(function() {
                var val = $(this).val()
                if (val == 0) {
                    $('#manual_margin').show()
                } else {
                    $('#manual_margin').hide()
                }
            })
        })
    </script>
@endpush
