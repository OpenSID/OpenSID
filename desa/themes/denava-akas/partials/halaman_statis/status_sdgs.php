<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php $evaluasi = sdgs() ?>
<div class="article-single">
	<div class="container-page mb-20">
		<div class="headingpage border-grey-soft bg-white flexleft" style="border-radius:5px 5px 0 0;">
			<div class="headingpage-image border-grey-soft flexcenter">
				<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/sdgs.png") ?>" alt=""/>
			</div>
			<h2>Skor SDGs <?= setting('kode_desa_bps') ?> - <?= $evaluasi->average ?></h2>
		</div>
		<div class="box-article bg-white" style="border-radius:0 0 5px 5px;">
			<div class="box-statis border-grey-soft" style="border-radius:0 0 5px 5px;">
				<?php foreach ($evaluasi->data as $key => $value): ?>
					<div class="pemb-row bg-grey-medium">
						<div class="column-pemb1">
							<div class="imglist"style="text-align:center;margin:10px 0 10px 0">
								<img src="<?= asset("images/sdgs/{$value->image}") ?>" height="100px" alt="<?= $value->image ?>">
							</div>
						</div>
					<div class="column-pemb2">
						<div class="p-15">
							<table class="table border-grey-soft" style="border:none !important;width:100%;">
								<tr style="border:none !important;">
									<td class="border-grey-soft" style="vertical-align:super;">Nilai</td>
									<td class="border-grey-soft" style="vertical-align:top;text-align:center;">:</td>
									<td class="border-grey-soft" style="vertical-align:top;text-align:center;"><?= $value->score ?></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>	
		</div>
	</div>	
</div>
