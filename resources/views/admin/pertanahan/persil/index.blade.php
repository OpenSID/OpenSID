@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Daftar Persil
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar Persil</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a href="{{ ci_route('data_persil.form') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
            @endif
            <a
                href="{{ ci_route('data_persil.dialog.cetak') }}"
                class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                title="Cetak Laporan"
                data-remote="false"
                data-toggle="modal"
                data-target="#modalBox"
                data-title="Cetak Laporan"
            >
                <i class="fa fa-print "></i>Cetak
            </a>
            <a
                href="{{ ci_route('data_persil.dialog.unduh') }}"
                class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                title="Unduh Laporan"
                data-remote="false"
                data-toggle="modal"
                data-target="#modalBox"
                data-title="Unduh Laporan"
            >
                <i class="fa fa-print "></i>Unduh
            </a>
        </div>
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="row mepet">
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" name="tipe">
                        <option value="">Tipe Tanah</option>
                        <option value="BASAH">Tanah Basah</option>
                        <option value="KERING">Tanah Kering</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" name="kelas">
                        <option value="">Kelas Tanah</option>
                        @foreach ($list_kelas as $key => $groups)
                            <optgroup value="{{ $key }}" label="{{ $key }}">
                                @foreach ($groups as $data)
                                    <option value="{{ $data->refKelas->id }}">{{ $data->refKelas->kode }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" name="lokasi">
                        <option value="">Tipe Lokasi</option>
                        <option value="1">Dalam Desa</option>
                        <option value="2">Luar Desa</option>
                    </select>
                </div>
                @include('admin.layouts.components.wilayah', ['colDusun' => 'col-sm-3'])
            </div>
            <hr class="batas">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr class="bg-gray judul-besar">
                            <th>No</th>
                            <th>Aksi</th>
                            <th>No. Persil : No. Urut Bidang</th>
                            <th>Tipe</th>
                            <th>Kelas Tanah</th>
                            <th>Luas (M2)</th>
                            <th>Lokasi</th>
                            <th>C-Desa Awal</th>
                            <th>Jml Mutasi</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>
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
                ajax: {
                    url: "{{ ci_route('data_persil.datatables') }}",
                    data: function(req) {
                        req.tipe = $('select[name="tipe"]').val()
                        req.kelas = $('select[name="kelas"]').val()
                        req.lokasi = $('select[name=lokasi]').val()
                        req.dusun = $('#dusun').val()
                        req.rw = $('#rw').val()
                        req.rt = $('#rt').val()
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false,
                        className: 'padat'
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false,
                        className: 'aksi'
                    },
                    {
                        data: 'nomor',
                        name: 'nomor',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'ref_kelas.tipe',
                        name: 'refKelas.tipe',
                        searchable: true,
                        orderable: false,
                        visible: false,
                    },
                    {
                        data: 'kelas',
                        name: 'kelas',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'luas_persil',
                        name: 'luas_persil',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'cdesa_awal',
                        name: 'cdesa_awal',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'mutasi_count',
                        name: 'mutasi_count',
                        searchable: false,
                        orderable: true,
                        className: 'padat'
                    },
                ],
                order: [],
                aaSorting: []
            });

            $('select[name=tipe]').change(function() {
                let _val = $(this).val()
                $('select[name=kelas]').find('optgroup').prop('disabled', 1)
                $('select[name=kelas]').find(`optgroup[label="${_val}"]`).prop('disabled', 0)
                TableData.draw();
            })
            $('select[name=kelas]').change(function() {
                TableData.draw();
            })
            $('select[name=lokasi]').change(function() {
                if ($(this).val() == 1) {
                    $('#dusun').closest('div').show()
                } else {
                    $('#dusun').closest('div').hide()
                    $('#dusun').val('')
                    $('#dusun').trigger('change')
                }
                TableData.draw();
            })
            $('#rt, #rw, #dusun').change(function() {
                TableData.draw();
            })

            $('select[name=kelas]').find('optgroup').prop('disabled', 1)
            $('select[name=lokasi]').trigger('change')
        });
    </script>
@endpush
