<div class="modal fade" id="confirm-restore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-red"></i>
                    Konfirmasi</h4>
            </div>
            <div class="modal-body btn-info">
                Apakah Anda yakin ingin mengembalikan surat bawaan/sistem ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-social btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                <a class="btn-ok">
                    <a href="#" class="btn btn-social btn-success btn-sm" id="ok-restore"><i class="fa fa-refresh"></i>
                        Kembalikan</a>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function restore(idForm, action) {
            $("#confirm-restore").modal("show");
            $("#ok-restore").click(function() {
                $("#" + idForm).attr("action", action);
                // addCsrfField($("#" + idForm)[0]);
                refreshFormCsrf();
                $("#" + idForm).submit();
            });
            return false;
        }
    </script>
@endpush
