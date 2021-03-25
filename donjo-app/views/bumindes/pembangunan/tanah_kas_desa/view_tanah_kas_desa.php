<form class="form-horizontal" id="validasi" name="form_tanah" method="post" action="<?= $form_action?>">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="<?= site_url() ?>bumindes_pembangunan/tables/tanah_kas"
                        class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                            class="fa fa-arrow-circle-left"></i> Kembali Ke Buku Tanah Kas Desa</a>
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
                                    for="nomor_register">Kelas</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" value="<?= $main->kelas; ?>" class="form-control input-sm"
                                        name="nomor_register" id="nomor_register" type="text" disabled />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label required" style="text-align:left;"
                                    for="hak_tanah">Perolehan TKD</label>
                                <div class="col-sm-4">
                                    <select name="hak_tanah" id="hak_tanah" class="form-control input-sm" disabled>
                                        <option value="<?= $main->perolehan_tkd; ?>"><?= $main->perolehan_tkd; ?></option>
                                    </select>
                                </div>
                            </div>        
                            <div class="form-group">
                                <label class="col-sm-3 control-label required" style="text-align:left;"
                                    for="hak_tanah">Jenis TKD</label>
                                <div class="col-sm-4">
                                    <select name="hak_tanah" id="hak_tanah" class="form-control input-sm" disabled>
                                        <option value="<?= $main->jenis_tkd; ?>"><?= $main->jenis_tkd; ?></option>
                                    </select>
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
                                <label class="col-sm-3 control-label" style="text-align:left;" for="tahun">Tanggal Perolehan</label>
                                <div class="col-sm-4">                                
                                    <input maxlength="50"
                                        value="<?= date('d M Y',strtotime($main->tanggal_perolehan)); ?>"
                                        class="form-control input-sm" name="tanggal_sertifikat" id="tanggal_sertifikat"
                                        type="text" disabled />
                                </div>
                            </div>                                                                                
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Petok Batas</label>
                                <div class="col-sm-8">
                                    <div class="container">
                                        <?php if($main->patok==1) { ?>
                                            <label class="radio-inline"> <input type="radio" name="petok_batas" id="petok_batas" value="1" checked disabled> Ada </label>
                                            <label class="radio-inline"> <input type="radio" name="petok_batas" id="petok_batas" value="0" disabled> Tidak Ada </label>
                                        <?php } else{ ?>
                                            <label class="radio-inline"> <input type="radio" name="petok_batas" id="petok_batas" value="1" disabled> Ada </label>
                                            <label class="radio-inline"> <input type="radio" name="petok_batas" id="petok_batas" value="0" checked disabled> Tidak Ada </label>  
                                        <?php }?>                                       
                                    </div>
                                </div>
                            </div>                                                                                                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Papan Nama</label>
                                <div class="col-sm-8">
                                    <div class="container">
                                        <?php if($main->papan_nama==1) { ?>
                                            <label class="radio-inline"> <input type="radio" name="papan_nama" id="papan_nama" value="1" checked disabled> Ada </label>
                                            <label class="radio-inline"> <input type="radio" name="papan_nama" id="papan_nama" value="0" disabled> Tidak Ada </label>
                                        <?php } else{ ?>                                            
                                            <label class="radio-inline"> <input type="radio" name="papan_nama" id="papan_nama" value="1" disabled> Ada </label>
                                            <label class="radio-inline"> <input type="radio" name="papan_nama" id="papan_nama" value="0" checked disabled> Tidak Ada </label>                                    
                                        <?php }?>        
                                    </div>
                                </div>
                            </div>       
                            <div class="form-group">
                                <label class="col-sm-3 control-label required" style="text-align:left;"
                                    for="nama_barang">Peeruntukan</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" value="<?= $main->peruntukan; ?>"
                                        class="form-control input-sm" name="peruntukan" id="peruntukan" type="text"
                                        disabled />
                                </div>
                            </div>                                                                                         
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="keterangan">Lokasi</label>
                                <div class="col-sm-8">
                                    <textarea rows="5" class="form-control input-sm" name="keterangan" id="keterangan"
                                        disabled><?= $main->lokasi; ?></textarea>
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