<style type="text/css">
  <!-- Bug? paksa kotak cari ke sisi kanan -->
  #info_publik_wrapper .row .col-sm-6 { width: 50% !important; }
</style>

<div class="box box-danger" style="padding-bottom: 2rem;">
	<div class="box-header with-border" style="margin-bottom: 15px;">
		<h3 class="box-title"><?= $heading ?></h3>
	</div>
	<div style="margin-right: 1rem; margin-left: 1rem;">
		<div class="table-responsive">
			<table class="table table-striped table-bordered" id="info_publik">
				<thead>
					<tr>
      		  <th>No</th>
						<th>Judul Informasi</th>
      		  <th>Tahun</th>
      		  <th>Kategori</th>
      		  <th>Tanggal Upload</th>
					</tr>
				</thead>
	      <tfoot>
	      </tfoot>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {

  var url = "<?= site_url('first/ajax_informasi_publik')?>";
    table = $('#info_publik').DataTable({
      'processing': true,
      'serverSide': true,
      "pageLength": 10,
      'order': [],
      "ajax": {
        "url": url,
        "type": "POST"
      },
      //Set column definition initialisation properties.
      "columnDefs": [
        {
          "targets": [ 0 ], //first column / numbering column
          "orderable": false, //set not orderable
        },
      ],
      'language': {
        'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
      },
      'drawCallback': function (){
          $('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
      }
    });

} );

</script>
