$(document).ready(function(){
  /*
  $('table.datalist td.action').hide();
  $('table.datalist').wrap('<div class="table-container" style="position:relative"/>');
    $('.table-container').append('<div id="action-panel"></div>');
  $('table.datalist tr.list').click(function(){
    $('#action-panel .buttonpane,#action-panel .bg').remove();
    var listPos = $(this).position();
    var actionPos = $(this).height()/2 - 20;
    $('#action-panel').show().css({
      'left':listPos.left,
      'width':$(this).width(),
      'height':$(this).height()
    }).animate({'top':listPos.top},function(){
      $('#action-panel .buttonpane').slideDown();
    });
    var action = $(this).find('td.action').html();
  $('#action-panel').append('<div class="bg"></div><div class="buttonpane">'+action+'</div>');

  });
  */

  
  $('table.datalist tr.list').hover(function(){
    $(this).addClass('hover');
  },function(){
    $(this).removeClass('hover');
  });
});