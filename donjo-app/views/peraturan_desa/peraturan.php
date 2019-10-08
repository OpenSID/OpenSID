<div class="box box-danger" style="padding-bottom: 2rem;">
	<div class="box-header with-border">
		<h3 class="box-title"><?= $heading ?></h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-3">
				<form id="peraturan-form">
					<div class="form-group">
						<label for="jenis_dokumen">Jenis Dokumen</label>
						<select class="form-control" name="kategori" id="kategori">
							<option value="">-Pilih Jenis Dokumen-</option>
							<?php foreach($kategori as $s): ?>
								<option value="<?= $s['id'] ?>"><?= $s['kategori'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
			</div>
			<div class="col-md-3">
					<div class="form-group">
						<label for="jenis_dokumen">Tahun</label>
						<select class="form-control" name="tahun" id="tahun">
							<option value="">-Pilih Tahun-</option>
							<?php foreach($tahun as $t): ?>
								<option value="<?= $t['tahun'] ?>"><?= $t['tahun'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
			</div>
			<div class="col-md-3">
					<div class="form-group">
						<label for="jenis_dokumen">Tentang</label>
						<input type="text" name="tentang" id="tentang" class="form-control">
					</div>
				</form>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<br/>
					<button onclick="ambil_dokumen()" type="button" class="btn btn-info"><i class="fa fa-search"></i> Cari</button>
				</div>
			</div>
		</div>
	</div>
	<div  style="margin-right: 1rem; margin-left: 1rem;">
		<table class="table table-striped table-bordered" id="jdih-table">
			<thead>
				<tr>
					<th>Nama Dokumen</th>
					<th>Jenis</th>
					<th>Tahun</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($main as $m): ?>
					<tr>
						<td><a href="<?= base_url('desa/upload/dokumen/') . $m['satuan'] ?>"><?= $m['nama'] ?></a></td>
						<td><?= $m['kategori'] ?></td>
						<td><?= $m['tahun'] ?></td>
						<td><a href="<?= base_url('desa/upload/dokumen/') . $m['satuan'] ?>"><i class="fa fa-download"></i> Unduh</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>	
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#jdih-table').DataTable({
    	"dom": 'rt<"bottom"p><"clear">',
    	"destroy": true,
      "paging": false
    });
} );

function ambil_dokumen() {
	var kategori = $('#kategori').val();
	var tahun = $('#tahun').val();
	var tentang = $('#tentang').val();

	$.ajax({
		type: "POST",
		success: function (data) {
			console.log(kategori);
			window.location.replace("<?= site_url('first/peraturan_desa/')?>" + kategori + "/" +tahun+ "/"+tentang);
		},
		error: function (err, jqxhr, errThrown) {
			console.log(err);
		}
	})
}
</script>