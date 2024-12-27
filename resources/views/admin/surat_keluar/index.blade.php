@include('admin.layouts.components.asset_datatables')

<div class="box box-info">
    <div class="box-header">
        @if (can('u'))
            <a href="{{ ci_route('surat_keluar.form') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
        @endif
        @if (can('h'))
            <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('surat_keluar.delete_all') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                    class='fa fa-trash-o'
                ></i> Hapus</a>
        @endif
        <a
            href="{{ ci_route('surat_keluar/dialog/cetak') }}"
            class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Cetak Agenda Surat Keluar"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Cetak Agenda Surat Keluar"
        ><i class="fa fa-print "></i> Cetak</a>
        <a
            href="{{ ci_route('surat_keluar/dialog/unduh') }}"
            class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            title="Unduh Agenda Surat Keluar"
            data-remote="false"
            data-toggle="modal"
            data-target="#modalBox"
            data-title="Unduh Agenda Surat Keluar"
        ><i class="fa fa-download"></i> Unduh</a>
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
