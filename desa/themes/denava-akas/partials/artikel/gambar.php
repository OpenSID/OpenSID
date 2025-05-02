<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
	.isi-content img{max-width:100% !important;max-width:100% !important;}
</style>
<div class="imglist">
	<?php if ($single_artikel['gambar1']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar1'])): ?>
		<div class="carouselcustom js-flickity" data-flickity-options='{ "wrapAround": true }'>
			<?php if ($single_artikel['gambar']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar'])): ?>
				<div class="carouselcustom-item">
					<div class="image-article-page">
						<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>" alt=""/>
						<a href="<?= AmbilFotoArtikel($single_artikel['gambar'],'sedang') ?>"  data-fancybox="images" data-caption="<?= $single_artikel["judul"]?>"><div class="image-zoom"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/fullscreen.svg") ?>" alt=""/></div></a>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($single_artikel['gambar1']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar1'])): ?>
				<div class="carouselcustom-item">
					<div class="image-article-page">
						<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= AmbilFotoArtikel($single_artikel['gambar1'],'sedang')?>" alt=""/>
						<a href="<?= AmbilFotoArtikel($single_artikel['gambar1'],'sedang') ?>"  data-fancybox="images" data-caption="<?= $single_artikel["judul"]?>"><div class="image-zoom"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/fullscreen.svg") ?>" alt=""/></div></a>
					</div>
				</div>
			<?php endif;?>
			<?php if ($single_artikel['gambar2']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar2'])): ?>
				<div class="carouselcustom-item">
					<div class="image-article-page">
						<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= AmbilFotoArtikel($single_artikel['gambar2'],'sedang')?>" alt=""/>
						<a href="<?= AmbilFotoArtikel($single_artikel['gambar2'],'sedang') ?>"  data-fancybox="images" data-caption="<?= $single_artikel["judul"]?>"><div class="image-zoom"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/fullscreen.svg") ?>" alt=""/></div></a>
					</div>
				</div>
			<?php endif;?>
			<?php if ($single_artikel['gambar3']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar3'])): ?>
				<div class="carouselcustom-item">
					<div class="image-article-page">
						<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= AmbilFotoArtikel($single_artikel['gambar3'],'sedang')?>" alt=""/>
						<a href="<?= AmbilFotoArtikel($single_artikel['gambar3'],'sedang') ?>"  data-fancybox="images" data-caption="<?= $single_artikel["judul"]?>"><div class="image-zoom"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/fullscreen.svg") ?>" alt=""/></div></a>
					</div>
				</div>
			<?php endif;?>
		</div>
	<?php else: ?>	
		<?php if ($single_artikel['gambar']!='' and is_file(LOKASI_FOTO_ARTIKEL."sedang_".$single_artikel['gambar'])): ?>
			<img style="display:block;width:100% !important;max-width:100%  !important;height:auto;margin:0 0 15px;" class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= AmbilFotoArtikel($single_artikel['gambar'],'sedang')?>" alt=""/>
		<?php endif; ?>
	<?php endif;?>
</div>
