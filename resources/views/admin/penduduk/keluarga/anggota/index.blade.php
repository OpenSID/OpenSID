@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        Daftar Anggota Keluarga
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('keluarga') }}">Daftar Anggota Keluarga</a></li>
    <li class="active">Daftar Anggota Keluarga</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <div class="btn-group btn-group-vertical">
                    <a class="btn btn-social btn-success btn-sm" data-toggle="dropdown"><i class='fa fa-plus'></i> Tambah Anggota</a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ ci_route('keluarga.form_peristiwa.1', $kk) }}" class="btn btn-social btn-block btn-sm" title="Anggota Keluarga Lahir"><i class="fa fa-plus"></i> Anggota Keluarga Lahir</a>
                        </li>
                        <li>
                            <a href="{{ ci_route('keluarga.form_peristiwa.5', $kk) }}" class="btn btn-social btn-block btn-sm" title="Anggota Keluarga Masuk"><i class="fa fa-plus"></i> Anggota Keluarga Masuk</a>
                        </li>
                        <li>
                            <a
                                href="{{ ci_route('keluarga.ajax_add_anggota', $kk) }}"
                                class="btn btn-social btn-block btn-sm"
                                title="Tambah Anggota Dari Penduduk Yang Sudah Ada"
                                data-remote="false"
                                data-toggle="modal"
                                data-target="#modalBox"
                                data-title="Tambah Anggota Keluarga"
                            ><i class="fa fa-plus"></i> Dari Penduduk Sudah Ada</a>
                        </li>
                    </ul>
                </div>
            @endif
            <a href="{{ ci_route('keluarga.kartu_keluarga', $kk) }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-book"></i> Kartu Keluarga</a>
            <a href="{{ ci_route('keluarga') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Keluarga"><i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Keluarga
            </a>
        </div>
        <div class="box-body">
            <h5><b>Rincian Keluarga</b></h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover tabel-rincian">
                    <tbody>
                        <tr>
                            <td width="20%">Nomor Kartu Keluarga (KK)</td>
                            <td width="1%">:</td>
                            <td>{{ $no_kk }}</td>
                        </tr>
                        <tr>
                            <td>Kepala Keluarga</td>
                            <td>:</td>
                            <td>{{ $kepala_kk['nama'] }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>{{ $kepala_kk['alamat_wilayah'] }}</td>
                        </tr>
                        <tr>
                            <td>
                                {!! $program['programkerja'] ? anchor("peserta_bantuan/peserta/2/{$no_kk}", 'Program Bantuan', 'target="_blank"') : 'Program Bantuan' !!}
                            </td>
                            <td>:</td>
                            <td>
                                @if ($program['programkerja'])
                                    @foreach ($program['programkerja'] as $item)
                                        {!! anchor("peserta_bantuan/data_peserta/{$item['id']}", '<span class="label label-success">' . $item['bantuan']['nama'] . '</span>&nbsp;', 'target="_blank"') !!}
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-body">
            <h5><b>Daftar Anggota Keluarga</b></h5>
            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <form id="mainform" name="mainform" method="post">
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <th>No</th>
                                    @if (can('u'))
                                        <th>Aksi</th>
                                    @endif
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Hubungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($main as $key => $data)
                                    <tr>
                                        <td class="padat">{{ $key + 1 }} </td>
                                        @if (can('u'))
                                            <td class="aksi">
                                                <a href="{{ ci_route("penduduk.form.{$data['id']}") }}" class="btn bg-orange btn-sm" title="Ubah Biodata Penduduk"><i class="fa fa-edit"></i></a>
                                                @if ($data['bisaPecahKK'])
                                                    <a
                                                        href="#"
                                                        data-href="{{ ci_route('keluarga.delete_anggota.' . $kk, $data['id']) }}"
                                                        class="btn bg-purple btn-sm"
                                                        title="Pecah KK"
                                                        data-toggle="modal"
                                                        data-target="#confirm-status"
                                                        data-body="Apakah Anda yakin ingin memecah Data Keluarga ini?"
                                                    ><i class="fa fa-cut"></i></a>
                                                @endif
                                                @if ($kepala_kk['status_dasar'] == 1)
                                                    <a
                                                        href="{{ ci_route('keluarga.edit_anggota.' . $kk, $data['id']) }}"
                                                        data-remote="false"
                                                        data-toggle="modal"
                                                        data-target="#modalBox"
                                                        data-title="Ubah Hubungan Keluarga"
                                                        title="Ubah Hubungan Keluarga"
                                                        class="btn bg-navy btn-sm"
                                                    ><i class='fa fa-link'></i></a>
                                                @endif
                                                @if ($data['kk_level'] != 1)
                                                    <a
                                                        href="#"
                                                        data-href="{{ ci_route('keluarga.keluarkan_anggota.' . $kk, $data['id']) }}"
                                                        class="btn bg-maroon btn-sm"
                                                        title="Bukan anggota keluarga ini"
                                                        data-toggle="modal"
                                                        data-target="#confirm-status"
                                                        data-body="Apakah yakin akan dikeluarkan dari keluarga ini?"
                                                    ><i class="fa fa-times"></i></a>
                                                @endif
                                        @endif
                                        </td>
                                        <td>{{ $data['nik'] }}</td>
                                        <td nowrap width="45%">{{ strtoupper($data['nama']) }}</td>
                                        <td nowrap>{{ tgl_indo($data['tanggallahir']) }}</td>
                                        <td>{{ $data['sex'] }}</td>
                                        <td nowrap>{{ $data['hubungan'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin.layouts.components.konfirmasi', ['periksa_data' => true])
@endsection
