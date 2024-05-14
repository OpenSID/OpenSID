<script>
    function ajax_save_dtks(url, data_form, callback_success = false, callback_fail = false, custom_config = {}) {
        let final_config = {
            type: 'POST',
            url: url,
            data: data_form,
        };
        final_config = {
            ...final_config,
            ...custom_config
        };
        $.ajax(final_config)
            .done(function(e) {
                let message = '';
                if (e.message instanceof Array) {
                    e.message.forEach(element => {
                        message += '<br>' + element;
                    });
                } else {
                    message = e.message;
                }

                if (typeof callback_success === "function") {
                    callback_success(e);
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        html: message,
                        timer: 1000
                    })
                } else {
                    alert(message);
                }
            })
            .fail(function(xhr, status, error) {
                console.log(xhr, status, error);
                let message = '';
                if (xhr.responseJSON.message instanceof Array) {
                    xhr.responseJSON.message.forEach(element => {
                        message += '<br>' + element;
                    });
                } else {
                    message = xhr.responseJSON.message;
                }

                if (typeof callback_fail === "function") {
                    callback_fail(xhr);
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        html: error + ": " + message,
                    })
                } else {
                    alert(error + ": " + message);
                }
            })
    }
</script>
