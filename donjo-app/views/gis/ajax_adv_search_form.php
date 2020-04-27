<script>
	function DusSel(str)
	{
		if (str==""):
			document.getElementById("RW").innerHTML="";
			return;
		endif;
		if (window.XMLHttpRequest):
			xmlhttp=new XMLHttpRequest();
		else:
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		endif;

		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200):
				document.getElementById("RW").innerHTML=xmlhttp.responseText;
			endif;
		}
		xmlhttp.open("GET","sid_penduduk/ajax_penduduk_pindah_rw/"+str,true);
		xmlhttp.send();
	}

	function RWSel(dusun,str)
	{
		if (str==""):
			document.getElementById("RT").innerHTML="";
			return;
		endif;
		if (window.XMLHttpRequest):
			xmlhttp=new XMLHttpRequest();
		else:
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		endif;
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200):
				document.getElementById("RT").innerHTML=xmlhttp.responseText;
			endif;
		}
		xmlhttp.open("GET","sid_penduduk/ajax_penduduk_pindah_rt/"+dusun+"/"+str,true);
		xmlhttp.send();
	}
</script>

<form method="post" action="<?= $form_action?>">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="col-sm-12">
							<label for="nama">Umur</label>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<input class="form-control  input-sm" type="text" placeholder="Dari" id="umur_min" name="umur_min"  value="<?= $umur_min?>"></input>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<input id="umur_max" class="form-control  input-sm" type="text" placeholder="Sampai" name="umur_max" value="<?= $umur_max?>"></input>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label><?=ucwords($this->setting->sebutan_dusun)?></label>
								<select class="form-control input-sm " name="dusun" nchange="DusSel(this.value)">
									<option value=""> -- </option>
									<?php foreach ($list_dusun as $data): ?>
										<option value="<?=$data['dusun']?>" <?php selected($dusun, $data['dusun']); ?>><?=$data['dusun']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div id="RW"></div>
						<div id="RT"></div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="sex">Jenis Kelamin</label>
								<select class="form-control input-sm" id="sex" name="sex">
									<option value=""> -- </option>
									<?php foreach ($list_jenis_kelamin AS $data): ?>
										<option value="<?= $data['id']?>" <?php selected($sex, $data['id']); ?>><?= $data['nama']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="status_dasar">Pekerjaan</label>
								<select class="form-control input-sm" id="pekerjaan_id" name="pekerjaan_id">
									<option value=""> -- </option>
									<?php foreach ($list_pekerjaan AS $data): ?>
										<option value="<?= $data['id']?>" <?php selected($pekerjaan_id, $data['id']); ?>><?= $data['nama']?></option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="status">Status Perkawinan</label>
								<select class="form-control input-sm" id="status" name="status" >
									<option value=""> -- </option>
									<?php foreach ($list_status_kawin AS $data): ?>
										<option value="<?= $data['id']?>" <?php selected($status, $data['id']); ?>><?= $data['nama']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="agama">Agama</label>
								<select class="form-control input-sm" id="agama" name="agama" >
									<option value=""> -- </option>
									<?php foreach ($list_agama AS $data): ?>
										<option value="<?= $data['id']?>" <?php selected($agama, $data['id']); ?>><?= $data['nama']?></option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="pendidikan_kk_id">Pendidikan KK</label>
								<select class="form-control input-sm" id="pendidikan_kk_id" name="pendidikan_kk_id" >
									<option value=""> -- </option>
									<?php foreach ($list_pendidikan_kk AS $data): ?>
										<option value="<?= $data['id']?>" <?php selected($pendidikan_kk_id, $data['id']); ?>><?= $data['nama']?></option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="status_penduduk">Status Penduduk</label>
								<select class="form-control input-sm" id="status_penduduk" name="status_penduduk">
									<option value=""> -- </option>
									<?php foreach ($list_status_penduduk AS $data): ?>
										<option value="<?= $data['id']?>" <?php selected($status_penduduk, $data['id']); ?>><?= $data['nama']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-search'></i> Cari</button>
	</div>
</form>
