<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="article-single">
    <div class="container-page mb-20">
        <div class="box-article bg-white" style="border-radius:0 0 5px 5px;">
            <div class="box-statis border-grey-soft" style="border-radius:0 0 5px 5px;">
                <div class="head-content"><h1><?= $heading ?></h1></div>
                <div class="table-responsive table content">
                    <table class="table table-striped table-bordered" id="tabel-data" style="background:transparent;">
                        <thead>
                            <tr>
                                <th rowspan="2" class="align-middle text-center">No</th>
                                <th rowspan="2" class="align-middle text-center">Nama</th>
                                <th rowspan="2" class="align-middle text-center">Umur</th>
                                <th colspan="2" class="text-center">Alamat</th>
                                <th colspan="3" class="text-center">Vaksin</th>
                            </tr>
                            <tr>
                                <th class="text-center">RT</th>
                                <th class="text-center">RW</th>
                                <th class="text-center">I</th>
                                <th class="text-center">II</th>
                                <th class="text-center">III</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($main as $data) : ?>
                                <?php if ($data->vaksin_1 == 1 || $data->vaksin_2 == 1 || $data->vaksin_3 == 1) : ?>
                                    <tr>
                                        <td class="angka text-center"></td>
                                        <td><?= $data->nama ?></td>
                                        <td class="text-center"><?= $data->umur ?></td>
                                        <td class="text-center"><?= $data->rt ?></td>
                                        <td class="text-center"><?= $data->rw ?></td>
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
        </div>
    </div>
</div>
<script>
	$(document).ready(function(){
		var tabelData = $('#tabel-data').DataTable({
			'processing': true,
			'order': [[1, 'asc']],
			'pageLength': 10,
			'language': {
				'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
			},
         'drawCallback': function (){
            $('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
        }
    });

        tabelData.on( 'order.dt search.dt', function () {
            tabelData.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1;
            } );
        } ).draw();
    });
</script>
