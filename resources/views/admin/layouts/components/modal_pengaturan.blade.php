@if ($kategori_pengaturan && can('u', $submodul_ini ?? $modul_ini))
    @include('admin.pengaturan.modal_form')
@endif
