<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $file = __DIR__ . '/../event/moment.json'; ?>
<?php if(is_file($file)) : ?>
<?php $json = file_get_contents($file); ?>
<?php $array = json_decode($json, true); ?>
<?php if($array['defaultevent']) : ?>

	<?php shuffle($array['momentevent']); foreach($array['momentevent'] as $index => $eventdetail) : ?>
	<?php $gambar1 = base_url($this->theme_folder.'/'.$this->theme .'/event/image/' . $eventdetail['gambar1']) ?>
	<?php $gambar2 = base_url($this->theme_folder.'/'.$this->theme .'/event/image/' . $eventdetail['gambar2']) ?>
	
	<div class="col-default">		
		<div class="event-inner bggrad-color1">
			<div class="merdeka">
			<div class="merahputih" style="background-image:url(<?= base_url("$this->theme_folder/$this->theme/images/animation-color.svg") ?>);"></div>
			</div>
			<div class="event-row margin-minlr-10">
			<?php
				$allowed = array('mp4', 'webm', 'ogg');
				$filename = pathinfo($gambar1);
				$ext = $filename['extension'];
				$allowed_pic = array('jpg', 'png', 'jpeg');
				$filename_pic = pathinfo($gambar1);
				$ext_pic = $filename['extension'];
				if (in_array($ext, $allowed)): ?>
						
			<?php elseif (in_array($ext_pic, $allowed_pic)): ?>
				<div class="event-image-left">
				<div class="padding-leftright-5">
					<img src="<?= $gambar1 ?>">
				</div>
				</div>
			<?php endif; ?>
			
			<?php
				$allowed2 = array('mp4', 'webm', 'ogg');
				$filename2 = pathinfo($gambar2);
				$ext2 = $filename2['extension'];
				$allowed_pic2 = array('jpg', 'png', 'jpeg');
				$filename_pic2 = pathinfo($gambar2);
				$ext_pic2 = $filename2['extension'];
				if (in_array($ext2, $allowed2)): ?>
						
			<?php elseif (in_array($ext_pic2, $allowed_pic2)): ?>
				<div class="event-image-right">
				<div class="padding-leftright-5">
					<img src="<?= $gambar2 ?>">
				</div>	
				</div>	
			<?php endif; ?>
			
			<div class="event-title">
			<div class="padding-leftright-5">
				<div>
				<?php if ($eventdetail['pemerintah_title']): ?>
					<h2>Pemerintah <?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?><br/><font style="font-weight:500;font-size:90%;padding-top:5px;">mengucapkan,</font></h2>
				<?php endif; ?>
				<?php if ($eventdetail['title']): ?>
					<h1><?= $eventdetail['title'] ?></h1>
				<?php endif; ?>
				<?php if ($eventdetail['subtitle']): ?>
					<h2><?= $eventdetail['subtitle'] ?></h2>
				<?php endif; ?>
				<?php if ($eventdetail['desktitle']): ?>
					<p><?= $eventdetail['desktitle'] ?></p>
				<?php endif; ?>
				</div>
			</div>
			</div>
		
			
			</div>
		</div>
	</div>

	<?php endforeach; ?>
	
<?php elseif($array['customevent']) : ?>
	
	<?php shuffle($array['momentevent']); foreach($array['momentevent'] as $index => $eventdetail) : ?>
	<?php $gambar2 = base_url($this->theme_folder.'/'.$this->theme .'/event/image/' . $eventdetail['gambar2']) ?>
		<?php
			$allowed2 = array('mp4', 'webm', 'ogg');
			$filename2 = pathinfo($gambar2);
			$ext2 = $filename2['extension'];
			$allowed_pic2 = array('jpg', 'png', 'jpeg');
			$filename_pic2 = pathinfo($gambar2);
			$ext_pic2 = $filename2['extension'];
			if (in_array($ext2, $allowed2)): ?>
						
		<?php elseif (in_array($ext_pic2, $allowed_pic2)): ?>
			<div class="event-body bg-grey-medium">
			<div class="event-custom">
				<img src="<?= $gambar2 ?>">
			</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
	
<?php endif ?>
<?php endif ?>