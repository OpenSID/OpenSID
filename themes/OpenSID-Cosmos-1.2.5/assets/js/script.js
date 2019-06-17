$(window).ready(function(){
	setTimeout(function() {
		$('body').addClass('loaded');
	}, 1000);
	$('.marquee').marquee({
		duration: 10000,
		direction: 'left',
		duplicated: false,
		pauseOnHover:true
	});
	$("#navMenu").aceResponsiveMenu({
	 resizeWidth: '768', 
	 animationSpeed: 'fast',
	 accoridonExpAll: false
	});
	$('#slides').owlCarousel({
		autoplay: true,
		autoplayTimeout: 7000,
		autoplayHoverPause: true,
		items: 1,
		lazyLoad: true,
		loop: true,
		animateOut: 'slideOutLeft',
		animateIn: 'flipInX',
	});
	let heightOfHeader = $('#main-content').offset().top;
	$(window).scroll(function(){
		if ($(document).scrollTop() > heightOfHeader) {
			$('nav.nav').addClass('fixed');
			$('.tombol-ke-atas').removeClass('hide');
		}
		else {
			$('nav.nav').removeClass('fixed');
			$('.tombol-ke-atas').addClass('hide');
		}
	});
	$('#akses-data-wrapper').owlCarousel({
		loop: true,
		autoplay: true,
		autoplayHoverPause: true,
		responsiveClass: true,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2,
				margin: 10
			},
			1000: {
				items: 4,
				margin: 10
			}
		}
	});
	let elem = '<div class="table-responsive"></div>';
	let fotoArtikel = '<figure class="foto-artikel"></figure>';
	$('article').find('table').each(function() {
		$(elem).insertAfter($(this)).html($(this));
	});
	$('article').find('iframe').each(function() {
		$(elem).insertAfter($(this)).html($(this));
	});
	$('article').find('img').each(function(){
		$(fotoArtikel).insertAfter($(this)).html($(this).addClass('img-fluid'));
	});
	$('.search-button').on('click', function(){
		$('body').addClass('search-form-open');
		$('#modal-search-form').removeClass('d-none');
	});
	$('.close-search-form').on('click', function(){
		$('body').removeClass('search-form-open');
		$('#modal-search-form').addClass('d-none');
	})
})