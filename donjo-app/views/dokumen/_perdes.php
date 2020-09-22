<div class="form-group">
	<label class="control-label col-sm-4" for="nama">Uraian Singkat</label>
	<div class="col-sm-6">
		<input name="attr[uraian]" class="form-control input-sm" type="text" value="<?=$dokumen['attr']['uraian']?>"></input>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-4" for="nama">Jenis Peraturan</label>
	<div class="col-sm-6">
		<select class="form-control input-sm select2-tags required" name="attr[jenis_peraturan]" style="width: 100%;">
			<option value=''>-- Pilih Jenis Peraturan --</option>
			<?php foreach ($jenis_peraturan as $item): ?>
				<option value="<?= $item ?>" <?php selected($item, $dokumen['attr']['jenis_peraturan'])?>><?= $item?></option>
			<?php endforeach;?>
		</select>

		<!-- <input name="attr[jenis_peraturan]" class="form-control input-sm" type="text" value="<?=$dokumen['attr']['jenis_peraturan']?>"></input> -->
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-4" for="nama">Nomor Ditetapkan</label>
	<div class="col-sm-6">
		<input name="attr[no_ditetapkan]" class="form-control input-sm nomor_sk" type="text" value="<?=$dokumen['attr']['no_ditetapkan']?>"></input>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-4" for="nama">Tgl Ditetapkan</label>
	<div class="col-sm-6">
    <div class="input-group input-group-sm date">
			<div class="input-group-addon">
		  	<i class="fa fa-calendar"></i>
			</div>
		  <input id="tgl_1" name="attr[tgl_ditetapkan]" class="form-control input-sm required" type="text" value="<?=$dokumen['attr']['tgl_ditetapkan']?>"></input>
    </div>
  </div>
</div>
<div class="form-group">
	<label class="control-label col-sm-4" for="nama">Tgl Kesepakatan</label>
	<div class="col-sm-6">
    <div class="input-group input-group-sm date">
			<div class="input-group-addon">
		  	<i class="fa fa-calendar"></i>
			</div>
		  <input id="tgl_2" name="attr[tgl_kesepakatan]" class="form-control input-sm" type="text" value="<?=$dokumen['attr']['tgl_kesepakatan']?>"></input>
    </div>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-4" for="nama">Nomor Dilaporkan</label>
	<div class="col-sm-6">
		<input name="attr[no_lapor]" class="form-control input-sm nomor_sk" type="text" value="<?=$dokumen['attr']['no_lapor']?>"></input>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-4" for="nama">Tgl Dilaporkan</label>
	<div class="col-sm-6">
    <div class="input-group input-group-sm date">
			<div class="input-group-addon">
		  	<i class="fa fa-calendar"></i>
			</div>
		  <input id="tgl_3" name="attr[tgl_lapor]" class="form-control input-sm" type="text" value="<?=$dokumen['attr']['tgl_lapor']?>"></input>
    </div>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-4" for="nama">No. Diundangkan Dlm Lembaran Desa</label>
	<div class="col-sm-6">
		<input name="attr[no_lembaran_desa]" class="form-control input-sm nomor_sk" type="text" value="<?=$dokumen['attr']['no_lembaran_desa']?>"></input>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-4" for="nama">Tgl Diundangkan Dlm Lembaran Desa</label>
	<div class="col-sm-6">
    <div class="input-group input-group-sm date">
			<div class="input-group-addon">
		  	<i class="fa fa-calendar"></i>
			</div>
		  <input id="tgl_4" name="attr[tgl_lembaran_desa]" class="form-control input-sm" type="text" value="<?=$dokumen['attr']['tgl_lembaran_desa']?>"></input>
    </div>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-4" for="nama">No. Diundangkan Dlm Berita Desa</label>
	<div class="col-sm-6">
		<input name="attr[no_berita_desa]" class="form-control input-sm nomor_sk" type="text" value="<?=$dokumen['attr']['no_berita_desa']?>"></input>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-4" for="nama">Tgl Diundangkan Dlm Berita Desa</label>
	<div class="col-sm-6">
    <div class="input-group input-group-sm date">
			<div class="input-group-addon">
		  	<i class="fa fa-calendar"></i>
			</div>
		  <input id="tgl_5" name="attr[tgl_berita_desa]" class="form-control input-sm" type="text" value="<?=$dokumen['attr']['tgl_berita_desa']?>"></input>
    </div>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-4" for="nama">Keterangan</label>
	<div class="col-sm-6">
		<input name="attr[keterangan]" class="form-control input-sm" type="text" value="<?=$dokumen['attr']['keterangan']?>"></input>
	</div>
</div>
