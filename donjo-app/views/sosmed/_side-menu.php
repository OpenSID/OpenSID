<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Media Sosial</h3>
		<div class="box-tools">
  		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
  	<ul class="nav nav-pills nav-stacked">
      <li class="<?php if ($media == 'facebook'): ?>active<?php endif; ?>"><a href="<?= site_url('sosmed')?>"><i class="fa fa-facebook"></i> Facebook</a></li>
      <li class="<?php if ($media == 'twitter'): ?>active<?php endif; ?>"><a href="<?= site_url('sosmed/twitter')?>"><i class="fa fa-twitter"></i> Twitter</a></li>
      <li class="<?php if ($media == 'google'): ?>active<?php endif; ?>"><a href="<?= site_url('sosmed/google')?>"><i class="fa fa-google"></i> Google</a></li>
      <li class="<?php if ($media == 'youtube'): ?>active<?php endif; ?>"><a href="<?= site_url('sosmed/youtube')?>"><i class="fa fa-youtube"></i> Youtube</a></li>
      <li class="<?php if ($media == 'instagram'): ?>active<?php endif; ?>"><a href="<?= site_url('sosmed/instagram')?>"><i class="fa fa-instagram"></i> Instagram</a></li>
		  <li class="<?php if ($media == 'whatsapp'): ?>active<?php endif; ?>"><a href="<?= site_url('sosmed/whatsapp')?>"><i class="fa fa-whatsapp"></i> WhatsApp</a></li>
		</ul>
	</div>
</div>
