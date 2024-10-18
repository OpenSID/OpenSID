<div class="box box-info">
    <div class="box-header with-border">
        @if (can('u'))
            <a href="{{ route('bumindes_tanah_kas_desa.form') }}" class="btn btn-social btn-success btn-sm btn-sm
			visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah"><i class="fa fa-plus"></i>Tambah</a>
        @endif

        <a
            href="{{ route('bumindes_tanah_kas_desa.dialog_cetak', ['aksi' => 'cetak']) }}"
            class="btn btn-social bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Cetak Buku Tanah Kas Desa"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Buku Tanah Kas Desa"
        ><i class="fa fa-print"></i> Cetak</a>

        <a
            href="{{ route('bumindes_tanah_kas_desa.dialog_cetak', ['aksi' => 'unduh']) }}"
            class="btn btn-social bg-navy btn-sm
		btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Unduh Buku Tanah Kas Desa"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Buku Tanah Kas Desa"
        ><i class="fa fa-download"></i>Unduh</a>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="tabel-tanahkasdesa" class="table table-bordered dataTable table-hover">
                                <thead class="bg-gray">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th width="120" class="text-center">Aksi</th>
                                        <th class="text-center">Asal Tanah</th>
                                        <th width="100" class="text-center">Nomor Sertifikat Buku Letter C / <br> Persil</th>
                                        <th class="text-center">Kelas</th>
                                        <th class="text-center">Luas Total (M<sup>2</sup>)</th>
                                        <th class="text-center">Tanggal Perolehan</th>
                                        <th class="text-center">Lokasi</th>
                                        <th class="text-center">Mutasi</th>
                                        <th class="text-center">Keterangan</th>
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
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabel-tanahkasdesa').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('bumindes_tanah_kas_desa.datatables') }}",
                    data: function(req) {

                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'ref_asal_tanah_kas.nama',
                        name: 'ref_asal_tanah_kas.nama',
                        searchable: true,
                        orderable: true,
                        render: function(data, type, row) {
                            return data.toUpperCase();
                        }
                    },
                    {
                        data: 'letter_c',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'kode',
                        name: 'ref_persil_kelas.kode',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'luas',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'tanggal_perolehan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'lokasi',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'mutasi',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'keterangan',
                        searchable: true,
                        orderable: false
                    },
                ],
                order: [
                    // [1, 'asc']
                ],
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
        });
    </script>
@endpush
