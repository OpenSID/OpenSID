<form class="form-horizontal" id="validasi" name="form_tanah" method="post" action="<?= $form_action ?>">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="<?= site_url() ?>bumindes_tanah_kas_desa"
                        class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left"></i> Kembali Ke Buku Tanah Kas Desa</a>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" id="id" name="id" value="<?= $main->id; ?>">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;" for="pemilik_asal">Asal Tanah Kas Desa</label>
                                <div class="col-sm-8">
                                    <input class="form-control input-sm nama required" name="pemilik_asal"
                                        id="pemilik_asal" type="text" placeholder="Asal Tanah Kas Desa" value="<?= $main->nama_pemilik_asal; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;" for="kode_barang">No. Letter C / Persil</label>
                                <div class="col-sm-4">                                                    
                                    <select class="form-control input-sm select2 required" style="width: 100%;" id="letter_c" name="letter_c">                                        
                                        <?php if($main->letter_c!=NULL){ ?>
                                            <option value="<?= $main->letter_c; ?>"><?= $main->letter_c; ?></option>
                                        <?php } ?>
                                        <?php foreach ($letterc as $item): ?>
                                        <option value="<?= $item['nomor']?>"><?= $item['nomor']?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="col-sm-4">                                                                                            
                                    <select class="form-control input-sm select2 required" style="width: 100%;" id="persil" name="persil">                                       
                                        <?php if($main->persil!=NULL){ ?>
                                            <option value="<?= $main->persil; ?>"><?= $main->persil; ?></option>
                                        <?php } ?>
                                        <?php foreach ($persil as $per): ?>
                                        <option value="<?= $per['nomor']?>"><?= $per['nomor']?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="nomor_register">Kelas</label>
                                <div class="col-sm-4">
                                    <select name="kelas" id="kelas"
                                        class="form-control input-sm required" placeholder="Kelas">
                                        <?php if($main->kelas!=NULL){ ?>
                                            <option value="<?= $main->kelas; ?>"><?= $main->kelas; ?></option>
                                        <?php } ?>                                        
                                        <option value="SI">SI</option>
                                        <option value="DI">DI</option>                                        
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label required" style="text-align:left;"
                                    for="tanggal_sertifikat">Tanggal Perolehan</label>
                                <div class="col-sm-4">
                                    <input maxlength="50" class="form-control input-sm required"
                                        name="tanggal_perolehan" id="tanggal_perolehan" type="date"
                                        placeholder="Tanggal Sertifikat" value="<?= $main->tanggal_perolehan; ?>"/>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;" for="luas_tanah">Luas Tanah Total</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="number" min="0" class="form-control input-sm number disabled required" 
                                        <?php if($main->luas!=0){ ?>   
                                                value="<?= $main->luas; ?>"                                           
                                            <?php } else { ?>                                                
                                                value=0 
                                            <?php } ?>
                                            id="luas" name="luas" />                                     
                                        <span class="input-group-addon input-sm "
                                            id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-12'>
                                <div class="form-group subtitle_head">
                                    <label class="text-right"><strong>Perolehan TKD :</strong></label>
                                </div>
                            </div> 
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="asli_milik_desa">Asli Milik Desa</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->asli_milik_desa!=0){ ?>   
                                                    value="<?= $main->asli_milik_desa; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="asli_milik_desa" name="asli_milik_desa" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="pemerintah">Bantuan Pemerintah</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->pemerintah!=0){ ?>   
                                                    value="<?= $main->pemerintah; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="pemerintah" name="pemerintah" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="provinsi">Bantuan Provinsi</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->pemerintah!=0){ ?>   
                                                    value="<?= $main->pemerintah; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="provinsi" name="provinsi" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="kabupaten_kota">Bantuan Kabupatan / Kota</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->kabupaten_kota!=0){ ?>   
                                                    value="<?= $main->kabupaten_kota; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="kabupaten_kota" name="kabupaten_kota" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="lain_lain">Lain - lain</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->lain_lain!=0){ ?>   
                                                    value="<?= $main->lain_lain; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="lain_lain" name="lain_lain" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-12'>
                                <div class="form-group subtitle_head">
                                    <label class="text-right"><strong>Jenis TKD :</strong></label>
                                </div>
                            </div> 
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="sawah">Sawah</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->sawah!=0){ ?>   
                                                    value="<?= $main->sawah; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="sawah" name="sawah" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="tegal">Tegal</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->tegal!=0){ ?>   
                                                    value="<?= $main->tegal; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="tegal" name="tegal" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="kebun">Kebun</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->kebun!=0){ ?>   
                                                    value="<?= $main->kebun; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="kebun" name="kebun" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="tambak_kolam">Tambak / Kolam</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->tambak_kolam!=0){ ?>   
                                                    value="<?= $main->tambak_kolam; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="tambak_kolam" name="tambak_kolam" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="tanah_kering_darat">Tanah Kering / Darat</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->tanah_kering_darat!=0){ ?>   
                                                    value="<?= $main->tanah_kering_darat; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="tanah_kering_darat" name="tanah_kering_darat" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-12'>
                                <div class="form-group subtitle_head">
                                    <label class="text-right"><strong>Patok Tanda Batas :</strong></label>
                                </div>
                            </div> 
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="ada_patok">Ada Patok Tanda Batas</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->ada_patok!=0){ ?>   
                                                    value="<?= $main->ada_patok; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="ada_patok" name="ada_patok" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="tidak_ada_patok">Tidak Ada Patok Tanda Batas</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->tidak_ada_patok!=0){ ?>   
                                                    value="<?= $main->tidak_ada_patok; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="tidak_ada_patok" name="tidak_ada_patok" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-12'>
                                <div class="form-group subtitle_head">
                                    <label class="text-right"><strong>Papan Nama :</strong></label>
                                </div>
                            </div> 
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="ada_papan_nama">Ada Papan Nama</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->ada_papan_nama!=0){ ?>   
                                                    value="<?= $main->ada_papan_nama; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="ada_papan_nama" name="ada_papan_nama" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <label class="col-sm-12 control-label" style="text-align:left;" for="tidak_ada_papan_nama">Tidak Ada Papan Nama</label>
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input onchange="dinamicLuas()" type="number" min="0" class="form-control input-sm number required"                                               
                                                <?php if($main->tidak_ada_papan_nama!=0){ ?>   
                                                    value="<?= $main->tidak_ada_papan_nama; ?>"                                           
                                                <?php } else { ?>                                                
                                                    value=0 
                                                <?php } ?>
                                                id="tidak_ada_papan_nama" name="tidak_ada_papan_nama" />
                                            <span class="input-group-addon input-sm "
                                                id="koefisien_dasar_bangunan-addon">M<sup>2</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-12'>
                                <div class="form-group subtitle_head">
                                    <label class="text-right"><strong>Catatan :</strong></label>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="nomor_register">Peruntukan</label>
                                <div class="col-sm-8">
                                    <input maxlength="50" class="form-control input-sm nomor_sk required" name="peruntukan"
                                        id="peruntukan" type="text" placeholder="Peruntukan" value="<?= $main->peruntukan; ?>"/>
                                </div>
                            </div>                                                                                                                                 
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="lokasi">Lokasi</label>
                                <div class="col-sm-8">
                                    <textarea rows="5" class="form-control input-sm nomor_sk" name="lokasi"
                                        id="lokasi" placeholder="Lokasi" ><?= $main->lokasi; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="mutasi">Mutasi</label>
                                <div class="col-sm-8">
                                    <textarea rows="5" class="form-control input-sm nomor_sk" name="mutasi"
                                        id="mutasi" placeholder="Mutasi" ><?= $main->mutasi; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" style="text-align:left;"
                                    for="keterangan">Keterangan</label>
                                <div class="col-sm-8">
                                    <textarea rows="5" class="form-control input-sm nomor_sk" name="keterangan"
                                        id="keterangan" placeholder="Keterangan"><?= $main->keterangan; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="form_footer" class="box-footer">
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
    $('document').ready(function()
	{
		var view = <?= $view_mark?>;
        if(1==view){
            $("#pemilik_asal").attr("disabled",true);
            $("#letter_c").attr("disabled",true);
            $("#persil").attr("disabled",true);
            $("#kelas").attr("disabled",true);                        
            $("#tanggal_perolehan").attr("disabled",true);           
            $("#luas").attr("disabled",true);            
            $("#asli_milik_desa").attr("disabled",true);            
            $("#pemerintah").attr("disabled",true);            
            $("#provinsi").attr("disabled",true);            
            $("#kabupaten_kota").attr("disabled",true);            
            $("#lain_lain").attr("disabled",true);            
            $("#sawah").attr("disabled",true);            
            $("#tegal").attr("disabled",true);            
            $("#kebun").attr("disabled",true);            
            $("#tambak_kolam").attr("disabled",true);            
            $("#tanah_kering_darat").attr("disabled",true);            
            $("#ada_patok").attr("disabled",true);            
            $("#tidak_ada_patok").attr("disabled",true);            
            $("#ada_papan_nama").attr("disabled",true);            
            $("#tidak_ada_papan_nama").attr("disabled",true);            
            $("#peruntukan").attr("disabled",true);
            $("#lokasi").attr("disabled",true);
            $("#mutasi").attr("disabled",true);
            $("#keterangan").attr("disabled",true);
            $("#form_footer").hide();              
        }
	});

    function dinamicLuas()
    {
        var res = 0;
        res = parseInt($('#asli_milik_desa').val())
            +parseInt($('#pemerintah').val())
            +parseInt($('#provinsi').val())
            +parseInt($('#kabupaten_kota').val())
            +parseInt($('#lain_lain').val())
            +parseInt($('#sawah').val())
            +parseInt($('#tegal').val())
            +parseInt($('#kebun').val())
            +parseInt($('#tambak_kolam').val())
            +parseInt($('#tanah_kering_darat').val())
            +parseInt($('#ada_patok').val())
            +parseInt($('#tidak_ada_patok').val())
            +parseInt($('#ada_papan_nama').val())
            +parseInt($('#tidak_ada_papan_nama').val())           
        $('#luas').val(res);
    }
</script>