<div id="nav">
	<ul>
	<?php if($_SESSION['grup']==1){?>
		<li <?php if($act==1){?>class="selected"<?php }?>>
			<a href="<?php echo site_url('modul')?>">Pengaturan Modul</a>
		</li>
	<?php }?>
	</ul>
</div>