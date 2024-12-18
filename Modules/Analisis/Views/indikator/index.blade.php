@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaturan Indikator - {{ $analisis_master['nama'] }}
    </h1>
@endsection

@section('breadcrumb')
    <li>Master Indikator</li>
    <li>{{ $analisis_master['nama'] }}</li>
    <li class="active">Pengaturan Indikator</li>
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
                    @if (can('u'))
                        <a href="{{ ci_route('analisis_indikator.' . $analisis_master['id'] . '.form') }}" class="btn btn-social btn-success btn-sm"><i class='fa fa-plus'></i>
                            Tambah</a>
                    @endif
                    @if (can('h'))
                        <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('analisis_indikator.' . $analisis_master['id'] . '.delete') }}')"
                            class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"
                        ><i class='fa fa-trash-o'></i>
                            Hapus</a>
                    @endif
                    <a href="{{ ci_route('analisis_master.menu', $analisis_master['id']) }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i>{{ $analisis_master['nama'] }}</a>
                </div>
                <div class="box-body">
                    <div class="row mepet">
                        <div class="col-sm-3">
                            <select class="form-control input-sm select2" id="id_tipe" @disabled($disableFilter)>
                                <option value="">Tipe Pertanyaan</option>
                                @foreach (Modules\Analisis\Enums\TipePertanyaanEnum::all() as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control input-sm  select2" id="id_kategori" @disabled($disableFilter)>
                                <option value="">Tipe Kategori</option>
                                @foreach ($tipeKategori as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control input-sm  select2" id="act_analisis" @disabled($disableFilter)>
                                <option value="">Aksi Analisis</option>
                                @foreach (App\Enums\StatusEnum::all() as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="batas">
                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table id="tabeldata" class="table table-bordered table-striped dataTable table-hover">
                                                <thead class="bg-gray disabled color-palette judul-besar">
                                                    <tr>
                                                        <th><input type="checkbox" id="checkall" /></th>
                                                        <th>No</th>
                                                        <th>Aksi</th>
                                                        <th>Kode</th>
                                                        <th>Pertanyaan/Indikator</th>
                                                        <th>Tipe Pertanyaan</th>
                                                        <th>Kategori/Variabel</th>
                                                        <th>Bobot</th>
                                                        <th>Aksi Analisis</th>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ ci_route('analisis_indikator.' . $analisis_master['id'] . '.datatables') }}",
                columns: [{
                        data: 'ceklist',
                        searchable: false,
                        orderable: false,
                        class: 'padat',
                    },
                    {
                        data: 'DT_RowIndex',
                        searchable: false,
                        orderable: false,
                        class: 'padat',
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false,
                        class: 'aksi',
                    },
                    {
                        data: 'nomor',
                        name: 'nomor',
                        searchable: false,
                        orderable: true,
                        class: 'text-bold text-center'
                    },
                    {
                        data: 'pertanyaan',
                        name: 'pertanyaan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'id_tipe',
                        name: 'id_tipe',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kategori.kategori',
                        name: 'id_kategori',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'bobot',
                        name: 'bobot',
                        searchable: true,
                        orderable: true,
                        class: 'padat',
                    },
                    {
                        data: 'act_analisis',
                        name: 'act_analisis',
                        searchable: true,
                        class: 'padat',
                    },
                ],
                columnDefs: [{
                    type: 'num',
                    targets: 3
                }],
                order: [
                    [3, 'asc']
                ]
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }

            $('#id_kategori').change(function() {
                TableData.column(6).search($(this).val()).draw()
            })
            $('#id_tipe').change(function() {
                TableData.column(5).search($(this).val()).draw()
            })
            $('#act_analisis').change(function() {
                TableData.column(8).search($(this).val()).draw()
            })
        });
    </script>
@endpush
