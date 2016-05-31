jQuery.fn.tabEnter = function() {
this.keypress(function(e){
// get key pressed (charCode from Mozilla/Firefox and Opera / keyCode in IE)
var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;

if(key == 13) {
// get tabindex from which element keypressed
var ntabindex = parseInt($(this).attr("tabindex")) + 1;
$("[tabindex=" + ntabindex + "]").focus();
return false;
}
});
}

$(function(){
  $("[tabindex]").tabEnter();
});