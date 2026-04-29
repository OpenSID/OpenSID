@extends('admin.auth.index')

@section('content')
        <div class="callout callout-info" style="margin-bottom: 15px;">
            <p style="margin: 0; font-size: 12px;">
                <i class="fa fa-info-circle"></i> Kode OTP berlaku selama
                <strong>{{ setting('otp_expiry_minutes') }} menit</strong>
            </p>
        </div>

        <!-- Timer Display -->
        <div class="alert alert-info text-center" id="timer-alert" style="margin-bottom: 15px; padding: 8px;">
            <i class="fa fa-clock-o"></i>
            <strong style="font-size: 12px;">Sisa Waktu:</strong>
            <span id="timer-display" style="font-size: 18px; font-weight: bold; margin-left: 10px;">
            </span>
        </div>

        <form id="validasi" class="login-form" action="{{ $form_action }}" method="POST">
            <div class="form-group text-center">
                <div class="otp-container">
                    <input type="text" class="form-control otp-input" maxlength="1" data-index="0">
                    <input type="text" class="form-control otp-input" maxlength="1" data-index="1">
                    <input type="text" class="form-control otp-input" maxlength="1" data-index="2">
                    <input type="text" class="form-control otp-input" maxlength="1" data-index="3">
                    <input type="text" class="form-control otp-input" maxlength="1" data-index="4">
                    <input type="text" class="form-control otp-input" maxlength="1" data-index="5">
                </div>
                <input type="hidden" name="otp" id="otp-value">
            </div>

            <div class="form-group">
                <button type="submit" class="btn">Login</button>
            </div>
        </form>

        <hr />

        <div class="text-center">
            <p>Tidak menerima kode?</p>
            <button type="button" class="btn" id="resend-btn" disabled>
                <i class="fa fa-refresh"></i> Kirim Ulang (<span id="countdown">30</span>s)
            </button>
        </div>

        <hr />

        <a href="{{ site_url('siteman') }}" class="btn">
            <i class="fa fa-mobile"></i> Kembali ke Login Password
        </a>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            var expiryMinutes = {{ setting('otp_expiry_minutes') }};
            var expirySeconds = expiryMinutes * 60;
            var resendCooldown = {{ setting('otp_resend_cooldown', 30) }};
            var countdown = resendCooldown;

            // OTP Input handling
            $('.otp-input').on('input', function() {
                var value = $(this).val();

                // Only allow numbers
                if (!/^\d*$/.test(value)) {
                    $(this).val('');
                    return;
                }

                // Move to next input
                if (value.length === 1) {
                    var nextIndex = parseInt($(this).data('index')) + 1;
                    if (nextIndex < 6) {
                        $('.otp-input[data-index="' + nextIndex + '"]').focus();
                    }
                }

                // Update hidden input
                updateOtpValue();
            });

            // Handle backspace
            $('.otp-input').on('keydown', function(e) {
                if (e.keyCode === 8 && $(this).val() === '') {
                    var prevIndex = parseInt($(this).data('index')) - 1;
                    if (prevIndex >= 0) {
                        $('.otp-input[data-index="' + prevIndex + '"]').focus();
                    }
                }
            });

            // Handle paste
            $('.otp-input').on('paste', function(e) {
                e.preventDefault();
                var pastedData = e.originalEvent.clipboardData.getData('text');
                var digits = pastedData.replace(/\D/g, '').slice(0, 6);

                for (var i = 0; i < digits.length; i++) {
                    $('.otp-input[data-index="' + i + '"]').val(digits[i]);
                }

                updateOtpValue();
            });

            function updateOtpValue() {
                var otp = '';
                $('.otp-input').each(function() {
                    otp += $(this).val();
                });
                $('#otp-value').val(otp);
            }

            // Countdown timer for resend
            var resendInterval = setInterval(function() {
                countdown--;
                $('#countdown').text(countdown);

                if (countdown <= 0) {
                    clearInterval(resendInterval);
                    $('#resend-btn').prop('disabled', false).html(
                        '<i class="fa fa-refresh"></i> Kirim Ulang');
                }
            }, 1000);

            // Inisialisasi timestamp kedaluwarsa HANYA SEKALI saat halaman dimuat.
            // Ambil dari localStorage jika ada, atau buat yang baru jika tidak ada/sudah lewat.
            let expiryTimestamp = localStorage.getItem('otpLoginExpiry');
            if (!expiryTimestamp || expiryTimestamp < Date.now()) {
                expiryTimestamp = Date.now() + expiryMinutes * 60 * 1000;
                localStorage.setItem('otpLoginExpiry', expiryTimestamp);
            }

            // Expiry timer countdown
            var expiryInterval = setInterval(function() {
                let remainingMilliseconds = expiryTimestamp - Date.now();
                let remainingSeconds = Math.round(remainingMilliseconds / 1000);

                if (remainingSeconds < 0) {
                    remainingSeconds = 0;
                }

                var percentage = (remainingSeconds / (expiryMinutes * 60)) * 100;

                // Update timer display
                var minutes = Math.floor(remainingSeconds / 60);
                var seconds = remainingSeconds % 60;
                var timeString = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
                $('#timer-display').text(timeString);

                // Change timer color based on remaining time
                if (percentage < 20) {
                    $('#timer-alert').removeClass('alert-warning alert-info').addClass(
                        'alert-danger');
                } else if (percentage < 50) {
                    $('#timer-alert').removeClass('alert-info').addClass('alert-warning');
                }

                if (remainingSeconds <= 0) {
                    clearInterval(expiryInterval);
                    clearInterval(resendInterval);
                    localStorage.removeItem('otpLoginExpiry'); // Hapus dari storage
                    Swal.fire({
                        icon: 'warning',
                        title: 'Waktu Habis',
                        text: 'Kode OTP telah kedaluwarsa. Silakan minta kode baru.',
                        showConfirmButton: true
                    }).then(() => window.location.href = '{{ ci_route('siteman.otp.form_login_otp') }}');
                }
            }, 1000);

            // Resend OTP
            $('#resend-btn').click(function() {
                if ($(this).prop('disabled')) return;

                $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Mengirim...');

                $.ajax({
                    url: '{{ ci_route('siteman.otp.resend') }}',
                    method: 'POST',
                    data: {
                        '{{ $token_name }}': '{{ $token_value }}',
                        purpose: 'login',
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // Reset timer dengan membuat timestamp baru di localStorage
                        expiryTimestamp = Date.now() + expiryMinutes * 60 * 1000;
                        localStorage.setItem('otpLoginExpiry', expiryTimestamp);
                        $('#timer-alert').removeClass('alert-danger alert-warning').addClass('alert-info');

                        // Reset resend countdown
                        countdown = resendCooldown;
                        $('#resend-btn').html(
                            '<i class="fa fa-refresh"></i> Kirim Ulang (<span id="countdown">' +
                            countdown + '</span>s)');

                        resendInterval = setInterval(function() {
                            countdown--;
                            $('#countdown').text(countdown);

                            if (countdown <= 0) {
                                clearInterval(resendInterval);
                                $('#resend-btn').prop('disabled', false).html(
                                    '<i class="fa fa-refresh"></i> Kirim Ulang');
                            }
                        }, 1000);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal mengirim ulang kode OTP. Silakan coba lagi.',
                        });
                        $('#resend-btn').prop('disabled', false).html(
                            '<i class="fa fa-refresh"></i> Kirim Ulang');
                    }
                });
            });

            // Auto focus first input
            $('.otp-input[data-index="0"]').focus();

            // Hapus localStorage saat form disubmit atau kembali
            $('a[href="{{ site_url('siteman') }}"]').on('click', function() {
                localStorage.removeItem('otpLoginExpiry');
            });

            // Auto dismiss flash messages (except timer)
            window.setTimeout(function() {
                $("#notifikasi, .alert:not(#timer-alert)").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);

            const inputs = document.querySelectorAll(".otp-input");
    const otpValue = document.getElementById("otp-value");

    inputs.forEach((input, index) => {
        input.addEventListener("input", (e) => {
            const value = e.target.value.replace(/[^0-9]/g, "");
            e.target.value = value;

            // pindah ke input berikutnya
            if (value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }

            // gabungkan semua nilai
            otpValue.value = Array.from(inputs).map(i => i.value).join("");
        });

        input.addEventListener("keydown", (e) => {
            // pindah ke input sebelumnya jika tekan backspace
            if (e.key === "Backspace" && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });
        });
    </script>
@endpush
