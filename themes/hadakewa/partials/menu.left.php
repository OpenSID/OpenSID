<div id='cssmenu'>
	<ul id="global-nav" class="main">
		<li><a href="<?php echo site_url()."first"?>">Beranda</a></li>
	<?php foreach($menu_kiri as $data){?>
		<?php echo $data['menu']?>
	<?php }?>
	</ul>
</div>
