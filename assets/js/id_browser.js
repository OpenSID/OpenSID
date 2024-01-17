$(document).ready(function() {
    // Initialize the agent at application startup.
    const fpPromise = import('/assets/js/fingerprint/fingerprintjs_v3.js').then(FingerprintJS =>
        FingerprintJS.load());

    // Get the visitor identifier when you need it.
    fpPromise
        .then(fp => fp.get())
        .then(result => {
            if (navigator.cookieEnabled) {                
                
                if (readCookie("pengunjung") == null) {
                    $('#konfirmasi-cookie').data('fingerprint', result.visitorId)
                    $('#konfirmasi-cookie').modal('show');
                } else {
                    // Tampilkan ke browser
                    document.getElementById("pengunjung").innerHTML = readCookie("pengunjung");
                }
                                
              } else {                
                $('#aktifkan-cookie').modal('show');         
              }                        
        });    
});

function rejectCookie() {
$(`<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Peringatan</h4>
            </div>
            <div class="modal-body bg-danger">
                Id pengujung tidak dapat digunakan karena tidak ada akses cookies dari browser
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
            </div>
        </div>
    </div>
</div>`).modal();
}

function buatPengunjungCookie(name) {
    // This is the visitor identifier:
    const browserId = $('#konfirmasi-cookie').data('fingerprint');
    // Tampilkan ke browser
    document.getElementById("pengunjung").innerHTML = browserId;
    createCookie("pengunjung", browserId, 360);
    $('#konfirmasi-cookie').modal('hide');
}

// Function to create the cookie 
function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }

    document.cookie = escape(name) + "=" +
        escape(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

$('.copy').on('click', function() {
    var text = $("#pengunjung").get(0)
    var selection = window.getSelection();
    var range = document.createRange();
    range.selectNodeContents(text);
    selection.removeAllRanges();
    selection.addRange(range);
    document.execCommand('copy');
});