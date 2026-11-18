@if ($kategori_pengaturan && can('u', $akses_modul))
    <div class="modal fade" id="pengaturan" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"> Pengaturan {{ ucwords(str_replace('_', ' ', $kategori_pengaturan)) }}</h4>
                </div>

                @include('admin.pengaturan.modal_form')

            </div>
        </div>
    </div>
@endif
