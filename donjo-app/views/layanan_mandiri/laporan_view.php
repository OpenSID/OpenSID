<?php 
//pre_print_r($aparat_aktif['result']);
?>
<style>
.aparat{
	background: #ddecff;
	padding: 0 12px;
	margin-right:10px;
	height:240px;
}
</style>
<div class='container'>
	<div class='panel-aparat row'>
<?php if ($error_msg = $this->session->error_msg): 
		$this->session->unset_userdata('error_msg');
	?>
	<div class="callout callout-danger" id="notif_msg">
		<?=$error_msg;?> 
	</div>	
<?php endif; ?>
<?php if ($success_msg = $this->session->success_msg): 
		$this->session->unset_userdata('success_msg');
	?>
	<div class="callout callout-success" id="notif_msg">
		<?=$success_msg;?> 
	</div>	
<?php endif; ?>
			
<?php 
foreach($aparat_aktif['result'] as $aparat)
{
	if($aparat->status==1)
	{
		$foto=@$aparat->foto;
		if ($foto == NULL || $foto == '')
		{
			$foto = base_url("assets/files/user_pict/kuser.png");
		}
		else
		{
			$foto = base_url("desa/upload/user_pict")."/kecil_".$foto;
		}
		?>
	<div class='aparat col-lg-3 col-md-6'>
		
		<div class="col-lg-12 col-md-12">
			<img class="profile-user-img img-responsive img-circle" src="<?=$foto;?>" alt="Foto">
			<div class='row'>
				<div class="col-lg-4 col-md-6">Nama:</div> 
				<div class="col-lg-8 col-md-6"><?=@$aparat->pamong_info->nama;?></div>
			</div>
			<div class='row'>
				<div class="col-lg-4 col-md-6">Jabatan:</div> 
				<div class="col-lg-8 col-md-6"><?=@$aparat->pamong_info->jabatan;?></div>
			</div>
			<div class='row'>
				<div class="col-lg-4 col-md-6">Laporkan:</div> 
				<div class="col-lg-8 col-md-6"><?= '<a href="'.site_url('layanan-mandiri/kehadiran/lapor?aparatid='.$aparat->id).'" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Melaporkan" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>';?></div>
			</div>

		</div>

	
	</div>
		<?php
	}
}

?>
	</div>
</div>
<div style='height:50px;clear:both'></div>
<!-- Untuk menampilkan modal bootstrap umum -->
<div class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4 class='modal-title' id='myModalLabel'> Pengaturan Pengguna</h4>
			</div>
			<div class="fetched-data"></div>
		</div>
	</div>
</div>