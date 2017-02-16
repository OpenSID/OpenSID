$(function datePicker(){
  $('.datepicker').datepicker({
  dateFormat:'dd/mm/yy',
  changeMonth: true,
  changeYear: true
  });
});


$(function formTips(){
  $('input.help,textarea.help').formtips({
        tippedClass: 'tipped'
  });
});

$(function checkAll(){
  $('.checkall').click(function () {
		$(this).parents('table').find(':checkbox').attr('checked', this.checked);
	});
});

$(function Tipsy(){
  $('.tipsy.north').tipsy({gravity: 'n'});
  $('.tipsy.east').tipsy({gravity: 'e'});
  $('.tipsy.south').tipsy({gravity: 's'});
  $('.tipsy.west').tipsy({gravity: 'w'});
});

$(function menuSub(){
  $('.sub').parent().hover(function(){
    $(this).children('.sub').show();
  },function(){
    $(this).children('.sub').hide();
  });
});
function notification(type,message){
    $('#maincontent').prepend(''
        +'<div id="notification" class="'+type+'">'
        +'<span class="icon">&nbsp;</span>'
        +'<label>'+type+'</label> : '+message+''
        +'<span class="ui-icon ui-icon-closethick" onclick="'
        +'$(this).parent().remove();'
        +'">&nbsp;</span>'
        +'</div>'
        +''
    );
	$('#notification').delay(3000).fadeOut();;
}

$(function buttonUI(){
    $('.uiradio,.uicheckbox').buttonset();
});
