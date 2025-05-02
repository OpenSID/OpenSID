<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<?php $this->load->view("$folder_themes/plus/header_side"); ?>
<div class="mainpage">
	<div class="margin-side-10">
	<div class="mainbodyarea">
		<div class="bigarea hiddenrelative">
		<div class="bigarea-margin">
			<div class="col-default margin-top-10">
				<div class="bodypage bgwhite bordergrey">
					<div class="headstat-lebar flexcenter">
									<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pembangunan.png") ?>"/>
									<div>
									<h1 class="color-1">Pemerintah</h1>
									<h2><?= ucwords($this->setting->sebutan_desa); ?></h2>
									</div>
					</div>
					<div class="pagebox">
						<div class="rowsame margin-minlr-5">
							<?php foreach ($pemerintah as $data): ?>
								<div class="aparatur-col">
									<a data-fancybox="gallery" href="<?= $data['foto'] ?>" data-caption="<?= $data['nama'] ?><br/><?= $data['jabatan'] ?>">
									<div class="image-pemerintah mb-10">
									<img src="<?= $data['foto'] ?>">
									</div>
									</a>
									<div class="pemerintah-title" style="padding-bottom:50px;">
									<h2 class="color-2"><?= $data['nama'] ?></h2>
									<h3><?= $data['jabatan'] ?></h3>
									</div>
										
									<div class="pemerintah-status flexcenter">
									<div>
									<?php if (count($media_sosial) > 0) : ?>
									<div class="flexcenter" style="position:relative;overflow:text-align:center;margin:0 0 3px 15px;">
												
													<?php  $sosmed_pengurus = json_decode($data['media_sosial'], true); ?>
													<?php foreach ($media_sosial as $value): ?>
														<?php if ($sosmed_pengurus[$value['id']]): ?>
															<a href="<?= $sosmed_pengurus[$value['id']] ?>" target="_blank" style="padding:  5px 5px 0;margin:0!important;">
																<span style="color:#919191;font-size:110%;"><i class="fa fa-<?=$value['id']?>"></i></span>
															</a>
														<?php else : ?>
															<a style="padding: 5px 5px 0;margin:0!important;">
																<span style="color:#919191;font-size:110%;"><i class="fa fa-<?=$value['id']?>"></i></span>
															</a>
														<?php endif ?>
													<?php endforeach ?>
												</div>	
									<?php endif ?>
										
									<?php if ($this->setting->tampilkan_kehadiran && $data['status_kehadiran'] == 'hadir') : ?>
										<div class="flexcenter">
										<div class="ada flexleft bghijau">Hadir</div>
										</div>
									<?php else: ?>
										<div class="flexcenter">
										<div class="tidakada flexleft bgmerah">Belum Rekap Kehadiran</div>
										</div>
									<?php endif ?>
										
									</div>
									</div>
								</div>
							<?php endforeach ?>
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