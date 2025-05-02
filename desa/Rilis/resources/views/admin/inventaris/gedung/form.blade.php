@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.asset_numeral')
@include('admin.layouts.components.datetime_picker')

@section('title')
    <h1>
        {{ $action }} Inventaris Gedung Dan Bangunan
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $action }} Inventaris Gedung Dan Bangunan</li>
@endsection

@push('css')
    <style type="text/css">
        .disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>
@endpush

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-sm-3">
            @include('admin.inventaris.menu')
        </div>
        <div class="col-sm-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ site_url('inventaris_gedung') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Inventaris Gedung Dan Bangunan</a>
                </div>
                {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="nama_barang">Nama Barang / Jenis Barang</label>
                                <div class="col-sm-8">
                                    <select class="form-control input-sm select2" id="nama_barang" name="nama_barang" onchange="formAction('main')">
                                        @foreach ($aset as $data)
                                            <option data-nama="{{ $data['nama'] }}" value="{{ $data['nama'] . '_' . $data['golongan'] . '.' . $data['bidang'] . '.' . $data['kelompok'] . '.' . $data['sub_kelompok'] . '.' . $data['sub_sub_kelompok'] . '.' . $hasil }}" @selected($main->nama_barang == $data['nama'])>Kode Reg
                                                : {{ $data['golongan'] . '.' . $data['bidang'] . '.' . $data['kelompok'] . '.' . $data['sub_kelompok'] . '.' . $data['sub_sub_kelompok'] . ' - ' . $data['nama'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="kode_barang">Kode Barang</label>
                                <div class="col-sm-8">
                                    <input type="hidden" name="nama_barang_save" id="nama_barang_save">
                                    <input maxlength="50" value="{{ $main->kode_barang }}" class="form-control input-sm required" name="kode_barang" id="kode_barang" type="text" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="nomor_register">Nomor Register</label>
                                <div class="col-sm-5">
                                    <input maxlength="50" value="{{ $main->register }}" class="form-control input-sm required" name="register" id="register" type="text" />
                                </div>
                                <div class="col-sm-3">
                                    @if ($action == 'Ubah')
                                        <a style="cursor: pointer;" id="view_modal" name="view_modal">Lihat Kode yang terdaftar</a>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="kondisi_bangunan">Kondisi Bangunan</label>
                                <div class="col-sm-4">
                                    <select name="kondisi_bangunan" id="kondisi_bangunan" class="form-control input-sm required">
                                        <option value="">-- Pilih Kondisi Bangunan --</option>
                                        <option value="Baik" @selected($main->kondisi_bangunan == 'Baik')>Baik</option>
                                        <option value="Rusak Ringan" @selected($main->kondisi_bangunan == 'Rusak Ringan')>Rusak Ringan</option>
                                        <option value="Rusak Sedang" @selected($main->kondisi_bangunan == 'Rusak Sedang')>Rusak Sedang</option>
                                        <option value="Rusak Berat" @selected($main->kondisi_bangunan == 'Rusak Berat')>Rusak Berat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="tingkat">Bangunan Bertingkat</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="number" value="<?= $main->kontruksi_bertingkat ?>" class="form-control input-sm required" id="tingkat" name="tingkat" type="number" />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">Lantai</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="kontruksi">Konstruksi Beton</label>
                                <div class="col-sm-4">
                                    <select name="kontruksi" id="kontruksi" class="form-control input-sm required">
                                        <option value="0" <?= $main->kontruksi_beton == 0 ? 'selected' : '' ?>>Tidak</option>
                                        <option value="1" <?= $main->kontruksi_beton == 1 ? 'selected' : '' ?>>Ya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="luas_bangunan">Luas Bangunan</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input value="<?= $main->luas_bangunan ?>" class="form-control input-sm number required" id="luas_bangunan" name="luas_bangunan" type="text" />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="alamat">Letak / Lokasi </label>
                                <div class="col-sm-8">
                                    <textarea class="form-control input-sm required" name="alamat" id="alamat">{{ $main->letak }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="tahun_pengadaan">Tahun Pengadaan</label>
                                <div class="col-sm-4">
                                    <select name="tahun_pengadaan" id="tahun_pengadaan" class="form-control input-sm required">
                                        @for ($i = date('Y'); $i >= 1900; $i--)
                                            <option value="{{ $i }}" @selected(date('Y', strtotime($main->tanggal_dokument ?? 'now')) == $i)>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="no_bangunan">Nomor Bangunan</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" class="form-control input-sm required" name="no_bangunan" id="no_bangunan" type="text" value="{{ $main->no_dokument }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="tanggal_bangunan">Tanggal Dokumen Bangunan</label>
                                <div class="col-sm-4">
                                    <input maxlength="50" value="{{ date('d-m-Y', strtotime($main->tanggal_dokument ?? 'now')) }}" class="form-control input-sm datepicker required" name="tanggal_bangunan" id="tanggal_bangunan" type="text" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="status_tanah">Status Tanah</label>
                                <div class="col-sm-4">
                                    <select name="status_tanah" id="status_tanah" class="form-control input-sm required">
                                        <option value="">-- Pilih Status Tanah --</option>
                                        <option @selected($main->status_tanah == 'Tanah milik Pemda') value="Tanah milik Pemda">Tanah milik Pemda</option>
                                        <option @selected($main->status_tanah == 'Tanah Negara') value="Tanah Negara">Tanah Negara (Tanah yang dikuasai langsung oleh Negara)</option>
                                        <option @selected($main->status_tanah == 'Tanah Hak Ulayat') value="Tanah Hak Ulayat">Tanah Hak Ulayat (Tanah masyarakat Hukum Adat)</option>
                                        <option @selected($main->status_tanah == 'Tanah Hak') value="Tanah Hak">Tanah Hak (Tanah kepunyaan perorangan atau Badan Hukum)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="luas_tanah">Luas Tanah </label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input class="form-control input-sm number required" id="luas_tanah" name="luas_tanah" type="text" value="{{ $main->luas }}" />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="kode_tanah">Nomor Kode Tanah</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" value="{{ $main->kode_tanah }}" class="form-control input-sm required" name="kode_tanah" id="kode_tanah" type="text" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label required" for="penggunaan_barang">Penggunaan Barang </label>
                                <div class="col-sm-4">
                                    <select name="penggunaan_barang" id="penggunaan_barang" class="form-control input-sm required" placeholder="Hak Tanah">
                                        @foreach (unserialize(PENGGUNAAN_BARANG) as $key => $value)
                                            <option value="{{ $key }}" {{ selected(substr($main->kode_barang, -7, 2), $key) }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="asal_usul">Asal Usul </label>
                                <div class="col-sm-4">
                                    <select name="asal" id="asal" class="form-control input-sm required">
                                        <option value="">-- Pilih Asal Usul --</option>
                                        @foreach (['Bantuan Kabupaten', 'Bantuan Pemerintah', 'Bantuan Provinsi', 'Pembelian Sendiri', 'Sumbangan'] as $item)
                                            <option @selected($item == $main->asal) value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="harga">Harga</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">Rp</span>
                                        <input onkeyup="price()" value="{{ $main->harga }}" class="form-control input-sm number required" id="harga" name="harga" type="text" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-sm required" id="output" name="output" placeholder="" disabled />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
                                <div class="col-sm-8">
                                    <textarea rows="5" class="form-control input-sm required" name="keterangan" id="keterangan">{{ $main->keterangan }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (!$view_mark)
                    <div class="box-footer">
                        <div class="col-xs-12">
                            <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                        </div>
                    </div>
                @endif
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="opensidInventaris" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="opensidInventaris">Kode Yang Terdaftar</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="list-group">
                                @foreach ($kd_reg as $reg)
                                    @if (strlen($reg->register) == 21)
                                        <li class="list-group-item" data-position-id="123">
                                            <div class="companyPosItem">
                                                <span class="companyPosLabel">{{ substr($reg->register, -6) }}</span>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
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
            var id = "{{ $main->id }}";
            var view = "{{ $view_mark }}";
            if (1 == view) {
                $('#validasi').find('input, select, textarea').attr('disabled', 'disabled');
            }

            var kode_desa = "{{ kode_wilayah($get_kode['kode_desa']) }}";
            $('#kode_barang').val(kode_desa + "." + $('#penggunaan_barang').val() + "." + $('#tahun_pengadaan').val());
            $("#tahun_pengadaan").change(function() {
                $('#kode_barang').val(kode_desa + "." + $('#penggunaan_barang').val() + "." + $('#tahun_pengadaan').val());
            });

            $("#penggunaan_barang").change(function() {
                $('#kode_barang').val(kode_desa + "." + $('#penggunaan_barang').val() + "." + $('#tahun_pengadaan').val());
            });
            price();

            $("#nama_barang").change(function() {
                if ($('#register').val().length != 21) {
                    $('#register').val($('#nama_barang').val().split('_').pop());
                } else {
                    $('#register').val($('#nama_barang').val().split('_').pop() + $('#register').val().slice(-6));
                }
                $('#nama_barang_save').val($('#nama_barang').find(':selected').data('nama'));
            });

            if (!id) {
                $("#tahun_pengadaan").change();
                $("#penggunaan_barang").change();
                $("#nama_barang").change();
            }
        });

        function price() {
            $('#output').val(numeral($('#harga').val()).format('Rp0,0'));
        }

        $("#view_modal").click(function(event) {
            $('#modal').modal("show");
        });
    </script>
@endpush
