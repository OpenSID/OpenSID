@include('admin.layouts.components.asset_datatables')
@push('css')
    <style>
        .text-left {
            text-align: left !important;
        }
    </style>
@endpush
<div class="box box-info">
    <div class="box-header with-border">
        <a
            href="{{ route('bumindes_penduduk_ktpkk.dialog_cetak', ['aksi' => 'cetak']) }}"
            class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Cetak Buku KTP dan KK"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Cetak Buku KTP dan KK"
        ><i class="fa fa-print "></i> Cetak</a>
        <a
            href="{{ route('bumindes_penduduk_ktpkk.dialog_cetak', ['aksi' => 'unduh']) }}"
            class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Unduh Buku KTP dan KK"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Unduh Buku KTP dan KK"
        ><i class="fa fa-download"></i> Unduh</a>
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
                <select id="bulan" class="form-control input-sm select2">
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

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('bumindes_penduduk_ktpkk.datatables') }}",
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
                        data: 'sex',
                        name: 'sex',
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

            $('#tahun').change(function() {
                TableData.draw()
            })

            $('#bulan').change(function() {
                TableData.draw()
            })
        });
    </script>
@endpush
