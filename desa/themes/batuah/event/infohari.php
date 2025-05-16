<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>


<svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" style="height:0 !important;">
	<defs>
		<radialGradient id="gradYellow" cx="50%" cy="50%" r="80%" fx="90%" fy="80%"><stop offset="0%" style="stop-color:yellow; stop-opacity:1" /><stop offset="100%" style="stop-color:orange ;stop-opacity:1" /></radialGradient>
		<radialGradient id="gradOrange" cx="50%" cy="50%" r="80%" fx="90%" fy="80%"><stop offset="0%" style="stop-color:#ee7000; stop-opacity:1" /><stop offset="100%" style="stop-color:#ff6600 ;stop-opacity:1" /></radialGradient>
		<radialGradient id="gradDarkGray" cx="50%" cy="50%" r="50%" fx="50%" fy="50%"><stop offset="0%" style="stop-color:white; stop-opacity:1" /><stop offset="70%" style="stop-color:gray; stop-opacity:1" /><stop offset="100%" style="stop-color:dimgray ;stop-opacity:1" /></radialGradient>
		<radialGradient id="gradGray" cx="50%" cy="50%" r="50%" fx="50%" fy="50%"><stop offset="0%" style="stop-color:white; stop-opacity:1" /><stop offset="100%" style="stop-color:darkgray ;stop-opacity:1" /></radialGradient>
		<radialGradient id="gradGray2" cx="50%" cy="50%" r="50%" fx="50%" fy="50%"><stop offset="0%" style="stop-color:#bdbdbd; stop-opacity:1" /><stop offset="100%" style="stop-color:#919191 ;stop-opacity:1" /></radialGradient>
		<radialGradient id="gradGray3" cx="50%" cy="50%" r="50%" fx="50%" fy="50%"><stop offset="0%" style="stop-color:#ffffff; stop-opacity:1" /><stop offset="100%" style="stop-color:#bdbdbd ;stop-opacity:1" /></radialGradient>
		<linearGradient id="gradWhite" x1="40%" y1="50%" x2="90%" y2="90%"><stop offset="0%" style="stop-color:white;stop-opacity:1" /><stop offset="100%" style="stop-color:darkgray;stop-opacity:1" /></linearGradient>     
	</defs>
  
	<symbol id="sun">
    <circle cx="50" cy="50" r="20" fill="url(#gradYellow)" />

    <line x1="50" y1="27" x2="50" y2="2" class="longRay" />
    <line x1="50" y1="73" x2="50" y2="98" class="longRay" />
    <line x1="27" y1="50" x2="2" y2="50" class="longRay" />
    <line x1="73" y1="50" x2="98" y2="50" class="longRay" />
    <line x1="34" y1="34" x2="16" y2="16" class="longRay" />
    <line x1="66" y1="66" x2="84" y2="84" class="longRay" />
    <line x1="34" y1="66" x2="16" y2="84" class="longRay" />
    <line x1="66" y1="34" x2="84" y2="16" class="longRay" />

    <line x1="59" y1="29" x2="66" y2="13" class="shortRay"/>
    <line x1="71" y1="42" x2="87" y2="35" class="shortRay" />
    <line x1="71" y1="58.5" x2="87" y2="65" class="shortRay" />
    <line x1="59" y1="71" x2="66" y2="87" class="shortRay" />
    <line x1="41" y1="71" x2="34" y2="87" class="shortRay" />
    <line x1="29.5" y1="58.5" x2="13" y2="66" class="shortRay" />
    <line x1="29" y1="42" x2="13" y2="35" class="shortRay" />
    <line x1="41" y1="29" x2="35" y2="13" class="shortRay" />
	</symbol>
	
	<symbol id="sun2">
    <circle cx="50" cy="50" r="20" fill="url(#gradOrange)" />

    <line x1="50" y1="27" x2="50" y2="2" class="longRay" />
    <line x1="50" y1="73" x2="50" y2="98" class="longRay" />
    <line x1="27" y1="50" x2="2" y2="50" class="longRay" />
    <line x1="73" y1="50" x2="98" y2="50" class="longRay" />
    <line x1="34" y1="34" x2="16" y2="16" class="longRay" />
    <line x1="66" y1="66" x2="84" y2="84" class="longRay" />
    <line x1="34" y1="66" x2="16" y2="84" class="longRay" />
    <line x1="66" y1="34" x2="84" y2="16" class="longRay" />

    <line x1="59" y1="29" x2="66" y2="13" class="shortRay"/>
    <line x1="71" y1="42" x2="87" y2="35" class="shortRay" />
    <line x1="71" y1="58.5" x2="87" y2="65" class="shortRay" />
    <line x1="59" y1="71" x2="66" y2="87" class="shortRay" />
    <line x1="41" y1="71" x2="34" y2="87" class="shortRay" />
    <line x1="29.5" y1="58.5" x2="13" y2="66" class="shortRay" />
    <line x1="29" y1="42" x2="13" y2="35" class="shortRay" />
    <line x1="41" y1="29" x2="35" y2="13" class="shortRay" />
	</symbol>
  
	<symbol id="moon"><path d="M60,20 A30,30 0 1,0 90,65 22,22 0 1,1 60,20z" fill="url(#gradYellow)"/></symbol>
	<symbol id="star"><polygon points="5,0 2,10 10,4 0,4 8,10" style="fill:url(#gradYellow);fill-rule:nonzero;"/></symbol>
    
	<symbol id="grayCloud"><path  d="M20,15 Q25,0 45,11 Q60,5 60,20 A30,15 5 1,1 20,15 Z" /></symbol>
  
	<symbol id="whiteCloud"><path fill="url(#gradWhite)" d="M11,47 Q13,37 21,42 Q31,30 41,38 A28,21 -25 1,1 35,75 Q23,85 19,73 A12,12 0 0,1 11,47Z" /></symbol>

</svg>

<div class="infohari">
<div class="col-default-right">

<script type='text/javascript'>
 var now = new Date();
 var hours = now.getHours();

 if (hours >= 15 && hours <= 17.59){
document.write('<div class="infohari-item sore flexleft" style="margin:0 !important;"><p>Selamat sore, sukses dan sehat selalu</p><div class="infohari-image flexcenter"><svg class="iconanime" viewbox="0 0 100 100"><use xlink:href="#sun2" x="-12" y="-5"/><use xlink:href="#grayCloud" class="small-cloud" fill="url(#gradGray)" y="18"/><use xlink:href="#whiteCloud" x="7" y="8" /></svg></div></div>');

 } else if (hours >= 18 && hours <= 23.59){
document.write('<div class="infohari-item malam flexleft"><p>Selamat malam dan selamat beristirahat</p><div class="infohari-image"><svg class="iconanime" viewbox="0 0 100 100"><use xlink:href="#moon" x="-15"/><use xlink:href="#star" x="42" y="30" class="stars animated infinite flash"/><use xlink:href="#star" x="61" y="32" class="stars animated infinite flash delay-1s"/><use xlink:href="#star" x="55" y="50" class="stars animated infinite flash delay-2s"/><use xlink:href="#grayCloud" class="small-cloud2" fill="url(#gradGray2)" y="26"/></svg></div></div>');

 } else if (hours >= 0 && hours <= 5.59){
document.write('<div class="infohari-item malam flexleft"><p>Selamat malam dan selamat beristirahat</p><div class="infohari-image"><svg class="iconanime" viewbox="0 0 100 100"><use xlink:href="#moon" x="-15"/><use xlink:href="#star" x="42" y="30" class="stars animated infinite flash"/><use xlink:href="#star" x="61" y="32" class="stars animated infinite flash delay-1s"/><use xlink:href="#star" x="55" y="50" class="stars animated infinite flash delay-2s"/><use xlink:href="#grayCloud" class="small-cloud2" fill="url(#gradGray2)" y="26"/></svg></div></div>');

 } else if (hours >= 6 && hours <= 7.59){
document.write('<div class="infohari-item pagi flexleft" style="margin:0 !important;"><p>Selamat pagi dan selamat beraktivitas</p><div class="infohari-image flexcenter"><svg class="iconanime" viewbox="0 0 100 100"><use xlink:href="#sun" x="-12" y="-5"/><use xlink:href="#grayCloud" class="small-cloud" fill="url(#gradGray)" y="18"/><use xlink:href="#whiteCloud" x="7" y="8" /></svg></div></div>');

 } else if (hours >= 8 && hours <= 11.59){
document.write('<div class="infohari-item pagi flexleft" style="margin:0 !important;"><p>Selamat pagi jelang siang, sukses dan sehat selalu</p><div class="infohari-image flexcenter"><svg class="iconanime" viewbox="0 0 100 100"><use xlink:href="#sun" x="-12" y="-5"/><use xlink:href="#grayCloud" class="small-cloud" fill="url(#gradGray)" y="18"/><use xlink:href="#whiteCloud" x="7" y="8" /></svg></div></div>');
 } else if (hours >= 12 && hours <= 14.59){
document.write('<div class="infohari-item siang flexleft" style="margin:0 !important;"><p>Selamat siang, semoga semua kegiatan berjalan lancar</p><div class="infohari-image flexcenter"><svg class="iconanime" viewbox="0 0 100 100"><use xlink:href="#sun"/></svg></div></div>');
 }
</script>
</div>
</div>

