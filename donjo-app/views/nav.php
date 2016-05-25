<div id="nav">
	<ul>
	<li <?php if($act==1){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database')?>">Export</a>
	</li>
	<li <?php if($act==2){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/import')?>">Import Excel</a>
	</li>
	<li <?php if($act==3){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/backup')?>">Backup/Restore</a>
	</li>
	</ul>
</div>
