<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="righthead-item border-grey-soft flexcenter tip-bottom" data-toggle="tooltip" data-original-title="Komentar Terbaru" onclick="openkomentar()">
	<svg viewBox="0 0 32 32">
		<path d="M26,30a1,1,0,0,1-.62-.22L18.15,24H4a3,3,0,0,1-3-3V5A3,3,0,0,1,4,2H28a3,3,0,0,1,3,3V21a3,3,0,0,1-3,3H27v5a1,1,0,0,1-.57.9A.91.91,0,0,1,26,30ZM4,4A1,1,0,0,0,3,5V21a1,1,0,0,0,1,1H18.5a1,1,0,0,1,.63.22L25,26.92V23a1,1,0,0,1,1-1h2a1,1,0,0,0,1-1V5a1,1,0,0,0-1-1Z"/><rect height="3" width="3" x="15" y="12"/><rect height="3" width="3" x="8" y="12"/><rect height="3" width="3" x="22" y="12"/>
	</svg>
</div>
<div id="komentar" class="righthead-panel">
	<div class="righthead-panel-column bg-white border-grey-soft color-1">
		<h1>Komentar Terbaru</h1>
		<?php foreach($komen As $data): ?>
			<div class="komentar">
				<div class="komentar-person flexleft">
					<div class="komentar-icon bg-grey-dark2 flexcenter">
						<svg viewBox="0 0 24 24">
							<path d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1,1,0,0,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1A10,10,0,0,0,15.71,12.71ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"></path>
						</svg>
					</div>
					<?= $data['owner']; ?>
				</div>
				<a href="<?= site_url('artikel/' . buat_slug($data)); ?>">
					<h3><?= tgl_indo2($data['tgl_upload'])?></h3>
					<p><?= potong_teks ($data['komentar'], 100); ?>...</p>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
	<a href="javascript:void(0)" onclick="closekomentar()">
		<div class="close-area flexcenter">
			<div class="close-button bg-grey-dark"></div>
		</div>
	</a>
</div>
<div class="righthead-item border-grey-soft flexcenter tip-bottom" data-toggle="tooltip" data-original-title="Konten Populer" onclick="openpopuler()">
	<svg viewBox="0 0 32 32">
		<path d="M16,28.72a3,3,0,0,1-2.13-.88L3.57,17.54a8.72,8.72,0,0,1-2.52-6.25,8.06,8.06,0,0,1,8.14-8A8.06,8.06,0,0,1,15,5.68l1,1,.82-.82h0a8.39,8.39,0,0,1,11-.89,8.25,8.25,0,0,1,.81,12.36L18.13,27.84A3,3,0,0,1,16,28.72ZM9.15,5.28A6.12,6.12,0,0,0,4.89,7a6,6,0,0,0-1.84,4.33A6.72,6.72,0,0,0,5,16.13l10.3,10.3a1,1,0,0,0,1.42,0L27.23,15.91A6.25,6.25,0,0,0,29,11.11a6.18,6.18,0,0,0-2.43-4.55,6.37,6.37,0,0,0-8.37.71L16.71,8.8a1,1,0,0,1-1.42,0l-1.7-1.7a6.28,6.28,0,0,0-4.4-1.82Z"/>
	</svg>
</div>
<div id="populer" class="righthead-panel">
	<div class="righthead-panel-column bg-white border-grey-soft color-1">
		<h1>Konten Populer</h1>
		<div class="righthead-panel-box">
			<?php foreach (array('populer' => 'arsip_populer') as $jenis => $jenis_arsip) : ?>
				<?php foreach ($$jenis_arsip as $arsip): ?>
					<div class="arsip-right">
						<a href="<?= site_url('artikel/'.buat_slug($arsip))?>">
							<div class="arsip-right-image">
								<?php if (is_file(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip['gambar'])): ?>
									<img class="yall_lazy" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/spinner.svg") ?>" data-src="<?= base_url(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip['gambar'])?>" alt="" />
								<?php else: ?>
									<img src="<?= $latar_website ? $latar_website : base_url('assets/front/css/images/latar_website.jpg'); ?>" alt="" />
									<div class="logo-no-image"><img src="<?= gambar_desa($desa['logo']);?>" alt="" /></div>
								<?php endif;?>
							</div>
							<div class="arsip-right-title"><?= hit($arsip['hit']); ?><br/><b><?= potong_teks($arsip['judul'], 50); ?>...</b></div>
						</a>
					</div>
				<?php endforeach; ?>
			<?php endforeach ?>
		</div>
	</div>
	<a href="javascript:void(0)" onclick="closepopuler()">
		<div class="close-area flexcenter">
			<div class="close-button bg-grey-dark"></div>
		</div>
	</a>
</div>
<div class="righthead-item border-grey-soft flexcenter tip-bottom" data-toggle="tooltip" data-original-title="Kategori" onclick="openkategori()">
	<svg viewBox="0 0 32 32">
		<path d="M4,28a3,3,0,0,1-3-3V7A3,3,0,0,1,4,4h7a1,1,0,0,1,.77.36L14.8,8H27a1,1,0,0,1,0,2H14.33a1,1,0,0,1-.76-.36L10.53,6H4A1,1,0,0,0,3,7V25a1,1,0,0,0,1,1,1,1,0,0,1,0,2Z M25.38,28H4a1,1,0,0,1-1-1.21l3-14A1,1,0,0,1,7,12H30a1,1,0,0,1,1,1.21L28.32,25.63A3,3,0,0,1,25.38,28ZM5.24,26H25.38a1,1,0,0,0,1-.79L28.76,14h-21Z"/>
	</svg>
</div>
<div id="kategori" class="righthead-panel">
	<div class="righthead-panel-column bg-white border-grey-soft color-1">
		<h1>Kategori</h1>
		<ul id="ul-menu">
			<?php foreach($menu_kiri as $data):?>
				<li>
					<div class="single-link"><a href="<?= site_url("artikel/kategori/$data[slug]"); ?>"><?= $data['kategori']; ?><?php (count($data['submenu'] ?? []) > 0) and print('<span class="caret"></span>'); ?>
				</a></div>
				<?php if(count($data['submenu'] ?? []) > 0): ?>
					<ul>
						<?php foreach($data['submenu'] as $submenu):?>
							<div class="subsingle-link"><a href="<?= site_url("artikel/kategori/$submenu[slug]"); ?>"><?= $submenu['kategori']?></a></div>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</li>
		<?php endforeach;?>
	</ul>
</div>
<a href="javascript:void(0)" onclick="closekategori()">
	<div class="close-area flexcenter">
		<div class="close-button bg-grey-dark"></div>
	</div>
</a>
</div>
<script>
	function openpopuler() {
		document.getElementById("populer").style.width = "100%";
	}
	function closepopuler() {
		document.getElementById("populer").style.width = "0";
	} 
	function openkomentar() {
		document.getElementById("komentar").style.width = "100%";
	}
	function closekomentar() {
		document.getElementById("komentar").style.width = "0";
	} 
	function openkategori() {
		document.getElementById("kategori").style.width = "100%";
	}
	function closekategori() {
		document.getElementById("kategori").style.width = "0";
	} 	
</script>
