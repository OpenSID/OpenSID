<script>
    function ajax_save_dtks(url, data_form, callback_success = null, callback_fail = null, custom_config = {}) {
        let final_config = {
            type: 'POST',
            url: url,
            data: data_form,
            ...custom_config
        };

        $.ajax(final_config)
            .done(function(response) {

                let message = Array.isArray(response.message) ?
                    response.message.join('<br>') :
                    response.message;

                if (typeof callback_success === "function") {
                    callback_success(response);
                }

                showMessageDtks('success', message);
            })
            .fail(function(xhr) {

                console.error(xhr);

                let errorMessage = '';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = Array.isArray(xhr.responseJSON.message) ?
                        xhr.responseJSON.message.join('<br>') :
                        xhr.responseJSON.message;
                } else {
                    errorMessage = 'Unknown error occurred.';
                }

                if (typeof callback_fail === "function") {
                    callback_fail(xhr);
                }

                showMessageDtks('error', xhr.statusText + ": " + errorMessage);
            });
    }

    function showMessageDtks(icon, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: icon,
                html: message,
                timer: 1000,
            });
        } else {
            alert(message);
        }
    }
</script>
