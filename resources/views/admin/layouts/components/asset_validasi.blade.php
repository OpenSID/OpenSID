@push('css')
    <!-- Jquery UI -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/jquery-ui.min.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('bootstrap/js/jquery.dataTables.min.js') }}"></script>
    <!-- Validasi -->
    <script src="{{ asset('js/validasi.js') }}"></script>
    <script src="{{ asset('js/localization/messages_id.js') }}"></script>
    <script>

        $('#tambahDaftarAnjungan').on('click', function(e) {
            // Mencegah form submit secara default
            e.preventDefault();

            // Cek jika anjungan_uuid sudah ada di localStorage, hapus
            if (localStorage.getItem('anjungan_uuid')) {
                localStorage.removeItem('anjungan_uuid');
            }

            // tambahakn anjungan uuid yang baru
            let uuid = $('#anjungan_id').val();
            if (uuid) {
                localStorage.setItem('anjungan_uuid', uuid);
            }
            // Submit form secara manual setelah menyimpan ke localStorage
            $('#validasi').submit();
        });

        function is_form_valid(form_id) {
            form_id = form_id.startsWith('#') ? form_id : '#' + form_id;
            let validate = $(form_id).validate();
            if (validate.errorList.length > 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        html: validate.errorList[0].message,
                    })
                } else {
                    alert(validate.errorList[0].message);
                }
                validate.errorList[0].element.focus();
                return false;
            }
            return true;
        }

        $.fn.select2.defaults.set("language", {
            inputTooShort: function(args) {
                return "Masukkan minimal " + args.minimum + " karakter";
            },
            inputTooLong: function(args) {
                return "Masukkan maksimal " + args.maximum + " karakter";
            },
            noResults: function() {
                return "Tidak ada hasil yang ditemukan";
            },
            searching: function() {
                return "Mencari...";
            },
            loadingMore: function() {
                return "Memuat lebih banyak hasil...";
            },
            escapeMarkup: function(markup) {
                return markup;
            }
        });
    </script>
@endpush
