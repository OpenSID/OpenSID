<div class="box box-danger" style="padding-bottom: 2rem;">
	<div class="box-header with-border">
		<h3 class="box-title"><?= $heading ?></h3>
	</div>
	<div class="box-body">
		<div class="row">
      <form id="peraturanForm" action="<?=site_url('dokumen_sekretariat/tentang_dokumen')?>" method="POST">
  			<div class="col-md-3">				
  				<div class="form-group">
  					<label for="jenis_dokumen">Jenis Dokumen</label>
  					<select class="form-control" name="kategori" id="kategori" onchange="formAction('peraturanForm', '<?=site_url('dokumen_sekretariat/kategori_dokumen')?>')">
  						<option value="">Semua</option>
  						<?php foreach($kategori as $s): ?>
  							<option value="<?= $s['id'] ?>" <?php if ($s['id']==$kategori_dokumen): ?>selected <?php endif ?>><?= $s['kategori'] ?></option>
  						<?php endforeach; ?>
  					</select>
  				</div>
  			</div>
  			<div class="col-md-3">
  				<div class="form-group">
  					<label for="jenis_dokumen">Tahun</label>
  					<select class="form-control" name="tahun" id="tahun" onchange="formAction('peraturanForm', '<?=site_url('dokumen_sekretariat/tahun_dokumen')?>')">
  						<option value="">Semua</option>
  						<?php foreach($tahun as $t): ?>
  							<option value="<?= $t['tahun'] ?>" <?php if ($t['tahun']==$tahun_dokumen): ?>selected <?php endif ?> ><?= $t['tahun'] ?></option>
  						<?php endforeach; ?>
  					</select>
  				</div>
  			</div>
  			<div class="col-md-3">
  				<div class="form-group">
  					<label for="jenis_dokumen">Tentang</label>
  					<input value="<?= $tentang_dokumen ?>" type="text" name="tentang" id="tentang" class="form-control" >
  				</div>
  			</div> 
        <div class="col-md-3">
          <button type="submit" class="btn btn-info" style="margin-top: 2.5rem;"><i class="fa fa-search" onsubmit="formAction('peraturanForm', '<?=site_url('dokumen_sekretariat/tentang_dokumen')?>')"></i> Cari</button>
        </div>   
      </form>
		</div>
	</div>
	<div  style="margin-right: 1rem; margin-left: 1rem;">
		<table class="table table-striped table-bordered" id="jdih-table">
			<thead>
				<tr>
					<th>Nama Dokumen</th>
					<th>Jenis</th>
					<th>Tahun</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($main as $m): ?>
					<tr>
						<td><a href="<?= base_url('desa/upload/dokumen/') . $m['satuan'] ?>"><?= $m['nama'] ?></a></td>
						<td><?= $m['kategori'] ?></td>
						<td><?= $m['tahun'] ?></td>
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

function formAction(idForm, action, target = '')
{
  if (target != '')
  {
    $('#'+idForm).attr('target', target);
  }
  $('#'+idForm).attr('action', action);
  $('#'+idForm).submit();
}
</script>