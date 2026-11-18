@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.datetime_picker')

@extends('admin.layouts.index')

@section('title')
    <h1>
        {{ $title }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('surat') }}">{{ $title }}</a></li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @includeWhen($widgets, 'admin.surat.keluar.widgets')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                @if ($tab_ini == 10)
                    <div class="box-header with-border">
                        <x-btn-button 
                            judul="Rekam Surat Perorangan"
                            icon="fa fa-archive"
                            type="bg-olive"
                            :url="'keluar/perorangan'"
                        />
                        <x-btn-button 
                            judul="Pie Surat Keluar"
                            icon="fa fa-pie-chart"
                            type="bg-orange"
                            :url="'keluar/graph'"
                        />
                        @php
                            $listCetakUnduh = [
                                [
                                    'url' => "keluar/dialog_cetak/cetak",
                                    'judul' => 'Cetak',
                                    'icon' => 'fa fa-print',
                                    'modal' => true,
                                ],
                                [
                                    'url' => "keluar/dialog_cetak/unduh",
                                    'judul' => 'Unduh',
                                    'icon' => 'fa fa-download',
                                    'modal' => true,
                                ]
                            ];
                        @endphp

                        <x-split-button
                            judul="Cetak/Unduh"
                            :list="$listCetakUnduh"
                            :icon="'fa fa-arrow-circle-down'"
                            :type="'bg-purple'"
                            :target="true"
                        />
                    </div>
                @endif

                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <form id="mainform" name="mainform" method="post">
                                    <div class="row mepet">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <select class="form-control input-sm select2 filter-table" name="tahun">
                                                    <option value="">Pilih Tahun</option>
                                                    @foreach ($tahun_surat as $thn)
                                                        <option value="{{ $thn->tahun }}">
                                                            {{ $thn->tahun }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <select class="form-control input-sm select2 filter-table" name="bulan">
                                                    <option value="">Pilih Bulan</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <select class="form-control input-sm select2 filter-table" name="jenis" style="width: 100%;">
                                                    <option value="">Pilih Jenis Surat</option>
                                                    @foreach ($jenis_surat as $data)
                                                        <option data='{!! json_encode($data) !!}' value="{{ $data['id'] ?? '' }}">
                                                            {{ $data['nama'] ?? '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            @if (can('u'))
                                                <div class="form-group">
                                                    <button id="perbaiki" type="button" title="Semua surat yang berstatus proses atau tidak ada statusnya akan diubah menjadi siap cetak"
                                                        class="btn btn-social bg-orange btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                                                    ><i class="fa fa-cogs "></i>Perbaiki</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <hr class="batas">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table id="tabeldata" class="table table-bordered dataTable table-striped table-hover">
                                                    <thead class="bg-gray disabled color-palette">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Aksi</th>
                                                            <th nowrap>Kode Surat</th>
                                                            <th>No Urut</th>
                                                            <th nowrap>Jenis Surat</th>
                                                            <th nowrap>Terlapor</th>
                                                            <th nowrap>Pemohon</th>
                                                            <th nowrap>Keterangan</th>
                                                            <th nowrap>Ditandatangani Oleh</th>
                                                            <th nowrap>Tanggal</th>
                                                            <th>User</th>
                                                            <th>Status</th>
                                                            <th>Alasan Ditolak</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.layouts.components.konfirmasi_hapus')

    <!-- Modal Timeline Penolakan -->
    <div class="modal fade" id="modalTimeline" tabindex="-1" role="dialog" aria-labelledby="modalTimelineLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalTimelineLabel">
                        <i class="fa fa-history"></i> Linimasa Penolakan Surat
                    </h4>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <ul class="timeline" id="timelineContent">
                        <!-- Timeline content will be populated by JavaScript -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                ajax: {
                    url: "{{ ci_route('keluar.datatables') }}?state={{ $state }}",
                    data: function(req) {
                        req.tahun = $('select[name=tahun]').val()
                        req.bulan = $('select[name=bulan]').val()
                        req.jenis = $('select[name=jenis]').val()
                    }
                },
                columns: [{
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
                        data: 'kode_surat',
                        name: 'kode_surat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'id_format_surat',
                        name: 'id_format_surat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'penduduk.nama',
                        name: 'penduduk.nama',
                        render: function(data, type, row, meta) {
                            return data ?? row.penduduk_non_warga
                        },
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pemohon',
                        name: 'pemohon',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'nama_pamong',
                        name: 'nama_pamong',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'id_user',
                        name: 'id_user',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'status_label',
                        name: 'status_label',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tolak',
                        name: 'tolak.keterangan',
                        render: function(data, type, row, meta) {
                            if (data && Array.isArray(data) && data.length > 0) {
                                const timelineData = JSON.stringify(data);
                                return `
                                    <button type="button" 
                                            class="btn btn-xs btn-info btn-timeline" 
                                            data-toggle="modal" 
                                            data-target="#modalTimeline" 
                                            data-timeline='${timelineData}'>
                                        <i class="fa fa-history"></i> Lihat Linimasa
                                    </button>
                                `;
                            }

                            return '';
                        },
                        searchable: false,
                        orderable: false,
                        defaultContent: '',
                        visible: {{ $state == 'tolak' ? 1 : 0 }}
                    },
                    {
                        data: 'nama_non_warga',
                        name: 'nama_non_warga',
                        searchable: true,
                        orderable: false,
                        visible: false
                    },

                ],
                order: [
                    [9, 'desc']
                ],
                pageLength: 25,
                createdRow: function(row, data, dataIndex) {
                    if (data.status == 0) {
                        $(row).addClass('select-row');
                    }
                },
                drawCallback: function(settings) {
                    var next = '{{ $next }}';
                    var pesan = `Apakah setuju surat ini di teruskan ke {{ $next }}?`;
                    var tte = "{{ setting('tte') }}"

                    $('button.kembalikan').click(function(e) {
                        e.preventDefault();
                        var id = $(e.target).closest('button').data('id')
                        var ulr_ajax = `{{ ci_route('keluar.kembalikan') }}`;
                        var redirect = `{{ ci_route('tolak') }}`;
                        var pesan = `Kembalikan surat ke pemohon untuk diperbaiki?`;
                        ditolak(id, ulr_ajax, redirect, pesan);
                    });

                    $('button.passphrase').click(function(e) {
                        e.preventDefault();
                        var id = $(e.target).closest('button').data('id');
                        Swal.fire({
                            customClass: {
                                popup: 'swal-lg',
                                input: 'swal-input-250'
                            },
                            title: 'TTE',
                            html: `
                                @if (empty($list_setting->firstWhere('key', 'tte_api')?->value) || $list_setting->firstWhere('key', 'tte_api')?->value == base_url())
                                    <div class="alert alert-warning alert-dismissible">
                                        <h4><i class="icon fa fa-warning"></i> Info Penting!</h4>
                                        Modul TTE ini hanya sebuah simulasi untuk persiapan penerapan TTE di {{ config_item('nama_aplikasi') }} dan Hanya berlaku untuk Surat yang Menggunakan TinyMCE
                                    </div>
                                @endif
                                <object data='{{ ci_route('keluar.unduh.tinymce') }}/${id}/true' style="width: 100%;min-height: 400px;" type="application/pdf"></object>
                                <input type="password" id="passphrase" autocomplete="off" class="swal2-input" placeholder="Masukkan Passphrase">
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Kirim',
                            showLoaderOnConfirm: true,
                            preConfirm: () => {
                                const passphrase = Swal.getPopup().querySelector('#passphrase').value

                                if (!passphrase) {
                                    Swal.showValidationMessage(`Mohon masukkan passphrase`)
                                }

                                const formData = new FormData();
                                formData.append('sidcsrf', getCsrfToken());
                                formData.append('id', id);
                                formData.append('passphrase', passphrase);

                                return fetch("{{ ci_route('external_api.tte.sign_visible') }}", {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        // other headers as needed
                                    },
                                    method: 'post',
                                    body: formData,
                                }).then(response => {
                                    if (response.ok) {
                                        return response.json();
                                    }

                                    if (!response.ok) {
                                        throw new Error(response.statusText)
                                    }
                                    // return response.json()
                                }).catch(error => {
                                    Swal.showValidationMessage(
                                        `Request failed: ${error}`
                                    )

                                })
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let response = result.value
                                if (response.status == false) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Request failed',
                                        text: response.pesan,
                                    })
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Dokumen berhasil tertanda tangani secara elektronik',
                                        showConfirmButton: true,
                                    }).then((result) => {
                                        window.location.replace("{{ ci_route('keluar.masuk') }}");
                                    })
                                }
                            }

                        })
                    });

                    $('a.kirim-kecamatan').click(function(e) {
                        e.preventDefault();
                        var id = $(e.target).closest('a').data('id')
                        Swal.fire({
                            title: 'Apakah Anda yakin ingin mengirim surat ini ke ' + '{{ ucwords(setting('sebutan_kecamatan')) }}' + ' ?',
                            showCancelButton: true,
                            confirmButtonText: 'Kirim',
                            showLoaderOnConfirm: true,
                            preConfirm: () => {

                                const formData = new FormData();
                                formData.append('sidcsrf', getCsrfToken());
                                formData.append('id', id);

                                return fetch("{{ ci_route('external_api.surat_kecamatan.kirim') }}", {
                                    method: 'post',
                                    body: formData,
                                }).then(response => {
                                    if (response.ok) {
                                        return response.json();
                                    }

                                    if (!response.ok) {
                                        throw new Error(response.statusText)
                                    }
                                }).catch(error => {
                                    Swal.showValidationMessage(
                                        `Request failed: ${error}`
                                    )

                                })
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let response = result.value
                                if (response.status == false) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Request failed',
                                        text: response.pesan,
                                    })
                                } else {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Dokumen berhasil dikirim ke ' + '{{ ucwords(setting('sebutan_kecamatan')) }}',
                                        showConfirmButton: true,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.replace("{{ ci_route('keluar') }}");
                                        }
                                    });
                                }
                            }

                        })
                    });

                    // Handle timeline modal
                    $('button.btn-timeline').click(function(e) {
                        e.preventDefault();
                        var timelineData = $(this).data('timeline');
                        var timelineContent = $('#timelineContent');

                        // Clear existing content
                        timelineContent.empty();

                        if (timelineData && timelineData.length > 0) {
                            // Group timeline by date
                            var groupedData = {};
                            $.each(timelineData, function(index, item) {
                                var dateString = item.created_at || item.tanggal_tolak;
                                var dateFormat = 'DD MMM YYYY';
                                var dateKey = dateString && moment(dateString).isValid() ? moment(dateString).format(dateFormat) : 'Tanggal tidak diketahui';
                                if (!groupedData[dateKey]) {
                                    groupedData[dateKey] = [];
                                }
                                groupedData[dateKey].push(item);
                            });

                            // Create timeline items using AdminLTE2 structure
                            $.each(groupedData, function(dateKey, items) {
                                // Add timeline time label
                                var timeLabel = `
                                    <li class="time-label">
                                        <span class="bg-red">${dateKey}</span>
                                    </li>
                                `;
                                timelineContent.append(timeLabel);

                                // Add timeline items for this date
                                $.each(items, function(index, item) {
                                    var dateString = item.created_at || item.tanggal_tolak;
                                    var timeOnly = dateString && moment(dateString).isValid() ? moment(dateString).format('HH:mm') : '--:--';
                                    var keterangan = item.keterangan || 'Tidak ada keterangan';
                                    var penolak = item.user.nama || 'System';
                                    var fullDate = dateString && moment(dateString).isValid() ? moment(dateString).format('DD MMMM YYYY HH:mm') : 'Tanggal tidak tersedia';

                                    var timelineItem = `
                                        <li>
                                            <i class="fa fa-times bg-red"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fa fa-clock-o"></i> ${timeOnly}</span>
                                                <h3 class="timeline-header">
                                                    <strong>
                                                        <i class="fa fa-user"></i> ${penolak}
                                                    </strong> menolak permohonan surat
                                                </h3>
                                                <div class="timeline-body">
                                                    <div class="well">
                                                        <h4>Alasan Penolakan:</h4>
                                                        <p>${keterangan}</p>
                                                    </div>
                                                </div>
                                                <div class="timeline-footer">
                                                    <small class="text-muted">
                                                        <i class="fa fa-calendar"></i> Ditolak pada ${fullDate}
                                                    </small>
                                                </div>
                                            </div>
                                        </li>
                                    `;

                                    timelineContent.append(timelineItem);
                                });
                            });
                            // Add end marker
                            timelineContent.append(`<li><i class="fa fa-clock-o bg-gray"></i></li>`);
                        } else {
                            var emptyState = `
                                <li>
                                    <div class="alert alert-info text-center">
                                        <i class="fa fa-info-circle"></i> Tidak ada data timeline penolakan
                                    </div>
                                </li>
                            `;
                            timelineContent.html(emptyState);
                        }
                    });
                }
            });

            $('select.filter-table[name=tahun]').change(function() {
                TableData.draw()
                // update list bulan
                $('select.filter-table[name=bulan]').find('option:gt(0)').remove()
                if ($(this).val() != '') {
                    $.get('{{ ci_route('keluar.bulanTahun') }}/' + $(this).val(), {}, function(data) {
                        for (var i in data.bulan) {
                            $('select.filter-table[name=bulan]').append('<option value="' + data.bulan[i]['bulan'] + '">' + data.bulan[i]['name'] + '</option>')
                        }
                    }, 'json')
                }

            })
            $('select.filter-table[name=bulan]').change(function() {
                TableData.draw()
            })
            $('select.filter-table[name=jenis]').change(function() {
                TableData.draw()
            })



            $('button#perbaiki').click(function(e) {
                swal.fire({
                    title: 'Perbaiki Arsip Surat',
                    text: 'Surat yang ada sekarang, akan diverifikasi semua. Ingin Melanjutkan?',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    icon: 'warning',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ ci_route('keluar.perbaiki') }}";
                    }
                })
            });

        });

        function lockSurat(id) {
            swal.fire({
                title: 'Kunci Surat',
                text: 'Surat yang dikunci tidak akan bisa diubah kembali. Ingin Melanjutkan?',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                icon: 'warning',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ ci_route('keluar.lock_surat') }}/" + id;
                }
            })
        }

        function setKeluar(id) {
            swal.fire({
                title: 'Surat Keluar',
                text: 'Surat yang telah ditetapkan keluar tidak dapat diubah kembali.. Ingin Melanjutkan?',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                icon: 'warning',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ ci_route('keluar.set_keluar') }}/" + id;
                }
            })
        }
    </script>
@endpush
