<div class="box box-solid">
	<div class="box-header with-border bg-aqua">
		<h4 class="box-title">PRODUK</h4>
	</div>
	<div class="box-body box-line">
		<a href="<?= site_url('layanan-mandiri/produk/form'); ?>" class="btn btn-social btn-success visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-pencil-square-o"></i>Tambah Produk</a>
		<a href="<?= site_url('layanan-mandiri/produk/pengaturan'); ?>" class="btn btn-social bg-purple visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-gear"></i>Pengaturan Lapak</a>
	</div>
	<div class="box-body box-line">
		<h4><b>DAFTAR PRODUK</b></h4>
	</div>
	<div class="box-body">
		<?php $this->load->view('fmandiri/notifikasi') ?>
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-data datatable-polos">
				<thead>
					<tr>
						<th width="1%">No</th>
						<th width="1%">Aksi</th>
						<th>PRODUK</th>
						<th width="15%">KATEGORI</th>
						<th width="10%">HARGA</th>
						<th width="10%">SATUAN</th>
						<th width="10%">POTONGAN</th>
						<th width="10%">STATUS</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($produk) :
                        foreach ($produk as $key => $data) : ?>
							<tr <?= jecho($data['status'], '2', 'class="select_row"'); ?>>
								<td class="padat"><?= ($key + 1); ?></td>
								<td class="padat">
									<a href="<?= site_url("layanan-mandiri/produk/form/{$data->id}"); ?>" class="btn btn-warning btn-sm" title="Ubah"><i class="fa fa-edit"></i></a>
								</td>
								<td><?= $data->nama; ?></td>
								<td class="padat"><?= $data->kategori->kategori; ?></td>
								<td class="padat"><?= rupiah($data->harga); ?></td>
								<td class="padat"><?= $data->satuan; ?></td>
								<td class="padat"><?= $data->tipe_potongan == 1 ? $data->potongan . '%' : rupiah($data->potongan) ?></td>
								<td class="padat"><?= $data->status == '1' ? '<label class="label label-success">Aktif</label>' : '<label class="label label-danger" title="Sedang Diverifikasi" >Tidak Aktif</label>'; ?></td>
							</tr>
						<?php endforeach;
                    else : ?>
						<tr>
							<td class="text-center" colspan="7">Data tidak tersedia</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>