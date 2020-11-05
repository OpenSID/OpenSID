<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section class="container --flex --justify-between --mt-4 --flex-lg-only">
	<?php foreach($data_widget as $subdata_name => $subdatas) : ?>
		<div class="column --mx-2">
			<div class="panel">
				<div class="panel__header">
					<h3 class="panel__heading"><?= ($subdatas['laporan'])?></h3>
				</div>
				<div class="panel__body">
					<?php foreach($subdatas as $key => $subdata) : ?>
						<?php if($subdata['judul'] != NULL and $key != 'laporan' and $subdata['realisasi'] != 0 or $subdata['anggaran'] != 0): ?>
							<div class="progress">
								<label class="progress__title"><?= ucwords(strtolower($subdata['judul'])) ?></label>
								<div class="--flex --justify-between">
									<span class="progress__subtitle">Rp<?= number_format($subdata['realisasi']) ?></span>
									<span class="progress__subtitle">Rp<?= number_format($subdata['anggaran']) ?></span>
								</div>
								<div class="progress-bar">
									<div class="progress-bar__item" style="width:<?= $subdata['persen'] ?>%">
										<span class="progress-bar__caption"><?= $subdata['persen'] ?>%</span>
									</div>
								</div>
							</div>
						<?php endif ?>
					<?php endforeach ?>
				</div>
			</div>
		</div>
	<?php endforeach ?>
</section>