@if (can('u'))
    <div class="modal fade" id="tambah-rtm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-red"></i> Konfirmasi</h4>
                </div>
                <div class="modal-body btn-info">
                    {{ $pesan ?? 'Apakah Anda yakin ingin menambahkan data keluarga ke rumah tangga?' }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                    <a class="btn-ok">
                        <div class="btn btn-social btn-danger btn-sm" id="ok-tambah-rtm"><i class="fa fa-plus"></i> Tambah</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
