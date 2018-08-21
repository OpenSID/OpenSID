<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Pengaturan Peta</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($tip==3): ?>class="active"<?php endif; ?>><a href="<?=site_url('plan/clear')?>">Lokasi</a></li>
      <li <?php if ($tip==0): ?>class="active"<?php endif; ?>><a href="<?=site_url('point/clear')?>">Tipe Lokasi</a></li>
      <li <?php if ($tip==1): ?>class="active"<?php endif; ?>><a href="<?=site_url('garis/clear')?>">Garis</a></li>
      <li <?php if ($tip==2): ?>class="active"<?php endif; ?>><a href="<?=site_url('line/clear')?>">Tipe Garis</a></li>
      <li <?php if ($tip==4): ?>class="active"<?php endif; ?>><a href="<?=site_url('area/clear')?>">Area</a></li>
      <li <?php if ($tip==5): ?>class="active"<?php endif; ?>><a href="<?=site_url('polygon/clear')?>">Tipe Area</a></li>
		</ul>
	</div>
</div>

