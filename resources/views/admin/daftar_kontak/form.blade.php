@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Kontak {{ $navigasi }}
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ $_SERVER['HTTP_REFERER'] }}">Kontak {{ $navigasi }}</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div class="col-md-3">
            @include('admin.daftar_kontak.navigasi')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke {{ $navigasi }}
                    </a>
                </div>
                {!! form_open($formAction, 'class="form-horizontal" id="validasi"') !!}
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nama</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-sm nama required" name="nama" placeholder="OpenDesa" value="{{ $daftarKontak->nama }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Cara Hubung Warga</label>
                        <div class="col-sm-8">
                            <select class="form-control input-sm required" id="hubung_warga" name="hubung_warga" onchange="jenis(this.value);">
                                <option value="">Pilih Cara Hubungi</option>
                                <?php foreach (['SMS', 'Email', 'Telegram'] as $value) : ?>
                                <?php
                                if ((bool) setting('aktifkan_sms') === false && $value === 'SMS') {
                                    continue;
                                }
                                ?>
                                <option value="<?= $value ?>" <?= selected($daftarKontak['hubung_warga'], $value) ?>><?= $value ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Telepon</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-sm number" id="telepon" name="telepon" placeholder="081234567890" value="{{ $daftarKontak->telepon }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-sm email" id="email" name="email" placeholder="opendesa@mail.com" value="{{ $daftarKontak->email }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Telegram</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control input-sm number" id="telegram" name="telegram" placeholder="2076476442" value="{{ $daftarKontak->telegram }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Keterangan</label>
                        <div class="col-sm-8">
                            <textarea name="keterangan" class="form-control input-sm" rows="5" placeholder="Keterangan lainnya...">{{ $daftarKontak->keterangan }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#hubung_warga').change();
        });

        function jenis(tipe) {
            $('#telepon').removeClass('required');
            $('#email').removeClass('required');
            $('#telegram').removeClass('required');

            if (tipe == 'SMS') {
                $('#telepon').addClass('required');
            } else if (tipe == 'Email') {
                $('#email').addClass('required');
            } else if (tipe == 'Telegram') {
                $('#telegram').addClass('required');
            }
        }
    </script>
@endpush
