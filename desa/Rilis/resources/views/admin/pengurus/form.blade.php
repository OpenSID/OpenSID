@extends('admin.layouts.index')
@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.asset_validasi')

@php $pemerintah = 'Staf ' . ucwords(setting('sebutan_pemerintah_desa')) @endphp
@section('title')
    <h1>
        {{ $pemerintah }}
        <small>{{ $aksi }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pengurus') }}">{{ $pemerintah }}</a></li>
    <li class="active">{{ $aksi }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('pengurus') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar
                {{ $pemerintah }}
            </a>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12 form-horizontal">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-4 col-lg-2 control-label" for="pengurus">Data Staf</label>
                        <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                            <label for="pengurus_1" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?php if (empty($pamong) || !empty($individu)) : ?>active<?php endif ?>">
                                <input
                                    id="pengurus_1"
                                    type="radio"
                                    name="pengurus"
                                    class="form-check-input"
                                    type="radio"
                                    value="1"
                                    @if (empty($pamong) || !empty($individu)) checked @endif
                                    autocomplete="off"
                                    onchange="pengurus_asal(this.value);"
                                > Dari Database Penduduk
                            </label>
                            <label for="pengurus_2" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?php if (!empty($pamong) && empty($individu)) : ?>active<?php endif; ?>">
                                <input
                                    id="pengurus_2"
                                    type="radio"
                                    name="pengurus"
                                    class="form-check-input"
                                    type="radio"
                                    value="2"
                                    @if (!empty($pamong) && empty($individu)) checked @endif
                                    autocomplete="off"
                                    onchange="pengurus_asal(this.value);"
                                > Tidak Terdata
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <form id="main" name="main" method="POST" class="form-horizontal">
                        <label class="col-xs-12 col-sm-4 col-lg-2 control-label" for="id_pend">NIK / Nama Penduduk </label>
                        <div class="col-xs-12 col-sm-8">
                            <select id="id_pend" name="id_pend" class="form-control input-sm" data-placeholder="-- Silakan Masukan NIK / Nama --" onchange="formAction('main')">
                                <option value="{{ $individu['id'] }}" selected>NIK :
                                    {{ $individu['nik'] . ' - ' . $individu['nama'] . ' - ' . $individu['dusun'] }}
                                </option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?= form_open_multipart($form_action, 'id="validasi-proses" class="form-horizontal"') ?>
        <input type="hidden" name="id_pend" value="{{ $individu['id'] }}">
        <div class="col-md-3">
            @include('admin.layouts.components.ambil_foto', [
                'id_sex' => $individu ? $individu['sex'] : $pamong['id_sex'],
                'foto' => $pamong['foto_staff'],
                'show_dimensi' => [
                    'width' => $imageInfo['width'],
                    'height' => $imageInfo['height'],
                ],
            ])

            @if (count($media_sosial) > 0)
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Media Sosial</h3>
                    </div>

                    <div class="box-body">
                        @foreach ($media_sosial as $value)
                            <strong><i class="fa fa-{{ $value['id'] }}"></i> {{ $value['nama'] }}</strong>
                            <input class="form-control input-sm" type="text" name="media_sosial[{{ $value['id'] }}]" style="margin-bottom: 10px;" value="{{ $pamong['media_sosial'][$value['id']] }}" placeholder="{{ $value['url'] }}">
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_nama">Nama Pegawai
                            <?= ucwords(setting('sebutan_desa')) ?></label>
                        <div class="col-sm-7">
                            <input type="hidden" name="nik" value="{{ $individu['nik'] }}">
                            <input id="nama_penduduk" class="form-control input-sm pengurus-desa" type="text" placeholder="Nama" value="{{ $individu['nama'] }}" disabled="disabled" />
                            <input
                                id="pamong_nama"
                                name="pamong_nama"
                                class="form-control input-sm pengurus-luar-desa {{ !empty($individu) ?: 'required' }}"
                                type="text"
                                placeholder="Nama"
                                value="{{ $pamong['nama'] }}"
                                style="display: none;"
                            />
                        </div>
                    </div>
                    <div class="form-group gelar">
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-7">
                            <input id="nama_dengan_gelar" class="form-control input-sm" type="text" placeholder="Nama dengan gelar" disabled />
                        </div>
                    </div>
                    <div class="form-group gelar">
                        <label class="col-sm-4 control-label" for="gelar">Gelar</label>
                        <div class="col-sm-2">
                            <input id="gelar_depan" name="gelar_depan" class="form-control input-sm input-gelar" type="text" placeholder="Gelar Depan" value="{{ $pamong['gelar_depan'] }}" />
                        </div>
                        <div class="col-sm-2">
                            <input id="gelar_belakang" name="gelar_belakang" class="form-control input-sm input-gelar" type="text" placeholder="Gelar Belakang" value="{{ $pamong['gelar_belakang'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_nik">Nomor Induk Kependudukan</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Nomor Induk Kependudukan" value="{{ $individu['nik'] }}" disabled="disabled" />
                            <input
                                id="pamong_nik"
                                name="pamong_nik"
                                class="form-control input-sm pengurus-luar-desa nik"
                                type="text"
                                maxlength="16"
                                placeholder="Nomor Induk Kependudukan"
                                value="{{ $pamong['pamong_nik'] }}"
                                style="display: none;"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_niap"><?= setting('sebutan_nip_desa') ?></label>
                        <div class="col-sm-7">
                            <input
                                id="pamong_niap"
                                name="pamong_niap"
                                class="form-control input-sm digits"
                                type="text"
                                maxlength="25"
                                placeholder="<?= setting('sebutan_nip_desa') ?>"
                                value="{{ $pamong['pamong_niap'] }}"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_nip">NIP</label>
                        <div class="col-sm-7">
                            <input
                                id="pamong_nip"
                                name="pamong_nip"
                                class="form-control input-sm digits"
                                type="text"
                                maxlength="20"
                                placeholder="NIP"
                                value="{{ $pamong['pamong_nip'] }}"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_tag_id_card">Tag ID Card</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Tag ID Card" value="{{ $individu['tag_id_card'] }}" disabled="disabled" />
                            <input
                                id="pamong_tag_id_card"
                                name="pamong_tag_id_card"
                                class="form-control input-sm pengurus-luar-desa tag_id_card"
                                type="text"
                                maxlength="17"
                                placeholder="Tag ID Card"
                                value="{{ $pamong['pamong_tag_id_card'] }}"
                                style="display: none;"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_tempatlahir">Tempat Lahir</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Tempat Lahir" value="{{ strtoupper($individu['tempatlahir']) }}" disabled="disabled" />
                            <input name="pamong_tempatlahir" class="form-control input-sm pengurus-luar-desa" type="text" placeholder="Tempat Lahir" value="{{ strtoupper($pamong['pamong_tempatlahir']) }}" style="display: none;" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_tanggallahir">Tanggal Lahir</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Tanggal Lahir" value="{{ strtoupper($individu['tanggallahir']) }}" disabled="disabled" />
                            <div class="input-group input-group-sm date pengurus-luar-desa" style="display: none;">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control input-sm pull-right tgl_1" name="pamong_tanggallahir" type="text" value="{{ $pamong['pamong_tanggallahir'] ? tgl_indo_out($pamong['pamong_tanggallahir']) : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_sex">Jenis Kelamin</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Jenis Kelamin" value="{{ $individu['jenis_kelamin']['nama'] }}" disabled="disabled" />
                            <select class="form-control input-sm pengurus-luar-desa" name="pamong_sex" style="display: none;">
                                <option value="">Jenis Kelamin</option>
                                <option value="1" {{ selected($pamong['pamong_sex'], '1') }}>Laki-Laki</option>
                                <option value="2" {{ selected($pamong['pamong_sex'], '2') }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_pendidikan">Pendidikan</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Pendidikan" value="{{ $individu['pendidikan_k_k']['nama'] }}" disabled="disabled" />
                            <select class="form-control input-sm pengurus-luar-desa" name="pamong_pendidikan" style="display: none;">
                                <option value="">Pilih Pendidikan (Dalam KK) </option>
                                @foreach ($pendidikan_kk as $key => $value)
                                    <option value="{{ $key }}" {{ selected($pamong['pamong_pendidikan'], $key) }}>
                                        {{ strtoupper($value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_agama">Agama</label>
                        <div class="col-sm-7">
                            <input class="form-control input-sm pengurus-desa" type="text" placeholder="Agama" value="{{ $individu['agama']['nama'] }}" disabled="disabled" />
                            <select class="form-control input-sm pengurus-luar-desa" name="pamong_agama" style="display: none;">
                                <option value="">Pilih Agama</option>
                                @foreach ($agama as $key => $value)
                                    <option value="{{ $key }}" {{ selected($pamong['pamong_agama'], $key) }}>
                                        {{ strtoupper($value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_pangkat">Pangkat / Golongan</label>
                        <div class="col-sm-7">
                            <input name="pamong_pangkat" class="form-control input-sm" type="text" maxlength="20" placeholder="Pangkat / Golongan" value="{{ $pamong['pamong_pangkat'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_nosk">Nomor Keputusan Pengangkatan</label>
                        <div class="col-sm-7">
                            <input name="pamong_nosk" class="form-control input-sm" type="text" maxlength="30" placeholder="Nomor Keputusan Pengangkatan" value="{{ $pamong['pamong_nosk'] }}" />
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class="col-sm-4 control-label" for="pamong_tglsk">Tanggal Keputusan Pengangkatan</label>
                        <div class="col-sm-7">
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control input-sm pull-right tgl_1" name="pamong_tglsk" type="text" value="{{ $pamong['pamong_tglsk'] ? tgl_indo_out($pamong['pamong_tglsk']) : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_nohenti">Nomor Keputusan Pemberhentian</label>
                        <div class="col-sm-7">
                            <input name="pamong_nohenti" class="form-control input-sm" type="text" placeholder="Nomor Keputusan Pemberhentian" value="{{ $pamong['pamong_nohenti'] }}" />
                        </div>
                    </div>
                    <div class='form-group'>
                        <label class="col-sm-4 control-label" for="pamong_tglhenti">Tanggal Keputusan
                            Pemberhentian</label>
                        <div class="col-sm-7">
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control input-sm pull-right tgl_1" name="pamong_tglhenti" type="text" value="{{ $pamong['pamong_tglhenti'] ? tgl_indo_out($pamong['pamong_tglhenti']) : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="pamong_masajab">Masa Jabatan (Usia/Periode)</label>
                        <div class="col-sm-7">
                            <input name="pamong_masajab" class="form-control input-sm" type="text" placeholder="Contoh: 6 Tahun Periode Pertama (2015 s/d 2021)" value="{{ $pamong['pamong_masajab'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="jabatan">Jabatan</label>
                        <div class="col-sm-7">
                            <select class="form-control select2 input-sm required" id="jabatan" name="jabatan_id">
                                <option value="">Pilih Jabatan</option>
                                @foreach ($jabatan as $key => $value)
                                    <option value="{{ $key }}" {{ selected($pamong['jabatan_id'], $key) }}>{{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="status_pejabat" style="display: none;">
                        <label class="col-xs-12 col-sm-4 col-lg-4 control-label" for="status">Status Pejabat</label>
                        <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                            <label id="sx3" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @if ($pamong['status_pejabat'] == 1) active @endif">
                                <input
                                    id="group1"
                                    type="radio"
                                    name="status_pejabat"
                                    class="form-check-input"
                                    type="radio"
                                    value="1"
                                    @if ($pamong['status_pejabat'] == 1) checked @endif
                                    autocomplete="off"
                                > Aktif
                            </label>
                            <label id="sx4" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @if ($pamong['status_pejabat'] == 0) active @endif">
                                <input
                                    id="group2"
                                    type="radio"
                                    name="status_pejabat"
                                    class="form-check-input"
                                    type="radio"
                                    value="0"
                                    @if ($pamong['status_pejabat'] == 0) checked @endif
                                    autocomplete="off"
                                > Tidak Aktif
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label text-red" for="atasan">Atasan</label>
                        <div class="col-sm-7">
                            <select class="form-control select2 input-sm" name="atasan">
                                <option value="">Pilih Atasan</option>
                                @foreach ($atasan as $data)
                                    <option value="{{ $data->id }}" {{ selected($pamong['atasan'], $data->id) }}>
                                        {{ $data->nama }} ({{ $data->jabatan }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label text-red" for="jabatan">Bagan - Tingkat</label>
                        <div class="col-sm-7">
                            <input name="bagan_tingkat" class="form-control input-sm number" type="text" placeholder="Angka menunjukkan tingkat di bagan organisasi. Contoh: 2" value="{{ $pamong['bagan_tingkat'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label text-red" for="jabatan">Bagan - Offset</label>
                        <div class="col-sm-7">
                            <input name="bagan_offset" class="form-control input-sm number" type="text" placeholder="Angka menunjukkan persentase geser (-n) atau kanan (+n). Contoh: 75%" value="{{ $pamong['bagan_offset'] }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label text-red" for="jabatan">Bagan - Layout</label>
                        <div class="col-sm-7">
                            <select class="form-control input-sm" name="bagan_layout">
                                <option value="">Tidak Ada Layout</option>
                                <option value="hanging" {{ selected($pamong['bagan_layout'], 'hanging') }}>Hanging
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4 text-red">Bagan - Warna</label>
                        <div class="col-sm-7">
                            <div class="input-group my-colorpicker2">
                                <input type="text" name="bagan_warna" class="form-control input-sm warna" placeholder="#FFFFFF" value="{{ $pamong['bagan_warna'] }}">
                                <div class="input-group-addon input-sm">
                                    <i></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-4 col-lg-4 control-label" for="status">Status Pegawai
                            Desa</label>
                        <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                            <label id="sx3" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @if ($pamong['pamong_status'] == '1' || $pamong['pamong_status'] == null) active @endif">
                                <input
                                    id="group1"
                                    type="radio"
                                    name="pamong_status"
                                    class="form-check-input"
                                    type="radio"
                                    value="1"
                                    @if ($pamong['pamong_status'] == '1' || $pamong['pamong_status'] == null) checked @endif
                                    autocomplete="off"
                                > Aktif
                            </label>
                            <label id="sx4" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @if ($pamong['pamong_status'] == '2') active @endif">
                                <input
                                    id="group2"
                                    type="radio"
                                    name="pamong_status"
                                    class="form-check-input"
                                    type="radio"
                                    value="2"
                                    @if ($pamong['pamong_status'] == '2') checked @endif
                                    autocomplete="off"
                                > Tidak Aktif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type='reset' class='btn btn-social btn-danger btn-sm btn-reset'><i class='fa fa-times'></i> Batal</button>
                    <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-colorpicker.min.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('bootstrap/js/bootstrap-colorpicker.min.js') }}"></script>
    <script>
        $('document').ready(function() {
            $('#id_pend').select2({
                ajax: {
                    url: SITE_URL + 'pengurus/apidaftarpenduduk',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            q: params.term || '',
                            page: params.page || 1,
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

            gelar();

            $("input[name='pengurus']:checked").change();
            if ($("input[name='id_pend']").val() != '') {
                $('#pamong_nama').removeClass('required');
            }

            $(".input-gelar").keyup(function() {
                gelar();
            });


            $('#jabatan').on('change', function() {
                const kades = "{{ $kades_id }}";
                if (this.value == 1) {
                    $('#status_pejabat').show();
                } else {
                    $('#status_pejabat').hide();
                }
            });

            $('#jabatan').trigger('change');
        });

        function gelar() {
            var nama = $("input[name='id_pend']").val() != '' ?
                $("#nama_penduduk").val() :
                $("#pamong_nama").val();

            var depan = $("#gelar_depan").val();
            var belakang = $("#gelar_belakang").val();

            if (depan) {
                nama = depan + ' ' + nama;
            }

            if (belakang) {
                nama = nama + ', ' + belakang;
            }

            $("#nama_dengan_gelar").val(nama);
        }

        function pengurus_asal(asal) {
            if (asal == 1) {
                $('#main').show();
                $('.pengurus-luar-desa').hide();
                $('.pengurus-desa').show();
                $('#pamong_nama').val('');
                $('#pamong_nama').removeClass('required');
            } else {
                $('#main').hide();
                $("input[name='id_pend']").val('');
                $('.pengurus-luar-desa').show();
                $('.pengurus-desa').hide();
                $('#pamong_nama').addClass('required');
            }
        }
    </script>
@endpush
