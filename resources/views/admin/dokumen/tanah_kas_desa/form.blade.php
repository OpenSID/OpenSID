{{-- @include('admin.layouts.components.validasi_form') --}}

<form class="form-horizontal" id="validasi" name="form_tanah_kas" method="post" action="{{ $form_action }}">
    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ site_url('bumindes_tanah_kas_desa') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left"></i> Kembali Ke Buku Tanah Kas {{ ucwords(setting('sebutan_desa')) }}</a>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" id="id" name="id" value="{{ $main->id }}">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" style="text-align:left;" for="pemilik_asal">Asal Tanah Kas
                            Desa</label>
                        <div class="col-sm-4">
                            <select name="pemilik_asal" id="pemilik_asal" class="form-control input-sm select2 required" onchange="pilih_asal_tanah(this.value)">
                                <option value>-- Pilih Asal Tanah--</option>
                                @foreach ($list_asal_tanah as $item)
                                    <option value="{{ $item['id'] }}" @selected($item['id'] == $main->nama_pemilik_asal)>{{ $item['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" style="text-align:left;" for="kode_barang">Nomor Sertifikat Buku
                            Letter C / Persil</label>
                        <div class="col-sm-4">
                            <input type="text" min="0" class="form-control input-sm number required" id="letter_c_persil" name="letter_c_persil" value="{{ $main->letter_c }}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" style="text-align:left;" for="nomor_register">Kelas</label>
                        <div class="col-sm-4">
                            <select name="kelas" id="kelas" class="form-control input-sm select2 required" placeholder="Kelas">
                                <option value>-- Pilih Tipe Tanah--</option>
                                @foreach ($persil as $item)
                                    :
                                    <option value="{{ $item['id'] }}" @selected($item['id'] == $main->kelas)>{{ $item['kode'] . ' ' . $item['ndesc'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label required" style="text-align:left;" for="tanggal_sertifikat">Tanggal
                            Perolehan</label>
                        <div class="col-sm-4">
                            <input
                                maxlength="50"
                                class="form-control input-sm required"
                                name="tanggal_perolehan"
                                id="tanggal_perolehan"
                                type="date"
                                placeholder="Tanggal Sertifikat"
                                value="{{ $main->tanggal_perolehan }}"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" style="text-align:left;" for="luas_tanah">Luas Tanah Total</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input
                                    type="text"
                                    onkeyup="isi_luas()"
                                    min="0"
                                    class="form-control input-sm number required"
                                    value="{{ $main->luas ?: 0 }}"
                                    id="luas"
                                    name="luas"
                                />
                                <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                            </div>
                        </div>
                    </div>
                    <div id="input_luas" {{ jecho($main, false, 'style="display:none"') }}>
                        <div class="col-sm-12">
                            <div class="form-group subtitle_head">
                                <label class="text-right"><strong>Perolehan TKD :</strong></label>
                            </div>
                        </div>
                        <div class="col-sm-12" id="view_label_asal_tanah" style="display: none;">
                            <p class="text-center">Pilih Asal Tanah</p>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" style="text-align:left;">Luas Tanah Total</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input disabled value="{{ $main->luas ?: 0 }}" id="luas_perolehan_tkd" />
                                        <span class="input-group-addon input-sm">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3" id="view_asli_milik_desa">
                            <div class="form-group dinamic_perolehan">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="asli_milik_desa">Asli Milik Desa</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input
                                            onkeyup="dinamic_perolehan()"
                                            type="text"
                                            min="0"
                                            class="form-control input-sm number required"
                                            value="{{ $main->asli_milik_desa ?: 0 }}"
                                            id="asli_milik_desa"
                                            name="asli_milik_desa"
                                        />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3" id="view_pemerintah">
                            <div class="form-group dinamic_perolehan">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="pemerintah">Bantuan Pemerintah</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input
                                            onkeyup="dinamic_perolehan()"
                                            type="text"
                                            min="0"
                                            class="form-control input-sm number required"
                                            value="{{ $main->pemerintah ?: 0 }}"
                                            id="pemerintah"
                                            name="pemerintah"
                                        />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3" id="view_provinsi">
                            <div class="form-group dinamic_perolehan">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="provinsi">Bantuan Provinsi</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input
                                            onkeyup="dinamic_perolehan()"
                                            type="text"
                                            min="0"
                                            class="form-control input-sm number required"
                                            value="{{ $main->provinsi ?: 0 }}"
                                            id="provinsi"
                                            name="provinsi"
                                        />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3" id="view_kabupaten_kota">
                            <div class="form-group dinamic_perolehan">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="kabupaten_kota">Bantuan Kabupatan
                                    / Kota</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input
                                            onkeyup="dinamic_perolehan()"
                                            type="text"
                                            min="0"
                                            class="form-control input-sm number required"
                                            value="{{ $main->kabupaten_kota ?: 0 }}"
                                            id="kabupaten_kota"
                                            name="kabupaten_kota"
                                        />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3" id="view_lain_lain">
                            <div class="form-group dinamic_perolehan">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="lain_lain">Lain - lain</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input
                                            onkeyup="dinamic_perolehan()"
                                            type="text"
                                            min="0"
                                            class="form-control input-sm number required"
                                            value="{{ $main->lain_lain ?: 0 }}"
                                            id="lain_lain"
                                            name="lain_lain"
                                        />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <code id="catatan_perolehan_tkd"></code>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group subtitle_head">
                                <label class="text-right"><strong>Jenis TKD :</strong></label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" style="text-align:left;">Luas Tanah Total</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input disabled value="{{ $main->luas ?: 0 }}" id="luas_jenis_tkd" />
                                        <span class="input-group-addon input-sm">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group dinamic_jenis_tkd">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="sawah">Sawah</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input
                                            onkeyup="dinamic_jenis_tkd()"
                                            type="text"
                                            min="0"
                                            class="form-control input-sm number required"
                                            value="{{ $main->sawah ?: 0 }}"
                                            id="sawah"
                                            name="sawah"
                                        />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group dinamic_jenis_tkd">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="tegal">Tegal</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input
                                            onkeyup="dinamic_jenis_tkd()"
                                            type="text"
                                            min="0"
                                            class="form-control input-sm number required"
                                            value="{{ $main->tegal ?: 0 }}"
                                            id="tegal"
                                            name="tegal"
                                        />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group dinamic_jenis_tkd">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="kebun">Kebun</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input
                                            onkeyup="dinamic_jenis_tkd()"
                                            type="text"
                                            min="0"
                                            class="form-control input-sm number required"
                                            value="{{ $main->kebun ?: 0 }}"
                                            id="kebun"
                                            name="kebun"
                                        />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group dinamic_jenis_tkd">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="tambak_kolam">Tambak / Kolam</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input
                                            onkeyup="dinamic_jenis_tkd()"
                                            type="text"
                                            min="0"
                                            class="form-control input-sm number required"
                                            value="{{ $main->tambak_kolam ?: 0 }}"
                                            id="tambak_kolam"
                                            name="tambak_kolam"
                                        />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group dinamic_jenis_tkd">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="tanah_kering_darat">Tanah Kering
                                    / Darat</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input
                                            onkeyup="dinamic_jenis_tkd()"
                                            type="text"
                                            min="0"
                                            class="form-control input-sm number required"
                                            value="{{ $main->tanah_kering_darat ?: 0 }}"
                                            id="tanah_kering_darat"
                                            name="tanah_kering_darat"
                                        />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <code id="catatan_jenis_tkd"></code>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group subtitle_head">
                                <label class="text-right"><strong>Patok Tanda Batas :</strong></label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="ada_patok">Ada Patok Tanda
                                    Batas</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="text" min="0" class="form-control input-sm number required" value="{{ $main->ada_patok ?: 0 }}" id="ada_patok" name="ada_patok" />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="tidak_ada_patok">Tidak Ada Patok
                                    Tanda Batas</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="text" min="0" class="form-control input-sm number required" value="{{ $main->tidak_ada_patok ?: 0 }}" id="tidak_ada_patok" name="tidak_ada_patok" />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group subtitle_head">
                                <label class="text-right"><strong>Papan Nama :</strong></label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="ada_papan_nama">Ada Papan
                                    Nama</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="text" min="0" class="form-control input-sm number required" value="{{ $main->ada_papan_nama ?: 0 }}" id="ada_papan_nama" name="ada_papan_nama" />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="col-sm-12 control-label" style="text-align:left;" for="tidak_ada_papan_nama">Tidak Ada
                                    Papan Nama</label>
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="text" min="0" class="form-control input-sm number required" value="{{ $main->tidak_ada_papan_nama ?: 0 }}" id="tidak_ada_papan_nama" name="tidak_ada_papan_nama" />
                                        <span class="input-group-addon input-sm" id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group subtitle_head">
                            <label class="text-right"><strong>Catatan :</strong></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" style="text-align:left;" for="peruntukan">Pemanfaatan</label>
                        <div class="col-sm-4">
                            <select name="peruntukan" id="peruntukan" class="form-control input-sm required">
                                <option value>-- Pemanfaatan Tanah--</option>
                                @foreach ($list_peruntukan as $item)
                                    <option value="{{ $item['id'] }}" @selected($item['id'] == $main->peruntukan)>{{ $item['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" style="text-align:left;" for="lokasi">Lokasi</label>
                        <div class="col-sm-8">
                            <textarea rows="5" class="form-control input-sm nomor_sk" name="lokasi" id="lokasi" placeholder="Lokasi">{{ $main->lokasi }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" style="text-align:left;" for="mutasi">Mutasi</label>
                        <div class="col-sm-8">
                            <textarea rows="5" class="form-control input-sm nomor_sk" name="mutasi" id="mutasi" placeholder="Mutasi">{{ $main->mutasi }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
                        <div class="col-sm-8">
                            <textarea rows="5" class="form-control input-sm nomor_sk" name="keterangan" id="keterangan" placeholder="Keterangan">{{ $main->keterangan }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="form_footer" class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>Batal</button>
            <button type="button" onclick="submit_form()" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
        </div>
    </div>
</form>
@push('scripts')
    <script src="{{ asset('js/validasi.js') }}"></script>
    <script>
        $('document').ready(function() {
            var view = "{{ $view_mark }}";
            console.log(view);
            var asal = "{{ $asal_tanah }}";

            if (1 == view) {
                $("#pemilik_asal").attr("disabled", true);
                $("#letter_c_persil").attr("disabled", true);
                $("#kelas").attr("disabled", true);
                $("#tanggal_perolehan").attr("disabled", true);
                $("#luas").attr("disabled", true);
                $("#asli_milik_desa").attr("disabled", true);
                $("#pemerintah").attr("disabled", true);
                $("#provinsi").attr("disabled", true);
                $("#kabupaten_kota").attr("disabled", true);
                $("#lain_lain").attr("disabled", true);
                $("#sawah").attr("disabled", true);
                $("#tegal").attr("disabled", true);
                $("#kebun").attr("disabled", true);
                $("#tambak_kolam").attr("disabled", true);
                $("#tanah_kering_darat").attr("disabled", true);
                $("#ada_patok").attr("disabled", true);
                $("#tidak_ada_patok").attr("disabled", true);
                $("#ada_papan_nama").attr("disabled", true);
                $("#tidak_ada_papan_nama").attr("disabled", true);
                $("#peruntukan").attr("disabled", true);
                $("#lokasi").attr("disabled", true);
                $("#mutasi").attr("disabled", true);
                $("#keterangan").attr("disabled", true);
                $('#form_footer').hide();
                show_hide(asal);
            } else if (view == 2) {
                show_hide(asal);
            } else {
                $("#view_asli_milik_desa").hide();
                $("#view_pemerintah").hide();
                $("#view_provinsi").hide();
                $("#view_kabupaten_kota").hide();
                $("#view_lain_lain").hide();
            }
        });

        function show_hide(param) {
            if (1 == param) {
                $("#view_asli_milik_desa").show();
                $("#view_pemerintah").hide();
                $("#view_provinsi").hide();
                $("#view_kabupaten_kota").hide();
                $("#view_lain_lain").hide();
            } else if (2 == param) {
                $("#view_asli_milik_desa").hide();
                $("#view_pemerintah").show();
                $("#view_provinsi").show();
                $("#view_kabupaten_kota").show();
                $("#view_lain_lain").hide();
            } else {
                $("#view_asli_milik_desa").hide();
                $("#view_pemerintah").hide();
                $("#view_provinsi").hide();
                $("#view_kabupaten_kota").hide();
                $("#view_lain_lain").show();
            }
        }

        function isi_luas() {
            var luas = $('#luas').val();

            if (parseInt(luas) >= 0 && $('#pemilik_asal').val()) {
                $('#input_luas').show();
            } else {
                $('#input_luas').hide();
            }

            $('#luas_perolehan_tkd').val(luas);
            $('#luas_jenis_tkd').val(luas);
        }

        function dinamic_perolehan() {
            var luas = $('#luas').val();

            var res = 0;
            res = parseFloat($('#asli_milik_desa').val()) +
                parseFloat($('#pemerintah').val()) +
                parseFloat($('#provinsi').val()) +
                parseFloat($('#kabupaten_kota').val()) +
                parseFloat($('#lain_lain').val());

            return res;
        }

        function dinamic_jenis_tkd() {
            var res = 0;
            res = parseFloat($('#sawah').val()) +
                parseFloat($('#tegal').val()) +
                parseFloat($('#kebun').val()) +
                parseFloat($('#tambak_kolam').val()) +
                parseFloat($('#tanah_kering_darat').val());

            return res;
        }

        function reset_hide_section(param) {
            $("#luas").val(0);
            var field = param.substring(5, param.length);
            $("#" + field).val(0);
            $("#" + param).hide();
        }

        function reset_show_section(param) {
            var field = param.substring(5, param.length);
            $("#" + field).val(0);
            $("#" + param).show();
        }

        function reset_field() {
            $('#sawah').val(0)
            $('#tegal').val(0)
            $('#kebun').val(0)
            $('#tambak_kolam').val(0)
            $('#tanah_kering_darat').val(0)
            $('#ada_patok').val(0)
            $('#tidak_ada_patok').val(0)
            $('#ada_papan_nama').val(0)
            $('#tidak_ada_papan_nama').val(0)
        }

        function pilih_asal_tanah(param) {
            if (1 == param) {
                $("#view_label_asal_tanah").hide();
                $("#luas").attr('disabled', false);
                var hideView = ["view_pemerintah", "view_provinsi", "view_kabupaten_kota", "view_lain_lain"];
                hideView.forEach(reset_hide_section);
                var showView = ["view_asli_milik_desa"];
                showView.forEach(reset_show_section);
                $("#catatan_perolehan_tkd").text('Catatan : Luas Tanah Total = Asli Milik Desa');
                $("#catatan_jenis_tkd").text('Catatan : Luas Tanah Total = sawah + Tegal + Kebun + Tambak / Kolam + Tanah Kering / Darat');

                reset_field();
            } else if (2 == param) {
                $("#view_label_asal_tanah").hide();
                $("#luas").attr('disabled', false);
                var hideView = ["view_asli_milik_desa", "view_lain_lain"];
                hideView.forEach(reset_hide_section);
                var showView = ["view_pemerintah", "view_provinsi", "view_kabupaten_kota"];
                showView.forEach(reset_show_section);
                $("#catatan_perolehan_tkd").text('Catatan : Luas Tanah Total = Bantuan Pemerintah + Bantuan Provinsi + Bantuan Kabupaten / Kota');
                $("#catatan_jenis_tkd").text('Catatan : Luas Tanah Total = sawah + Tegal + Kebun + Tambak / Kolam + Tanah Kering / Darat');

                reset_field();
            } else if (3 == param) {
                $("#view_label_asal_tanah").hide();
                $("#luas").attr('disabled', false);
                var hideView = ["view_asli_milik_desa", "view_pemerintah", "view_provinsi", "view_kabupaten_kota"];
                hideView.forEach(reset_hide_section);
                var showView = ["view_lain_lain"];
                showView.forEach(reset_show_section);
                $("#catatan_perolehan_tkd").text('Catatan : Luas Tanah Total = Lain - lain');
                $("#catatan_jenis_tkd").text('Catatan : Luas Tanah Total = sawah + Tegal + Kebun + Tambak / Kolam + Tanah Kering / Darat');

                reset_field();
            } else {
                $("#view_label_asal_tanah").show();
                $("#luas").attr('disabled', true);
                var hideView = ["view_asli_milik_desa", "view_pemerintah", "view_provinsi", "view_kabupaten_kota", "view_lain_lain"];
                hideView.forEach(reset_hide_section);
                $("#catatan_perolehan_tkd").text('');
                $("#catatan_jenis_tkd").text('');

                reset_field();
            }

            isi_luas();
        }

        function submit_form() {
            var luas = $('#luas').val();
            var dinLuas = dinamic_perolehan();
            var dinTKD = dinamic_jenis_tkd();
            $("#notification").remove();
            if (luas != dinLuas) {
                $(".dinamic_perolehan").addClass('has-error');
                notification('error', 'Luas Tanah Total tidak sama dengan Perolehan TKD');
            } else if (luas != dinTKD) {
                $(".dinamic_jenis_tkd").addClass('has-error');
                notification('error', 'Luas Tanah Total tidak sama dengan Jenis TKD');
            } else {
                $("#validasi").submit();
            }
        }
    </script>
@endpush
