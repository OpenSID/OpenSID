@extends('admin.layouts.index')

@section('title')
    <h1>
        Laporan Hasil Analisis
    </h1>
@endsection

@section('breadcrumb')
    <li>Master Analisis</li>
    <li>{{ $analisis_master['nama'] }}</li>
    <li class="active">Laporan Hasil Klasifikasi</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4 col-lg-3">
            @include('analisis::master.menu')
        </div>
        <div class="col-md-8 col-lg-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ ci_route('analisis_laporan.' . $analisis_master['id'] . '.dialog_kuisioner.' . $id . '.cetak') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal"
                        data-target="#modalBox" data-title="Cetak Laporan Hasil Analisis {{ $asubjek }} {{ $subjek['nama'] }} "
                    ><i class="fa fa-print "></i> Cetak</a>

                    <a href="{{ ci_route('analisis_laporan.' . $analisis_master['id'] . '.dialog_kuisioner.' . $id . '.unduh') }}" class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal"
                        data-target="#modalBox" data-title="Unduh Laporan Hasil Analisis {{ $asubjek }} {{ $subjek['nama'] }} "
                    ><i class="fa fa-download "></i> Unduh</a>

                    <a href="{{ ci_route('analisis_laporan', $analisis_master['id']) }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Laporan Hasil Klasifikasi">
                        <i class="fa fa-arrow-circle-left "></i>Kembali Ke Laporan Hasil Klasifikasi</a>
                </div>
                <div class="box-header with-border">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <tr>
                                <td nowrap width="150">Hasil Pendataan</td>
                                <td width="1">:</td>
                                <td><a href="{{ ci_route('analisis_master.' . $analisis_master['id'] . '.menu') }}">{{ $analisis_master['nama'] }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Nomor Identitas</td>
                                <td>:</td>
                                <td>{{ $subjek['nid'] }}</td>
                            </tr>
                            <tr>
                                <td>Nama Subjek</td>
                                <td>:</td>
                                <td>{{ $subjek['nama'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="box-title">DAFTAR ANGGOTA</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover ">
                                    <thead class="bg-gray color-palette judul-besar">
                                        <tr>
                                            <th>NO</th>
                                            <th>NIK</th>
                                            <th>NAMA</th>
                                            <th>TANGGAL LAHIR</th>
                                            <th>JENIS KELAMIN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list_anggota as $ang)
                                            <tr>
                                                <td class="padat">{{ $loop->iteration }}</td>
                                                <td class="padat">{{ $ang['nik'] }}</td>
                                                <td width="45%">{{ $ang['nama'] }}</td>
                                                <td nowrap>{{ tgl_indo(implode('-', array_reverse(explode('-', $ang['tanggallahir'])))) }}</td>
                                                <td class="padat">{{ strtoupper(App\Enums\JenisKelaminEnum::valueOf($ang['sex'])) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="bg-gray color-palette judul-besar">
                                        <tr>
                                            <th>No</th>
                                            <th width="45%">Pertanyaan / Indikator</th>
                                            <th>Bobot</td>
                                            <th>Jawaban</th>
                                            <th>Nilai</th>
                                            <th>Poin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list_jawab as $data)
                                            <tr>
                                                <td class="padat">{{ $data['no'] }}</td>
                                                <td>{{ $data['pertanyaan'] }}</td>
                                                <td class="padat">{{ $data['bobot'] }}</td>
                                                <td class="padat">{{ $data['jawaban'] }}</td>
                                                <td class="padat">{{ $data['nilai'] }}</td>
                                                <td class="padat">{{ $data['poin'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-info olor-palette">
                                        <tr class="total">
                                            <td colspan='5'><strong>TOTAL</strong></td>
                                            <td class="padat">{{ $total }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
