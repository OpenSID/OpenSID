@push('css')
    
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            var originalAppend = $.fn.append;
            $.fn.append = function(content) {
                var isElement = content && (content instanceof $ || content instanceof Element || content instanceof Node);
                if (isElement && $(content).hasClass && $(content).hasClass('error-messages')) {
                    var $formGroup = this;
                    if ($formGroup.hasClass('form-group')) {
                        var $colDiv = $formGroup.find('[class*="col-sm-"]').last();
                        if ($colDiv.length) {
                            $colDiv.append(content);
                            return this;
                        }
                    }
                }
                return originalAppend.call(this, content);
            };

            var $forms = $('#form_validasi');
            var validationTimeouts = {};
            
            // $forms.on('input change', 'input, select, textarea', function() {
            //     var input = $(this);
            //     var form = input.closest('form');
            //     var formGroup = input.closest('.form-group');
            //     var fieldName = input.attr('name');
            //     var timeoutKey = form.attr('id') + '_' + fieldName;
                
            //     input.removeClass('error');
            //     formGroup.removeClass('has-error');
            //     formGroup.find('label[generated="true"].error').remove();
                
            //     if (validationTimeouts[timeoutKey]) {
            //         clearTimeout(validationTimeouts[timeoutKey]);
            //     }
                
            //     validationTimeouts[timeoutKey] = setTimeout(function() {
            //         var url = form.attr('action');
            //         var method = form.attr('method') || 'POST';
            //         var formData = new FormData(form[0]);
                    
            //         var singleFieldData = new FormData();
                    
            //         // Handle file inputs properly
            //         if (input.attr('type') === 'file' && input[0].files.length > 0) {
            //             singleFieldData.append(fieldName, input[0].files[0]);
            //         } else if (input.attr('type') === 'file') {
            //             // Skip validation for empty file inputs
            //             return;
            //         } else {
            //             singleFieldData.append(fieldName, input.val());
            //         }
                    
            //         $.ajax({
            //             url: url,
            //             type: method,
            //             data: singleFieldData,
            //             processData: false,
            //             contentType: false,
            //             dataType: 'json',
            //             timeout: 5000,
            //             success: function(response) {
            //                 // Jika success, tidak perlu menampilkan apa-apa
            //             },
            //             error: function(xhr) {
            //                 if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
            //                     var errors = xhr.responseJSON.errors;
                                
            //                     if (errors[fieldName]) {
            //                         var messages = errors[fieldName];
                                    
            //                         formGroup.removeClass('has-error');
            //                         formGroup.find('.error-messages').remove();
                                    
            //                         formGroup.addClass('has-error');
                                    
            //                         var errorMessages = $('<div>').addClass('error-messages');
            //                         $.each(messages, function(index, message) {
            //                             var errorLabel = $('<label>').attr({
            //                                 'for': fieldName,
            //                                 'generated': 'true',
            //                                 'class': 'error'
            //                             }).text(message);
            //                             errorMessages.append(errorLabel);
            //                         });
                                    
            //                         formGroup.append(errorMessages);
            //                     } else {
            //                         formGroup.removeClass('has-error');
            //                         formGroup.find('.error-messages').remove();
            //                     }
            //                 }
            //             }
            //         });
            //     }, 500);
            // });
            
            $forms.on('submit', function(e) {
                e.preventDefault();
                
                var form = $(this);
                var url = form.attr('action');
                var method = form.attr('method') || 'POST';
                var formData = new FormData(this);
                var defaultRedirectUrl = url.substring(0, url.lastIndexOf('/'));

                Swal.fire({
                    icon: 'info',
                    title: 'Memproses',
                    text: 'Sedang memproses data Anda...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: (modal) => {
                        Swal.showLoading();
                    }
                });

                form.find('button[type="submit"]').prop('disabled', true);

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        Swal.close();
                        form.find('button[type="submit"]').prop('disabled', false);
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: response.message,
                                timer: 2000,
                            }).then(() => {
                                var redirectUrl = response.redirect_url || defaultRedirectUrl;
                                window.location.href = redirectUrl;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message,
                                timer: 2000,
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        form.find('button[type="submit"]').prop('disabled', false);
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;

                            form.find('.has-error').removeClass('has-error');
                            form.find('label[generated="true"].error').remove();
                            form.find('input.error, select.error, textarea.error').removeClass('error');

                            $.each(errors, function(field, messages) {
                                var input = form.find('[name="' + field + '"]');
                                
                                if (input.length) {
                                    var formGroup = input.closest('.form-group');
                                    
                                    if (formGroup.length) {
                                        formGroup.addClass('has-error');
                                        
                                        var errorMessages = $('<div>').addClass('error-messages');
                                        $.each(messages, function(index, message) {
                                            var errorLabel = $('<label>').attr({
                                                'for': field,
                                                'generated': 'true',
                                                'class': 'error'
                                            }).text(message);
                                            errorMessages.append(errorLabel);
                                        });
                                        
                                        formGroup.append(errorMessages);
                                    }
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan saat memproses data.',
                                timer: 2000,
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
