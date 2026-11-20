@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Persetujuan Catatan Harian
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Persetujuan Catatan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <button type="button" class="btn btn-success btn-social btn-sm" id="btnBulkApprove" disabled>
                    <i class="fa fa-check"></i> Setujui Terpilih
                </button>
            @endif
        </div>

        <div class="box-body">
            <!-- Filter Section -->
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="filterStatus">Pilih Status</label>
                        <select id="filterStatus" class="form-control input-sm select2">
                            <option value="">Semua Status</option>
                            @foreach ($statuses as $value => $label)
                                @if ($value !== \Modules\Kehadiran\Enums\StatusCatatan::DRAFT->value)
                                    <option value="{{ $value }}" @if ($value === \Modules\Kehadiran\Enums\StatusCatatan::DRAFT->value) selected @endif>
                                        {{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filterPamong">Pilih Perangkat {{ ucwords(setting('sebutan_desa')) }}</label>
                        <select id="filterPamong" class="form-control input-sm select2">
                            <option value="">Semua Perangkat</option>
                            @foreach ($pamongs as $pamong)
                                <option value="{{ $pamong['id'] }}">{{ $pamong['nama'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="box-body">
                <!-- DataTable -->
                <div class="table-responsive">
                    <table id="tabeldata" class="table table-bordered table-striped table-hover">
                        <thead class="bg-gray color-palette">
                            <tr>
                                <th class="padat"><input type="checkbox" id="checkAll" title="Pilih Semua"></th>
                                <th class="padat">No</th>
                                <th class="aksi">Aksi</th>
                                <th>Tanggal</th>
                                <th>Nama Perangkat</th>
                                <th>Jabatan</th>
                                <th>Uraian Kegiatan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                var statusDefault = '{{ \Modules\Kehadiran\Enums\StatusCatatan::MENUNGGU->value }}';

                $('#filterStatus').val(statusDefault).trigger('change');

                // Initialize DataTable
                const table = $('#tabeldata').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    pageLength: 25,
                    order: [
                        [5, "desc"]
                    ], // Order by tanggal desc (index 5 after checkbox + rowindex)
                    ajax: {
                        url: "{{ route('kehadiran_approval_catatan.datatables') }}",
                        data: function(d) {
                            d.status = $('#filterStatus').val();
                            d.pamong_id = $('#filterPamong').val();
                        }
                    },
                    columns: [{
                            data: 'uuid',
                            name: 'uuid',
                            class: 'padat',
                            orderable: false,
                            searchable: false,
                            render: function(data) {
                                return '<input type="checkbox" class="row-checkbox" value="' + data +
                                    '">';
                            }
                        },
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
                            class: 'aksi',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal'
                        },
                        {
                            data: 'nama_pamong',
                            name: 'nama_pamong'
                        },
                        {
                            data: 'jabatan',
                            name: 'jabatan'
                        },
                        {
                            data: 'uraian_kegiatan',
                            name: 'uraian_kegiatan'
                        },
                    ]
                });

                // Handle "Select All" checkbox
                $('#checkAll').on('click', function() {
                    const isChecked = $(this).is(':checked');
                    $('#tabeldata tbody').find('.row-checkbox').prop('checked', isChecked);
                    updateBulkApproveButton();
                });

                // Handle individual row checkboxes
                $(document).on('change', '.row-checkbox', function() {
                    updateBulkApproveButton();

                    // Check/uncheck "Select All" based on all checkboxes
                    const allChecked = $('#tabeldata tbody').find('.row-checkbox').length === $(
                        '#tabeldata tbody').find('.row-checkbox:checked').length;
                    $('#checkAll').prop('checked', allChecked);
                });

                // Update bulk approve button state
                function updateBulkApproveButton() {
                    const checkedCount = $('#tabeldata tbody').find('.row-checkbox:checked').length;
                    $('#btnBulkApprove').prop('disabled', checkedCount === 0);
                }

                // Handle bulk approve button
                $('#btnBulkApprove').on('click', function() {
                    const selectedIds = $('#tabeldata tbody').find('.row-checkbox:checked').map(function() {
                        return $(this).val();
                    }).get();

                    if (selectedIds.length === 0) {
                        Swal.fire({
                            title: 'Peringatan!',
                            text: 'Pilih minimal satu catatan untuk disetujui.',
                            icon: 'warning',
                            confirmButtonColor: '#28a745',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Setujui ' + selectedIds.length + ' Catatan?',
                        text: 'Apakah Anda yakin ingin menyetujui catatan terpilih?',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Setujui',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitBulkApprove(selectedIds);
                        }
                    });
                });

                // Handle status filter change
                $('#filterStatus').on('change', function() {
                    table.ajax.reload();
                });

                // Handle pamong filter change
                $('#filterPamong').on('change', function() {
                    table.ajax.reload();
                });

                // Handle approve/reject button clicks
                $(document).on('click', '.btn-action', function(e) {
                    e.preventDefault();
                    const action = $(this).data('action');
                    const id = $(this).data('id');
                    const actionTitle = action === 'approve' ? 'Setujui' : 'Tolak';
                    const actionIcon = action === 'approve' ? 'success' : 'warning';

                    Swal.fire({
                        title: actionTitle + ' Catatan?',
                        text: 'Apakah Anda yakin ingin ' + (action === 'approve' ? 'menyetujui' :
                            'menolak') + ' catatan ini?',
                        icon: actionIcon,
                        showCancelButton: true,
                        confirmButtonColor: action === 'approve' ? '#28a745' : '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: actionTitle,
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (action === 'approve') {
                                submitSingleApprove(id);
                            } else if (action === 'reject') {
                                // Show modal for rejection reason
                                window.location.href =
                                    '{{ route('kehadiran_approval_catatan.detail', ':id') }}'.replace(
                                        ':id', id);
                            }
                        }
                    });
                });

                // Function to submit single approve
                function submitSingleApprove(id) {
                    const formData = new FormData();
                    formData.append('{{ $token_name }}', '{{ $token_value }}');

                    const url = '{{ route('kehadiran_approval_catatan.approve', ':id') }}'.replace(':id', id);

                    fetch(url, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#28a745',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    table.ajax.reload(null, false);
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan: ' + error.message,
                                icon: 'error',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'OK'
                            });
                        });
                }

                // Function to submit bulk approve
                function submitBulkApprove(selectedIds) {
                    const formData = new FormData();
                    selectedIds.forEach(id => {
                        formData.append('id_cb[]', id);
                    });
                    formData.append('{{ $token_name }}', '{{ $token_value }}');

                    fetch("{{ route('kehadiran_approval_catatan.bulk-approve') }}", {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#28a745',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    table.ajax.reload(null, false);
                                    $('#checkAll').prop('checked', false);
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonColor: '#dc3545',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan: ' + error.message,
                                icon: 'error',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        </script>
    @endpush
