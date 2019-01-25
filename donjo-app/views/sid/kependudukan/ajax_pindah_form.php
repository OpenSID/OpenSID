<script>
	function DusSel(str)
	{
		if (str=="")
		{
			document.getElementById("rw").innerHTML="";
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
				document.getElementById("rw").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","<?= site_url()?>penduduk/ajax_penduduk_pindah_rw/"+encodeURIComponent(str).replace(/\(/g, "%28").replace(/\)/g, "%29"), true);
		xmlhttp.send();
	}

	function RWSel(dusun,str)
	{
		if (str=="")
		{
			document.getElementById("rt").innerHTML="";
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
				document.getElementById("rt").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","<?= site_url()?>penduduk/ajax_penduduk_pindah_rt/"+dusun+"/"+str,true);
		xmlhttp.send();
	}
</script>
<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<?php if ($is_anggota_keluarga): ?>
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class='box box-danger'>
					<div class='box-body'>
						<p class="text-red">Penduduk ini anggota keluarga, bukan penduduk lepas, dan tidak bisa dipindahkan perorangan.
						Keluarga penduduk ini dapat dipindahkan pada modul Keluarga.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php else: ?>
<form action="<?= $form_action?>" method="post" id="validasi">
	<input type="hidden" name="rt" value="">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class='box box-primary'>
					<div class='box-body'>
						<table class="table table-bordered  table-striped table-hover" >
							<tr>
								<td style="padding-top : 10px;padding-bottom : 10px; width:30%;" >Nama / NIK</td>
								<td> : </td>
								<td><strong><?= strtoupper($kepala_keluarga['nama'])?></strong> - [<?= $kepala_keluarga['nik']?>]</td>
							</tr>
							<tr>
								<td style="padding-top : 10px;padding-bottom : 10px;" >Tempat / Tgl. Lahir</td>
								<td> : </td>
								<td><?= strtoupper($kepala_keluarga['tempatlahir'])?> / <?= strtoupper($kepala_keluarga['tanggallahir'])?></td>
							</tr>
							<tr>
								<td style="padding-top : 10px;padding-bottom : 10px;" >Alamat Sekarang</td>
								<td> : </td>
								<td><?= $alamat_wilayah ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label>Alamat Baru</label>
							<input id="alamat" name="alamat" class="form-control input-sm" type="text" placeholder="Nama Jalan" value="<?= $data['alamat']?>"></input>
						</div>
						<div class="form-group">
							<label>Dusun</label>
							<select class="form-control input-sm" name="dusun1" onchange="DusSel(this.value)">
								<option value="">Pilih Dusun</option>
								<?php foreach ($dusun as $data): ?>
								<?php ///$data['dusun']=myUrlEncode($data['dusun']);?>
									<option value="<?= ($data['dusun'])?>"><?= $data['dusun']?></option>
								<?php endforeach;?>
							</select>
						</div>
						<div id="rw"></div>
						<div id="rt"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</div>
</form>
<?php endif; ?>
