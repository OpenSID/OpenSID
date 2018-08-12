<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Inventaris</h3>
		<div class="box-tools">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body no-padding">
		<ul class="nav nav-pills nav-stacked">
			<li <?php if ($tip==1): ?>class="active"<?php endif; ?>><a href="<?= site_url('inventaris/form_jenis')?>"><i class="fa fa-plus"></i> Tambah Jenis Barang Baru</a></li>
      <li <?php if ($tip==2): ?>class="active"<?php endif; ?>><a href="<?= site_url('inventaris')?>"><i class="fa fa-list"></i> Daftar Data Inventaris</a></li>
		</ul>
	</div>
</div>

