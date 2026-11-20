@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Daftar Surat
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar Surat</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            <x-tambah-button :url="'surat_master/form'" />
            <x-hapus-button confirmDelete="true" selectData="true" :url="'surat_master/delete'" />
            <x-impor-ekspor-grup-button :impor="ci_route('surat_master.impor')" :ekspor="ci_route('surat_master.ekspor')" target="impor-surat" />

            <x-btn-button judul="Pengaturan" icon="fa fa-gear" type="bg-purple" :url="'surat_master/pengaturan'" />
        </div>
        {!! form_open(null, 'id="mainform" name="mainform"') !!}
        <div class="box-body">
            <div class="row mepet">
                <div class="col-sm-2">
                    <select class="form-control input-sm select2" id="status" name="status">
                        <option value="">Pilih Status</option>
                        <option value="0" selected>Aktif</option>
                        <option value="1">Tidak Aktif</option>
                        {{-- Aktif = Kunci 0, Tidak Aktif = Kunci 1 --}}
                    </select>
                </div>
                <div class="col-sm-3">
                    <select class="form-control input-sm select2" id="jenis" name="jenis">
                        <option value="">Pilih Surat</option>
                        @foreach ($jenisSurat as $key => $value)
                            <option value="{{ $key }}">{{ SebutanDesa($value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr class="batas">
            <div class="table-responsive">
                <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                    <thead class="bg-gray">
                        <tr>
                            <th class="padat"><input type="checkbox" id="checkall" /></th>
                            <th class="padat">NO</th>
                            <th class="aksi">AKSI</th>
                            <th>NAMA SURAT</th>
                            <th class="padat">KODE / KLASIFIKASI</th>
                            <th class="padat">LAMPIRAN</th>
                            <th class="padat">STATUS VALIDASI</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
    @include('admin.pengaturan_surat.impor')
    @include('admin.layouts.components.restore_surat')
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('surat_master.datatables') }}",
                    method: 'POST',
                    data: function(d) {
                        d.status = $('#status').val();
                        d.jenis = $('#jenis').val();
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kode_surat',
                        name: 'kode_surat',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'lampiran',
                        name: 'lampiran',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'status_validasi',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [3, 'asc']
                ],
                pageLength: 25,
                createdRow: function(row, data, dataIndex) {
                    if (data.jenis == 2 || data.jenis == 4) {
                        $(row).addClass('select-row');
                    }
                }
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
                TableData.column(7).visible(false);
            }

            $('#status').on('select2:select', function(e) {
                TableData.draw();
            });

            $('#jenis').on('select2:select', function(e) {
                TableData.draw();
            });

            window.validasiTemplateSurat = function(url) {
                Swal.fire({
                    title: 'Memvalidasi template..',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: () => false
                });

                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                }).done(function(response) {
                    TableData.draw(false);

                    var temuan = response.temuan || [];
                    if (temuan.length === 0) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Template Valid',
                            text: 'Tidak ditemukan masalah pada template surat ini.',
                        });

                        return;
                    }

                    var html = '<ul style="text-align:left;">';
                    temuan.forEach(function(item) {
                        var warna = item.level === 'error' ? 'red' : '#c09853';
                        html += `<li style="color:${warna};margin-bottom:6px;">${item.pesan}</li>`;
                    });
                    html += '</ul>';

                    Swal.fire({
                        icon: response.status === 2 ? 'error' : 'warning',
                        title: response.status === 2 ? 'Template Tidak Valid' : 'Template Valid (dengan peringatan)',
                        html: html,
                    });
                }).fail(function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Validasi',
                        text: xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat memvalidasi template.',
                    });
                });
            };
        });
    </script>
@endpush
