@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@section('title')
<h1>
    Buku Administrasi Penduduk
</h1>
@endsection

@section('breadcrumb')
<li class="active">Buku KTP dan KK</li>
@endsection

@section('content')
@include('admin.layouts.components.notifikasi')

<div class="row">
    <div class="col-md-3">
        @include('admin.bumindes.penduduk.side', ['selectedNav' => 'ktpkk'])
    </div>
    <div class="col-md-9">
        @include('admin.layouts.components.asset_datatables')

        <div class="box box-info">
            <div class="box-header with-border">
                @php
                $listCetakUnduh = [
                    [
                        'url' => "{$controller}/dialog_cetak/cetak",
                        'judul' => 'Cetak',
                        'icon' => 'fa fa-print',
                        'modal' => true,
                    ],
                    [
                        'url' => "{$controller}/dialog_cetak/unduh",
                        'judul' => 'Unduh',
                        'icon' => 'fa fa-download',
                        'modal' => true,
                    ]
                ];
                @endphp
        
                <x-split-button judul="Cetak/Unduh" :list="$listCetakUnduh" :icon="'fa fa-arrow-circle-down'"
                    :type="'bg-purple'" :target="true" />
            </div>
            <div class="box-body">
                <div class="row mepet">
                    <div class="col-sm-2">
                        <select id="tahun" class="form-control input-sm select2">
                            <option value="">Pilih Tahun</option>
                            @foreach ($list_tahun as $value)
                            <option value="{{ $value['tahun'] }}">{{ $value['tahun'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select id="bulan" class="form-control input-sm select2" style="display: none;">
                            <option value="">Pilih Bulan</option>
                            @foreach (bulan() as $index => $value)
                            <option value="{{ $index }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr class="batas">
                {!! form_open(null, 'id="mainform" name="mainform"') !!}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover tabel-daftar text-center" id="tabeldata">
                        <thead class="bg-gray color-palette">
                            <tr>
                                <th rowspan="2">Nomor Urut</th>
                                <th rowspan="2">No. KK</th>
                                <th rowspan="2" width="20%">Nama Lengkap</th>
                                <th rowspan="2">NIK</th>
                                <th rowspan="2">Jenis Kelamin</th>
                                <th rowspan="2">Tempat / Tanggal Lahir</th>
                                <th rowspan="2">Gol. Darah</th>
                                <th rowspan="2">Agama</th>
                                <th rowspan="2">Pendidikan</th>
                                <th rowspan="2">Pekerjaan</th>
                                <th rowspan="2">Alamat</th>
                                <th rowspan="2">Status Perkawinan</th>
                                <th rowspan="2">Tempat dan Tanggal Dikeluarkan</th>
                                <th rowspan="2">Status Hub. Keluarga</th>
                                <th rowspan="2">Kewarganegaraan</th>
                                <th colspan="2">Orang Tua</th>
                                <th rowspan="2">Tgl Mulai Tinggal di {{ ucwords(setting('sebutan_desa')) }}</th>
                                <th rowspan="2">Ket</th>
                            </tr>
                            <tr>
                                <th>Ayah</th>
                                <th>Ibu</th>
                            </tr>
                        </thead>
                        <tbody>
        
                        </tbody>
                    </table>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        var urlDatatables = "{{ ci_route('bumindes_penduduk_ktpkk.datatables') }}";

        var TableData = $('#tabeldata').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: urlDatatables,
                data: function(req) {
                    req.tahun = $('#tahun').val();
                    req.bulan = $('#bulan').val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    class: 'padat',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'kk',
                    name: 'keluarga.no_kk',
                    searchable: true,
                    orderable: false
                },
                {
                    data: 'nama',
                    name: 'nama',
                    className: 'text-left',
                    searchable: true,
                    orderable: true,
                    render: function(data, type, row) {
                        return data.toUpperCase();
                    }
                },
                {
                    data: 'nik',
                    name: 'nik',
                    searchable: true,
                    orderable: false
                },
                {
                    data: 'jenis_kelamin_inisial',
                    name: 'jenis_kelamin_inisial',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'tanggallahir',
                    name: 'tanggallahir',
                    className: 'text-left',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'golongan_darah',
                    name: 'golongan_darah',
                    defaultContent: '-',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'agama',
                    name: 'agama',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'pendidikan',
                    name: 'pendidikan',
                    className: 'text-left',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'pekerjaan',
                    name: 'pekerjaan',
                    className: 'text-left',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'alamat_wilayah',
                    name: 'alamat_wilayah',
                    className: 'text-left',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'status_perkawinan',
                    name: 'status_perkawinan',
                    className: 'text-left',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'tgl_keluar',
                    name: 'tanggal_cetak_ktp',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'kk_level',
                    name: 'kk_level',
                    className: 'text-left',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'warganegara',
                    name: 'warganegara',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'nama_ayah',
                    name: 'nama_ayah',
                    searchable: true,
                    orderable: false,
                    render: function(data, type, row) {
                        return data.toUpperCase();
                    }
                },
                {
                    data: 'nama_ibu',
                    name: 'nama_ibu',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        return data.toUpperCase();
                    }
                },
                {
                    data: 'tgl_datang',
                    name: 'log_latest.tgl_lapor',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'log_latest.catatan',
                    name: 'log_latest.catatan',
                    searchable: false,
                    orderable: false
                }
            ],
            order: [
                // [17, 'desc']
            ]
        });

        // Sembunyikan bulan saat load awal jika tahun kosong
        if (!$('#tahun').val()) {
            $('#bulan').parent().hide();
        }

        $('#tahun').change(function() {
            if (!$(this).val()) {
                $('#bulan').val('').parent().hide();
            } else {
                $('#bulan').parent().show();
            }

            TableData.draw()
        })

        $('#bulan').change(function() {
            TableData.draw()
        })
    });
</script>
@endpush