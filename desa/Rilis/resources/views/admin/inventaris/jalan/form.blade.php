@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.jquery_ui')
@include('admin.layouts.components.datetime_picker')

@section('title')
    <h1>
        {{ $action }} Inventaris Jalan
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $action }} Inventaris Jalan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-3">
            @include('admin.inventaris.menu')
        </div>
        <div class="col-md-9">
            <form class="form-horizontal" id="validasi" name="form_jalan" method="post" action="{{ $form_action }}">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <a href="{{ site_url('inventaris_jalan') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Inventaris Jalan</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label " style="text-align:left;" for="nama_barang">Nama Barang / Jenis Barang</label>
                                    <div class="col-sm-8">
                                        @if ($action == 'Ubah')
                                            <input type="hidden" id="id" name="id" value="{{ $main->id }}">
                                        @endif
                                        <select class="form-control input-sm select2" id="nama_barang" name="nama_barang" @disabled($view_mark) onchange="formAction('main')">
                                            @foreach ($aset as $data)
                                                <option value="{{ $data['nama'] . '_' . $data['golongan'] . '.' . $data['bidang'] . '.' . $data['kelompok'] . '.' . $data['sub_kelompok'] . '.' . $data['sub_sub_kelompok'] . '.' . $hasil }}" @selected($main->nama_barang == $data['nama'])>Kode Reg :
                                                    {{ $data['golongan'] . '.' . $data['bidang'] . '.' . $data['kelompok'] . '.' . $data['sub_kelompok'] . '.' . $data['sub_sub_kelompok'] . ' - ' . $data['nama'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="kode_barang">Kode Barang</label>
                                    <div class="col-sm-8">
                                        <input
                                            maxlength="50"
                                            value="{{ $main->kode_barang }}"
                                            @disabled($view_mark)
                                            class="form-control input-sm required"
                                            name="kode_barang"
                                            id="kode_barang"
                                            type="text"
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="nomor_register">Nomor Register</label>
                                    <div class="col-sm-5">
                                        <input
                                            maxlength="50"
                                            value="{{ $main->register }}"
                                            @disabled($view_mark)
                                            class="form-control input-sm required"
                                            name="register"
                                            id="register"
                                            type="text"
                                            placeholder="Nomor Register"
                                        />
                                    </div>
                                    <div class="col-sm-3">
                                        @if ($action == 'Ubah')
                                            <a style="cursor: pointer;" id="view_modal" name="view_modal">Lihat Kode yang terdaftar</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="kondisi">Kondisi Bangunan</label>
                                    <div class="col-sm-4">
                                        <select name="kondisi" id="kondisi" @disabled($view_mark) class="form-control input-sm required">
                                            <option value="">-- Pilih Kondisi --</option>
                                            <option value="Baik" @selected($main->kondisi == 'Baik')>Baik</option>
                                            <option value="Rusak Ringan" @selected($main->kondisi == 'Rusak Ringan')>Rusak Ringan</option>
                                            <option value="Rusak Sedang" @selected($main->kondisi == 'Rusak Sedang')>Rusak Sedang</option>
                                            <option value="Rusak Berat" @selected($main->kondisi == 'Rusak Berat')>Rusak Berat</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="kontruksi">Konstruksi</label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control input-sm required" @disabled($view_mark) name="kontruksi" id="kontruksi">{{ $main->kontruksi }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label " style="text-align:left;" for="panjang">Panjang</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <input value="{{ empty($main->panjang) ? '' : $main->panjang }}" @disabled($view_mark) class="form-control input-sm number required" id="panjang" name="panjang" type="text" />
                                            <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="lebar">Lebar</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <input value="{{ empty($main->lebar) ? '' : $main->lebar }}" class="form-control input-sm number required" @disabled($view_mark) id="lebar" name="lebar" type="text" />
                                            <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label " style="text-align:left;" for="luas">Luas</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <input value="{{ empty($main->luas) ? '' : $main->luas }}" class="form-control input-sm number required" id="luas" name="luas" @disabled($view_mark) type="text" />
                                            <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="alamat">Letak / Lokasi </label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control input-sm required" name="alamat" id="alamat" @disabled($view_mark)>{{ $main->letak }}</textarea>
                                    </div>
                                </div>
                                {{-- TODO:: data ini tidak tersimpan di database --}}
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="tahun_pengadaan">Tahun Pembelian</label>
                                    <div class="col-sm-4">
                                        <select name="tahun_pengadaan" id="tahun_pengadaan" class="form-control input-sm required" @disabled($view_mark)>
                                            @for ($i = date('Y'); $i >= 1900; $i--)
                                                <option value="{{ $i }}" @selected(date('Y', strtotime($main->tanggal_dokument)) == $i)>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label " style="text-align:left;" for="no_bangunan">Nomor Kepemilikan</label>
                                    <div class="col-sm-8">
                                        <input
                                            maxlength="50"
                                            value="{{ empty($main->no_dokument) ? '' : $main->no_dokument }}"
                                            @disabled($view_mark)
                                            class="form-control input-sm required"
                                            name="no_bangunan"
                                            id="no_bangunan"
                                            type="text"
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label " style="text-align:left;" for="tanggal_bangunan">Tanggal Dokumen Kepemilikan</label>
                                    <div class="col-sm-4">
                                        <input
                                            maxlength="50"
                                            type="text"
                                            value="{{ $main->tanggal_dokument }}"
                                            class="form-control input-sm required datepicker"
                                            @disabled($view_mark)
                                            name="tanggal_bangunan"
                                            id="tanggal_bangunan"
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label " style="text-align:left;" for="status_tanah">Status Tanah</label>
                                    <div class="col-sm-8">
                                        <select name="status_tanah" id="status_tanah" class="form-control input-sm required" @disabled($view_mark)>
                                            <option value="">-- Pilih Status Tanah --</option>
                                            <option value="Tanah milik Pemda" @selected($main->status_tanah == 'Tanah milik Pemda')>Tanah milik Pemda</option>
                                            <option value="Tanah Negara" @selected($main->status_tanah == 'Tanah Negara')>Tanah Negara</option>
                                            <option value="Tanah Hak Ulayat" @selected($main->status_tanah == 'Tanah Hak Ulayat')>Tanah Hak Ulayat</option>
                                            <option value="Tanah Hak" @selected($main->status_tanah == 'Tanah Hak')>Tanah Hak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label " style="text-align:left;" for="kode_tanah">Nomor Kode Tanah</label>
                                    <div class="col-sm-8">
                                        <input
                                            maxlength="50"
                                            value="{{ empty($main->kode_tanah) ? '' : $main->kode_tanah }}"
                                            class="form-control input-sm required"
                                            name="kode_tanah"
                                            id="kode_tanah"
                                            type="text"
                                            @disabled($view_mark)
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label required" style="text-align:left;" for="hak_tanah">Penggunaan Barang </label>
                                    <div class="col-sm-4">
                                        <select name="penggunaan_barang" id="penggunaan_barang" class="form-control input-sm required" placeholder="Hak Tanah" required @disabled($view_mark)>
                                            @foreach (unserialize(PENGGUNAAN_BARANG) as $key => $value)
                                                <option value="{{ $key }}" @selected(substr($main->kode_barang, -7, 2) == $key)>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label " style="text-align:left;" for="asal">Asal Usul </label>
                                    <div class="col-sm-8">
                                        <select name="asal" id="asal" class="form-control input-sm required" @disabled($view_mark)>
                                            <option value="">-- Pilih Asal Usul Lahan --</option>
                                            <option value="Bantuan Kabupaten" @selected($main->asal == 'Bantuan Kabupaten')>Bantuan Kabupaten</option>
                                            <option value="Bantuan Pemerintah" @selected($main->asal == 'Bantuan Pemerintah')>Bantuan Pemerintah</option>
                                            <option value="Bantuan Provinsi" @selected($main->asal == 'Bantuan Provinsi')>Bantuan Provinsi</option>
                                            <option value="Pembelian Sendiri" @selected($main->asal == 'Pembelian Sendiri')>Pembelian Sendiri</option>
                                            <option value="Sumbangan" @selected($main->asal == 'Sumbangan')>Sumbangan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="harga">Harga</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">Rp</span>
                                            <input onkeyup="price()" class="form-control input-sm number required" id="harga" name="harga" value="{{ $main->harga }}" @disabled($view_mark) />
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-sm required" id="output" name="output" placeholder="" disabled />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
                                    <div class="col-sm-8">
                                        <textarea rows="5" class="form-control input-sm required" name="keterangan" id="keterangan" @disabled($view_mark)>{{ $main->keterangan }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (!$view_mark)
                        <div class="box-footer">
                            <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                        </div>
                    @endif
                </div>
            </form>
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
                                @php
                                    foreach ($kd_reg as $reg) {
                                        if (strlen($reg->register) == 21) {
                                            echo '<li class="list-group-item" data-position-id="123">
													<div class="companyPosItem">
														<span class="companyPosLabel">' .
                                                substr($reg->register, -6) .
                                                '</span>
													</div>
												</li>';
                                        }
                                    }
                                @endphp
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('admin.layouts.components.asset_numeral')
    <script>
        $(document).ready(function() {
            var kode_desa = "{{ kode_wilayah($get_kode['kode_desa']) }}";
            $('#kode_barang').val(kode_desa + "." + $('#penggunaan_barang').val() + "." + $('#tahun_pengadaan').val());
            $("#tahun_pengadaan").change(function() {
                $('#kode_barang').val(kode_desa + "." + $('#penggunaan_barang').val() + "." + $('#tahun_pengadaan').val());
            });

            $("#penggunaan_barang").change(function() {
                $('#kode_barang').val(kode_desa + "." + $('#penggunaan_barang').val() + "." + $('#tahun_pengadaan').val());
            });
            price();

            $("#tahun_pengadaan").change();
            $("#penggunaan_barang").change();
            $("#nama_barang").change();
        });

        function price() {
            // gunakan format indonesia, Rupiah
            $('#output').val(numeral($('#harga').val()).format('Rp0,0'));
        }

        $(function() {
            $('.select2').select2();
        })

        $("#view_modal").click(function(event) {
            $('#modal').modal("show");
        });
    </script>
@endpush
