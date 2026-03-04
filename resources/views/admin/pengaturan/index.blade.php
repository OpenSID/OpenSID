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

        {!! form_open_multipart(isset($aksi_controller) ? $aksi_controller : ci_route('notif.update_setting'), 'id="validasi" class="form-horizontal"') !!}
        @if ($atur_latar)
            <div class="col-md-3">
                @if (in_array('sistem', $pengaturan_kategori ?? []))
                    @include('admin.layouts.components.box_unggah', ['judul' => 'Latar Website', 'name' => 'latar_website', 'foto' => site_url("setting/ambil_foto?foto={$latar_website[0]}&pengaturan={$latar_website[1]}")])
                    @include('admin.layouts.components.box_unggah', ['judul' => 'Latar Login Admin', 'name' => 'latar_login', 'foto' => site_url("setting/ambil_foto?foto={$latar_siteman[0]}&pengaturan={$latar_siteman[1]}"), 'nomor' => 1])
                    <input type="text" class="hidden" name="lokasi" value="{{ $lokasi }}" />
                @endif
                @if (in_array('Layanan Mandiri', $pengaturan_kategori ?? []))
                    @include('admin.layouts.components.box_unggah', ['judul' => 'Latar Login Mandiri', 'name' => 'latar_login_mandiri', 'foto' => site_url("setting/ambil_foto?foto={$latar_mandiri[0]}&pengaturan={$latar_mandiri[1]}"), 'nomor' => 2])
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
                                <a href="{{ site_url('surat_master') }}" class="small-box-footer">Lihat Pengaturan <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                            <div class="small-box bg-blue">
                                <div class="inner">
                                    <h4>Syarat Surat</h4><br>
                                </div>
                                <div class="icon">
                                    <i class="ion-ios-paper"></i>
                                </div>
                                <a href="{{ site_url('surat_mohon') }}" class="small-box-footer">Lihat Pengaturan <i class="fa fa-arrow-circle-right"></i></a>
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
                <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                    Batal</button>
                @if (can('u', $akses_modul))
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
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

        $('#file').change(function() {
            previewImage(this, '.preview-img');
        });

        $('#file1').change(function() {
            previewImage(this, '.preview-img-1');
        });

        $('#file2').change(function() {
            previewImage(this, '.preview-img-2');
        });
    </script>
@endpush
