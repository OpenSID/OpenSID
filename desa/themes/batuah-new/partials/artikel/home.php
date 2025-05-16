<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="homepage">
<?php $this->load->view("$folder_themes/plus/header_side"); ?>
<div class="mainpage">
	<?php if (!empty($judul_kategori)): ?>
	<?php else : ?>
		<?php $this->load->view("$folder_themes/partials/slider"); ?>
	<?php endif; ?>
	<div class="margin-side-10">
	<div class="mainbodyarea">
		<div class="bigarea hiddenrelative">
		<div class="bigarea-margin">
			<?php if (!empty($judul_kategori)): ?>
				<?php else : ?>
				<?php $this->load->view("$folder_themes/plus/running"); ?>
				<?php $this->load->view("$folder_themes/event/hari_besar"); ?>
				<?php $this->load->view("$folder_themes/event/moment"); ?>
				<?php $this->load->view("$folder_themes/event/jadwal_shalat"); ?>
				<?php $this->load->view("$folder_themes/partials/artikel/headline"); ?>
			<?php endif; ?>
			<div class="col-default-nopad margin-top-10">
				<div class="artikelhome bggrad-color2">
					<div class="headmod bgcolor-2 flexleft">
					<h1>
						<?php if ($cari) : ?>	
							Pencarian
						<?php elseif (!empty($judul_kategori)): ?>
							Kategori
						<?php else : ?>
							Artikel & Berita
						<?php endif; ?>
					</h1>
					</div>
					<div class="tabs">
						<input type="radio" id="tab1" name="tab-control" checked>
						<input type="radio" id="tab2" name="tab-control">
						<ul>
							<li title="Features">
							<label for="tab1" role="button" class="flexcenter tip-bottom" data-toggle="tooltip" data-original-title="Format Grid">
							<svg viewBox="0 0 24 24">
								<path d="M3,11H11V3H3M3,21H11V13H3M13,21H21V13H13M13,3V11H21V3" />
							</svg>
							</label>
							</li>
							<li title="Delivery Contents">
							<label for="tab2" role="button" class="flexcenter tip-bottom" data-toggle="tooltip" data-original-title="Format Row">
							<svg viewBox="0 0 24 24" style="height:30px;">
								<path d="M9,5V9H21V5M9,19H21V15H9M9,14H21V10H9M4,9H8V5H4M4,19H8V15H4M4,14H8V10H4V14Z" />
							</svg>
							</label>
							</li>
						</ul>
						<div class="content">
							<section>
								<div class="headhome-cat flexcenter">
								<h2>
								<?php if ($cari) : ?>	
									Pencarian
								<?php elseif (!empty($judul_kategori)): ?>
									<?= $judul_kategori['kategori'] ?>
								<?php endif; ?>
								</h2>
							</div>
								<div class="gridstyle">
									<?php $this->load->view("$folder_themes/partials/artikel/artikel_home_item"); ?>
								</div>	
							</section>
							<section>
								<div class="rowstyle">
									<?php $this->load->view("$folder_themes/partials/artikel/artikel_home_item"); ?>
								</div>
							</section>
						</div>
					</div>
				</div>
			</div>
			<?php $this->load->view("$folder_themes/plus/middle_page"); ?>
		</div>
		</div>
		<div class="rightsidearea">
			<?php $this->load->view("$folder_themes/plus/side"); ?>			
		</div>
	</div>
	</div>
</div>
</div>