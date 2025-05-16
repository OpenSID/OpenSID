<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if($headline) : ?>
<div class="col-default">
<div class="rowsame headline forhover">
	
	<div class="headline-text bgcolor-1 color-white flexleft">
		<div class="headline-padding">
		<h1>Headline</h1>
		<a href="<?= site_url('artikel/'.buat_slug($headline))?>">	
			<h2><?= $headline['judul'] ?></h2>
			<p><?= potong_teks ($headline['isi'], 90); ?>...</p>
		</a>	
		</div>
	<div class="headline-head">
	<svg viewBox="0 0 945.000000 187.000000"><g transform="translate(0.000000,187.000000) scale(0.100000,-0.100000)"><path d="M0 935 l0 -935 175 0 175 0 0 420 0 420 235 0 235 0 0 -420 0 -420 180 0 180 0 0 935 0 935 -180 0 -180 0 0 -365 0 -365 -235 0 -235 0 0 365 0 365 -175 0 -175 0 0 -935z"/><path d="M1420 935 l0 -935 495 0 495 0 0 160 0 160 -315 0 -315 0 0 250 0 250 285 0 285 0 0 155 0 155 -285 0 -285 0 0 210 0 210 310 0 310 0 0 160 0 160 -490 0 -490 0 0 -935z"/><path d="M2875 1858 c-7 -20 -415 -1837 -415 -1848 0 -6 70 -10 185 -10 167 0 185 2 185 17 0 9 19 115 42 235 l42 218 195 0 196 0 44 -235 45 -235 193 0 c106 0 193 3 193 6 0 3 -94 422 -210 930 -115 508 -210 926 -210 929 0 3 -108 5 -240 5 -184 0 -242 -3 -245 -12z m245 -395 c0 -10 29 -171 65 -358 36 -187 65 -341 65 -342 0 -2 -63 -3 -141 -3 -129 0 -140 1 -135 18 2 9 33 171 68 359 35 189 66 343 71 343 4 0 7 -8 7 -17z"/><path d="M3890 935 l0 -935 353 0 c385 1 468 9 562 55 67 32 124 95 168 182 78 153 97 276 104 663 6 306 -3 473 -32 585 -53 204 -188 341 -369 374 -36 7 -205 11 -423 11 l-363 0 0 -935z m675 633 c61 -33 100 -98 124 -208 33 -149 30 -707 -4 -855 -24 -104 -60 -165 -115 -193 -39 -19 -69 -23 -188 -29 l-142 -6 0 658 0 657 148 -4 c110 -3 155 -8 177 -20z"/><path d="M5290 935 l0 -935 495 0 495 0 0 160 0 160 -315 0 -315 0 0 775 0 775 -180 0 -180 0 0 -935z"/><path d="M6440 935 l0 -935 180 0 180 0 0 935 0 935 -180 0 -180 0 0 -935z"/><path d="M7040 935 l0 -935 171 0 171 0 -4 634 c-2 349 -2 634 -1 633 1 -1 105 -279 231 -617 127 -338 234 -623 237 -632 6 -16 25 -18 191 -18 l184 0 0 935 0 935 -172 0 -171 0 6 -637 6 -638 -88 235 c-48 129 -155 416 -237 637 l-149 403 -187 0 -188 0 0 -935z"/><path d="M8460 935 l0 -935 495 0 495 0 0 160 0 160 -315 0 -315 0 0 250 0 250 290 0 290 0 0 155 0 155 -290 0 -290 0 0 210 0 210 310 0 310 0 0 160 0 160 -490 0 -490 0 0 -935z"/></g></svg>
	</div>	
	</div>
	<div class="headline-image">
		<div class="headline-image-inner">
		<?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_".$headline['gambar'])): ?>
			<img src="<?= AmbilFotoArtikel($headline['gambar'],'sedang') ?>">
		<?php else: ?>
			<img src="<?= base_url("$this->theme_folder/$this->theme/images/pengganti.jpg") ?>"/>
			<div class="logo-no-image"><img src="<?= gambar_desa($desa['logo']);?>"/></div>
		<?php endif; ?>
		</div>
		<div class="hoverimage flexcenter">
			<div class="hoverimage-left bgcolor-1 hover-width"></div>
			<div>
				<a href="<?= AmbilFotoArtikel($headline['gambar'],'sedang') ?>"  data-fancybox="images" data-caption="<?= $headline['judul'] ?>">
				<div class="hoverimage-icon bgcolor-1 flexcenter hover-height">
					<svg viewBox="0 0 24 24"><path d="M9.5,13.09L10.91,14.5L6.41,19H10V21H3V14H5V17.59L9.5,13.09M10.91,9.5L9.5,10.91L5,6.41V10H3V3H10V5H6.41L10.91,9.5M14.5,13.09L19,17.59V14H21V21H14V19H17.59L13.09,14.5L14.5,13.09M13.09,9.5L17.59,5H14V3H21V10H19V6.41L14.5,10.91L13.09,9.5Z"/></svg>
				</div>
				</a>
				<a href="<?= site_url('artikel/'.buat_slug($headline))?>">
				<div class="hoverimage-icon bgcolor-1 flexcenter hover-height">
					<svg viewBox="0 0 24 24"><path d="M20,18H22V6H20M11.59,7.41L15.17,11H1V13H15.17L11.59,16.58L13,18L19,12L13,6L11.59,7.41Z"/></svg>
				</div>
				</a>
			</div>
		</div>
		<div class="headline-image-border"></div>
	</div>
</div>
</div>
<?php endif; ?>	