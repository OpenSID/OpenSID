@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.asset_validasi')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Stunting
        <small> {{ $action }} Data Sasaran Paud</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('stunting/pemantauan_paud') }}">Sasaran Paud</a></li>
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
                    <a href="{{ ci_route('stunting.pemantauan_paud') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Sasaran Paud
                    </a>
                </div>
                {!! form_open($formAction, 'class="form-horizontal" id="validasi"') !!}
                <div class="box-body">
                    <div class="form-group" style="display: {{ $paud->kia_id ? 'none' : '' }}">
                        <label class="col-sm-3 control-label">No Register KIA</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm required select2" id="id_kia" name="id_kia" style="width:100%;">
                                <option value="">-- Cari Nomor KIA --</option>
                                @foreach ($kia as $data)
                                    <option value="{{ $data->id }}" @selected($paud->kia_id == $data->id)>Nomor KIA :
                                        {{ $data->no_kia . ' - ' . $data->anak->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group" style="display: {{ $paud->created_at ? 'none' : '' }}">
                        <label class="col-sm-3 control-label">Tanggal Periksa</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-sm tgl_sekarang required" name="tanggal_periksa" placeholder="Masukkan tanggal periksa" value="{{ $paud->created_at }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Posyandu</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm required select2" name="id_posyandu" style="width:100%;">
                                <option value="">-- Cari Posyandu --</option>
                                @foreach ($posyandu as $data)
                                    <option value="{{ $data->id }}" @selected($paud->posyandu_id == $data->id)>{{ $data->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Kategori Usia</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm required" name="kategori_usia">
                                <option value="">Pilih Kategori Usia</option>
                                @foreach (['Anak Usia 2 - < 3 Tahun', 'Anak Usia 3 - 6 Tahun'] as $key => $value)
                                    <option value="{{ $key + 1 }}" {{ selected($paud->kategori_usia, $key + 1) }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Januari</label>
                                <div class="col-sm-12">
                                    <select class="form-control input-sm required" name="januari">
                                        <option value="">Pilih Status</option>
                                        @foreach (['Belum', 'Mengikuti', 'Tidak Mengikuti'] as $key => $value)
                                            <option value="{{ $key + 1 }}" {{ selected($paud->januari, $key + 1) }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Februari</label>
                                <div class="col-sm-12">
                                    <select class="form-control input-sm required" name="februari">
                                        <option value="">Pilih Status</option>
                                        @foreach (['Belum', 'Mengikuti', 'Tidak Mengikuti'] as $key => $value)
                                            <option value="{{ $key + 1 }}" {{ selected($paud->februari, $key + 1) }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Maret</label>
                                <div class="col-sm-12">
                                    <select class="form-control input-sm required" name="maret">
                                        <option value="">Pilih Status</option>
                                        @foreach (['Belum', 'Mengikuti', 'Tidak Mengikuti'] as $key => $value)
                                            <option value="{{ $key + 1 }}" {{ selected($paud->maret, $key + 1) }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">April</label>
                                <div class="col-sm-12">
                                    <select class="form-control input-sm required" name="april">
                                        <option value="">Pilih Status</option>
                                        @foreach (['Belum', 'Mengikuti', 'Tidak Mengikuti'] as $key => $value)
                                            <option value="{{ $key + 1 }}" {{ selected($paud->april, $key + 1) }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Mei</label>
                                <div class="col-sm-12">
                                    <select class="form-control input-sm required" name="mei">
                                        <option value="">Pilih Status</option>
                                        @foreach (['Belum', 'Mengikuti', 'Tidak Mengikuti'] as $key => $value)
                                            <option value="{{ $key + 1 }}" {{ selected($paud->mei, $key + 1) }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Juni</label>
                                <div class="col-sm-12">
                                    <select class="form-control input-sm required" name="juni">
                                        <option value="">Pilih Status</option>
                                        @foreach (['Belum', 'Mengikuti', 'Tidak Mengikuti'] as $key => $value)
                                            <option value="{{ $key + 1 }}" {{ selected($paud->juni, $key + 1) }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Juli</label>
                                <div class="col-sm-12">
                                    <select class="form-control input-sm required" name="juli">
                                        <option value="">Pilih Status</option>
                                        @foreach (['Belum', 'Mengikuti', 'Tidak Mengikuti'] as $key => $value)
                                            <option value="{{ $key + 1 }}" {{ selected($paud->juli, $key + 1) }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Agustus</label>
                                <div class="col-sm-12">
                                    <select class="form-control input-sm required" name="agustus">
                                        <option value="">Pilih Status</option>
                                        @foreach (['Belum', 'Mengikuti', 'Tidak Mengikuti'] as $key => $value)
                                            <option value="{{ $key + 1 }}" {{ selected($paud->agustus, $key + 1) }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">September</label>
                                <div class="col-sm-12">
                                    <select class="form-control input-sm required" name="september">
                                        <option value="">Pilih Status</option>
                                        @foreach (['Belum', 'Mengikuti', 'Tidak Mengikuti'] as $key => $value)
                                            <option value="{{ $key + 1 }}" {{ selected($paud->september, $key + 1) }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Oktober</label>
                                <div class="col-sm-12">
                                    <select class="form-control input-sm required" name="oktober">
                                        <option value="">Pilih Status</option>
                                        @foreach (['Belum', 'Mengikuti', 'Tidak Mengikuti'] as $key => $value)
                                            <option value="{{ $key + 1 }}" {{ selected($paud->oktober, $key + 1) }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">November</label>
                                <div class="col-sm-12">
                                    <select class="form-control input-sm required" name="november">
                                        <option value="">Pilih Status</option>
                                        @foreach (['Belum', 'Mengikuti', 'Tidak Mengikuti'] as $key => $value)
                                            <option value="{{ $key + 1 }}" {{ selected($paud->november, $key + 1) }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Desember</label>
                                <div class="col-sm-12">
                                    <select class="form-control input-sm required" name="desember">
                                        <option value="">Pilih Status</option>
                                        @foreach (['Belum', 'Mengikuti', 'Tidak Mengikuti'] as $key => $value)
                                            <option value="{{ $key + 1 }}" {{ selected($paud->desember, $key + 1) }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
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
        $('input[name=umur_bulan]').bind('keyup mouseup', function() {
            if (this.value >= 6) {
                $('#pemberian_imunisasi_campak').prop("disabled", false);
            } else {
                $('#pemberian_imunisasi_campak').prop("disabled", true);
            }
        });
    </script>
@endpush
