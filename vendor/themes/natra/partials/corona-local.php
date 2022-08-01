<?php

	defined('BASEPATH') || exit('No direct script access allowed');

	$panel = [
		'default',
		'info',
		'primary',
		'secondary',
		'warning',
		'danger',
		'success',
	];

?>

<div class="archive_style_1" style="font-family: Oswald">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Status COVID-19 di <?= ucwords($this->setting->sebutan_desa); ?></span></h2>
	<div class="row">
		<div style="margin-top:10px;">
			<?php foreach ($covid as $key => $val):
				if ($key >= 7) break;
				if($key >= 3) echo '<br/>'
			?>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<div class="panel panel-<?= $panel[$key]; ?>" style="border: 1px solid">
						<div style="height: 40px;padding:1px" class="panel-heading text-center"><h4><?= $val['nama']; ?></h4></div>
						<div style="height: 40px;padding:1px" class="panel-body text-center">
							<h4><?= number_format($val['jumlah']); ?> <small>Orang</small></h4>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>