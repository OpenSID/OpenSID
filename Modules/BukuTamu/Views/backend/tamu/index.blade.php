@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.asset_fancybox')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Data Tamu
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Data Tamu</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            <div class="form-inline">
                @if (can('h'))
                    <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('buku_tamu.deleteAll') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                            class='fa fa-trash-o'
                        ></i>
                        Hapus</a>
                @endif
                <a id="cetak" title="Cetak Data" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                    <i class='fa fa-print'></i> Cetak
                </a>
                @include('admin.layouts.components.tombol_ekspor', [
                    'id' => 'expor',
                    'targetBlank' => false,
                ])
            </div>
        </div>

        
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="row mepet">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="sr-only" for="status">Status</label>
                        <select name="status" id="status" class="form-control input-sm select2">
                            <option value="">Semua</option>
                            <option value="{{ Modules\BukuTamu\Models\TamuModel::BARU }}">Belum Dibaca</option>
                            <option value="{{ Modules\BukuTamu\Models\TamuModel::SELESAI }}">Sudah Dibaca</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <label class="sr-only" for="date-range">Rentang Tanggal</label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon" style="border-radius: 5px 0 0 5px">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input
                                type="text"
                                name="tanggal"
                                id="date-range"
                                class="form-control input-sm"
                                title="Rentang Tanggal"
                                placeholder="Masukan Rentang Tanggal"
                                style="border-radius: 0 5px 5px 0"
                            >
                        </div>
                    </div>
                </div>
            </div>
            <hr class="batas">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkall" /></th>
                            <th class="padat">NO</th>
                            <th class="padat">AKSI</th>
                            <th>HARI / TANGGAL</th>
                            <th>NAMA</th>
                            <th>TELEPON</th>
                            <th>INSTANSI</th>
                            <th>JENIS KELAMIN</th>
                            <th>ALAMAT</th>
                            <th>BERTEMU</th>
                            <th>KEPERLUAN</th>
                            <th>STATUS</th>
                            <th>FOTO</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </form>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
    @include('admin.layouts.components.datetime_picker')
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#cetak, #expor').on('click', function() {
                let url = $(this).attr('id') == 'cetak' ?
                    "{{ ci_route('buku_tamu.cetak') }}/" :
                    "{{ ci_route('buku_tamu.ekspor') }}/";

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        tanggal: $('#date-range').val(),
                    },
                    success: function(data) {
                        window.open(this.url, '_blank');
                    },
                })
            });

            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('buku_tamu') }}",
                    data: function(req) {
                        req.tanggal = $('#date-range').val();
                        // selalu kirimkan nilai status dari select ('' berarti semua)
                        req.status = $('#status').val();
                    },
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
                        data: 'created_at',
                        name: 'created_at',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'telepon',
                        name: 'telepon',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'instansi',
                        name: 'instansi',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'jenis_kelamin',
                        name: 'jenis_kelamin',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'alamat',
                        name: 'alamat',
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
                        data: 'keperluan',
                        name: 'keperluan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'tampil_foto',
                        name: 'tampil_foto',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [3, 'desc']
                ]
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }

            // set nilai awal select status: gunakan query string jika ada, jika tidak default ke SELESAI
            const initialStatus = @json(request()->has('status') ? request()->get('status') : Modules\BukuTamu\Models\TamuModel::SELESAI);
            $('#status').val(initialStatus).trigger('change.select2');

            // reload tabel saat status berubah
            $('#status').on('change', function() {
                TableData.ajax.reload();
            });

            $('input[name="tanggal"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
                TableData.ajax.reload();
            });

            $('input[name="tanggal"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                TableData.ajax.reload();
            });
        });
    </script>
@endpush
