<div id="nav">
<ul>
	<?php if($_SESSION['grup']==1){?>
		<li <?php if($act==0){?>class="selected"<?php }?>>
			<a href="<?php echo site_url('hom_desa/konfigurasi')?>">Identitas <?php echo ucwords($this->setting->sebutan_desa)?></a>
		</li>
	<?php }?>
	<li <?php if($act==1){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('pengurus')?>">Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?></a>
	</li>
	<li <?php if($act==2){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('hom_desa')?>">SID</a>
	</li>
	<li <?php if($act==3){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('hom_desa/donasi')?>">Donasi</a>
	</li>
</ul>
</div>
