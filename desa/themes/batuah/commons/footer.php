<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/wow.min.js") ?>"></script>
<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/slick.min.js") ?>"></script>
<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/custom.js") ?>"></script>
<script>
	$(document).ready(function(){
    $(".tip-top").tooltip({
        placement : 'top'
    });
    $(".tip-right").tooltip({
        placement : 'right'
    });
    $(".tip-bottom").tooltip({
        placement : 'bottom'
    });
    $(".tip-left").tooltip({
        placement : 'left'
    });
	});
</script>
<script type='text/javascript'>
$(function() { $(window).scroll(function() { if($(this).scrollTop()>100) { $('#ScrollToTop').fade()} else { $('#ScrollToTop').fade();}});
$('#ScrollToTop').click(function(){$('html,body').animate({scrollTop:0},1000);return false})});
</script>
