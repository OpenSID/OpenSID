<form action="{{ ci_route('otp.request-activation') }}" method="POST">
    <div class="box-body">
        <div class="callout callout-info">
            <h4><i class="icon fa fa-info-circle"></i> Informasi</h4>
            <p>Aktifkan OTP untuk menambahkan lapisan keamanan ekstra pada akun Anda. Anda
                dapat memilih
                untuk menerima kode OTP melalui email atau Telegram.</p>
        </div>

        @if ($userData->otp_enabled)
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i> OTP Sudah Aktif</h4>
                <p>Anda saat ini menggunakan OTP melalui:
                    <strong>{{ ucfirst($userData->otp_channel) }}</strong>
                </p>
                <p>Identifier: <strong>{{ $userData->otp_identifier }}</strong></p>
            </div>

            <div class="form-group">
                <label>Ubah Konfigurasi OTP</label>
                <p class="help-block">Jika Anda ingin mengubah konfigurasi OTP, nonaktifkan
                    terlebih
                    dahulu lalu aktifkan kembali dengan konfigurasi baru.</p>
                <a href="{{ ci_route('otp.deactivate') }}" class="btn btn-warning" id="btn-deactivate-otp">
                    <i class="fa fa-times"></i> Nonaktifkan OTP
                </a>
            </div>
        @else
            @php
                $emailNotifActive = setting('email_notifikasi');
                $telegramNotifActive = setting('telegram_notifikasi');

                $defaultChannel = null;

                // Prioritaskan old('channel') jika ada dan aktif
                if (old('channel')) {
                    if (old('channel') === 'email' && $emailNotifActive) {
                        $defaultChannel = 'email';
                    } elseif (old('channel') === 'telegram' && $telegramNotifActive) {
                        $defaultChannel = 'telegram';
                    }
                }

                // Jika belum ada default channel (baik karena old('channel') tidak ada atau tidak aktif),
                // tentukan default berdasarkan pengaturan yang aktif
                if ($defaultChannel === null) {
                    if ($emailNotifActive && !$telegramNotifActive) { // Hanya email yang aktif
                        $defaultChannel = 'email';
                    } elseif (!$emailNotifActive && $telegramNotifActive) { // Hanya telegram yang aktif
                        $defaultChannel = 'telegram';
                    } elseif ($emailNotifActive && $telegramNotifActive) { // Keduanya aktif, default ke email
                        $defaultChannel = 'email';
                    }
                    // Jika keduanya tidak aktif, $defaultChannel tetap null
                }

                $canSubmit = $emailNotifActive || $telegramNotifActive;
            @endphp
            <div class="form-group">
                <label>Pilih Saluran OTP <span class="text-danger">*</span></label>
                <div class="radio">
                    <label>
                        <input type="radio" name="channel" value="email" @checked($defaultChannel === 'email') {{ $emailNotifActive ? 'required' : '' }} @disabled(!$emailNotifActive)>
                        <i class="fa fa-envelope"></i> Email @if (!$emailNotifActive) <small class="text-red">(pengaturan belum diaktifkan)</small> @endif
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="channel" value="telegram" @checked($defaultChannel === 'telegram') {{ $telegramNotifActive ? 'required' : '' }} @disabled(!$telegramNotifActive)>
                        <i class="fa fa-telegram"></i> Telegram @if (!$telegramNotifActive)
                            <small class="text-red">(pengaturan belum diaktifkan)</small>
                        @endif
                    </label>
                </div>
            </div>

            <div class="form-group" id="email-group" style="{{ $defaultChannel === 'email' ? '' : 'display: none;' }}">
                <label for="email">Alamat Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="{{ $defaultChannel === 'email' ? 'identifier' : 'email_identifier' }}"
                    placeholder="email@example.com" value="{{ old('identifier', $userData->email) }}" {{ $defaultChannel === 'email' ? 'required' : '' }}>
                <span class="help-block">Masukkan alamat email yang valid untuk menerima
                    kode
                    OTP.</span>
            </div>

            <div class="form-group" id="telegram-group" style="{{ $defaultChannel === 'telegram' ? '' : 'display: none;' }}">
                <label for="telegram">Chat ID Telegram <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="telegram" name="{{ $defaultChannel === 'telegram' ? 'identifier' : 'telegram_identifier' }}"
                    placeholder="123456789" value="{{ old('identifier', $userData->id_telegram) }}" {{ $defaultChannel === 'telegram' ? 'required' : '' }} @disabled($telegramError)>
                <span class="help-block">
                    <strong>Cara mendapatkan Chat ID:</strong><br>
                    1. Buka bot <a href="https://t.me/userinfobot" target="_blank">@userinfobot</a> di
                    Telegram<br>
                    2. Kirim pesan /start<br>
                    3. Bot akan memberikan ID Anda.<br>
                    4. Pastikan Anda juga sudah memulai percakapan dengan bot.
                </span>
                @if ($telegramError && setting('telegram_notifikasi'))
                    {{-- <span class="help-block text-red">
                        <i class="fa fa-exclamation-triangle"></i> Terjadi kesalahan saat memuat informasi bot. Pastikan token bot Telegram pada menu <strong>Pengaturan > Aplikasi</strong> sudah benar.
                    </span> --}}
                    <div class="alert alert-danger">
                        <h4><i class="icon fa fa-warning"></i> Perhatian!</h4>
                        <p>Terjadi kesalahan saat memuat informasi bot. Pastikan token bot Telegram pada menu <strong>Pengaturan > Aplikasi</strong> sudah benar.</p>
                    </div>
                @elseif ($telegramBotUsername)
                    <a href="https://t.me/{{ $telegramBotUsername }}?start" target="_blank" class="btn btn-social btn-primary btn-sm" style="margin-top: 10px;">
                        <i class="fa fa-telegram"></i> Klik untuk memulai percakapan dengan Bot {{ '@' . $telegramBotUsername }}
                        </a>
                @endif
            </div>

            <div class="alert alert-warning">
                <h4><i class="icon fa fa-warning"></i> Perhatian!</h4>
                <p>Pastikan Anda memiliki akses ke email atau Telegram yang Anda daftarkan.
                    Kode
                    verifikasi akan dikirim ke saluran yang Anda pilih.</p>
            </div>
        @endif
    </div>

    <div class="box-footer">
        @if (!$userData->otp_enabled)
            <button type="submit" class="btn btn-success btn-sm btn-social" id="submit-otp-btn" @disabled(!$canSubmit)>
                <i class="fa fa-send"></i> Kirim Kode OTP
            </button>
        @endif
    </div>
</form>
@push('scripts')
    <script>
        $(document).ready(function() {
            // Toggle between email and telegram input
            const telegramError = {{ json_encode($telegramError) }};
            const canSubmit = {{ json_encode($canSubmit) }};
            const submitBtn = $('#submit-otp-btn');

            $('input[name="channel"]').change(function() {
                var channel = $(this).val();
                if (channel === 'email') {
                    $('#email-group').show();
                    $('#telegram-group').hide();
                    $('#email').attr('name', 'identifier').prop('required', true);
                    $('#telegram').attr('name', 'telegram_identifier').prop('required', false);
                    if (canSubmit) {
                        submitBtn.prop('disabled', false);
                    }
                } else if (channel === 'telegram') {
                    $('#email-group').hide();
                    $('#telegram-group').show();
                    $('#telegram').attr('name', 'identifier').prop('required', true);
                    $('#email').attr('name', 'email_identifier').prop('required', false);
                    if (telegramError) {
                        submitBtn.prop('disabled', true);
                    }
                }
            });

            // Trigger initial state
            $('input[name="channel"]:checked').trigger('change');

            $('#btn-deactivate-otp').on('click', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin menonaktifkan OTP?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, nonaktifkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });

            // Hapus localStorage untuk timer OTP login setiap kali halaman login utama dimuat.
            // Ini untuk memastikan timer direset jika pengguna kembali ke halaman ini
            // setelah gagal OTP atau membatalkan proses.
            localStorage.removeItem('otpLoginExpiry');
        })
    </script>
@endpush
