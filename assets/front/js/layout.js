$(document).ready(function(){
	$_wrapperHeight = parseInt($('body').height());
	$('#content').css({'height':$_wrapperHeight-20});

	var csrf_param = '_csrf'
	var csrf_token = document.cookie.match(new RegExp(csrf_param +'=(\\w+)'))[1]
	$('form').each(function(i, form) {
		if (form.method.toLowerCase() !== 'post') {
			return
		}
		if (!form[csrf_param]) {
			$(form).append($('<input type=hidden name='+ csrf_param +'>'))
		}
		form[csrf_param].value = csrf_token
	})
})
