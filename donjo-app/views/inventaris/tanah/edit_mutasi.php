<style>
    #footer {
		color: #f83535;
        text-shadow: 1px 1px 0.5px #444;
        padding: 8px;
        text-align: center;
        position: relative;
        bottom: 0px;
        width: 100%;
        background: #eaa852;
        height: 34px;
}
</style>
<script src="<?php echo base_url('assets/js/sweetalert.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery-validation-1.17.0/dist/jquery.validate.js') ?>"></script>
<script src="<?php echo base_url('assets/js/jquery-validation-1.17.0/dist/jquery.validate.min.js') ?>"></script>

<div class="panel">
    <div class="panel-body">
        <section class="content">

            <div class='box box-default'>
                <div class='box-header with-border'>
                    <h4 class='box-title'>Edit Mutasi -
                        <small>Data Inventaris Tanah Desa</small>
                    </h4>
                    <hr>
                </div>

                <div class='box-body'>
                    <div class="form">

                        <form class="form-horizontal" id="update_mutasi_tanah" name="update_mutasi_tanah" method="post" action="">    

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label required" style="text-align:left;" for="nama_barang">Nama Barang</label>
                                    <div class="col-sm-9">
                                        <input type="hidden" name="id" id="id" value="<?php echo $main->id; ?>">
                                        <input maxlength="50" value="<?php echo $main->nama_barang; ?>"  class="form-control" name="nama_barang" id="nama_barang" type="text" disabled/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="text-align:left;" for="kode_barang">Kode Barang</label>
                                    <div class="col-sm-9">
                                        <input maxlength="50" value="<?php echo $main->kode_barang; ?>"  class="form-control" name="kode_barang" id="kode_barang" type="text" disabled/>
                                    </div>
                                </div><!-- row -->

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="text-align:left;" for="nomor_register">Nomor Register</label>
                                    <div class="col-sm-9">
                                        <input maxlength="50" value="<?php echo $main->register; ?>"  class="form-control" name="kode_barang" id="kode_barang" type="text" disabled/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="text-align:left;" for="mutasi" require>Jenis Mutasi </label>
                                    <div class="col-sm-9">
                                        <select name="mutasi" id="mutasi" class="form-control">
                                          <option value="<?php echo $main->jenis_mutasi; ?>">   <?php echo $main->jenis_mutasi;?></option>
                                          <option value="Rusak">Status Rusak</option>
                                          <option value="Diperbaiki">Status Diperbaiki</option>
                                          <optgroup label="Barang Masih Baik">
                                            <option value="Masih Baik Disumbangkan">Sumbangakan</option>
                                            <option value="Masih Baik Dijual">Jual</option>
                                          </optgroup>
                                          <optgroup label="Barang Sudah Rusak">
                                            <option value="Barang Rusak Disumbangkan">Sumbangakan</option>
                                            <option value="Barang Rusak Dijual">Jual</option>
                                          </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group disumbangkan">
                                    <label class="col-sm-2 control-label" style="text-align:left;" for="disumbangkan">Disumbangkan ke-</label>
                                    <div class="col-sm-9">
                                        <input maxlength="50"  class="form-control" name="disumbangkan" id="disumbangkan" type="text" value="<?php echo $main->sumbangkan; ?>"/>
                                    </div>
                                </div>
                                <div class="form-group harga_jual">
                                    <label class="col-sm-2 control-label " style="text-align:left;" for="harga_jual">Harga Penjualan</label>
                                    <div class="col-sm-9">
                                        <input maxlength="50"  class="form-control" name="harga_jual" id="harga_jual" type="number" value="<?php echo $main->harga_jual; ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="text-align:left;" for="tahun">Tahun Pengadaan </label>
                                    <div class="col-sm-9">
                                        <select name="tahun" id="tahun" class="form-control" disabled>
                                            <option value="<?php echo $main->tahun_pengadaan; ?>"><?php echo $main->tahun_pengadaan; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label required" style="text-align:left;" for="tahun_mutasi">Tahun Mutasi</label>
                                    <div class="col-sm-9">
                                        <input type="date" maxlength="50" class="form-control" name="tahun_mutasi" id="tahun_mutasi" value="<?php echo $main->tahun_mutasi; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
                                    <div class="col-sm-9">
                                        <textarea rows="5" class="form-control" name="keterangan" id="keterangan" ><?php echo $main->keterangan; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right" >
                                <button type="submit" class="btn btn-primary"> Update</button>
                                <a href="<?php echo base_url() ?>index.php/inventaris_tanah/mutasi" class="btn btn-default save"
                                           id="btn_batal" name="yt1" type="button"/>Batal</a>
                            </div>
                        </form>
                    </div><!-- form -->
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    $(document).ready(function(){
        if($("#mutasi").val() == "Masih Baik Disumbangkan" | $("#mutasi").val() == "Barang Rusak Disumbangkan" ){
            $(".disumbangkan").show();
            $(".harga_jual").hide();
        }else if($("#mutasi").val() == "Masih Baik Dijual" | $("#mutasi").val() == "Barang Rusak Dijual" ){
            $(".disumbangkan").hide();
            $(".harga_jual").show();
        }else if($("#mutasi").val() == "Rusak" | $("#mutasi").val() == "Diperbaiki" ){
            $(".disumbangkan").hide();
            $(".harga_jual").hide();
        }

        $("#mutasi").change(function() {
            if($("#mutasi").val() == "Masih Baik Disumbangkan" | $("#mutasi").val() == "Barang Rusak Disumbangkan" ){
                $(".disumbangkan").show();
                $(".harga_jual").hide();
            }else if($("#mutasi").val() == "Masih Baik Dijual" | $("#mutasi").val() == "Barang Rusak Dijual" ){
                $(".disumbangkan").hide();
                $(".harga_jual").show();
            }else if($("#mutasi").val() == "Rusak" | $("#mutasi").val() == "Diperbaiki" ){
                $(".disumbangkan").hide();
                $(".harga_jual").hide();
            }
        });
    });


    $("#update_mutasi_tanah").validate({
        submitHandler: function(form) {
          var formInput = new FormData($(form));
        //   formInput.append('id', $('#id').val());
          formInput.append('jenis_mutasi', $('#mutasi').val());
          formInput.append('tahun_mutasi', $('#tahun_mutasi').val());
          formInput.append('harga_jual', $('#harga_jual').val());
          formInput.append('sumbangkan', $('#disumbangkan').val());
          formInput.append('keterangan', $('#keterangan').val());

          $.ajax({
              url: '<?php echo site_url("api_inventaris_tanah/update_mutasi"); ?>'+'/'+ $('#id').val(),
              method: 'post',
              dataType: 'json',
              data: formInput,
              contentType: false,
              processData: false,
              success: function(){
                    swal({
                        title: 'Sukses!',
                        text: 'Berhasil Berhasil diubah',
                        type: 'success'
                    });
                    setTimeout(function(){
                        window.location.href = '<?php echo site_url("inventaris_tanah/mutasi"); ?>';
                    }, 2000)
              },
              error: function(err){
                  console.log('error',err);
              },
          }); 
        }
    });
    
</script>