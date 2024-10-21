@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Pengaturan Master Analisis
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('analisis_master') }}">Master Analisis</a></li>
    <li class="active">Pengaturan Master Analisis</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('analisis_master') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left "></i>Kembali ke Master Analisis
            </a>
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="nama">Nama Analisis</label>
                            <div class="col-sm-7">
                                <input
                                    id="nama"
                                    class="form-control input-sm required judul"
                                    maxlength="40"
                                    type="text"
                                    placeholder="Nama Analisa"
                                    name="nama"
                                    value="{{ $analisis_master['nama'] }}"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="nama">Subjek/Unit Analisis</label>
                            <div class="col-sm-7 col-lg-4">
                                <select class="form-control input-sm required" id="subjek_tipe" name="subjek_tipe">
                                    @foreach ($list_subjek as $key => $value)
                                        <option value="{{ $key }}" @selected($analisis_master['subjek_tipe'] == $key)>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class=" col-sm-12">
                        <div class="form-group hide" id="idelik">
                            <label class="col-sm-3 control-label" for="nama">Kategori Kelompok</label>
                            <div class="col-sm-7 col-lg-4">
                                <select class="form-control input-sm" id="id_kelompok" name="id_kelompok" style="width:100%">
                                    <option value="">--Kategori Kelompok--</option>
                                    @foreach ($list_kelompok as $data)
                                        <option value="{{ $data['id'] }}" @selected($analisis_master['id_kelompok'] == $data['id'])>{{ $data['kelompok'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="nama">Status Analisis</label>
                            <div class="col-sm-7 col-lg-4">
                                <select class="form-control input-sm" id="lock" name="lock">
                                    <option value="1" @selected(($analisis_master['lock'] ?? '1') == '1')>Tidak Terkunci</option>
                                    <option value="2" @selected($analisis_master['lock'] == '2')>Terkunci</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Format Impor Tambahan</label>
                            <div class="col-sm-7 col-lg-4">
                                <select class="form-control input-sm" id="format_impor" name="format_impor" @selected($analisis_master['jenis'] == 1)>
                                    <option value="">--Pilih Format Impor--</option>
                                    @foreach ($list_format_impor as $key => $nama)
                                        <option value="{{ $key }}" @selected($analisis_master['format_impor'] == $key)>{{ $nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="kepala_lama">Rumus Penilaian Analisis</label>
                            <div class="col-sm-7">
                                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;margin-bottom: 10px;">
                                    <code>Sigma (Bobot (indikator) x Nilai (parameter)) / Bilangan Pembagi</code>
                                </P>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="pembagi">Bilangan Pembagi</label>
                            <div class="col-sm-7">
                                <input
                                    id="pembagi"
                                    class="form-control input-sm bilangan_titik"
                                    maxlength="10"
                                    type="text"
                                    placeholder="Bilangan Pembagi"
                                    name="pembagi"
                                    value="{{ $analisis_master['pembagi'] }}"
                                >
                                <p class="help-block"><code>Untuk tanda koma "," gunakan tanda titik "." sebagai substitusinya</code></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="nama">Analisis Terhubung</label>
                            <div class="col-sm-7 col-lg-4">
                                <select class="form-control input-sm" id="id_child" name="id_child">
                                    <option value="">-- Silakan Masukan Analisis Terhubung--</option>
                                    @foreach ($list_analisis as $data)
                                        <option value="{{ $data['id'] }}" @selected($analisis_master['id_child'] == $data['id'])>{{ $data['nama'] }}</option>
                                    @endforeach
                                </select>
                                <p class="help-block"><code>Kosongkan jika tidak ada Analisis yang terhubung</code></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="nama">Deskripsi Analisis</label>
                            <div class="col-sm-7">
                                <textarea id="deskripsi" class="form-control input-sm required" placeholder="Deskripsi Analisis" name="deskripsi">{{ $analisis_master['deskripsi'] }}</textarea>
                            </div>
                        </div>
                    </div>
                    @if ($analisis_master['gform_id'])
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="pembagi">ID Google Form</label>
                                <div class="col-sm-7">
                                    <input readonly class="form-control input-sm" value="{{ $analisis_master['gform_id'] }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="pembagi">Sinkronasi Google Form</label>
                                <div class="col-sm-7">
                                    <input readonly class="form-control input-sm" value="{{ tgl_indo($analisis_master['gform_last_sync']) }}">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#subjek_tipe').change(function() {
                if ($(this).val() == 4)
                    $('#idelik').addClass("show").removeClass("hide");
                else
                    $('#idelik').removeClass("show").addClass("hide");
            });
        });
    </script>
@endpush
