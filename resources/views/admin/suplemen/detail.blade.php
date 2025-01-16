@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Daftar Terdata Suplemen
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar Terdata Suplemen</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <div class="btn-group btn-group-vertical">
                    <a class="btn btn-social btn-flat btn-success btn-sm" data-toggle="dropdown"><i class='fa fa-plus'></i> Tambah</a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ site_url("suplemen/form_terdata/{$suplemen->id}/1") }}" class="btn btn-social btn-block btn-sm" title="Tambah Satu Data Warga"><i class="fa fa-plus"></i> Tambah Satu Data Warga</a>
                        </li>
                        <li>
                            <a href="{{ site_url("suplemen/form_terdata/{$suplemen->id}/2") }}" class="btn btn-social btn-block btn-sm" title="Tambah Beberapa Data Warga"><i class="fa fa-plus"></i> Tambah Beberapa Data Warga</a>
                        </li>
                    </ul>
                </div>
                @include('admin.layouts.components.tombol_cetak_unduh', [
                    'cetak' => "suplemen/dialog_daftar/{$suplemen->id}/cetak",
                    'unduh' => "suplemen/dialog_daftar/{$suplemen->id}/unduh",
                ])
                @include('admin.layouts.components.tombol_ekspor', [
                    'ekspor' => "suplemen/ekspor/{$suplemen->id}",
                ])
            @endif
            @if (can('h'))
                <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('suplemen.delete_all_terdata') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                        class='fa fa-trash-o'
                    ></i> Hapus</a>
            @endif
            @if (can('u'))
                <a href="{{ ci_route('suplemen') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Data Suplemen</a>
            @endif
        </div>
        @include('admin.suplemen.rincian')
        <hr style="margin-bottom: 5px;">
        <div class="box-body">
            <h5><b>Daftar Terdata</b></h5>
            <div class="row mepet">
                <div class="col-sm-2">
                    <select class="form-control input-sm" id="sex" name="sex">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="1">Laki-laki</option>
                        <option value="2">Perempuan</option>
                    </select>
                </div>
                @include('admin.layouts.components.wilayah')
            </div>
            <hr>
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkall" /></th>
                            <th class="padat">NO</th>
                            <th class="padat">AKSI</th>
                            <th>{{ $suplemen->sasaran == 1 ? 'NO.' : 'NIK' }} KK</th>
                            <th>{{ $suplemen->sasaran == 1 ? 'NIK PENDUDUK' : 'NO. KK' }}</th>
                            <th>{{ $suplemen->sasaran == 1 ? 'NAMA PENDUDUK' : 'KEPALA KELUARGA' }}</th>
                            <th>TEMPAT LAHIR</th>
                            <th>TANGGAL LAHIR</th>
                            <th>JENIS KELAMIN</th>
                            <th>ALAMAT</th>
                            <th>KETERANGAN</th>
                            <th>DATA FORM ISIAN</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@include('admin.layouts.components.filter_wilayah')

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('suplemen.datatables_terdata') }}",
                    data: function(req) {
                        req.id = {{ $suplemen->id }};
                        req.sasaran = {{ $suplemen->sasaran }};
                        req.sex = $('#sex').val();
                        req.dusun = $('#dusun').val();
                        req.rw = $('#rw').val();
                        req.rt = $('#rt').val();
                    },
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
                        data: 'terdata_info',
                        name: `{{ $suplemen->sasaran == '1' ? 'tweb_keluarga.no_kk' : 'tweb_penduduk.nik' }}`,
                        orderable: true
                    },
                    {
                        data: 'terdata_plus',
                        name: `{{ $suplemen->sasaran == '1' ? 'tweb_penduduk.nik' : 'tweb_keluarga.no_kk' }}`,
                        orderable: true
                    },
                    {
                        data: 'terdata_nama',
                        name: 'tweb_penduduk.nama',
                        orderable: true
                    },
                    {
                        data: 'tempatlahir',
                        name: 'tweb_penduduk.tempatlahir',
                        orderable: true
                    },
                    {
                        data: 'tanggallahir',
                        name: 'tanggallahir',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'sex',
                        name: 'sex',
                        searchable: false,
                        orderable: true,
                        class: 'padat'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat',
                        searchable: false,
                        orderable: false,
                        class: 'padat'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        orderable: false,
                        class: 'padat'
                    },
                    {
                        data: 'data_form_isian',
                        name: 'data_form_isian',
                        orderable: false,
                        class: 'padat',
                        render: function(data, type, row, meta) {
                            // Menampilkan tombol untuk melihat data form isian
                            return `<a href="javascript:void(0)" class="btn btn-info btn-sm" onclick="toggleDetails(${meta.row})">Selengkapnya</a>`;
                        }
                    }
                ],
                order: [
                    [3, 'asc']
                ],
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }

            $('#sex, #dusun, #rw, #rt').change(function() {
                TableData.draw()
            })

            // Fungsi untuk menampilkan detail saat tombol diklik
            window.toggleDetails = function(rowIndex) {
                var table = $('#tabeldata').DataTable();
                var row = table.row(rowIndex);
                var rowData = row.data();

                // Cek apakah sudah ada baris tambahan, jika ada maka hapus
                if (row.child.isShown()) {
                    row.child.hide();
                } else {
                    // Tampilkan baris tambahan dengan data form isian
                    row.child(formatDetails(rowData)).show();
                }
            };

            // Fungsi untuk format detail
            function formatDetails(data) {
                var detailsHtml = '<div class="details-row"><table class="table table-bordered"><tr>';

                // Iterasi formData untuk menampilkan key dan value
                for (var key in data.data_form_isian) {
                    if (data.data_form_isian.hasOwnProperty(key)) {
                        var formattedKey = formatKey(key);
                        detailsHtml += `<td><b>${formattedKey}</b>: ${data.data_form_isian[key]}</td><tr>`;
                    }
                }

                detailsHtml += '</table></div>';
                return detailsHtml;
            }


            // Fungsi untuk mendekodekan HTML entities
            function decodeHtmlEntities(text) {
                var element = document.createElement('div');
                if (text) {
                    element.innerHTML = text;
                    text = element.textContent;
                    element.textContent = '';
                }
                return text;
            }

            // Fungsi untuk memformat key: mengganti underscore dengan spasi dan kapitalisasi huruf pertama
            function formatKey(key) {
                return key.replace(/_/g, ' ').replace(/^\w/, (c) => c.toUpperCase());
            }

        });
    </script>
@endpush
