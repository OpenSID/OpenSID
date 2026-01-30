@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.datetime_picker')
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
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                @php
                    $listTambahPenduduk = [
                        [
                            'url' => 'penduduk/form_peristiwa/1',
                            'judul' => 'Penduduk Lahir',
                            'icon' => 'fa fa-plus',
                            'modal' => false,
                            'target' => false
                        ],
                        [
                            'url' => 'penduduk/form_peristiwa/5',
                            'judul' => 'Penduduk Masuk',
                            'icon' => 'fa fa-plus',
                            'modal' => false,
                            'target' => false
                        ],
                        [
                            'url' => 'penduduk/form_peristiwa/2',
                            'judul' => 'Penduduk Meninggal',
                            'icon' => 'fa fa-plus',
                            'modal' => false,
                            'target' => false
                        ]
                    ];
                @endphp

                <x-split-button
                    judul="Tambah Penduduk"
                    :list="$listTambahPenduduk"
                    icon="fa fa-plus"
                    type="btn-success"
                />
            @endif
            <x-hapus-button
                url="penduduk/delete_all"
                :confirmDelete="true"
                :selectData="true"
                judul="Hapus Data Terpilih"
            />

            @php
                $listAksiLainnya = [];

                if ($disableFilter) {
                    $listAksiLainnya = [
                        [
                            'url' => '#',
                            'judul' => 'Pencarian Spesifik',
                            'icon' => 'fa fa-search',
                            'modal' => false,
                            'target' => false,
                            'can' => false
                        ],
                        [
                            'url' => '#',
                            'judul' => 'Pencarian Program Bantuan',
                            'icon' => 'fa fa-search',
                            'modal' => false,
                            'target' => false,
                            'can' => false
                        ],
                        [
                            'url' => '#',
                            'judul' => 'Pilihan Kumpulan NIK',
                            'icon' => 'fa fa-search',
                            'modal' => false,
                            'target' => false,
                            'can' => false
                        ],
                        [
                            'url' => '#',
                            'judul' => 'NIK Sementara',
                            'icon' => 'fa fa-search',
                            'modal' => false,
                            'target' => false,
                            'can' => false
                        ]
                    ];
                } else {
                    $listAksiLainnya = [
                        [
                            'url' => 'penduduk/ajax_adv_search',
                            'judul' => 'Pencarian Spesifik',
                            'icon' => 'fa fa-search',
                            'modal' => true,
                            'target' => '#modalBox'
                        ],
                        [
                            'url' => 'penduduk/program_bantuan',
                            'judul' => 'Pencarian Program Bantuan',
                            'icon' => 'fa fa-search',
                            'modal' => true,
                            'target' => '#modalBox'
                        ],
                        [
                            'url' => 'penduduk/search_kumpulan_nik',
                            'judul' => 'Pilihan Kumpulan NIK',
                            'icon' => 'fa fa-search',
                            'modal' => true,
                            'target' => '#modalBox'
                        ],
                        [
                            'url' => '#',
                            'judul' => 'NIK Sementara',
                            'icon' => 'fa fa-search',
                            'modal' => false,
                            'target' => false,
                            'data' => [
                                'onclick' => "$('#tabeldata').data('nik_sementara', 1); $('#tabeldata').data('kumpulanNIK', []);$('#tabeldata').data('bantuan', null);$('#tabeldata').DataTable().draw(); return false;"
                            ]
                        ]
                    ];
                }

                // Gabungkan dengan menu cetak/unduh untuk satu dropdown
                if (!empty($listAksiLainnya)) {
                    $listAksiLainnya = array_merge($listAksiLainnya, [
                        [
                            'url' => "{$controller}/ajax_cetak/cetak",
                            'judul' => 'Cetak',
                            'icon' => 'fa fa-print',
                            'modal' => true,
                            'target' => '#modalBox',
                            'data' => [
                                'id' => 'cetak_id'
                            ]
                        ],
                        [
                            'url' => "{$controller}/ajax_cetak/unduh",
                            'judul' => 'Unduh',
                            'icon' => 'fa fa-download',
                            'modal' => true,
                            'target' => '#modalBox',
                            'data' => [
                                'id' => 'unduh_id'
                            ]
                        ]
                    ]);
                }
            @endphp

            @if (!empty($listAksiLainnya))
                <x-split-button
                    judul="Pilih Aksi Lainnya"
                    :list="$listAksiLainnya"
                    :icon="'fa fa-arrow-circle-down'"
                    :type="'btn-info'"
                    :target="true"
                />
            @endif

            @php
                $listImporEkspor = [];
                
                if (ci_auth()->id_grup == $akses) {
                    $listImporEkspor[] = [
                        'url' => 'penduduk/impor',
                        'judul' => 'Impor Penduduk',
                        'icon' => 'fa fa-upload',
                        'modal' => false,
                        'target' => false
                    ];
                    
                    if (!setting('multi_desa')) {
                        $listImporEkspor[] = [
                            'url' => 'penduduk/impor_bip',
                            'judul' => 'Impor BIP',
                            'icon' => 'fa fa-upload',
                            'modal' => false,
                            'target' => false
                        ];
                    }
                }

                $listImporEkspor = array_merge($listImporEkspor, [
                    [
                        'url' => 'penduduk/ekspor',
                        'judul' => 'Ekspor Penduduk',
                        'icon' => 'fa fa-download',
                        'modal' => false,
                        'target' => true,
                        'data' => [
                            'class' => 'btn-ekspor'
                        ]
                    ],
                    [
                        'url' => 'penduduk/ekspor/1',
                        'judul' => 'Ekspor Penduduk Huruf',
                        'icon' => 'fa fa-download',
                        'modal' => false,
                        'target' => true,
                        'data' => [
                            'class' => 'btn-ekspor'
                        ]
                    ]
                ]);
            @endphp

            <x-split-button
                judul="Impor / Ekspor"
                :list="$listImporEkspor"
                icon="fa fa-arrow-circle-down"
                type="bg-navy"
            />
            @if ($disableFilter)
                <x-btn-button
                    url="penduduk"
                    judul="Bersihkan"
                    icon="fa fa-refresh"
                    type="bg-purple"
                />
            @endif
        </div>
        <div class="box-body">
            <div class="row mepet">
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="status_penduduk" @disabled($disableFilter)>
                        <option value="">Pilih Status Penduduk</option>
                        @foreach ($list_status_penduduk as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control input-sm  select2" id="status_dasar" @disabled($disableFilter)>
                        <option value="">Pilih Status Dasar</option>
                        @foreach ($list_status_dasar as $key => $item)
                            <option value="{{ $key }}" @selected($defaultStatusDasar == $key)>{{ set_ucwords($item) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="jenis_kelamin" @disabled($disableFilter)>
                        <option value="">Pilih Jenis Kelamin</option>
                        @foreach ($list_jenis_kelamin as $key => $item)
                            <option value="{{ $key }}">{{ set_ucwords($item) }}</option>
                        @endforeach
                    </select>
                </div>
                @include('admin.layouts.components.wilayah')
            </div>
            <hr class="batas">
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
                            <th nowrap>JENIS KELAMIN</th>
                            <th nowrap>ALAMAT</th>
                            <th nowrap>{{ strtoupper(setting('sebutan_dusun')) }}</th>
                            <th nowrap>RW</th>
                            <th nowrap>RT</th>
                            <th nowrap>PENDIDIKAN DALAM KK</th>
                            <th nowrap>UMUR</th>
                            <th nowrap><?= HEADER_PEKERJAAN ?></th>
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
        
        .col-nama {
            min-width: 160px;
            white-space: normal;
            word-wrap: break-word;
        }
        .col-alamat {
            min-width: 200px;
            white-space: normal;
            word-wrap: break-word;
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
                        searchable: true,
                        orderable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        class: 'col-nama',
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
                        class: 'col-nama',
                        defaultContent: '',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama_ibu',
                        name: 'nama_ibu',
                        class: 'col-nama',
                        defaultContent: '',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'no_kk',
                        name: 'no_kk',
                        defaultContent: ''
                    },
                    {
                        data: 'jenis_kelamin',
                        name: 'jenis_kelamin',
                        searchable: false,
                        orderable: false,
                        defaultContent: '-',
                    },
                    {
                        data: 'alamat_wilayah',
                        name: 'alamat_wilayah',
                        class: 'col-alamat',
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
                        data: 'pendidikan_kk',
                        name: 'pendidikan_kk',
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
                        data: 'pekerjaan',
                        name: 'pekerjaan',
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

            let filterSelector = '#status_dasar, #status_penduduk, #jenis_kelamin, #dusun, #rw, #rt';

            // Saat user memilih dari Select2 hide judul statistik
            $(document).on('select2:select select2:clear', filterSelector, function (e) {
                $('#judul-statistik').hide();
                $('#tabeldata').data('statistikfilter', {});
                TableData.draw()
            });

            $(filterSelector).change(function() {
                TableData.draw()
            })

            if (filterColumn) {
                if (filterColumn['status_dasar'] > 0) {
                    $('#status_dasar').val(filterColumn['status_dasar'])
                    $('#status_dasar').trigger('change')
                }

                if (filterColumn['dusun']) {
                    $('#dusun').val(filterColumn['dusun'])
                    $('#dusun').trigger('change')

                    if (filterColumn['rw']) {
                        $('#rw').val(filterColumn['dusun'] + '__' + filterColumn['rw'])
                        $('#rw').trigger('change')
                    }

                    if (filterColumn['rt']) {
                        $('#rt').find('optgroup[value="' + filterColumn['dusun'] + '__' + filterColumn['rw'] + '"] option').filter(function() {
                            return $(this).text() == filterColumn['rt']
                        }).prop('selected', 1)
                        $('#rt').trigger('change')
                    }
                }

                if (filterColumn['sex']) {
                    $('#jenis_kelamin').val(filterColumn['sex'])
                    $('#jenis_kelamin').trigger('change')
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
