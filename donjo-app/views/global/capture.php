<link rel="stylesheet" href="<?= asset('css/camera.css'); ?>">
<link rel="stylesheet" href="<?= asset('css/cropper.min.css'); ?>">
<div class="modal fade" id="modal-camera">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title text-center">Ambil Gambar</h4>
			</div>
			<div class="modal-body">
				<div id="kamera"></div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<div class="btn-group">
						<button type="button" class="btn btn-flat btn-danger btn-sm" onClick="ambil();"><i class="fa fa-camera"></i>&nbsp; Ambil Gambar</button>
					</div>
					<select class="input-sm" id="mode">
							<option value="user" selected>Kamera Depan</option>
							<option value="environment">Kamera Belakang</option>
							<option value="computer">Webcam</option>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="modal-crop">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title text-center">Ubah Gambar</h4>
			</div>
			<div class="modal-body">
				<div id="cropimage"></div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<div class="btn-group">
						<button type="button" class="btn btn-flat btn-danger btn-sm" title="Ambil Gambar" onclick="kamera();"><i class="fa fa-camera"></i>&nbsp;</button>
						<button type="button" class="btn btn-flat btn-primary btn-sm" id="rotateL" title="Putar ke kiri"><i class="fa fa-undo"></i>&nbsp;</button>
						<button type="button" class="btn btn-flat btn-primary btn-sm" id="rotateR" title="Putar ke kanan"><i class="fa fa-repeat"></i>&nbsp;</button>
						<button type="button" class="btn btn-flat btn-primary btn-sm" id="scaleX" title="Balik Horizontal"><i class="fa fa-arrows-h"></i>&nbsp;</button>
						<button type="button" class="btn btn-flat btn-primary btn-sm" id="scaleY" title="Balik Vertikal"><i class="fa fa-arrows-v"></i>&nbsp;</button>
						<button type="button" class="btn btn-flat btn-primary btn-sm" id="reset-ini" title="Default"><i class="fa fa-refresh"></i>&nbsp;</button>
						<button type="button" class="btn btn-flat btn-success btn-sm" id="simpan-gambar" title="Simpan"><i class="fa fa-save"></i>&nbsp;</button>
					</div>
					<div class="btn-group">
						<select class="input-sm" id="ratio">
							<option value="NaN">Pilih Ratio (NaN)</option>
							<option value="1.777">16 : 9</option>
							<option value="1.500">3 : 2</option>
							<option value="1.333" selected>4 : 3</option>
							<option value="1.000">1 : 1</option>
							<option value="0.666">2 : 3</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?= asset('js/webcam.min.js') ?>"></script>
<script src="<?= asset('js/cropper.min.js') ?>"></script>
<script src="<?= asset('js/main-camera.js') ?>"></script>
