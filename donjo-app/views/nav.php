<div id="nav">
	<ul>
	<li <?if($act==1){?>class="selected"<?}?>>
		<a href="<?=site_url('database')?>">Export</a>
	</li>
	<li <?if($act==2){?>class="selected"<?}?>>
		<a href="<?=site_url('database/import')?>">Import Excel</a>
	</li>
	<li <?if($act==3){?>class="selected"<?}?>>
		<a href="<?=site_url('database/backup')?>">Backup/Restore</a>
	</li>
	</ul>
</div>
