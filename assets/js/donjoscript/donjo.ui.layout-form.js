$(document).ready(function(){
var myLayout;
  myLayout = $('body').layout({ 
      //applyDefaultStyles: true,
			closable:				true	// pane can open & close
		,	resizable:				false	// when open, pane can be resized 
		,	slidable:				true	// when closed, pane can 'slide' open over other panes - closes on mouse-out
		,	north__spacing_closed:	true		// big resizer-bar when open (zero height)
		,	north__spacing_open:	0		// big resizer-bar when open (zero height)
		,	south__spacing_open:	0		// no resizer-bar when open (zero height)
		,	south__spacing_closed:	true		// big resizer-bar when open (zero height)
		,	east__spacing_open:	    false	// big resizer-bar when open (zero height)  
		,	east__spacing_closed:	true		// big resizer-bar when open (zero height)
    , east__initClosed: true
    , center__onresize: "innerLayout.resizeAll"
  });
  
 // myLayout.addToggleBtn('#east-toggler', 'west');
  $_wrapperHeight = parseInt($('#wrapper').height());
  //$_wrapper2Height = parseInt($('#wrapper').height());
  
  innerLayout = $('#contentpane').css({'height':$_wrapperHeight-65}).layout({ 
            closable:				false	
		,	resizable:				false	
		,	slidable:				false	
		,	north__spacing_closed:	0		
		,	north__spacing_open:	0		
		,	south__spacing_open:	0		
		,	south__spacing_closed:	0		
  });
  
  
  //innerLayout = $('#map').css({'height':$_wrapperHeight-35}).layout({});
  
		//$('#map').css({'height':$_wrapperHeight-35}).layout({});
  leftSidebar = $('#left-sidebar').layout({});
});