<?php
 
$foto=$session['is_login']->foto;
if($foto==NULL||$foto=='')
{
	$foto=base_url("assets/files/user_pict/kuser.png");
}
else
{
	$foto=base_url("desa/upload/user_pict")."kecil_".$foto;
}
?>
<div2 class="container2" style="background-color: #f6f6f6;">
	<div2 class="row2" style="margin-bottom:3px; margin-top:5px;">
	<div class="login-box" style='margin:auto;width:400px'>

		<div class="login-box-body">
		<div class="col-lg-12 col-md-12">
			<div class="header_top">
				<div id="jam" style="margin:5px 0 5px 0; background:#2196f3;border:3px double #ffffff;padding:3px;width:auto;" align="center;"> </div>
				
			</div>
		</div>
		<div class="col-lg-12 col-md-12">
			<img class="profile-user-img img-responsive img-circle" src="<?=$foto;?>" alt="Foto">
			<div class='row'>
				<div class="col-lg-4 col-md-6">Nama:</div> 
				<div class="col-lg-8 col-md-6"><?=@$session['login_data']->nama;?></div>
			</div>
			<div class='row'>
				<div class="col-lg-4 col-md-6">NIP:</div> 
				<div class="col-lg-8 col-md-6"><?=@$session['login_data']->nip;?></div>
			</div>
			<div class='row'>
				<div class="col-lg-4 col-md-6">Jabatan:</div> 
				<div class="col-lg-8 col-md-6"><?=@$session['login_data']->jabatan;?></div>
			</div>

			<form class="form-hadir " method="post" id='form_hadir'>
				<?php
				if($hadir['waktu_masuk']==NULL)
				{?>
					<h3>Masuk</h3>
					<label class="switch">
					  <input type="checkbox" value="1" name="hadir" id="stat_hadir" />
					  <span class="slider round"></span>
					</label>
				<?php
				}
				elseif($hadir['waktu_masuk']!=NULL&&$hadir['waktu_keluar']==NULL)
				{?>
					<h3>Keluar</h3>
					<label class="switch">
					  <input type="checkbox" value="2" name="hadir2" id="stat_hadir" checked />
					  <span class="slider round"></span>
					</label>
					<input type='hidden' value="2" name="hadir" />
				<?php
				}
				else{
				?>
				<div class="callout callout-danger" id="notif_msg">
					Maaf, Anda sudah Mengisi Kehadiran Keluar
				</div>
				<?php 
				}
				?>
				<br/>
				
				<button type="button" class="btn btn-block btn-block bg-green" 
				onclick='cekHadir()'><b>KONFIRMASI</b></button>
			</form>
<?php if( false&&ENVIRONMENT == 'development'){?>
<!--
 [login_data] => stdClass Object
        (
            [nama] => Mustahiq S.Adm
            [nik] => NTIwMTE0MDUwNjc5MDAwMQ==
            [pin] => 3645e735f033e8482be0c7993fcba946
            [tag_id_card] => 11123
            [jabatan] => Sekretaris Desa
            [pamong_id] => 20
            [pamong_status] => 1
        )
-->
			<pre><?php print_r($hadir); ?></pre>
			<a href='<?=site_url('kehadiran/keluar');?>'>Keluar</a>
			<pre><?php print_r($session); ?></pre>
<?php } ?>
		</div>
		</div>
	</div>
	</div2>
</div2>
<?php $this->load->view('kehadiran/js/status_js');?>