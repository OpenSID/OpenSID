@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        {{ $judul }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $judul }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">

        {!! form_open_multipart(route('setting.new_update'), 'id="validasi" class="form-horizontal"') !!}
        @if ($atur_latar)
            <div class="col-md-3">
                @if (in_array('sistem', $pengaturan_kategori))
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <b>Latar Website</b>
                        </div>
                        <div class="box-body box-profile text-center">
                            <img class="img-responsive" src="{{ $latar_website }}" alt="Latar Halaman Website" width="100%">
                            <p class="text-muted text-center text-red">(Kosongkan, jika latar website tidak berubah)</p>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" id="file_path" name="latar_website">
                                <input type="file" class="hidden" id="file" name="latar_website"
                                    accept=".jpg,.jpeg,.png" />
                                <input type="text" class="hidden" name="lokasi" value="{{ $lokasi }}" />
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat btn-sm" id="file_browser"><i
                                            class="fa fa-search"></i>&nbsp;</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <b>Latar Login Admin</b>
                        </div>
                        <div class="box-body box-profile text-center">
                            <img class="img-responsive" src="{{ $latar_siteman }}" alt="Latar Halaman Login" width="100%">
                            <p class="text-muted text-center text-red">(Kosongkan, jika latar login tidak berubah)</p>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" id="file_path1" name="latar_login" />
                                <input type="file" class="hidden" id="file1" name="latar_login"
                                    accept=".jpg,.jpeg,.png" />
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat btn-sm" id="file_browser1"><i
                                            class="fa fa-search"></i>&nbsp;</button>
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
                @if (in_array('setting_mandiri', $pengaturan_kategori))
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <b>Latar Login Mandiri</b>
                        </div>
                        <div class="box-body box-profile text-center">
                            <img class="img-responsive" src="{{ $latar_mandiri }}" alt="Latar Halaman Login" width="100%">
                            <p class="text-muted text-center text-red">(Kosongkan, jika latar login tidak berubah)</p>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" id="file_path2"
                                    name="latar_login_mandiri" />
                                <input type="file" class="hidden" id="file2" name="latar_login_mandiri"
                                    accept=".jpg,.jpeg,.png" />
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat btn-sm" id="file_browser2"><i
                                            class="fa fa-search"></i>&nbsp;</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <b>Pintasan</b>
                        </div>
                        <div class="box-body box-profile">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h4>Pengaturan Surat</h4><br>
                                </div>
                                <div class="icon">
                                    <i class="ion-ios-paper"></i>
                                </div>
                                <a href="{{ site_url('surat_master') }}" class="small-box-footer">Lihat Pengaturan <i
                                        class="fa fa-arrow-circle-right"></i></a>
                            </div>
                            <div class="small-box bg-blue">
                                <div class="inner">
                                    <h4>Syarat Surat</h4><br>
                                </div>
                                <div class="icon">
                                    <i class="ion-ios-paper"></i>
                                </div>
                                <a href="{{ site_url('surat_mohon') }}" class="small-box-footer">Lihat Pengaturan <i
                                        class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-9">
            @else
                <div class="col-md-12">
        @endif
        <div class="box box-primary">
            <div class="box-header with-border">
                <b>Pengaturan Dasar</b>
            </div>
            <div class="box-body">
                @include('admin.pengaturan.form')
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i>
                    Batal</button>
                @if ($ci->cek_hak_akses_url('u', $aksi_controller))
                    <button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i
                            class="fa fa-check"></i> Simpan</button>
                @endif
            </div>
        </div>
    </div>
    </form>
    </div>
    </section>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $("#form_tampilan_anjungan_video").hide();
        var e = document.getElementById("tampilan_anjungan");

        function show() {
            var as = document.forms[0].tampilan_anjungan.value;
            var strUser = e.options[e.selectedIndex].value;
            if (as == 1) {
                $('#form_tampilan_anjungan_slider').show();
                $('#form_tampilan_anjungan_audio').hide();
                $('#form_tampilan_anjungan_video').hide();
                $('#form_tampilan_anjungan_waktu').show();
            } else if (as == 2) {
                $('#form_tampilan_anjungan_slider').hide();
                $('#form_tampilan_anjungan_audio').show();
                $('#form_tampilan_anjungan_video').show();
                $('#form_tampilan_anjungan_waktu').show();
            } else {
                $('#form_tampilan_anjungan_slider').hide();
                $('#form_tampilan_anjungan_video').hide();
                $('#form_tampilan_anjungan_waktu').hide();
            }
        }
        if (e != null) {
            e.onchange = show;
            show();
        }
    </script>
@endpush
