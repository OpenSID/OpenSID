@include('admin.layouts.components.token')
<script>
    // Jangan gunakan $(document).ready()) karena bisa re-init select2 di halaman utama
    // Gunakan anonymous function yang langsung dijalankan
    (function() {
        // Select2 dengan fitur pencarian - HANYA untuk element di modal
        if ($.fn.select2) {
            $('#modalBox .fetched-data').find('.select2').each(function() {
                if (!$(this).hasClass("select2-hidden-accessible")) {
                    $(this).select2({
                        width: '100%',
                        dropdownAutoWidth: true,
                        dropdownParent: $(this).closest('.modal')
                    });
                }
            });
        }
 
        // Validasi ulang saat Select2 berubah nilainya
        $('#modalBox .fetched-data').on('change', '.select2', function() {
            $(this).valid();
        });

        const $form = $('#modalBox .fetched-data').find('form');
        $form.validate({
            errorElement: "label",
            errorClass: "error",
            highlight: function(element) {
                $(element).closest(".form-group").addClass("has-error");
            },
            unhighlight: function(element) {
                $(element).closest(".form-group").removeClass("has-error");
            },
            errorPlacement: function(error, element) {
                error.addClass('help-block');
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.next('span.select2-container').length) {
                    error.insertAfter(element.next('span.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // Pastikan modal tertutup setelah submit jika valid
        $form.on('submit', function(e) {
            if ($(this).valid()) {
                $(this).closest('.modal').modal('hide');
            } else {
                e.preventDefault();
            }
        });

        // Reset select2 ke nilai asli
        // https://stackoverflow.com/questions/10319289/how-to-execute-code-after-html-form-reset-with-jquery
        $('#modalBox .fetched-data').find('button[type="reset"]').click(function(e) {
            e.preventDefault();
            $(this).closest('form').trigger('reset');
            // https://stackoverflow.com/questions/15205262/resetting-select2-value-in-dropdown-with-reset-button
            $(this).closest('form').find('.select2').trigger('change');
            $('#kategori').trigger('change');
            var jenis_artikel = $('#jenis_artikel').val();
            if (jenis_artikel == 'Dinamis') {
                $('#kategori_dinamis').removeClass('hide').find('select').prop('disabled', false);
                $('#kategori_statis').addClass('hide').find('select').prop('disabled', true);
            } else {
                $('#kategori_dinamis').addClass('hide').find('select').prop('disabled', true);
                $('#kategori_statis').removeClass('hide').find('select').prop('disabled', false);
            }
        });

        if ($.fn.datetimepicker) {
            $('#modalBox .fetched-data').find('#jam_mati').datetimepicker({
                format: 'HH:mm',
                locale: 'id'
            });
        }
    })();
</script>
