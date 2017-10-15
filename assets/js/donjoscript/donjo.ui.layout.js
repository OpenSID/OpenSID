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

  innerLayout = $('#contentpane').css({'height':$_wrapperHeight-100}).layout({
      closable:				false
		,	resizable:			false
		,	slidable:				false
		,	north__spacing_closed:	0
		,	north__spacing_open:	0
		,	south__spacing_open:	0
		,	south__spacing_closed:	0
  });
  $('#sidecontent2').css({'height':$_wrapperHeight-200});

  $('#sidecontent3').css({'height':$_wrapperHeight-260});

  //innerLayout = $('#contentpane_map').css({'height':$_wrapperHeight}).layout({});

  //innerLayout = $('#map').css({'height':$_wrapperHeight-35}).layout({});

		//$('#map').css({'height':$_wrapperHeight-35}).layout({});
  leftSidebar = $('#left-sidebar').layout({});

  var my2Layout;
  $_wrapperWidth = parseInt($('#wrapper').width());
  $_sWest = ($_wrapperWidth/3)+100;
  my2Layout = $('#pageD').layout({
      //applyDefaultStyles: true,
			center__paneSelector:	".middin-center"
		,	west__paneSelector:		".middin-west"
		,	north__paneSelector:	".middin-north"
		,	west__size:				$_sWest
		,	closable:					true	// pane can open & close
		,	resizable:				true	// when open, pane can be resized
		,	west__spacing_open:		6  // ALL panes
		,	west__spacing_closed:	6  // ALL panes
		,	north__spacing_open:	0		// big resizer-bar when open (zero height)
		,	south__spacing_open:	0		// no resizer-bar when open (zero height)
		, center__onresize: "innerLayout.resizeAll"
		,	west__onopen: function () {
				hiRes();
			}
		,	west__onclose: function () {
				hiRes();
			}
		,	west__onresize: function () {
				hiRes();
			}
  });
	$('#pageD').css({'height':$_wrapperHeight-100});

});