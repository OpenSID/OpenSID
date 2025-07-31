@include('admin.layouts.components.asset_datatables')

<div class="box box-info">
    <div class="box-header">
        <x-tambah-button :url="'bumindes_tanah_desa/form'" />
        @php
            $listCetakUnduh = [
                [
                    'url' => 'bumindes_tanah_desa/dialog/cetak',
                    'judul' => 'Cetak',
                    'icon' => 'fa fa-print',
                    'modal' => true,
                ],
                [
                    'url' => 'bumindes_tanah_desa/dialog/unduh',
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
                <thead class="bg-gray">
                    <tr>
                        <th class="text-center">No</th>
                        <th width="120" class="text-center">Aksi</th>
                        <th class="text-center">Nama Perorangan &nbsp/ <br> Badan Hukum</th>
                        <th class="text-center">Luas Total (M<sup>2</sup>)</th>
                        <th class="text-center">Mutasi</th>
                        <th class="text-center">Keterangan</th>
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
                    url: "{{ ci_route('bumindes_tanah_desa.datatables') }}"
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
                        data: function(data) {
                            return (data.nama_pemilik_asal) ? data.nama_pemilik_asal : data.penduduk.nama
                        },
                        name: 'nama_pemilik_asal',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'luas',
                        name: 'luas',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'mutasi',
                        name: 'mutasi',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: []
            });

            $('#tahun').change(function() {
                TableData.draw()
            })
        });
    </script>
@endpush
