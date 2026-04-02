<script>
    function ajax_save_dtsen(url, data, callback_success = null, callback_fail = null, custom_config = {}) {
        let default_config = {
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            beforeSend: function() {
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        };

        let config = Object.assign(default_config, custom_config);

        $.ajax(config)
            .done(function(data) {
                Swal.fire({
                    icon: 'success',
                    html: data.message || 'Berhasil disimpan',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                if (callback_success) {
                    callback_success(data);
                }
            })
            .fail(function(xhr) {
                console.error('AJAX Error:', xhr);
                
                let errorMessage = 'Unknown error occurred';
                
                if (xhr.status === 404) {
                    errorMessage = 'URL tidak ditemukan (404). Periksa route Anda.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Internal Server Error (500). Periksa log server.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        let response = JSON.parse(xhr.responseText);
                        errorMessage = response.message || errorMessage;
                    } catch(e) {
                        errorMessage = xhr.responseText.substring(0, 200);
                    }
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errorMessage,
                    footer: `Status: ${xhr.status} ${xhr.statusText}`
                });
                
                if (callback_fail) {
                    callback_fail(xhr);
                }
            });
    }

    function is_form_valid(form_id) {
        let form = $('#' + form_id);
        
        if (!form.length) {
            console.error('Form not found:', form_id);
            return false;
        }
        
        // Validasi required fields
        let isValid = true;
        form.find('.required').each(function() {
            if (!$(this).val() || $(this).val() === '') {
                $(this).addClass('error');
                isValid = false;
            } else {
                $(this).removeClass('error');
            }
        });
        
        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Mohon lengkapi field yang wajib diisi'
            });
        }
        
        return isValid;
    }
    function showMessageDtsen(type, message) {
        if (type === 'success') {
            Swal.fire({
                icon: 'success',
                html: message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: message
            });
        }
    }
</script>
