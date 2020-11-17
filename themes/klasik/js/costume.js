jQuery(document).ready(function() {
	$(".backTop").hide();
	$(window).on('scroll', function() {
		if ($(this).scrollTop() > 100) {
			$(".backTop").fadeIn();
		} else {
			$(".backTop").fadeOut();
		}
	});

	$(".backTop").on('click', function(e) {
		$("html, body").animate({scrollTop: 0}, 500);
	});
});
