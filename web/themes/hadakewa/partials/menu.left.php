<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id='cssmenu'>
	<ul id="global-nav" class="main">
	<?php foreach($menu_kiri as $data){?>
		<?php echo $data['menu']?>
	<?php }?>
	</ul>
</div>
