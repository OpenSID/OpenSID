<?php
/*

    [mandiri_timeout] => 1631457117
    [kehadiran] => 1
    [is_login] => stdClass Object
        (
            [pin] => 11948479d5a1007cc6fdb1f652a86abb
            [last_login] => 
            [tanggal_buat] => 2021-09-12 15:36:35
            [id_pend] => 26
            [ganti_pin] => 1
            [nama] => IDA BAGUS MAS BUJANA
            [nik] => 5201143112707040
            [foto] => 
            [kk_level] => 1
            [jabatan] => ADMIN IT
            [pamong_id] => 26
            [pamong_status] => 1
            [id_penduduk] => 26
        )

*/
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
<div class="container" style="background-color: #f6f6f6;">
	<div class="row" style="margin-bottom:3px; margin-top:5px;">
		<div class="col-lg-12 col-md-12">
			<div class="header_top">
				<div id="jam" style="margin:5px 0 5px 0; background:#e64946;border:3px double #ffffff;padding:3px;width:auto;" align="center;"> </div>
				
			</div>
		</div>
		<div class="col-lg-12 col-md-12">
			<img class="profile-user-img img-responsive img-circle" src="<?=$foto;?>" alt="Foto">
			<h3 class='text-mid'><?=@$session['is_login']->nama;?></h3>
			<h4 class='text-mid'><?=@$session['is_login']->jabatan;?></h4>
			<form class="form-hadir ">
				<?php
				if($hadir['waktu_masuk']==NULL)
				{?>
					<label class="switch">
					  <input type="checkbox">
					  <span class="slider round"></span>
					</label>
				<?php
				}
				?>
				<br/>
				<button type="submit" class="btn btn-block btn-block bg-green"><b>KONFIRMASI</b></button>
			</form>
<?php if(ENVIRONMENT == 'development'){?>
			<pre><?php print_r($hadir); ?></pre>
			<a href='<?=site_url('kehadiran/keluar');?>'>Keluar</a>
			<pre><?php print_r($session); ?></pre>
<?php } ?>
		</div>
	</div>
</div>