<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Kategori Menu</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($tip==1): ?>class="active"<?php endif; ?>><a href="<?= site_url('menu/index/1')?>">Menu Statis</a></li>
     	<li <?php if ($tip==2): ?>class="active"<?php endif; ?>><a href="<?= site_url('kategori/clear')?>">Menu Dinamis / Kategori</a></li>
		</ul>
	</div>
</div>