<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if($gallery) : ?>
	<div class="galeri-wrapper">
		<?php foreach($gallery as $data) : ?>
			<?php if(is_file(LOKASI_GALERI . "kecil_" . $data['gambar'])) : ?>
				<?php $link = site_url('first/sub_gallery/'.$data['id']) ?>
				<a href="<?= $link ?>" class="item-foto">
					<img src="<?= AmbilGaleri($data['gambar'],'kecil') ?>" alt="<?= $data['nama'] ?>" class="img-fluid" title="<?= $data['nama'] ?>">
				</a>
			<?php endif ?>
		<?php endforeach ?>
	</div>
<?php endif ?>