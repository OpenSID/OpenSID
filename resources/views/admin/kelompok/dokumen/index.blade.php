@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>Data Dokumen {{ $module_name }}</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url($tipe) }}">Data {{ $module_name }}</a></li>
    <li class="active">Data Dokumen {{ $module_name }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    {!! form_open(null, 'id="mainform" name="mainform"') !!}
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <x-tambah-button :url="$tipe . '/dokumen-form' . '?id_kelompok=' . $id_kelompok" />
                    <x-hapus-button :url="$tipe . '/dokumen-delete'" :confirmDelete="true" :selectData="true" />
                </div>
                <div class="box-body">
                    <div class="row mepet">
                        <div class="col-sm-2">
                            <select id="status" class="form-control input-sm select2">
                                <option value="">Pilih Status</option>
                                @foreach ($status as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="batas">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped dataTable table-hover" id="tabeldata">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <th><input type="checkbox" id="checkall" /></th>
                                    <th>No</th>
                                    <th>Aksi</th>
                                    <th>Judul</th>
                                    <th>Tahun</th>
                                    <th nowrap>Aktif</th>
                                    <th nowrap>Dimuat Pada</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! form_close() !!}
    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route($tipe . '.dokumen.datatables') }}",
                    data: function(req) {
                        req.status      = $('#status').val();
                        req.id_kelompok = '{{ $id_kelompok }}';
                    }
                },
                columns: [
                    { data: 'ceklist',    class: 'padat', searchable: false, orderable: false },
                    { data: 'DT_RowIndex',class: 'padat', searchable: false, orderable: false },
                    { data: 'aksi',       class: 'aksi',  searchable: false, orderable: false },
                    { data: 'nama',       name: 'nama',   searchable: true,  orderable: true  },
                    { data: 'tahun',      name: 'tahun',  searchable: false, orderable: false },
                    { data: 'aktif',      name: 'aktif',  searchable: false, orderable: true  },
                    { data: 'dimuat',     name: 'tgl_upload', searchable: false, orderable: true },
                    { data: 'keterangan', name: 'keterangan', searchable: true, orderable: false },
                    { data: 'status',     name: 'status', searchable: false, orderable: true  },
                ],
                aaSorting: [],
                order: [[6, 'desc']],
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }
            if (ubah == 0) {
                TableData.column(2).visible(false);
            }

            $('#status').change(function() {
                TableData.draw();
            });
        });
    </script>
@endpush