<div class='modal fade' id='confirm-acak' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
            </div>
            <div class='modal-body btn-info'>
                Apakah Anda yakin ingin mengacak data di server ini?
                <br><br>
                Data yang telah diacak tidak bisa dikembalikan. Pastikan data sudah dibackup.
            </div>
            <div class='modal-footer'>
                <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                <a class='btn-ok' href="{{ ci_route('database.acak') }}">
                    <button type="button" class="btn btn-social btn-danger btn-sm" id="ok-delete"><i class='fa fa-random'></i> Acak</button>
                </a>
            </div>
        </div>
    </div>
</div>
