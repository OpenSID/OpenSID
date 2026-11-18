<div class="box box-info">
    <div class="box-header with-border">
        <x-tambah-button :url="$controller . '/form/' . $kat" />
        <x-hapus-button confirmDelete="true" selectData="true" :url="'dokumen_sekretariat/delete_all/'.$kat" />
        @php
            $listCetakUnduh = [
                [
                    'url' => "{$controller}/dialog_cetak/{$kat}/cetak",
                    'judul' => 'Cetak',
                    'icon' => 'fa fa-print',
                    'modal' => true,
                ],
                [
                    'url' => "{$controller}/dialog_cetak/{$kat}/unduh",
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
        <form id="mainform" name="mainform" method="post">
            <input name="kategori" id="kategori" type="hidden" value="{{ $kat }}">
            <div class="row mepet">
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" name="filter" id="filter">
                        <option value="">Pilih Status</option>
                        <option value="1" @selected($active == 1)>Berlaku</option>
                        <option value="0" @selected($active == 0)>Dicabut/Tidak Berlaku</option>
                    </select>
                </div>
                @if ($kat == 3)
                    <div class="col-sm-3">
                        <select class="form-control input-sm select2" name="jenis_peraturan" id="jenis_peraturan">
                            <option value="">Pilih Jenis Peraturan</option>
                            @foreach ($jenis_peraturan as $jenis)
                                <option value="{{ $jenis }}">
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
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
            <hr class="batas">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabeldata">
                            <thead class="bg-gray color-palette">
                                <tr>
                                    <th><input type="checkbox" id="checkall" /></th>
                                    <th>No</th>
                                    <th>Aksi</th>
                                    <th>Judul</th>
                                    @if ($kat == 1)
                                        <th>Kategori Info Publik</th>
                                        <th>Tahun</th>
                                    @elseif ($kat == 2)
                                        <th nowrap>No./Tgl Keputusan</th>
                                        <th nowrap>Uraian Singkat</th>
                                    @elseif ($kat == 3)
                                        <th>Jenis Peraturan</th>
                                        <th>No./Tgl Ditetapkan</th>
                                        <th>Uraian Singkat</th>
                                    @endif
                                    <th nowrap>Aktif</th>
                                    <th nowrap>Dimuat Pada</th>
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
@push('scripts')
    <script>
        $(document).ready(function() {
            var kategori = $('#kategori').val();
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('buku-umum.dokumen_sekretariat.datatables') }}",
                    data: function(req) {
                        req.kategori = kategori;
                        req.filter = $('#filter').val();
                        req.tahun = $('#tahun').val();
                    }
                },
                columns: [{
                        data: 'ceklist',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true,
                    },
                    @if ($kat == 1)
                        {
                            data: 'additional.kategori_info_publik',
                            name: 'kategori_info_publik',
                            searchable: true,
                            orderable: false,
                        }, {
                            data: 'additional.tahun',
                            name: 'tahun',
                            searchable: true,
                            orderable: false,
                        },
                    @elseif ($kat == 2) {
                            data: 'additional.tgl_keputusan',
                            name: 'attr->tgl_kep_kades',
                            searchable: true,
                            orderable: true,
                        }, {
                            data: 'additional.uraian_singkat',
                            name: 'attr',
                            searchable: true,
                            orderable: false,
                        },
                    @elseif ($kat == 3) {
                            data: 'additional.jenis_peraturan',
                            name: 'attr',
                            searchable: true,
                            orderable: false,
                        }, {
                            data: 'additional.tgl_ditetapkan',
                            name: 'attr->tgl_ditetapkan',
                            searchable: true,
                            orderable: true,
                        }, {
                            data: 'additional.uraian_singkat',
                            name: 'attr',
                            searchable: true,
                            orderable: false,
                        },
                    @endif {
                        data: 'enabled',
                        name: 'enabled',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'tgl_upload',
                        name: 'tgl_upload',
                        searchable: true,
                        orderable: true
                    }
                ],
                order: [
                    @switch($kat)
                        @case(2)[4, 'asc']
                        @break

                        @case(3)[5, 'asc']
                        @break
                    @endswitch
                ],
            });

            // buat kondisi sesuai kategori untuk data nomor column\
            // default colfilter dan tahun set ke kategori 1 / 2
            var colFilter = 6;
            var colTahun = 4;

            if (kategori == 3 || kategori == 2) {
                if (kategori == 3) {
                    colFilter = 7;
                }
                colTahun = 5;
            }

            $('#filter').change(function() {
                if ($(this).attr("data-reset")) {
                    return;
                }

                TableData.column(colFilter).search($(this).val()).draw()
            })

            $('#tahun').change(function() {
                if ($(this).attr("data-reset")) {
                    return;
                }

                if (kategori == 2 || kategori == 3) {
                    // Untuk SK Kades dan Perdes, filter di server-side
                    TableData.draw()
                } else {
                    // Untuk kategori lain, filter di client-side
                    TableData.column(colTahun).search($(this).val()).draw()
                }
            })

            $('#jenis_peraturan').change(function() {
                if ($(this).attr("data-reset")) {
                    return;
                }

                TableData.column(4).search($(this).val()).draw()
            })

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
            @if ($active)
                $('#filter').trigger('change')
            @endif
        });
    </script>
@endpush
