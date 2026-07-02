@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Daftar Notifikasi
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar Notifikasi</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.buttons.hapus', [
                'url'           => "notifikasi/deleteAll",
                'confirmDelete' => true,
                'selectData'    => true,
            ])
            <x-btn-button
                url=""
                judul="Tandai Semua Dibaca"
                icon="fa fa-check"
                type="btn-success hapus-terpilih"
                modal="true"
                confirm="true"
                confirmTarget="confirm-delete"
                onclick="aksiBorongan('mainform', '{{ ci_route('notifikasi/mark-all-read') }}')"
            />
        </div>
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="row mepet">
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="status" name="status">
                        <option value="">Pilih Status</option>
                        <option value="read">Dibaca</option>
                        <option selected value="unread">Belum Dibaca</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="kategori" name="kategori">
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategori as $slug => $label)
                            <option value="{{ $slug }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr class="batas">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkall" /></th>
                            <th class="padat">NO</th>
                            <th class="padat">AKSI</th>
                            <th class="padat">KATEGORI</th>
                            <th class="padat">STATUS</th>
                            <th>PESAN</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
    @include('admin.layouts.components.konfirmasi', [
        'periksa_data' => true,
        'pertanyaan'   => 'Apakah Anda yakin ingin menandai semua notifikasi sebagai dibaca?<br> Perubahan ini tidak dapat dikembalikan lagi.',
    ])
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: false,
                ajax: {
                    url: "{{ ci_route('notifikasi.datatables') }}",
                    data: function(req) {
                        req.status = $('#status').val();
                        req.kategori = $('#kategori').val();
                    }
                },
                columns: [
                    {
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
                        data: 'kategori',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'status',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'pesan',
                        name: 'pesan',
                        searchable: false,
                        orderable: false
                    },
                ],
            });

            $('#status').on('select2:select', function(e) {
                TableData.draw();
            });

            $('#kategori').on('select2:select', function(e) {
                TableData.draw();
            });
        });
    </script>
@endpush
