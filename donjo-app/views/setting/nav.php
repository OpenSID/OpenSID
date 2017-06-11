<div id="nav">
	<ul>
	<?php if($_SESSION['grup']==1){?>
		<li <?php if($act==1){?>class="selected"<?php }?>>
			<a href="<?php echo site_url('modul')?>">Manajemen Modul</a>
		</li>
		<li <?php if($act==2){?>class="selected"<?php }?>>
			<a href="<?php echo site_url('setting')?>">Setting Aplikasi</a>
		</li>
	<?php }?>
	</ul>
</div>