<script type="text/javascript">
	$(document).ready(function()
	{
		$('#tanggal_tiba').datetimepicker(
		{
			format: 'YYYY-MM-DD'
		});
	});
</script>

<div class="form-group">
	<label for="asal_pemudik"  class="col-sm-3 control-label">Asal Pemudik (kota) / Tiba Tanggal</label>
	<div class="col-sm-4">
		<input class="form-control input-sm" type="text" name="asal_pemudik" id="asal_pemudik" value="<?= $asal_mudik?>" placeholder="Kota">
	</div>

	<div class="col-sm-4 input-group input-group-sm date">
		<div class="input-group-addon">
	        <i class="fa fa-calendar"></i>
	    </div>
	    <input type="text" class="form-control input-sm pull-right" id="tanggal_tiba" name="tanggal_tiba" value="<?= $tanggal_datang?>">
	    
	</div>
</div>

<div class="form-group">
	<label for="tujuan_pemudik"  class="col-sm-3 control-label">Tujuan Mudik / Durasi Mudik</label>
	<div class="col-sm-4">
		<select class="form-control input-sm" name="tujuan_pemudik" id="tujuan_pemudik">
			<?php if($tujuan_mudik === ""): $selected = "selected"; else:  $selected = ""; endif ?>
			<option <?= $selected?> value="0" >-- Pilih Tujuan Mudik --</option>

			<?php if($tujuan_mudik === "Liburan"): $selected = "selected"; else:  $selected = ""; endif ?>
			<option <?= $selected?> value="1">Liburan</option>

			<?php if($tujuan_mudik === "Menjenguk Keluarga"): $selected = "selected"; else:  $selected = ""; endif ?>
			<option <?= $selected?> value="2">Menjenguk Keluarga</option>

			<?php if($tujuan_mudik === "Pulang Kampung"): $selected = "selected"; else:  $selected = ""; endif ?>
			<option <?= $selected?> value="3">Pulang Kampung</option>

			<?php if($tujuan_mudik === "Dll"): $selected = "selected"; else:  $selected = ""; endif ?>
			<option <?= $selected?> value="4">Dll</option>
		</select>
	</div>
	<div class="col-sm-4">
		<input class="form-control input-sm" type="text" name="durasi_pemudik" id="durasi_pemudik" value="<?= $durasi_mudik?>" placeholder="Hari">
	</div>
</div>

<div class="form-group">
	<label for="hp_pemudik"  class="col-sm-3 control-label">Kontak Pemudik (HP/Email)</label>
	<div class="col-sm-4">
		<input class="form-control input-sm" type="text" name="hp_pemudik" id="hp_pemudik" value="<?= $no_hp?>" placeholder="No HP">
	</div>
	<div class="col-sm-4">
		<input class="form-control input-sm" type="text" name="email_pemudik" id="email_pemudik" value="<?= $email?>" placeholder="Email">
	</div>
</div>

<div class="form-group">
	<label  class="col-sm-3 control-label" for="status_covid">Status Covid-19</label>
	<div class="col-sm-8">
		<select class="form-control input-sm" name="status_covid" id="status_covid">
			<?php if($status_covid === ""): $selected = "selected"; else:  $selected = ""; endif ?>
			<option <?= $selected?> value="" >-- Pilih Status Covid-19 --</option>

			<?php if($status_covid === "ODP"): $selected = "selected"; else:  $selected = ""; endif ?>
			<option <?= $selected?> value="ODP">Orang Dalam Pemantauan (ODP)</option>

			<?php if($status_covid === "PDP"): $selected = "selected"; else:  $selected = ""; endif ?>
			<option <?= $selected?> value="PDP">Pasien Dalam Pengawasan (PDP)</option>

			<?php if($status_covid === "ODR"): $selected = "selected"; else:  $selected = ""; endif ?>
			<option <?= $selected?> value="ODR">Orang Dalam Resiko (ODR)</option>

			<?php if($status_covid === "OTG"): $selected = "selected"; else:  $selected = ""; endif ?>
			<option <?= $selected?> value="OTG">Orang Tanpa Gejala (OTG)</option>

			<?php if($status_covid === "POSITIF"): $selected = "selected"; else:  $selected = ""; endif ?>
			<option <?= $selected?> value="POSITIF">Positif</option>

			<?php if($status_covid === "DLL"): $selected = "selected"; else:  $selected = ""; endif ?>
			<option <?= $selected?> value="DLL">Dan Lain Lain</option>
		</select>
	 </div>
</div>

<div class="form-group">
	<label  class="col-sm-3 control-label" for="keluhan">Keluhan Kesehatan</label>
	<div class="col-sm-8">
		 <textarea name="keluhan" id="keluhan" class="form-control input-sm" placeholder="Keluhan Kesehatan"  rows="3"><?= $keluhan_kesehatan?></textarea>
	 </div>
</div>

<div class="form-group">
	<label  class="col-sm-3 control-label" for="keterangan">Keterangan</label>
	<div class="col-sm-8">
		 <textarea name="keterangan" id="keterangan" class="form-control input-sm" placeholder="Keterangan"  rows="3"><?= $keterangan?></textarea>
	 </div>
</div>