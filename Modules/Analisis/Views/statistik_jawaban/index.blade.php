@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.datetime_picker')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Laporan Statistik Jawaban
    </h1>
@endsection

@section('breadcrumb')
    <li>Master Analisis</li>
    <li>{{ $analisis_master['nama'] }}</li>
    <li class="active">Laporan Per Indikator</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-4 col-lg-3">
            @include('analisis::master.menu')
        </div>
        <div class="col-md-8 col-lg-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <form id="validasi" action="{{ ci_route('analisis_statistik_jawaban.' . $analisis_master['id'] . '.cetak') }}" method="POST" target="_blank">
                        <button name="tipe" value="cetak" type="submit" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-print"></i> Cetak</button>
                        <button name="tipe" value="unduh" type="submit" class="btn btn-social bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-download"></i> Unduh</button>
                        <input type="hidden" name="params">
                        <a href="{{ ci_route('analisis_master.menu', $analisis_master['id']) }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar RW"><i
                                class="fa fa-arrow-circle-left "></i>Kembali Ke
                            {{ $analisis_master['nama'] }}</a>
                    </form>

                </div>
                <div class="box-header with-border">
                    <h5>Analisis Statistik Jawaban - <a href="{{ ci_route("analisis_master.menu.{$analisis_master['id']}") }}">{{ $analisis_master['nama'] }}</a>
                    </h5>
                </div>

                <div class="box-body">
                    <div class="row mepet">
                        <div class="col-sm-3">
                            <select class="form-control input-sm select2" name="id_tipe">
                                <option value="">Pilih Tipe Indikator</option>
                                @foreach ($list_tipe as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control input-sm select2" name="id_kategori">
                                <option value="">Pilih Tipe Kategori</option>
                                @foreach ($list_kategori as $data)
                                    <option value="{{ $data['id'] }}">{{ $data['kategori'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control input-sm select2" name="act_analisis">
                                <option value="">Pilih Aksi Analisis</option>
                                <option value="1">Ya</option>
                                <option value="2">Tidak</option>
                            </select>
                        </div>
                        @include('admin.layouts.components.wilayah')
                    </div>
                    <hr class="batas">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <form id="mainform" name="mainform" method="post">
                            <div class="table-responsive">
                                <table id="tabeldata" class="table table-bordered table-striped table-hover">
                                    <thead class="bg-gray disabled color-palette judul-besar">
                                        <tr>
                                            <th>No</th>
                                            <th nowrap>Pertanyaan/Indikator</th>
                                            <th>Total</th>
                                            <th>Kode</th>
                                            <th width="30%" nowrap>Jawaban</th>
                                            <th>Responden</th>
                                            <th>Jumlah</th>
                                            <th nowrap>Tipe Pertanyaan</th>
                                            <th nowrap>Kategori/Variabel</th>
                                            <td nowrap>Aksi Analisis</td>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </form>
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
                    url: "{{ ci_route('analisis_statistik_jawaban.' . $analisis_master['id'] . '.datatables') }}",
                    data: function(req) {
                        req.dusun = $('#dusun').val()
                        req.rw = $('#rw').val()
                        req.rt = $('#rt').val()
                        req.klasifikasi = $('#klasifikasi').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'pertanyaan',
                        class: 'pertanyaan',
                    },
                    {
                        data: 'bobot',
                        name: 'bobot',
                        defaultContent: '-',
                        searchable: false,
                        orderable: false,
                        class: 'padat'
                    },
                    {
                        data: 'nomor',
                        name: 'nomor',
                        defaultContent: '-',
                        searchable: false,
                        orderable: true,
                        class: 'padat'
                    },
                    {
                        data: 'kode_jawaban',
                        name: 'kode_jawaban',
                        render: function(item, data, row) {
                            let _result = ['<ul>']
                            row.par.forEach(element => {
                                _result.push(`<li>${element.kode_jawaban}. ${element.jawaban}</li>`)
                            })
                            _result.push('</ul>')
                            return `${_result.join('')}`
                        },
                        class: 'nowrap',
                        defaultContent: '-',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'jml_p',
                        name: 'jml_p',
                        render: function(item, data, row) {
                            let _result = []
                            row.par.forEach(element => {
                                _result.push(`<a href="{{ ci_route('analisis_statistik_jawaban.' . $analisis_master['id'] . '.subjek_parameter') }}/${row.id}/${element.id}?${row.list_cluster}" >${element.jml_p}</a><br>`)
                            })
                            return `${_result.join('')}`
                        },
                        defaultContent: '-',
                        searchable: false,
                        orderable: false,
                        class: 'padat'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        defaultContent: '-',
                        searchable: false,
                        orderable: false,
                        class: 'padat'
                    },
                    {
                        data: 'id_tipe',
                        name: 'id_tipe',
                        defaultContent: '-',
                    },
                    {
                        data: 'kategori.kategori',
                        name: 'id_kategori',
                        defaultContent: '-',
                    },
                    {
                        data: 'act_analisis',
                        name: 'act_analisis',
                        defaultContent: '-',
                        class: 'padat'
                    },
                ],
                order: [
                    [3, 'asc']
                ]
            });

            $('#dusun, #rw, #rt').change(function() {
                TableData.draw()
            })

            $('select[name=id_tipe]').change(function() {
                TableData.column(7).search($(this).val()).draw();
            })
            $('select[name=id_kategori]').change(function() {
                TableData.column(8).search($(this).val()).draw();
            })
            $('select[name=act_analisis]').change(function() {
                TableData.column(9).search($(this).val()).draw();
            })

            $(document).ready(function() {
                $('form#validasi').submit(function() {
                    refreshFormCsrf()
                    let _objParams = $('#tabeldata').DataTable().ajax.params()
                    delete(_objParams.draw)
                    delete(_objParams.search)
                    $('form#validasi').find(`input[name=params])`).val(JSON.stringify(_objParams))
                })
            })
        });
    </script>
@endpush
@push('css')
    <style>
        .nowrap {
            white-space: nowrap;
        }

        .nowrap ul {
            list-style: none;
            padding: 0px;
        }
    </style>
@endpush
