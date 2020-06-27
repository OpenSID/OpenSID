<script>
	function DusSel(str)
	{
		if (str=="")
		{
			document.getElementById("RW").innerHTML="";
			return;
		}
		if (window.XMLHttpRequest)
		{
			xmlhttp=new XMLHttpRequest();
			}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("RW").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "ajax_penduduk_rw/"+str, true);
		xmlhttp.send();
	}

	function RWSel(dusun, str)
	{
		if (str=="")
		{
			document.getElementById("RT").innerHTML="";
			return;
		}
		if (window.XMLHttpRequest)
		{
			xmlhttp=new XMLHttpRequest();
		}
		else
		{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("RT").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "ajax_penduduk_rt/"+dusun+"/"+str, true);
		xmlhttp.send();
	}
</script>
<script>
  $(function ()
	{
	$('.select2').select2()

  })
</script>
<form action="<?=$form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<div class="col-sm-12">
								<label for="nama">Umur</label>
							</div>
							<div class="col-sm-6">
								<input class="form-control input-sm bilangan" type="text" placeholder="Dari" id="umur_min1" name="umur_min1"></input>
								<input type="hidden" name="rt" value="">
							</div>
							<div class="col-sm-6">
								<input class="form-control input-sm bilangan" type="text" placeholder="Sampai" id="umur_max1" name="umur_max1"></input>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="sex1">Jenis Kelamin</label>
								<select class="form-control input-sm"  id="sex1"  name="sex1">
									<option value=""> -- </option>
									<option value="1">LAKI-LAKI</option>
	  							<option value="2">PEREMPUAN</option>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="pekerjaan">Pekerjaan</label>
								<select class="form-control input-sm"  id="pekerjaan1"  name="pekerjaan1">
									<option value=""> -- </option>
									<?php foreach ($pekerjaan AS $data): ?>
										<option value="<?=$data['id']?>"><?=$data['nama']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="status_perkawinan">Status Perkawinan</label>
								<select class="form-control input-sm"  id="status1"  name="status1">
									 <option value=""> -- </option>
									 <option value="1">BELUM KAWIN</option>
									 <option value="2">KAWIN</option>
									 <option value="3">CERAI HIDUP</option>
									 <option value="4">CERAI MATI</option>
									 <option value="5">TIDAK KAWIN</option>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="agama">Agama</label>
								<select class="form-control  input-sm" id="agama1" name="agama1">
									<option value=""> -- </option>
									<?php foreach ($agama AS $data): ?>
										<option value="<?=$data['id']?>"><?=$data['nama']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="pendidikan1">Pendidikan dalam KK</label>
								<select class="form-control  input-sm"  id="pendidikan1"  name="pendidikan1">
									<option value=""> -- </option>
									<?php foreach ($pendidikan AS $data): ?>
										<option value="<?=$data['id']?>"><?=$data['nama']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="status_penduduk1">Status Penduduk</label>
								<select class="form-control input-sm"  id="status_penduduk1"  name="status_penduduk1" >
									<option value=""> -- </option>
									<option value="1">AKTIF</option>
									<option value="2">TIDAK AKTIF</option>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label>Dusun</label>
								<select class="form-control input-sm required" name="dusun1" onchange="DusSel(this.value)">
									<option value="">Pilih Dusun</option>
									<?php foreach ($dusun as $data): ?>
										<option value="<?=($data['dusun'])?>"><?=$data['dusun']?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div id="RW"></div>
							<div id="RT"></div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label for="grup1">Group Kontak</label>
								<select class="form-control input-sm"  id="grup1"  name="grup1" >
									<option value=""> -- </option>
									<?php foreach ($grup AS $data): ?>
										<option value="<?=$data['id_grup']?>"><?=$data['nama_grup']?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<label class="control-label" for="pesan">Isi Pesan</label>
								<textarea name="TextDecoded1" class="form-control input-sm required" placeholder="Isi Pesan"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
		<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-envelope-o'></i> Kirim</button>
	</div>
</form>
