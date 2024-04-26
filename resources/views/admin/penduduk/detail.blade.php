@extends('admin.layouts.index')
@section('title')
    <h1>
        Data Penduduk
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('penduduk') }}"> Daftar Penduduk</a></li>
    <li class="active">Biodata Penduduk</li>
@endsection

@push('css')
    <style>
        .table {
            font-size: 12px;
        }

        .detail {
            margin-top: 5px;
            margin-bottom: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="box box-info">
        <div class="box-header">
            <a href="{{ ci_route('penduduk.dokumen', $penduduk->id) }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Manajemen Dokumen Penduduk"><i class="fa fa-book"></i> Manajemen Dokumen</a>
            @if (can('u'))
                @if ($penduduk->status_dasar == App\Enums\StatusDasarEnum::HIDUP)
                    <a href="{{ ci_route('penduduk.form', $penduduk->id) }}" class="btn btn-social btn-warning btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Ubah Biodata"><i class="fa fa-edit"></i> Ubah Biodata</a>
                @endif
            @endif
            <a href="{{ ci_route('penduduk.cetak_biodata', $penduduk->id) }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Biodata" target="_blank"><i class="fa fa-print"></i>Cetak Biodata</a>
            @if ($penduduk->keluarga->no_kk && $penduduk->status_dasar == App\Enums\StatusDasarEnum::HIDUP && !empty($penduduk->id_kk))
                <a href="{{ ci_route("keluarga.anggota.{$penduduk->id_kk}") }}" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Anggota Keluarga"><i class="fa fa-users"></i> Anggota Keluarga</a>
            @endif
            @if (can('u'))
                <div class="btn-group btn-group-vertical">
                    <a class="btn btn-social btn-success btn-sm" data-toggle="dropdown"><i class='fa fa-plus'></i> Tambah Penduduk</a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ ci_route('penduduk.form_peristiwa', 1) }}" class="btn btn-social btn-block btn-sm" title="Tambah Data Penduduk Lahir"><i class="fa fa-plus"></i> Penduduk Lahir</a>
                        </li>
                        <li>
                            <a href="{{ ci_route('penduduk.form_peristiwa', 5) }}" class="btn btn-social btn-block btn-sm" title="Tambah Data Penduduk Masuk"><i class="fa fa-plus"></i> Penduduk Masuk</a>
                        </li>
                    </ul>
                </div>
            @endif
            <a href="{{ ci_route('penduduk.clear') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Penduduk">
                <i class="fa fa-arrow-circle-left"></i>Kembali Ke Daftar Penduduk
            </a>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="box-header with-border">
                        <h3 class="box-title">Biodata Penduduk (NIK : {{ $penduduk->nik }})</h3>
                        <br>
                        @if (!empty($penduduk->pembuat))
                            <p class="kecil">
                                Terdaftar pada:
                                <i class="fa fa-clock-o"></i>{{ tgl_indo2($penduduk->created_at) }}
                                <i class="fa fa-user"></i> {{ $penduduk->pembuat->nama }}
                            </p>
                        @else
                            <p class="kecil">
                                Terdaftar sebelum:
                                <i class="fa fa-clock-o"></i>{{ tgl_indo2($penduduk->created_at) }}
                            </p>
                        @endif
                        @if (!empty($penduduk->pengubah))
                            <p class="kecil">
                                Terakhir diubah:
                                <i class="fa fa-clock-o"></i>{{ tgl_indo2($penduduk->updated_at) }}
                                <i class="fa fa-user"></i> {{ $penduduk->pengubah->nama }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <tr>
                                <td colspan="3">
                                    <img class="penduduk" src="{{ AmbilFoto($penduduk->foto, '', $penduduk->sex) }}" alt="Foto Penduduk">
                                </td>
                            </tr>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <tbody>
                                        <tr>
                                            <td>Status Dasar</td>
                                            <td>:</td>
                                            <td><span class="{{ $penduduk->status_dasar != App\Enums\StatusDasarEnum::HIDUP ? 'label label-danger' : '' }}"><strong>{{ strtoupper(App\Enums\StatusDasarEnum::valueOf($penduduk->status_dasar)) }}</strong></span></td>
                                        </tr>
                                        <tr>
                                            <td width="300">Nama</td>
                                            <td width="1">:</td>
                                            <td>{{ strtoupper($penduduk->nama) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Status Kepemilikan Identitas</td>
                                            <td>:</td>
                                            <td>
                                                <table class="table table-bordered table-striped table-hover detail">
                                                    <tr>
                                                        <th>Wajib Identitas</th>
                                                        <th>Identitas-EL</th>
                                                        <th>Status Rekam</th>
                                                        <th>Tag ID Card</th>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ strtoupper($penduduk->wajib_ktp) }}</td>
                                                        <td>{{ strtoupper(array_flip(unserialize(KTP_EL))[$penduduk->ktp_el]) }}</td>
                                                        <td>{{ strtoupper(App\Enums\StatusKTPEnum::valueOf($penduduk->status_rekam)) }}</td>
                                                        <td>{{ $penduduk->tag_id_card }}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nomor Kartu Keluarga</td>
                                            <td>:</td>
                                            <td>
                                                {{ $penduduk->keluarga->no_kk }}
                                                @if ($penduduk->status_dasar != '1' && $penduduk->no_kk != $penduduk->log_no_kk)
                                                    ( waktu peristiwa [{{ $penduduk->status_dasar }}]: [{{ $penduduk->log_no_kk }}] )
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nomor KK Sebelumnya</td>
                                            <td>:</td>
                                            <td>{{ $penduduk->no_kk_sebelumnya }}</td>
                                        </tr>
                                        <tr>
                                            <td>Hubungan Dalam Keluarga</td>
                                            <td>:</td>
                                            <td>{{ App\Enums\SHDKEnum::valueOf($penduduk->kk_level) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Kelamin</td>
                                            <td>:</td>
                                            <td>{{ strtoupper(App\Enums\JenisKelaminEnum::valueOf($penduduk->sex)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Agama</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->agama->nama) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Status Penduduk</td>
                                            <td>:</td>
                                            <td>{{ strtoupper(App\Enums\StatusPendudukEnum::valueOf($penduduk->status)) }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="subtitle_head"><strong>DATA KELAHIRAN</strong></th>
                                        </tr>
                                        <tr>
                                            <td>Akta Kelahiran</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->akta_lahir) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tempat / Tanggal Lahir</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->tempatlahir) }} / {{ strtoupper($penduduk->tanggallahir->format('d-m-Y')) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tempat Dilahirkan</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->dilahirkan) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Kelahiran</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->jenisLahir) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kelahiran Anak Ke</td>
                                            <td>:</td>
                                            <td>{{ $penduduk->kelahiran_anak_ke }}</td>
                                        </tr>
                                        <tr>
                                            <td>Penolong Kelahiran</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->penolongLahir) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Berat Lahir</td>
                                            <td>:</td>
                                            <td>{{ $penduduk->berat_lahir }} Gram</td>
                                        </tr>
                                        <tr>
                                            <td>Panjang Lahir</td>
                                            <td>:</td>
                                            <td>{{ $penduduk->panjang_lahir }} cm</td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="subtitle_head"><strong>PENDIDIKAN DAN PEKERJAAN</strong></th>
                                        </tr>
                                        <tr>
                                            <td>Pendidikan dalam KK</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->pendidikanKK->nama) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Pendidikan sedang ditempuh</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->pendidikan->nama) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Pekerjaan</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->pekerjaan->nama) }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="subtitle_head"><strong>DATA KEWARGANEGARAAN</strong></th>
                                        </tr>
                                        <tr>
                                            <td>Suku/Etnis</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->suku) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Warga Negara</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->warganegara->nama) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nomor Paspor</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->dokumen_pasport) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Berakhir Paspor</td>
                                            <td>:</td>
                                            <td>{{ strtoupper(tgl_indo_out($penduduk->tanggal_akhir_paspor)) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nomor KITAS/KITAP</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->dokumen_kitas) }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="subtitle_head"><strong>ORANG TUA</strong></th>
                                        </tr>
                                        <tr>
                                            <td>NIK Ayah</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->ayah_nik) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nama Ayah</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->nama_ayah) }}</td>
                                        </tr>
                                        <tr>
                                            <td>NIK Ibu</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->ibu_nik) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nama Ibu</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->nama_ibu) }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="subtitle_head"><strong>ALAMAT</strong></th>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->keluarga->alamat) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Dusun</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->wilayah->dusun) }}</td>
                                        </tr>
                                        <tr>
                                            <td>RT/ RW</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->wilayah->rt) }} / {{ $penduduk->wilayah->rw }}</td>
                                        </tr>
                                        <tr>
                                            <td>Alamat Sebelumnya</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->alamat_sebelumnya) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nomor Telepon</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->telepon) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Alamat Email</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->email) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Telegram</td>
                                            <td>:</td>
                                            <td>{{ $penduduk->telegram }}</td>
                                        </tr>
                                        <tr>
                                            <td>Cara Hubung Warga</td>
                                            <td>:</td>
                                            <td>{{ $penduduk->hubung_warga }}</td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="subtitle_head"><strong>STATUS KAWIN</strong></th>
                                        </tr>
                                        <tr>
                                            <td>Status Kawin</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->statusPerkawinan) }}</td>
                                        </tr>
                                        @if ($penduduk->status_kawin != 1)
                                            <tr>
                                                <td>Akta perkawinan</td>
                                                <td>:</td>
                                                <td>{{ strtoupper($penduduk->akta_perkawinan) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal perkawinan</td>
                                                <td>:</td>
                                                <td>{{ strtoupper($penduduk->tanggalperkawinan) }}</td>
                                            </tr>
                                        @endif
                                        @if ($penduduk->status_kawin != 1 && $penduduk->status_kawin != 2)
                                            <tr>
                                                <td>Akta perceraian</td>
                                                <td>:</td>
                                                <td>{{ strtoupper($penduduk->akta_perceraian) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal perceraian</td>
                                                <td>:</td>
                                                <td>{{ strtoupper($penduduk->tanggalperceraian) }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th colspan="3" class="subtitle_head"><strong>DATA KESEHATAN</strong></th>
                                        </tr>
                                        <tr>
                                            <td>Golongan Darah</td>
                                            <td>:</td>
                                            <td>{{ $penduduk->golonganDarah->nama ?? 'TIDAK TAHU' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Cacat</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->cacat->nama) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Sakit Menahun</td>
                                            <td>:</td>
                                            <td>{{ strtoupper($penduduk->sakitMenahun->nama) }}</td>
                                        </tr>
                                        @if ($penduduk->status_kawin == App\Enums\StatusKawinEnum::KAWIN)
                                            <tr>
                                                <td>Akseptor KB</td>
                                                <td>:</td>
                                                <td>{{ strtoupper($penduduk->cara_kb) }}</td>
                                            </tr>
                                        @endif
                                        @if ($penduduk->id_sex == App\Enums\JenisKelaminEnum::PEREMPUAN)
                                            <tr>
                                                <td>Status Kehamilan</td>
                                                <td>:</td>
                                                <td>{{ strtoupper(App\Enums\HamilEnum::valueOf($penduduk->hamil)) }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>Nama/Nomor Asuransi Kesehatan</td>
                                            <td>:</td>
                                            <td>{{ $penduduk->asuransi->nama . ' / ' . strtoupper($penduduk->no_asuransi) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nomor BPJS Ketenagakerjaan</td>
                                            <td>:</td>
                                            <td>{{ $penduduk->bpjs_ketenagakerjaan }}</td>
                                        </tr>

                                        <tr>
                                            <th colspan="3" class="subtitle_head"><strong>DATA LAINNYA</strong></th>
                                        </tr>
                                        <tr>
                                            <td>Bahasa</td>
                                            <td>:</td>
                                            <td>{{ $penduduk->bahasa->nama }}</td>
                                        </tr>
                                        <tr>
                                            <td>Keterangan</td>
                                            <td>:</td>
                                            <td>{{ $penduduk->ket }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <tbody>
                                        <tr>
                                            <th colspan="3" class="subtitle_head"><strong>PROGRAM BANTUAN</strong></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                                                        <thead class="bg-gray disabled color-palette">
                                                            <tr>
                                                                <th class="padat">No</th>
                                                                <th>Waktu / Tanggal</th>
                                                                <th>Nama Program</th>
                                                                <th>Keterangan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($program as $key => $item)
                                                                <tr>
                                                                    <td class="text-center">{{ $key + 1 }}</td>
                                                                    <td>{{ fTampilTgl($item->bantuanPenduduk->sdate, $item->bantuanPenduduk->edate) }}</td>
                                                                    <td><a href="{{ ci_route('peserta_bantuan.data_peserta', $item->id) }}">{{ $item->bantuanPenduduk->nama }}</a></td>
                                                                    <td>{{ $item->bantuanPenduduk->ndesc }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="subtitle_head"><strong>DOKUMEN / KELENGKAPAN PENDUDUK</strong></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                                                        <thead class="bg-gray disabled color-palette">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Aksi</th>
                                                                <th>Nama Dokumen</th>
                                                                <th>Tanggal Upload</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($list_dokumen as $key => $item)
                                                                <tr>
                                                                    <td class="padat">{{ $key + 1 }}</td>
                                                                    <td class="aksi">
                                                                        <a href="{{ ci_route("penduduk.unduh_berkas.{$item->id}") }}" class="btn bg-purple btn-sm" title="Unduh Dokumen"><i class="fa fa-download"></i></a>
                                                                        <a href="{{ ci_route("penduduk..unduh_berkas.{$item->id}.1") }}" class="btn bg-info btn-sm" title="Lihat Dokumen"><i class="fa fa-eye"></i></a>
                                                                    </td>
                                                                    <td>{{ $item->nama }}</td>
                                                                    <td>{{ tgl_indo2($item->tgl_upload) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
