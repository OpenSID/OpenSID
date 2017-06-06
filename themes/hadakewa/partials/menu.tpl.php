<?php if(is_file("desa/css/".$this->theme."/front/default.css")){?>
  <link type='text/css' href="<?php echo base_url()?>desa/css/<?php echo $this->theme ?>/front/default.css" rel='Stylesheet' />
<?php } else { ?>
  <link type='text/css' href="<?php echo base_url()?>assets/front/css/default.css" rel='Stylesheet' />
<?php } ?>

<?php if(is_file("desa/css/".$this->theme."/desa-default.css")):?>
  <link type='text/css' href="<?php echo base_url()?>desa/css/<?php echo $this->theme ?>/desa-default.css" rel='Stylesheet' />
<?php endif; ?>

<div id='cssmenu'>
	<ul id="global-nav" class="top">
		<li><a href="<?php echo site_url()."first"?>"><i class="fa fa-home fa-lg"></i> Beranda</a></li>
	<?php foreach($menu_atas as $data){?>
		<?php echo $data['menu']?>
	<?php }?>
	</ul>
	<ul id="global-nav-right" class="top">	
	  <li style="border-left: 1px solid #ccc"><a href="<?php echo site_url('siteman') ?>"><i class="fa fa-lock fa-lg"></i> Login Admin</a></li>
	  <li style="border-left: 1px solid #ccc"><a href="https://www.facebook.com/groups/1323978001042529/" target="_blank"><span style="color:#083d74" ><i class="fa fa-facebook-square fa-2x"></i></span></a></li>
	  <li><a href="https://twitter.com/alhawarizmi_sh" target="_blank"><span style="color:#0ccdd9"><i class="fa fa-twitter-square fa-2x"></i></span></a></li>
	  <li><a href="https://www.youtube.com/channel/UCriD_Mi9StAsaSyYaa0dyKw" target="_blank"><span style="color:#c2080c"><i class="fa fa-youtube fa-2x"></i></span></a></li>
	  <li><a href="https://plus.google.com/u/0/109529517203605062291" target="_blank"><span style="color:#c2080c"><i class="fa fa-google-plus-square fa-2x"></i></span></a></li>
	</ul>
</div>
