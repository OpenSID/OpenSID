@include('admin.pengaturan_surat.asset_tinymce')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@extends('admin.layouts.index')

@section('title')
    <h1>Profil Penerima Manfaat Program</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('program_bantuan') }}"> Daftar Program Bantuan</a></li>
    <li class="active">Profil Penerima Manfaat Program</li>
@endsection

<style>
    .aksi .btn {
        margin-right: 3px;
    }
</style>

@section('content')
    @include('admin.layouts.components.notifikasi')
    {{-- @dd($profil) --}}
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ site_url('program_bantuan') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke
                        Daftar Program Bantuan</a>
                </div>
                <div class="box-body">
                    <h5><b>Profil Penerima Manfaat Program Bantuan</b></h5>
                    <div class="table-responsive">
                        <table class="table table-bordered  table-striped table-hover tabel-rincian">
                            <tbody>
                                <tr>
                                    <td width="20%">Nama Penerima</td>
                                    <td width="1">:</td>
                                    <td>
                                        {{ strtoupper($profil['nama']) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td>
                                        {{ $profil['ndesc'] }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>

                    <h5><b>Program Bantuan Yang Pernah Diikuti</b></h5>
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable table-hover tabel-daftar" id="tabeldata">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <th class="padat">No</th>
                                    <th width="15%">Waktu/Tanggal</th>
                                    <th width="15%">Nama Program</th>
                                    <th>Keterangan</th>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('peserta_bantuan.datatable_peserta') }}",
                    data: function(req) {
                        req.cat = {{ $cat }},
                            req.id = '{{ $id }}'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tanggal',
                        name: 'sdate',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        class: 'padat',
                        searchable: true,
                        orderable: false,
                    },
                    {
                        data: 'ndesc',
                        name: 'ndesc',
                        // class: 'padat',
                        searchable: true,
                        orderable: false
                    },
                ],
                aaSorting: [],
            });
        });
    </script>
@endpush
