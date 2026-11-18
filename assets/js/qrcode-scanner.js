var resultContainer = document.getElementById("qr-reader-results");
var lastResult,
  countResults = 0;

// Global function to clear all scan results
function clearAllScanResults() {
  if (resultContainer) {
    resultContainer.innerHTML = '';
  }
  lastResult = '';
  countResults = 0;
}

function docReady(fn) {
  // see if DOM is already available
  if (
    document.readyState === "complete" ||
    document.readyState === "interactive"
  ) {
    // call on next available tick
    setTimeout(fn, 1);
  } else {
    document.addEventListener("DOMContentLoaded", fn);
  }
}

docReady(function () {
  // Clear results when scanner starts
  if (resultContainer) {
    resultContainer.innerHTML = '';
  }

  var resultContainer = document.getElementById("qr-reader-results");
  var lastResult,
    countResults = 0;

  function onScanSuccess(qrCodeMessage) {
    if (qrCodeMessage !== lastResult) {
      ++countResults;
      lastResult = qrCodeMessage;

      // Clear previous results to prevent stacking
      resultContainer.innerHTML = '';

      // Create div safely to prevent XSS
      var resultDiv = document.createElement("div");
      var strongTag = document.createElement("strong");
      strongTag.textContent = qrCodeMessage;
      resultDiv.appendChild(strongTag);
      resultContainer.appendChild(resultDiv);

      var url_exp =
        /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)?/gi;
      var regex_url = new RegExp(url_exp);
      url_matches = qrCodeMessage.match(regex_url);
      if (url_matches) {
        // Create link safely
        var linkDiv = document.createElement("p");
        var link = document.createElement("a");
        link.href = url_matches[0];
        link.className = "btn btn-social btn-info btn-sm";
        link.target = "_blank";
        link.innerHTML = '<i class="fa fa-globe"></i>&nbsp;Kunjungi Website';
        linkDiv.appendChild(link);
        resultContainer.appendChild(linkDiv);
      }
    }

    html5QrcodeScanner.clear();
  }

  var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", {
    fps: 10,
    qrbox: 250,
  });
  html5QrcodeScanner.render(onScanSuccess);
});
