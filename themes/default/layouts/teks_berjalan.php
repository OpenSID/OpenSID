<!--
	Untuk bisa menghentikan scroller, perlu menambah plugin jquery.pause
	dan mengubah jquery.cycle2.carousel.js, mengikuti contoh di
	https://github.com/malsup/cycle2/issues/178
 -->

<script src="<?php echo base_url()?>assets/js/jquery.pause.min.js"></script>

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
	});
</script>
<div id="scroller" style="margin-bottom: 0px;">
  <?php foreach($teks_berjalan AS $data) {?>
  	<span style="vertical-align: middle; color: white; font: bold 8pt Arial; padding-right: 200px;"><?php echo $data['isi']?></span>
  <?php }?>
  <span>&nbsp;</span>
</div>