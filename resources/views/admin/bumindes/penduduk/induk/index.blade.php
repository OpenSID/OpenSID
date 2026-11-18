@include('admin.layouts.components.asset_datatables')

<div class="box box-info">
    <div class="box-header with-border">
        @php
            $listCetakUnduh = [
                [
                    'url' => "{$controller}/dialog/cetak",
                    'judul' => 'Cetak',
                    'icon' => 'fa fa-print',
                    'modal' => true,
                ],
                [
                    'url' => "{$controller}/dialog/unduh",
                    'judul' => 'Unduh',
                    'icon' => 'fa fa-download',
                    'modal' => true,
                ]
            ];
        @endphp

        <x-split-button
            judul="Cetak/Unduh"
            :list="$listCetakUnduh"
            :icon="'fa fa-arrow-circle-down'"
            :type="'bg-purple'"
            :target="true"
        />
    </div>
    <div class="box-body">
        <div class="row mepet">
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
        <hr class="batas">
        <div class="table-responsive">
            <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                <thead class="bg-gray color-palette">
                    <tr>
                        <th rowspan="2">Nomor Urut</th>
                        <th rowspan="2" style="width: 5px;">Nama Lengkap / Panggilan</th>
                        <th rowspan="2">Jenis Kelamin</th>
                        <th rowspan="2">Status Perkawinan</th>
                        <th colspan="2">Tempat & Tanggal Lahir</th>
                        <th rowspan="2">Agama</th>
                        <th rowspan="2">Pendidikan Terakhir</th>
                        <th rowspan="2">Pekerjaan</th>
                        <th rowspan="2">Dapat Membaca Huruf</th>
                        <th rowspan="2">Kewarganegaraan</th>
                        <th rowspan="2">Alamat Lengkap</th>
                        <th rowspan="2">Kedudukan Dlm Keluarga</th>
                        <th rowspan="2">NIK</th>
                        <th rowspan="2">No. KK</th>
                        <th rowspan="2">Ket</th>
                    </tr>
                    <tr>
                        <th>Tempat Lahir</th>
                        <th width="50px">Tgl</th>
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
                    url: "{{ ci_route('bumindes_penduduk_induk.datatables') }}",
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
                        data: 'sex',
                        name: 'sex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'status_kawin',
                        name: 'status_kawin',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tempatlahir',
                        name: 'tempatlahir',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tanggallahir',
                        name: 'tanggallahir',
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
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'pekerjaan',
                        name: 'pekerjaan',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'bahasa',
                        name: 'bahasa',
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
                        data: 'alamat_wilayah',
                        name: 'alamat_wilayah',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'kk_level',
                        name: 'kk_level',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nik',
                        name: 'nik',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kk',
                        name: 'keluarga.no_kk',
                        searchable: true,
                        orderable: true
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
