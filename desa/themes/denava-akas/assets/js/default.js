
$.noConflict();
jQuery( document ).ready(function( $ ) {
});
$(function(){
'use strict';
$('html').removeClass('no-js').addClass('js');
function setActiveStyleSheet(title) {
var i, a;
for(i=0; (a = document.getElementsByTagName('link')[i]); i++) {
if(a.getAttribute('rel').indexOf('style') != -1
&& a.getAttribute('title')
&& a.getAttribute('rel').indexOf('alt') != -1 ){
a.disabled = true;
if(a.getAttribute('title') == title) a.disabled = false;
}
}
}
if( typeof Storage != 'undefined' ){
var color = window.localStorage.getItem('color');
if(color != null ){
setActiveStyleSheet( color );
$('.colors > a[data-val='+color+']').addClass('active');
}
}
$('.colors > a').on('click', function(e){
e.preventDefault();
var color = $(this).data('val');
setActiveStyleSheet(color);
$('.colors > a.active').removeClass('active');
$(this).addClass('active');
if( typeof Storage != 'undefined' ){
window.localStorage.setItem('color', color);
}
});
});	

