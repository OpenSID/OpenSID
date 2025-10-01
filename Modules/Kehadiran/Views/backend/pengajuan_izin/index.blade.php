@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengajuan Izin
        <small>Kelola Pengajuan Izin Perangkat Desa</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Pengajuan Izin</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Data Pengajuan Izin</h3>
        </div>

        <div class="box-body">
            <!-- Filter Section -->
            <div class="row">
                <div class="col-sm-3">
                    <select id="filter_status" class="form-control input-sm select2">
                        <option value="">Semua Status</option>
                        @foreach(\Modules\Kehadiran\Enums\StatusApproval::all() as $key => $value)
                            <option value="{{ $key }}" @selected(\Modules\Kehadiran\Enums\StatusApproval::PENDING == $key)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <select id="filter_jenis" class="form-control input-sm select2">
                        <option value="">Semua Jenis Izin</option>
                        @foreach(\Modules\Kehadiran\Enums\JenisIzin::all() as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>                               
            </div>
            <br>

            <!-- DataTable -->
            <div class="table-responsive">
                <table id="tabeldata" class="table table-bordered table-striped table-hover">
                    <thead class="bg-gray color-palette">
                        <tr>
                            <th width="30px">No</th>
                            <th style="min-width:120px">Aksi</th>
                            <th>Nama Perangkat</th>
                            <th>Jabatan</th>
                            <th>Jenis Izin</th>
                            <th>Keterangan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th>Tanggal Pengajuan</th>                            
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>    
    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
<script>
$(document).ready(function() {          
    // Initialize DataTable
    window.table = $('#tabeldata').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,                
        ajax: {
            url: "{{ ci_route('kehadiran_pengajuan_izin.datatables') }}",
            data: function(d) {
                d.status = $('#filter_status').val();
                d.jenis_izin = $('#filter_jenis').val();                
            }
        },
        columns: [
            { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex', 
                class: 'padat',
                orderable: false, 
                searchable: false 
            },
            {
                data: 'aksi',
                name: 'aksi',                
                orderable: false,
                searchable: false
            },
            { 
                data: 'pamong_nama', 
                name: 'pamong.pamong_nama',
                searchable: true,
                orderable: true
            },
            { 
                data: 'pamong_jabatan', 
                name: 'pamong.jabatan.nama',
                searchable: true,
                orderable: true
            },
            { 
                data: 'jenis_izin', 
                name: 'jenis_izin',
                searchable: true,
                orderable: true
            },
            { 
                data: 'keterangan', 
                name: 'keterangan',
                searchable: false,
                orderable: false
            },
            { 
                data: 'tanggal_mulai', 
                name: 'tanggal_mulai',
                searchable: true,
                orderable: true
            },
            { 
                data: 'tanggal_selesai', 
                name: 'tanggal_selesai',
                searchable: true,
                orderable: true
            },
            { 
                data: 'durasi_hari', 
                name: 'durasi_hari',
                searchable: false,
                orderable: true
            },
            { 
                data: 'status_approval', 
                name: 'status_approval',
                searchable: true,
                orderable: true
            },
            { 
                data: 'created_at', 
                name: 'created_at',
                searchable: true,
                orderable: true
            }            
        ],
        order: [[ 8, "desc" ]],
        drawCallback: function(settings) {                    
            $('.approve-btn').click(function(e) {
                e.preventDefault();
                $('#ok-delete').html('<i class="fa fa-check"></i> Setujui');
                $('#ok-delete').removeClass('btn-danger').addClass('btn-primary');  
                $('#confirm-delete').find('.modal-body').html('Apakah Anda yakin ingin menyetujui pengajuan izin ini?');
            });
            $('.reject-btn').on('click', function(e) {
                e.preventDefault();
                $('#ok-delete').html('<i class="fa fa-times"></i> Tolak');
                $('#ok-delete').removeClass('btn-primary').addClass('btn-danger');
                $('#confirm-delete').find('.modal-body').html('Apakah Anda yakin ingin menolak pengajuan izin ini?');
            });
        }
    });

    // Filter change events
    $('#filter_status').on('change', function() {
        table.columns(9).search($(this).val()).draw();
    });
    $('#filter_jenis').on('change', function() {
        table.columns(4).search($(this).val()).draw();
    });
    $('#filter_status').trigger('change');
});
</script>
@endpush
