$(function dialogClick(){
  $('a .content,button .content').hide();
  $('a[target=confirm],button[target=confirm]').click(function(){
    var id = $(this).attr('target');
    var title = $(this).attr('title') || $(this).attr('header');
    var message = $(this).attr('message');
    var url = $(this).attr('href');
    var width = $(this).attr('modalWidth');
    var height = $(this).attr('modalHeight');
    dialog(id,title,message,url,width,height);
    return false;
  });
  $('a[target=confirm2],button[target=confirm2]').click(function(){
    var id = $(this).attr('target');
    var title = $(this).attr('title') || $(this).attr('header');
    var message = $(this).attr('message');
    var url = $(this).attr('href');
    var width = $(this).attr('modalWidth');
    var height = $(this).attr('modalHeight');
    dialog2(id,title,message,url,width,height);
    return false;
  });
  $('a[target=modal],button[target=modal]').click(function(){
    var id = $(this).attr('target');
    var title = $(this).attr('title');
    var message = $(this).children('.content').html();
    var width = $(this).attr('modalWidth');
    var height = $(this).attr('modalHeight');
    modal(id,title,message,width,height);
    return false;
  });
  $('a[target=ajax-modal],button[target=ajax-modal]').click(function(){
    var id = $(this).attr('rel');
    var title = $(this).attr('header');
    var url = $(this).attr('href');
    var width = $(this).attr('modalWidth');
    var height = $(this).attr('modalHeight');
    ajaxModal(id,title,url,width,height);
    return false;
  });
  $('a[target=ajax-modalx],button[target=ajax-modalx]').click(function(){
    var id = $(this).attr('rel');
    var title = $(this).attr('header');
    var url = $(this).attr('href');
    var width = $(this).attr('modalWidth');
    var height = 400;
    ajaxModalx(id,title,url,width,height);
    return false;
  });
  $('a[target=ajax-modalc],button[target=ajax-modalc]').click(function(){
    var id = $(this).attr('rel');
    var title = $(this).attr('header');
    var url = $(this).attr('href');
    var width = $(this).attr('modalWidth');
    var height = 600;
    var width = 420;
    ajaxModal(id,title,url,width,height);
    return false;
  });
  $('a[target=ajax-modal-map],button[target=ajax-modalx]').click(function(){
    var id = $(this).attr('rel');
    var title = $(this).attr('header');
    var url = $(this).attr('href');
    var width = 640;
    var height = 560;
    ajaxModalx(id,title,url,width,height);
    return false;
  });
  $('a[target=ajax-modalz],button[target=ajax-modalx]').click(function(){
    var id = $(this).attr('rel');
    var title = $(this).attr('header');
    var url = $(this).attr('href');
    var width = $(this).attr('modalWidth');
    var height = 400;
    ajaxModalx(id,title,url,width,height);
    return false;
  });
});
function dialog(id,title,message,url,width,height){
  if (width==null || height==null){
    width='400';
    height='auto';
  }
  $('#'+id+'').remove();
  $('body').append('<div id="'+id+'" title="'+title+'" style="display:none;">'+message+'</div>');
		$('#'+id+'').dialog({
			resizable: false,
			draggable: true,
            width:width,
            height:height,
            autoOpen: false,
            modal: true,
            buttons: {
				"Ya": function() {
					location.href=url;
				},
				"Tidak": function() {
                    $('#'+id+'').remove();
					$( this ).dialog( "close" );
			}
      },
      dragStart: function(event, ui) {
        $(this).parent().addClass('drag');
      },
      dragStop: function(event, ui) {
        $(this).parent().removeClass('drag');
      }

	});

  $('#'+id+'').dialog('open');
}

function dialog2(id,title,message,url,width,height){
  if (width==null || height==null){
    width='400';
    height='auto';
  }
  $('#'+id+'').remove();
  $('body').append('<div id="'+id+'" title="'+title+'" style="display:none;">'+message+'</div>');
		$('#'+id+'').dialog({
			resizable: false,
			draggable: true,
            width:width,
            height:height,
            autoOpen: false,
            modal: true,
      dragStart: function(event, ui) {
        $(this).parent().addClass('drag');
      },
      dragStop: function(event, ui) {
        $(this).parent().removeClass('drag');
      }

	});
  $('#'+id+'').dialog('open');
  }

function modal(id,title,message,width,height){
  if (width==null || height==null){
    width='400';
    height='auto';
  }
  $('#'+id+'').remove();
  $('body').append('<div id="'+id+'" title="'+title+'" style="display:none;">'+message+'</div>');
		$('#'+id+'').dialog({
			resizable: false,
			draggable: true,
      width:width,
      height:height,
      autoOpen: false,
			modal: false,
      dragStart: function(event, ui) {
        $(this).parent().addClass('drag');
      },
      dragStop: function(event, ui) {
        $(this).parent().removeClass('drag');
      }

	});
  $('#'+id+'').dialog('open');
  }

function ajaxModalz(id,title,url,width,height){
  if (width==null || height==null){
    width='450';
    height='400';
  }
  $('#'+id+'').remove();

  $('body').append('<div id="'+id+'" title="'+title+'" style="display:none;position:relative"><div style="width:300px;height:100px;background:url(assets/images/background/fbloading.gif) no-repeat center center"></div></div>');

		$('#'+id+'').dialog({
			resizable: false,
			draggable: true,
      width:width,
      height:height,
      autoOpen: false,
			modal: false,
      dragStart: function(event, ui) {
        $(this).parent().addClass('drag');
      },
      dragStop: function(event, ui) {
        $(this).parent().removeClass('drag');
      },
      open: function(event, ui) {
        $('#'+id+'').load(url);
      }
	});
  $('#'+id+'').dialog('open');
  }

function ajaxModalx(id,title,url,width,height){
  if (width==null || height==null){
    width='450';
    height='400';
  }
  $('#'+id+'').remove();

  $('body').append('<div id="'+id+'" title="'+title+'" style="display:none;position:relative"><div style="width:300px;height:100px;background:url(assets/images/background/fbloading.gif) no-repeat center center"></div></div>');

		$('#'+id+'').dialog({
			resizable: false,
			draggable: true,
      width:width,
      height:height,
      autoOpen: false,
			modal: false,
      dragStart: function(event, ui) {
        $(this).parent().addClass('drag');
      },
      dragStop: function(event, ui) {
        $(this).parent().removeClass('drag');
      },
      open: function(event, ui) {
        $('#'+id+'').load(url);
      }
	});
  $('#'+id+'').dialog('open');
  }

function ajaxModal(id,title,url,width,height){
  if (width==null || height==null){
    width='450';
    height='200';
  }
  $('#'+id+'').remove();

  $('body').append('<div id="'+id+'" title="'+title+'" style="display:none;position:relative;overflow:scroll;"><div style="width:300px;height:100px;background:url(assets/images/background/fbloading.gif) no-repeat center center"></div></div>');

		$('#'+id+'').dialog({
      position: ['center', 150],
			resizable: true,
			draggable: true,
      width:width,
      height:height,
      autoOpen: false,
			modal: false,
      dragStart: function(event, ui) {
        $(this).parent().addClass('drag');
      },
      dragStop: function(event, ui) {
        $(this).parent().removeClass('drag');
      },
      open: function(event, ui) {
        $('#'+id+'').load(url);
      }
	});
  $('#'+id+'').dialog('open');
  }
