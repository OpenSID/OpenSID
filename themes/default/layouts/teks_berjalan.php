<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

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
<div id="scroller" style="margin-bottom: 0px;">
	<span style="vertical-align: middle; color: white; font: bold 8pt Arial; padding-right: 200px;"><?= $teks_berjalan?></span>
  <span>&nbsp;</span>
</div>