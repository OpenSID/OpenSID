$(function donjomenu(){
    $('.donjo-menu li ul').parent().each(function(){
       var x = $(this).find('li').length;
       $(this).children('a').append('<span class="number">'+x+'</span>').css({'padding-right':'25px'});
       $(this).children('a').click(function(){
            if ($(this).attr('class') == 'active'){
              $(this).removeClass('active').next('ul').slideUp(300);
            }else{
              $('.donjo-menu li a.active').removeClass('active').next('ul').slideUp();
              $(this).addClass('active').next('ul').slideDown(300);
            };
       });
    });
    
    var active = $('#active-menu').text();
    $('.donjo-menu li a').filter(function() {
        return $(this).text() == active;
    }).addClass('active');
    
    var active = $('#contentpane').attr('rel');
    $('.donjo-menu li[rel='+active+']').children('ul').show();
    $('.donjo-menu li[rel='+active+'] > a').addClass('active'); 
    
    $('#userbox').hover(function(){
        $(this).children('ul.menu').show();
        $(this).addClass('hover');
    },function(){        
        $(this).children('ul.menu').hide();
        $(this).removeClass('hover');
    });
    
    $('.donjo-menu li a.active').parent().next().children('a').hover(function(){
        //$(this).css({'border-top-color':'transparent'})
    });
    $('.donjo-menu li a.active').parent().prev().children('a').hover(function(){
        //$(this).css({'border-bottom-color':'transparent'})
    });
    
    $('a[rel=subbox]').toggle(function(){
       var pos = $(this).offset();
       
       $(this).addClass('active');
       $('#topbox').css({
            'top': parseInt(pos.top) + 45,
            'left':parseInt(pos.left)
       }).show();
    },function(){
        $(this).removeClass('active');
        $('#topbox').hide();
    });
});
