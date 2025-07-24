@include('admin.layouts.components.asset_datatables')

<div class="box box-info">
    <div class="box-header">
        <x-tambah-button :url="$controller . '/form'" />
        <x-hapus-button confirmDelete="true" selectData="true" :url="'bumindes_kader/delete_all'" />
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
        {!! form_open(null, 'id="mainform" name="mainform"') !!}
        <div class="table-responsive">
            <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                <thead class="bg-gray color-palette">
                    <tr>
                        <th><input type="checkbox" id="checkall" /></th>
                        <th>No</th>
                        <th>Aksi</th>
                        <th>Nama</th>
                        <th>Umur</th>
                        <th>Jenis Kelamin</th>
                        <th>Pendidikan/Kursus</th>
                        <th>Bidang</th>
                        <th>Alamat</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
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
                    url: "{{ ci_route('bumindes_kader.datatables') }}"
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
                        data: 'penduduk.nama',
                        name: 'penduduk.nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'umur',
                        name: 'umur',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: function(data) {
                            return data.penduduk.sex == 1 ? 'L' : 'P'
                        },
                        name: 'penduduk.sex',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'pendidikan',
                        name: 'penduduk.pendidikan_kk_id',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'bidang',
                        name: 'bidang',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'penduduk.alamat_wilayah',
                        name: 'penduduk.wilayah.dusun',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [3, 'asc']
                ]
            });

            $('#tahun').change(function() {
                TableData.draw()
            })

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }
        });
    </script>
@endpush
