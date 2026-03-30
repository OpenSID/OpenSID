@include('admin.layouts.components.asset_datatables')

<div class="box box-info">
    <div class="box-header with-border">
        @php
            $listCetakUnduh = [
                [
                    'url' => 'bumindes_hasil_pembangunan/dialog/cetak',
                    'judul' => 'Cetak',
                    'icon' => 'fa fa-print',
                    'modal' => true,
                ],
                [
                    'url' => 'bumindes_hasil_pembangunan/dialog/unduh',
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
                    @foreach ($tahun as $item)
                        <option>{{ $item->tahun_anggaran }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <hr class="batas">
        {!! form_open(null, 'id="mainform" name="mainform"') !!}
        <div class="table-responsive">
            <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                <thead class="bg-gray color-palette">
                    <tr>
                        <th>NOMOR URUT</th>
                        <th>JENIS/NAMA HASIL PEMBANGUNAN</th>
                        <th>VOLUME</th>
                        <th>BIAYA</th>
                        <th>LOKASI</th>
                        <th>KETERANGAN</th>
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
                    url: "{{ ci_route('bumindes_hasil_pembangunan.datatables') }}",
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
                        data: 'judul',
                        name: 'judul',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'volume',
                        name: 'volume',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'anggaran',
                        name: 'anggaran',
                        render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'alamat',
                        name: 'wilayah.dusun',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [1, 'desc']
                ]
            });

            $('#tahun').change(function() {
                TableData.draw()
            })
        });
    </script>
@endpush
