<form class="form-horizontal" id="validasi" name="form_tanah" method="post" action="<?= site_url("bumindes_pembangunan/add_tanah_kas_desa"); ?>">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="<?= site_url() ?>bumindes_pembangunan/tables/tanah_kas"
                        class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left"></i> Kembali Ke Buku Tanah Kas Desa</a>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;" for="pemilik_asal">Asal</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" class="form-control input-sm required" name="pemilik_asal"
                                        id="pemilik_asal" type="text" placeholder="Asal" />
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
                                    for="nomor_register">Kelas</label>
                                <div class="col-sm-4">
                                    <select name="kelas" id="kelas"
                                        class="form-control input-sm required" placeholder="Kelas">
                                        <option value="SI">SI</option>
                                        <option value="DI">DI</option>                                        
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="nomor_register">Perolehan TKD</label>
                                <div class="col-sm-4">
                                    <select name="perolehan_tkd" id="perolehan_tkd"
                                        class="form-control input-sm required" placeholder="Perolehan TKD">
                                        <option value="Asli Milik Desa">Asli Milik Desa</option>
                                        <option value="Pemerintah">Pemerintah</option>                                        
                                        <option value="Provinsi">Provinsi</option>                                        
                                        <option value="Kabupaten / Kota">Kabupaten / Kota</option>                                        
                                        <option value="Lain - lain">Lain - lain</option>                                            
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="nomor_register">Jenis TKD</label>
                                <div class="col-sm-4">
                                    <select name="jenis_tkd" id="jenis_tkd"
                                        class="form-control input-sm required" placeholder="Perolehan TKD">
                                        <option value="Sawah">Sawah</option>
                                        <option value="Tegal">Tegal</option>                                        
                                        <option value="Kebun">Kebun</option>                                        
                                        <option value="Tambak / Kolam">Tambak / Kolam</option>                                        
                                        <option value="Tanah Kering / Darat">Tanah Kering / Darat</option>                                        
                                    </select>
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
                                    for="tanggal_sertifikat">Tanggal Perolehan</label>
                                <div class="col-sm-4">
                                    <input maxlength="50" class="form-control input-sm required"
                                        name="tanggal_perolehan" id="tanggal_perolehan" type="date"
                                        placeholder="Tanggal Perolehan" />
                                </div>
                            </div>                                                       
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Patok Batas</label>
                                <div class="col-sm-8">
                                    <div class="container">                                      
                                        <label class="radio-inline"> <input type="radio" name="patok_batas" id="patok_batas" value="1" checked> Ada </label>
                                        <label class="radio-inline"> <input type="radio" name="patok_batas" id="patok_batas" value="0" > Tidak Ada </label>                                                                             
                                    </div>
                                </div>
                            </div>          
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Papan Nama</label>
                                <div class="col-sm-8">
                                    <div class="container">                                       
                                        <label class="radio-inline"> <input type="radio" name="papan_nama" id="papan_nama" value="1" checked > Ada </label>
                                        <label class="radio-inline"> <input type="radio" name="papan_nama" id="papan_nama" value="0" > Tidak Ada </label>                                          
                                    </div>
                                </div>
                            </div>      
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;" for="pemilik_asal">Peruntukan</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" class="form-control input-sm required" name="peruntukan"
                                        id="peruntukan" type="text" placeholder="Peruntukan" />
                                </div>
                            </div>                                                                                                                                  
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="keterangan">Lokasi</label>
                                <div class="col-sm-8">
                                    <textarea rows="5" class="form-control input-sm required" name="lokasi"
                                        id="lokasi" placeholder="Lokasi"></textarea>
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
    // $(function () {
    //     $('.select2').select2();
    // })
</script>