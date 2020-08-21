<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="<?= base_url()?>assets/css/jquery-ui-1.12.1.css">
<script src="<?= base_url()?>assets/js/jquery-ui.min.js"></script>

<script>
	function show_kartu_peserta(elem){
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
<div class="box-header with-border">
	<h3 class="box-title"><b>DAFTAR BANTUAN YANG DITERIMA</b></h3>
</div>
<div class="box-body">
	<?php if ($bantuan_penduduk) : ?>
		<i class="fa fa-caret-right"></i> <b>SASARAN PENDUDUK</b>
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="text-center" width="1">No.</th>
						<th class="text-center" width="1">Aksi</th>
						<th>Waktu/Tanggal</th>
						<th>Nama Program</th>
						<th>Keterangan</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($bantuan_penduduk as $no => $bantuan) : ?>
					<tr>
						<td class="text-center"><?= $no + 1; ?></td>
						<td nowrap>
							<?php if($bantuan['no_id_kartu']) : ?>
								<button type="button" target="data_peserta" title="Data Peserta" href="<?= site_url("first/kartu_peserta/tampil/$bantuan[id]")?>" onclick="show_kartu_peserta($(this));" class="btn btn-success btn-flat btn-sm"><i class="fa fa-eye"></i></button>
								<a href="<?= site_url("first/kartu_peserta/unduh/$bantuan[id]")?>" class="btn bg-black btn-flat btn-sm" title="Kartu Peserta"><i class="fa fa-download"></i></a>
							<?php endif; ?>
						</td>
						<td nowrap><?= fTampilTgl($bantuan["sdate"], $bantuan["edate"]);?></td>
						<td><?= $bantuan['nama']?></td>
						<td width="60%"><?= $bantuan["ndesc"];?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php else: ?>
		<span>Anda tidak terdaftar dalam program bantuan apapun</span>
	<?php endif; ?>
</div>

