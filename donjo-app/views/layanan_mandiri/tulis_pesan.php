<div class="box box-solid">
	<div class="box-header with-border bg-yellow">
		<h4 class="box-title">Pesan</h4>
	</div>
	<div class="box-body box-line">
		<div class="form-group">
			<a href="<?= site_url("layanan-mandiri/$tujuan"); ?>" class="btn bg-aqua btn-social"><i class="fa fa-arrow-circle-left "></i>Kembali ke <?= ucwords(spaceunpenetration($tujuan)); ?></a>
		</div>
	</div>
	<div class="box-body box-line">
		<h4><b>TULIS PESAN</b></h4>
	</div>
	<div class="box-body">
		<form id="validasi" action="<?= site_url('layanan-mandiri/pesan/kirim'); ?>" method="post">
			<div class="form-group">
				<label for="subjek">Subjek</label>
				<input type="text" class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" name="subjek" placeholder="Subjek" value="<?= $subjek; ?>">
			</div>
			<div class="form-group">
				<label for="pesan">Isi Pesan</label>
				<textarea class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, TRUE, 'kbvtext'); ?>" name="pesan" placeholder="Isi Pesan"></textarea>
			</div>
			<div class="form-group">
				<button type="submit" class="btn bg-green btn-social"><i class="fa fa-send-o"></i>Kirim Pesan</button>
			</div>
		</form>
	</div>
</div>
