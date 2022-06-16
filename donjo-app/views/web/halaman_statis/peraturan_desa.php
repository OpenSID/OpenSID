<div class="box box-danger" style="padding-bottom: 2rem;">
	<div class="box-header with-border">
		<h3 class="box-title"><?= $heading ?></h3>
	</div>
  </style>
	<div class="box-body">
		<div class="row">
      <form id="peraturanForm" onsubmit="formAction(); return false;">
  			<div class="col-md-3">
  				<div class="form-group">
  					<label for="jenis_dokumen">Jenis</label>
  					<select class="form-control" name="kategori" id="kategori" onchange="formAction()">
  						<option value="">Semua</option>
  						<?php foreach ($kategori as $s): ?>
  							<option value="<?= $s['id'] ?>" <?php selected($s['id'], $kategori_dokumen) ?>><?= $s['nama'] ?></option>
  						<?php endforeach; ?>
  					</select>
  				</div>
  			</div>
  			<div class="col-md-3">
  				<div class="form-group">
  					<label for="jenis_dokumen">Tahun</label>
  					<select class="form-control" name="tahun" id="tahun" onchange="formAction()">
  						<option value="">Semua</option>
  						<?php foreach ($tahun as $t): ?>
  							<option value="<?= $t['tahun'] ?>" <?php selected($t['tahun'], $tahun_dokumen) ?> ><?= $t['tahun'] ?></option>
  						<?php endforeach; ?>
  					</select>
  				</div>
  			</div>
  			<div class="col-md-3">
  				<div class="form-group">
  					<label for="jenis_dokumen">Tentang</label>
  					<input type="text" name="tentang" id="tentang" class="form-control" style="margin-top: 0rem;">
  				</div>
  			</div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-info" style="margin-top: 2.5rem;"><i class="fa fa-search"></i> Cari</button>
        </div>
      </form>
		</div>
	</div>
	<div  style="margin-right: 1rem; margin-left: 1rem;">
		<table class="table table-striped table-bordered" id="jdih-table">
			<thead>
				<tr>
					<th>Judul Produk Hukum</th>
					<th>Jenis</th>
					<th>Tahun</th>
				</tr>
			</thead>
			<tbody id="tbody-dokumen">
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#jdih-table').DataTable({
    	"dom": 'rt<"bottom"p><"clear">',
    	"destroy": true,
      "paging": false,
      "ordering": false
    });

    get_table();
} );

function get_table() {
  var url = "<?= site_url('first/ajax_table_peraturan')?>";

  $.ajax({
    type: "GET",
    url: url,
    dataType: "JSON",
    success: function(data)
    {
      var html;
      if (data.length == 0)
      {
        html = "<tr><td colspan='3' align='center'>No Data Available</td></tr>";
      }
      for (var i = 0; i < data.length; i++)
      {
        html += "<tr>"+"<td><a href='<?= site_url('dokumen_web/unduh_berkas/')?>"+data[i].id+"'>"+data[i].nama+"</a></td>"+
        "<td>"+data[i].kategori+"</td>"+
        "<td>"+data[i].tahun+"</td>";
      }
      $('#tbody-dokumen').html(html);
    },
    error: function(err, jqxhr, errThrown)
    {
      console.log(err);
    }
  })
}

function formAction()
{
  $('#tbody-dokumen').html('');
  var url = "<?= site_url('first/filter_peraturan') ?>";
  var kategori = $('#kategori').val();
  var tahun = $('#tahun').val();
  var tentang = $('#tentang').val();

  $.ajax({
    type: "POST",
    url: url,
    data: {
      kategori: kategori,
      tahun: tahun,
      tentang: tentang
    },
    dataType: "JSON",
    success: function(data)
    {
      var html;
      if (data.length == 0)
      {
        html = "<tr><td colspan='3' align='center'>No Data Available</td></tr>";
      }
      for (var i = 0; i < data.length; i++)
      {
        html += "<tr>"+"<td><a href='<?= site_url('dokumen_web/unduh_berkas/')?>"+data[i].id+"'>"+data[i].nama+"</a></td>"+
        "<td>"+data[i].kategori+"</td>"+
        "<td>"+data[i].tahun+"</td>";
      }
      $('#tbody-dokumen').html(html);
    },
    error: function(err, jqxhr, errThrown)
    {
      console.log(err);
    }
  })
}
</script>