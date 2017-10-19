<div id="nav">
	<ul>
	<li <?php if($act==1){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database')?>">Ekspor Database</a>
	</li>
	<li <?php if($act==2){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/import')?>">Impor Database</a>
	</li>
	<li <?php if($act==5){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/import_bip')?>">Impor BIP</a>
	</li>
	<li <?php if($act==3){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/backup')?>">Backup/Restore</a>
	</li>
	<li <?php if($act==6){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('database/migrasi_cri')?>">Migrasi DB</a>
	</li>
	</ul>
</div>
