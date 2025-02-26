@extends('admin.layouts.index')

@section('title')
    <h1>SINKRONISASI</h1>
@endsection

@section('breadcrumb')
    <li class="active">Sinkronisasi</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#opendk" data-toggle="tab"><b>OPENDK</b></a></li>
            <li><a href="#tab_buat_key" data-toggle="tab"><b>BUAT KEY</b></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="opendk">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                        <thead class="bg-gray disabled color-palette">
                            <tr>
                                <th>No.</th>
                                <th>Kirim Data {{ config_item('nama_aplikasi') }} Ke OpenDK</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kirim_data as $key => $data)
                                <tr>
                                    <td class="padat">{{ $key + 1 }}</td>
                                    <td>{{ $data }}</td>
                                    <td class="aksi">
                                        @php $slug = url_title($data, 'dash', true); @endphp
                                        @if (in_array($slug, ['penduduk', 'identitas-desa', 'program-bantuan', 'pembangunan']))
                                            @if ($list_setting->firstWhere('key', 'api_opendk_key')?->value)
                                                <a href="#" data-href="{{ site_url('sinkronisasi/kirim/') . $slug }}" class="btn btn-social btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block kirim_data" title="Kirim Data"
                                                    data-modul='{{ isset($modul[$data]) ? json_encode($modul[$data], JSON_THROW_ON_ERROR) : '' }}' data-body="Apakah yakin mengirim data {{ $data }} ke OpenDK?"
                                                ><i class="fa fa-random"></i> Kirim Data</a>
                                            @else
                                                <a href="#" title="API Key Belum Ditentukan" class="btn btn-social btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" disabled><i class="fa fa-random"></i> Kirim Data</a>
                                            @endif
                                        @else
                                            <a href="{{ site_url('sinkronisasi/kirim/') . $slug }}" class="btn btn-social btn-warning btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Buka Modul"><i class="fa fa-link"></i> Buka Modul</a>
                                        @endif
                                        @if (in_array($slug, ['penduduk', 'program-bantuan']))
                                            <a href="{{ site_url('sinkronisasi/unduh/') . $slug }}" title="Unduh Data" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-download"></i> Unduh Data</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="3">Data Tidak Tersedia</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="tab_buat_key">
                <form id="validasi" class="form-horizontal" action="{{ site_url('setting/update') }}" method="POST">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-12 col-md-3" for="nama">Sinkronisasi Server OpenDK</label>
                            <div class="col-sm-12 col-md-4">
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons" style="padding: 0px;">
                                    <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ jecho(setting('sinkronisasi_opendk'), 1, 'active') }}">
                                        <input type="radio" name="sinkronisasi_opendk" class="form-check-input" value="1" autocomplete="off" {{ jecho(setting('sinkronisasi_opendk'), 1, 'checked') }}>Ya</label>
                                    <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ jecho(setting('sinkronisasi_opendk'), 0, 'active') }}">
                                        <input type="radio" name="sinkronisasi_opendk" class="form-check-input" value="0" autocomplete="off" {{ jecho(setting('sinkronisasi_opendk'), 0, 'checked') }}>Tidak
                                    </label>
                                </div>
                            </div>
                            <label class="col-sm-12 col-md-5 pull-left" for="nama">Aktifkan Sinkronisasi Server OpenDK</code></label>
                        </div>
                        <div id="modul-sinkronisasi">
                            <div class="form-group">
                                <label class="col-sm-12 col-md-3" for="nama">Api Opendk Server</label>
                                <div class="col-sm-12 col-md-4">
                                    <input id="api_opendk_server" name="api_opendk_server" class="form-control input-sm" type="text" value="{{ $list_setting->firstWhere('key', 'api_opendk_server')?->value }}" />
                                </div>
                                <label class="col-sm-12 col-md-5 pull-left" for="nama">Alamat Server OpenDK <code>(contoh: https://demodk.opendesa.id)</code></label>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-12 col-md-3" for="nama">Api Opendk Key</label>
                                <div class="col-sm-12 col-md-4">
                                    <textarea rows="5" id="api_opendk_key" name="api_opendk_key" class="form-control input-sm" placeholder="Silakan Masukkan API Key OpenDK">{{ $list_setting->firstWhere('key', 'api_opendk_key')?->value }}</textarea>
                                </div>
                                <label class="col-sm-12 col-md-5 pull-left" for="nama">OpenDK API Key untuk Sinkronisasi Data</label>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                            Batal</button>
                        <button type="submit" class="btn btn-social btn-info btn-sm pull-right simpan"><i class="fa fa-check"></i>
                            Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id='loading' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header btn-warning">
                    <h4 class="modal-title">Proses Sinkronisasi</h4>
                </div>
                <div class="modal-body">
                    Harap tunggu sampai proses sinkronisasi selesai. Proses ini bisa memakan waktu beberapa menit tergantung data yang dikirimkan.
                    <div class='text-center'>
                        <img src="{{ asset('images/loading.gif') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (session('notif'))
        <div class="modal fade" id="response" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Response</h4>
                    </div>

                    <div class="modal-body btn-{{ session('notif')['status'] }}">
                        {!! session('notif')['pesan'] !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@include('admin.layouts.components.konfirmasi_hapus')
@include('admin.layouts.components.sinkronisasi_notif')

<script src="{{ asset('js/axios.min.js') }}"></script>

@if (cek_koneksi_internet())
    @push('scripts')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush
@endif

@push('scripts')
    <script>
        $(document).ready(function() {
            $('input[name="sinkronisasi_opendk"]').on('change', function(e) {
                console.log($('input[name="sinkronisasi_opendk"]').filter(':checked').val());
                if ($('input[name="sinkronisasi_opendk"]').filter(':checked').val() == 1) {
                    $('input[name="api_opendk_server"]').prop("required", true);
                    $('textarea[name="api_opendk_key"]').prop("required", true);
                    $('#modul-sinkronisasi').show();
                } else {
                    $('input[name="api_opendk_server"]').prop("required", false);
                    $('textarea[name="api_opendk_key"]').prop("required", false);
                    $('#modul-sinkronisasi').hide();
                }
            });

            $('input[name="sinkronisasi_opendk"]:first').trigger('change');

            $('#response').modal({
                backdrop: 'static',
                keyboard: false
            }).show();

            $('.kirim_data').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: $(this).data('body'),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Kirim',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if ($(this).data('modul') == '') {
                            $('#loading').modal({
                                backdrop: 'static',
                                keyboard: false
                            }).show();
                            window.location.replace($(this).data('href'));
                        } else {
                            // kirim ke opendk menggunakan async
                            if (!supportsES6) {
                                alert('Browser tidak support. Harap gunakan versi broswer terbaru')
                            }
                            kirim_opendk($(this).data('modul'))
                        }
                    }
                })

            });
        });

        kirim_opendk = async (modul) => {
            $('#sinkronisasi').modal({
                backdrop: 'static',
                keyboard: false
            }).show();
            // $('#status .modal-content')
            for (var i = 0; i < modul.length; i++) {

                var val = modul[i];
                // cek pagination
                let page = await $.ajax({
                        'url': "{{ site_url($controller . '/total') }}",
                        data: {
                            'modul': val.modul,
                            'model': val.model
                        },
                        type: 'Post',
                    })
                    .fail(function(err) {
                        alert(error);
                        return 0;
                    })



                var status = new Array();
                var akhir = false;
                for (var j = 0; j < page; j++) {
                    akhir = (j + 1 == page) ? true : false;
                    status = await $.ajax({
                        url: "{{ site_url($controller) }}" + `/${val.path}`,
                        data: {
                            p: j,
                            akhir: akhir
                        },
                    })
                    // tampilkan bar success
                    $('#sinkronisasi .message').html(`
                    Harap tunggu sampai proses sinkronisasi selesai. Proses ini bisa memakan waktu beberapa menit tergantung data yang dikirimkan.
                    <p><strong>Jalankan Sinkronisasi ${val.modul}</strong></p>
                    <div class="progress">
                        <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="${((j+1)/modul.length)*100}" aria-valuemin="0" aria-valuemax="100" style="width: ${((j+1)/page)*100}%">
                            <span class="sr-only">${((j+1)/page)*100}% Complete (success)</span>
                        </div>
                    </div>
                `);

                    if (status == 'danger') {
                        $('#sinkronisasi').modal('hide');
                        $('#status').modal().show();

                        var title_msg = status.pesan.message;
                        var invalid_data = status.pesan.errors;
                        var error_msg = `<h4>${title_msg}</h4>`;

                        if (invalid_data.length > 0) {
                            error_msg += `<ul>`;
                            for (var key in invalid_data) {
                                if (test.errors.hasOwnProperty(key)) {
                                    var errorMessages = status.pesan.errors[key];
                                    for (var i = 0; i < errorMessages.length; i++) {
                                        error_msg += '<li>' + errorMessages[i] + '</li>';
                                    }
                                }
                            }
                            error_msg += `</ul>`;
                        }

                        $('#status .modal-content').html(`
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Response</h4>
                        </div>
                        <div class="modal-body btn-${status.status}">
                                                    ${error_msg}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                        </div>
                    `);
                        return; // paksa loop berhenti
                    }

                }


            }

            // sinkronisasi success
            $('#sinkronisasi').modal('hide');
            $('#status').modal().show();
            $('#status .modal-content').html(`
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Response</h4>
            </div>
            <div class="modal-body btn-${status.status}">
                                        ${status.pesan}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
            </div>
        `);

        }

        kirim_opendk = async (modul) => {
            $('#sinkronisasi').modal({
                backdrop: 'static',
                keyboard: false
            }).show();
            // $('#status .modal-content')
            for (var i = 0; i < modul.length; i++) {
                var val = modul[i];
                // cek pagination
                let page = await $.ajax({
                        'url': "<?= site_url($controller . '/total') ?>",
                        data: {
                            'modul': val.modul,
                            'model': val.model,
                            'inkremental': val.inkremental
                        },
                        type: 'Post',
                    })
                    .fail(function(err) {
                        alert(error);
                        return 0;
                    })



                var status = new Array();
                var akhir = false;
                for (var j = 0; j < page; j++) {
                    akhir = (j + 1 == page) ? true : false;
                    status = await $.ajax({
                        url: "<?= site_url($controller) ?>" + `/${val.path}`,
                        data: {
                            p: j,
                            akhir: akhir
                        },
                    })
                    // tampilkan bar success
                    $('#sinkronisasi .message').html(`
                    Harap tunggu sampai proses sinkronisasi selesai. Proses ini bisa memakan waktu beberapa menit tergantung data yang dikirimkan.
                    <p><strong>Jalankan Sinkronisasi ${val.modul}</strong></p>
                    <div class="progress">
                      <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="${((j+1)/modul.length)*100}" aria-valuemin="0" aria-valuemax="100" style="width: ${((j+1)/page)*100}%">
                        <span class="sr-only">${((j+1)/page)*100}% Complete (success)</span>
                      </div>
                    </div>
                `);
                    if (status == 'danger') {
                        $('#sinkronisasi').modal('hide');
                        $('#status').modal().show();

                        var title_msg = status.pesan.message;
                        var invalid_data = status.pesan.errors;
                        var error_msg = `<h4>${title_msg}</h4>`;

                        if (invalid_data.length > 0) {
                            error_msg += `<ul>`;
                            for (var key in invalid_data) {
                                if (test.errors.hasOwnProperty(key)) {
                                    var errorMessages = status.pesan.errors[key];
                                    for (var i = 0; i < errorMessages.length; i++) {
                                        error_msg += '<li>' + errorMessages[i] + '</li>';
                                    }
                                }
                            }
                            error_msg += `</ul>`;
                        }

                        $('#status .modal-content').html(`
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Response</h4>
                        </div>
                        <div class="modal-body btn-${status.status}">
                                                    ${error_msg}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                        </div>
                    `);

                        return; // paksa loop berhenti
                    }

                }


            }

            // sinkronisasi success
            $('#sinkronisasi').modal('hide');
            $('#status').modal().show();
            $('#status .modal-content').html(`
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Response</h4>
            </div>
            <div class="modal-body btn-${status.status}">
                                        ${status.pesan}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
            </div>
        `);

        }

        $('#ok-delete').on('click', function() {
            $('#confirm-status').hide();
            $('#loading').modal({
                backdrop: 'static',
                keyboard: false
            }).show();
        });
    </script>
@endpush
