<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="footer-left">&copy; 2006-<?php echo date("Y");?> <a target="_blank" href="https://github.com/eddieridwan/OpenSID">OpenSID</a> <?php echo AmbilVersi()?>. Pengembangan dari SID <a target="_blank" href="http://www.combine.or.id">COMBINE Resource Institution</a>. Tema dibuat oleh <a href="https://www.facebook.com/agung.adi.5623" target="_blank">Happy Agung</a></div>
<div id="footer-right">
	<ul id="global-nav-right" class="top">
	  <li><a href="<?php echo $sosmed[nested_array_search('Facebook',$sosmed)]['link']?>" target="_blank"><span style="color:#fff" ><i class="fa fa-facebook-square fa-2x"></i></span></a></li>
	  <li><a href="<?php echo $sosmed[nested_array_search('Twitter',$sosmed)]['link']?>" target="_blank"><span style="color:#fff"><i class="fa fa-twitter-square fa-2x"></i></span></a></li>
	  <li><a href="<?php echo $sosmed[nested_array_search('YouTube',$sosmed)]['link']?>" target="_blank"><span style="color:#fff"><i class="fa fa-youtube-square fa-2x"></i></span></a></li>
	  <li><a href="<?php echo $sosmed[nested_array_search('Google Plus',$sosmed)]['link']?>" target="_blank"><span style="color:#fff"><i class="fa fa-google-plus-square fa-2x"></i></span></a></li>
	</ul>
</div>
