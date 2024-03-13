<div class="modal fade in" id="form-penduduk-luar">
    <div class="modal-dialog">
        <form id="validasi-penduduk-luar">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Isian Penduduk Luar</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="label">Label</label>
                                <input type="text" class="form-control input-sm judul" minlength="3" maxlength="20" />
                                <p class="help-block small">Isi dengan <code>[desa]</code> untuk menyesuaikan sebutan desa berdasarkan pengaturan aplikasi.</p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="input">Pilihan Input</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label><input type="checkbox" data-id="nama" checked disabled> Nama Lengkap</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" data-id="no_ktp" checked disabled> NIK / No. KTP</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" data-id="tempat_lahir"> Tempat lahir</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" data-id="tanggal_lahir"> Tanggal lahir</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" data-id="jenis_kelamin"> Jenis Kelamin</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" data-id="agama"> Agama</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="checkbox">
                                            <label><input type="checkbox" data-id="pendidikan_kk"> Pendidikan</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" data-id="pekerjaan"> Pekerjaan</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" data-id="warga_negara"> Warga Negara</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" data-id="alamat"> Alamat</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
                    <button type="button" class="btn btn-sm btn-success form-penduduk-btn"> <i class="fa fa-check"></i>
                        Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
