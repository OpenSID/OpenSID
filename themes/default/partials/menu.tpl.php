<link type='text/css' href="<?php echo base_url()?>assets/front/css/default.css" rel='Stylesheet' />
<?php if(is_file("desa/css/".$this->theme."/desa-default.css")): ?>
  <link type='text/css' href="<?php echo base_url()?>desa/css/<?php echo $this->theme?>/desa-default.css" rel='Stylesheet' />
<?php endif; ?>

<div id='cssmenu'>
	<ul id="global-nav" class="top">
	<?php foreach($menu_atas AS $data){?>
		<?php echo $data['menu']?>
	<?php }?>
	</ul>
</div>

