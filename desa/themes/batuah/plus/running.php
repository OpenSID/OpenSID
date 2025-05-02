<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="col-default">
<div class="running bgwhite bordergrey1 flexleft">
	<div class="running-title bgcolor-3 flexleft">
	<svg viewBox="0 0 24 24"><path d="M13,9H11V7H13M13,17H11V11H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" /></svg>
	Info
	</div>
	<?php if (!empty($teks_berjalan)): ?>
		<marquee onmouseover="this.stop()" onmouseout="this.start()" style="margin:0;padding:0;line-height:1.2;" class="flexleft">
			<?php foreach ($teks_berjalan AS $teks): ?>
	<span style="font-family:Arial !important;font-size:100% !important;padding-right: 50px;">
		<?= $teks['teks']?>
		<?php if ($teks['tautan']): ?>
			<a href="<?= $teks['tautan'] ?>" rel="noopener noreferrer" title="Baca Selengkapnya"><?= $teks['judul_tautan']?></a>
		<?php endif; ?>
	</span>
<?php endforeach; ?>
		</marquee>
	<?php endif; ?>
</div>
</div>