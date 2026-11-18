<div class="box box-info">
    <div class="box-header">
        @php
            $listCetakUnduh = [
                [
                    'url' => 'lembaran_desa/dialog/cetak',
                    'judul' => 'Cetak',
                    'icon' => 'fa fa-print',
                    'modal' => true,
                ],
                [
                    'url' => 'lembaran_desa/dialog/unduh',
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
            <div class="row mepet">
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" name="filter" id="filter">
                        <option value="">Pilih Status</option>
                        <option value="1">Aktif</option>
                        <option value="2">Tidak Aktif</option>
                    </select>
                </div>
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
                <div class="col-sm-3">
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
                                    <th>No</th>
                                    <th>Aksi</th>
                                    <th>Judul</th>
                                    <th>Jenis Peraturan</th>
                                    <th>No./Tgl Ditetapkan</th>
                                    <th>Uraian Singkat</th>
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
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('lembaran_desa.datatables') }}",
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'additional.jenis_peraturan',
                        name: 'attr',
                        searchable: true,
                        orderable: false,
                    },
                    {
                        data: 'additional.tgl_ditetapkan',
                        name: 'attr',
                        searchable: true,
                        orderable: false,
                    },
                    {
                        data: 'additional.uraian_singkat',
                        name: 'attr',
                        searchable: true,
                        orderable: false,
                    },
                    {
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
                order: [],
            });

            $('#filter').change(function() {
                TableData.column(6).search($(this).val()).draw()
            })

            $('#jenis_peraturan').change(function() {
                TableData.column(4).search($(this).val()).draw()
            })

            $('#tahun').change(function() {
                TableData.column(7).search($(this).val()).draw()
            })

            @if ($status)
                $('#filter').val({{ $status }})
                $('#filter').trigger('change')
            @endif

            if (ubah == 0) {
                TableData.column(1).visible(false);
            }
        });
    </script>
@endpush
