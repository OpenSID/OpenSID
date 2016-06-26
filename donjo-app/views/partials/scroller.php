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
//var imageSum = $j("#rps .slider div").size();
var imageReelWidth = imageWidth * 3;

//Adjust the image reel to its new size
$j("#rps .slider").css({"width" : imageReelWidth});

//Paging + Slider Function
rotate = function(){
var triggerID = $active.attr("rel") - 1; //Get number of times to slide
//alert(triggerID);
var sliderPosition = triggerID * imageWidth; //Determines the distance the image reel needs to slide

$j("#rps .paging a").removeClass("active");
$active.addClass("active");

//Slider Animation
$j("#rps .slider").stop(true,false).animate({
left: -sliderPosition
}, 500 );
};
var play;
//Rotation + Timing Event
rotateSwitch = function(){
play = setInterval(function(){ //Set timer - this will repeat itself every 3 seconds
$active = $j("#rps .paging a.active").next();
if ( $active.length === 0) { //If paging reaches the end...
$active = $j("#rps .paging a:first"); //go back to first
}
rotate(); //Trigger the paging and slider function
}, 5070);
};

rotateSwitch(); //Run function on launch

//On Hover
$j("#rps .slider a").hover(function() {
clearInterval(play); //Stop the rotation
}, function() {
rotateSwitch(); //Resume rotation
});

//On Click
$j("#rps .paging a").click(function() {
$active = $j(this); //Activate the clicked paging
//Reset Timer
clearInterval(play); //Stop the rotation
rotate(); //Trigger rotation immediately
rotateSwitch(); // Resume rotation
return false; //Prevent browser jump to link anchor
});
});

</script>
<div id="rps">
<div class="window">
<div class="slider">
<div class="slide">
<?php  $j=1;foreach($slide AS $data){?>
<div class="slider-content-img">
<a href="<?php echo site_url("first/artikel/$data[id]")?>">
<?php  if($data['gambar']!=''){?>
		<?php  if(is_file(LOKASI_FOTO_ARTIKEL."kecil_".$data['gambar'])) {?>
			<img src="<?php echo AmbilFotoArtikel($data['gambar'],'kecil')?>" width="120" height="90">

<?php  }?>
</a>
	<?php  }?>
</div>
	<?php  }?>
</div>
</div>
</div>
</div>