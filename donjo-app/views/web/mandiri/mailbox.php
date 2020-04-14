<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<section class="content no-padding">
	<div class="row">
		<div class="col-sm-12">
			<div class="box-header">
				<a href="<?= site_url('mailbox_web/form')?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tulis Pesan">
					<i class="fa fa-plus"></i> Tulis Pesan
				</a>
			</div>
			<div class="box-body">
				<ul class="nav nav-tabs">
					<?php foreach($submenu as $id => $nama_menu) : ?>
						<li class="<?php ($_SESSION['mailbox'] == $id) and print('active') ?>">
							<a href="<?= site_url("first/mandiri/1/3/$id") ?>"><?= $nama_menu ?></a>
						</li>
					<?php endforeach ?>
				</ul>
			</div>
		</div>
		<div class="col-sm-12">
			<div class="box-body">
				<div class="table-responsive">
					<table class="table table-bordered dataTable">
						<thead class="bg-gray disabled color-palette">
							<tr>
								<th>No</th>
								<th>Aksi</th>
								<th>Subjek Pesan</th>
								<?php if($kat != 2) : ?><th>Status Pesan</th><?php endif; ?>
								<th>Dikirimkan Pada</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($main_list as $data) : ?>
								<tr class="<?php ($data['baca']!=1 AND $kat != 2) and print('unread')?>">
									<td><?=$data['no']?></td>
									<td nowrap>
										<a href="<?=site_url("mailbox_web/baca_pesan/{$kat}/{$data['id']}")?>" class="btn bg-navy btn-flat btn-sm" title="Baca pesan"><i class="fa fa-list">&nbsp;</i></a>
										<?php if($kat != 2) : ?>
											<?php if ($data['baca'] == 1): ?>
												<a href="<?=site_url('mailbox_web/baca/'.$data['id'].'/2')?>" class="btn bg-navy btn-flat btn-sm" title="Tandai sebagai belum dibaca"><i class="fa fa-envelope-o">&nbsp;</i></a>
											<?php else : ?>
												<a href="<?=site_url('mailbox_web/baca/'.$data['id'].'/1')?>" class="btn bg-navy btn-flat btn-sm" title="Tandai sebagai sudah dibaca"><i class="fa fa-envelope-open-o">&nbsp;</i></a>
											<?php endif; ?>
										<?php endif ?>
									</td>
									<td width="40%"><?=$data['subjek']?></td>
									<?php if($kat !=2) : ?> 
									<td nowrap><?=$data['baca'] == 1 ? 'Sudah Dibaca' : 'Belum Dibaca' ?></td>
									<?php endif ?>
									<td nowrap><?=tgl_indo2($data['created_at'])?></td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>