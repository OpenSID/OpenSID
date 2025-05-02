<div class='modal fade' id='konfirmasi-cookie' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
            </div>
            <div class='modal-body bg-info'>
                <h3>Privasi Anda</h3>
                <div>
                    Dengan mengklik "Terima semua cookie", Anda setuju bahwa OpenSID dapat menyimpan cookie di perangkat Anda dan mengungkapkan informasi sesuai dengan Kebijakan Cookie kami.
                </div>
            </div>
            <div class='modal-footer'>
                <button type="button" style="margin-right:5px" class="btn btn-social btn-warning btn-sm" onclick="rejectCookie()"><i class='fa fa-sign-out'></i> Tolak</button>
                <a class='btn-ok'>
                    <button type="button" class="btn btn-social btn-danger btn-sm" onclick="buatPengunjungCookie('<?= $cookie_name ?>')"> <i class='fa fa-exclamation'></i> Terima semua cookie</button>
                </a>

            </div>
        </div>
    </div>
</div>
