@if (!empty($teks_berjalan))
    <div style="overflow:hidden; width:100%;">
        <div class="marquee-track-natra">
            @for ($i = 0; $i < 2; $i++)
                @include('theme::layouts.teks_berjalan')
            @endfor
        </div>
    </div>
@endif