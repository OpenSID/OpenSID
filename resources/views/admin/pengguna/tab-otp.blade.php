@php
    $otp_activation = ci()->session->userdata('otp_activation');
    $show_verify = false;
    $remaining_seconds = 0;
    if ($otp_activation) {
        $channel = $otp_activation['channel'];
        $identifier = $otp_activation['identifier'];
        $expires_at = $otp_activation['expires_at'] ?? 0;
        $is_expired = now()->timestamp > $expires_at;

        if (!$is_expired && (($channel === 'email' && setting('email_notifikasi')) || ($channel === 'telegram' && setting('telegram_notifikasi')))) {
            $remaining_seconds = $expires_at - now()->timestamp;
            $show_verify = true;
        } else {
            ci()->session->unset_userdata('otp_activation');
        }
    }
@endphp
@if ($show_verify)
    <div class="tab-pane" id="otp">
        @include('admin.pengaturan.otp.verify-activation')
    </div>
@else
    @php
        ci()->session->set_flashdata('clear_otp_timer', true);
    @endphp
    <div class="tab-pane" id="otp">
        @include('admin.pengaturan.otp.aktivasi')
    </div>
@endif