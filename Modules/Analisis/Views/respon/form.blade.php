@include('admin.layouts.components.asset_datatables')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Data Sensus {{ $analisis_master['nama'] }}
    </h1>
@endsection

@section('breadcrumb')
    <li>Master Analisis</li>
    <li>{{ $analisis_master['nama'] }}</li>
    <li>Data Sensus</li>
    <li class="active">Input Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-4 col-lg-3">
            @include('analisis::master.menu')
        </div>
        <div class="col-md-8 col-lg-9">
            <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
                <div id="box-full-screen" class="box box-info">
                    <div class="box-header with-border">
                        @if ($fullscreen)
                            <a id="toggle-btn" href="{{ current_url() }}?fs=0" class="btn btn-social bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                                <i class="fa fa-search-minus"></i>Normal
                            </a>
                        @else
                            <a id="toggle-expand-btn" href="{{ current_url() }}?fs=1" class="btn btn-social bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                                <i class="fa fa-search-plus"></i>Full Screen
                            </a>
                        @endif
                        @if (can('u'))
                            <a href="{{ $perbaharui }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Perbaharui Data {{ $analisis_master['subjek_nama'] }}"><i class="fa fa-refresh"></i>
                                Pebaharui Data
                                {{ $analisis_master['subjek_nama'] }}</a>
                        @endif
                        <a href="{{ ci_route('analisis_respon', $analisis_master['id']) }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Data Sensus</a>
                    </div>
                    <div class="box-body">
                        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover">
                                            <tr>
                                                <td width="150">Form Pendataan</td>
                                                <td width="1">:</td>
                                                <td><a href="{{ ci_route('analisis_master.menu', $analisis_master['id']) }}">{{ $analisis_master['nama'] }}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Nomor Identitas</td>
                                                <td>:</td>
                                                <td>{{ $subjek['nid'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>Nama Subjek</td>
                                                <td>:</td>
                                                <td>{{ $subjek['nama'] }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    @if ($list_anggota)
                                        <div class="table-responsive">
                                            <table class="table table-bordered dataTable table-hover nowrap">
                                                <thead class="bg-gray disabled color-palette">
                                                    <tr>
                                                        <th>No</th>
                                                        @if ($analisis_master['id_child'] != 0)
                                                            <th>Aksi</th>
                                                        @endif
                                                        <th>NIK</th>
                                                        <th>Nama</th>
                                                        <th>Tanggal Lahir</th>
                                                        <th>Jenis Kelamin</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($list_anggota as $ang)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            @if ($analisis_master['id_child'] != 0)
                                                                <td nowrap>
                                                                    <a
                                                                        href="{{ ci_route('analisis_respon.' . $analisis_master['id'] . '.child.form.' . $idSubjek, $ang['id']) }}"
                                                                        class="btn bg-purple btn-sm"
                                                                        title="Input Data"
                                                                        data-remote="false"
                                                                        data-toggle="modal"
                                                                        data-target="#modalBox"
                                                                        data-title="{{ $ang['nama'] }} - [{{ $ang['nik'] }}]"
                                                                    ><i class='fa fa-check-square-o'></i></a>
                                                                </td>
                                                            @endif
                                                            <td>{{ $ang['nik'] }}</td>
                                                            <td nowrap>{{ $ang['nama'] }}</td>
                                                            <td nowrap>{{ tgl_indo(implode('-', array_reverse(explode('-', $ang['tanggallahir'])))) }}</td>
                                                            <td>{{ strtoupper(App\Enums\JenisKelaminEnum::valueOf($ang['sex'])) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                    <div class="table-responsive">
                                        <table class="table">
                                            @php
                                                $new = 1;
                                                $last = 0;
                                            @endphp
                                            @foreach ($list_jawab as $data)
                                                @php
                                                    if ($data['id_kategori'] != $last || $last == 0) {
                                                        $new = 1;
                                                    }
                                                @endphp
                                                @if ($new == 1)
                                                    <tr>
                                                        <th colspan="2" class="bg-aqua"><strong>{{ $data['kategori'] }}</strong></th>
                                                    </tr>
                                                    @php
                                                        $new = 0;
                                                        $last = $data['id_kategori'];
                                                    @endphp
                                                @endif
                                                <tr>
                                                    <td colspan="2"><label>{{ $data['nomor'] }} {{ $data['pertanyaan'] }}</label></td>
                                                </tr>
                                                @if ($data['id_tipe'] == 1)
                                                    <tr>
                                                        <td width="35px;"></td>
                                                        <td class="col-xs-12 col-sm-4 pull-left">
                                                            <select class="form-control input-sm select2" name="rb[{{ $data['id'] }}]" onchange="formAction('mainform', '{{ ci_route('analisis_indikator.' . $analisisMaster['id'] . '.kategori') }}')">
                                                                <option value="" @disabled(($data['referensi'] && $subjek[$data['referensi']] && $subjek[$data['referensi']] != $data2['kode_jawaban']) || $data2['cek'] == 1)>
                                                                    Pilih Jawaban</option>
                                                                @foreach ($data['parameter_respon'] as $data2)
                                                                    <option {{ jecho(($data['referensi'] && $subjek[$data['referensi']] && $subjek[$data['referensi']] != $data2['kode_jawaban']) || $data2['cek'] == 1, true, '') }} value="{{ $data['id'] }}.{{ $data2['id_parameter'] }}"
                                                                        @selected($data2['cek'] == 1 || $subjek[$data['referensi']] == $data2['kode_jawaban'])
                                                                    >
                                                                        {{ $data2['kode_jawaban'] }}. {{ $data2['jawaban'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @elseif ($data['id_tipe'] == 2)
                                                    <tr>
                                                        <td></td>
                                                        <td id="op_item">
                                                            @foreach ($data['parameter_respon'] as $data2)
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input name="cb[{{ $data2['id_parameter'] }}_{{ $data['id'] }}]" value="{{ $data['id'] }}.{{ $data2['id_parameter'] }}" type="checkbox" @checked($data2['cek'])>
                                                                        {{ $data2['kode_jawaban'] }}. {{ $data2['jawaban'] }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @elseif ($data['id_tipe'] == 3)
                                                    <div class="form-group">
                                                        <tr>
                                                            <td></td>
                                                            <td>
                                                                <input @readonly($data['referensi'] && $subjek[$data['referensi']]) class="form-control input-sm" name="ia[{{ $data['id'] }}]" value="{{ $data['parameter_respon'][0]['jawaban'] ?? $subjek[$data['referensi']] }}" type="number" min=0>
                                                            </td>
                                                        </tr>
                                                    </div>
                                                @else
                                                    <div class="form-group">
                                                        <tr>
                                                            <td></td>
                                                            <td>
                                                                @php
                                                                    if (preg_match('/tanggal/i', $data['referensi']) || preg_match('/tanggal/i', $data['referensi'])) {
                                                                        $subjek[$data['referensi']] = tgl_indo_dari_str($subjek[$data['referensi']]);
                                                                    }
                                                                @endphp
                                                                <textarea @readonly($data['referensi'] && $subjek[$data['referensi']]) id="it[{{ $data['id'] }}]" name="it[{{ $data['id'] }}]" class="form-control input-sm" style="width:100%" rows="5">{{ $data['parameter_respon'][0]['jawaban'] ?? $subjek[$data['referensi']] }}</textarea>
                                                            </td>
                                                        </tr>
                                                    </div>
                                                @endif
                                                <tr>
                                                    <td colspan="2" style="height:15px;"></td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                    <div class="col-sm-12">
                                        @if (!empty($list_bukti))
                                            <div class="form-group">
                                                <label class="col-sm-2 no-padding">Berkas Form Pendaftaran</label>
                                                <div class="col-sm-2">
                                                    <input type="hidden" name="old_file" value="{{ $list_bukti[0]['pengesahan'] }}">
                                                    <img class="attachment-img img-responsive" src="{{ base_url(LOKASI_PENGESAHAN . $list_bukti[0]['pengesahan']) }}" alt="Bukti Pengesahan">
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label class="control-label" for="upload">Unggah Berkas Form Pendataan</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" id="file_path">
                                                <input id="file" type="file" class="hidden" name="pengesahan" accept=".jpg,.jpeg">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                                                </span>
                                            </div>
                                            @if (!empty($list_bukti))
                                                <p class="help-block"><code>(Kosongkan jika tidak ingin mengubah berkas)</code></p>
                                            @endif
                                            <p><label class="control-label">*) Format file harus *.jpg</label></p>
                                            <p><label class="control-label">*) Berkas form pendataan digunakan sebagai penguat /
                                                    bukti
                                                    pendataan maupun untuk verifikasi data yang sudah terinput.</label></p>
                                            <p><label class="control-label">*) Berkas Bukti / pengesahan harus berupa file
                                                    gambar dengan
                                                    format .jpg, dengan ukuran maksimal 1 Mb (1 megabyte)</label></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (can('u'))
                                <div class="box-footer">
                                    <button type='reset' class='btn btn-social btn-danger btn-sm'><i class='fa fa-times'></i>
                                        Batal</button>
                                    <button type='submit' class='btn btn-social btn-info btn-sm pull-right'><i class='fa fa-check'></i> Simpan</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var fullscreen = {{ $fullscreen ? 1 : 0 }}

            if (fullscreen) {
                $('#box-full-screen').addClass("panel-fullscreen");
            } else {
                $('#box-full-screen').removeClass("panel-fullscreen");
            }

            var op_item_width = (parseInt($('#op_item').width()) / 2 - 10);
            var label_width = (parseInt($('#op_item').width()) / 2) - 42;

            $('#op_item div').css('width', op_item_width);
            $('#op_item label').css('width', label_width);
        });
    </script>
@endpush
