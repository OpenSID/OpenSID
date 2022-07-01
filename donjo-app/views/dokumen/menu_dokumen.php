<div class="col-md-3">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Jenis Produk Hukum</h3>
			<div class="box-tools">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body no-padding">
			<ul class="nav nav-pills nav-stacked">
				<?php for ($i = 1; $i < count($submenu); $i++): ?>
  				<li class="<?php ($_SESSION['submenu'] == $submenu[$i]['id']) && print 'active' ?>"><a href="<?= site_url('dokumen_sekretariat/peraturan_desa/' . $submenu[$i]['id'])?>"><?= $submenu[$i]['nama'] ?></a></li>
				<?php endfor; ?>
			</ul>
		</div>
	</div>
</div>