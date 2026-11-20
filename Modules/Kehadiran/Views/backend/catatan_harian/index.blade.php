@use('Modules\Kehadiran\Enums\StatusCatatan')

@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Catatan Harian Kerja
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Catatan Harian Kerja</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <x-tambah-button :url="'kehadiran_catatan_harian/form'" class="btn-primary" />                
            @endif
        </div>

        <div class="box-body">            
            <!-- Filter Section -->
            <div class="row margin-bottom">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label>Pilih Status</label>
                        <select id="filter_status" class="form-control select2">
                            <option value="">Semua Status</option>
                            @foreach(StatusCatatan::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- DataTable -->
            <div class="table-responsive">
                <table id="tabeldata" class="table table-bordered table-striped table-hover">
                    <thead class="bg-gray color-palette">
                        <tr>
                            <th class="padat">No</th>
                            <th class="padat">Aksi</th>
                            <th>Tanggal</th>
                            <th>Uraian Kegiatan</th>
                            <th>Lokasi</th>
                            <th class="padat">Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')

    <!-- Modal untuk lihat foto -->
    <div class="modal fade" id="modalFoto" tabindex="-1" role="dialog" aria-labelledby="modalFotoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFotoLabel">
                        <i class="fa fa-image"></i> Foto Catatan Harian
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 600px; overflow-y: auto;">
                    <div id="loadingFoto" class="text-center padding">
                        <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="margin-top">Memuat foto...</p>
                    </div>
                    
                    <div id="fotoContainer" class="row" style="display: none;">
                        <!-- Foto akan dimuat di sini via AJAX -->
                    </div>

                    <div id="errorFoto" class="alert alert-danger" style="display: none; margin: 0;">
                        <i class="fa fa-exclamation-circle"></i>
                        <span id="errorMessage">Gagal memuat foto</span>
                    </div>

                    <div id="emptyFoto" class="alert alert-info text-center" style="display: none; margin: 0;">
                        <i class="fa fa-image fa-3x text-muted"></i>
                        <p class="margin-top text-muted">Tidak ada foto</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {    
    // Initialize DataTable
    const table = $('#tabeldata').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        pageLength: 25,
        order: [[ 2, "desc" ]], // Order by tanggal desc
        ajax: {
            url: "{{ route('kehadiran_catatan_harian.datatables') }}",
            data: function(d) {
                d.status = $('#filter_status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', class: 'padat', orderable: false, searchable: false },            
            { data: 'aksi', name: 'aksi', class: 'aksi', orderable: false, searchable: false },        
            { data: 'tanggal', name: 'tanggal', render: function(data, type, row) {
                return row.hari + ', ' + data;
            }},
            { data: 'uraian_kegiatan', name: 'uraian_kegiatan' },
            { data: 'lokasi_kegiatan', name: 'lokasi_kegiatan' },
            { data: 'status', class: 'padat', name: 'status' },
        ],
        rowCallback: function(row, data, index) {
            // Make sure HTML in cells is rendered
        }
    });

    // Make aksi and status columns render as HTML
    table.on('draw', function() {
        $.fn.dataTable.tables({ visible: true, api: true }).columns().every(function() {
            $(this.nodes()).each(function() {
                if ($(this).html().indexOf('<') !== -1) {
                    // This is HTML
                }
            });
        });
    });

    // Reload table on filter change
    $('#filter_status').change(function() {
        table.draw();
    });

    $(document).on('click', '.view-photos', function(e) {
        e.preventDefault();
        const uuid = $(this).data('uuid');
        
        // Reset all states
        $('#fotoContainer').empty().hide();
        $('#errorFoto').hide();
        $('#emptyFoto').hide();
        $('#loadingFoto').show();
        $('#modalFoto').modal('show');
        
        $.ajax({
            url: "{{ route('kehadiran_catatan_harian.fotos') }}",
            type: 'GET',
            data: { uuid: uuid },
            dataType: 'json',
            timeout: 10000,
            success: function(response) {
                $('#loadingFoto').hide();
                
                if (response.success) {
                    if (response.fotos && response.fotos.length > 0) {
                        let html = '';
                        response.fotos.forEach(function(foto, index) {
                            html += `
                            <div class="col-sm-6 col-md-4 margin-bottom">
                                <div class="thumbnail" style="text-align: center; cursor: pointer;">
                                    <img src="${foto.file_path}" alt="Foto ${index + 1}" 
                                         class="img-responsive img-thumbnail" 
                                         style="max-height: 250px; width: auto; margin: 0 auto; cursor: pointer;"
                                         onclick="window.open('${foto.file_path}', '_blank')"
                                         title="Klik untuk membuka fullsize">
                                    ${foto.keterangan ? '<p class="text-center text-muted margin-top"><small><strong>' + foto.keterangan + '</strong></small></p>' : ''}
                                </div>
                            </div>
                            `;
                        });
                        $('#fotoContainer').html(html).show();
                    } else {
                        $('#emptyFoto').show();
                    }
                } else {
                    $('#errorMessage').text(response.message || 'Gagal memuat foto');
                    $('#errorFoto').show();
                }
            },
            error: function(xhr, status, error) {
                $('#loadingFoto').hide();
                let message = 'Gagal memuat foto';
                if (status === 'timeout') {
                    message = 'Request timeout - coba lagi';
                } else if (xhr.status === 404) {
                    message = 'Endpoint tidak ditemukan';
                } else if (xhr.status === 500) {
                    message = 'Server error - coba lagi nanti';
                }
                $('#errorMessage').text(message);
                $('#errorFoto').show();
                console.error('Error loading fotos:', status, error);
            }
        });
    });

    $(document).on('click', '.btn-submit-catatan', function(e) {
        e.preventDefault();
        const url = $(this).data('href');
        
        Swal.fire({
            title: 'Kirim Catatan?',
            text: "Catatan akan dikirim untuk mendapatkan persetujuan. Anda tidak dapat mengubahnya lagi setelah dikirim (kecuali jika ditolak).",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = $('<form>', {
                    action: url,
                    method: 'POST'
                }).append($('<input>', {
                    type: 'hidden',
                    name: csrfParam,
                    value: getCsrfToken()
                }));
                
                $('body').append(form);
                form.submit();
            }
        });
    });
});
</script>
@endpush
