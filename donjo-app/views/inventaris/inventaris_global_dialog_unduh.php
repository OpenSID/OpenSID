<div id="unduhBox" class="modal fade" role="dialog" style="padding-top:30px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Unduh Inventaris</h4>
            </div>
            <form target="_blank" class="form-horizontal" method="get">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" style="text-align:left;" for="nama_barang">Tahun</label>
                        <div class="col-sm-9">
                            <select name="tahun" id="tahun" class="form-control select2 input-sm" style="width:100%;">
                                <option value="1">Semua Tahun</option>
                                <?php for ($i = date('Y'); $i >= date('Y') - 30; $i--) : ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label required" style="text-align:left;" for="penandatangan">Penandatangan</label>
                        <div class="col-sm-9">
                            <select name="penandatangan" id="penandatangan" class="form-control input-sm">
                                <?php foreach ($pamong as $data) : ?>
                                    <option value="<?= $data['pamong_id'] ?>" data-jabatan="<?= trim($data['pamong_jabatan']) ?>" <?= selected($data['pamong_id'], $desa['pamong_id']) ?>>
                                        <?= $data['pamong_nama'] ?> (<?= $data['pamong_jabatan'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?= batal(); ?>
                    <button type="submit" class="btn btn-social btn-info btn-sm" id="form_download" name="form_download" data-dismiss="modal"><i class='fa fa-check'></i> Unduh</button>
                </div>
            </form>
        </div>
    </div>
</div>