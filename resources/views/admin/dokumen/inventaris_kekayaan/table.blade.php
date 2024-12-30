<form id="mainform" name="mainform" method="post" class="">
    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ route('bumindes_inventaris_kekayaan.cetak', ['aksi' => 'cetak']) }}" target="_blank" class="btn btn-social bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Inventaris">
                <i class="fa fa-print"></i>Cetak
            </a>
            <a href="{{ route('bumindes_inventaris_kekayaan.cetak', ['aksi' => 'unduh']) }}" target="_blank" class="btn btn-social bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Inventaris">
                <i class="fa fa-download"></i>Unduh
            </a>
        </div>
        <div class="box-body">
            <div class="row mepet">
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="tahun" name="tahun">
                        <option value="{{ date('Y') }}" selected>Pilih Tahun</option>
                        @if ($min_tahun)
                            @for ($i = date('Y'); $i >= $min_tahun; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        @endif
                    </select>
                </div>
            </div>
            <hr class="batas">
            <div class="row">
                <div class="col-sm-12">
                    <div class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="table-responsive">
                            <table id="tabeldata" class="table table-bordered table-hover">
                                <thead class="bg-gray">
                                    <tr>
                                        <th class="text-center" rowspan="3">No</th>
                                        <th class="text-center" rowspan="3">Jenis Barang/Bangunan</th>
                                        <th class="text-center" rowspan="1" colspan="5">Asal Barang/Bangunan</th>
                                        <th class="text-center" rowspan="1" colspan="2">Keadaan Barang / Bangunan AWal
                                            Tahun</th>
                                        <th class="text-center" rowspan="1" colspan="4">Penghapusan Barang Dan Bangunan
                                        </th>
                                        <th class="text-center" rowspan="1" colspan="2">Keadaan Barang / Bangunan Akhir
                                            Tahun</th>
                                        <th class="text-center" rowspan="3">Ket</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" rowspan="2">Dibeli Sendiri</th>
                                        <th class="text-center" rowspan="1" colspan="3">Bantuan</th>
                                        <th class="text-center" rowspan="2">Sumbangan</th>
                                        <th class="text-center" rowspan="2" width="70px">Baik</th>
                                        <th class="text-center" rowspan="2" width="70px">Rusak</th>
                                        <th class="text-center" rowspan="2">Rusak</th>
                                        <th class="text-center" rowspan="2">Dijual</th>
                                        <th class="text-center" rowspan="2">Disumbangkan</th>
                                        <th class="text-center" rowspan="2">Tgl Penghapusan</th>
                                        <th class="text-center" rowspan="2" width="70px">Baik</th>
                                        <th class="text-center" rowspan="2" width="70px">Rusak</th>
                                    </tr>
                                    <tr>
                                        <th>Pemerintah</th>
                                        <th>Provinsi</th>
                                        <th>Kab/Kota</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">1</th>
                                        <th class="text-center">2</th>
                                        <th class="text-center">3</th>
                                        <th class="text-center">4</th>
                                        <th class="text-center">5</th>
                                        <th class="text-center">6</th>
                                        <th class="text-center">7</th>
                                        <th class="text-center">8</th>
                                        <th class="text-center">9</th>
                                        <th class="text-center">10</th>
                                        <th class="text-center">11</th>
                                        <th class="text-center">12</th>
                                        <th class="text-center">13</th>
                                        <th class="text-center">14</th>
                                        <th class="text-center">15</th>
                                        <th class="text-center">16</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('bumindes_inventaris_kekayaan.datatables') }}",
                    data: function(req) {
                        req.tahun = $('#tahun').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama_barang',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'Pembelian Sendiri',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data.length;
                        }
                    },
                    {
                        data: 'Bantuan Pemerintah',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data.length;
                        }
                    }, {
                        data: 'Bantuan Provinsi',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data.length;
                        }
                    },
                    {
                        data: 'Bantuan Kabupaten',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data.length;
                        }
                    },
                    {
                        data: 'Sumbangan',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data.length;
                        }
                    },
                    {
                        data: 'awal_baik',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data.length;
                        }
                    },
                    {
                        data: 'awal_rusak',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data.length;
                        }
                    }, {
                        data: 'hapus_rusak',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data.length;
                        }
                    },
                    {
                        data: 'hapus_jual',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data.length;
                        }
                    },
                    {
                        data: 'hapus_sumbang',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data.length;
                        }
                    },
                    {
                        data: 'tgl_hapus',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data ?? '-';
                        }
                    },
                    {
                        data: 'akhir_baik',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data.length;
                        }
                    },
                    {
                        data: 'akhir_rusak',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data.length;
                        }
                    },
                    {
                        data: 'keterangan',
                        searchable: true,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            return data ?? '-';
                        },
                        width: '200px',
                    }
                ],
                order: [
                    // []
                ],
            });

            $('#tahun').change(function() {
                // TableData.column(12).search($(this).val()).draw()
                TableData.column(12).draw()
            })
        });
    </script>
@endpush
