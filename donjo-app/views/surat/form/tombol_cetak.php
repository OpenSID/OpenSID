<div class="box-footer">
	<?php if ($mandiri): ?>
		<button type="reset" onclick="window.history.back();" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
	<?php elseif ($periksa): ?>
		<a href="<?= site_url("permohonan_surat_admin/konfirmasi/{$periksa['id']}"); ?>" class="btn btn-social btn-flat btn-danger btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Konfirmasi Belum Lengkap"><i class="fa fa-times"></i> Belum Lengkap</a>
	<?php else: ?>
		<button type="reset" onclick="$('#validasi').trigger('reset');" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
	<?php endif; ?>
	<?php if ($mandiri): ?>
		<button type="button" onclick="$('#validasi').attr('action', '<?= site_url(MANDIRI . '/surat/kirim/' . $permohonan[id])?>'); $('#validasi').submit();" class="btn btn-social btn-flat btn-success btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-file-text"></i> Kirim</button>
	<?php else: ?>
		<?php if (SuratExport($url) && function_exists('exec') && $this->setting->libreoffice_path) : ?>
			<button type="button" onclick="tambah_elemen_cetak('cetak_pdf'); $('#validasi').submit()" class="btn btn-social btn-flat bg-fuchsia btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-file-pdf-o"></i> Cetak PDF</button>
		<?php endif; ?>
		<?php if (SuratExport($url)): ?>
			<button type="button" onclick="tambah_elemen_cetak('cetak_rtf'); $('#validasi').submit()" class="btn btn-social btn-flat bg-purple btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-file-word-o"></i> Unduh RTF</button>
		<?php endif; ?>
	<?php endif; ?>
</div>
<script type="text/javascript">
	function tambah_elemen_cetak($nilai) {
		$('<input>').attr({
				type: 'hidden',
				name: 'submit_cetak',
				value: $nilai
		}).appendTo($('#validasi'));
	}
</script>
