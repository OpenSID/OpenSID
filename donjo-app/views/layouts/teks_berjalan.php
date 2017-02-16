<div id="scroller">
<script type="text/javascript">
jQuery.fn.liScroll = function(settings) {
settings = jQuery.extend({
travelocity: 0.03
}, settings);
return this.each(function(){
$('ul#ticker01').show();
var $strip = jQuery(this);
$strip.addClass("newsticker")
var stripWidth = 1;
$strip.find("li").each(function(i){
stripWidth += jQuery(this, i).outerWidth(true);
});
var $mask = $strip.wrap("<div class='mask'></div>");
var $tickercontainer = $strip.parent().wrap("<div class='tickercontainer'></div>");
var containerWidth = $strip.parent().parent().width();
$strip.width(stripWidth);
var totalTravel = stripWidth+containerWidth;
var defTiming = totalTravel/settings.travelocity;
function scrollnews(spazio, tempo){
$strip.animate({left: '-='+ spazio}, tempo, "linear", function(){$strip.css("left", containerWidth); scrollnews(totalTravel, defTiming);});
}
scrollnews(totalTravel, defTiming);
$strip.hover(function(){
jQuery(this).stop();
},
function(){
var offset = jQuery(this).offset();
var residualSpace = offset.left + stripWidth;
var residualTime = residualSpace/settings.travelocity;
scrollnews(residualSpace, residualTime);
});
});
};
$(function(){$("ul#ticker01").liScroll();});
</script>
<style>
.tickercontainer {
width: 970px; 
height: 24px; 
margin-left: 5px; 
padding: 0;
overflow: hidden; 
}
.tickercontainer .mask {
position: relative;
left: 0px;
top: 1px;
width: 12555px;
overflow: hidden;
}
ul.newsticker {
position: relative;
left: 900px;
font: bold 10px Verdana;
list-style-type: none;
margin: 0;
padding: 0;

}
ul.newsticker li {
float: left;
margin: 0;
padding: 0 10px;
color: #fefe77;
}
</style>
<ul id="ticker01" style="display:none;">
	<?php $tb=0;foreach($teks_berjalan AS $data){?>
	<li> <?php echo fixTag($data['isi']);?> </li><li> | </li>
	<?php $tb++;} ?> </li>
</ul>
</div>