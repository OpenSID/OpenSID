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
    @includeWhen($widgets, 'admin.surat.keluar.widgets')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <form id="mainform" name="mainform" method="post">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover" id="tabeldata">
                                                    <thead>
                                                        <tr>
                                                            <th class="padat">AKSI</th>
                                                            <th>JENIS SURAT</th>
                                                            <th>PEMOHON</th>
                                                            <th>PENANDATANGAN</th>
                                                            <th>STATUS</th>
                                                            <th>KETERANGAN</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($main as $item)
                                                            <tr>
                                                                <td>
                                                                    @if ($item->log_verifikasi == 5)
                                                                        <a href="{{ ci_route('api.surat_kecamatan.download', $item->nomor) }}" target="_blank" class="btn btn-social bg-black btn-sm" title="Unduh"><i class="fa fa-download"></i> Unduh</a>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $item->nama }}</td>
                                                                <td>{{ $item->penduduk->nama }}</td>
                                                                <td>{{ $item->pengurus->nama }}</td>
                                                                <td>
                                                                    @if ($item->log_verifikasi == 1)
                                                                        <span class="label label-warning">Menunggu Verifikasi Operator</span>
                                                                    @elseif ($item->log_verifikasi == 2)
                                                                        <span class="label label-warning">Menunggu Verifikasi Sekretaris</span>
                                                                    @elseif ($item->log_verifikasi == 3)
                                                                        <span class="label label-warning">Menunggu Verifikasi Camat</span>
                                                                    @elseif ($item->log_verifikasi == 4)
                                                                        <span class="label label-primary">Menunggu Ditandatangani</span>
                                                                    @elseif ($item->log_verifikasi == 5)
                                                                        <span class="label label-success">Sudah Ditandatangani</span>
                                                                    @else
                                                                        <span class="label label-danger">Ditolak</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $item->keterangan }}</td>
                                                            </tr>
                                                        @endforeach
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: false,
                autoWidth: false,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                pageLength: 10,
                language: {
                    url: "{{ asset('bootstrap/js/dataTables.indonesian.lang') }}",
                }
            });
        });
    </script>
@endpush
