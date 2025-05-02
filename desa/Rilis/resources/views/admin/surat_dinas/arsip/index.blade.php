@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.asset_datatables')

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
    @includeWhen($widgets, 'admin.surat_dinas.arsip.widgets')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                @if ($tab_ini == 10)
                    <div class="box-header with-border">
                        <a href="{{ ci_route('surat_dinas_arsip.graph') }}" class="btn btn-social bg-orange btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-pie-chart"></i> Pie Surat Keluar</a>
                        <a href="{{ ci_route('surat_dinas_arsip.dialog_cetak/cetak') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox"
                            data-title="Cetak Arsip Surat Dinas"
                        ><i class="fa fa-print"></i> Cetak</a>
                        <a href="{{ ci_route('surat_dinas_arsip.dialog_cetak/unduh') }}" class="btn btn-social bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox"
                            data-title="Unduh Arsip Surat Dinas"
                        ><i class="fa fa-download"></i> Unduh</a>
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
                                                    <button id="perbaiki" type="button" title="Semua surat yang berstatus proses atau tidak ada statusnya akan di ubah menjadi siap cetak"
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                ajax: {
                    url: "{{ ci_route('surat_dinas_arsip.datatables') }}?state={{ $state }}",
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
                        data: 'alasan',
                        name: 'alasan',
                        searchable: false,
                        orderable: false,
                        defaultContent: '',
                        visible: {{ $state == 'tolak' ? 1 : 0 }}
                    },

                ],
                order: [
                    [7, 'desc']
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
                        var ulr_ajax = `{{ ci_route('surat_dinas_arsip.kembalikan') }}`;
                        var redirect = `{{ ci_route('surat_dinas_arsip.tolak') }}`;
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
                    @if (empty(setting('tte_api')) || setting('tte_api') == base_url())
                        <div class="alert alert-warning alert-dismissible">
                            <h4><i class="icon fa fa-warning"></i> Info Penting!</h4>
                            Modul TTE ini hanya sebuah simulasi untuk persiapan penerapan TTE di {{ config_item('nama_aplikasi') }} dan Hanya berlaku untuk Surat yang Menggunakan TinyMCE
                        </div>
                    @endif
                    <object data='{{ ci_route('surat_dinas_arsip.unduh.tinymce') }}/${id}/true' style="width: 100%;min-height: 400px;" type="application/pdf"></object>
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
                                formData.append('tipe', 'surat_dinas');
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
                                        window.location.replace("{{ ci_route('surat_dinas_arsip.masuk') }}");
                                    })
                                }
                            }

                        })
                    });

                    $('a.kirim-kecamatan').click(function(e) {
                        e.preventDefault();
                        var id = $(e.target).closest('a').data('id')
                        Swal.fire({
                            title: 'Apakah anda yakin ingin mengirim surat ini ke ' + '{{ setting('sebutan_kecamatan') }}' + ' ?',
                            showCancelButton: true,
                            confirmButtonText: 'Kirim',
                            showLoaderOnConfirm: true,
                            preConfirm: () => {

                                const formData = new FormData();
                                formData.append('sidcsrf', getCsrfToken());
                                formData.append('id', id);

                                return fetch("{{ ci_route('api.surat_kecamatan.kirim') }}", {
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
                                        title: 'Dokumen berhasil dikirim ke kecamatan',
                                        showConfirmButton: true,
                                    }).then((result) => {
                                        window.location.replace("{{ ci_route('surat_dinas_arsip') }}");
                                    })
                                }
                            }

                        })
                    });
                }
            });

            $('select.filter-table[name=tahun]').change(function() {
                TableData.draw()
                // update list bulan
                $('select.filter-table[name=bulan]').find('option:gt(0)').remove()
                if ($(this).val() != '') {
                    $.get('{{ ci_route('surat_dinas_arsip.bulanTahun') }}/' + $(this).val(), {}, function(data) {
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
                        window.location.href = "{{ ci_route('surat_dinas_arsip.perbaiki') }}";
                    }
                })
            });

        });
    </script>
@endpush
