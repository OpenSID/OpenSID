<link rel="stylesheet" id="rps-style-css" href="<?php echo base_url()?>assets/front/css/slide.css" type="text/css" media="all">
<script type="text/javascript" src="<?php echo base_url()?>assets/front/js/jquery_003.js"></script>
<script type="text/javascript">
$j = jQuery.noConflict();
$j(document).ready(function() {$j("#rps .paging").show();$j("#rps .paging a:first").addClass("active");
$j(".slide").css({"width" : 925});
$j("#rps .window").css({"width" : 925});
$j("#rps .window").css({"height" : 110});
$j("#rps .col").css({"width" : 300});
$j("#rps .col").css({"height" : 110});
$j("#rps .col p.post-title span").css({"color" : "#99c"});
$j("#rps .post-date").css({"top" : 80});
$j("#rps .post-date").css({"width" : 220.5});var imageWidth = $j("#rps .window").width();
var imageReelWidth = imageWidth * 3;
$j("#rps .slider").css({"width" : imageReelWidth});
rotate = function(){
var triggerID = $active.attr("rel") - 1; 
var sliderPosition = triggerID * imageWidth; 
$j("#rps .paging a").removeClass("active"); 
$active.addClass("active");
$j("#rps .slider").stop(true,false).animate({ 
left: -sliderPosition
}, 500 );
}; 
var play;
rotateSwitch = function(){
play = setInterval(function(){ 
$active = $j("#rps .paging a.active").next();
if ( $active.length === 0) { 
$active = $j("#rps .paging a:first"); 
rotate(); 
}, 5070);
};
rotateSwitch(); 
$j("#rps .slider a").hover(function() {
clearInterval(play); 
}, function() {
rotateSwitch(); 
});
$j("#rps .paging a").click(function() {
$active = $j(this); 
clearInterval(play); 
rotate(); 
rotateSwitch(); 
return false; 
});
});
</script>
<div id="rps">
<div class="window">
<div class="slider">
<div class="slide">
<?php $j=1;foreach($slide AS $data){?>
<div class="slider-content-img">
<a href="<?php echo site_url("first/artikel/$data[id]")?>">
<?php if($data['gambar']!=''){?>
		<?php if(is_file("assets/files/artikel/kecil_".$data['gambar'])) {?>
			<img src="<?php echo base_url()?>/assets/files/artikel/kecil_<?php echo $data['gambar']?>" width="120" height="90">
		
<?php }?>
</a>
	<?php }?>
</div>
	<?php }?>
</div>
</div>
</div>
</div>