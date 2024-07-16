@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.asset_validasi')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Stunting
        <small> {{ $action }} Data Bulanan Anak</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('stunting/pemantauan_anak') }}">Bulanan Anak</a></li>
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
                    <a href="{{ ci_route('stunting.pemantauan_anak') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Bulanan Anak
                    </a>
                </div>
                {!! form_open($formAction, 'class="form-horizontal" id="validasi"') !!}
                <div class="box-body">
                    <div class="form-group" style="display: {{ $anak->kia_id ? 'none' : '' }}">
                        <label class="col-sm-3 control-label">No Register KIA</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm required select2" id="id_kia" name="id_kia" style="width:100%;">
                                <option value="">-- Cari Nomor KIA --</option>
                                @foreach ($kia as $data)
                                    <option value="{{ $data->id }}" @selected($anak->kia_id == $data->id)>Nomor KIA :
                                        {{ $data->no_kia . ' - ' . $data->anak->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="display: {{ $anak->created_at ? 'none' : '' }}">
                        <label class="col-sm-3 control-label">Tanggal Periksa</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-sm tgl_sekarang required" name="tanggal_periksa" placeholder="Masukkan tanggal periksa" value="{{ $anak->created_at }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Posyandu</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm required select2" name="id_posyandu" style="width:100%;">
                                <option value="">-- Cari Posyandu --</option>
                                @foreach ($posyandu as $key => $value)
                                    <option value="{{ $key }}" @selected($anak->posyandu_id == $key)>{{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Status Gizi Anak</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm required select2" name="status_gizi" style="width:100%;">
                                <option value="">Pilih Status Gizi Anak</option>
                                @foreach ($status_gizi_anak as $key => $value)
                                    <option value="{{ $key }}" @selected($anak->status_gizi == $key)>{{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Umur</label>
                        <div class="col-sm-9">
                            <input disabled type="test" class="form-control input-sm" name="umur" value="{{ $umur->y . ' tahun ' . $umur->m . ' bulan' }}" />
                        </div>
                    </div>
                    <div class="form-group" style="display: none">
                        <label class="col-sm-3 control-label">Umur (Bulan)</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control input-sm required" min="1" max="24" name="umur_bulan" value="{{ $anak->umur_bulan }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Hasil Status Tikar</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm required select2" name="status_tikar" style="width:100%;">
                                <option value="">Pilih Status Tikar</option>
                                @foreach ($status_tikar_anak as $key => $value)
                                    <option value="{{ $key }}" @selected($anak->status_tikar == $key)>{{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Imunisasi Campak</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm required" {{ $anak->umur_bulan >= 6 ? '' : 'disabled' }} id="pemberian_imunisasi_campak" name="pemberian_imunisasi_campak">
                                <option value="">Pilih Status Pemberian Imunisasi Campak</option>
                                @foreach ($status_imunisasi_campak as $key => $value)
                                    <option value="{{ $key }}" @selected($anak->pemberian_imunisasi_campak == $key)>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Berat Badan (kg)</label>
                        <div class="col-sm-9">
                            <input
                                type="text"
                                class="form-control input-sm required luas"
                                {{ $anak->pengukuran_berat_badan ? '' : 'disabled' }}
                                min="1"
                                id="berat_badan"
                                name="berat_badan"
                                placeholder="Masukkan berat badan"
                                value="{{ $anak->berat_badan }}"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Tinggi Badan (cm)</label>
                        <div class="col-sm-9">
                            <input
                                type="text"
                                class="form-control input-sm required luas"
                                {{ $anak->pengukuran_tinggi_badan ? '' : 'disabled' }}
                                min="1"
                                id="tinggi_badan"
                                name="tinggi_badan"
                                placeholder="Masukkan tinggi badan"
                                value="{{ $anak->tinggi_badan }}"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Imunisasi Dasar</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($anak->pemberian_imunisasi_dasar)">
                                        <input
                                            type="radio"
                                            name="pemberian_imunisasi_dasar"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($anak->pemberian_imunisasi_dasar)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$anak->pemberian_imunisasi_dasar)">
                                        <input
                                            type="radio"
                                            name="pemberian_imunisasi_dasar"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$anak->pemberian_imunisasi_dasar)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Pengukuran Berat Badan</label>
                                <div name="pengukuran_berat_badan" class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($anak->pengukuran_berat_badan)">
                                        <input
                                            type="radio"
                                            name="pengukuran_berat_badan"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($anak->pengukuran_berat_badan)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$anak->pengukuran_berat_badan)">
                                        <input
                                            type="radio"
                                            name="pengukuran_berat_badan"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$anak->pengukuran_berat_badan)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Pengukuran Tinggi Badan</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($anak->pengukuran_tinggi_badan)">
                                        <input
                                            type="radio"
                                            name="pengukuran_tinggi_badan"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($anak->pengukuran_tinggi_badan)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$anak->pengukuran_tinggi_badan)">
                                        <input
                                            type="radio"
                                            name="pengukuran_tinggi_badan"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$anak->pengukuran_tinggi_badan)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Konseling Gizi Ayah</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($anak->konseling_gizi_ayah)">
                                        <input
                                            type="radio"
                                            name="konseling_gizi_ayah"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($anak->konseling_gizi_ayah)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$anak->konseling_gizi_ayah)">
                                        <input
                                            type="radio"
                                            name="konseling_gizi_ayah"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$anak->konseling_gizi_ayah)
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
                                <label class="col-sm-12 control-label">Konseling Gizi Ibu</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($anak->konseling_gizi_ibu)">
                                        <input
                                            type="radio"
                                            name="konseling_gizi_ibu"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($anak->konseling_gizi_ibu)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$anak->konseling_gizi_ibu)">
                                        <input
                                            type="radio"
                                            name="konseling_gizi_ibu"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$anak->konseling_gizi_ibu)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Kunjungan Rumah</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($anak->kunjungan_rumah)">
                                        <input
                                            type="radio"
                                            name="kunjungan_rumah"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($anak->kunjungan_rumah)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$anak->kunjungan_rumah)">
                                        <input
                                            type="radio"
                                            name="kunjungan_rumah"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$anak->kunjungan_rumah)
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
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($anak->air_bersih)">
                                        <input
                                            type="radio"
                                            name="air_bersih"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($anak->air_bersih)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$anak->air_bersih)">
                                        <input
                                            type="radio"
                                            name="air_bersih"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$anak->air_bersih)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Kepemilikan Jamban Sehat</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($anak->kepemilikan_jamban)">
                                        <input
                                            type="radio"
                                            name="kepemilikan_jamban"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($anak->kepemilikan_jamban)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$anak->kepemilikan_jamban)">
                                        <input
                                            type="radio"
                                            name="kepemilikan_jamban"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$anak->kepemilikan_jamban)
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
                                <label class="col-sm-12 control-label">Akta Lahir</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($anak->akta_lahir)">
                                        <input
                                            type="radio"
                                            name="akta_lahir"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($anak->akta_lahir)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$anak->akta_lahir)">
                                        <input
                                            type="radio"
                                            name="akta_lahir"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$anak->akta_lahir)
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
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($anak->jaminan_kesehatan)">
                                        <input
                                            type="radio"
                                            name="jaminan_kesehatan"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($anak->jaminan_kesehatan)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$anak->jaminan_kesehatan)">
                                        <input
                                            type="radio"
                                            name="jaminan_kesehatan"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$anak->jaminan_kesehatan)
                                            autocomplete="off"
                                        >Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Pengasuhan (PAUD)</label>
                                <div class="btn-group col-xs-12 col-sm-12" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active($anak->pengasuhan_paud)">
                                        <input
                                            type="radio"
                                            name="pengasuhan_paud"
                                            class="form-check-input"
                                            type="radio"
                                            value="1"
                                            @checked($anak->pengasuhan_paud)
                                            autocomplete="off"
                                        >Ya
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-6 form-check-label @active(!$anak->pengasuhan_paud)">
                                        <input
                                            type="radio"
                                            name="pengasuhan_paud"
                                            class="form-check-input"
                                            type="radio"
                                            value="0"
                                            @checked(!$anak->pengasuhan_paud)
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
        $('input[type=radio][name=pengukuran_berat_badan]').change(function() {
            if (this.value == 1) {
                $('#berat_badan').prop("disabled", false);
            } else {
                $('#berat_badan').prop("disabled", true);
            }
        });

        $('input[type=radio][name=pengukuran_tinggi_badan]').change(function() {
            if (this.value == 1) {
                $('#tinggi_badan').prop("disabled", false);
            } else {
                $('#tinggi_badan').prop("disabled", true);
            }
        });

        $('select[name="id_kia"]').on('change', function() {
            var id = this.value;
            $.ajax({
                type: "GET",
                url: "{{ ci_route('stunting.formAnak') }}",
                data: {
                    kia: id,
                },
                dataType: 'json',
                success: function(data) {
                    if (data == null) {
                        console.log('a');
                    } else {
                        if (data.m >= 6) {
                            $('#pemberian_imunisasi_campak').prop("disabled", false);
                        } else {
                            $('#pemberian_imunisasi_campak').prop("disabled", true);
                        }
                        if (data.y == 1) {
                            $('input[name=umur_bulan]').val(12 + data.m);
                        } else {
                            $('input[name=umur_bulan]').val(data.m);
                        }
                        $('input[name=umur]').val(data.y + ' tahun ' + data.m + ' bulan');
                    }
                }
            });
        });
    </script>
@endpush
