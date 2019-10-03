<div class="col-md-3">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Jenis Dokumen</h3>
			<div class="box-tools">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body no-padding">
			<ul class="nav nav-pills nav-stacked">
				<?php foreach($submenu as $s): ?>
				<li <?php if ($_SESSION['submenu'] == $s['id']): ?>class="active"<?php endif; ?>><a href="<?= site_url('dokumen_sekretariat/peraturan_desa/'.$s['id'])?>"><?= $s['kategori'] ?></a></li>
				<?php endforeach;?>
			</ul>
		</div>
	</div>
</div>