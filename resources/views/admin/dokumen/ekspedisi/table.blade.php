<div class="box box-info">
    <div class="box-header with-border">
        <a
            href="{{ route('buku-umum.ekspedisi.dialog_cetak', ['aksi' => 'cetak']) }}"
            class="btn btn-social bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Cetak Dokumen"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Cetak Buku Ekspedisi"
        >
            <i class="fa fa-print"></i>Cetak
        </a>
        <a
            href="{{ route('buku-umum.ekspedisi.dialog_cetak', ['aksi' => 'unduh']) }}"
            class="btn btn-social bg-navy btn-sm
			btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Unduh Dokumen"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Unduh
			Buku Ekspedisi"
        ><i class="fa fa-download"></i>Unduh</a>
    </div>
    <div class="box-body">
        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <form id="mainform" name="mainform" method="post">
                <input name="kategori" id="kategori" type="hidden" value="{{ $kat }}">
                <div class="row">
                    <div class="col-sm-2">
                        <select class="form-control input-sm select2 " name="tahun" id="tahun">
                            <option value="">Tahun</option>
                            @foreach ($list_tahun as $thn)
                                <option value="{{ $thn['tahun'] }}" @selected($tahun == $thn['tahun'])>
                                    {{ $thn['tahun'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="tabeldata">
                                <thead class="bg-gray color-palette">
                                    <tr>
                                        <th>No.</th>
                                        <th>Aksi</th>
                                        <th>Tgl Pengiriman</th>
                                        <th>No. Surat</th>
                                        <th>Tgl Surat</th>
                                        <th>Isi Singkat</th>
                                        <th>Ditujukan Kepada</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
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
                    url: "{{ route('buku-umum.ekspedisi.datatables') }}",
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
                        data: 'tanggal_pengiriman',
                        name: 'tanggal_pengiriman',
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'nomor_surat',
                        name: 'nomor_surat',
                        searchable: true,
                        orderable: false,
                    }, {
                        data: 'tanggal_surat',
                        name: 'tanggal_surat',
                        searchable: true,
                        orderable: false,
                    },
                    {
                        data: 'isi_singkat',
                        name: 'isi_singkat',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'tujuan',
                        name: 'tujuan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: true
                    }
                ],
                order: [
                    [4, 'asc']
                ],
            });

            $('#tahun').change(function() {
                TableData.column(4).search($(this).val()).draw()
            })

            if (ubah == 0) {
                TableData.column(1).visible(false);
            }
        });
    </script>
@endpush
