<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="modal-tambah" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form-tambah" action="{{ ci_route('keuangan_manual.template') }}" method="POST">
                <div class="modal-header">
                    <h4 class="modal-title">Template</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-dark">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input
                            type="number"
                            class="form-control required"
                            id="tahun"
                            name="tahun"
                            value="{{ date('Y') }}"
                            min="1945"
                            max="{{ date('Y') }}"
                        >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Buat</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
