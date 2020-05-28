<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!--
	Untuk bisa menghentikan scroller, perlu menambah plugin jquery.pause
	dan mengubah jquery.cycle2.carousel.js, mengikuti contoh di
	https://github.com/malsup/cycle2/issues/178
 -->

<?php if($teks_berjalan): ?>
	<script src="<?php echo base_url()?>assets/front/js/jquery.pause.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function()
		{
			$('#scroller').cycle(
			{
				fx: 'carousel',
				speed: 20000,
				timeout: '10',
				easing: 'linear',
				pauseOnHover: true,
				slides: '> span',
				throttleSpeed: true
			});

			$('#scroller').on('cycle-paused', function(event, opts)
			{
				$('#scroller span.cycle-slide').each(function() {
						this.style.color = "#ffff00";
				});
			});

			$('#scroller').on('cycle-resumed', function(event, opts)
			{
				$('#scroller span.cycle-slide').each(function() {
						this.style.color = "#ffffff";
				});
			});
		});
	</script>

	<div id="scroller" class="teks_berjalan" style="margin-bottom: 0px; padding-bottom: 3px;">
		<?php foreach ($teks_berjalan AS $teks): ?>
			<span class="teks">
				<?= $teks['teks']?>
				<?php if ($teks['tautan']): ?>
					<a href="<?=$teks['tautan']?>" target="_blank"><?=$teks['judul_tautan']?></a>
				<?php endif; ?>
			</span>
		<?php endforeach; ?>
		<span>&nbsp;</span>
	</div>
<?php endif; ?>
