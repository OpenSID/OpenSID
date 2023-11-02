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
    <li class="breadcrumb-item"><a href="{{ route('surat_master') }}">Daftar Lampiran Surat</a></li>
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
                    <a href="{{ route('surat_master.lampiran') }}"
                        class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Lampiran
                    </a>
                </div>
            
                <div class="box-body form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama Lampiran</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <span class="input-group-addon input-sm">Lampiran</span>
                                <input type="text" class="form-control input-sm nama_terbatas required" id="nama"
                                    name="nama" placeholder="Nama lampiran" value="{{ $lampiranSurat->nama }}" />
                            </div>
                        </div>
                    </div>
            
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="status">Status Lampiran</label>
                        <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                            <label id="lm1"
                                class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($lampiranSurat->status)">
                                <input id="im1" type="radio" name="status" class="form-check-input" type="radio"
                                    value="1" @checked($lampiranSurat->status) autocomplete="off">Aktif
                            </label>
                            <label id="lm2"
                                class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$lampiranSurat->status)">
                                <input id="im2" type="radio" name="status" class="form-check-input" type="radio"
                                    value="0" @checked(!$lampiranSurat->status) autocomplete="off">Tidak Aktif
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="template-lampiran">
                <div class="box-header with-border">
                    <a href="{{ route('surat_master.lampiran') }}"
                        class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Lampiran
                    </a>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <textarea name="template_desa" data-filemanager='<?= json_encode(['external_filemanager_path'=> base_url() . 'assets/filemanager/', 'filemanager_title' => 'Responsive Filemanager', 'filemanager_access_key' => $session->fm_key]) ?>' data-salintemplate="isi" class="form-control input-sm editor required">{{ $lampiranSurat->template_desa ?? $lampiranSurat->template }}</textarea>
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
    </script>
@endpush
