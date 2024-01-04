@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>Optimasi Gambar</h1>
@endsection

@section('breadcrumb')
    <li class="active">Optimasi Gambar</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="row">
            <div class="col-md-12">
                <div class="box-header with-border">
                    <h3 class="box-title"><strong>Ubah Ukuran Gambar</strong></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="col-sm-10">Folder desa/upload/</td>
                                        <td class="col-sm-2">
                                            @if (can('u'))
                                                <button type="button" class="btn btn-social btn-flat btn-block btn-info btn-sm resize-all" title="Resize Gambar"><i class="fa fa-picture-o"></i> Resize Gambar
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered">
                                <tbody>
                                    @foreach ($folders as $dir)
                                        <tr>
                                            <td class="col-sm-10">Folder desa/upload/<b>{{ $dir }}</b></td>
                                            <td class="col-sm-2">
                                                @if (can('u'))
                                                    <button data-dir="{{ $dir }}" type="button" class="btn btn-social btn-flat btn-block btn-info btn-sm resize" title="Resize Gambar"><i class="fa fa-picture-o"></i> Resize Gambar
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <p>Proses Resize hanya merubah ukuran gambar yang melebihi ukuran lebar 880px dan tinggi 880px.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('.resize').click(function(event) {
                Swal.fire({
                    title: 'Ubah ukuran gambar di folder ' + $(this).data('dir'),
                    showCancelButton: true,
                    confirmButtonText: 'Ok',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        periksa($(this).data('dir'));

                    }
                })
            });

            $('.resize-all').click(function(event) {
                Swal.fire({
                    title: 'Ubah ukuran gambar di folder upload',
                    showCancelButton: true,
                    confirmButtonText: 'Ok',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        periksa();
                    }
                })
            });

            periksa = function(dir = '') {
                Swal.fire({
                    title: 'Sedang Memeriksa',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                $.ajax({
                        url: "{{ ci_route('optimasi_gambar.get_image') }}/" + dir,
                        dataType: 'json',
                    })
                    .done(function(files) {
                        if (files.status == true) {
                            Swal.fire({
                                title: 'Sedang mengoptimasi gambar',
                                html: ` `,
                                didOpen: () => {
                                    Swal.showLoading();
                                    resize_gambar(files.data);
                                },
                            })
                        }
                    })
                    .fail(function(error) {
                        Swal.fire(err.responseText, '', 'warning')
                    });
            }
        });

        resize_gambar = async (files) => {
            var length = files.length;
            for (var i = 1; i < (length + 1); i++) {
                await $.ajax({
                        url: '{{ ci_route('optimasi_gambar.resize') }}',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            'file': files[i - 1]
                        },
                    })
                    .done(function() {
                        $('#swal2-html-container').html(
                            `<div class="progress-bar" role="progressbar" aria-valuenow="${(i/length)*100}" aria-valuemin="0" aria-valuemax="100" style="width: ${(i/length)*100}%;">${i}/${length}</div>`
                        );
                    })
                    .fail(function(error) {
                        Swal.fire(err.responseText, '', 'warning')
                        return;
                    });
            }
            Swal.fire('Optimasi gambar selesai', '', 'info')
        }
    </script>
@endpush
