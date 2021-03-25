<form class="form-horizontal" id="validasi" name="form_tanah" method="post" action="<?= $form_action?>">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="<?= site_url() ?>bumindes_pembangunan/tables/tanah"
                        class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                            class="fa fa-arrow-circle-left"></i> Kembali Ke Buku Tanah di Desa</a>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label required" style="text-align:left;"
                                    for="nama_barang">Pemilik / Asal</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" value="<?= $main->nama_pemilik_asal; ?>"
                                        class="form-control input-sm" name="nama_barang" id="nama_barang" type="text"
                                        disabled />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;" for="kode_barang">No. Letter C / Persil</label>
                                <div class="col-sm-4">
                                    <input maxlength="50" value="<?= $main->letter_c; ?>"
                                        class="form-control input-sm" name="kode_barang" id="kode_barang" type="text"
                                        disabled />
                                </div>
                                <div class="col-sm-4">
                                    <input maxlength="50" value="<?= $main->persil; ?>"
                                        class="form-control input-sm" name="kode_barang" id="kode_barang" type="text"
                                        disabled />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="nomor_register">No. Sertifikat</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" value="<?= $main->nomor_sertif; ?>" class="form-control input-sm"
                                        name="nomor_register" id="nomor_register" type="text" disabled />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;" for="luas_tanah">Luas Tanah</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input value="<?= $main->luas; ?>" class="form-control input-sm" id="luas_tanah"
                                            name="luas_tanah" type="text" disabled />
                                        <span class="input-group-addon input-sm"
                                            id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;" for="tahun">Tanggal Sertifikat </label>
                                <div class="col-sm-4">
                                    <!-- <select name="tahun" id="tahun" class="form-control input-sm" disabled>
                                        <option value="<?= $main->tanggal_sertif; ?>"><?= $main->tanggal_sertif; ?>
                                        </option>
                                    </select> -->
                                    <input maxlength="50"
                                        value="<?= date('d M Y',strtotime($main->tanggal_sertif)); ?>"
                                        class="form-control input-sm" name="tanggal_sertifikat" id="tanggal_sertifikat"
                                        type="text" disabled />
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label required" style="text-align:left;"
                                    for="hak_tanah">Jenis Hak Tanah</label>
                                <div class="col-sm-4">
                                    <select name="hak_tanah" id="hak_tanah" class="form-control input-sm" disabled>
                                        <option value="<?= $main->hak_tanah; ?>"><?= $main->hak_tanah; ?></option>
                                    </select>
                                </div>
                            </div>                                                        
                            <div class="form-group">
                                <label class="col-sm-3 control-label required" style="text-align:left;"
                                    for="penggunaan">Penggunaan Tanah</label>
                                <div class="col-sm-4">
                                    <select name="penggunaan" id="penggunaan" class="form-control input-sm" disabled>
                                        <option value="<?= $main->penggunaan_tanah; ?>"><?= $main->penggunaan_tanah; ?></option>
                                        <option value="Industri">Industri</option>
                                        <option value="Jalan">Jalan</option>
                                        <option value="Komersial">Komersial</option>
                                        <option value="Permukiman">Permukiman</option>
                                        <option value="Tanah Publik">Tanah Publik</option>
                                        <option value="Tanah Kosong">Tanah Kosong</option>
                                    </select>
                                </div>
                            </div>                            
                            <!-- <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;" for="harga">Harga</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon input-sm"
                                            id="koefisien_dasar_bangunan-addon">Rp</span>
                                        <input value="<?= $main->harga; ?>" class="form-control input-sm" id="harga"
                                            name="harga" type="text" disabled />
                                    </div>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="keterangan">Lain - lain</label>
                                <div class="col-sm-8">
                                    <textarea rows="5" class="form-control input-sm" name="keterangan" id="keterangan"
                                        disabled><?= $main->lain; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="keterangan">Keterangan</label>
                                <div class="col-sm-8">
                                    <textarea rows="5" class="form-control input-sm" name="keterangan" id="keterangan"
                                        disabled><?= $main->keterangan; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>