<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php $this->load->view("$folder_themes/plus/header_side"); ?>
<div class="mainpage">
	<div class="margin-side-10">
	<div class="mainbodyarea">
		<div class="bigarea hiddenrelative">
		<div class="bigarea-margin">
			<div class="col-default margin-top-10">
				<div class="bodypage bgwhite bordergrey">
					<div class="headsmall headstat-lebar padding-leftright-10 flexcenter" style="padding: 10px 10px 5px !important;">
						<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/sdgs.png") ?>"/>
						<div>
						<h3 class="color-1">SDGs</h3>
						<p>(Sustainable Development Goals)</p>
						</div>
					</div>
					<div class="pagebox">
						<div class="relative-hidden plr-15 ptb-15">
							<?php $evaluasi = sdgs() ?>
							<?php if ($error_msg = $evaluasi->error_msg): ?>
								<div class="alert alert-danger">
									<b><?= $error_msg ?></b>
								</div>
							<?php else: ?>	
								<div class="info-box sdgshead bgwhite bordergrey" style="display: flex;justify-content: center;padding:15px !important;margin-bottom:5px !important;">
									<span class="info-box-number total-bumds" style="text-align: center;" id="total">
									<span class="info-box-text desc-bumds" style="text-align: center;">Skor SDGs <?= ucwords($this->setting->sebutan_desa) ?><br/><font style="font-size:140%;"><?= $evaluasi->average ?></font></span>
									</span>
								</div>
								<div class="sdgs-row margin-minlr-5">
								<?php foreach ($evaluasi->data as $key => $value): ?>
									<div class="sdgs-col bgwhite bordergrey">
										<div class="icon-sdgs">
											<div class="padding-leftright-10"><img src="<?= asset("images/sdgs/{$value->image}") ?>" alt="sdgs-logo"></div>
										</div>
										<div class="sdgs-point bordergrey">
											<p>Nilai</p>
											<h2><?= $value->score ?></h2>
										</div>
									</div>
									<?php endforeach ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php $this->load->view("$folder_themes/plus/middle_page"); ?>
		</div>
		</div>
		<div class="rightsidearea">
			<?php $this->load->view("$folder_themes/plus/side"); ?>			
		</div>
	</div>
	</div>
</div>