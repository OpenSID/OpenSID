/*==============================================================================================================
Project:	Socase - One page HTML5 Template 
Version:	1.0
Theme Available on - Theme Forest 
Author:	abmathasuriya
Design By - VibrantTheme.com
===============================================================================================================*/
(function($) { 
	"use strict";
	
	/* --------------------------------------------
     Platform detect
     --------------------------------------------- */
    var mobileTest;
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
        mobileTest = true;
        $("html").addClass("mobile");
    }
    else {
        mobileTest = false;
        $("html").addClass("no-mobile");
    }
    
    var mozillaTest;
    if (/mozilla/.test(navigator.userAgent)) {
        mozillaTest = true;
    }
    else {
        mozillaTest = false;
    }
    var safariTest;
    if (/safari/.test(navigator.userAgent)) {
        safariTest = true;
    }
    else {
        safariTest = false;
    }
     // Detect touch devices    
    if (!("ontouchstart" in document.documentElement)) {
        document.documentElement.className += " no-touch";
    }

    // Set cursor focus to search form{
    $('.btn-search').on('click',function(){
    	$('#ModalSearch').modal('show');
    	$('#ModalSearch').on('shown.bs.modal', function () {
		    $('.input-search').focus();
		})  
    });

	
	/*==================DOCUMENT READY METHID==================*/
	$(document).ready(function(){
        
        $(window).trigger("resize");            
        
		//Initialize Menu
		init_menu();  
		
		//Initialize Parallax
		init_parallax();	
		
		//Initialize Portfolio Filter	
		init_ProjectFilter();		
		
		//Initialize Masonry layoutMode
		init_masonry();		
		
		//Initialize Lightbox
        init_lightbox();
		
		//Initialize scroll top
        init_scrolltop(); 		
		
		//Initialize WOW animation
        init_wow();		
		
		
		//Initialize Subscribe Form
		//init_subscribe();
		
		// Responsive video
        $(".video, .blog-media").fitVids();
        $(".work-full-media").fitVids(); 
		
		//Right sidebar Sub Menu Trigger
        $('.sn-main-menu li a.sub-menu-trigger').on('mouseenter', function(){
            $(this).next('.sub-menu').stop().slideDown(800);
        });
        $('.sn-main-menu li').on('mouseleave', function(){
            $('.sub-menu').stop().slideUp(800);
        });
		

		
		//close popup
		$(document).on('click', '.popup-modal-dismiss', function (e) {
			e.preventDefault();
			$.magnificPopup.close();
		});
    });
	/*==================END DOCUMENT READY METHID==================*/
	
    /*==================WINDOW RESIZE METHID==================*/
    $(window).resize(function(){
        
		//Initialize Menu on resize
        init_menu_resize();
		
		//Initialize Fullscreen on resize
        js_hight_fullscreen();
        
    });
	/*==================END WINDOW RESIZE METHID==================*/
	
	//Set Sections background image
	var pageSection = $(".page-section, .idea-item.bg-img,.article_author .portrait,.article-thumbnail, .macbook-fade .slick-item");
	pageSection.each(function(indx){			
		if ($(this).attr("data-background")){
			$(this).css("background-image", "url(" + $(this).data("background") + ")");
		}
	});
	
	//Initialize Menu variable
	var mobile_nav = $(".mobile-nav");
	var desktop_nav = $(".large-nav");
	
	// Initialize Projects filtering variable and portfolio grid
	var selector = 0;
	var project_grid = $("#project_grid, #isotope");
	
	// Function equal height
    !function(a){
        a.fn.equalHeights = function(){
            var b = 0, c = a(this);
            return c.each(function(){
                var c = a(this).innerHeight();
                c > b && (b = c)
            }), c.css("height", b)
        }, a("[data-equal]").each(function(){
            var b = a(this), c = b.data("equal");
            b.find(c).equalHeights()
        })
    }(jQuery);
	
	/* ==============================================
	Navigation resize
	=============================================== */	
    function init_menu_resize(){

		// Mobile menu max height
        $(".small-device-menu .large-nav > ul").css("max-height", $(window).height() - $(".main-nav").height() - 20 + "px");
        
        // Mobile menu style toggle
        if ($(window).width() <= 1024) {
            $(".main-nav").addClass("small-device-menu");			
			$(".main-nav-mobile").removeClass("dt-mobile-nav");
			$(".cd-right-content").removeClass("sidebar-section");
			$(".dt-sidebar").hide();			
        }else if ($(window).width() > 1024) {
			$(".main-nav").removeClass("small-device-menu");						
			$(".main-nav-mobile").addClass("dt-mobile-nav"); 			
			$(".cd-right-content").addClass("sidebar-section");
			$(".dt-sidebar").show();				
			desktop_nav.show();
		}
    }
		
	/* ==============================================
	Nav line height
	=============================================== */		
    function menu_line_height(height_initial, height_after){
        height_initial.height(height_after.height());
        height_initial.css({
            "line-height": height_after.height() + "px"
        });
    }
	    
	/* ==============================================
	Nav initialization
	=============================================== */		
	function init_menu(){
    
		// Navbar sticky
        $(".sticky").sticky({
            topSpacing: 0
        });
		
		//menu height
		menu_line_height($(".nav-wrapper > ul > li > a"), $(".main-nav"));
        menu_line_height(mobile_nav, $(".main-nav"));
        mobile_nav.css({
            "width": $(".main-nav").height() + "px"
        });

        // Transpaner menu        
        if ($(".main-nav").hasClass("transparent")){
           $(".main-nav").addClass("nav-transparent"); 
        }
		
		// On scroll nav height change
        $(window).on("scroll",function(){        
			if ($(window).scrollTop() > 10) {
				$(".nav-transparent").removeClass("transparent");
				$(".main-nav, .header-logo-wrap .logo, .mobile-nav, .header-nav-section .section-nav").addClass("small-height");
				$(".nav-wrapper ul li .cart-label").css('top','7px');
			}
			else {
				$(".nav-transparent").addClass("transparent");
				$(".main-nav, .header-logo-wrap .logo, .mobile-nav, .header-nav-section .section-nav").removeClass("small-height");
				$(".nav-wrapper ul li .cart-label").css('top','17px');
			}                        
        });

        // Mobile menu toggle        
		mobile_nav.on("click",function(){
            if (desktop_nav.hasClass("menu-opened")) {
                desktop_nav.slideUp("slow", "easeOutExpo").removeClass("menu-opened");
                $(this).removeClass("active");
            } else {
                desktop_nav.slideDown("slow", "easeOutQuart").addClass("menu-opened");
                $(this).addClass("active");
            }            
        });
		
		//set active menu link
		desktop_nav.find("a:not(.menu-down)").on("click",function(){
            if (mobile_nav.hasClass("active")) {
                desktop_nav.slideUp("slow", "easeOutExpo").removeClass("menu-opened");
                mobile_nav.removeClass("active");
            }            
        });
        
        /* ==============================================
		Sub menu
		=============================================== */		
       
        var menuSub = $(".menu-down");
        var menulist;
        
        $(".small-device-menu .menu-down").find(".fa:first").removeClass("fa-angle-right").addClass("fa-angle-down");		
				
		menuSub.on("click",function(){
            if ($(".main-nav").hasClass("small-device-menu")) {
                menulist = $(this).parent("li:first");
                if (menulist.hasClass("menu-opened")) {
                    menulist.find(".nav-sub:first").slideUp(function(){
                        menulist.removeClass("menu-opened");
                        menulist.find(".menu-down").find(".fa:first").removeClass("fa-angle-up").addClass("fa-angle-down");
                    });
                }
                else {
                    $(this).find(".fa:first").removeClass("fa-angle-down").addClass("fa-angle-up");
                    menulist.addClass("menu-opened");
                    menulist.find(".nav-sub:first").slideDown();
                }
                
                return false;
            }
            else {				
               
            }
            
        });
        
        menulist = menuSub.parent("li");
        menulist.hover(function(){        
            if (!($(".main-nav").hasClass("small-device-menu"))) {            
                /*$(this).find(".nav-sub:first").stop(true, true).slideToggle(0,"linear");*/
				$(this).find(".nav-sub:first").stop(true, true).fadeIn(600);
            }            
        }, function(){        
            if (!($(".main-nav").hasClass("small-device-menu"))) {            
                /*$(this).find(".nav-sub:first").stop(true, true).slideToggle(0,"linear");*/
				$(this).find(".nav-sub:first").stop(true, true).delay(100).fadeOut("fast");
			}            
        });       
			
    }

	/* ==============================================
	Scroll to homepage 
	=============================================== */
	function init_scrolltop(){		
		var offset = 450;
		var duration = 500;		
		jQuery('.top-button').on("click",function(event){
			event.preventDefault();
			jQuery('html, body').animate({scrollTop: 0}, duration);
			return false;
		})
	}	
	
	/* ==============================================
	Number Counter
	=============================================== */
	function init_counter(){
		$(".fact-wrapper").filter(":in-viewport:first").each(function(){
			$('.counter').counterUp({
				delay: 10,
				time: 2000
			});	
		});		
	}
	
	/* ==============================================
	Parallax 
	=============================================== */
	function init_parallax(){
        if (($(window).width() >= 1024) && (mobileTest == false)) {
            $(".parallax-1").parallax("50%", 0.1);
            $(".parallax-2").parallax("50%", 0.2);			
            $(".parallax-3").parallax("50%", 0.3);
            $(".parallax-4").parallax("50%", 0.4);
            $(".parallax-5").parallax("50%", 0.5);
            $(".parallax-6").parallax("50%", 0.6);
            $(".parallax-7").parallax("50%", 0.7);
            $(".parallax-8").parallax("50%", 0.9);
            $(".parallax-9").parallax("50%", 0.9);
            $(".parallax-10").parallax("50%", 0.5);
            $(".parallax-11").parallax("50%", 0.05);
        }        
    }
	
	/* ==============================================
	Lightbox
	=============================================== */
	function init_lightbox(){
		// Works Item Lightbox				
        $(".work-lightbox").magnificPopup({
            gallery: {
                enabled: true
            },
            mainClass: "mfp-fade",
			image: {
				verticalFit: true,
				titleSrc: function(item) {
					return item.el.attr('title');
				}
			 }
        });
		
		// gallery Item Lightbox				
        $(".gallery-lightbox").magnificPopup({
            gallery: {
                enabled: true
            },
            mainClass: "mfp-fade"
        });
		
		//lightbox
		$(".lightbox").magnificPopup();
		
		// lightbox with zoom gallery effect
		$('.zoom-gallery').magnificPopup({
		  type: 'image',
		  mainClass: 'mfp-with-zoom mfp-img-mobile',
		  gallery: {
                enabled: true
          },
		  image: {
			verticalFit: true,
			titleSrc: function(item) {
				return item.el.attr('title') + ' &middot; <a class="image-source-link" href="'+item.el.attr('data-source')+'" target="_blank">image source</a>';
			}
		 },
		  zoom: {
			enabled: true,
			duration: 300,
			easing: 'ease-in-out',
			// The "opener" function should return the element from which popup will be zoomed in
			// and to which popup will be scaled down
			// By defailt it looks for an image tag:
			opener: function(openerElement) {
			  // openerElement is the element on which popup was initialized, in this case its <a> tag
			  // you don't need to add "opener" option if this code matches your needs, it's defailt one.
			  return openerElement.is('img') ? openerElement : openerElement.find('img');
			}
		  }

		});
		
		//Lightbox with popup gallery
		$('.popup-gallery').magnificPopup({
			delegate: 'a',
			type: 'image',
			tLoading: 'Loading image #%curr%...',
			mainClass: 'mfp-img-mobile',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1] // Will preload 0 - before current, and 1 after the current image
			},
			image: {
				tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
				titleSrc: function(item) {
					return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
				}
			}
		});
		
		//Lightbox with popup with vertical-fit
		$('.image-popup-vertical-fit').magnificPopup({
			type: 'image',
			closeOnContentClick: true,
			mainClass: 'mfp-img-mobile',
			image: {
				verticalFit: true
			}			
		});

		//Lightbox popup with fit-width
		$('.image-popup-fit-width').magnificPopup({
			type: 'image',
			closeOnContentClick: true,
			image: {
				verticalFit: false
			}
		});

		$('.image-popup-no-margins').magnificPopup({
			type: 'image',
			closeOnContentClick: true,
			closeBtnInside: false,
			fixedContentPos: true,
			mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
			image: {
				verticalFit: true
			},
			zoom: {
				enabled: true,
				duration: 300 // don't foget to change the duration also in CSS
			}
		});
		
		//lightbox for popup map, Video
		$('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
          disableOn: 700,
          type: 'iframe',
          mainClass: 'mfp-fade',
          removalDelay: 160,
          preloader: false,

          fixedContentPos: false
        });
		
		$('.popup-with-zoom-anim').magnificPopup({
			type: 'inline',
			fixedContentPos: false,
			fixedBgPos: true,
			overflowY: 'auto',
			closeBtnInside: true,
			preloader: false,			
			midClick: true,
			removalDelay: 300,
			mainClass: 'my-mfp-zoom-in'
		});

		$('.popup-with-move-anim').magnificPopup({
			type: 'inline',
			fixedContentPos: false,
			fixedBgPos: true,
			overflowY: 'auto',
			closeBtnInside: true,
			preloader: false,			
			midClick: true,
			removalDelay: 300,
			mainClass: 'my-mfp-slide-bottom'
		});
		
		$('.popup-with-form').magnificPopup({
			type: 'inline',
			preloader: false,
			focus: '#Email',
			// When elemened is focused, some mobile browsers in some cases zoom in
			// It looks not nice, so we disable it:
			callbacks: {
				beforeOpen: function() {
					if($(window).width() < 700) {
						this.st.focus = false;
					} else {
						this.st.focus = '#Email';
					}
				}
			}
		});
		
		$('.popup-modal').magnificPopup({
			type: 'inline',
			preloader: false,
			focus: '#username',
			modal: true
		});
		
	}

	/* ==============================================
	Smooth scroll
	=============================================== */
	function init_smoothScroll(){		
		smoothScroll.init({
			speed: 400,
			easing: 'easeInOutCubic',
			offset: 40,
			updateURL: false,
			callbackBefore: function ( toggle, anchor ) {},
			callbackAfter: function ( toggle, anchor ) {}
		});
	}
	
	/* ==============================================
	Set fullscreen height
	=============================================== */
	function js_hight_fullscreen(){				
		$(".home").height($(window).height());        
		$(".fullscreen").height($(window).height());	
		$(".height-parent").each(function(){
			$(this).height($(this).parent().first().height());
		});		
	}


  
	/* ==============================================
	Masonry Layouts
	=============================================== */
	function init_masonry(){
		$(".masonry").imagesLoaded(function(){
			$(".masonry").masonry();
		});
	}
	
	/* ==============================================
	Individual section scroll
	=============================================== */
	function init_scroll_navigate(){        
        $(".local-scroll").localScroll({
            target: "body",
            duration: 1500,
            offset: 0,
            easing: "easeInOutExpo"
        });
        var sections = $(".page-section");
        var menu_links = $(".scroll-nav li a");
        var nav_arrow = $(".section-navigation");
		$(window).on("scroll",function(){           
            sections.filter(":in-viewport:first").each(function(){
                var active_section = $(this);
                var active_link = $('.scroll-nav li a[href="#' + active_section.attr("id") + '"]');
				var last_active_id = "#"+active_section.closest(".page-section").nextAll("section[id]").filter(':last').attr("id");
				var active_id = "#"+active_section.closest(".page-section").nextAll("section[id]").filter(':first').attr("id");
				if(last_active_id==active_id){					
					nav_arrow.find('span').removeClass("lnr-arrow-down").addClass("lnr-arrow-up");
					nav_arrow.find('a').attr("href", "#top");
				}else{
					nav_arrow.find('a').attr("href", active_id);
					if (nav_arrow.find('span').hasClass("lnr-arrow-up"))
						nav_arrow.find('span').removeClass("lnr-arrow-up").addClass("lnr-arrow-down");
				}
                menu_links.removeClass("active");
                active_link.addClass("active");
            });
            
        });
        
    }

  })(jQuery); 
