<div id="nav">
	<ul>
	<li <?php if($act==2){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/import')?>">Import Database</a>
	</li>
	<?php /*
	<li <?php if($act==4){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/import_ppls')?>">Import Grup Rumah Tangga</a>
	</li>
	<li <?php if($act==6){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/siak')?>">Import SIAK</a>
	</li>
	*/ ?>
	<li <?php if($act==1){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database')?>">Export Database</a>
	</li>
	<li <?php if($act==3){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/backup')?>">Backup/Restore Database</a>
	</li>
	</ul>
</div>
