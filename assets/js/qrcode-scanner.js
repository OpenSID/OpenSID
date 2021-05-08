var resultContainer = document.getElementById('qr-reader-results');
var lastResult, countResults = 0;

function docReady(fn) {
	// see if DOM is already available
	if (document.readyState === "complete"
	|| document.readyState === "interactive") {
		// call on next available tick
		setTimeout(fn, 1);
	} else {
		document.addEventListener("DOMContentLoaded", fn);
	}
}

docReady(function () {
	var resultContainer = document.getElementById('qr-reader-results');
	var lastResult, countResults = 0;

	function onScanSuccess(qrCodeMessage) {
		if (qrCodeMessage !== lastResult) {
			++countResults;
			lastResult = qrCodeMessage;
			resultContainer.innerHTML += `<div><strong>${qrCodeMessage}<strong></div>`;
			var url_exp = /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)?/gi;
			var regex_url = new RegExp(url_exp);
			url_matches = qrCodeMessage.match(regex_url);
			if (url_matches) {
				resultContainer.innerHTML += `<p><a href="${url_matches[0]}" class="btn btn-social btn-flat btn-info btn-sm" target="_blank"><i class="fa fa-globe"></i>&nbsp;Kunjungi Website</a></p>`;
			}
		}

		html5QrcodeScanner.clear();
	}

	var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });
	html5QrcodeScanner.render(onScanSuccess);
});