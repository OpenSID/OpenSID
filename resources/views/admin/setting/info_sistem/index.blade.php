@include('admin.pengaturan_surat.asset_tinymce')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')
@include('admin.layouts.components.jsondiffpatch')

@extends('admin.layouts.index')

@push('css')
    <style>
        .scroll {
            height: 500px;
            overflow-y: auto;
        }

        .huge {
            font-size: 40px;
        }

        .bottom {
            display: flex;
            align-items: self-end;
        }

        ul.tree-folder {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        ul.tree-folder ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        ul.tree-folder ul {
            margin-left: 10px;
        }

        ul.tree-folder li {
            margin: 0;
            padding: 5px 7px;
            line-height: 20px;
            color: #369;
            font-weight: bold;
            border-left: 1px solid rgb(100, 100, 100);
        }

        ul.tree-folder li:last-child {
            border-left: none;
        }

        ul.tree-folder li:before {
            position: relative;
            top: -0.3em;
            height: 1em;
            width: 12px;
            color: white;
            border-bottom: 1px solid rgb(100, 100, 100);
            content: "";
            display: inline-block;
            left: -7px;
        }

        ul.tree-folder li:last-child:before {
            border-left: 1px solid rgb(100, 100, 100);
        }

        ul.tree-folder li i {
            position: absolute;
            right: 40px;
        }
    </style>
@endpush

@section('title')
    <h1>
        Info Sistem
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Info Sistem</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    @if ($disk)
        <div class="row">
            <div class="col-md-6">
                <div class="panel bg-yellow">
                    <div class="panel-heading">
                        <div class="row bottom">
                            <div class="col-xs-2">
                                <h1><i class="fa fa-hdd-o"></i></h1>
                            </div>
                            <div class="col-xs-10 text-right">
                                <div class="huge"><small style="font-size:60%">{{ $total_space }}</small></div>
                                <div>Total Ruang Penyimpanan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel bg-green">
                    <div class="panel-heading">
                        <div class="row bottom">
                            <div class="col-xs-2">
                                <h1><i class="fa fa-hdd-o"></i></h1>
                            </div>
                            <div class="col-xs-10 text-right">
                                <div class="huge"><small style="font-size:60%">{{ $free_space }}</small></div>
                                <div>Sisa Ruang Penyimpanan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form id="mainform" name="mainform" method="post">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#log_viewer" data-loaded="true">Logs</a></li>
                <li><a data-toggle="tab" data-load-type="log_aktivitas" href="#log_aktifitas">Log Aktivitas</a></li>
                <li><a data-toggle="tab" data-load-type="ekstensi" href="#ekstensi">Kebutuhan Sistem</a></li>
                @if (ci_auth()->id == super_admin())
                    <li><a data-toggle="tab" data-load-type="info_sistem" href="#info_sistem">Info Sistem</a></li>
                @endif
                <li><a data-toggle="tab" data-load-type="optimasi" href="#optimasi">Optimasi</a></li>
                <li><a data-toggle="tab" data-load-type="folder_desa" href="#folder_desa">Folder Desa</a></li>
                <li><a data-toggle="tab" data-load-type="file_desa" data-url="{{ ci_route('info_sistem.file_desa') }}" href="#file_desa">File Unggah Desa</a></li>
                @if (class_exists(\Modules\Keamanan\Services\Security\FileIntegrityService::class))
                    <li><a data-toggle="tab" onclick="loadSecurityReports()" href="#keamanan">Keamanan Folder Desa</a></li>
                @endif
            </ul>
            <div class="tab-content">
                <div id="log_viewer" class="tab-pane fade in active">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">File logs</h3>
                                    @if (can('h') && $files)
                                        <div class="box-tools">
                                            <span class="label pull-right"><input type="checkbox" id="checkall" class="checkall" />
                                        </div>
                                    @endif
                                </div>
                                <div class="box-body no-padding">
                                    <ul class="nav nav-pills nav-stacked scroll">
                                        @if (empty($files))
                                            <li><a href="#"><?= $file ?>File log tidak ditemukan</a></li>
                                        @else
                                            @foreach ($files as $file)
                                                <li {{ jecho($currentFile, $file, 'class="active"') }}><a href="?f={{ base64_encode($file) }}">
                                                        {{ $file }}
                                                        @if (can('h'))
                                                            <span class="pull-right-container">
                                                                <span class="label pull-right"><input type="checkbox" class="checkbox" name="id_cb[]" value="{{ $file }}" />
                                                    </a></span>
                                                    </span>
                                            @endif
                                            </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    @if ($currentFile)
                                        <a href="?dl={{ base64_encode($currentFile) }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block " title="Unduh file log"><i class="fa fa-download"></i> Unduh</a>
                                        @if (can('h'))
                                            <a href="#" data-href="?del={{ base64_encode($currentFile) }}" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block " title="Hapus log file" data-toggle="modal"
                                                data-target="#confirm-delete"
                                            ><i class="fa fa-trash-o"></i>Hapus log file</a>
                                            <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ route($controller . '.remove_log') }}?f={{ base64_encode($currentFile) }}')"
                                                class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"
                                            ><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
                                        @endif
                                    @endif
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="table-responsive">
                                                            @if (null === $logs)
                                                                <div>
                                                                    <strong>File log kosong atau lebih dari 500 Mb, silakan unduh.</strong>
                                                                </div>
                                                            @else
                                                                <div class="table-responsive">
                                                                    <table id="tabel-logs" class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                                                                        <thead class="bg-gray">
                                                                            <tr>
                                                                                <th>Level</th>
                                                                                <th>Tanggal</th>
                                                                                <th>Pesan</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($logs as $key => $log)
                                                                                <tr>
                                                                                    <td class="padat">
                                                                                        <h6><span class="label label-{{ $log['class'] }}">{{ $log['level'] }}</span></h6>
                                                                                    </td>
                                                                                    <td class="padat">{{ $log['date'] }}</td>
                                                                                    <td class="text">
                                                                                        @if (array_key_exists('extra', $log))
                                                                                            <a class="pull-right btn btn-primary btn-xs" data-toggle="collapse" href="#collapse{{ $key }}" aria-expanded="false" aria-controls="collapse{{ $key }}">
                                                                                                <span class="glyphicon glyphicon-search"></span>
                                                                                            </a>
                                                                                        @endif
                                                                                        {{ strip_tags($log['content']) }}
                                                                                        @if (array_key_exists('extra', $log))
                                                                                            <div class="collapse" id="collapse{{ $key }}">
                                                                                                {!! $log['extra'] !!}
                                                                                            </div>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="log_aktifitas" class="tab-pane fade in">
                    <div class="text-center" style="padding: 20px;">
                        <i class="fa fa-spinner fa-spin fa-2x"></i> Memuat data...
                    </div>
                </div>

                <div id="ekstensi" class="tab-pane fade in">
                    <div class="text-center" style="padding: 20px;">
                        <i class="fa fa-spinner fa-spin fa-2x"></i> Memuat data...
                    </div>
                </div>
                {{-- prettier-ignore-start --}}
                @if (ci_auth()->id == super_admin())
                    <div id="info_sistem" class="tab-pane fade in">
                        <div class="text-center" style="padding: 20px;">
                            <i class="fa fa-spinner fa-spin fa-2x"></i> Memuat data...
                        </div>
                    </div>
                @endif
                {{-- prettier-ignore-end --}}

            <div id="optimasi" class="tab-pane fade in">
                <div class="row">
                    <div class="col-sm-6">
                        <h5><b>CACHE</b></h5>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ str_replace(['\\', '//'], ['/', '/'], config('cache.stores.file.path')) }}" readonly>
                            @if (can('u'))
                                <span class="input-group-btn">
                                    <a href="{{ ci_route('info_sistem.cache_desa') }}" class="btn btn-info btn-flat">Bersihkan</a>
                                </span>
                            @endif
                        </div>
                        <hr>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ str_replace(['\\', '//'], ['/', '/'], config('view.compiled')) }}" readonly>
                            @if (can('u'))
                                <span class="input-group-btn">
                                    <a href="{{ ci_route('info_sistem.cache_blade') }}" class="btn btn-info btn-flat">Bersihkan</a>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div id="folder_desa" class="tab-pane fade in">
                <div class="text-center" style="padding: 20px;">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Memuat struktur folder...
                </div>
            </div>

            <div id="file_desa" class="tab-pane fade in"></div>
            
            @if (class_exists(\Modules\Keamanan\Services\Security\FileIntegrityService::class))
            {{-- Tab Keamanan File --}}
            <div id="keamanan" class="tab-pane fade in">
                <div class="text-center" style="padding: 20px;">
                    <i class="fa fa-spinner fa-spin fa-2x"></i> Memuat data keamanan...
                </div>
                @include('keamanan::backend.index')
            </div>
            @endif
        </div>
    </form>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        const loadedTabs = {
            log_viewer: true, // Tab aktif saat load
            log_aktivitas: false,
            ekstensi: false,
            info_sistem: false,
            optimasi: true, // Tab dengan konten statis
            folder_desa: false,
            file_desa: false,
            keamanan: false
        };

        let logTable;

        const tabLoadingText = {
            log_aktivitas: 'Memuat log aktivitas...',
            ekstensi: 'Memuat ekstensi...',
            info_sistem: 'Memuat info sistem...',
            folder_desa: 'Memuat struktur folder desa...',
            file_desa: 'Memuat file desa...',
            keamanan: 'Memuat data keamanan...'
        };

        const renderLoader = (text = 'Memuat data...') => `
            <div class="text-center text-muted" style="padding: 14px 0;">
                <i class="fa fa-refresh fa-spin"></i> ${text}
            </div>
        `;

        const tabUrls = {
            log_aktivitas: '{{ ci_route("info_sistem.datatablesLogAktifitas") }}',
            ekstensi: '{{ route("info_sistem.index") }}',
            info_sistem: '{{ route("info_sistem.index") }}',
            folder_desa: '{{ ci_route("info_sistem.folder_desa") }}',
            file_desa: '{{ ci_route("info_sistem.file_desa") }}',
            keamanan: '{{ ci_route("info_sistem.keamanan") }}'
        };

        $(function() {
            const hash = window.location.hash;

            // Handle hash navigation
            if (hash && hash !== '#tab-perubahan' && hash !== '#tab-properties') {
                const tabLink = $(`a[href="${hash}"]`);
                if (tabLink.length) {
                    tabLink.tab('show');
                    const loadType = tabLink.data('load-type');
                    if (loadType && !loadedTabs[loadType]) {
                        loadTabContent(loadType, hash);
                    }
                }
            }

            // Event listener untuk tab switching dengan lazy loading
            $('a[data-toggle="tab"]').on('click', function (e) {
                const tabHref = $(this).attr('href');
                const loadType = $(this).data('load-type');

                if (loadType && !loadedTabs[loadType]) {
                    loadTabContent(loadType, tabHref);
                }
            });

            // Setelah tab ditampilkan
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                const target = e.target.hash;
                const loadType = $(e.target).data('load-type');

                if (loadType && !loadedTabs[loadType]) {
                    loadTabContent(loadType, target);
                }

                if (target !== '#tab-perubahan' && target !== '#tab-properties') {
                    history.replaceState(null, null, target);
                }
            });

            // Initialize log viewer datatable only if loaded
            if ($('#tabel-logs').length) {
                initializeLogsTable();
            }

            function initializeLogsTable() {
                $('#tabel-logs').DataTable({
                    "processing": true,
                    "autoWidth": false,
                    "serverSide": false,
                    'pageLength': 10,
                    "order": [
                        [1, "desc"]
                    ],
                    "columnDefs": [{
                        "targets": [0, 2],
                        "orderable": false
                    }]
                });
            }

            // Check all functionality for logs
            function setupCheckAll() {
                $('.box-header').on('click', "#checkall", function() {
                    if ($(this).is(':checked')) {
                        $(".nav input[type=checkbox]").each(function() {
                            $(this).prop("checked", true);
                        });
                    } else {
                        $(".nav input[type=checkbox]").each(function() {
                            $(this).prop("checked", false);
                        });
                    }
                    $(".nav input[type=checkbox]").change();
                    enableHapusTerpilih();
                });
                $("[data-toggle=tooltip]").tooltip();
            }

            setupCheckAll();
            $('ul.nav').on('click', "input[name='id_cb[]']", function() {
                enableHapusTerpilih();
            });

            function enableHapusTerpilih() {
                if ($("input[name='id_cb[]']:checked:not(:disabled)").length <= 0) {
                    $(".hapus-terpilih").addClass('disabled');
                    $(".hapus-terpilih").attr('href', '#');
                } else {
                    $(".hapus-terpilih").removeClass('disabled');
                    $(".hapus-terpilih").attr('href', '#confirm-delete');
                }
            }
        });

        /**
         * Load tab content secara lazy
         */
        function loadTabContent(loadType, tabHref) {
            const tabElement = $(tabHref);
            
            if (tabElement.html().trim() === '' || tabElement.html().includes('Memuat')) {
                const loaderText = tabLoadingText[loadType] || 'Memuat data...';
                tabElement.html(renderLoader(loaderText));

                switch(loadType) {
                    case 'log_aktivitas':
                        loadLogAktifitas();
                        break;
                    case 'ekstensi':
                        loadEkstensi();
                        break;
                    case 'info_sistem':
                        loadInfoSistem();
                        break;
                    case 'folder_desa':
                        loadFolderDesa();
                        break;
                    case 'file_desa':
                        loadFileDesa(tabHref);
                        break;
                    case 'keamanan':
                        loadSecurityReports();
                        break;
                }
            }
        }

        /**
         * Load Log Aktivitas dengan Datatables
         */
        function loadLogAktifitas() {
            if (loadedTabs.log_aktivitas) {
                return;
            }

            const container = $('#log_aktifitas');
            container.html(`
                <div class="box box-info">
                    <div class="box-body">
                        <div id="log-aktifitas-loader" class="text-center text-muted" style="padding: 12px 0; display: none;">
                            <i class="fa fa-refresh fa-spin"></i> Memuat data...
                        </div>
                        <div class="row mepet">
                            <div class="col-sm-2">
                                <select class="form-control input-sm select2-lazy" id="log_name" name="log_name" placeholder="Pilih Kategori">
                                    <option value="">Pilih Kategori</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control input-sm select2-lazy" id="log_event" name="log_event" placeholder="Pilih Peristiwa">
                                    <option value="">Pilih Peristiwa</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control input-sm select2-lazy" id="username" name="username" placeholder="Pilih Pengguna">
                                    <option value="">Pilih Pengguna</option>
                                </select>
                            </div>
                        </div>
                        <hr class="batas">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover tabel-daftar" id="tabel-logaktifitas">
                                <thead class="bg-gray judul-besar">
                                    <tr>
                                        <th class="padat">No</th>
                                        <th class="padat">Aksi</th>
                                        <th class="padat">Kategori</th>
                                        <th class="padat">Peristiwa</th>
                                        <th class="padat">Subjek Tipe</th>
                                        <th class="padat">Penyebab Tipe</th>
                                        <th class="padat">Pelaku</th>
                                        <th>Deskripsi</th>
                                        <th class="padat">Dibuat Pada</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="logDetailModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title"><i class="fa fa-exclamation-triangle text-red"></i> &nbsp;Detail Log Aktivitas</h4>
                            </div>
                            <div class="modal-body">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab-perubahan" data-toggle="tab">Perubahan</a></li>
                                    <li><a href="#tab-properties" data-toggle="tab">Properti Lain</a></li>
                                </ul>
                                <div class="tab-content" style="margin-top: 15px;">
                                    <div class="tab-pane active" id="tab-perubahan">
                                        <div id="json-diff-output"></div>
                                    </div>
                                    <div class="tab-pane" id="tab-properties">
                                        <table class="table table-bordered table-striped" id="properties-table">
                                            <thead>
                                                <tr>
                                                    <th>Kunci</th>
                                                    <th>Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            `);

            loadedTabs.log_aktivitas = true;

            const $loader = $('#log-aktifitas-loader');
            const disableFilters = (state) => {
                $('#log_name, #log_event, #username').prop('disabled', state);
            };

            // Lazy load select2 options
            disableFilters(true);
            $loader.show();
            $.ajax({
                url: '{{ ci_route("info_sistem.get_select_options") }}',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.log_names) {
                        $('#log_name').append(data.log_names);
                    }
                    if (data.events) {
                        $('#log_event').append(data.events);
                    }
                    if (data.users) {
                        $('#username').append(data.users);
                    }
                    
                    // Initialize select2 setelah options loaded
                    $('.select2-lazy').each(function () {
                        const $el = $(this);
                        $el.select2({
                            allowClear: true,
                            placeholder: $el.attr('placeholder') || 'Pilih...',
                            dropdownParent: $el.parent(),
                            width: '100%'
                        });
                    });
                },
                complete: function () {
                    disableFilters(false);
                }
            });

            // Initialize datatable
            logTable = $('#tabel-logaktifitas').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                deferRender: true,
                searchDelay: 350,
                orderMulti: false,
                order: [[8, 'desc']],
                ajax: {
                    url: '{{ ci_route("info_sistem.datatables-log") }}',
                    type: 'GET',
                    data: function (d) {
                        d.log_name = $('#log_name').val();
                        d.log_event = $('#log_event').val();
                        d.username = $('#username').val();
                    }
                },
                columnDefs: [
                    {targets: [0, 1], orderable: false},
                    {targets: '_all', className: 'text-nowrap'}
                ],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
                    {data: 'aksi', name: 'aksi', searchable: false},
                    {data: 'log_name', name: 'log_name'},
                    {data: 'event_label', name: 'event', searchable: false},
                    {data: 'subject_type', name: 'subject_type'},
                    {data: 'causer_type', name: 'causer_type'},
                    {data: 'username', name: 'username', searchable: true},
                    {data: 'description', name: 'description'},
                    {data: 'created_at', name: 'created_at'}
                ]
            });

            logTable.on('preXhr.dt', function () {
                $loader.show();
            });

            logTable.on('xhr.dt', function () {
                $loader.hide();
            });

            $('#log_name, #log_event, #username').on('change', function () {
                logTable.ajax.reload();
            });

            $('#log_aktifitas').on('click', '.btn-detail-log', function (e) {
                e.preventDefault();

                if (! logTable) {
                    return;
                }

                const row = logTable.row($(this).closest('tr')).data();
                if (! row) {
                    return;
                }

                const props = row.properties || {};
                const changes = {
                    old: props.old || {},
                    attributes: props.attributes || {}
                };

                if (Object.keys(changes.old).length && Object.keys(changes.attributes).length) {
                    const delta = jsondiffpatch.diff(changes.old, changes.attributes);
                    const htmlDiff = jsondiffpatch.formatters.html.format(delta, changes.old);
                    $('#json-diff-output').html(htmlDiff);
                } else {
                    $('#json-diff-output').html('<div class="text-muted text-center">Tidak ada perubahan data.</div>');
                }

                const otherProps = Object.assign({}, props);
                delete otherProps.old;
                delete otherProps.attributes;

                const $tbody = $('#properties-table tbody');
                $tbody.empty();

                if (Object.keys(otherProps).length) {
                    $.each(otherProps, function(key, val) {
                        let valDisplay;
                        try {
                            const parsed = (typeof val === 'string') ? JSON.parse(val) : val;
                            valDisplay = (typeof parsed === 'object') ? `<pre>${JSON.stringify(parsed, null, 2)}</pre>` : parsed;
                        } catch (err) {
                            valDisplay = val;
                        }
                        $tbody.append(`<tr><td>${key}</td><td>${valDisplay}</td></tr>`);
                    });
                } else {
                    $tbody.append('<tr><td colspan="2" class="text-muted text-center">Tidak ada properti tambahan.</td></tr>');
                }

                $('#logDetailModal').modal('show');
            });
        }

        /**
         * Load Ekstensi tab
         */
        function loadEkstensi() {
            if (loadedTabs.ekstensi) {
                return;
            }

            const $target = $('#ekstensi');
            $target.html(renderLoader(tabLoadingText.ekstensi));

            $.ajax({
                url: '{{ ci_route("info_sistem.load_ekstensi") }}',
                type: 'GET',
                success: function(data) {
                    $target.html(data);
                    loadedTabs.ekstensi = true;
                }
            });
        }

        /**
         * Load Info Sistem (PHP Info)
         */
        function loadInfoSistem() {
            if (loadedTabs.info_sistem) {
                return;
            }

            const $target = $('#info_sistem');
            $target.html(renderLoader(tabLoadingText.info_sistem));

            $.ajax({
                url: '{{ ci_route("info_sistem.load_phpinfo") }}',
                type: 'GET',
                success: function(data) {
                    $target.html(data);
                    loadedTabs.info_sistem = true;
                }
            });
        }

        /**
         * Load Folder Desa dengan struktur tree
         */
        function loadFolderDesa() {
            if (loadedTabs.folder_desa) {
                return;
            }

            const $target = $('#folder_desa');
            $target.html(renderLoader(tabLoadingText.folder_desa));

            $.ajax({
                url: '{{ ci_route("info_sistem.load_folder_desa") }}',
                type: 'GET',
                success: function(data) {
                    $target.html(data);
                    loadedTabs.folder_desa = true;
                }
            });
        }

        /**
         * Load File Desa
         */
        function loadFileDesa(tabHref) {
            if (loadedTabs.file_desa) {
                return;
            }

            const _url = $(`a[href="${tabHref}"]`).data('url') || '{{ ci_route("info_sistem.file_desa") }}';
            const _target = tabHref;

            if ($(_target).html().trim() === '' || $(_target).html().includes('Memuat')) {
                $(_target).html(renderLoader(tabLoadingText.file_desa));

                $.get(_url, function(data) {
                    $(_target).html(data);
                    loadedTabs.file_desa = true;
                }, 'html');
            }
        }

        /**
         * Load Security Reports
         */
        function loadSecurityReports() {
            if (loadedTabs.keamanan) {
                return;
            }

            const $target = $('#keamanan');
            $target.html(renderLoader(tabLoadingText.keamanan));

            $.ajax({
                url: '{{ ci_route("info_sistem.load_security_reports") }}',
                type: 'GET',
                success: function(data) {
                    $target.html(data);
                    loadedTabs.keamanan = true;
                }
            });
        }

        /**
         * Update folder permissions
         */
        function updatePermission(elm) {
            let _folderDesa = $(elm).closest('#folder_desa');
            let _data = [];
            _folderDesa.find('.box-body li.text-red').each(function(i, v) {
                _data.push($(v).data('path'));
            });

            if (_data.length) {
                Swal.fire({
                    title: 'Sedang Menyimpan',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    url: 'info_sistem/set_permission_desa',
                    dataType: "JSON",
                    data: {
                        folders: _data
                    },
                    type: "POST",
                    success: function(data) {
                        Swal.fire({
                            'icon': 'success',
                            'title': 'Success',
                            'timer': 2000,
                            'text': data.message
                        }).then((result) => {
                            window.location.hash = '#folder_desa';
                            window.location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            'icon': 'error',
                            'title': 'Error',
                            'timer': 2000,
                            'text': 'Terjadi kesalahan saat memproses permintaan'
                        });
                    }
                });
            } else {
                Swal.fire({
                    'icon': 'info',
                    'title': 'Info',
                    'timer': 2000,
                    'text': 'Tidak ada yang harus diubah permissionnya'
                });
            }
        }
    </script>
@endpush
