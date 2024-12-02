
var tw = new Date();
	if (tw.getTimezoneOffset() == 0) (a=tw.getTime() + ( 7 *60*60*1000))
	else (a=tw.getTime());
	tw.setTime(a);
var tahun= tw.getFullYear ();
var hari= tw.getDay ();
var bulan= tw.getMonth ();
var tanggal= tw.getDate ();
var hariarray=new Array("Minggu,","Senin,","Selasa,","Rabu,","Kamis,","Jum'at,","Sabtu,");
var bulanarray=new Array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
document.getElementById("tanggal").innerHTML = hariarray[hari]+" "+tanggal+" "+bulanarray[bulan]+" "+tahun;

function animation(span) {
	span.className = "turn";
	setTimeout(function () {
	span.className = ""
	}, 700);
}
function thistime() {
	setInterval(function () {
	var waktu = new Date();
	var thistime   = document.getElementById('thistime');
	var hours = waktu.getHours();
	var minutes = waktu.getMinutes();
	var seconds = waktu.getSeconds();
		if (waktu.getHours() < 10) {
			hours = '0' + waktu.getHours();
		}
		if (waktu.getMinutes() < 10) {
			minutes = '0' + waktu.getMinutes();
		}
		if (waktu.getSeconds() < 10) {
			seconds = '0' + waktu.getSeconds();
		}
	thistime.innerHTML  = '<span class="jammenit">' + hours + ':</span>' 
		+ '<span class="jammenit">' + minutes + ':</span>'
		+ '<span class="jammenit">' + seconds +'</span>';
		var spans      = thistime.getElementsByTagName('span');
		animation(spans[2]);
		if (seconds == 0) animation(spans[1]);
		if (minutes == 0 && seconds == 0) animation(spans[0]);
	}, 1000);
	}
thistime();

function setDarkMode(isDark){var darkBtn=document.getElementById('darkBtn')
var lightBtn=document.getElementById('lightBtn')
if(isDark){lightBtn.style.display="block"
darkBtn.style.display="none"}else{lightBtn.style.display="none"
darkBtn.style.display="block"}
document.body.classList.toggle("darkmode")}
if(localStorage.getItem('theme')=='dark')
setDarkMode()
function setDarkMode(){
 let emoticon = ''
 let isDark = document.body.classList.toggle('darkmode')
 if (isDark) 
 {      
 emoticon = '<div class="darklight light difle-l"><div class="darklight-icon difle-c"><img src="images/icon/light.png"></div><p>Terangkan Layar</p></div>'      
 localStorage.setItem('theme','dark')
 } else {      
 emoticon = '<div class="darklight difle-l"><div class="darklight-icon difle-c"><img src="images/icon/dark.png"></div><p>Gelapkan Layar</p></div>'
 localStorage.removeItem('theme')    }    
 document.getElementById('darkBtn').innerHTML = emoticon
}

( function(window,factory){if(typeof define=='function'&&define.amd){define(['flickity/js/index','fizzy-ui-utils/utils',],factory)}else if(typeof module=='object'&&module.exports){module.exports=factory(require('flickity'),require('fizzy-ui-utils'))}else{factory(window.Flickity,window.fizzyUIUtils)}}( this, function factory( Flickity, utils ) {var Slide = Flickity.Slide;var slideUpdateTarget=Slide.prototype.updateTarget;Slide.prototype.updateTarget=function(){slideUpdateTarget.apply(this,arguments);if(!this.parent.options.fade){return}var slideTargetX=this.target-this.x;var firstCellX=this.cells[0].x;this.cells.forEach(function(cell){var targetX=cell.x-firstCellX-slideTargetX;cell.renderPosition(targetX)})};Slide.prototype.setOpacity=function(alpha){this.cells.forEach(function(cell){cell.element.style.opacity=alpha})};var proto=Flickity.prototype;Flickity.createMethods.push('_createFade');proto._createFade = function() {this.fadeIndex = this.selectedIndex;this.prevSelectedIndex = this.selectedIndex;this.on( 'select', this.onSelectFade );this.on( 'dragEnd', this.onDragEndFade );this.on( 'settle', this.onSettleFade );this.on( 'activate', this.onActivateFade );this.on( 'deactivate', this.onDeactivateFade );};var updateSlides=proto.updateSlides;proto.updateSlides=function(){updateSlides.apply(this,arguments);if(!this.options.fade){return}this.slides.forEach(function(slide,i){var alpha=i==this.selectedIndex?1:0;slide.setOpacity(alpha)},this)};proto.onSelectFade=function(){this.fadeIndex=Math.min(this.prevSelectedIndex,this.slides.length-1);this.prevSelectedIndex=this.selectedIndex};proto.onSettleFade=function(){delete this.didDragEnd;if(!this.options.fade){return}this.selectedSlide.setOpacity(1);var fadedSlide=this.slides[this.fadeIndex];if(fadedSlide&&this.fadeIndex!=this.selectedIndex){this.slides[this.fadeIndex].setOpacity(0)}};proto.onDragEndFade=function(){this.didDragEnd=true};proto.onActivateFade=function(){if(this.options.fade){this.element.classList.add('is-fade')}};proto.onDeactivateFade=function(){if(!this.options.fade){return}this.element.classList.remove('is-fade');this.slides.forEach(function(slide){slide.setOpacity('')})};var positionSlider=proto.positionSlider;proto.positionSlider=function(){if(!this.options.fade){positionSlider.apply(this,arguments);return}this.fadeSlides();this.dispatchScrollEvent()};var positionSliderAtSelected=proto.positionSliderAtSelected;proto.positionSliderAtSelected=function(){if(this.options.fade){this.setTranslateX(0)}positionSliderAtSelected.apply(this,arguments)};proto.fadeSlides=function(){if(this.slides.length<2){return};var indexes=this.getFadeIndexes();var fadeSlideA=this.slides[indexes.a];var fadeSlideB=this.slides[indexes.b];var distance=this.wrapDifference(fadeSlideA.target,fadeSlideB.target);var progress=this.wrapDifference(fadeSlideA.target,-this.x);progress=progress/distance;fadeSlideA.setOpacity(1-progress);fadeSlideB.setOpacity(progress);var fadeHideIndex=indexes.a;if(this.isDragging){fadeHideIndex=progress>0.5?indexes.a:indexes.b}var isNewHideIndex=this.fadeHideIndex!=undefined&&this.fadeHideIndex!=fadeHideIndex&&this.fadeHideIndex!=indexes.a&&this.fadeHideIndex!=indexes.b;if(isNewHideIndex){this.slides[this.fadeHideIndex].setOpacity(0)}this.fadeHideIndex=fadeHideIndex};proto.getFadeIndexes=function(){if(!this.isDragging&&!this.didDragEnd){return{a:this.fadeIndex,b:this.selectedIndex,}}if(this.options.wrapAround){return this.getFadeDragWrapIndexes()}else{return this.getFadeDragLimitIndexes()}};proto.getFadeDragWrapIndexes=function(){var distances=this.slides.map(function(slide,i){return this.getSlideDistance(-this.x,i)},this);var absDistances=distances.map(function(distance){return Math.abs(distance)});var minDistance=Math.min.apply(Math,absDistances);var closestIndex=absDistances.indexOf(minDistance);var distance=distances[closestIndex];var len=this.slides.length;var delta=distance>=0?1:-1;return{a:closestIndex,b:utils.modulo(closestIndex+delta,len),}};proto.getFadeDragLimitIndexes=function(){var dragIndex=0;for(var i=0;i<this.slides.length-1;i++){var slide=this.slides[i];if(-this.x<slide.target){break}dragIndex=i}return{a:dragIndex,b:dragIndex+1,}};proto.wrapDifference=function(a,b){var diff=b-a;if(!this.options.wrapAround){return diff}var diffPlus=diff+this.slideableWidth;var diffMinus=diff-this.slideableWidth;if(Math.abs(diffPlus)<Math.abs(diff)){diff=diffPlus}if(Math.abs(diffMinus)<Math.abs(diff)){diff=diffMinus}return diff};var _getWrapShiftCells=proto._getWrapShiftCells;proto._getWrapShiftCells=function(){if(!this.options.fade){_getWrapShiftCells.apply(this,arguments)}};var shiftWrapCells=proto.shiftWrapCells;proto.shiftWrapCells=function(){if(!this.options.fade){shiftWrapCells.apply(this,arguments)}};return Flickity;}));