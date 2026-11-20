@if (can('h'))
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">
                        <i class="fa fa-exclamation-triangle"></i> Konfirmasi Penghapusan Data
                    </h4>
                </div>
                <div class="modal-body" data-confirm-text="{{ $konfirmasiNama ?? 'HAPUS' }}">
                    <!-- Peringatan -->
                    <div class="callout callout-danger">
                        <h4><i class="fa fa-ban"></i> Perhatian!</h4>
                        <p>Penghapusan data bersifat <strong>permanen</strong> dan <strong>tidak dapat
                                dikembalikan</strong>.</p>
                    </div>

                    <!-- Info Data yang akan dihapus -->
                    @if (isset($item_info))
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <strong><i class="fa fa-info-circle"></i> Data yang akan dihapus</strong>
                            </div>
                            <div class="panel-body">
                                {!! $item_info !!}
                            </div>
                        </div>
                    @endif

                    <!-- Konsekuensi Penghapusan -->
                    @if (isset($konsekuensi) && is_array($konsekuensi))
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <strong><i class="fa fa-warning"></i> Konsekuensi</strong>
                            </div>
                            <div class="panel-body">
                                <ul style="margin-bottom: 0; padding-left: 20px;">
                                    @foreach ($konsekuensi as $item)
                                        <li style="margin-bottom: 5px;">{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Pesan Custom -->
                    <p><strong>{{ $pesan_hapus ?? 'Apakah Anda yakin ingin menghapus data ini?' }}</strong></p>

                    <!-- Konfirmasi Input -->
                    <div class="form-group" id="confirm-group">
                        <input type="text" id="confirm-input" class="form-control input-sm"
                            placeholder="Ketik {{ $konfirmasiNama ?? 'HAPUS' }} untuk melanjutkan" autocomplete="off">
                        <span class="help-block" style="margin-bottom: 0;">
                            <small><i class="fa fa-info-circle"></i> Ketik
                                "<strong>{{ $konfirmasiNama ?? 'HAPUS' }}</strong>" (case sensitive)</small>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal">
                        <i class="fa fa-sign-out"></i> Tutup
                    </button>
                    <a class="btn-ok">
                        <button type="button" class="btn btn-social btn-danger btn-sm" id="ok-delete" disabled>
                            <i class="fa fa-trash-o"></i> Hapus
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
    <script>
        (function() {
            const modal = $('#confirm-delete');

            // Reset modal state
            const resetModal = () => {
                const input = modal.find('#confirm-input');
                const button = modal.find('#ok-delete');
                const formGroup = modal.find('#confirm-group');

                input.val('');
                button.prop('disabled', true);
                formGroup.removeClass('has-success has-error');
            };

            // Modal shown event
            modal.on('shown.bs.modal', function() {
                const input = modal.find('#confirm-input');
                const button = modal.find('#ok-delete');
                const formGroup = modal.find('#confirm-group');
                const requiredText = modal.find('.modal-body').data('confirm-text');

                resetModal();

                // Validasi input
                input.off('input.confirmDelete').on('input.confirmDelete', function() {
                    const inputValue = $(this).val();
                    const match = inputValue === requiredText;

                    button.prop('disabled', !match);

                    if (inputValue.length > 0) {
                        formGroup.toggleClass('has-error', !match)
                            .toggleClass('has-success', match);
                    } else {
                        formGroup.removeClass('has-success has-error');
                    }
                });

                // Submit dengan Enter
                input.off('keydown.confirmDelete').on('keydown.confirmDelete', function(e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        if (!button.prop('disabled')) {
                            button.click();
                        }
                    }
                });

                // Auto focus
                setTimeout(() => input.focus(), 150);
            });

            // Modal hidden event
            modal.on('hidden.bs.modal', resetModal);
        })();
    </script>
@endpush
