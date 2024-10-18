<?php $data = $program[0]; ?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Ubah Program Bantuan <?= $nama_excerpt; ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li><a href="<?=site_url('program_bantuan')?>"> Daftar Program Bantuan</a></li>
			<li class="active">Ubah Program Bantuan <?= $nama_excerpt; ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<div class="box-header with-border">
				<a href="<?=site_url('program_bantuan')?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Program Bantuan</a>
			</div>
			<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="box-body">
					<?php $cid = $data['sasaran']; ?>
					<div class="form-group">
						<label class="col-sm-3 control-label">Sasaran Program</label>
						<div class="col-sm-3">
							<?php if ($jml != 0): ?>
								<input type="hidden" name="cid" value="<?= $cid ?>">
								<select class="form-control input-sm" disabled>
							<?php else: ?>
								<select class="form-control input-sm required" name="cid" id="cid">
							<?php endif; ?>
								<option value="">Pilih Sasaran Program</option>
								<option value="1" <?php selected($cid, 1); ?>>Penduduk Perorangan</option>
								<option value="2" <?php selected($cid, 2); ?>>Keluarga - KK</option>
								<option value="3" <?php selected($cid, 3); ?>>Rumah Tangga</option>
								<option value="4" <?php selected($cid, 4); ?>>Kelompok / Organisasi</option>
							</select>
						</div>
					</div>
					<div class="form-group" id="penerima" <?= $data['sasaran'] == '2' ? '' : 'style="display: none;"' ?>>
						<label class="col-sm-3 control-label" for="penerima">Penerima</label>
						<div class="col-sm-9">
							<select class="form-control input-sm select2 required" name="kk_level[]" multiple="multiple" <?= $jml != 0 ? 'disabled' : '' ?>>
								<?php
                                    $data['kk_level'] = json_decode($data['kk_level'], true);
                                    if ($data['kk_level'] === null || count($kk_level) == 0) {
                                        $data['kk_level'] = ['1', '2', '3', '4'];
                                    }
                                ?>
								<?php foreach ($kk_level as $key => $value): ?>
									<option value="<?= $key ?>" <?= in_array($key, $data['kk_level'] ?? []) ? 'selected' : '' ?>><?= $value ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" for="nama">Nama Program</label>
						<div class="col-sm-8">
							<input name="nama" class="form-control input-sm nomor_sk" maxlength="100" placeholder="Nama Program"  type="text" value="<?= $data['nama']; ?>"></input>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="ndesc">Keterangan</label>
						<div class="col-sm-8">
							<textarea id="ndesc" name="ndesc" class="form-control input-sm required" placeholder="Isi Keterangan" maxlength="500" rows="8"><?= $data['ndesc']; ?></textarea>
						</div>
					</div>
					<?php $data = $program[0];
$val             = $data['asaldana']; ?>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="asaldana">Asal Dana</label>
						<div class="col-sm-3">
							<select class="form-control input-sm required" name="asaldana" id="asaldana">
								<option value="">Sumber Dana</option>
								<?php foreach ($asaldana as $ad): ?>
									<option value="<?= $ad?>" <?php selected($val, $ad); ?>><?= $ad?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="tgl_post">Rentang Waktu Program</label>
						<div class="col-sm-4">
							<div class="input-group input-group-sm date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input class="form-control input-sm pull-right" id="tgl_mulai" name="sdate" placeholder="Tgl. Mulai" type="text" value="<?= date('d-m-Y', strtotime($data['sdate'])); ?>">
							</div>
						</div>
						<div class="col-sm-4">
							<div class="input-group input-group-sm date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input class="form-control input-sm pull-right" id="tgl_akhir" name="edate" placeholder="Tgl. Akhir" type="text" value="<?= date('d-m-Y', strtotime($data['edate'])); ?>">
							</div>
						</div>
					</div>
				</div>
				<div class='box-footer'>
					<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Batal</button>
					<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
				</div>
			</div>
		</div>
	</section>
</div>
<script>
	$('#cid').change(function(){
		var cid = $(this).val();
		if (cid == 2) {
			$('#penerima').show();
			$('#penerima').find('select').addClass('required');
		} else {
			$('#penerima').hide();
			$('#penerima').find('select').removeClass('required');
		}
	});
</script>