@include('admin.layouts.components.asset_datatables')

<div class="box box-info">
    <div class="box-header">
        @if (can('u'))
            <a href="{{ ci_route('bumindes_kader.form') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
        @endif
        @if (can('h'))
            <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('bumindes_kader.delete_all') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                    class='fa fa-trash-o'
                ></i> Hapus</a>
        @endif
        <a
            href="{{ ci_route('bumindes_kader/dialog/cetak') }}"
            class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Cetak Buku Kader Pemberdayaan Masyarakat"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Cetak Buku Kader Pemberdayaan Masyarakat"
        ><i class="fa fa-print "></i> Cetak</a>
        <a
            href="{{ ci_route('bumindes_kader/dialog/unduh') }}"
            class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Unduh Buku Kader Pemberdayaan Masyarakat"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Unduh Buku Kader Pemberdayaan Masyarakat"
        ><i class="fa fa-download"></i> Unduh</a>
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
