<script type="text/javascript">
    $(function() {
        $('#response').modal({backdrop: 'static', keyboard: false}).show();
        $('#kirim').on('click', function(e) {
            e.preventDefault();
            $('#loading').modal({backdrop: 'static', keyboard: false}).show();
        });
    });
</script>
<div class="modal fade" id='loading' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header btn-warning">
                <h4 class="modal-title">Proses Sinkronisasi</h4>
            </div>
            <div class="modal-body">
                Harap tunggu sampai proses sinkronisasi selesai. Proses ini bisa memakan waktu beberapa menit tergantung data yang dikirimkan.
                <div class='text-center'>
                    <img src="<?= base_url('assets/images/background/loading.gif')?>">
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($notif = $this->session->flashdata('notif')): ?>
    <div class="modal fade" id="response" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Response</h4>
                    </div>
                    <div class="modal-body btn-<?= $notif['status']; ?>">
                        <?= $notif['pesan']; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                    </div>
                </div>
            </div>
        </div>
<?php endif; ?>