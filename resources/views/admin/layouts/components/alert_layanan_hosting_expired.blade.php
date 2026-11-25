{{-- alert layanan hosting expired --}}
@if ($notif['langganan'] && $notif['langganan']['status_key'] === 'hosting_expired')
    <div style="background-color: {{ $notif['langganan']['warna'] }}; color: white; text-align: center; padding: 10px;">
        <a href="{{ $notif['langganan']['link'] }}" target="_blank" style="color: white; text-decoration: none;">
            <i class="fa {{ $notif['langganan']['ikon'] }}"></i>&nbsp;
            {{ $notif['langganan']['pesan'] }}
        </a>
    </div>
@endif