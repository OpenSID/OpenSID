@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.jquery_ui')
@include('admin.layouts.components.datetime_picker')

@section('title')
    <h1>
        {{ $action }} Inventaris Asset
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">{{ $action }} Inventaris Asset</li>
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
                        <a href="{{ site_url('inventaris_asset') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Inventaris Asset</a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label " style="text-align:left;" for="nama_barang">Nama
                                        Barang / Jenis Barang</label>
                                    <div class="col-sm-8">
                                        @if ($action == 'Ubah')
                                            <input type="hidden" id="id" name="id" value="{{ $main->id }}">
                                        @endif
                                        <select class="form-control input-sm select2" id="nama_barang" name="nama_barang" @disabled($view_mark) onchange="formAction('main')">
                                            @foreach ($aset as $data)
                                                <option value="{{ $data['nama'] . '_' . $data['golongan'] . '.' . $data['bidang'] . '.' . $data['kelompok'] . '.' . $data['sub_kelompok'] . '.' . $data['sub_sub_kelompok'] . '.' . $hasil }}" @selected($main->nama_barang == $data['nama'])>Kode Reg :
                                                    {{ $data['golongan'] . '.' . $data['bidang'] . '.' . $data['kelompok'] . '.' . $data['sub_kelompok'] . '.' . $data['sub_sub_kelompok'] . ' - ' . $data['nama'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="kode_barang">Kode
                                        Barang</label>
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
                                            <a style="cursor: pointer;" id="view_modal" name="view_modal">Lihat Kode yang
                                                terdaftar</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="jenis_asset">Jenis
                                        Asset</label>
                                    <div class="col-sm-4">
                                        <select name="jenis_asset" @disabled($view_mark) id="jenis_asset" class="form-control input-sm  required">
                                            <option value="">-- Pilih Jenis Asset --</option>
                                            <option value="Buku" @selected('Buku' == $main->jenis)>Buku</option>
                                            <option value="Barang Kesenian" @selected('Barang Kesenian' == $main->jenis)>Barang Kesenian</option>
                                            <option value="Hewan Ternak" @selected('Hewan Ternak' == $main->jenis)>Hewan
                                                Ternak</option>
                                            <option value="Tumbuhan" @selected('Tumbuhan' == $main->jenis)>Tumbuhan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group judul">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="judul">Judul dan
                                        Pencipta Buku</label>
                                    <div class="col-sm-8">
                                        <input class="form-control input-sm" id="judul" @disabled($view_mark) value="{{ $main->judul_buku }}" name="judul" type="text" />
                                    </div>
                                </div>

                                <div class="form-group spesifikasi">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="spesifikasi">Spesifikasi Buku</label>
                                    <div class="col-sm-8">
                                        <input class="form-control input-sm" id="spesifikasi" name="spesifikasi" type="text" @disabled($view_mark) value="{{ $main->spesifikasi_buku }}" />
                                    </div>
                                </div>
                                <div class="form-group asal_kesenian">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="asal_kesenian">Asal
                                        Daerah Kesenian</label>
                                    <div class="col-sm-8">
                                        <input class="form-control input-sm" id="asal_kesenian" name="asal_kesenian" type="text" @disabled($view_mark) value="{{ $main->asal_daerah }}" />
                                    </div>
                                </div>
                                <div class="form-group pencipta_kesenian">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="pencipta_kesenian">Pencipta Kesenian </label>
                                    <div class="col-sm-8">
                                        <input class="form-control input-sm" id="pencipta_kesenian" @disabled($view_mark) value="{{ $main->pencipta }}" name="pencipta_kesenian" type="text" />
                                    </div>
                                </div>
                                <div class="form-group bahan_kesenian">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="bahan_kesenian">Bahan Kesenian</label>
                                    <div class="col-sm-8">
                                        <input class="form-control input-sm" id="bahan_kesenian" name="bahan_kesenian" type="text" @disabled($view_mark) value="{{ $main->bahan }}" />
                                    </div>
                                </div>
                                <div class="form-group jenis_hewan">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="jenis_hewan">Jenis
                                        Hewan Ternak</label>
                                    <div class="col-sm-8">
                                        <input class="form-control input-sm" id="jenis_hewan" name="jenis_hewan" type="text" @disabled($view_mark) value="{{ $main->jenis_hewan }}" />
                                    </div>
                                </div>
                                <div class="form-group ukuran_hewan">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="ukuran_hewan">Ukuran
                                        Hewan Ternak</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <input class="form-control input-sm number" id="ukuran_hewan" name="ukuran_hewan" type="text" @disabled($view_mark) value="{{ $main->ukuran_hewan }}" />
                                            <span class="input-group-addon input-sm" id="ukuran_hewan-addon">Kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group jenis_tumbuhan">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="jenis_tumbuhan">Jenis Tumbuhan</label>
                                    <div class="col-sm-8">
                                        <input class="form-control input-sm" id="jenis_tumbuhan" @disabled($view_mark) value="{{ $main->jenis_tumbuhan }}" name="jenis_tumbuhan" type="text" />
                                    </div>
                                </div>
                                <div class="form-group ukuran_tumbuhan">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="ukuran_tumbuhan">Ukuran Tumbuhan</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <input class="form-control input-sm number" id="ukuran_tumbuhan" name="ukuran_tumbuhan" type="text" @disabled($view_mark) value="{{ $main->ukuran_tumbuhan }}" />
                                            <span class="input-group-addon input-sm" id="ukuran_tumbuhan">M</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="jumlah">Jumlah</label>
                                    <div class="col-sm-4">
                                        <input class="form-control input-sm number required" id="jumlah" name="jumlah" type="text" @disabled($view_mark) value="{{ $main->jumlah }}" />
                                    </div>
                                </div>

                                {{-- TODO:: data ini tidak tersimpan di database --}}
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" style="text-align:left;" for="tahun_pengadaan">Tahun Pembelian</label>
                                    <div class="col-sm-4">
                                        <select name="tahun_pengadaan" id="tahun_pengadaan" class="form-control input-sm required" @disabled($view_mark)>
                                            @for ($i = date('Y'); $i >= 1900; $i--)
                                                <option value="{{ $i }}" @selected(date('Y', strtotime($main->tahun_pengadaan ?? date('Y'))) == $i)>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label required" style="text-align:left;" for="hak_tanah">Penggunaan Barang </label>
                                    <div class="col-sm-4">
                                        <select name="penggunaan_barang" id="penggunaan_barang" class="form-control input-sm required" placeholder="Hak Tanah" @disabled($view_mark)>
                                            <?php foreach (unserialize(PENGGUNAAN_BARANG) as $key => $value) : ?>
                                            <option value="<?= $key ?>" <?= selected(substr($main->kode_barang, -7, 2), $key) ?>>
                                                <?= $value ?>
                                            </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label " style="text-align:left;" for="asal">Asal Usul
                                    </label>
                                    <div class="col-sm-8">
                                        <select name="asal" id="asal" class="form-control input-sm required" @disabled($view_mark)>
                                            <option value="">-- Pilih Asal Usul Lahan --</option>
                                            <option value="Bantuan Kabupaten" @selected($main->asal == 'Bantuan Kabupaten')>Bantuan Kabupaten</option>
                                            <option value="Bantuan Pemerintah" @selected($main->asal == 'Bantuan Pemerintah')>Bantuan Pemerintah</option>
                                            <option value="Bantuan Provinsi" @selected($main->asal == 'Bantuan Provinsi')>Bantuan Provinsi</option>
                                            <option value="Pembelian Sendiri" @selected($main->asal == 'Pembelian Sendiri')>Pembelian Sendiri</option>
                                            <option value="Sumbangan" @selected($main->asal == 'Sumbangan')>Sumbangan
                                            </option>
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
                            <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                                Batal</button>
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                                Simpan</button>
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
            $(".judul").hide();
            $(".spesifikasi").hide();
            $(".asal_kesenian").hide();
            $(".pencipta_kesenian").hide();
            $(".bahan_kesenian").hide();
            $(".jenis_hewan").hide();
            $(".ukuran_hewan").hide();
            $(".jenis_tumbuhan").hide();
            $(".ukuran_tumbuhan").hide();
            @if ($action == 'Ubah')
                if ($("#jenis_asset").val() == "Buku") {
                    $(".judul").show();
                    $(".spesifikasi").show();
                    $(".asal_kesenian").hide();
                    $(".pencipta_kesenian").hide();
                    $(".bahan_kesenian").hide();
                    $(".jenis_hewan").hide();
                    $(".ukuran_hewan").hide();
                    $(".jenis_tumbuhan").hide();
                    $(".ukuran_tumbuhan").hide();
                } else if ($("#jenis_asset").val() == "Barang Kesenian") {
                    $(".judul").hide();
                    $(".spesifikasi").hide();
                    $(".asal_kesenian").show();
                    $(".pencipta_kesenian").show();
                    $(".bahan_kesenian").show();
                    $(".jenis_hewan").hide();
                    $(".ukuran_hewan").hide();
                    $(".jenis_tumbuhan").hide();
                    $(".ukuran_tumbuhan").hide();
                } else if ($("#jenis_asset").val() == "Hewan Ternak") {
                    $(".judul").hide();
                    $(".spesifikasi").hide();
                    $(".asal_kesenian").hide();
                    $(".pencipta_kesenian").hide();
                    $(".bahan_kesenian").hide();
                    $(".jenis_hewan").show();
                    $(".ukuran_hewan").show();
                    $(".jenis_tumbuhan").hide();
                    $(".ukuran_tumbuhan").hide();
                } else if ($("#jenis_asset").val() == "Tumbuhan") {
                    $(".judul").hide();
                    $(".spesifikasi").hide();
                    $(".asal_kesenian").hide();
                    $(".pencipta_kesenian").hide();
                    $(".bahan_kesenian").hide();
                    $(".jenis_hewan").hide();
                    $(".ukuran_hewan").hide();
                    $(".jenis_tumbuhan").show();
                    $(".ukuran_tumbuhan").show();
                }
                console.log(123);
            @endif
            $("#jenis_asset").change(function() {
                if ($("#jenis_asset").val() == "Buku") {
                    $(".judul").show();
                    $(".spesifikasi").show();
                    $(".asal_kesenian").hide();
                    $(".pencipta_kesenian").hide();
                    $(".bahan_kesenian").hide();
                    $(".jenis_hewan").hide();
                    $(".ukuran_hewan").hide();
                    $(".jenis_tumbuhan").hide();
                    $(".ukuran_tumbuhan").hide();
                } else if ($("#jenis_asset").val() == "Barang Kesenian") {
                    $(".judul").hide();
                    $(".spesifikasi").hide();
                    $(".asal_kesenian").show();
                    $(".pencipta_kesenian").show();
                    $(".bahan_kesenian").show();
                    $(".jenis_hewan").hide();
                    $(".ukuran_hewan").hide();
                    $(".jenis_tumbuhan").hide();
                    $(".ukuran_tumbuhan").hide();
                } else if ($("#jenis_asset").val() == "Hewan Ternak") {
                    $(".judul").hide();
                    $(".spesifikasi").hide();
                    $(".asal_kesenian").hide();
                    $(".pencipta_kesenian").hide();
                    $(".bahan_kesenian").hide();
                    $(".jenis_hewan").show();
                    $(".ukuran_hewan").show();
                    $(".jenis_tumbuhan").hide();
                    $(".ukuran_tumbuhan").hide();
                } else if ($("#jenis_asset").val() == "Tumbuhan") {
                    $(".judul").hide();
                    $(".spesifikasi").hide();
                    $(".asal_kesenian").hide();
                    $(".pencipta_kesenian").hide();
                    $(".bahan_kesenian").hide();
                    $(".jenis_hewan").hide();
                    $(".ukuran_hewan").hide();
                    $(".jenis_tumbuhan").show();
                    $(".ukuran_tumbuhan").show();
                }
            });

            var kode_desa = "{{ kode_wilayah($get_kode['kode_desa']) }}";
            $('#kode_barang').val(kode_desa + "." + $('#penggunaan_barang').val() + "." + $('#tahun_pengadaan').val());
            $("#tahun_pengadaan").change(function() {
                $('#kode_barang').val(kode_desa + "." + $('#penggunaan_barang').val() + "." + $('#tahun_pengadaan').val());
            });

            $("#nama_barang").change(function() {
                $('#register').val($('#nama_barang').val().split("_").pop());
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
