<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
$current_date = date('Y-m-d');
$file = __DIR__ . '/event.json';
?>
<?php if (is_file($file)) : ?>
    <?php
    $json = file_get_contents($file);
    $data = json_decode($json, true);
    ?>
    <?php if (theme_config('defaultevent') == 'true') : ?>
        <?php foreach ($data['momentevent'] as $eventdetail) : ?>
            <?php
            $start_date = theme_config('event_mulai');
            $end_date = theme_config('event_akhir');
			?>
            <?php if ($start_date && $end_date) : ?>
				<?php
				$start_date_obj = DateTime::createFromFormat('d-m-Y', $start_date);
                $end_date_obj = DateTime::createFromFormat('d-m-Y', $end_date);
                $current_date_obj = DateTime::createFromFormat('Y-m-d', $current_date);
				?>
				<?php if ($current_date_obj >= $start_date_obj && $current_date_obj <= $end_date_obj) : ?>
				<?php
				$gambar1 = base_url($this->theme_folder.'/'.$this->theme .'/assets/event/' . $eventdetail['gambar1']);
				$gambar2 = base_url($this->theme_folder.'/'.$this->theme .'/assets/event/' . $eventdetail['gambar2']);
				?>
				<div class="event-body bg-grey-medium">
					<div class="event-container bg-gradient-hor">
						<div class="event-container-bg bg-pattern"></div>
						<div class="container-page">
							<div class="event-inner flexcenter">
								<?php
								$allowed = array('mp4', 'webm', 'ogg');
								$filename = pathinfo($gambar1);
								$ext = $filename['extension'];
								$allowed_pic = array('jpg', 'png', 'jpeg');
								$filename_pic = pathinfo($gambar1);
								$ext_pic = $filename['extension'];
								if (in_array($ext, $allowed)): ?>
								<?php elseif (!IS_PREMIUM && IS_240302 && in_array($ext_pic, $allowed_pic)): ?>
									<div class="event-image-left">
										<img src="<?= $gambar1 ?>" alt="">
									</div>
								<?php endif; ?>						
								<div class="event-title">
									<?php if (theme_config('event_toptitle')): ?>
										<h2 style="font-size:105%;font-weight:500;"><?= theme_config('event_toptitle'); ?></h2>
									<?php endif; ?>
									<?php if (theme_config('event_titlearab')): ?>
										<div class="event-title-arab color-light"><?= theme_config('event_titlearab') ?></div>
									<?php endif; ?>
									<?php if ($eventdetail['titlestandar']): ?>
										<h1><?= $eventdetail['titlestandar'] ?></h1>
									<?php endif; ?>
									<?php if (theme_config('event_subtitle')): ?>
										<h2><?= theme_config('event_subtitle') ?></h2>
									<?php endif; ?>
									<?php if (theme_config('event_desktitle')): ?>
										<p><?= theme_config('event_desktitle') ?></p>
									<?php endif; ?>
								</div>
								<?php
								$allowed2 = array('mp4', 'webm', 'ogg');
								$filename2 = pathinfo($gambar2);
								$ext2 = $filename2['extension'];
								$allowed_pic2 = array('jpg', 'png', 'jpeg');
								$filename_pic2 = pathinfo($gambar2);
								$ext_pic2 = $filename2['extension']; ?>
								<?php if (in_array($ext2, $allowed2)): ?>
								<?php elseif (!IS_PREMIUM && IS_240302 && in_array($ext_pic2, $allowed_pic2)): ?>
									<div class="event-image-right">
										<img src="<?= $gambar2 ?>" alt="" style="height: 100px; width: auto;">
									</div>
								<?php else: ?>
									<?php if ($w_cos): ?>
									<?php foreach ($w_cos as $data): ?>
										<?php if (in_array(strtoupper(strip_tags($data['judul'])), [strtoupper('event')])): ?>
										<div class="event-image-right"><img style="height: 100px; width: auto;" src="<?= to_base64(LOKASI_GAMBAR_WIDGET . $data['foto']); ?>" alt=""></div>
										<?php endif; ?>
									<?php endforeach; ?>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
<?php endif; ?>
