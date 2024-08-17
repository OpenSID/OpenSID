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

$counter = count($submenu);<?php for ($i = 1; $i < $counter; $i++): ?>
  				<li class="<?php if ($_SESSION['submenu'] == $submenu[$i]['id']) {
                    echo 'active';
                } ?>"><a href="<?= site_url('dokumen_sekretariat/peraturan_desa/' . $submenu[$i]['id'])?>"><?= $submenu[$i]['nama'] ?></a></li>
				<?php endfor; ?>
			</ul>
		</div>
	</div>
</div>
