@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Manajemen Pengguna
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Manajemen Pengguna</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.layouts.components.konfirmasi_hapus')
    <div class="row">
        <div class="col-md-3">
            @include('admin.pengaturan.pengguna.menu')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <?php if (can('u')) : ?>
                <div class="box-header with-border">
                    <x-tambah-button :url="'man_user/form'" />
                    @if (can('h'))
                        <button type="button"
                            class="btn btn-social btn-danger btn-sm hapus-terpilih"
                            onclick="return softDeleteAllConfirm('mainform', '{{ ci_route('man_user.delete_all') }}')"
                            title="Hapus Data">
                            <i class="fa fa-trash-o"></i> Hapus
                        </button>
                    @endif
                    @if (ci_auth()->id == super_admin())
                        <a id="btn-hapus-semua-permanen"
                            href="#"
                            data-href="{{ ci_route('man_user.cleanup_soft_deleted') }}"
                            data-method="POST"
                            class="btn btn-social btn-danger btn-sm hidden"
                            data-toggle="modal"
                            data-target="#confirm-delete">
                            <i class="fa fa-times"></i> Hapus Semua Permanen
                        </a>
                    @endif
                </div>
                @endif
                <div class="box-body">
                    <div class="row mepet">
                        <div class="col-sm-2">
                            <select id="status" class="form-control input-sm select2" name="status">
                                <option value="">Semua</option>
                                @foreach ($status as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                @endforeach
                                @if (ci_auth()->id == super_admin() && $soft_deleted_count > 0)
                                    <option value="deleted">Dihapus ({{ $soft_deleted_count }})</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select id="group" class="form-control input-sm select2" name="group">
                                <option value="">Semua</option>
                                @foreach ($user_group as $id => $item)
                                    <option value="{{ $id }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="batas">
                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                            <thead class="bg-gray">
                                <tr>
                                    <th class="padat"><input type="checkbox" id="checkall" /></th>
                                    <th class="padat">No</th>
                                    <th class="padat">Aksi</th>
                                    <th class="padat">Foto</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Staf</th>
                                    <th>Group</th>
                                    <th>Login Terakhir</th>
                                    <th>Status</th>
                                    <th>Tanggal Verifikasi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    </form>
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
                    url: "{{ ci_route('man_user.index') }}",
                    method: 'POST',
                    data: function(req) {
                        req.status = $('#status').val();
                        req.group = $('#group').val();
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
                        data: 'url_foto',
                        name: 'url_foto',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'username',
                        name: 'username',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pamong_status',
                        name: 'pamong_status',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'user_grup.nama',
                        name: 'userGrup.nama',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'last_login',
                        name: 'last_login',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'status_label',
                        name: 'active',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {data: 'email_verified_at', name: 'email_verified_at', class: 'padat', searchable: false, orderable: false},
                ],
                order: [
                    [4, 'asc']
                ],
                pageLength: 25,
                createdRow: function(row, data, dataIndex) {
                    if (data.jenis == 0 || data.jenis == 1) {
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

            function toggleDeletedMode(isDeleted) {
                TableData.column(0).visible(!isDeleted && hapus != 0);
                $('.hapus-terpilih').toggleClass('hidden', isDeleted);
                $('#btn-hapus-semua-permanen').toggleClass('hidden', !isDeleted);
                $('#tabeldata thead th:last-child').text(isDeleted ? 'Dihapus Pada' : 'Tanggal Verifikasi');
            }

            $('#status').select2().val(1).trigger('change');
            toggleDeletedMode($('#status').val() === 'deleted');
            TableData.draw();

            $('#status').on('select2:select', function(e) {
                toggleDeletedMode($(this).val() === 'deleted');
                TableData.draw();
            });
            $('#group').on('select2:select', function(e) {
                TableData.draw();
            });

            // Override #confirm-delete: jika trigger punya data-method="POST",
            // simpan URL-nya lalu cegah navigasi GET dan POST saat OK diklik.
            var pendingPostUrl = null;

            $('#confirm-delete').on('show.bs.modal', function(e) {
                var trigger = $(e.relatedTarget);
                if (trigger.data('method') && trigger.data('method').toUpperCase() === 'POST') {
                    pendingPostUrl = trigger.data('href');
                } else {
                    pendingPostUrl = null;
                }
            });

            $('#confirm-delete').on('click', '.btn-ok', function(e) {
                if (pendingPostUrl) {
                    e.preventDefault();
                    var url = pendingPostUrl;
                    pendingPostUrl = null;
                    $('#confirm-delete').modal('hide');
                    submitPost(url);
                }
            });
        });

        function swalKonfirmasi(opts, onConfirmed) {
            Swal.fire(Object.assign({
                showCancelButton : true,
                cancelButtonText : 'Batal',
            }, opts)).then(function(result) {
                if (result.isConfirmed) { onConfirmed(); }
            });
        }

        function softDeleteAllConfirm(idForm, action) {
            swalKonfirmasi({
                title            : 'Hapus Pengguna Terpilih?',
                text             : 'Pengguna yang dipilih akan dipindahkan ke tempat sampah.',
                icon             : 'warning',
                confirmButtonText: 'Ya, Hapus',
                confirmButtonColor: '#d33',
            }, function() {
                $('#' + idForm).attr('action', action);
                refreshFormCsrf();
                $('#' + idForm).submit();
            });
            return false;
        }

        /**
         * Submit URL sebagai POST dengan CSRF token.
         * Digunakan untuk aksi hapus/pulihkan agar tidak bisa diakses via URL langsung.
         */
        function submitPost(url) {
            var $form = $('<form method="POST" style="display:none"></form>').attr('action', url);
            $form.append($('<input type="hidden">').attr('name', csrfParam).val(getCsrfToken()));
            $('body').append($form);
            $form.submit();
        }

        function konfirmasiHapus(url, nama) {
            swalKonfirmasi({
                title            : 'Hapus Pengguna?',
                html             : 'Pengguna <strong>' + nama + '</strong> akan dihapus dan dapat dipulihkan kembali.',
                icon             : 'warning',
                confirmButtonText: 'Ya, Hapus',
                confirmButtonColor: '#d33',
            }, function() { submitPost(url); });
        }

        function konfirmasiPulihkan(url, nama) {
            swalKonfirmasi({
                title            : 'Pulihkan Pengguna?',
                html             : 'Pengguna <strong>' + nama + '</strong> akan dipulihkan.',
                icon             : 'question',
                confirmButtonText: 'Ya, Pulihkan',
                confirmButtonColor: '#00a65a',
            }, function() { submitPost(url); });
        }
    </script>
@endpush
