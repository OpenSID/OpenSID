@include('admin.pengaturan_surat.asset_tinymce')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@extends('admin.layouts.index')

@section('title')
    <h1>Daftar Program Bantuan</h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar Program Bantuan</li>
@endsection

<style>
    .aksi .btn {
        margin-right: 3px;
    }
</style>

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <x-tambah-button :url="'program_bantuan/create'" />
                    <x-impor-button modal="true" judul="'Impor Program Bantuan'" :url="'program_bantuan/impor'" />
                    
                    @if (can('h'))
                        <a href="{{ site_url('program_bantuan/bersihkan_data') }}" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Bersihkan Data Peserta Tidak Valid"><i class="fa fa-wrench"></i>Bersihkan Data
                            Peserta Tidak Valid</a>
                    @endif
                    @if ($tampil != 0)
                        <x-kembali-button judul="Kembali ke Daftar Program Bantuan" :url="'/program_bantuan'" />
                    @endif
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <div class="row mepet">
                                    @include('admin.layouts.components.select_status')
                                    <div class="col-sm-2">
                                        <select class="form-control input-sm select2" name="sasaran" id="sasaran">
                                            <option value="">Pilih Sasaran</option>
                                            @foreach ($list_sasaran as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-12">
                                        <hr class="batas">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="tabeldata">
                                                <thead class="bg-gray disabled color-palette">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Aksi</th>
                                                        <th>Nama Program</th>
                                                        <th>Asal Dana</th>
                                                        <th>Jumlah Peserta</th>
                                                        <th>Masa Berlaku</th>
                                                        <th>Sasaran</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
    @include('admin.layouts.components.konfirmasi')
    @include('admin.layouts.components.program_bantuan.impor')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#status').val('1').trigger('change');

            let filterColumn = {!! json_encode($filterColumn) !!}
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('program_bantuan.datatables') }}",
                    data: function(req) {
                        req.status = $('#status').val();
                        req.sasaran = $('#sasaran').val();
                    }
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'asaldana',
                        name: 'asaldana',
                        class: 'padat',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'peserta_count',
                        name: 'peserta_count',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tampil_tanggal',
                        name: 'tampil_tanggal',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'sasaran',
                        name: 'sasaran',
                        class: 'padat',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'status_masa_aktif',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                ],
                aaSorting: [],
            });

            $('#status').change(function() {
                TableData.draw();
            });

            $('#sasaran').change(function() {
                TableData.draw();
            });

            if (filterColumn) {
                if (filterColumn['status'] > 0) {
                    $('#status').val(filterColumn['status'])
                    $('#status').trigger('change')
                }
                if (filterColumn['sasaran'] > 0) {
                    $('#sasaran').val(filterColumn['sasaran'])
                    $('#sasaran').trigger('change')
                }
            }
        });
    </script>
@endpush
