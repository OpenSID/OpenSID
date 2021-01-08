<link rel="stylesheet" href="<?= base_url()?>assets/bootstrap/css/jquery-ui.min.css">
<script src="<?= base_url()?>assets/bootstrap/js/jquery-ui.min.js"></script>
<script>
	function show_kartu_peserta(elem) {
		var id = elem.attr('target');
		var title = elem.attr('title');
		var url = elem.attr('href');
		$('#'+id+'').remove();

		$('body').append('<div id="'+id+'" title="'+title+'" style="display:none;position:relative;overflow:scroll;"></div>');

		$('#'+id+'').dialog({
			resizable: true,
			draggable: true,
			width: 500,
			height: 'auto',
			open: function(event, ui) {
				$('#'+id+'').load(url);
			}
		});
		$('#'+id+'').dialog('open');
	}
</script>
<div class="box box-solid">
	<div class="box-header with-border bg-red">
		<h4 class="box-title">Bantuan</h4>
	</div>
	<div class="box-body box-line">
		<h4><b>BANTUAN PENDUDUK</b></h4>
	</div>
	<div class="box-body box-line">
		<div class="table-responsive">
		<table class="table table-bordered table-hover table-data" id="datatable-polos">
				<thead>
					<tr class="judul">
						<th>No</th>
						<th>Aksi</th>
						<th width="20%">Waktu / Tanggal</th>
						<th width="20%">Nama Program</th>
						<th>Keterangan</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($bantuan_penduduk):
						foreach ($bantuan_penduduk as $key => $item): ?>
							<tr>
								<td class="padat"><?= ($key + 1); ?></td>
								<td class="padat">
									<?php if ($item['no_id_kartu']) : ?>
										<button type="button" target="data_peserta" title="Data Peserta" href="<?= site_url("layanan_mandiri/bantuan/kartu_peserta/tampil/$item[id]")?>" onclick="show_kartu_peserta($(this));" class="btn btn-success btn-sm" ><i class="fa fa-eye"></i></button>
										<a href="<?= site_url("mandiri_web/kartu_peserta/unduh/$item[id]")?>" class="btn bg-black btn-sm" title="Kartu Peserta" <?php empty($item['kartu_peserta']) and print('disabled')?>><i class="fa fa-download"></i></a>
									<?php endif; ?>
								</td>
								<td><?= fTampilTgl($item["sdate"], $item["edate"]); ?></td>
								<td><?= $item["nama"]; ?></td>
								<td><p align="justify"><?= $item["ndesc"];?></p></td>
							</tr>
						<?php endforeach;
					else: ?>
						<tr>
							<td class="text-center" colspan="4">Data tidak tersedia</td>
						</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
