<div id="cetakBox" class="modal fade" role="dialog" style="padding-top:30px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cetak Inventaris</h4>
            </div>
            <form target="_blank" method="get">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="penandatangan_pdf">Kepala Desa</label>
                        <select name="kades" id="kades" class="form-control input-sm select2">
                            <?php foreach ($kades as $data) : ?>
                                <option value="<?= $data['pamong_id'] ?>" data-jabatan="<?= trim($data['pamong_jabatan']) ?>" <?= selected($data['jabatan_id'], 1) ?>>
                                    <?= $data['pamong_nama'] ?> (<?= $data['pamong_jabatan'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="penandatangan_pdf">Sekretaris Desa</label>
                        <select name="sekdes" id="sekdes" class="form-control input-sm select2">
                            <?php foreach ($sekdes as $data) : ?>
                                <option value="<?= $data['pamong_id'] ?>" data-jabatan="<?= trim($data['pamong_jabatan']) ?>" <?= selected($data['jabatan_id'], 2) ?>>
                                    <?= $data['pamong_nama'] ?> (<?= $data['pamong_jabatan'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
                    <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="form_cetak" name="form_cetak" data-dismiss="modal"><i class='fa fa-check'></i> Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>