<div class="col-md-3">
	<?php if (! $kk_baru): ?>
		<input name="no_kk" type="hidden" value="<?= $penduduk['no_kk'] ?>">
	<?php endif; ?>
	<?php $this->load->view('global/ambil_foto', ['id_sex' => $penduduk['id_sex'], 'foto' => $penduduk['foto']]); ?>
</div>
<div class="col-md-9">
	<div class="box box-primary">
		<div class="box-header with-border">
			<?php if (preg_match('/keluarga/i', $_SERVER['HTTP_REFERER'])): ?>
				<a href="<?= $_SERVER['HTTP_REFERER']; ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Data Penduduk"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Anggota Keluarga</a>
			<?php endif; ?>
			<a href="<?= site_url('penduduk/clear'); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Data Penduduk"><i class="fa fa-arrow-circle-o-left"></i>Kembali Ke Daftar Penduduk</a>
		</div>
		<div class="box-body">
			<?php $this->load->view('sid/kependudukan/penduduk_form_isian_bersama'); ?>
		</div>
		<?php if ($penduduk['status_dasar_id'] == 1 || ! isset($penduduk['status_dasar_id'])): ?>
			<div class="box-footer">
				<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class='fa fa-times'></i> Batal</button>
				<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right" onclick="$('#'+'mainform').attr('action', '<?= $form_action ?>');$('#'+'mainform').submit();"><i class="fa fa-check"></i> Simpan</button>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php $this->load->view('global/capture'); ?>
