@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.datetime_picker')

<div class="box box-info">
    <div class="box-header">
        <x-tambah-button :url="'surat_keluar/form'" />
        <x-hapus-button confirmDelete="true" selectData="true" :url="'surat_keluar/delete_all'" />
        @php
            $listCetakUnduh = [
                [
                    'url' => 'surat_keluar/dialog/cetak',
                    'judul' => 'Cetak',
                    'icon' => 'fa fa-print',
                    'modal' => true,
                ],
                [
                    'url' => 'surat_keluar/dialog/unduh',
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
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <hr class="batas">
        {!! form_open(null, 'id="mainform" name="mainform"') !!}
        <div class="table-responsive">
            <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                <thead class="bg-gray">
                    <tr>
                        <th><input type="checkbox" id="checkall" /></th>
                        <th>No. Urut</th>
                        <th width="120">Aksi</th>
                        <th>Nomor Surat</th>
                        <th>Tanggal Surat</th>
                        <th nowrap>Ditujukan Kepada</th>
                        <th>Isi Singkat</th>
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
                order: [
                    [4, 'desc']
                ],
                ajax: {
                    url: "{{ ci_route('surat_keluar.datatables') }}",
                    data: function(req) {
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
                        data: 'nomor_urut',
                        class: 'padat'
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nomor_surat',
                        name: 'nomor_surat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tanggal_surat',
                        name: 'tanggal_surat'
                    },
                    {
                        data: 'tujuan',
                        name: 'tujuan'
                    },
                    {
                        data: 'isi_singkat',
                        name: 'isi_singkat',
                        searchable: true,
                        orderable: false
                    },
                ],
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            $('#tahun').change(function() {
                TableData.draw()
            })
        });
    </script>
@endpush
