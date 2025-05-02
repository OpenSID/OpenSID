@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        Pengelompokan Rumah Tangga
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('rtm') }}">Daftar Rumah Tangga</a></li>
    <li class="active">Daftar Anggota Rumah Tangga</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u') && (string) $kepala_kk['status_dasar'] === '1')
                <a
                    href="{{ ci_route('rtm.ajax_add_anggota', $kk) }}"
                    data-remote="false"
                    data-toggle="modal"
                    data-target="#modalBox"
                    data-title="Tambah Anggota Rumah Tangga"
                    title="Tambah Anggota Dari Penduduk Yang Sudah Ada"
                    class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                ><i class='fa fa-plus'></i> Tambah Anggota</a>
            @endif
            @if (can('h'))
                <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','{{ ci_route('rtm.delete_all_anggota', $kk) }}')" class="btn btn-social	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                        class='fa fa-trash-o'
                    ></i> Hapus</a>
            @endif
            <a href="{{ ci_route('rtm.kartu_rtm', $kk) }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-book"></i> Kartu Rumah Tangga</a>
            <a href="{{ ci_route('rtm') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Rumah Tangga">
                <i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Rumah Tangga
            </a>
        </div>
        <div class="box-body">
            <h5><b>Rincian Keluarga</b></h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover tabel-rincian">
                    <tbody>
                        <tr>
                            <td width="20%">Nomor Rumah Tangga (RT)</td>
                            <td width="1%">:</td>
                            <td>{{ $kepala_kk['no_kk'] }}</td>
                        </tr>
                        <tr>
                            <td>Kepala Rumah Tangga</td>
                            <td>:</td>
                            <td>{{ $kepala_kk['nama'] }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>{{ $kepala_kk['alamat_wilayah'] }}</td>
                        </tr>
                        <tr>
                            <td>Jumlah KK</td>
                            <td>:</td>
                            <td>{{ $kepala_kk['jumlah_kk'] }} </td>
                        </tr>
                        <tr>
                            <td>Jumlah Anggota</td>
                            <td>:</td>
                            <td>{{ count($main) }} </td>
                        </tr>
                        <tr>
                            <td>BDT</td>
                            <td>:</td>
                            <td>{{ $kepala_kk['bdt'] ?? '-' }} </td>
                        </tr>
                        <tr>
                            <td>
                                @if ($program['programkerja'])
                                    {!! anchor("peserta_bantuan/peserta/3/{$kepala_kk['no_kk']}", 'Program Bantuan', 'target="_blank"') !!}
                                @else
                                    Program Bantuan
                                @endif
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
            <h5><b>Daftar Anggota</b></h5>
            <form id="mainform" name="mainform" method="post">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                        <thead class="bg-gray disabled color-palette">
                            <tr>
                                @if (can('h'))
                                    <th><input type="checkbox" id="checkall" /></th>
                                @endif
                                <th>No</th>
                                @if (can('u'))
                                    <th>Aksi</th>
                                @endif
                                <th>NIK</th>
                                <th>Nomor KK</th>
                                <th width="25%">Nama</th>
                                <th>Jenis Kelamin</th>
                                <th width="35%">Alamat</th>
                                <th>Hubungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($main)
                                @foreach ($main as $key => $data)
                                    <tr>
                                        @if (can('u'))
                                            <td class="padat"><input type="checkbox" name="id_cb[]" value="{{ $data['id'] }}" /></td>
                                        @endif
                                        <td class="padat">{{ $key + 1 }}</td>
                                        @if (can('u'))
                                            <td class="aksi">
                                                @if (can('u'))
                                                    <a href="{{ ci_route("penduduk.form.{$data['id']}") }}" class="btn bg-orange btn-sm" title="Ubah Biodata Penduduk"><i class="fa fa-edit"></i></a>
                                                    <a
                                                        href="{{ ci_route("rtm.edit_anggota.{$kk}", $data['id']) }}"
                                                        data-remote="false"
                                                        data-toggle="modal"
                                                        data-target="#modalBox"
                                                        data-title="Ubah Hubungan Rumah Tangga"
                                                        title="Ubah Hubungan Rumah Tangga"
                                                        class="btn bg-navy btn-sm"
                                                    ><i class="fa fa-link"></i></a>
                                                @endif
                                                @if (can('h'))
                                                    <a href="#" data-href="{{ ci_route("rtm.delete_anggota.{$kk}", $data['id']) }}" class="btn bg-maroon btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                                                @endif
                                            </td>
                                        @endif
                                        <td>{{ $data['nik'] }}</td>
                                        <td>{{ $data['keluarga']['no_kk'] }}</td>
                                        <td nowrap>{{ strtoupper($data['nama']) }}</td>
                                        <td>{{ strtoupper(App\Enums\JenisKelaminEnum::valueOf($data['sex'])) }}</td>
                                        <td>{{ $data['alamat_wilayah'] }} </td>
                                        <td nowrap>{{ strtoupper(App\Enums\HubunganRTMEnum::valueOf($data['rtm_level'])) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="9">Data Tidak Tersedia</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
    @include('admin.layouts.components.konfirmasi_hapus')
@endsection
