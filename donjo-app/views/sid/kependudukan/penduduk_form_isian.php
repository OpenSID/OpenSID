<div class="col-md-3">
	<?php if (!$kk_baru): ?>
		<input name="no_kk" type="hidden" value="<?= $penduduk['no_kk'] ?>">
	<?php endif; ?>
	<div class="box box-primary">
		<div class="box-body box-profile">
			<img class="penduduk profile-user-img img-responsive img-circle" src="<?= AmbilFoto($penduduk['foto'])?>" alt="Foto">
			<br/>
			<p class="text-muted text-center"> (Kosongkan jika tidak ingin mengubah foto)</p>
			<br/>
			<div class="input-group input-group-sm">
				<input type="text" class="form-control" id="file_path" name="foto">
				<input type="file" class="hidden" id="file" name="foto">
				<input type="hidden" name="old_foto" value="<?= $penduduk['foto']?>">
				<span class="input-group-btn">
					<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
				</span>
			</div>
		</div>
	</div>
</div>
<div class="col-md-9">
	<div class='box box-primary'>
		<div class="box-header with-border">
			<a href="<?=site_url()?>penduduk/clear" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Data Penduduk"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Penduduk</a>
		</div>
		<div class='box-body'>
			<?php $this->load->view('sid/kependudukan/penduduk_form_isian_bersama'); ?>
		</div>
		<?php if($penduduk['status_dasar_id'] == 1 || !isset($penduduk['status_dasar_id'])): ?>
			<div class='box-footer'>
				<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' ><i class='fa fa-times'></i> Batal</button>
				<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right" onclick="$('#'+'mainform').attr('action', '<?= $form_action ?>');$('#'+'mainform').submit();"><i class="fa fa-check"></i> Simpan</button>
			</div>
		<?php endif; ?>
	</div>
</div>
