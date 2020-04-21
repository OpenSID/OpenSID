// automatically send CSRF token for all AJAX and POST requests

function addCsrfField(form) {
	if (form.method.toUpperCase() !== 'GET') {
		const input = document.createElement('input')
		input.type = 'hidden'
		input.name = csrfParam
		form[csrfParam] || form.append(input)
		form[csrfParam].value = getCsrfToken()
	}
}

function csrf_semua_form()
{
	document.querySelectorAll('form').forEach((form) => {
		addCsrfField(form)
		form.addEventListener('submit', (e) => addCsrfField(e.target))
	})	
}

$('document').ready(function() {
	csrf_semua_form();

	$.ajaxPrefilter((opts, origOpts, xhr) => {
		if (!opts.crossDomain && opts.type !== 'GET' && !(opts.data instanceof FormData)) {
			opts.data = `${opts.data||''}&${csrfParam}=${getCsrfToken()}`
		}
	})
})
