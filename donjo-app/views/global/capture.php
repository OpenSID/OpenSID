<link rel="stylesheet" href="<?= base_url('assets/css/camera.css'); ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/cropper.min.css'); ?>">
<div class="modal fade" id="modal-camera">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title text-center">Ambil Gambar (Webcam)</h4>
			</div>
			<div class="modal-body">
				<div id="kamera"></div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button type="button" class="btn btn-flat btn-danger btn-sm" onClick="ambil();"><i class="fa fa-camera"></i>&nbsp; Ambil Gambar</button>
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
					<button type="button" class="btn btn-flat btn-primary btn-sm" title="Ambil Gambar" onclick="kamera();"><i class="fa fa-camera"></i>&nbsp;</button>
					<button type="button" class="btn btn-flat btn-primary btn-sm" id="rotateL" title="Putar ke kiri"><i class="fa fa-undo"></i>&nbsp;</button>
					<button type="button" class="btn btn-flat btn-primary btn-sm" id="rotateR" title="Putar ke kanan"><i class="fa fa-repeat"></i>&nbsp;</button>
					<button type="button" class="btn btn-flat btn-primary btn-sm" id="scaleX" title="Balik Horizontal"><i class="fa fa-arrows-h"></i>&nbsp;</button>
					<button type="button" class="btn btn-flat btn-primary btn-sm" id="scaleY" title="Balik Vertikal"><i class="fa fa-arrows-v"></i>&nbsp;</button>
					<button type="button" class="btn btn-flat btn-primary btn-sm" id="reset" title="Default"><i class="fa fa-refresh"></i>&nbsp;</button>
					<button type="button" class="btn btn-flat btn-primary btn-sm" id="simpan-gambar" title="Simpan"><i class="fa fa-save"></i>&nbsp;</button>&nbsp;
					<select class="input-sm" id="ratio">
						<option value="NaN">Pilih Ratio</option>
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
<script src="<?= base_url('assets/js/webcam.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/cropper.min.js'); ?>"></script>
<script type="text/javascript">
	var shutter = new Audio();

	function konfigurasi() {
		Webcam.reset();

		shutter.autoplay = false;
		shutter.src = BASE_URL + '/assets/files/sound/shutter.mp3';

		Webcam.set({
			width: 640,
			height: 480,

			image_format: 'jpeg',
			jpeg_quality: 100,
		});

		Webcam.attach('#kamera');
	}

	function ambil() {
		shutter.play();
		Webcam.snap( function(data_uri) {
			$("#modal-camera").modal('hide');
			$("#modal-crop").modal('show');
			$("#modal-crop").modal({backdrop: "static", keyboard: false});
			$("#cropimage").html('<img id="tampilkan-latar" src="' + BASE_URL + 'assets/images/background/bg.png"/>');
			$("#cropimage").html('<img id="tampilkan-gambar" src="' + data_uri + '"/>');
			cropImage();
			Webcam.reset();
		});
	}

	function kamera() {
		$("#modal-camera").modal('show');
		$("#modal-camera").modal({backdrop: "static", keyboard: false});
		$("#modal-crop").modal('hide');
		konfigurasi();
	}

	function cropImage() {
		var image = document.querySelector('#tampilkan-gambar');
		var minAspectRatio = 0.5;
		var maxAspectRatio = 1.5;

		var cropper = new Cropper(image, {
			aspectRatio: 3 / 4,
			minCropBoxWidth: 250,
			minCropBoxHeight: 250,

			ready: function () {
				var cropper = this.cropper;
				var containerData = cropper.getContainerData();
				var cropBoxData = cropper.getCropBoxData();
				var aspectRatio = cropBoxData.width / cropBoxData.height;
				//var aspectRatio = 4 / 3;
				var newCropBoxWidth;
				cropper.setDragMode("move");
				if (aspectRatio < minAspectRatio || aspectRatio > maxAspectRatio) {
					newCropBoxWidth = cropBoxData.height * ((minAspectRatio + maxAspectRatio) / 2);

					cropper.setCropBoxData({
						left: (containerData.width - newCropBoxWidth) / 2,
						width: newCropBoxWidth
					});
				}
			},

			cropmove: function () {
				var cropper = this.cropper;
				var cropBoxData = cropper.getCropBoxData();
				var aspectRatio = cropBoxData.width / cropBoxData.height;

				if (aspectRatio < minAspectRatio) {
					cropper.setCropBoxData({
						width: cropBoxData.height * minAspectRatio
					});
				} else if (aspectRatio > maxAspectRatio) {
					cropper.setCropBoxData({
						width: cropBoxData.height * maxAspectRatio
					});
				}
			},


		});

		$("#scaleY").click(function() {
			var Yscale = cropper.imageData.scaleY;
			if(Yscale == 1){ cropper.scaleY(-1); } else { cropper.scaleY(1); };
		});

		$("#scaleX").click( function() {
			var Xscale = cropper.imageData.scaleX;
			if(Xscale == 1){ cropper.scaleX(-1); } else { cropper.scaleX(1); };
		});

		$("#rotateR").click(function() {
			cropper.rotate(45);
		});

		$("#rotateL").click(function() {
			cropper.rotate(-45);
		});

		$("#reset").click(function() {
			cropper.reset();
			alert('test');
		});

		$("#ratio").change(function() {
			var ratio = $("#ratio").val();
			cropper.setAspectRatio(ratio);
		});

		$("#simpan-gambar").click(function() {
			canvas = cropper.getCroppedCanvas({
				width: 220,
				height: 240,
			});

			var dataURL = canvas.toDataURL();

			$('#foto').attr("src", dataURL);
			$('#file_path').val(dataURL);
			$("#modal-crop").modal('hide');
		});
	};
</script>
