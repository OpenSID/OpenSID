@extends('admin.layouts.index')

@section('title')
    <h1>
        Impor Data Kependudukan
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Impor Data Kependudukan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('penduduk') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Data Penduduk"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Data Penduduk</a>
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'class="form-horizontal" id="impor" enctype="multipart/form-data"') !!}
            <p><b>Penting: Impor BIP hanya bisa dilakukan ketika data penduduk belum ada</b></p>
            <p>Proses ini untuk mengimpor data Buku Induk Penduduk (BIP) yang diperoleh dari Disdukcapil dalam format Excel.
            </p>
            <p>BIP yang dapat dibaca proses ini adalah yang tersusun berdasarkan keluarga, seperti contoh yang dapat dilihat
                pada tautan berikut :</P>
            <a class="btn btn-social btn-info btn-sm btn-margin visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="{{ asset('import/format_bip_2012.xls') }}"><i class="fa fa-download"></i>Contoh BIP 2012</a>
            <a class="btn btn-social btn-info btn-sm btn-margin visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="{{ asset('import/format_bip_2016.xls') }}"><i class="fa fa-download"></i>Contoh BIP 2016</a>
            <a class="btn btn-social btn-info btn-sm btn-margin visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="{{ asset('import/format_bip_ektp.xls') }}"><i class="fa fa-download"></i>Contoh BIP eKTP</a>
            <a class="btn btn-social btn-info btn-sm btn-margin visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="{{ asset('import/format_bip_2016_luwutimur.xls') }}"><i class="fa fa-download"></i>Contoh BIP 2016
                Luwu Timur</a>
            <a class="btn btn-social btn-info btn-sm btn-margin visible-xs-block visible-sm-block visible-md-inline-block visible-lg-inline-block" href="{{ asset('import/format_siak.xls') }}"><i class="fa fa-download"></i>Contoh Data SIAK</a>
            <p></p>
            <p>Proses ini mengimpor data keluarga di semua worksheet di berkas BIP. Misalnya, apabila data BIP tersusun
                menjadi satu worksheet per dusun, proses ini akan mengimpor data semua dusun.</p>
            <p class="text-muted text-red well well-sm no-shadow" style="margin-top: 10px;">
                <small>
                    <strong>
                        <i class="fa fa-info-circle text-red"></i> Pastikan berkas BIP format Excel 2003, ber-ekstensi .xls
                        <br>
                        <i class="fa fa-info-circle text-red"></i> Sebelum di-impor ganti semua format tanggal (seperti
                        tanggal lahir) menjadi dd/mm/yyyy (misalnya 26/07/1964).
                    </strong>
                </small>
            </p>
            <p>
            <p>Batas maksimal pengunggahan berkas <strong>{{ max_upload() }} MB.</strong></p>
            <p>Proses ini akan membutuhkan waktu beberapa menit, menyesuaikan dengan spesifikasi komputer server SID,
                banyaknya data dan sambungan internet yang tersedia.</p>
            </p>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td style="padding-top:20px;padding-bottom:10px;">
                            <div class="form-group">
                                <label for="file" class="col-md-2 col-lg-3 control-label">Pilih File .xls:</label>
                                <div class="col-sm-12 col-md-5 col-lg-5">
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="file_path_bip" name="userfile">
                                        <input type="file" class="hidden" id="file_bip" name="userfile"
                                            accept="application/octet-stream, application/vnd.ms-excel, application/x-csv, text/x-csv, text/csv, application/csv, application/excel, application/vnd.msexcel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel.sheet.macroenabled.12"
                                        />
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-info" id="file_browser_bip"><i class="fa fa-search"></i> Browse</button>
                                        </span>
                                    </div>
                                    @if ($boleh_hapus_penduduk)
                                        <p class="help-block"><input type="checkbox" name="hapus_data" value="hapus"></input> Hapus data penduduk sebelum Impor</p>
                                    @endif
                                </div>
                                <div class="col-sm-12 col-md-5 col-lg-4">
                                    <a href="#" class="btn btn-block btn-success btn-sm" title="Impor Database" onclick="document.getElementById('impor').submit();" data-toggle="modal" data-target="#loading"> <i class="fa fa-spin fa-refresh"></i> Impor Data
                                        Penduduk</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @if ($pesan_impor = session('pesan_impor'))
                        <tr>
                            <td>
                                <dl class="dl-horizontal">
                                    <dt>Jumlah Data Gagal : </dt>
                                    <dd>{{ $pesan_impor['gagal'] }}</dd>
                                </dl>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <dl class="dl-horizontal">
                                    <dt>Letak Baris Data Gagal : </dt>
                                    <dd>{!! $pesan_impor['baris'] !!}</dd>
                                </dl>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <dl class="dl-horizontal">
                                    <dt>Total Keluarga Terimpor :</dt>
                                    <dd>{{ $pesan_impor['total_keluarga'] }}</dd>
                                </dl>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <dl class="dl-horizontal">
                                    <dt>Total Penduduk Terimpor :</dt>
                                    <dd>{{ $pesan_impor['total_penduduk'] }}</dd>
                                </dl>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            </form>
        </div>

        @include('admin.penduduk.proses')

    </div>
@endsection

@push('scripts')
    <script>
        $('document').ready(function() {
            $('#file_browser_bip').click(function(e) {
                e.preventDefault();
                $('#file_bip').click();
            });

            $('#file_bip').change(function() {
                $('#file_path_bip').val($(this).val());
            });

            $('#file_path_bip').click(function() {
                $('#file_browser_bip').click();
            });
        });
    </script>
@endpush
