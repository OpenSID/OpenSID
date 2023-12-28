@include('admin.layouts.components.asset_validasi')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Surat {{ ucwords($surat['nama']) }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('surat') }}">Daftar Cetak Surat</a></li>
    <li class="active"> Surat {{ ucwords($surat['nama']) }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ site_url('surat') }}"
                class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                title="Kembali Ke Daftar Wilayah">
                <i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat
            </a>
        </div>
        <div class="box-body">
            @if ($penduduk)
                <form id="main" name="main" method="POST" class="form-horizontal">
                    <div class="form-group">
                        <label for="nik" class="col-sm-3 control-label">NIK / Nama</label>
                        <div class="col-sm-6 col-lg-4">
                            <select id="nik" name="nik" class="form-control input-sm required"
                                data-placeholder="-- Cari NIK / Tag ID Card / Nama Penduduk --"
                                onchange="formAction('main')" data-surat="{{ $surat->id }}">
                                @if ($individu)
                                    <option value="{{ $individu->id }}" selected>
                                        {{ $individu->nik . ' - ' . ($individu->tag_id_card ?: ' ') . ' - ' . $individu->nama }}
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                </form>
            @endif

            {!! form_open($form_action, 'id="validasi" method="POST" class="form-surat form-horizontal"') !!}
            <input type="hidden" id="url_surat" name="url_surat" value="{{ $url }}">
            <input type="hidden" id="url_remote" name="url_remote" value="{{ site_url('surat/nomor_surat_duplikat') }}">

            @if ($penduduk)
                @if ($individu)
                    @include('admin.surat.konfirmasi_pemohon')

                    @if ($anggota)
                        <div class="form-group">
                            <label for="keperluan" class="col-sm-3 control-label">Data Keluarga / KK</label>
                            <div class="col-sm-8">
                                <a id="showData" class="btn btn-social btn-danger btn-sm"><i class="fa fa-search-plus"></i>
                                    Tampilkan</a>
                                <a id="hideData" class="btn btn-social btn-danger btn-sm"><i
                                        class="fa fa-search-minus"></i>
                                    Sembunyikan</a>
                            </div>
                        </div>

                        <div id="kel" class="form-group hide">
                            <label for="pengikut" class="col-sm-3 control-label"></label>
                            <div class="col-sm-8">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover tabel-daftar">
                                        <thead class="bg-gray disabled color-palette">
                                            <tr>
                                                <th>No</th>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Tempat Tanggal Lahir</th>
                                                <th>Hubungan</th>
                                                <th>Status Kawin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($anggota as $key => $data)
                                                <tr>
                                                    <td class="padat">{{ $key + 1 }}</td>
                                                    <td class="padat">{{ $data->nik }}</td>
                                                    <td nowrap>{{ $data->nama }}</td>
                                                    <td nowrap>{{ $data->jenisKelamin->nama }}</td>
                                                    <td nowrap>{{ $data->tempatlahir }},
                                                        {{ tgl_indo($data->tanggallahir) }}
                                                    </td>
                                                    <td nowrap>{{ $data->pendudukHubungan->nama }}</td>
                                                    <td nowrap>{{ $data->statusKawin->nama }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="form-group">
                            <label for="keperluan" class="col-sm-3 control-label">Data Keluarga / KK</label>
                            <div class="col-sm-8">
                                <label class="text-red small">Penduduk yang dipilih bukan
                                    {{ \App\Enums\StatusHubunganEnum::valueOf(\App\Enums\StatusHubunganEnum::KEPALA_KELUARGA) }}</label>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="row jar_form">
                    <label for="nomor" class="col-sm-3"></label>
                    <div class="col-sm-8">
                        <input class="required" type="hidden" name="nik" value="{{ $individu['id'] }}">
                    </div>
                </div>
            @endif

            @include('admin.surat.nomor_surat')

            @include('admin.surat.kode_isian')

            @include('admin.surat.form_tgl_berlaku')

            @include('admin.surat.form_pamong')

        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i
                    class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                Simpan</button>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $('document').ready(function() {
            $('#nik').select2({
                ajax: {
                    url: SITE_URL + 'surat/apipenduduksurat',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            q: params.term || '',
                            page: params.page || 1,
                            surat: $(this).data('surat'),
                        };
                    },
                    cache: true
                },
                placeholder: function() {
                    $(this).data('placeholder');
                },
                minimumInputLength: 0,
                allowClear: true,
                escapeMarkup: function(markup) {
                    return markup;
                },
            });

            $('#showData').click(function() {
                $("#kel").removeClass('hide');
                $('#showData').hide();
                $('#hideData').show();
            });

            $('#hideData').click(function() {
                $('#kel').addClass('hide');
                $('#hideData').hide();
                $('#showData').show();
            });
            $('#hideData').hide();
        });
    </script>
@endpush
