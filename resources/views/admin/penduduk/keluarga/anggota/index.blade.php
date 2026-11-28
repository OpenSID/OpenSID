@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.datetime_picker')
@section('title')
    <h1>
        Data Anggota {{ $module_name }}
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('keluarga') }}">Data Anggota {{ $module_name }}</a></li>
    <li class="active">Data Anggota {{ $module_name }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                @php
                    $listTambahAnggota = [
                        [
                            'url' => "keluarga/form_peristiwa/1/{$kk}",
                            'judul' => 'Anggota Keluarga Lahir',
                            'icon' => 'fa fa-plus',
                            'can' => true,
                            'modal' => false,
                            'target' => false
                        ],
                        [
                            'url' => "keluarga/form_peristiwa/5/{$kk}",
                            'judul' => 'Anggota Keluarga Masuk',
                            'icon' => 'fa fa-plus',
                            'can' => true,
                            'modal' => false,
                            'target' => false
                        ],
                        [
                            'url' => "keluarga/ajax_add_anggota/{$kk}",
                            'judul' => 'Dari Penduduk Sudah Ada',
                            'icon' => 'fa fa-plus',
                            'can' => true,
                            'modal' => true,
                            'target' => '#modalBox',
                            'data' => [
                                'data-remote' => 'false',
                                'data-toggle' => 'modal',
                                'data-target' => '#modalBox',
                                'data-title' => 'Tambah Anggota Keluarga'
                            ]
                        ]
                    ];
                @endphp
                <x-split-button :list="$listTambahAnggota" type="btn-success" icon="fa fa-plus" judul="Tambah Anggota" />
            @endif
            <x-btn-button url="keluarga/kartu_keluarga/{{ $kk }}" type="bg-purple" icon="fa fa-book" judul="Kartu Keluarga" />
            <x-kembali-button url="keluarga" judul="Kembali Ke Daftar Keluarga" />

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
                                        {!! anchor("peserta_bantuan/detail_clear/{$item['program_id']}", '<span class="label label-success">' . $item['bantuan']['nama'] . '</span>&nbsp;', 'target="_blank"') !!}
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
                                                @if (can('b', 'penduduk'))
                                                    <x-btn-button url="{{ ci_route('penduduk.detail', $data['id']) }}" type="btn-primary" icon="fa fa-user" judul="Lihat Detail Biodata Penduduk" buttonOnly="true" />
                                                @endif
                                                @if (can('u', 'penduduk'))
                                                    <x-btn-button url="{{ ci_route('penduduk.form', $data['id']) }}" type="bg-orange" icon="fa fa-edit" judul="Ubah Biodata Penduduk" buttonOnly="true" />
                                                @endif
                                                @if (can('b', 'penduduk'))
                                                    <x-btn-button url="{{ ci_route('penduduk.dokumen', $data['id']) }}" type="btn-success" icon="fa fa-upload" judul="Manajemen Dokumen" buttonOnly="true" />
                                                @endif
                                                @if (can('u', 'penduduk') && data_lengkap())
                                                    <x-status-dasar-button url="{{ ci_route('penduduk.edit_status_dasar', [$data['id'], 'keluarga.anggota', $kk]) }}" />
                                                @endif
                                                @if ($data['bisaPecahKK'])
                                                    <x-confirm-button 
                                                        url="{{ ci_route('keluarga.delete_anggota.' . $kk, $data['id']) }}" 
                                                        type="bg-purple" 
                                                        icon="fa fa-cut" 
                                                        judul="Pecah KK (Mengeluarkan Penduduk dari Data Keluarga)" 
                                                        target="confirm-status" 
                                                        confirmMessage="Apakah Anda yakin ingin mengeluarkan Penduduk tersebut dari Data Keluarga ini?" 
                                                    />
                                                @else
                                                    <x-confirm-button 
                                                        url="{{ ci_route('keluarga.delete_anggota.' . $kk, $data['id']) }}" 
                                                        type="bg-purple" 
                                                        icon="fa fa-cut" 
                                                        judul="Pecah KK" 
                                                        target="confirm-status" 
                                                        confirmMessage="<p>Tindakan ini <strong>tidak dapat dibatalkan</strong>.</p>
                                                                        <p>KK yang dipecah oleh Kepala Keluarga <strong>tidak dapat digunakan kembali serta semua anggota keluarga akan ikut dipecah</strong>.</p>
                                                                        <p>Apakah Anda yakin ingin melanjutkan proses ini?</p>" 
                                                    />
                                                @endif
                                                @if ($data['bisaGabungKK'])
                                                    <x-btn-button :url="ci_route('keluarga.ajax_gabung_kk', [$kk, $data['id']])" type="bg-yellow" icon="fa fa-plus-square" judul="Buat KK Baru" modal="true" buttonOnly="true" modalTarget="modalBox"  />
                                                @endif
                                                @if ($kepala_kk['status_dasar'] == 1 && $data['kk_level'] != 1)
                                                    <x-edit-hubungan-button url="{{ ci_route('keluarga.edit_anggota.' . $kk, $data['id']) }}" />
                                                @endif
                                                @if ($data['kk_level'] != 1)
                                                    <x-confirm-button 
                                                        url="{{ ci_route('keluarga.keluarkan_anggota.' . $kk, $data['id']) }}" 
                                                        type="bg-maroon" 
                                                        icon="fa fa-times" 
                                                        judul="Bukan anggota keluarga ini" 
                                                        target="confirm-status" 
                                                        confirmMessage="Apakah yakin akan dikeluarkan dari keluarga ini?" 
                                                    />
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
