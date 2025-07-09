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
                    @if (can('u'))
                        <a href="{{ site_url('program_bantuan/create') }}" class="btn btn-social bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Program Bantuan"><i class="fa fa-plus"></i> Tambah</a>
                        <a
                            href="{{ site_url('program_bantuan/impor') }}"
                            class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                            title="Impor Program Bantuan"
                            data-target="#impor"
                            data-remote="false"
                            data-toggle="modal"
                            data-backdrop="false"
                            data-keyboard="false"
                        ><i class="fa fa-upload"></i> Impor</a>
                    @endif
                    <a href="{{ site_url('program_bantuan/panduan') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Panduan"><i class="fa fa-question-circle"></i> Panduan</a>
                    @if (can('h'))
                        <a href="{{ site_url('program_bantuan/bersihkan_data') }}" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Bersihkan Data Peserta Tidak Valid"><i class="fa fa-wrench"></i>Bersihkan Data
                            Peserta Tidak Valid</a>
                    @endif
                    @if ($tampil != 0)
                        <a href="{{ site_url('program_bantuan') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar
                            Program Bantuan</a>
                    @endif
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <form id="mainform" name="mainform" method="post">
                                            <select class="form-control input-sm select2" name="sasaran" id="sasaran">
                                                <option value="">Pilih Sasaran</option>
                                                @foreach ($list_sasaran as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                        <hr>
                                    </div>
                                    <div class="col-sm-12">
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
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('program_bantuan.datatables') }}",
                    data: function(req) {
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

            $('#sasaran').change(function() {
                TableData.draw();
            })
        });
    </script>
@endpush
