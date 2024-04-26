                                        <div class="tab-pane {{ $act_tab == 2 ? 'active' : '' }}">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="box-header with-border">
                                                        <h3 class="box-title"><strong>Migrasi Database Ke
                                                                {{ config_item('nama_aplikasi') }}
                                                                {{ AmbilVersi() }}</strong></h3>
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <form action="{{ $form_action }}" method="post" enctype="multipart/form-data" id="excell" class="form-horizontal">
                                                                    <p>Proses ini untuk mengubah database SID ke
                                                                        struktur database
                                                                        {{ config_item('nama_aplikasi') }}
                                                                        {{ AmbilVersi() }}.</p>
                                                                    <p class="text-muted text-red well well-sm no-shadow" style="margin-top: 10px;">
                                                                        <small>
                                                                            <strong><i class="fa fa-info-circle text-red"></i>
                                                                                Sebelum melakukan migrasi ini, pastikan
                                                                                database SID anda telah
                                                                                dibackup.</strong>
                                                                        </small>
                                                                    </p>
                                                                    <p>Apabila sesudah melakukan konversi ini, masih
                                                                        ditemukan masalah, laporkan di :</P>
                                                                    <ul>
                                                                        <li> <a href="https://github.com/OpenSID/OpenSID/issues">https://github.com/OpenSID/OpenSID/issues</a>
                                                                        </li>
                                                                        <li> <a href="{{ config_item('fb_opendesa') }}">{{ config_item('fb_opendesa') }}</a>
                                                                        </li>
                                                                    </ul>
                                                                    <table class="table table-bordered">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td style="padding-top:20px;padding-bottom:10px;">
                                                                                    <div class="form-group">
                                                                                        <div class="col-sm-5 col-md-4">
                                                                                            <div class="btn-group">
                                                                                                <button type="button" class="btn btn-social btn-info btn-danger ajax" style="border-radius: 3px 0 0 3px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-database"></i>
                                                                                                    Migrasi Database
                                                                                                </button>
                                                                                                <button type="button" class="btn dropdown-toggle btn-danger" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                                                    <span class="caret"></span>
                                                                                                    <span class="sr-only">Toggle
                                                                                                        Dropdown</span>
                                                                                                </button>
                                                                                                <ul class="dropdown-menu">
                                                                                                    <li><a class="migrasi" data-migrasi="new" href="#">Migrasi
                                                                                                            Terbaru</a>
                                                                                                    </li>
                                                                                                    <li><a class="migrasi" data-migrasi="all" href="#">Semua
                                                                                                            Migrasi</a>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="ajax-content"></div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @push('css')
                                            <link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">
                                        @endpush
                                        @push('scripts')
                                            <script src="{{ asset('js/sweetalert2/sweetalert2.all.min.js') }}"></script>
                                            <script src="{{ asset('js/backup.min.js') }}"></script>
                                            <script>
                                                $(function() {
                                                    $('a.migrasi').click(function(e) {
                                                        const _mode = $(this).data('migrasi')
                                                        const _error = []
                                                        e.preventDefault();
                                                        Swal.fire({
                                                            title: 'Apakah anda sudah melakukan backup database ?',
                                                            showDenyButton: true,
                                                            confirmButtonText: 'Sudah',
                                                            denyButtonText: `Belum`,
                                                        }).then((result) => {
                                                            /* Read more about isConfirmed, isDenied below */
                                                            if (result.isConfirmed) {
                                                                let f = new FormData
                                                                let _redirect = false
                                                                f.append("sidcsrf", getCsrfToken())

                                                                Swal.fire({
                                                                    title: "Proses migrasi database, mohon ditunggu ",
                                                                    html: "Progress : <b></b>",
                                                                    timerProgressBar: !0,
                                                                    didOpen() {
                                                                        Swal.showLoading();
                                                                        let lastResponseLength = false
                                                                        let a = Swal.getHtmlContainer().querySelector("b");
                                                                        $.ajax({
                                                                            url: '{{ $form_action }}?mode=' + _mode,
                                                                            type: "POST",
                                                                            data: f,
                                                                            dataType: 'json',
                                                                            processData: !1,
                                                                            contentType: !1,
                                                                            xhrFields: {
                                                                                // Getting on progress streaming response
                                                                                onprogress: function(e) {
                                                                                    var result, tmpJson;
                                                                                    var response = e.currentTarget
                                                                                        .response;
                                                                                    var parsedResponse;
                                                                                    let lastPosition = 0;
                                                                                    if (lastResponseLength === false) {
                                                                                        result = response;
                                                                                        lastResponseLength = response
                                                                                            .length;
                                                                                    } else {
                                                                                        result = response.substring(
                                                                                            lastResponseLength);
                                                                                        lastResponseLength = response
                                                                                            .length;
                                                                                    }

                                                                                    try {
                                                                                        lastPosition = result
                                                                                            .lastIndexOf('{');
                                                                                        tmpJson = $.trim(result
                                                                                            .substring(lastPosition)
                                                                                        );

                                                                                        parsedResponse = JSON.parse(
                                                                                            tmpJson);

                                                                                        a.textContent = parsedResponse[
                                                                                            'message']
                                                                                        if (parsedResponse['status'] !==
                                                                                            undefined) {
                                                                                            Swal.hideLoading()
                                                                                            Swal.disableButtons();
                                                                                            // error code set 500
                                                                                            if (parsedResponse[
                                                                                                    'status'] == 500) {
                                                                                                _error.push(a.textContent)
                                                                                            }
                                                                                            if (parsedResponse[
                                                                                                    'status'] == 1) {
                                                                                                if (_error.length) {
                                                                                                    Swal.fire({
                                                                                                        title: "Pesan kesalahan migrasi ",
                                                                                                        html: _error.join('<br>'),
                                                                                                        timerProgressBar: !0,
                                                                                                    })
                                                                                                }
                                                                                                _redirect = true
                                                                                                Swal.enableButtons()
                                                                                            }
                                                                                        }
                                                                                    } catch (error) {
                                                                                        console.err(error)
                                                                                    }
                                                                                }
                                                                            },
                                                                            success: function(e) {
                                                                                Swal.hideLoading()
                                                                            },
                                                                            error: function(x, status, error) {
                                                                                // console.log(error)
                                                                            }
                                                                        })
                                                                    }
                                                                }).then((result) => {
                                                                    if (result && _redirect) {
                                                                        window.location.reload()
                                                                    }
                                                                });
                                                            }
                                                        })
                                                    })
                                                })
                                            </script>
                                        @endpush
