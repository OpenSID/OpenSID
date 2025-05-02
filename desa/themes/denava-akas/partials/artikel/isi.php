<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
	.isi-content table, table{width:100% !important;max-width:100% !important;}
	table.blueTable td, table.blueTable th {overflow: hidden;text-overflow: ellipsis;}
</style>
<?php if (IS_PREMIUM || (!IS_PREMIUM && IS_240810) ? $single_artikel['tipe'] == 'agenda' : $single_artikel['id_kategori'] == 1000) : ?>
	<div class="agenda-artikel">
		<table class="table" style="width:100%;">
			<tbody class="border-grey-soft">
				<tr>
					<td class="border-grey-soft" style="vertical-align:top;border:none;border-top:1px solid;">Tanggal</td>
					<td class="border-grey-soft" style="vertical-align:top;border:none;border-top:1px solid;text-align:center;width:10px;">:</td>
					<td class="border-grey-soft" style="vertical-align:top;border:none;border-top:1px solid;"><?= tgl_indo2($detail_agenda['tgl_agenda']) ?></td>
				</tr>
				<tr>
					<td class="border-grey-soft" style="vertical-align:top;border:none;border-top:1px solid;">Tempat</td>
					<td class="border-grey-soft" style="vertical-align:top;border:none;border-top:1px solid;text-align:center;width:10px;">:</td>
					<td class="border-grey-soft" style="vertical-align:top;border:none;border-top:1px solid;"><?= $detail_agenda['lokasi_kegiatan'] ?></td>
				</tr>	
				<tr>
					<td class="border-grey-soft" style="vertical-align:top;border:none;border-top:1px solid;">Koordinator</td>
					<td class="border-grey-soft" style="vertical-align:top;border:none;border-top:1px solid;text-align:center;width:10px;">:</td>
					<td class="border-grey-soft" style="vertical-align:top;border:none;border-top:1px solid;">
						<?php if (!empty($detail_agenda['koordinator_kegiatan'])): ?>
							<?= $detail_agenda['koordinator_kegiatan'] ?>
						<?php else: ?>
							-
						<?php endif; ?>
					</td>
				</tr>	
			</tbody>
		</table>
	</div>
<?php endif; ?>
<div id="printableArea" class="isi-content">
	<div style="width:100% !important;max-width:100% !important;float:left;overflow:hidden;">
		<?php $this->load->view($folder_themes . "/partials/artikel/gambar"); ?>
	</div>
	<?= htmlspecialchars_decode($single_artikel["isi"]) ?>
</div>
<?php if ($single_artikel['dokumen']!='' and is_file(LOKASI_DOKUMEN.$single_artikel['dokumen'])): ?>
	<div class="lampiran">
		<table class="border-grey-soft" style="width:100%;border:1px solid;margin-bottom:20px;">
			<tr>
				<td style="vertical-align:top;padding:10px;">Lampiran File<br/><b><?= $single_artikel['link_dokumen']?></b></td>
				<td style="vertical-align:top;text-align:right;padding:10px;"><a href="<?= site_url("first/unduh_dokumen_artikel/{$single_artikel['id']}") ?>" title=""><div class="tombol bg-gradient-hor flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/download.svg") ?>" alt=""/><p>Download</p></div></a></td>
			</tr>
		</table>
	</div>
<?php endif; ?>
<div class="sharethis-inline-reaction-buttons"></div>
<div class="share-bottom flexcenter">
	<?php
	$share = ['link' => site_url('artikel/' . buat_slug($single_artikel)), 'judul' => $single_artikel["judul"], ];
	$this->load->view($folder_themes . "/commons/share", $share);
	?>
</div>
<?php if ($single_artikel['boleh_komentar'] == 1): ?>
	<div class="fb-comments" data-href="<?= site_url('artikel/'.buat_slug($single_artikel))?>" width="100%" data-numposts="5" style="margin-bottom:20px;"></div>
<?php endif; ?>
<?php if (!empty($komentar)): ?>
	<div class="head-small border-grey-soft"><h1>Kiriman Komentar</h1></div>
	<?php foreach ($komentar AS $data): ?>
		<div class="column-<?= (count($data['children']) > 0) ? '2' : '1' ?> komentar">
			<div class="komentar-person flexleft">
				<div class="komentar-icon bg-grey-dark2 flexcenter">
					<svg x="24px" y="24px" viewBox="0 0 24 24">
						<path d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1,1,0,0,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1A10,10,0,0,0,15.71,12.71ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"></path>
					</svg>
				</div>
				<?= $data['pengguna']['nama']; ?>
			</div>
			<div class="komentar-person">
				<h3><?= tgl_indo2($data['tgl_upload'])?></h3>
				<p><?= $data['komentar']?></p>
			</div>
		</div>
		<?php if (count($data['children']) > 0): ?>
		<?php foreach ($data['children'] as $children) : ?>
		<div class="column-2 komentar">
			<div class="komentar-person flexleft">
				<div class="komentar-icon bg-grey-dark2 flexcenter">
					<svg x="24px" y="24px" viewBox="0 0 24 24">
						<path d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1,1,0,0,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1A10,10,0,0,0,15.71,12.71ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"></path>
					</svg>
				</div>
				Tanggapan : <?= $children['pengguna']['nama'] ?> <code>(<?= $children['pengguna']['level'] ?>)</code>
			</div>
			<div class="komentar-person">
				<h3><?= tgl_indo2($children['tgl_upload'])?></h3>
				<p><?= $children['komentar']?></p>
			</div>
		</div>
		<?php endforeach ?>
		<?php endif; ?>
		<div class="head-small border-grey-soft"></div>
	<?php endforeach; ?>
<?php endif; ?>
<div class="kirim-komentar" id="kolom-komentar">
	<?php if ($single_artikel['boleh_komentar'] == 1): ?>
		<div class="head-small border-grey-soft"><h1>Beri Komentar</h1></div>
		<?php
		$notif = $this->session->flashdata('notif');
		$label = ($notif['status'] == -1) ? 'label-danger' : 'label-info';
		?>
		<?php if ($notif): ?>
			<div class="flexcenter" style="width:100%;float:left;">
				<div class="komentar-notif bg-grey-dark2 flexcenter">
					<p><?= $notif['pesan']; ?></p>
				</div>
			</div>
		<?php endif; ?>
		<div class="form-komentar">
			<form class="contact_form" id="validasi" name="form" action="<?= site_url("add_comment/$single_artikel[id]"); ?>" method="POST" onSubmit="return validasi(this);">
				<input class="form-control border-grey-soft" type="text" name="owner" autocomplete="off" maxlength="100" placeholder="Isikan Nama Anda*" value="<?= $notif['data']['owner']; ?>" required>
				<input class="form-control border-grey-soft" type="text" name="no_hp" autocomplete="off" maxlength="15" placeholder="Isikan Nomor Telp./HP*" value="<?= $notif['data']['no_hp']; ?>" required>
				<input class="form-control border-grey-soft" type="text" name="email" autocomplete="off" maxlength="100" placeholder="Isikan Email" value="<?= $notif['data']['email']; ?>">
				<textarea style="width:100%;" class="form-control border-grey-soft" autocomplete="off" name="komentar" placeholder="Tulis Komentar*" required><?= $notif['data']['komentar']; ?></textarea>
				<div class="capthastyle mb-10">
					<div class="flexrow">
						<div class="captha-column1 bgwhite border-grey-soft">
							<div class="p-5 flexcenter">
								<img id="captcha" src="<?= site_url('captcha') ?>" alt="CAPTCHA Image"/>
								<a href="#" onclick="document.getElementById('captcha').src = '<?= site_url('captcha') ?><?= IS_PREMIUM && IS_240710 ? '?\' + Math.random();' : '\'; return false'?>" alt="">
									<i class="fa fa-refresh fa-lg" style="padding: 10px; color: #0b7d33"></i>
								</a>
							</div>
						</div>
						<div class="captha-column2 border-grey-soft flexcenter">
							<input type="text" name="captcha_code" autocomplete="off" class="form-control border-grey-soft" maxlength="6" placeholder="Ketik kode di sini*" value="<?= $notif['data']['captcha_code']; ?>" style="margin:0 10px !important;border:none !important;text-align:center;background:transparent !important;" required/>
						</div>
					</div>
				</div>
				<div class="flexcenter"><input class="tombol bg-gradient-hor flexcenter" autocomplete="off" type="submit" value="Kirim Komentar"></div>
			</form>
		</div>
	<?php else: ?>
		<span class='info'></span>
	<?php endif; ?>
</div>
