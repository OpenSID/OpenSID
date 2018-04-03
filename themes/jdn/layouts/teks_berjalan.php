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
  <?php foreach($teks_berjalan AS $data) {?>
  	<span style="vertical-align: middle; color: white; font: bold 8pt Arial; padding-right: 200px;"><?php echo $data['isi']?></span>
  <?php }?>
  <span>&nbsp;</span>
</div>