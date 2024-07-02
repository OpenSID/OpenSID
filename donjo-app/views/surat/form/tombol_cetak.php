<div class="box-footer">
    <?php if ($mandiri): ?>
        <button type="reset" onclick="window.history.back();" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
    <?php elseif ($periksa): ?>
        <a href="<?= site_url("permohonan_surat_admin/konfirmasi/{$periksa['id']}"); ?>" class="btn btn-social btn-flat btn-danger btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Konfirmasi Belum Lengkap"><i class="fa fa-times"></i> Belum Lengkap</a>
    <?php else: ?>
        <button type="reset" onclick="$('#validasi').trigger('reset');" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
    <?php endif; ?>
    <?php if ($mandiri): ?>
        <button type="button" onclick="$('#validasi').attr('action', '<?= site_url(MANDIRI . '/surat/kirim/' . $permohonan['id'])?>'); $('#validasi').submit();" class="btn btn-social btn-flat btn-success btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-file-text"></i> Kirim</button>
    <?php else: ?>
        <?php if (SuratExport($url) && function_exists('exec') && $this->setting->libreoffice_path) : ?>
            <button type="button" onclick="tambah_elemen_cetak('cetak_pdf');" class="btn btn-social btn-flat bg-fuchsia btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-file-pdf-o"></i> Cetak PDF</button>
        <?php endif; ?>
        <?php if (SuratExport($url) !== '' && SuratExport($url) !== '0'): ?>
            <button type="button" onclick="tambah_elemen_cetak('cetak_rtf');" id="btn_cetak_rtf" class="btn btn-social btn-flat bg-purple btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-file-word-o"></i> Unduh RTF</button>
        <?php endif; ?>
        <?php if (in_array($surat['jenis'], [3, 4])): ?>
            <button type="button" onclick="tambah_elemen_cetak('cetak_pdf');" class="btn btn-social btn-flat bg-fuchsia btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-file-word-o"></i> Cetak PDF</button>
        <?php endif; ?>
    <?php endif; ?>
    <a href="<?= site_url('keluar/clear/masuk') ?>" id="next" class="btn btn-social btn-info btn-sm btn-sm pull-right visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" style="display: none !important;">
            ke Permohonan Surat<i class="fa fa-arrow-circle-right"></i></a>
</div>
<script type="text/javascript">
    function tambah_elemen_cetak($nilai) {
        $('<input>').attr({
                type: 'hidden',
                name: 'submit_cetak',
                value: $nilai
        }).appendTo($('#validasi'));

        if ($nilai == 'cetak_rtf' || $nilai == 'cetak_pdf') {
            $('#validasi').submit();

            if ($('.box-body').find('.has-error').length < 1) {
                $('#btn_cetak_rtf').hide();
                $('#next').show();
            }

        }

    }
</script>
