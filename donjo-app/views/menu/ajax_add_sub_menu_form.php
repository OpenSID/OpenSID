<script>
	function ganti_jenis_link(jenis)
	{
		$('.jenis_link').hide();
		$('.jenis_link').removeAttr( "name" );
		if (jenis == '1')
		{
			$('#link').show();
			$('#link').attr('name', 'link');
		}
		else if (jenis == '2')
		{
			$('#statistik_penduduk').show();
			$('#statistik_penduduk').attr('name', 'link');
		}
		else if (jenis == '3')
		{
			$('#statistik_keluarga').show();
			$('#statistik_keluarga').attr('name', 'link');
		}
		else if (jenis == '4')
		{
			$('#statistik_program_bantuan').show();
			$('#statistik_program_bantuan').attr('name', 'link');
		}
		else if (jenis == '5')
		{
			$('#statis_lainnya').show();
			$('#statis_lainnya').attr('name', 'link');
		}
		else if (jenis == '6')
		{
			$('#artikel_keuangan').show();
			$('#artikel_keuangan').attr('name', 'link');
		}
		else if (jenis == '99')
		{
			$('#eksternal').show();
			$('#eksternal > input').show();
			$('#eksternal > input').attr('name', 'link');
		}
	}
</script>
<script type="text/javascript" src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
<form action="<?=$form_action?>" method="post" id="validasi">
	<div class='modal-body'>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label class="control-label" for="nama">Nama</label>
							<input name="nama" class="form-control input-sm required nomor_sk" maxlength="50" type="text" value="<?=$submenu['nama']?>"></input>
						</div>
						<?php if (!empty($submenu['link'])): ?>
							<div class="form-group">
								<label class="control-label" for="link_sebelumnya">Link Sebelumnya</label>
								<input class="form-control input-sm" type="text" value="<?=$submenu['link']?>" disabled=""></input>
							</div>
						<?php endif; ?>
						<div class="form-group">
							<label class="control-label" for="link">Jenis Link</label>
							<select class="form-control input-sm required" id="link_tipe" name="link_tipe" style="width:100%;" onchange="ganti_jenis_link($(this).val());">
								<option option value="">-- Pilih Jenis Link --</option>
								<option value="1" <?php selected($submenu['link_tipe'], "1") ?>>Artikel Statis</option>
								<option value="2" <?php selected($submenu['link_tipe'], "2") ?>>Statistik Penduduk</option>
								<option value="3" <?php selected($submenu['link_tipe'], "3") ?>>Statistik Keluarga</option>
								<option value="4" <?php selected($submenu['link_tipe'], "4") ?>>Statistik Program Bantuan</option>
								<option value="5" <?php selected($submenu['link_tipe'], "5") ?>>Halaman Statis Lainnya</option>
								<option value="6" <?php selected($submenu['link_tipe'], "6") ?>>Artikel Keuangan</option>
								<option value="99" <?php selected($submenu['link_tipe'], "99") ?>>Eksternal</option>
							</select>
						</div>
						<div class="form-group">
							<label class="control-label" for="link">Link</label>
							<select id="link" class="form-control input-sm jenis_link select2"  name="<?php if ($submenu['link_tipe']==1): ?>link<?php endif; ?>" style="<?php if ($submenu['link_tipe']!=1): ?>display:none<?php endif; ?>">
								<option value="">Pilih Artikel Statis</option>
								<?php foreach ($link as $data): ?>
									<option value="artikel/<?= $data['id']?>" <?php selected($submenu['link'], $id) ?>><label>No link : </label><?=$data['judul']?></option>
								<?php endforeach; ?>
							</select>
							<select id="statistik_penduduk" class="form-control input-sm jenis_link"  name="<?php if ($submenu['link_tipe']==2): ?>link<?php endif; ?>" style="<?php if ($submenu['link_tipe']!=2): ?>display:none;<?php endif; ?>">
								<option value="">Pilih Statistik Penduduk</option>
								<?php foreach ($statistik_penduduk as $id => $nama): ?>
									<option value="<?=$id?>" <?php selected($submenu['link'], $id) ?>>><?= $nama?></option>
								<?php endforeach; ?>
							</select>
							<select id="statistik_keluarga" class="form-control jenis_link input-sm"  name="<?php if ($submenu['link_tipe']==3): ?>link<?php endif; ?>" style="<?php if ($submenu['link_tipe']!=3): ?>display:none;<?php endif; ?>">
								<option value="">Pilih Statistik Keluarga</option>
								<?php foreach ($statistik_keluarga as $id => $nama): ?>
									<option value="<?= $id?>" <?php selected($submenu['link'], $id) ?>><?= $nama?></option>
								<?php endforeach; ?>
							</select>
							<select id="statistik_program_bantuan" class="form-control input-sm jenis_link"  name="<?php if ($submenu['link_tipe']==4): ?>link<?php endif; ?>" style="<?php if ($submenu['link_tipe']!=4): ?>display:none;<?php endif; ?>">
								<option value="">Pilih Statistik Program Bantuan</option>
								<?php foreach ($statistik_program_bantuan as $id => $nama): ?>
									<option value="<?= $id?>" <?php selected($submenu['link'], $id) ?>><?= $nama?></option>
								<?php endforeach; ?>
							</select>
							<select id="statis_lainnya" class="form-control input-sm jenis_link" name="<?php if ($submenu['link_tipe']==5): ?>link<?php endif; ?>" style="<?php if ($submenu['link_tipe']!=5): ?>display:none;<?php endif; ?>">
								<option value="">Pilih Halaman Statis Lainnya</option>
								<?php foreach ($statis_lainnya as $id => $nama): ?>
									<option value="<?= $id?>" <?php selected($submenu['link'], $id) ?>><?= $nama?></option>
								<?php endforeach; ?>
							</select>
							<select id="artikel_keuangan" class="form-control input-sm jenis_link" name="<?php if ($submenu['link_tipe']==6): ?>link<?php endif; ?>" style="<?php if ($submenu['link_tipe']!=6): ?>display:none;<?php endif; ?>">
								<option value="">Pilih Artikel Keuangan</option>
								<?php foreach ($artikel_keuangan as $id => $nama): ?>
									<option value="<?= $id?>" <?php selected($submenu['link'], $id) ?>><?= $nama?></option>
								<?php endforeach; ?>
							</select>
							<span id="eksternal" class="jenis_link" style="<?php if ($submenu['link_tipe']!=99): ?>display:none;<?php endif; ?>">
								<input  name="<?php if ($submenu['link_tipe']==99): ?>link<?php endif; ?>" class="form-control input-sm" type="text" value="<?=$submenu['link']?>"></input>
								<span class="text-sm text-red">(misalnya: http://opensid.info)</span>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			<button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
		</div>
	</div>
</form>
