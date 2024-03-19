@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Rekam Surat Perseorangan
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('keluar') }}">Arsip Layanan Surat</a></li>
    <li class="active">Rekam Surat Perseorangan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ ci_route('keluar') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Wilayah">
                        <i class="fa fa-arrow-circle-left "></i>Kembali Ke Arsip Layanan Surat
                    </a>
                </div>
                <div class="box-header with-border">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" style="width:75%">
                            <thead>
                                <tr>
                                    <td style="padding-top : 10px;padding-bottom : 10px;width:25%;">Nama Penduduk </td>
                                    <td>
                                        <div class="form-group">
                                            <div>
                                                <select class="form-control required input-sm select2-nik-ajax" id="nik" name="nik" data-url="{{ ci_route('surat/list_penduduk_bersurat_ajax') }}">
                                                    @if ($penduduk)
                                                        <option value="{{ $penduduk->id }}">NIK/Tag ID Card : {{ $penduduk->nik }} - {{ $penduduk->nama }} Alamat: {{ $penduduk->alamat_wilayah }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </thead>
                            <tbody class="info-penduduk">
                                <tr>
                                    <td style="padding-top : 10px;padding-bottom : 10px;" nowrap="">Tempat/ Tanggal Lahir (Umur)</td>
                                    <td class="ttl"> </td>
                                </tr>
                                <tr>
                                    <td style="padding-top : 10px;padding-bottom : 10px;">Alamat</td>
                                    <td class="alamat"> </td>
                                </tr>
                                <tr>
                                    <td style="padding-top : 10px;padding-bottom : 10px;">Pendidikan</td>
                                    <td class="pendidikan"> </td>
                                </tr>
                                <tr>
                                    <td style="padding-top : 10px;padding-bottom : 10px;">Warganegara / Agama</td>
                                    <td class="warganegara"> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <form id="mainform" name="mainform" method="post">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table id="tabeldata" class="table table-bordered dataTable table-hover">
                                                    <thead class="bg-gray disabled color-palette">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Aksi</th>
                                                            <th>Kode Surat</th>
                                                            <th>No. Urut</th>
                                                            <th>Jenis Surat</th>
                                                            <th>Nama Penduduk</th>
                                                            <th>Keterangan</th>
                                                            <th>Ditandatangani Oleh</th>
                                                            <th>Tanggal</th>
                                                            <th>User</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script src="{{ asset('js/custom-select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                ajax: {
                    url: "{{ ci_route('keluar.perorangan_datatables') }}",
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
                        data: 'kode_surat',
                        name: 'kode_surat',

                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',

                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'id_format_surat',
                        name: 'id_format_surat',

                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'id_pend',
                        name: 'id_pend',

                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',

                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'nama_pamong',
                        name: 'nama_pamong',

                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',

                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'id_user',
                        name: 'id_user',

                        searchable: false,
                        orderable: false
                    },

                ],
                order: [
                    [8, 'desc']
                ],
                pageLength: 25,
                createdRow: function(row, data, dataIndex) {
                    if (data.status == 0) {
                        $(row).addClass('select-row');
                    }
                }
            });

            $('#nik').change(function() {
                TableData.column(5).search($(this).val()).draw()
                $('tbody.info-penduduk').hide()
                if ($(this).val() > 0) {
                    $('tbody.info-penduduk').show()
                    $.get("{{ ci_route('keluar.dataPenduduk') }}/" + $(this).val(), function(data) {
                        if (data) {
                            $('tbody.info-penduduk td.ttl').text(data.ttl)
                            $('tbody.info-penduduk td.alamat').text(data.alamat)
                            $('tbody.info-penduduk td.pendidikan').text(data.pendidikan)
                            $('tbody.info-penduduk td.warganegara').text(`${data.warganegara} / ${data.agama}`)
                        }
                    }, 'json')
                }
            })

            $('#nik').val(0)
            var penduduk = @json($penduduk->id);
            if (penduduk) {
                $('#nik').val(penduduk)
            }
            $('#nik').trigger('change')
        });
    </script>
@endpush
