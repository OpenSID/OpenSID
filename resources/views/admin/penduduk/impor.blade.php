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
            <a href="{{ route('penduduk') }}"
                class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                title="Kembali Ke Data Penduduk"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Data Penduduk</a>
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'class="form-horizontal" id="impor" enctype="multipart/form-data"') !!}
            <div class="row">
                <div class="col-sm-12">
                    <p>
                        <b>Penting: fitur ini tidak dimaksudkan untuk Restore data penduduk dan Mengubah struktur dan
                            keanggotaan keluarga
                        </b>
                    </p>
                    <p>Fitur ini dimaksudkan untuk memasukkan data penduduk awal dan data susulan serta mengubah data
                        penduduk yang sudah ada secara masal
                    </p>
                    <p>Mempersiapkan data dengan bentuk excel untuk Impor ke dalam database SID : </p>
                    <p>
                        <div class="col-sm-12">
                            <div class="row">
                                <ol>
                                    <li value="1">Pastikan format data yang akan diImpor sudah sesuai dengan aturan Impor data:
                                    </li>
                                        <ul class="col-sm-12">
                                            <li> Boleh menggunakan tanda ' (petik satu) dalam penggunaan nama</li>
                                            <li> Kolom Nama, Dusun, RW, RT dan NIK harus diisi. Tanda '-' bisa dipakai di mana RW
                                                atau RT tidak diketahui atau tidak ada</li>
                                            <li> Data Penduduk yang dapat menampilkan data RT/RW/Dusun pada tabel Kependudukan
                                                adalah Status Hubungan Dalam Keluarga = Kepala Keluarga atau penduduk yang memiliki
                                                Kepala Keluarga</li>
                                            <li> NIK harus bilangan dengan 16 angka atau 0 untuk menunjukkan belum ada NIK</li>
                                            <li> Kolom NIK merupakan data identitas wajib yang harus diisi</li>
                                            <li> Selain data identitas wajib (NIK), kolom data tidak harus terurut ataupun lengkap.
                                                Sebagai contoh, dapat digunakan untuk mengubah nomor telepon saja secara masal</li>
                                            <li> Data penduduk baru yang ditambah juga wajib berisi Nama, No KK, SHDK (status
                                                hubungan dalam keluarga), Dusun, RW, RT</li>
                                            <li> Terdapat beberapa data yang terwakili dengan Kode Nomor yang dapat diisi dengan
                                                kode nomor ataupun tulisan seperti jenis kelamin. Selengkapnya dapat dilihat pada
                                                berkas <b>Aturan dan contoh format</b></li>
                                            <li> <b>Penduduk baru tidak dapat ditambahkan apabila data dinyatakan sudah lengkap</b>
                                            </li>
                                        </ul>
                                    <li>Simpan (Save) berkas Excel sebagai .xlsx </li>
                                    <li>Pastikan format excel ber-ekstensi .xlsx (format Excel versi 2007 ke atas)</li>
                                    <li>Data yang dibutuhkan untuk Impor dengan memenuhi urutan format dan aturan data pada tautan
                                        di bawah ini :
                                        <div class="timeline-footer col-sm-12">
                                            <a href="{{ asset('import/FormatImporExcel.xlsm') }}"
                                                class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block margin"
                                                wrap><i class="fa fa-download"></i> Aturan dan Contoh Format Data</a>
                                            <a href="{{ asset('import/contoh_penduduk.xlsx') }}"
                                                class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block margin"
                                                wrap><i class="fa fa-download"></i> Contoh Data Penduduk Ekspor</a>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </p>
                    <p>Berkas pada tautan tersebut dapat dipergunakan untuk memasukkan data penduduk. Klik 'Enable Macros'
                        pada waktu membuka berkas tersebut.
                    </p>
                    <p>
                        <p>Batas maksimal pengunggahan berkas <strong>{{ max_upload() }} MB.</strong></p>
                        <p>Proses ini akan membutuhkan waktu beberapa menit, menyesuaikan dengan spesifikasi komputer server SID
                            dan sambungan internet yang tersedia.
                        </p>
                    </p>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td style="padding-top:20px;padding-bottom:10px;">
                                    <div class="form-group">
                                        <label for="file" class="col-md-2 col-lg-3 control-label">Pilih File
                                            .xlsx:</label>
                                        <div class="col-sm-12 col-md-5 col-lg-5">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" id="file_path_penduduk"
                                                    name="userfile">
                                                <input type="file" class="hidden" id="file_penduduk" name="userfile"
                                                    accept="application/octet-stream, application/vnd.ms-excel, application/x-csv, text/x-csv, text/csv, application/csv, application/excel, application/vnd.msexcel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel.sheet.macroenabled.12" />
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info"
                                                        id="file_browser_penduduk"><i class="fa fa-search"></i>
                                                        Browse</button>
                                                </span>
                                            </div>
                                            @if ($boleh_hapus_penduduk)
                                                <p class="help-block"><input type="checkbox" name="hapus_data"
                                                        value="hapus"></input> Hapus data penduduk sebelum Impor</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-12 col-md-5 col-lg-4">
                                            <a href="#" class="btn btn-block btn-success btn-sm"
                                                title=" Impor Data Penduduk Hapus data penduduk sebelum impor "
                                                onclick="document.getElementById('impor').submit();" data-toggle="modal"
                                                data-target="#loading"> <i class="fa fa-spin fa-refresh"></i> Impor Data
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
                                            <dt>Jumlah Data Ganda : </dt>
                                            <dd>{{ $pesan_impor['ganda'] }}</dd>
                                        </dl>
                                    </td>
                                </tr>
                                @if ($pesan_impor['pesan'])
                                    <tr>
                                        <td>
                                            <dl class="dl-horizontal">
                                                <dt>Rincian Pesan : </dt>
                                                <dd>{!! $pesan_impor['pesan'] !!}</dd>
                                            </dl>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <dl class="dl-horizontal">
                                            <dt>Total Data Berhasil :</dt>
                                            <dd>{{ $pesan_impor['sukses'] }}</dd>
                                        </dl>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            @include('admin.penduduk.proses')

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('document').ready(function() {
            $('#file_browser_penduduk').click(function(e) {
                e.preventDefault();
                $('#file_penduduk').click();
            });

            $('#file_penduduk').change(function() {
                $('#file_path_penduduk').val($(this).val());
            });

            $('#file_path_penduduk').click(function() {
                $('#file_browser_penduduk').click();
            });
        });
    </script>
@endpush
