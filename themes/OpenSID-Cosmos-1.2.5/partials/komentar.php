<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if(is_array($komentar)) : ?>
	<?php 
	$k = array();
	foreach ($komentar as $data) {
		if ($data['enabled'] == 1) {
			array_push($k, $data);
		}
	}
	?>
	<?php if(count($k) > 0) : ?>
		<div class="py-2 pl-4 bg-light align-middle d-flex align-items-center" style="border-left: 3px solid orange">
			<h4 class="h5 font-weight-bold m-0"><?= count($k) ?> Komentar atas artikel <?= $single_artikel["judul"]?></h4>
		</div>
		<ul class="comment-section">
			<?php foreach($k as $data) : ?>
					<li class="comment user-comment">
						<div class="info">
								<a href="#!" title="<?= $data['owner'] ?>"><?= $data['owner'] ?></a>
								<span><?= tgl_indo($data['tgl_upload']); ?></span>
						</div>
						<span class="avatar">
								<i class="fa fa-user fa-lg p-2 rounded-circle bg-light"></i>
						</span>
						<p><?= $data['komentar'] ?></p>
					</li>
			<?php endforeach ?>
		</ul>
	<?php endif ?>
<?php endif ?>
<div class="form-group group-komentar" id="kolom-komentar">
	<?php if($single_artikel['boleh_komentar']): ?>
		<div class="mb-3 font-weight-bold h6">Silakan tulis komentar dalam formulir berikut ini (Gunakan bahasa yang santun)</div>
		<div class="box box-default shadow-sm border border-info">
			<div class="box-header bg-info text-light py-2 px-3 mb-2">
				<div class="h6 font-weight-bold m-0 py-2"><i class="fa fa-comments"></i>	Formulir Komentar <span class="font-weight-normal">(Komentar baru terbit setelah disetujui Admin)</span></div>
			</div>
			<!-- Tampilkan hanya jika 'flash_message' ada -->
			<?php $label = !empty($_SESSION['validation_error']) ? 'alert-danger' : 'alert-success'; ?>
			<?php if ($flash_message): ?>
				<div class="box-header alert <?= $label?> mx-2 rounded-0"><?= $flash_message?></div>
				<?php unset($_SESSION['validation_error']); ?>
			<?php endif; ?>
			<div class="box-body py-3 px-3">
				<form id="form-komentar" name="form" action="<?= site_url('first/add_comment/'.$single_artikel['id'])?>" method="POST" onSubmit="return validasi(this);">
					<div class="form-group row">
						<label class="col-lg-3 col-form-label">Nama<span class="text-danger">*</span></label>
						<div class="col-lg-9">
							<input class="form-control input-sm" type="text" required name="owner" maxlength="30" value="<?= !empty($_SESSION['post']['owner']) ? $_SESSION['post']['owner'] : $_SESSION['nama'] ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label">No. HP<span class="text-danger">*</span></label>
						<div class="col-lg-9">
							<input class="form-control input-sm" type="text" required placeholder="" name="no_hp" maxlength="30" value="<?= $_SESSION['post']['no_hp']; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label">Alamat email</label>
						<div class="col-lg-9">
							<input class="form-control input-sm" type="text" placeholder="" name="email" maxlength="30" value="<?= $_SESSION['post']['email']; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 col-form-label">Komentar<span class="text-danger">*</span></label>
						<div class="col-lg-9">
							<textarea class="form-control input-sm" required name="komentar"><?= $_SESSION['post']['komentar'] ?></textarea>
						</div>
					</div>
					<div class="row">
						<div class="offset-lg-3 col-lg-9">
							<img id="captcha" src="<?= base_url('securimage/securimage_show.php') ?>" alt="CAPTCHA Image"/ class="img-fluid border border-black">
						</div>
					</div>
					<div class="row mb-2">
						<div class="offset-lg-3 col-lg-9">
							<a href="#!" onclick="document.getElementById('captcha').src = '<?= base_url("securimage/securimage_show.php?")?>'+Math.random(); return false"><small>[ Ganti Gambar ]</small></a>
						</div>
					</div>
					<div class="row">
						<div class="offset-lg-3 col-lg-9">
							<input class="form-control input-sm" type="text" required name="captcha_code" maxlength="6" value="<?= $_SESSION['post']['captcha_code'] ?>"/>
							<span class="d-block">
								Isikan kode di gambar
							</span>
						</div>
					</div>
					<div class="row">
						<div class="offset-lg-3 mt-3 col-lg-10">
							<button class="btn btn-info btn-md" type="submit"><i class="fa fa-paper-plane"></i> KIRIM KOMENTAR</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	<?php else: ?>
		<span class="d-block alert alert-warning px-2 py-3"><i class="fa fa-exclamation-triangle pl-1 pr-2"></i> Komentar untuk artikel ini telah ditutup.</span>
	<?php endif; ?>
</div>