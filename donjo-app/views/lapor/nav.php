<div id="nav">
<ul>
	<li <?php if($act==0){?>class="selected"<?php }?>>
		<a href="<?php echo site_url('lapor/index/1')?>">Laporan Masuk</a>
	</li>
	<?php if($_SESSION['grup']==1 || $_SESSION['grup']==2){?>
  	<li <?php if($act==1){?>class="selected"<?php }?>>
  		<a href="<?php echo site_url('mandiri/clear')?>">Layanan Mandiri</a>
  	</li>
	<?php }?>
</ul>
</div>
