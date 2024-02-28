@include('admin.layouts.components.asset_datatables')

<div class="box box-info">
    <div class="box-header">
        @if (can('u'))
            <a href="{{ ci_route('bumindes_tanah_desa.form') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
        @endif
        <a
            href="{{ ci_route('bumindes_tanah_desa/dialog/cetak') }}"
            class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Cetak Buku Tanah di {{ setting('sebutan_desa') }}"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Cetak Buku Tanah di {{ setting('sebutan_desa') }}"
        ><i class="fa fa-print "></i> Cetak</a>
        <a
            href="{{ ci_route('bumindes_tanah_desa/dialog/unduh') }}"
            class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Unduh Buku Tanah di {{ setting('sebutan_desa') }}"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Unduh Buku Tanah di {{ setting('sebutan_desa') }}"
        ><i class="fa fa-download"></i> Unduh</a>
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
