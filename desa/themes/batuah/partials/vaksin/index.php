<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="center-head bggrad-color2 flexcenter">
	<div class="icon-titlepage bgcolor-1 flexcenter"><svg viewBox="0 0 56.966 56.966" style="height:16px;"><path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23 s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92 c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17 s-17-7.626-17-17S14.61,6,23.984,6z" /></svg></div>
	<h1>Vaksinasi</h1>
</div>

<div class="pagebox">
	<div class="statishead" style="margin-bottom:20px;">
		<h1><?= $heading ?></h1>
	</div>
	<div class="table-responsive">
			  <table class="table table-striped table-bordered" id="tabel-data" style="width:100%;">
				<thead>
				  <tr>
					<th rowspan="2">No</th>
					<th rowspan="2">Nama</th>
					<th rowspan="2">Alamat Dusun</th>
					<th rowspan="2">Tanggal</th>
					<th colspan="6">Vaksin</th>
				  </tr>
				  <tr>
					<th>I</th>
					<th>II</th>
					<th>III</th>
				  </tr>
				</thead>
				<tbody>
				  <?php foreach ($main as $data) : ?>
				  <?php if ($data->vaksin_1 == 1 || $data->vaksin_2 == 1 || $data->vaksin_3 == 1) : ?>
				  <tr>
					<td class="text-center"></td>
					<td><?= $data->nama ?></td>
					<td><?= $data->dusun ?></td>
					<td>
					  <?php if ($data->vaksin_1 == 1 && $data->vaksin_2 == 0 && $data->vaksin_3 == 0) : ?>
					  <?= $data->tgl_vaksin_1 ?>
					  <?php endif ?>

					  <?php if ($data->vaksin_1 == 1 && $data->vaksin_2 == 1 && $data->vaksin_3 == 0) : ?>
					  <?= $data->tgl_vaksin_2 ?>
					  <?php endif ?>

					  <?php if ($data->vaksin_1 == 1 && $data->vaksin_2 == 1 && $data->vaksin_3 == 1) : ?>
					  <?= $data->tgl_vaksin_3 ?>
					  <?php endif ?>
					</td>
					<td class="text-center">
					  <?php if ($data->vaksin_1 == 1 && $data->tunda == 0) : ?>
					  <i class="fa fa-check" aria-hidden="true"></i>
					  <?php endif ?>
					</td>
					<td class="text-center">
					  <?php if ($data->vaksin_2 == 1 && $data->tunda == 0) : ?>
					  <i class="fa fa-check" aria-hidden="true"></i>
					  <?php endif ?>
					</td>
					<td class="text-center">
					  <?php if ($data->vaksin_3 == 1 && $data->tunda == 0) : ?>
					  <i class="fa fa-check" aria-hidden="true"></i>
					  <?php endif ?>
					</td>
				  </tr>
				  <?php endif; ?>
				  <?php endforeach ?>
				</tbody>
			  </table>
			</div>
</div>

<script>
  $(document).ready(function () {
    var tabelData = $('#tabel-data').DataTable({
      'processing': false,
      'order': [
        [1, 'desc']
      ],
      'pageLength': 10,
      'lengthMenu': [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "Semua"]
      ],
      'columnDefs': [{
          'searchable': false,
          'targets': [0, 4, 5, 6]
        },
        {
          'orderable': false,
          'targets': [0, 4, 5, 6]
        }
      ],
      'language': {
        'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
      },
    });

    tabelData.on('order.dt search.dt', function () {
      tabelData.column(0, {
        search: 'applied',
        order: 'applied'
      }).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();
  });
</script>
	
