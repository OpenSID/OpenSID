@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.asset_validasi')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Stunting
        <small> {{ $action }} Data Bulanan Ibu Hamil</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('stunting/pemantauan_ibu_hamil') }}">Bulanan Ibu Hamil</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    @include('admin.stunting.widget')

    <div class="row">
        @include('admin.stunting.navigasi')

        <div class="col-md-9 col-lg-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ ci_route('stunting.pemantauan_ibu_hamil') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Bulanan Ibu Hamil
                    </a>
                </div>
                {!! form_open($formAction, 'class="form-horizontal" id="validasi"') !!}
                <div class="box-body">
                    <div class="form-group" style="display: {{ $ibuHamil->kia_id ? 'none' : '' }}">
                        <label class="col-sm-3 control-label">No Register KIA</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm required select2" id="id_kia" name="id_kia" style="width:100%;">
                                <option value="">-- Cari Nomor KIA --</option>
                                @foreach ($kia as $data)
                                    <option value="{{ $data->id }}" @selected($ibuHamil->kia_id == $data->id)>Nomor KIA :
                                        {{ $data->no_kia . ' - ' . $data->ibu->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal Periksa</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-sm {{ $ibuHamil->created_at ? 'datepicker' : 'tgl_sekarang' }} required" name="tanggal_periksa" placeholder="Masukkan tanggal periksa"
                                value="{{ $ibuHamil->created_at ? date('d-m-Y', strtotime($ibuHamil->created_at)) : date('d-m-Y') }}"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Posyandu</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm required select2" name="id_posyandu" style="width:100%;">
                                <option value="">-- Cari Posyandu --</option>
                                @foreach ($posyandu as $key => $value)
                                    <option value="{{ $key }}" @selected($ibuHamil->posyandu_id == $key)>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status Kehamilan</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm required select2" @disabled($ibuHamil->status_kehamilan === null) id="status_kehamilan" name="status_kehamilan">
                                <option value="">Pilih Status Kehamilan</option>
                                @foreach ($status_kehamilan_ibu as $key => $value)
                                    <option value="{{ $key }}" {{ selected($ibuHamil->status_kehamilan, $key) }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Usia Kehamilan (Bulan)</label>
                        <div class="col-sm-9">
                            <input
                                type="number"
                                @disabled($ibuHamil->usia_kehamilan === null)
                                class="form-control input-sm required"
                                min="1"
                                max="12"
                                id="usia_kehamilan"
                                name="usia_kehamilan"
                                placeholder="Masukkan usia kehamilan"
                                value="{{ $ibuHamil->usia_kehamilan }}"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tanggal Melahirkan</label>
                        <div class="col-sm-9">
                            <input
                                type="text"
                                @disabled($ibuHamil->tanggal_melahirkan === null)
                                class="form-control input-sm datepicker required"
                                id="tanggal_melahirkan"
                                name="tanggal_melahirkan"
                                placeholder="Masukkan tanggal melahirkan"
                                value="{{ $ibuHamil->tanggal_melahirkan }}"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Berapa butir pil Fe</label>
                        <div class="col-sm-9">
                            <input
                                type="number"
                                @disabled($ibuHamil->konsumsi_pil_fe === null)
                                class="form-control input-sm required"
                                min="1"
                                id="butir_pil_fe"
                                name="butir_pil_fe"
                                placeholder="Masukkan jumlah butir pil Fe"
                                value="{{ $ibuHamil->butir_pil_fe }}"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Pemeriksaan Kehamilan</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($ibuHamil->pemeriksaan_kehamilan)">
                                        <input
                                            type="radio"
                                            name="pemeriksaan_kehamilan"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($ibuHamil->pemeriksaan_kehamilan)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$ibuHamil->pemeriksaan_kehamilan)">
                                        <input
                                            type="radio"
                                            name="pemeriksaan_kehamilan"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$ibuHamil->pemeriksaan_kehamilan)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Dapat & Konsumsi Pil Fe</label>
                                <div id="konsumsi_pil_fe" name="konsumsi_pil_fe" class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($ibuHamil->konsumsi_pil_fe)">
                                        <input
                                            type="radio"
                                            name="konsumsi_pil_fe"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($ibuHamil->konsumsi_pil_fe)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$ibuHamil->konsumsi_pil_fe)">
                                        <input
                                            type="radio"
                                            name="konsumsi_pil_fe"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$ibuHamil->konsumsi_pil_fe)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Pemeriksaan Nifas</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($ibuHamil->pemeriksaan_nifas)">
                                        <input
                                            type="radio"
                                            name="pemeriksaan_nifas"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($ibuHamil->pemeriksaan_nifas)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$ibuHamil->pemeriksaan_nifas)">
                                        <input
                                            type="radio"
                                            name="pemeriksaan_nifas"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$ibuHamil->pemeriksaan_nifas)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Konseling Gizi (Kelas IH)</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($ibuHamil->konseling_gizi)">
                                        <input
                                            type="radio"
                                            name="konseling_gizi"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($ibuHamil->konseling_gizi)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$ibuHamil->konseling_gizi)">
                                        <input
                                            type="radio"
                                            name="konseling_gizi"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$ibuHamil->konseling_gizi)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Kunjungan Rumah</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($ibuHamil->kunjungan_rumah)">
                                        <input
                                            type="radio"
                                            name="kunjungan_rumah"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($ibuHamil->kunjungan_rumah)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$ibuHamil->kunjungan_rumah)">
                                        <input
                                            type="radio"
                                            name="kunjungan_rumah"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$ibuHamil->kunjungan_rumah)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Kepemilikan Akses Air Bersih</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($ibuHamil->akses_air_bersih)">
                                        <input
                                            type="radio"
                                            name="akses_air_bersih"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($ibuHamil->akses_air_bersih)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$ibuHamil->akses_air_bersih)">
                                        <input
                                            type="radio"
                                            name="akses_air_bersih"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$ibuHamil->akses_air_bersih)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Kepemilikan Jamban</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($ibuHamil->kepemilikan_jamban)">
                                        <input
                                            type="radio"
                                            name="kepemilikan_jamban"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($ibuHamil->kepemilikan_jamban)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$ibuHamil->kepemilikan_jamban)">
                                        <input
                                            type="radio"
                                            name="kepemilikan_jamban"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$ibuHamil->kepemilikan_jamban)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Jaminan Kesehatan</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($ibuHamil->jaminan_kesehatan)">
                                        <input
                                            type="radio"
                                            name="jaminan_kesehatan"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($ibuHamil->jaminan_kesehatan)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$ibuHamil->jaminan_kesehatan)">
                                        <input
                                            type="radio"
                                            name="jaminan_kesehatan"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$ibuHamil->jaminan_kesehatan)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                        Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                        Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('input[type=radio][name=konsumsi_pil_fe]').change(function() {
            if (this.value == 1) {
                $('#butir_pil_fe').prop("disabled", false);
            } else {
                $('#butir_pil_fe').prop("disabled", true);
            }
        });

        $('select[name="id_kia"]').on('change', function() {
            var id = this.value;
            $.ajax({
                type: "GET",
                url: "{{ ci_route('stunting.formIbuHamil') }}",
                data: {
                    kia: id,
                },
                dataType: 'json',
                success: function(data) {
                    if (data == 0) {
                        $('#tanggal_melahirkan').prop("disabled", true)
                        $('#status_kehamilan').prop("disabled", false)
                        $('#usia_kehamilan').prop("disabled", false)
                    } else {
                        $('#tanggal_melahirkan').prop("disabled", false)
                        $('#status_kehamilan').prop("disabled", true)
                        $('#usia_kehamilan').prop("disabled", true)
                    }
                }
            });
        });
    </script>
@endpush
