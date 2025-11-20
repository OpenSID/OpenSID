@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@php
    $tipe = ucfirst($ci->controller);
@endphp

@section('title')
    <h1>
        Data {{ $module_name }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Data {{ $module_name }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.layouts.components.konfirmasi_hapus')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <x-tambah-button :url="$ci->controller . '/form'" />
                    
                    <x-hapus-button 
                        confirmDelete="true"
                        selectData="true"
                        :url="$ci->controller . '/delete_all'"
                    />

                    @php
                        $listCetakUnduh = [
                            [
                                'url' => $ci->controller . '/dialog/cetak',
                                'judul' => 'Cetak',
                                'icon' => 'fa fa-print',
                                'modal' => true,
                            ],
                            [
                                'url' => $ci->controller . '/dialog/unduh',
                                'judul' => 'Unduh',
                                'icon' => 'fa fa-download',
                                'modal' => true,
                            ],
                        ];
                    @endphp
                    <x-split-button
                        judul="Cetak / Unduh"
                        :list="$listCetakUnduh"
                        icon="fa fa-arrow-circle-down"
                        type="bg-purple"
                        target="true"
                    />

                    <x-btn-button
                        :url="$ci->controller . '_master'"
                        judul="Kategori"
                        icon="fa fa-list"
                        type="bg-orange"
                    />

                    <x-btn-button
                        :url="$ci->controller . '/clear'"
                        judul="Bersihkan"
                        icon="fa fa-refresh"
                        type="bg-purple"
                    />
                </div>
                <div class="box-body">
                    <div class="row mepet">
                        <div class="col-sm-2">
                            <select id="status_dasar" class="form-control input-sm select2" name="status_dasar">
                                <option value="0" @selected($default_status_dasar == 0)>Pilih Status</option>
                                <option value="1" @selected($default_status_dasar == 1)>Aktif</option>
                                <option value="2" @selected($default_status_dasar == 2)>Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select id="filter" class="form-control input-sm select2" name="filter">
                                <option value="">Pilih Kategori {{ $tipe }}</option>
                                @foreach ($list_master as $data)
                                    <option @selected($default_kelompok == $data->id) value="{{ $data->id }}">{{ $data->kelompok }}</option>
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
                                    <th class="padat"><input type="checkbox" id="checkall" /></th>
                                    <th class="padat">No</th>
                                    <th class="aksi">Aksi</th>
                                    <th class="padat">Kode {{ $tipe }}</th>
                                    <th>Nama {{ $tipe }}</th>
                                    <th class="padat">Ketua {{ $tipe }}</th>
                                    <th class="padat">Kategori {{ $tipe }}</th>
                                    <th class="padat">Jumlah Anggota {{ $tipe }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ $ci->controller }}",
                    data: function(req) {
                        req.status_dasar = $('#status_dasar').val();
                        req.filter = $('#filter').val();
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
                        data: 'kode',
                        name: 'kode',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'nama',
                        name: 'kelompok.nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'ketua.nama',
                        name: 'ketua.nama',
                        class: 'padat',
                        defaultContent: '',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kelompok_master.kelompok',
                        name: 'kelompokMaster.kelompok',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'jml_anggota',
                        name: 'jml_anggota',
                        class: 'padat',
                        searchable: false,
                        orderable: true
                    },
                ],
                order: [
                    [3, 'asc']
                ],
                pageLength: 25,
                createdRow: function(row, data, dataIndex) {
                    if (data.jenis == 0 || data.jenis == 1) {
                        $(row).addClass('select-row');
                    }
                }
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            $('#status_dasar').on('select2:select', function(e) {
                TableData.draw();
            });
            $('#filter').on('select2:select', function(e) {
                TableData.draw();
            });
        });
    </script>
@endpush
