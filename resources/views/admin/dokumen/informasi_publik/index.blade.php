@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        {{ $kat_nama }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $kat_nama }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    {!! form_open(null, 'id="mainform" name="mainform"') !!}
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <x-tambah-button :url="'dokumen/form'" />
                    <x-hapus-button :url="'dokumen/delete'" :confirmDelete="true" :selectData="true" />
                    @php
                    $listCetakUnduh = [
                        ['url' => "dokumen/dialog_cetak/cetak", 'modal' => true, 'judul' => "Cetak", 'icon' => 'fa fa-print'],
                        ['url' => "dokumen/dialog_cetak/unduh", 'modal' => true, 'judul' => "Unduh", 'icon' => 'fa fa-download']
                    ];
                    @endphp
                    <x-split-button judul="Cetak/Unduh" :list="$listCetakUnduh" :icon="'fa fa-arrow-circle-down'" :type="'bg-purple'" :target="true" />
                    <x-btn-button judul="Ekspor" icon="fa fa-download" type="bg-blue" modal="true" :url="'dokumen/ekspor'" />
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
                                    <th>Kategori Info Publik</th>
                                    <th>Tahun</th>
                                    <th nowrap>Aktif</th>
                                    <th nowrap>Dimuat Pada </th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Tanggal Retensi</th>
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
                    url: "{{ ci_route('dokumen.datatables') }}",
                    data: function(req) {
                        req.status = $('#status').val();
                    }
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'infoPublic',
                        name: 'infoPublic',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tahun',
                        name: 'tahun',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aktif',
                        name: 'aktif',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'dimuat',
                        name: 'tgl_upload',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'retensi',
                        name: 'retensi',
                        searchable: false,
                        orderable: true
                    },
                ],
                aaSorting: [],
                order: [
                    [7, 'desc']
                ],
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }

            $('#status').change(function() {
                TableData.draw()
            })

        });
    </script>
@endpush
