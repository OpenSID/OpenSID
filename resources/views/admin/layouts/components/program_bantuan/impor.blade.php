@if (can('u'))
    <div class="modal fade" id="impor">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Impor Program Bantuan</h4>
                </div>
                <form id="mainform" action="{{ site_url('program_bantuan/impor') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="file" class="control-label">File Program Bantuan</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="file_path" name="userfile" placeholder="Pilih file Excel..." required readonly>
                                        <input type="file" class="hidden" id="file" name="userfile" accept=".xls,.xlsx,.xlsm">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-info btn-flat" id="file_browser">
                                                <i class="fa fa-search"></i> Browse
                                            </button>
                                        </span>
                                    </div>
                                    <p class="help-block text-muted small">Format file yang didukung: .xls, .xlsx, .xlsm</p>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <label class="control-label">Impor Program:</label>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="ganti_program" name="ganti_program" value="1">
                                        <label class="form-check-label" for="ganti_program">Ganti data lama jika data ditemukan sama</label>
                                    </div>
                                    <p class="help-block text-muted small">Centang jika ingin memperbarui data program bantuan lama yang memiliki nama/program sama dengan data yang diimpor. Jika tidak dicentang, data lama tidak diubah dan data baru dengan nama/program sama diabaikan.</p>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <label class="control-label">Opsi Impor Peserta:</label>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="kosongkan_peserta" name="kosongkan_peserta" value="1">
                                        <label class="form-check-label" for="kosongkan_peserta"><strong>Kosongkan data peserta sebelum impor</strong></label>
                                    </div>
                                    <p class="help-block text-muted small">Centang jika ingin menghapus semua data peserta lama sebelum data baru diimpor</p>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="ganti_peserta" name="ganti_peserta" value="1">
                                        <label class="form-check-label" for="ganti_peserta"><strong>Ganti data peserta lama jika NIK sama</strong></label>
                                    </div>
                                    <p class="help-block text-muted small">Centang jika ingin memperbarui data peserta lama yang memiliki NIK sama</p>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="rand_kartu_peserta" name="rand_kartu_peserta" value="1">
                                        <label class="form-check-label" for="rand_kartu_peserta"><strong>Acak nomor kartu peserta jika kosong</strong></label>
                                    </div>
                                    <p class="help-block text-muted small">Centang jika ingin sistem mengisi otomatis nomor kartu peserta secara acak</p>
                                </div>
                            </div>

                            <div class="col-sm-12 text-center">
                                <a href="{{ $formatImpor }}" class="btn btn-social bg-purple btn-sm">
                                    <i class="fa fa-file-excel-o"></i> Contoh Format Impor Program Bantuan
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left">
                            <i class="fa fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok">
                            <i class="fa fa-check"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
