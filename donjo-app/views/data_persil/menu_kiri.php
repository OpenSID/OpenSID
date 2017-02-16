<?php
?>
<legend>Menu Pendataan Persil</legend>
<div class="blok" id="blok_menu">
	<div class="lmenu">
		<ul>
			<li><a href="<?php echo site_url('data_persil/create')?>"><i class="icon-pencil"></i> Tambah Data Persil Baru</a></li>
			<li><a href="<?php echo site_url('data_persil/create_ext')?>"><i class="icon-pencil"></i> Tambah Data (Manual)</a></li>
			<li><a href="<?php echo site_url('data_persil/clear')?>"><i class="icon-th-list"></i> Daftar Persil</a></li>
			<li><a href="<?php echo site_url('data_persil/import')?>" target="ajax-modal" rel="window" header="Import Data Persil"><i class="icon-plus"></i> Import Data Persil</a></li>
		</ul>
	</div>
</div>
<div class="blok" id="blok_jenis">
	<legend><a href="<?php echo site_url('data_persil/persil_jenis');?>">Jenis Persil</a></legend>
	<div class="lmenu"><ul>
		<?php
		if($persil_jenis){
			foreach($persil_jenis as $key=>$item){
				echo "<li><a href=\"".site_url('data_persil/jenis/'.$key.'/')."\" title=\"".$item[1]."\">".$item[0]."</a></li>";
			}
		}
		?>
		<li><a href="<?php echo site_url('data_persil/persil_jenis');?>"><i class="icon-plus"></i> Tambah Data Jenis Persil</a></li>
	</ul></div>
</div>
<div class="blok" id="blok_peruntukan">
	<legend><a href="<?php echo site_url('data_persil/persil_peruntukan');?>">Peruntukan Persil</a></legend>
	<div class="lmenu"><ul>
		<?php
		if($persil_peruntukan){
			foreach($persil_peruntukan as $key=>$item){
				echo "<li><a href=\"".site_url('data_persil/peruntukan/'.$key.'/')."\" title=\"".$item[1]."\">".$item[0]."</a></li>";
			}
		}
		?>
		<li><a href="<?php echo site_url('data_persil/persil_peruntukan');?>"><i class="icon-plus"></i> Tambah Data Peruntukan</a></li>
	</ul></div>
</div>