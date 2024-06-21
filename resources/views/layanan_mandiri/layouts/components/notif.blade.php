<div
    class="modal fade"
    id="notif"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel"
    aria-hidden="true"
    data-backdrop="false"
    data-keyboard="false"
>
    <div class="modal-dialog notifikasi">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4><b>Informasi</b></h4>
                <p>{!! $pesan !!}</p>
                <a href="{!! $aksi !!}" class="btn bg-green">OK</a>
            </div>
        </div>
    </div>
</div>
