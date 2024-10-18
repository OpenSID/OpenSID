@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.datetime_picker')
@section('title')
    <h1>
        Data Penduduk
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Data Penduduk</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <div class="btn-group btn-group-vertical">
                    <a class="btn btn-social btn-success btn-sm" data-toggle="dropdown"><i class='fa fa-plus'></i> Tambah Penduduk</a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ ci_route('penduduk.form_peristiwa.1') }}" class="btn btn-social btn-block btn-sm" title="Tambah Data Penduduk Lahir"><i class="fa fa-plus"></i> Penduduk Lahir</a>
                        </li>
                        <li>
                            <a href="{{ ci_route('penduduk.form_peristiwa.5') }}" class="btn btn-social btn-block btn-sm" title="Tambah Data Penduduk Masuk"><i class="fa fa-plus"></i> Penduduk Masuk</a>
                        </li>
                    </ul>
                </div>
            @endif
            @if (can('h') && !data_lengkap())
                <a href="#confirm-delete" title="Hapus Data Terpilih" onclick="deleteAllBox('mainform', '{{ ci_route('penduduk.delete_all') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                        class='fa fa-trash-o'
                    ></i> Hapus Data Terpilih</a>
            @endif
            <div class="btn-group-vertical">
                <a class="btn btn-social btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Pilih Aksi Lainnya</a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a
                            id="cetak_id"
                            href="{{ ci_route('penduduk.ajax_cetak.cetak') }}"
                            class="btn btn-social btn-block btn-sm"
                            title="Cetak Data"
                            data-remote="false"
                            data-toggle="modal"
                            data-target="#modalBox"
                            data-title="Cetak Data"
                        ><i class="fa fa-print"></i> Cetak</a>
                    </li>
                    <li>
                        <a
                            id="unduh_id"
                            href="{{ ci_route('penduduk.ajax_cetak.unduh') }}"
                            class="btn btn-social btn-block btn-sm"
                            title="Unduh Data"
                            data-remote="false"
                            data-toggle="modal"
                            data-target="#modalBox"
                            data-title="Unduh Data"
                        ><i class="fa fa-download"></i> Unduh</a>
                    </li>
                    <li>
                        <a
                            href="{{ ci_route('penduduk.ajax_adv_search') }}"
                            class="btn btn-social btn-block btn-sm"
                            title="Pencarian Spesifik"
                            data-remote="false"
                            data-toggle="modal"
                            data-target="#modalBox"
                            data-title="Pencarian Spesifik"
                        ><i class="fa fa-search"></i> Pencarian Spesifik</a>
                    </li>
                    <li>
                        <a
                            href="{{ ci_route('penduduk.program_bantuan') }}"
                            class="btn btn-social btn-block btn-sm"
                            title="Pencarian Program Bantuan"
                            data-remote="false"
                            data-toggle="modal"
                            data-target="#modalBox"
                            data-title="Pencarian Program Bantuan"
                        ><i class="fa fa-search"></i> Pencarian Program Bantuan</a>
                    </li>
                    <li>
                        <a
                            href="{{ ci_route('penduduk.search_kumpulan_nik') }}"
                            class="btn btn-social btn-block btn-sm"
                            title="Pilihan Kumpulan NIK"
                            data-remote="false"
                            data-toggle="modal"
                            data-target="#modalBox"
                            data-title="Pilihan Kumpulan NIK"
                        ><i class="fa fa-users"></i> Pilihan Kumpulan NIK</a>
                    </li>
                    <li>
                        <a href="#" onclick="$('#tabeldata').data('nik_sementara', 1);$('#tabeldata').data('kumpulanNIK', []);$('#tabeldata').data('bantuan', null);$('#tabeldata').DataTable().draw()" class="btn btn-social btn-block btn-sm" title="NIK Sementara"><i class="fa fa-search"></i> NIK
                            Sementara</a>
                    </li>
                </ul>
            </div>
            <div class="btn-group-vertical">
                <a class="btn btn-social bg-navy btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Impor / Ekspor</a>
                <ul class="dropdown-menu" role="menu">
                    @if (!config_item('demo_mode') && auth()->id_grup == $akses && !data_lengkap())
                        <li>
                            <a href="{{ ci_route('penduduk.impor') }}" class="btn btn-social btn-block btn-sm" title="Impor Penduduk"><i class="fa fa-upload"></i> Impor Penduduk</a>
                        </li>
                        @if (!setting('multi_desa'))
                            <li>
                                <a href="{{ ci_route('penduduk.impor_bip') }}" class="btn btn-social btn-block btn-sm" title="Impor BIP"><i class="fa fa-upload"></i> Impor BIP</a>
                            </li>
                        @endif
                    @endif
                    <li>
                        <a href="{{ ci_route('penduduk.ekspor') }}" target="_blank" class="btn btn-social btn-block btn-sm btn-ekspor" title="Ekspor Penduduk"><i class="fa fa-download"></i> Ekspor Penduduk</a>
                    </li>
                    <li>
                        <a href="{{ ci_route('penduduk.ekspor.1') }}" target="_blank" class="btn btn-social btn-block btn-sm btn-ekspor" title="Ekspor Penduduk Berupa Isian Lengkap (Huruf)"><i class="fa fa-download"></i> Ekspor Penduduk Huruf</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="box-body">
            <div class="row mepet">
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="status_penduduk">
                        <option value="">Status Penduduk</option>
                        @foreach ($list_status_penduduk as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control input-sm  select2" id="status_dasar">
                        <option value="">Status Dasar</option>
                        @foreach ($list_status_dasar as $key => $item)
                            <option value="{{ $key }}" @selected($defaultStatusDasar == $key)>{{ set_ucwords($item) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="jenis_kelamin">
                        <option value="">Jenis Kelamin</option>
                        @foreach ($list_jenis_kelamin as $key => $item)
                            <option value="{{ $key }}">{{ set_ucwords($item) }}</option>
                        @endforeach
                    </select>
                </div>
                @include('admin.layouts.components.wilayah')
            </div>
            <hr>
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            @if ($judul_statistik)
                <h5 id="judul-statistik" class="box-title text-center"><b>{{ $judul_statistik }}</b></h5>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata" data-advancesearch='{!! json_encode($advanceSearch) !!}' data-statistikfilter='{!! json_encode($statistikFilter) !!}'>
                    <thead>
                        <tr>
                            <th nowrap><input type="checkbox" id="checkall"></th>
                            <th nowrap>NO</th>
                            <th nowrap>AKSI</th>
                            <th nowrap>FOTO</th>
                            <th nowrap>NIK</th>
                            <th nowrap>TAG ID CARD</th>
                            <th nowrap>NAMA</th>
                            <th nowrap>NO. KK</th>
                            <th nowrap>NAMA AYAH</th>
                            <th nowrap>NAMA IBU</th>
                            <th nowrap>NO. RUMAH TANGGA</th>
                            <th nowrap>ALAMAT</th>
                            <th nowrap>{{ strtoupper(setting('sebutan_dusun')) }}</th>
                            <th nowrap>RW</th>
                            <th nowrap>RT</th>
                            <th nowrap>PENDIDIKAN DALAM KK</th>
                            <th nowrap>UMUR</th>
                            <th nowrap>PEKERJAAN</th>
                            <th nowrap>KAWIN</th>
                            <th nowrap>TGL PERISTIWA</th>
                            <th nowrap>TGL TERDAFTAR</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            </form>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
    @include('admin.layouts.components.konfirmasi', ['periksa_data' => true])
@endsection
@push('css')
    <style>
        .select2-results__option[aria-disabled=true] {
            display: none;
        }

        .row.mepet>div {
            margin-right: -25px;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            let filterColumn = {!! json_encode($filterColumn) !!}
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('penduduk.datatables') }}",
                    data: function(req) {
                        req.status_penduduk = $('#status_penduduk').val();
                        req.status_dasar = $('#status_dasar').val();
                        req.jenis_kelamin = $('#jenis_kelamin').val();
                        req.dusun = $('#dusun').val();
                        req.rw = $('#rw').val();
                        req.rt = $('#rt').val();
                        req.kumpulan_nik = $('#tabeldata').data('kumpulanNIK')
                        req.nik_sementara = $('#tabeldata').data('nik_sementara')
                        req.bantuan = $('#tabeldata').data('bantuan')
                        req.advancesearch = $('#tabeldata').data('advancesearch')
                        req.statistikfilter = $('#tabeldata').data('statistikfilter')
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
                        data: 'nik',
                        name: 'nik',
                        render: function(item, data, row) {
                            return `<a href='{{ ci_route('penduduk.detail') }}/${row.id}'>${item}</a>`
                        },
                        searchable: true,
                        orderable: true,
                        defaultContent: ''
                    },
                    {
                        data: 'tag_id_card',
                        name: 'tag_id_card',
                        searchable: false,
                        orderable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true,
                        defaultContent: ''
                    },
                    {
                        data: 'keluarga.no_kk',
                        name: 'keluarga.no_kk',
                        render: function(item, data, row) {
                            return !item ? '' : `<a href='{{ ci_route('keluarga.kartu_keluarga') }}/${row.id_kk}'>${item}</a>`
                        },
                        searchable: true,
                        orderable: true,
                    },
                    {
                        data: 'nama_ayah',
                        name: 'nama_ayah',
                        defaultContent: '',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama_ibu',
                        name: 'nama_ibu',
                        defaultContent: '',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'rtm.no_kk',
                        name: 'rtm.no_kk',
                        searchable: false,
                        orderable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'alamat_wilayah',
                        name: 'alamat_wilayah',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'wilayah.dusun',
                        name: 'dusun',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'wilayah.rw',
                        name: 'tw',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'wilayah.rt',
                        name: 'rt',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'pendidikan_k_k.nama',
                        name: 'pendidikan_k_k.nama',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'umur',
                        name: 'tanggallahir',
                        searchable: false,
                        orderable: true,
                        defaultContent: '-',
                    },
                    {
                        data: 'pekerjaan.nama',
                        name: 'pekerjaan.nama',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'status_perkawinan',
                        name: 'status_perkawinan',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'tgl_peristiwa',
                        name: 'log_latest.tgl_peristiwa',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: false,
                        orderable: true
                    },

                ],
                order: [
                    [4, 'asc']
                ],
                createdRow: function(row, data, dataIndex) {
                    if (data.valid_kk) {
                        $(row).addClass(data.valid_kk);
                    }
                },
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }

            $('#status_dasar, #status_penduduk, #jenis_kelamin, #dusun, #rw, #rt').change(function() {
                TableData.draw()
            })

            if (filterColumn) {
                if (filterColumn['status_dasar'] > 0) {
                    $('#status_dasar').val(filterColumn['status_dasar'])
                    $('#status_dasar').trigger('change')
                }
            }

            $('.btn-ekspor').click(function() {
                let _href = $(this).attr('href')
                let _newHref = _href + '?params=' + JSON.stringify($('#tabeldata').DataTable().ajax.params())
                location.href = _newHref
            })
        });
    </script>
@endpush
