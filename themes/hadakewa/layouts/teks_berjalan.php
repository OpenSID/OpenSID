<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!--
	Untuk bisa menghentikan scroller, perlu menambah plugin jquery.pause
	dan mengubah jquery.cycle2.carousel.js, mengikuti contoh di
	https://github.com/malsup/cycle2/issues/178
 -->

<script src="<?= base_url()?>assets/js/jquery.cycle2.min.js"></script>
<script src="<?= base_url()?>assets/js/jquery.cycle2.carousel.js"></script>
<script src="<?= base_url()?>assets/js/jquery.pause.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
    $('#scroller').cycle({
			fx: 'carousel',
			speed: 20000,
			timeout: '10',
			easing: 'linear',
			pauseOnHover: true,
			slides: '> span',
			throttleSpeed: true
		});
		$( '#scroller' ).on( 'cycle-paused', function( event, opts ) {
			$('#scroller span.cycle-slide').each(function() {
		    	this.style.color = "#ffff00";
			});
		});
		$( '#scroller' ).on( 'cycle-resumed', function( event, opts ) {
			$('#scroller span.cycle-slide').each(function() {
		    	this.style.color = "#ffffff";
			});
		});
	});
</script>
<div id="scroller" style="margin-bottom: 0px; padding-bottom: 3px;">
  <?php foreach ($teks_berjalan AS $data): ?>
  	<span style="vertical-align: middle; color: white; font: bold 8pt Arial; padding-right: 200px;"><?= $data['isi']?></span>
  <?php endforeach; ?>
  <span>&nbsp;</span>
</div>
