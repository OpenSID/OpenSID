@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengajuan Izin
        <small>Kelola Pengajuan Izin Pribadi</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Pengajuan Izin</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <x-tambah-button :url="'kehadiran_pengajuan_izin_pamong/form'" />                
            @endif
            @if (can('h'))
                <x-hapus-button confirmDelete="true" selectData="true" :url="'kehadiran_pengajuan_izin_pamong/delete_all'" />
            @endif
        </div>

        <div class="box-body">            
            <!-- Filter Section -->
            <div class="row">
                <div class="col-sm-4">
                    <select id="filter_status" class="form-control select2">
                        <option value="">Semua Status</option>
                        @foreach(\Modules\Kehadiran\Enums\StatusApproval::all() as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4">
                    <select id="filter_jenis" class="form-control select2">
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
                            <th><input type="checkbox" id="checkall" /></th>
                            <th class="padat">No</th>
                            <th style="min-width:120px" class="padat">Aksi</th>
                            <th>Jenis Izin</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>                            
                            <th>Keterangan</th>
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
    const table = $('#tabeldata').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        pageLength: 25,
        order: [[ 7, "desc" ]], // Order by tanggal pengajuan desc
        ajax: {
            url: "{{ route('kehadiran_pengajuan_izin_pamong.datatables') }}",
            data: function(d) {
                d.status = $('#filter_status').val();
                d.jenis_izin = $('#filter_jenis').val();
            }
        },
        columns: [
            { data: 'ceklist', name: 'ceklist',class: 'padat', orderable: false, searchable: false },
            { data: 'DT_RowIndex', name: 'DT_RowIndex',class: 'padat', orderable: false, searchable: false },            
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false },        
            { 
                data: 'jenis_izin', 
                name: 'jenis_izin'                
            },
            { data: 'tanggal_mulai', name: 'tanggal_mulai' },
            { data: 'tanggal_selesai', name: 'tanggal_selesai' },            
            { 
                data: 'keterangan', 
                name: 'keterangan',
                render: function(data) {
                    return data.length > 50 ? data.substring(0, 50) + '...' : data;
                }
            },
            { 
                data: 'status_approval', 
                name: 'status_approval',                
            },
            { data: 'created_at', name: 'created_at' },            
        ],
        
    });

    // Calculate duration when dates change
    $('#filter_status').on('change', function() {        
        table.columns(7).search(this.value).draw();        
    })
    $('#filter_jenis').on('change', function() {        
        table.columns(3).search(this.value).draw();
    })   
});

</script>
@endpush