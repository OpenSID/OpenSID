@include('admin.layouts.components.asset_datatables')

<div class="box box-info">
    <div class="box-header with-border">
        <a
            href="{{ ci_route('bumindes_penduduk_sementara/dialog/cetak') }}"
            class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Cetak Buku Rencana Kerja Pembangunan"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Cetak Buku Rencana Kerja Pembangunan"
        ><i class="fa fa-print "></i> Cetak</a>
        <a
            href="{{ ci_route('bumindes_penduduk_sementara/dialog/unduh') }}"
            class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Unduh Buku Rencana Kerja Pembangunan"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Unduh Buku Rencana Kerja Pembangunan"
        ><i class="fa fa-download"></i> Unduh</a>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-2">
                <select id="tahun" class="form-control input-sm select2">
                    <option value="">Pilih Tahun</option>
                    @foreach ($tahun as $value)
                        <option @selected($value == date('Y')) value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <select id="bulan" class="form-control input-sm select2">
                    <option value="">Pilih Bulan</option>
                    @foreach (bulan() as $index => $value)
                        <option @selected($index == date('m')) value="{{ $index }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                <thead class="bg-gray color-palette">
                    <tr>
                        <th rowspan="2">Nomor Urut</th>
                        <th rowspan="2">Nama Lengkap</th>
                        <th colspan="2">Jenis Kelamin</th>
                        <th rowspan="2">Nomor Identitas / Tanda Pengenal</th>
                        <th rowspan="2">Tempat dan Tanggal Lahir / Umur</th>
                        <th rowspan="2">Pekerjaan</th>
                        <th colspan="2">Kewarganegaraan</th>
                        <th rowspan="2">Datang Dari</th>
                        <th rowspan="2">Maksud dan Tujuan Kedatangan</th>
                        <th rowspan="2">Nama dan Alamat yg Didatangi</th>
                        <th rowspan="2">Datang Tanggal</th>
                        <th rowspan="2">Pergi Tanggal</th>
                        <th rowspan="2">Ket</th>
                    </tr>
                    <tr>
                        <th>L</th>
                        <th>P</th>
                        <th>Kebangsaan</th>
                        <th>Keturunan</th>
                    </tr>
                </thead>
            </table>
        </div>
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
                    url: "{{ ci_route('bumindes_penduduk_sementara.datatables') }}",
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.sex == 1 ? 'L' : ''
                        },
                        name: 'sex_l',
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: function(data) {
                            return data.sex == 2 ? 'P' : ''
                        },
                        name: 'sex_p',
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'nik',
                        name: 'nik',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'ttl',
                        name: 'ttl',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'pekerjaan.nama',
                        name: 'pekerjaan.nama',
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
                        data: function(data) {
                            return data.negara_asal ?? ''
                        },
                        name: 'negara_asal',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: function(data) {
                            return data.alamat_sebelumnya ?? ''
                        },
                        name: 'alamat_sebelumnya',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'log_latest.maksud_tujuan_kedatangan',
                        name: 'log_latest.maksud_tujuan_kedatangan',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'alamat_wilayah',
                        name: 'alamat_wilayah',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tanggal_datang',
                        name: 'tanggal_datang',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tanggal_pergi',
                        name: 'tanggal_pergi',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'ket',
                        name: 'ket',
                        searchable: false,
                        orderable: false
                    }
                ],
                order: []
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
