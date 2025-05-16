<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $file = __DIR__ . '/../linkplus/link.json'; ?>
<?php if(is_file($file)) : ?>
<?php $json = file_get_contents($file); ?>
<?php $array = json_decode($json, true); ?>
<?php if($array['aktif']) : ?>

<div class="col-default">
	<div class="widget-head bggrad-color2 flexleft" style="border-radius:5px 5px 0 0;">
		<svg viewBox="0 0 24 24"><path d="M12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10M4,4H11A2,2 0 0,1 13,6V9H11V6H4V11H6V9L9,12L6,15V13H4A2,2 0 0,1 2,11V6A2,2 0 0,1 4,4M20,20H13A2,2 0 0,1 11,18V15H13V18H20V13H18V15L15,12L18,9V11H20A2,2 0 0,1 22,13V18A2,2 0 0,1 20,20Z" /></svg>
		<h1>Link</h1>
	</div>
	<div class="link-custom bgwhite bordergrey1">
		<div class="rowsame margin-minlr-5">
			<?php ($array['linkplus']); foreach($array['linkplus'] as $index => $forlink) : ?>
				<div class="link-col">
					<?php $gambar = base_url($this->theme_folder.'/'.$this->theme .'/linkplus/icon/' . $forlink['gambar']) ?>
					<a href="<?= $forlink['link'] ?>" target="blank">
						<?php
						$allowed = array('mp4', 'webm', 'ogg');
						$filename = pathinfo($gambar);
						$ext = $filename['extension'];
						$allowed_pic = array('jpg', 'png', 'jpeg');
						$filename_pic = pathinfo($gambar);
						$ext_pic = $filename['extension'];
						if (in_array($ext, $allowed)): ?>
						<?php elseif (in_array($ext_pic, $allowed_pic)): ?>
							<img src="<?= $gambar ?>">
						<?php else: ?>
							<img src="<?= gambar_desa($desa['logo']);?>"/>
						<?php endif; ?>
						<div class="flexcenter">
							<p><?= $forlink['judulitem'] ?></p>
						</div>	
					</a>
				</div>
			<?php endforeach; ?>
		</div>	
	</div>
</div>

<?php endif ?>
<?php endif ?>
