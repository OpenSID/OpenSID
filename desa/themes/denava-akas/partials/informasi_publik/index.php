<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="article-single">
	<div class="container-page mb-20">
		<div class="headingpage border-grey-soft bg-white flexleft" style="border-radius:5px 5px 0 0;">
			<div class="headingpage-image border-grey-soft flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/arsip.svg") ?>" alt=""/></div>
			<h2>INFORMASI PUBLIK</h2>
		</div>
		<div class="box-article bg-white" style="border-radius:0 0 5px 5px;">
			<div class="box-statis border-grey-soft" style="border-radius:0 0 5px 5px;">
				<div class="table-responsive">
					<table class="table table-striped table-bordered" id="info_publik">
						<thead>
							<tr>
								<th>No</th>
								<th>Judul Informasi</th>
								<th>Tahun</th>
								<th>Kategori</th>
								<th>Tanggal Upload</th>
								<th>Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?= asset('js/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<link rel="stylesheet" href="<?= asset('js/sweetalert2/sweetalert2.min.css') ?>">
<script type="text/javascript">
$(document).ready(function() {
  var url = "<?= site_url('informasi-publik/data') ?>";
    table = $('#info_publik').DataTable({
      'processing': true,
      'serverSide': true,
      "pageLength": 10,
      'order': [[2, 'desc']],
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
      'drawCallback': function (event){
        $('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
        if (event.iDraw == 1) {
          $('#info_publik_wrapper').on('click','table tr a.pdf', function (e) {
            e.preventDefault();
              var attr = $(this).attr('href');
              Swal.fire({
                customClass:{
                      popup: 'swal-lg',
                },
            title: 'Lihat',
                html:`<object data="${attr}" style="width: 100%; min-height: 400px;" ></object>`,
                showCancelButton: true,
                cancelButtonText: 'Tutup',
                showConfirmButton: false,
              })
          })
        }
      }
    });
} );

</script>