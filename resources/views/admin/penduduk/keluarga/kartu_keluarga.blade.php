@extends('admin.layouts.index')

@section('title')
    <h1>
        Salinan Kartu Keluarga
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('keluarga') }}"> Daftar Keluarga</a></li>
    <li><a href="{{ ci_route('keluarga.anggota', $id_kk) }}"> Daftar Anggota Keluarga</a></li>
    <li class="active">Kartu Keluarga</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <form id="mainform" name="mainform" method="post">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <a href="{{ ci_route('keluarga.cetak_kk', $id_kk) }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-print "></i> Cetak</a>
                        <a href="{{ ci_route('keluarga.doc_kk', $id_kk) }}" class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-download"></i> Unduh</a>
                        <a href="{{ ci_route('keluarga.anggota', $id_kk) }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Rincian Anggota Keluarga">
                            <i class="fa fa-arrow-circle-left"></i>Kembali Ke Daftar Anggota Keluarga
                        </a>
                        <a href="{{ ci_route('keluarga') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Anggota Keluarga">
                            <i class="fa fa-arrow-circle-left"></i>Kembali Ke Daftar Keluarga
                        </a>
                    </div>
                    <div class="box-header">
                        <h3 class="text-center"><strong>SALINAN KARTU KELUARGA</strong></h3>
                        <h5 class="text-center"><strong>No. {{ get_nokk($main['no_kk']) }} </strong></h5>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">ALAMAT</label>
                                    <div class="col-sm-8">
                                        <p class="text-muted">: {{ strtoupper($kepala_kk['alamat_wilayah_kartu_keluarga']) }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">RT/RW</label>
                                    <div class="col-sm-9">
                                        <p class="text-muted">: {{ $kepala_kk['keluarga']['wilayah']['rt'] }} / {{ $kepala_kk['keluarga']['wilayah']['rw'] }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">DESA / KELURAHAN</label>
                                    <div class="col-sm-9">
                                        <p class="text-muted">: {{ strtoupper($desa['nama_desa']) }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">KECAMATAN</label>
                                    <div class="col-sm-9">
                                        <p class="text-muted">: {{ strtoupper($desa['nama_kecamatan']) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">KABUPATEN</label>
                                    <div class="col-sm-7">
                                        <p class="text-muted">: {{ strtoupper($desa['nama_kabupaten']) }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">KODE POS</label>
                                    <div class="col-sm-7">
                                        <p class="text-muted">: {{ $desa['kode_pos'] }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">PROVINSI</label>
                                    <div class="col-sm-7">
                                        <p class="text-muted">: {{ strtoupper($desa['nama_propinsi']) }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">JUMLAH ANGGOTA</label>
                                    <div class="col-sm-7">
                                        <p class="text-muted">: {{ count($main['anggota']) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover ">
                                        <thead class="bg-gray disabled color-palette">
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Nama Lengkap</th>
                                                <th class="text-center">NIK</th>
                                                <th class="text-center">Jenis Kelamin</th>
                                                <th class="text-center">Tempat Lahir</th>
                                                <th class="text-center">Tanggal Lahir</th>
                                                <th class="text-center">Agama</th>
                                                <th class="text-center">Pendidikan</th>
                                                <th class="text-center">Jenis Pekerjaan</th>
                                                <th class="text-center">Golongan Darah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($main['anggota'] as $key => $data)
                                                <tr>
                                                    <td class="text-center">{{ $key + 1 }}</td>
                                                    <td>{{ strtoupper($data['nama']) }}</td>
                                                    <td>{{ get_nik($data['nik']) }}</td>
                                                    <td>{{ $data['jenis_kelamin']['nama'] ?? '' }}</td>
                                                    <td>{{ $data['tempatlahir'] }}</td>
                                                    <td>{{ tgl_indo_out($data['tanggallahir']) }}</td>
                                                    <td>{{ $data['agama']['nama'] ?? '' }}</td>
                                                    <td>{{ $data['pendidikan_k_k']['nama'] ?? '' }}</td>
                                                    <td>{{ $data['pekerjaan']['nama'] ?? '' }}</td>
                                                    <td>{{ $data['golongan_darah']['nama'] ?? '' }}</td>
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
                                    <table class="table table-bordered table-hover ">
                                        <thead class="bg-gray disabled color-palette">
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th class="text-center">Status Perkawinan</th>
                                                <th class="text-center">Tanggal Perkawinan</th>
                                                <th class="text-center">Status Hubungan Dalam Keluarga</th>
                                                <th class="text-center">Kewarganegaraan</th>
                                                <th class="text-center">No. Paspor</th>
                                                <th class="text-center">No. KITAS / KITAP</th>
                                                <th class="text-center">Nama Ayah</th>
                                                <th class="text-center">Nama Ibu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($main['anggota'] as $key => $data)
                                                <tr>
                                                    <td class="text-center">{{ $key + 1 }}</td>
                                                    <td>{{ $data['status_perkawinan'] ?? '' }}</td>
                                                    <td class="text-center">{{ tgl_indo_out($data['tanggalperkawinan']) }}</td>
                                                    <td>{{ App\Enums\SHDKEnum::valueOf($data['kk_level']) }}</td>
                                                    <td>{{ $data['warga_negara']['nama'] ?? '' }}</td>
                                                    <td>{{ $data['dokumen_pasport'] }}</td>
                                                    <td>{{ $data['dokumen_kitas'] }}</td>
                                                    <td>{{ strtoupper($data['nama_ayah']) }}</td>
                                                    <td>{{ strtoupper($data['nama_ibu']) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="table no-border">
                                        <tbody>
                                            <tr>
                                                <td width="25%">&nbsp;</td>
                                                <td width="50%">&nbsp;</td>
                                                <td class="text-center" width="25%">{{ $desa['nama_desa'] }}, {{ tgl_indo(date('Y m d')) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">KEPALA KELUARGA</td>
                                                <td>&nbsp;</td>
                                                <td class="text-center">{{ strtoupper(setting('sebutan_kepala_desa') . ' ' . $desa['nama_desa']) }}</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">{{ strtoupper($kepala_kk['nama']) }}</td>
                                                <td width="50%">&nbsp;</td>
                                                <td class="text-center">{{ strtoupper($desa['nama_kepala_desa']) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
