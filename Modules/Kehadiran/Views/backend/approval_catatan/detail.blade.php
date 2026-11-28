@extends('admin.layouts.index')
@include('admin.layouts.components.asset_form_request')

@section('title')
    <h1>
        Review Catatan Harian Kerja
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('kehadiran_approval_catatan/index') }}">Persetujuan Catatan</a></li>
    <li class="active">Review</li>
@endsection

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('kehadiran_catatan_harian'), 'label' => 'Daftar Catatan Harian'])
        </div>

        <div class="box-body" style="padding-bottom: 0;">
            <div class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Tanggal</label>
                    <div class="col-sm-4">
                        <p class="form-control-static">{{ $catatan->hari . ', ' . tgl_indo($catatan->tanggal) }}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Nama Perangkat</label>
                    <div class="col-sm-6">
                        <p class="form-control-static">{{ $catatan->pamong?->pamong_nama ?? '-' }}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Jabatan</label>
                    <div class="col-sm-6">
                        <p class="form-control-static">{{ $catatan->pamong?->jabatan?->nama ?? '-' }}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Uraian Kegiatan</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">{{ nl2br(e($catatan->uraian_kegiatan)) }}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Lokasi Kegiatan</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">{{ $catatan->lokasi_kegiatan }}</p>
                    </div>
                </div>

                @if($catatan->hasil_diharapkan)
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Hasil yang Diharapkan</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">{{ nl2br(e($catatan->hasil_diharapkan)) }}</p>
                        </div>
                    </div>
                @endif

                @if($catatan->fotos->isNotEmpty())
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Foto Kegiatan</label>
                        <div class="col-sm-9">
                            <div class="row">
                                @foreach($catatan->fotos as $foto)
                                    <div class="col-sm-4 margin-bottom">
                                        <div class="box box-solid">
                                            <div class="box-body" style="padding: 0;">
                                                <a href="{{ base_url($foto->file_path) }}" target="_blank" class="thumbnail">
                                                    <img src="{{ base_url($foto->file_path) }}" class="img-responsive" style="max-width: 100%; height: auto;">
                                                </a>
                                            </div>
                                            @if($foto->keterangan)
                                                <div class="box-footer" style="font-size: 12px; padding: 5px;">
                                                    {{ $foto->keterangan }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="box-footer">
            <div class="row">
                <div class="col-sm-6">
                    @if (can('u'))
                        <button type="button" class="btn btn-danger btn-social btn-sm" id="btn-reject" onclick="showRejectSweetAlert()">
                            <i class="fa fa-times"></i> Tolak
                        </button>
                    @endif
                </div>
                <div class="col-sm-6 text-right">
                    @if (can('u'))
                        <button type="button" class="btn btn-success btn-social btn-sm" id="btn-approve" onclick="confirmApprove()">
                            <i class="fa fa-check"></i> Setujui
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmApprove() {
            Swal.fire({
                title: 'Setujui Catatan?',
                text: 'Apakah Anda yakin ingin menyetujui catatan harian ini?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Setujui',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitApproveForm();
                }
            });
        }

        function submitApproveForm() {
            const formData = new FormData();
            formData.append('{{ $token_name }}', '{{ $token_value }}');

            fetch(SITE_URL + '/kehadiran_approval_catatan/approve/{{ $catatan->uuid }}', {
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
                        window.location.href = data.redirect_url;
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
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

        function showRejectSweetAlert() {
            Swal.fire({
                title: 'Tolak Catatan?',
                text: 'Masukkan alasan penolakan:',
                icon: 'warning',
                input: 'textarea',
                inputPlaceholder: 'Jelaskan alasan penolakan...',
                inputAttributes: {
                    'minlength': 5,
                    'maxlength': 500,
                    'class': 'form-control'
                },
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Tolak',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Alasan penolakan tidak boleh kosong!';
                    }
                    if (value.trim().length < 5) {
                        return 'Alasan penolakan minimal 5 karakter!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const alasanPenolakan = result.value;
                    submitRejectForm(alasanPenolakan);
                }
            });
        }

        function submitRejectForm(alasanPenolakan) {
            const formData = new FormData();
            formData.append('alasan_penolakan', alasanPenolakan);
            formData.append('{{ $token_name }}', '{{ $token_value }}');

            fetch(SITE_URL + '/kehadiran_approval_catatan/reject/{{ $catatan->uuid }}', {
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
                        window.location.href = data.redirect_url;
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
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
    </script>
@endsection
