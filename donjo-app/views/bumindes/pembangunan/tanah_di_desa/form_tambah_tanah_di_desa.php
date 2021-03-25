<form class="form-horizontal" id="validasi" name="form_tanah" method="post" action="<?= site_url("bumindes_pembangunan/add_tanah_desa"); ?>">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="<?= site_url() ?>bumindes_pembangunan/tables/tanah"
                        class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                            class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Inventaris Tanah</a>
                </div>                
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">                           
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;" for="pemilik_asal">Pemilik</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" class="form-control input-sm required" name="pemilik_asal"
                                        id="pemilik_asal" type="text" placeholder="Pemilik" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;" for="kode_barang">No. Letter C / Persil</label>
                                <div class="col-sm-4">                    
                                    <input maxlength="50" class="form-control input-sm required" name="letter_c"
                                        id="letter_c" type="text" placeholder="No. Letter C" />
                                </div>
                                <div class="col-sm-4">                    
                                    <input maxlength="50" class="form-control input-sm required" name="persil"
                                        id="persil" type="text" placeholder="Persil" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="nomor_register">No. Sertifikat</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" class="form-control input-sm required" name="no_sertif"
                                        id="no_sertif" type="text" placeholder="No. Sertifikat" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;" for="luas_tanah">Luas Tanah</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="number" min="0" class="form-control input-sm number required" id="luas"
                                            name="luas" type="text" placeholder="Luas Tanah" />
                                        <span class="input-group-addon input-sm "
                                            id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label required" style="text-align:left;"
                                    for="tanggal_sertifikat">Tanggal Sertifikat</label>
                                <div class="col-sm-4">
                                    <input maxlength="50" class="form-control input-sm required"
                                        name="tanggal_sertif" id="tanggal_sertifikat" type="date"
                                        placeholder="Tanggal Sertifikat" />
                                </div>
                            </div>                                                       
                            <div class="form-group">
                                <label class="col-sm-3 control-label required" style="text-align:left;"
                                    for="hak_tanah">Hak Tanah </label>
                                <div class="col-sm-4">
                                    <select name="hak_tanah" id="hak_tanah" class="form-control input-sm required"
                                        placeholder="Hak Tanah">
                                        <option disabled value="">-------------Sertifikat-------------</option>
                                        <option value="Hak Milik">Hak Milik</option>
                                        <option value="Hak Guna Bangunan">Hak Guna Bangunan</option>
                                        <option value="Hak Pakai">Hak Pakai</option>
                                        <option value="Hak Guna Usaha">Hak Guna Usaha</option>
                                        <option value="Hak Pengelolaan">Hak Pengelolaan</option>
                                        <option disabled value="">----------Belum Sertifikat----------</option>
                                        <option value="Hak Milik Adat">Hak Milik Adat</option>
                                        <option value="Hak Verponding Indonesia">Hak Verponding Indonesia (Milik Pribumi)</option>
                                        <option value="Tanah Negara">Tanah Negara</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label required" style="text-align:left;"
                                    for="hak_tanah">Penggunaan Tanah </label>
                                <div class="col-sm-4">
                                    <select name="penggunaan_tanah" id="penggunaan_barang"
                                        class="form-control input-sm required" placeholder="Penggunaan Tanah">
                                        <option disabled value="">----------Non Pertanian----------</option>
                                        <option value="Perumahan">Perumahan</option>
                                        <option value="Perdagangan dan Jasa">Perdagangan dan Jasa</option>
                                        <option value="Perkantoran">Perkantoran</option>
                                        <option value="Industri">Industri</option>
                                        <option value="Fasilitas Umum">Fasilitas Umum</option>
                                        <option disabled value="">------------Pertanian-------------</option>
                                        <option value="Sawah">Sawah</option>
                                        <option value="Tegalan">Tegalan</option>
                                        <option value="Perkebunan">Perkebunan</option>
                                        <option value="Peternakan / Perikanan">Peternakan / Perikanan</option>
                                        <option value="Hutan Belukar">Hutan Belukar</option>
                                        <option value="Hutan Lebat / Lindung">Hutan Lebat / Lindung</option>
                                        <option value="Mutasi Tanah di Desa">Mutasi Tanah di Desa</option>
                                        <option value="Tanah Kosong">Tanah Kosong</option>                                        
                                    </select>
                                </div>
                            </div>                                                                                                                                        
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="keterangan">Lain - lain</label>
                                <div class="col-sm-8">
                                    <textarea rows="5" class="form-control input-sm required" name="lain"
                                        id="lain" placeholder="Lain - lain"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="keterangan">Keterangan</label>
                                <div class="col-sm-8">
                                    <textarea rows="5" class="form-control input-sm required" name="keterangan"
                                        id="keterangan" placeholder="Keterangan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="col-xs-12">
                        <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i
                                class="fa fa-times"></i> Batal</button>
                        <button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i
                                class="fa fa-check"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {

    });
    $(function () {
        $('.select2').select2();
    })
</script>