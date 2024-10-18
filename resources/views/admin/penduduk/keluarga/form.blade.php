@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.datetime_picker')
@section('title')
    <h1>
        Data Keluarga
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('keluarga') }}"> Daftar Keluarga</a></li>
    <li class="active">Data Keluarga</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="">
        <form id="mainform" name="mainform" action="{{ $form_action }}" method="post" enctype="multipart/form-data">
            <div class="row">
                <div id="nik_kepala" name="nik_kepala"></div>
                <div class="col-md-12">
                    <div class='box box-primary'>
                        <div class="box-header with-border">
                            <a href="{{ site_url('keluarga') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Penduduk">
                                <i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Keluarga
                            </a>
                        </div>
                        <div class='box-body'>
                            <div class="row">
                                <div class='col-sm-7'>
                                    <div class='form-group'>
                                        <label for="no_kk"> Nomor KK <code id="tampil_nokk" style="display: none;">
                                                (Sementara) </code></label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon">
                                                <input type="checkbox" title="Centang jika belum memiliki No. KK" id="nokk_sementara" @checked($cek_nokk == '0')>
                                            </span>
                                            <input
                                                id="no_kk"
                                                name="no_kk"
                                                class="form-control input-sm required no_kk"
                                                type="text"
                                                placeholder="Nomor KK"
                                                value="{{ $no_kk }}"
                                                @readonly($cek_nokk == '0')
                                            ></input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-sm-12'>
                            <div class="form-group bg-primary" style="padding:10px">
                                <strong>DATA KEPALA KELUARGA :</strong>
                            </div>
                        </div>
                        @include('admin.penduduk.penduduk_form_isian')
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#nokk_sementara').change(function() {
                var cek_nokk = '{{ $cek_nokk }}';
                var nokk_sementara_berikut = '{{ $nokk_sementara }}';
                var nokk_asli = '{{ $no_kk }}';
                if ($('#nokk_sementara').prop('checked')) {
                    $('#no_kk').removeClass('no_kk');
                    if (cek_nokk != '0') $('#no_kk').val(nokk_sementara_berikut);
                    $('#no_kk').prop('readonly', true);
                    $('#tampil_nokk').show();
                } else {
                    $('#no_kk').addClass('no_kk');
                    $('#no_kk').val(nokk_asli);
                    $('#no_kk').prop('readonly', false);
                    $('#tampil_nokk').hide();
                }
            });

            $('#nokk_sementara').change();
        });
    </script>
@endpush
