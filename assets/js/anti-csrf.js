// automatically send CSRF token for all AJAX and POST requests
document.addEventListener('DOMContentLoaded', () => {
	const csrfParam = document.querySelector('meta[name=csrf-param]').content
	const csrfToken = document.cookie.match(new RegExp(csrfParam +'=(\\w+)'))[1]

	function addCsrfField(form) {
		if (form.method.toUpperCase() !== 'GET') {
			const input = document.createElement('input')
			input.type = 'hidden'
			input.name = csrfParam
			form[csrfParam] || form.append(input)
			form[csrfParam].value = csrfToken
		}
	}

	document.querySelectorAll('form').forEach((form) => {
		addCsrfField(form)
		form.addEventListener('submit', (e) => {
			addCsrfField(e.target)
  		})
	})

	$.ajaxPrefilter((opts, origOpts, xhr) => {
		if (!opts.crossDomain && opts.type !== 'GET') {
			opts.data = `${opts.data||''}&${csrfParam}=${csrfToken}`
		}
	})
})
