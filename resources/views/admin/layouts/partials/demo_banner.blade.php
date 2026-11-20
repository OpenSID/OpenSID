{{-- Penanda halus bahwa data desa telah dianonimkan (diacak). Tampil sebagai
     bilah tipis di pojok kanan atas konten agar tidak mengganggu, bukan callout
     mencolok. Aktif bila setelan `data_diacak` terisi (lihat DiacakFlagSanitizer). --}}
@if (setting('data_diacak'))
    <div style="background:#f9f9f9;border-bottom:1px solid #e4e4e4;padding:3px 15px;font-size:11px;color:#999;text-align:right;">
        <i class="fa fa-user-secret" style="color:#bbb"></i>
        Data desa ini telah dianonimkan pada
        {{ \Carbon\Carbon::parse(setting('data_diacak'))->translatedFormat('d M Y H:i') }} — bukan data warga sebenarnya.
    </div>
@endif
