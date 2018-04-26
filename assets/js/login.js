$(document).ready(function(){
  var $_container= $('#container');
  $_container.css({
    'position':'absolute',
    'margin':'0',
    'top':($(window).height() - $_container.height())/2  + 'px',
    'left':($(window).width() - $_container.width())/2  + 'px'
  });
  $_container.css("top", ($(window).height() - $_container.height())/2  + 'px');
  $_container.css("left", ($(window).width() - $_container.width())/2  + 'px');
});

function loginForm(){
  $('#login-button').html("&nbsp;&nbsp;<img src='../assets/images/background/loading.gif' alt='|||'/>&nbsp;&nbsp;");
  $('#username-label').html('Checking..');
  $('#username-check .loadingbar').fadeIn().animate({'width':'267px'},800,function(){
    $(this).children('span').fadeIn();
  });
  $('#password-label').html('Checking..');
  $( "#container" ).delay(700).fadeOut(1200);
  $('#password-check .loadingbar').delay(500).fadeIn().animate({'width':'267px'},700,function(){
    $(this).children('span').fadeIn(function(){
      $('#loginform').delay(1000).submit();
    });
  });
}