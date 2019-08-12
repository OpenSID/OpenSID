<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="footer-left">&copy; 2016-<?php echo date("Y");?>
	<a target="_blank" href="https://opendesa.id">OpenDesa</a> <i class="fa fa-circle" style="font-size: smaller;"></i> <a target="_blank" href="https://github.com/OpenSID/OpenSID">OpenSID</a> <?php echo AmbilVersi()?> <i class="fa fa-circle" style="font-size: smaller;"></i> Tema Hadakewa
	<br>Dikembangkan oleh <a target="_blank" href="https://www.facebook.com/groups/OpenSID/">Komunitas OpenSID</a>
	<br/>Dikelola oleh Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?> <?php echo $desa['nama_desa']?>
  <?php if (file_exists('mitra')): ?>
  	<br/>Hosting didukung <a target="_blank" href="https://idcloudhost.com"><img src="<?= base_url('/assets/images/Logo-IDcloudhost.png')?>" height='15px' alt="Logo-IDCloudHost" title="Logo-IDCloudHost"></a>
  <?php endif; ?>
</div>
<div id="footer-right">
	<ul id="global-nav-right" class="top">
	  <li><a href="<?php echo $sosmed[nested_array_search('Facebook',$sosmed)]['link']?>" target="_blank"><span style="color:#fff" ><i class="fa fa-facebook-square fa-2x"></i></span></a></li>
	  <li><a href="<?php echo $sosmed[nested_array_search('Twitter',$sosmed)]['link']?>" target="_blank"><span style="color:#fff"><i class="fa fa-twitter-square fa-2x"></i></span></a></li>
	  <li><a href="<?php echo $sosmed[nested_array_search('YouTube',$sosmed)]['link']?>" target="_blank"><span style="color:#fff"><i class="fa fa-youtube-square fa-2x"></i></span></a></li>
	  <li><a href="<?php echo $sosmed[nested_array_search('Google Plus',$sosmed)]['link']?>" target="_blank"><span style="color:#fff"><i class="fa fa-google-plus-square fa-2x"></i></span></a></li>
	  <li><a href="<?php echo $sosmed[nested_array_search('Instagram',$sosmed)]['link']?>" target="_blank"><span style="color:#fff"><i class="fa fa-instagram fa-2x"></i></span></a></li>
	</ul>
</div>
