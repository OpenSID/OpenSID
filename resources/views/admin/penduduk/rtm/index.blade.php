@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        Pengelompokan Rumah Tangga
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar Rumah Tangga</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a
                    href="{{ ci_route('rtm.form') }}"
                    title="Tambah"
                    data-remote="false"
                    data-toggle="modal"
                    data-target="#modalBox"
                    data-title="Tambah"
                    class="btn btn-social bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                ><i class='fa fa-plus'></i>Tambah</a>
            @endif
            @if (can('h'))
                <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','{{ ci_route('rtm.delete') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i>
                    Hapus</a>
            @endif

            @if (can('u'))
                <a
                    href="{{ ci_route('suplemen.impor') }}"
                    class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block btn-import"
                    title="Impor Data"
                    data-target="#impor"
                    data-remote="false"
                    data-toggle="modal"
                    data-backdrop="false"
                    data-keyboard="false"
                ><i class="fa fa-upload"></i>Impor</a>
            @endif
            <div class="btn-group-vertical">
                <a class="btn btn-social bg-orange btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Laporan</a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a
                            href="{{ ci_route('rtm.ajax_cetak.cetak') }}"
                            class="btn btn-social btn-block btn-sm"
                            title="Cetak Data"
                            data-remote="false"
                            data-toggle="modal"
                            data-target="#modalBox"
                            data-title="Cetak Data"
                        ><i class="fa fa-print"></i>
                            Cetak</a>
                    </li>
                    <li>
                        <a
                            href="{{ ci_route('rtm.ajax_cetak.unduh') }}"
                            class="btn btn-social btn-block btn-sm"
                            title="Unduh Data"
                            data-remote="false"
                            data-toggle="modal"
                            data-target="#modalBox"
                            data-title="Unduh Data"
                        ><i class="fa fa-file-excel-o"></i> Unduh</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-2">
                    <select id="status" class="form-control input-sm select2">
                        <option value="">Pilih Status</option>
                        @foreach ($status as $key => $item)
                            <option @selected($key == App\Enums\StatusEnum::YA) value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select id="jenis_kelamin" class="form-control input-sm select2">
                        <option value="">Pilih Jenis Kelamin</option>
                        @foreach ($jenis_kelamin as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select id="dusun" class="form-control input-sm select2">
                        <option value="">Pilih {{ ucwords(setting('sebutan_dusun')) }}</option>
                        @foreach ($wilayah as $item)
                            <option value="{{ $item->id }}">{{ $item->dusun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select id="rw" class="form-control input-sm select2">
                        <option value="">Pilih RW</option>
                        @foreach ($wilayah as $item)
                            <optgroup label="{{ $item->dusun }}">
                                @foreach ($item->rwAll as $child)
                                    <option value="{{ $child->id }}">{{ $child->rw }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select id="rt" class="form-control input-sm select2">
                        <option value="">Pilih RT</option>
                        @foreach ($wilayah as $item)
                            @foreach ($item->rwAll as $child)
                                <optgroup value={{ $child->id }} label="{{ $child->rw }}">
                                    @foreach ($item->rts->where('rw', $child->rw) as $rt)
                                        <option value="{{ $rt->id }}">{{ $rt->rt }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>
            <hr>
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            @if ($judul_statistik)
                <h5 class="box-title text-center"><b>{{ $judul_statistik }}</b></h5>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th nowrap><input type="checkbox" id="checkall"></th>
                            <th nowrap>NO</th>
                            <th nowrap>AKSI</th>
                            <th nowrap>FOTO</th>
                            <th nowrap>NOMOR RUMAH TANGGA</th>
                            <th nowrap>KEPALA RUMAH TANGGA</th>
                            <th nowrap>NIK</th>
                            <th nowrap>DTKS</th>
                            <th nowrap>JUMLAH ANGGOTA</th>
                            <th nowrap>ALAMAT</th>
                            <th nowrap>{{ strtoupper(setting('sebutan_dusun')) }}</th>
                            <th nowrap>RW</th>
                            <th nowrap>RT</th>
                            <th nowrap>TANGGAL TERDAFTAR</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
    @include('admin.penduduk.rtm.impor')
    @include('admin.penduduk.rtm.dtks_modal')
@endsection
@push('css')
    <style>
        .select2-results__option[aria-disabled=true] {
            display: none;
        }
    </style>
@endpush
@push('scripts')
    <script>
        function show_confirm(el) {
            $('#versi')
                .replaceWith("{{ \App\Enums\Dtks\DtksEnum::VERSION_LIST[\App\Enums\Dtks\DtksEnum::VERSION_CODE] }}")
            $('#rtm_clear').attr('href', "{{ ci_route('rtm') }}");
            $('#tujuan').attr('href', $(el).attr('href'))
        }
        $(document).ready(function() {
            let filterColumn = {!! json_encode($filterColumn) !!}
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('rtm.datatables') }}",
                    data: function(req) {
                        req.status = $('#status').val();
                        req.jenis_kelamin = $('#jenis_kelamin').val();
                        req.dusun = $('#dusun').val();
                        req.rw = $('#rw').val();
                        req.rt = $('#rt').val();
                        if (filterColumn['status']) {
                            req.bdt = filterColumn['status'];
                        }
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
                        data: 'foto',
                        name: 'foto',
                        searchable: false,
                        orderable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'no_kk',
                        name: 'no_kk',
                        searchable: true,
                        orderable: true,
                        render: function(row, data, item) {
                            return `<span class="text-bold">${item.no_kk}</span>`;
                        }
                    },
                    {
                        data: 'kepala_keluarga.nama',
                        name: 'kepalaKeluarga.nama',
                        defaultContent: '-',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kepala_keluarga.nik',
                        name: 'kepalaKeluarga.nik',
                        defaultContent: '-',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'terdaftar_dtks',
                        name: 'terdaftar_dtks',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'anggota_count',
                        name: 'anggota_count',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'kepala_keluarga.alamat_wilayah',
                        name: 'alamat_wilayah',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'kepala_keluarga.keluarga.wilayah.dusun',
                        name: 'dusun',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'kepala_keluarga.keluarga.wilayah.rw',
                        name: 'tw',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'kepala_keluarga.keluarga.wilayah.rt',
                        name: 'rt',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'tgl_daftar',
                        name: 'tgl_daftar',
                        searchable: false,
                        orderable: true
                    },


                ],
                order: [
                    [4, 'asc']
                ]
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }

            $('#dusun').change(function() {
                let _label = $(this).find('option:selected').text()
                $('#rw').find(`optgroup`).prop('disabled', 1)
                if ($(this).val()) {
                    $('#rw').closest('div').show()
                    $('#rw').find(`optgroup[label="${_label}"]`).prop('disabled', 0)
                } else {
                    $('#rw').closest('div').hide()
                    $('#rw').find(`optgroup`).prop('disabled', 1)
                }
                $('#rw').val('')
                $('#rw').trigger('change')
            })

            $('#rw').change(function() {
                let _label = $(this).find('option:selected').val()
                $('#rt').find(`optgroup`).prop('disabled', 1)
                if ($(this).val()) {
                    $('#rt').closest('div').show()
                    $('#rt').find(`optgroup[value="${_label}"]`).prop('disabled', 0)
                } else {
                    $('#rt').closest('div').hide()
                    $('#rt').find(`optgroup`).prop('disabled', 1)
                }
            })

            $('#status, #jenis_kelamin, #dusun, #rw, #rt').change(function() {
                TableData.draw()
            })

            if (filterColumn) {
                if (filterColumn['sex'] > 0) {
                    $('#jenis_kelamin').val(filterColumn['sex'])
                }
            }

            $('#dusun').trigger('change')
        });
    </script>
@endpush
