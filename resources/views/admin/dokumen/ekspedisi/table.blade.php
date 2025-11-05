<div class="box box-info">
    <div class="box-header with-border">
        @php
            $listCetakUnduh = [
                [
                    'url' => "ekspedisi/dialog_cetak/cetak",
                    'judul' => 'Cetak',
                    'icon' => 'fa fa-print',
                    'modal' => true,
                ],
                [
                    'url' => "ekspedisi/dialog_cetak/unduh",
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
        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
            <form id="mainform" name="mainform" method="post">
                <input name="kategori" id="kategori" type="hidden" value="{{ $kat }}">
                <div class="row">
                    <div class="col-sm-2">
                        <select class="form-control input-sm select2 " name="tahun" id="tahun">
                            <option value="">Pilih Tahun</option>
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
                                        <th>Tanggal Surat</th>
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
                        orderable: true,
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
                    [4, 'desc']
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
