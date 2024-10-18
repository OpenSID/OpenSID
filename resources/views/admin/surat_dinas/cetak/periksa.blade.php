@include('admin.pengaturan_surat.asset_tinymce')
@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Periksa Surat {{ ucwords($surat->formatSurat->nama) }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('surat') }}">Daftar Cetak Surat</a></li>
    <li class="active"> Surat {{ ucwords($surat->nama) }}</li>
    <li class="active"> Konsep Surat {{ ucwords($surat->nama) }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    {!! form_open(null, 'class="form-horizontal"') !!}
    <input type="hidden" id="idsurat" value="{{ $surat->id }}">
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Data Surat</h3>
        </div>

        <div class="box-body">
            @if ($surat->suratDinas->form_isian->individu->sumber)
                <div class="form-group">
                    <label class="control-label col-sm-3">NIK / Nama Penduduk</label>
                    <div class="col-sm-9">
                        <input class="form-control input-sm" readonly="readonly" value="{{ $surat->penduduk->nik }} - {{ $surat->penduduk->nama }}">
                    </div>
                </div>
                @include('admin.surat_dinas.cetak.konfirmasi_pemohon')
            @endif
            <div class="form-group">
                <input type="hidden" name="id_surat" value="{{ $surat['id'] }}">
                <label class="col-sm-3 control-label">Nomor Surat</label>
                <div class="col-sm-4">
                    <input
                        id="nomor"
                        class="form-control input-sm digits required"
                        type="text"
                        placeholder="Nomor Surat"
                        name="nomor"
                        value="{{ $surat->Formatpenomoransurat }}"
                        disabled
                    >
                </div>
            </div>
        </div>
    </div>

    @if ($surat->verifikasi_operator == '-1')
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Alasan Ditolak</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                        <thead class="bg-gray disabled color-palette">
                            <tr>
                                <th>No</th>
                                <th>Pesan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($surat->tolak)
                                @foreach ($surat->tolak as $key => $keterangan)
                                    <tr>
                                        <td class="padat"><?= $key + 1 ?></td>
                                        <td> {{ $keterangan->keterangan }}</td>
                                        <td class="padat">{{ $keterangan->created_at->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="9">Data Tidak Tersedia</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Lampiran</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                    <thead class="bg-gray disabled color-palette">
                        <tr>
                            <th>No</th>
                            <th>Nama Dokumen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($surat->lampiran != null)
                            <tr>
                                <td class="padat">1</td>
                                <td>Lampiran {{ $surat->formatSurat->nama }}</td>
                                <td class="padat">
                                    <button type="button" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block lampiran" title="Cek Dokumen"><i class="fa fa-eye"></i> Cek Dokumen</button>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="9">Tidak Terdapat Lampiran</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-footer text-center">
            <a href="{{ ci_route('surat_dinas_arsip.masuk') }}" id="back" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Permohonan
            </a>
            @if (is_file($surat->filesurat . '.pdf'))
                <button type="button" class="btn btn-social btn-primary btn-sm preview"><i class="fa fa-file-pdf-o"></i>Lihat PDF</button>
            @elseif(is_file($surat->filesurat . '.rtf'))
                <a href="{{ ci_route('surat_dinas_arsip.unduh.rtf', $surat->id) }}" class="btn btn-flat bg-purple btn-sm" title="Unduh Surat RTF" target="_blank"><i class="fa fa-file-word-o"></i> Unduh File</a>
            @endif

            <button type="button" class="btn btn-social btn-success btn-sm verifikasi" data-ulang="{{ $surat->verifikasi_operator == -1 ? 'true' : 'false' }}"><i class="fa fa-check-circle"></i>{{ $surat->verifikasi_operator == -1 ? 'Kirim Ulang' : 'Setujui' }}</button>

            @if (!$operator)
                <button type="button" class="btn btn-social btn-danger btn-sm ditolak"><i class="fa fa-times"></i>Tolak</button>
            @endif

            @if ($surat->verifikasi_operator == -1 && $operator)
                <button type="button" class="btn btn-social btn-danger btn-sm kembalikan"><i class="fa fa-times"></i>Kembalikan</button>
            @endif
        </div>
    </div>

    </form>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('button.verifikasi').click(function(e) {
                e.preventDefault();
                var id = $('#idsurat').val();
                var next = `{{ $next ?? '' }}`;
                var tte = `{{ setting('tte') ?? '' }}`;
                var ulang = $(this).data('ulang')
                var pesan = `Apakah setuju surat ini di teruskan ke ${next}?`
                if (next != '' && ulang) {
                    pesan = `Kirim ulang surat ini untuk diperiksa?`
                } else if (next == '' && tte == '0') {
                    pesan = `Apakah setuju surat ini di teruskan ke Arsip?`
                } else if (next == '' && tte == '1') {
                    pesan = 'Apakah setuju surat ini untuk ditandatangani secara elektronik?'
                }

                var ulr_ajax = {
                    'confirm': `{{ ci_route('surat_dinas_arsip.verifikasi') }}`,
                }

                var redirect = {
                    'confirm': `{{ ci_route('surat_dinas_arsip.masuk') }}`,
                }
                var data = {
                    id: id
                };
                swal2_question(ulr_ajax, redirect, pesan, data, false);
            });

            $('button.ditolak').click(function(e) {
                e.preventDefault();
                console.log(e)
                var id = $('#idsurat').val();
                var ulr_ajax = `{{ ci_route('surat_dinas_arsip.tolak') }}`;
                var redirect = `{{ ci_route('surat_dinas_arsip.masuk') }}`;
                ditolak(id, ulr_ajax, redirect, 'Konfirmasi Pengembalian Surat', 'Pesan singkat alasan pengembalian', 'Pesan singkat alasan permohonan surat dikembalikan');
            });

            $('button.kembalikan').click(function(e) {
                e.preventDefault();
                console.log(e)
                var id = $('#idsurat').val();
                var ulr_ajax = `{{ ci_route('surat_dinas_arsip.kembalikan') }}`;
                var redirect = `{{ ci_route('surat_dinas_arsip.masuk') }}`;
                ditolak(id, ulr_ajax, redirect, 'Konfirmasi Pengembalian Permohonan', 'Pesan singkat alasan permohonan surat dikembalikan', 'Pesan singkat alasan permohonan surat dikembalikan');
            });

            $('button.preview').click(function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                Swal.fire({
                    customClass: {
                        popup: 'swal-lg',
                    },
                    title: 'Lihat',
                    html: `<object data="{{ ci_route('surat_dinas_arsip.unduh/tinymce', $surat->id . '/true') }}" style="width: 100%;min-height: 400px;" type="application/pdf"></object>`,
                    showCancelButton: true,
                    cancelButtonText: 'tutup',
                    showConfirmButton: false,
                })
            });

            $('button.lampiran').click(function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                Swal.fire({
                    customClass: {
                        popup: 'swal-lg',
                    },
                    title: 'Lihat',
                    html: `<object data="{{ ci_route('surat_dinas_arsip.unduh/lampiran', $surat->id . '/true') }}" style="width: 100%;min-height: 400px;" type="application/pdf"></object>`,
                    showCancelButton: true,
                    cancelButtonText: 'tutup',
                    showConfirmButton: false,
                })
            });

            $('a.syarat').click(function(e) {
                e.preventDefault();
                var attr = $(this).attr('href');
                Swal.fire({
                    customClass: {
                        popup: 'swal-lg',
                    },
                    title: 'Lihat',
                    html: `<object data="${attr}" style="width: 100%;min-height: 400px;" ></object>`,
                    showCancelButton: true,
                    cancelButtonText: 'tutup',
                    showConfirmButton: false,
                })
            });
        });
    </script>
@endpush
