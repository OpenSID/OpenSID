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

	document.addEventListener('submit', (e) => {
		if (e.target.nodeName === 'FORM') {
			addCsrfField(e.target)
		}
	})
}

$('document').ready(function() {
	csrf_semua_form();

	$.ajaxPrefilter((opts, origOpts, xhr) => {
		if (opts.crossDomain) {
			return
		}
		if (opts.type !== 'GET' && opts.type !== 'PUT') {
			if (opts.data instanceof FormData) {
				opts.data.append(csrfParam, getCsrfToken())
			} else {
				opts.data = `${opts.data||''}&${csrfParam}=${getCsrfToken()}`
			}
		}
	})	
})
