<div id="nav">
<ul>
	<li <?php if($act==1){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('surat')?>">Cetak Surat</a>
	</li>
	<li <?php if($act==2){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('keluar/clear')?>">Surat Keluar</a>
	</li>
	<?php if($_SESSION['grup']==1){?>
		<li <?php if($act==3){?>class="selected"<?php }?>>
			<a href="<?php echo site_url('surat_master/clear')?>">Master Surat</a>
		</li>
	<?php }?>
	<li <?php if($act==5){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('surat_masuk/clear')?>">Surat Masuk</a>
	</li>
	<li <?php if($act==4){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('surat/panduan')?>">Panduan</a>
	</li>
</ul>
</div>
