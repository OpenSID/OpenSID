<div id="nav">
<ul>
	<li class="nonaktif" <?php if($this->tab_ini == 1){?>class="selected"<?php }?>>
		<a href="#">Surat Keluar</a>
	</li>
	<li <?php if($this->tab_ini == 2){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('surat_masuk/clear')?>">Surat Masuk</a>
	</li>
	<li <?php if($this->tab_ini == 3){?>class="selected"<?php }?>>
		<a href="<?php echo site_url("dokumen_sekretariat/index/2")?>">SK KADES</a>
	</li>
	<li <?php if($this->tab_ini == 4){?>class="selected"<?php }?>>
		<a href="<?php echo site_url("dokumen_sekretariat/index/3")?>">PERDES</a>
	</li>
	<li <?php if($this->tab_ini == 5){?>class="selected"<?php }?>>
		<a href="<?php echo site_url("inventaris")?>">Inventaris Desa</a>
	</li>
</ul>
</div>
