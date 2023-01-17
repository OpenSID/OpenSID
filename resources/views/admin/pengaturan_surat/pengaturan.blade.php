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
