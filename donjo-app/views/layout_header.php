	<div id="sid-logo"><a href="<?php echo site_url()?>first" target="_blank"><img src="<?php echo LogoDesa($desa['logo']);?>" alt=""/></a></div>
	<div id="sid-judul">SID Sistem Informasi Desa</div>
	<div id="sid-info"><?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>,  <?php echo unpenetration($desa['nama_kabupaten'])?></div>
	<div id="userbox" class="wrapper-dropdown-3" tabindex="1">
	  <div class="avatar">
			<?php if($foto){?>
				<img src="<?php echo AmbilFoto($foto)?>" alt=""/>
			<?php }else{?>
				<img src="<?php echo base_url()?>assets/files/user_pict/kuser.png" alt=""/>
			<?php }?>
		</div>
		<div class="info">
			<div><strong>Anda Login sebagai</strong></div>
			<div><?php echo $nama?></div>
		</div>

		<ul class="dropdown" tabindex="1">
			<li><a href="<?php echo site_url()?>user_setting" target="ajax-modalz" rel="window-lok" header="Pengaturan Pengguna" title="Pengaturan Pengguna"><i class="icon-gear icon-large"></i>Setting User</a></li>
			<?php  if($_SESSION['grup']==1 OR $_SESSION['grup']==2){?>
				<li><a href="<?php echo site_url()?>modul/clear"><i class="icon-gear icon-large"></i>Pengaturan</a></li>
				<li><a href="<?php echo site_url()?>hom_desa"><i class="icon-home icon-large"></i>SID Home</a></li>
				<li><a href="<?php echo site_url()?>penduduk"><i class="icon-group icon-large"></i>Penduduk</a></li>
				<li><a href="<?php echo site_url()?>statistik"><i class="icon-bar-chart icon-large"></i>Statistik</a></li>
				<li><a href="<?php echo site_url()?>surat"><i class="icon-print icon-large"></i>Cetak Surat</a></li>
				<li><a href="<?php echo site_url()?>analisis"><i class="icon-dashboard icon-large"></i>Analisis</a></li>
				<li><a href="<?php echo site_url()?>program_bantuan"><i class="icon-folder-open icon-large"></i>Program</a></li>
			<?php  }?>
			<?php  if($_SESSION['grup']==1 OR $_SESSION['grup']==2){?>
				<?php  if($_SESSION['grup']==1){?>
					<li><a href="<?php echo site_url()?>man_user/clear"><i class="icon-user icon-large"></i>Pengguna</a></li>
					<li><a href="<?php echo site_url()?>database"><i class="icon-hdd icon-large"></i>Database</a></li>
				<?php  }?>
				<li><a href="<?php echo site_url()?>sms"><i class="icon-envelope-alt icon-large"></i>SMS</a></li>
				<li><a href="<?php echo site_url()?>web"><i class="icon-cloud icon-large"></i>Admin Web</a></li>
			<?php  }?>
			<li><a href="<?php echo site_url()?>siteman"><i class="icon-off icon-large"></i>Log Out</a></li>
		</ul>
	</div>



<!-- </div>
<div id="sidebar" >
</div>
 -->