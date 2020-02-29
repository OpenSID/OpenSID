<div class="box-footer">
	<div class="row">
		<div class="col-xs-12">
			<button type="reset" onclick="$('#validasi').trigger('reset');" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
			<?php if (SuratExport($url)): ?>
				<button type="button" onclick="$('#'+'validasi').submit();" class="btn btn-social btn-flat btn-success btn-sm pull-right" style="margin-right: 5px;"><i class="fa fa-file-text"></i> Ekspor Dok</button>
			<?php endif; ?>
		</div>
	</div>
</div>
