<div id="nav">
	<ul>
	<li <?php if($act==1){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database')?>">Export</a>
	</li>
	<li <?php if($act==2){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/import')?>">Import Excel</a>
	</li>
	<li <?php if($act==5){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/import_bip')?>">Import BIP</a>
	</li>
	<li <?php if($act==3){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/backup')?>">Backup/Restore</a>
	</li>
	<li <?php if($act==4){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/import_ppls')?>">Import PPLS</a>
	</li>
	<li <?php if($act==6){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/migrasi_cri')?>">Migrasi DB</a>
	</li>
	</ul>
</div>
