@if (can("h") || $periksa_data)
  <div class="modal fade" id="confirm-status" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-red"></i>&nbsp; Konfirmasi</h4>
        </div>
        <div class="modal-body btn-info">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
          <a class="btn-ok">
            <button type="button" class="btn btn-social btn-info btn-sm" id="ok-delete"><i class="fa fa-check"></i> Simpan</button>
          </a>
        </div>
      </div>
    </div>
  </div>
@endif