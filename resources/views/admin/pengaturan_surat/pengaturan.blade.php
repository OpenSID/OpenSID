@include('admin.pengaturan_surat.asset_tinymce', ['height' => 250])

@extends('admin.layouts.index')

@section('title')
    <h1>
        Daftar Surat
        <small>{{ $action }} Pengaturan Surat</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('surat_master') }}">Daftar Surat</a></li>
    <li class="active">{{ $action }} Pengaturan Surat</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    {!! form_open($formAksi, 'id="validasi"') !!}
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#header" data-toggle="tab">Header</a></li>
            <li><a href="#footer" data-toggle="tab">Footer</a></li>
            <li><a href="#alur" data-toggle="tab">Alur Surat</a></li>
            <li><a href="#lainnya" data-toggle="tab">Lainnya</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="header">

                @include('admin.pengaturan_surat.kembali')

                <div class="box-body">
                    <div class="form-group">
                        <label>Tinggi Header Surat</label>
                        <div class="input-group">
                            <input type="number" name="tinggi_header" class="form-control input-sm required" min="0"
                                max="100" step="0.01" value="{{ $pengaturanSurat['tinggi_header'] }}" />
                            <span class="input-group-addon input-sm">cm</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Template Header Surat</label>
                        <textarea name="header_surat" class="form-control input-sm editor required">{{ $pengaturanSurat['header_surat'] }}</textarea>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="footer">

                @include('admin.pengaturan_surat.kembali')

                <div class="box-body">
                    <div class="form-group">
                        <label>Tinggi Footer Surat</label>
                        <div class="input-group">
                            <input type="number" name="tinggi_footer" class="form-control input-sm required" min="0"
                                max="100" step="0.01" value="{{ $pengaturanSurat['tinggi_footer'] }}" />
                            <span class="input-group-addon input-sm">cm</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Template Footer Surat</label>
                        <textarea name="footer_surat" class="form-control input-sm editor required">{{ $pengaturanSurat['footer_surat'] }}</textarea>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="alur">

                @include('admin.pengaturan_surat.kembali')

                <div class="box-body">
                    <div class="form-group">
                        <label>Verifikasi Sekretaris Desa</label>
                        <div class="input-group col-xs-12 col-sm-8">
                            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="padding: 0px;">
                                <label
                                    class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($alur['verifikasi_sekdes'] == 1)">
                                    <input type="radio" name="verifikasi_sekdes" class="form-check-input" value="1"
                                        autocomplete="off" @checked($alur['verifikasi_sekdes'] == 1)>Ya</label>
                                <label
                                    class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($alur['verifikasi_sekdes'] == 0)">
                                    <input type="radio" name="verifikasi_sekdes" class="form-check-input" value="0"
                                        autocomplete="off" @checked($alur['verifikasi_sekdes'] == 0)>Tidak
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <label>Verifikasi {{ setting('sebutan_kepala_desa') }}</label>
                        <div class="input-group col-xs-12 col-sm-8">
                            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="padding: 0px;">
                                <label
                                    class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($alur['verifikasi_kades'] == 1)">
                                    <input type="radio" name="verifikasi_kades" class="form-check-input" value="1"
                                        autocomplete="off" @checked($alur['verifikasi_kades'] == 1)>Ya</label>
                                <label
                                    class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($alur['verifikasi_kades'] == 0)">
                                    <input type="radio" name="verifikasi_kades" class="form-check-input" value="0"
                                        autocomplete="off" @checked($alur['verifikasi_kades'] == 0)>Tidak
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="lainnya">
                @include('admin.pengaturan_surat.kembali')
                <div class="box-body">
                    <div class="form-group">
                        <label>Jenis Font Bawaan </label>
                        <div class="row">
                            <div class="col-lg-4 col-md-7 col-sm-12">
                                <select class="select2 form-control" name="font_surat">
                                    @foreach ($fonts as $font)
                                        <option value="{{ $font->font_family }}" @selected($font->font_family === $pengaturanSurat['font_surat'])>
                                            {{ $font->font_family }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
        </div>
    </div>
    </form>

    @include('admin.pengaturan_surat.info')
@endsection
