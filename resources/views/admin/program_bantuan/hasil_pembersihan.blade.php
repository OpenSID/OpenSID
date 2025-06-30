@include('admin.pengaturan_surat.asset_tinymce')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@extends('admin.layouts.index')

@section('title')
    <h1>Pembersihan Data Peserta Program Bantuan</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('program_bantuan') }}"> Daftar Program Bantuan</a></li>
    <li class="active">Pembersihan Data Peserta Program Bantuan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-12">
            <form id="mainform" name="mainform" method="post">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <a href="<?= site_url('program_bantuan') ?>" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar
                            Program Bantuan</a>
                        @if (can('h'))
                            <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url('program_bantuan/bersihkan_data_peserta') ?>')"
                                class="btn btn-social	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"
                            ><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
                        @endif
                    </div>
                    <div class="box-body">
                        <h4>Data Peserta Tidak Valid</h4>
                        <p>Sasaran (penduduk, keluarga, rumah tangga, kelompok) peserta tidak valid berikut telah dihapus dari program bantuannya.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered  table-striped table-hover tabel-rincian">
                                <tbody>
                                    <tr>
                                        <th><input type="checkbox" id="checkall" /></th>
                                        <th>No.</td>
                                        <th>Program Bantuan</td>
                                        <th>Sasaran</td>
                                        <th>ID Peserta (NIK/No KK/Kode RTM/Kode Kelompok)</td>
                                        <th>Nama Kartu Peserta</td>
                                    </tr>
                                    @forelse ($invalid as $key => $peserta)
                                        <tr>
                                            <td class="padat"><input type="checkbox" name="id_cb[]" value="{{ $peserta->id }}"></td>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $peserta->nama }}</td>
                                            <td>{{ $ref_sasaran[$peserta->sasaran] }}</td>
                                            <td>{{ $peserta->peserta }}</td>
                                            <td>{{ $peserta->kartu_nama }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada peserta yang tidak valid</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <h4>Data Peserta Duplikat</h4>
                        <p>Peserta duplikat berikut telah dihapus dari program bantuannya.</p>
                        <div class="table-responsive">
                            <table class="table table-bordered  table-striped table-hover tabel-rincian">
                                <tbody>
                                    <tr>
                                        <th><input type="checkbox" id="checkall" /></th>
                                        <th>No.</td>
                                        <th>Program Bantuan</td>
                                        <th>Sasaran</td>
                                        <th>ID Peserta (NIK/No KK/Kode RTM/Kode Kelompok)</td>
                                        <th>Nama Kartu Peserta</td>
                                        <th>Jumlah Duplikat</th>
                                    </tr>
                                    @forelse ($duplikat as $key => $peserta)
                                        <tr>
                                            <td class="padat"><input type="checkbox" name="id_cb[]" value="{{ $peserta->id }}"></td>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $peserta->nama }}</td>
                                            <td>{{ $ref_sasaran[$peserta->sasaran] }}</td>
                                            <td>{{ $peserta->peserta }}</td>
                                            <td>{{ $peserta->kartu_nama }}</td>
                                            <td>{{ $peserta->jumlah }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada peserta duplikat</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@include('admin.layouts.components.konfirmasi_hapus')
@include('admin.layouts.components.konfirmasi')
