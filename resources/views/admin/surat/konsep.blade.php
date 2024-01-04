@include('admin.pengaturan_surat.asset_tinymce')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Konsep Surat {{ ucwords($surat->nama) }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('surat') }}">Daftar Cetak Surat</a></li>
    <li class="active"> Surat {{ ucwords($surat->nama) }}</li>
    <li class="active"> Konsep Surat {{ ucwords($surat->nama) }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        {!! form_open(null, 'id="validasi"') !!}
        <div class="box-body">
            <input type="hidden" id="id_surat" value="{{ $id_surat }}">
            <div class="form-group">
                <textarea name="isi_surat" data-filemanager='<?= json_encode(['external_filemanager_path'=> base_url('assets/kelola_file/'), 'filemanager_title' => 'Responsive Filemanager', 'filemanager_access_key' => $session->fm_key]) ?>' data-salintemplate="isi" class="form-control input-sm editor required">{{ $isi_surat }}</textarea>
            </div>
        </div>
        <div class="box-footer text-center">
            <a href="{{ ci_route('surat') }}" id="back" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Surat
            </a>
            <button type="button" id="preview-pdf" class="btn btn-social btn-vk btn-success btn-sm"><i class="fa fa-eye"></i>Tinjau PDF</button>
            <button type="button" id="cetak-pdf" class="btn btn-social btn-success btn-sm"><i class="fa fa-file-pdf-o"></i>Cetak PDF</button>
            @if ($tolak != '-1')
                <a onclick="formAction('validasi', '{{ $aksi_konsep }}')" id="konsep" class="btn btn-social btn-warning btn-sm"><i class="fa fa-file-code-o"></i>
                    Konsep</a>
                <a href="{{ ci_route('keluar/masuk') }}" id="next" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hide">
                    ke Permohonan Surat<i class="fa fa-arrow-circle-right"></i>
                @else
                    <a href="{{ ci_route('keluar/ditolak') }}" id="next" style="display:none" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        Ke Daftar Surat Ditolak <i class="fa fa-arrow-circle-right"></i>
            @endif

            </a>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('#cetak-pdf').click(function(e) {
                e.preventDefault();
                tinymce.triggerSave();
                Swal.fire({
                    title: 'Membuat Surat..',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: () => false
                })
                $.ajax({
                        url: `{{ $aksi_cetak }}`,
                        type: 'POST',
                        xhrFields: {
                            responseType: 'blob'
                        },
                        data: $("#validasi").serialize(),
                        success: function(response, status, xhr) {
                            // https://stackoverflow.com/questions/34586671/download-pdf-file-using-jquery-ajax
                            var filename = "";
                            var disposition = xhr.getResponseHeader('Content-Disposition');

                            if (disposition) {
                                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                var matches = filenameRegex.exec(disposition);
                                if (matches !== null && matches[1]) filename = matches[1].replace(
                                    /['"]/g, '');
                            }
                            var linkelem = document.createElement('a');
                            try {
                                var blob = new Blob([response], {
                                    type: 'application/octet-stream'
                                });
                                if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                    //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                                    window.navigator.msSaveBlob(blob, filename);
                                } else {
                                    var URL = window.URL || window.webkitURL;
                                    var downloadUrl = URL.createObjectURL(blob);

                                    if (filename) {
                                        // use HTML5 a[download] attribute to specify filename
                                        var a = document.createElement("a");

                                        // safari doesn't support this yet
                                        if (typeof a.download === 'undefined') {
                                            window.location = downloadUrl;
                                        } else {
                                            a.href = downloadUrl;
                                            a.download = filename;
                                            document.body.appendChild(a);
                                            a.target = "_blank";
                                            a.click();
                                        }
                                    } else {
                                        window.location = downloadUrl;
                                    }
                                }
                            } catch (ex) {
                                alert(ex); // This is an error
                            }
                        }
                    })
                    .done(function(response, textStatus, xhr) {
                        if (xhr.status == 200) {
                            $('#cetak-pdf').hide();
                            $('#draft-pdf').hide();
                            $('#preview-pdf').hide();
                            $('#konsep').hide();
                            $('#back').remove();
                            $('#next').show();
                            $('#next').removeClass('hide');
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Surat Selesai Dibuat',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    })
                    .fail(function(response, status, xhr) {

                        Swal.fire({
                            title: xhr.statusText,
                            icon: 'error',
                            text: response.statusText,
                        })
                    });
            });

            $('#preview-pdf').click(function(e) {
                e.preventDefault();
                tinymce.triggerSave();

                Swal.fire({
                    title: 'Membuat pratinjau..',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: () => false
                });

                $.ajax({
                    url: `{{ $aksi_cetak . '/true' }}`,
                    type: 'POST',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    data: $("#validasi").serialize(),
                    success: function(response, status, xhr) {
                        // https://stackoverflow.com/questions/34586671/download-pdf-file-using-jquery-ajax
                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');

                        if (disposition) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches !== null && matches[1]) filename = matches[1].replace(
                                /['"]/g, '');
                        }
                        try {
                            var blob = new Blob([response], {
                                type: 'application/pdf'
                            });
                            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                                window.navigator.msSaveBlob(blob, filename);
                            } else {
                                var URL = window.URL || window.webkitURL;
                                var downloadUrl = URL.createObjectURL(blob);
                                Swal.fire({
                                    customClass: {
                                        popup: 'swal-lg'
                                    },
                                    title: 'Pratinjau',
                                    html: `
                                        <object data="${downloadUrl}" style="width: 100%;min-height: 400px;" type="application/pdf"></object>
                                    `,
                                    showCancelButton: true,
                                    showConfirmButton: false,
                                    cancelButtonText: 'Tutup',
                                    allowOutsideClick: () => false
                                })
                            }
                        } catch (ex) {
                            alert(ex); // This is an error
                        }
                    }
                }).fail(function(response, status, xhr) {

                    Swal.fire({
                        title: xhr.statusText,
                        icon: 'error',
                        text: response.statusText,
                    })
                })
            });
        });
    </script>
@endpush
