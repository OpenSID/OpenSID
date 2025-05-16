<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (theme_config('banner', true)) : ?>
<div class="relative-row ptb-5">
    <div class="container-page">
        <div class="autowidth">
            <?php
            $sections = [
                [
                    'url' => site_url('data-wilayah'),
                    'image' => "$this->theme_folder/$this->theme/assets/images/icon/statistik.svg",
                    'title' => 'Data Wilayah',
                    'subtitle' => 'Populasi Per Wilayah',
                ],
                [
                    'url' => site_url('lapak'),
                    'image' => "$this->theme_folder/$this->theme/assets/images/icon/shop.svg",
                    'title' => 'Lapak '.ucwords($this->setting->sebutan_desa),
                    'subtitle' => 'Hasil Olahan Warga',
                ],
                [
                    'url' => site_url('data-statistik/golongan-darah'),
                    'image' => "$this->theme_folder/$this->theme/assets/images/goldarah.png",
                    'title' => 'Golongan Darah',
                    'subtitle' => 'Data Statistik',
                ],
                [
                    'url' => site_url('kehadiran'),
                    'image' => "$this->theme_folder/$this->theme/assets/images/icon/monitor.svg",
                    'title' => 'Rekam Kehadiran',
                    'subtitle' => 'di Kantor '.ucwords($this->setting->sebutan_desa),
                ],
                [
                    'url' => site_url('pembangunan'),
                    'image' => "$this->theme_folder/$this->theme/assets/images/excavator.png",
                    'title' => 'Pembangunan',
                    'subtitle' => 'Dokumentasi Kegiatan',
                ],
                [
                    'url' => site_url('peraturan-desa'),
                    'image' => "$this->theme_folder/$this->theme/assets/images/icon/arsip.svg",
                    'title' => 'Produk Hukum',
                    'subtitle' => 'Peraturan di '.ucwords($this->setting->sebutan_desa),
                ],
                [
                    'url' => site_url('first/dpt'),
                    'image' => "$this->theme_folder/$this->theme/assets/images/voting.png",
                    'title' => 'DPT',
                    'subtitle' => 'Calon Pemilih',
                ],
                [
                    'url' => site_url('pengaduan'),
                    'image' => "$this->theme_folder/$this->theme/assets/images/pengaduan-small.png",
                    'title' => 'Lapor',
                    'subtitle' => 'Pengaduan Warga',
                ],
                [
                    'url' => site_url('galeri'),
                    'image' => "$this->theme_folder/$this->theme/assets/images/sekilas.png",
                    'title' => 'Galeri',
                    'subtitle' => 'Album Foto',
                ],
                [
                    'url' => site_url('status-sdgs'),
                    'image' => "$this->theme_folder/$this->theme/assets/images/sdgs.png",
                    'title' => 'Status SDGs',
                    'subtitle' => 'Sustainable Development Goals',
                ],
                [
                    'url' => site_url('peta'),
                    'image' => "$this->theme_folder/$this->theme/assets/images/lokasi.png",
                    'title' => 'Peta',
                    'subtitle' => 'Wilayah '.ucwords($this->setting->sebutan_desa),
                ],
                [
                    'url' => site_url('first/statistik/bantuan_penduduk'),
                    'image' => "$this->theme_folder/$this->theme/assets/images/statbantuan.png",
                    'title' => 'Bantuan',
                    'subtitle' => 'Penerima Manfaat',
                ],
            ];
            foreach ($sections as $section):
			?>
				<div class="autowidth-item bg-grey-medium border-grey-soft">
                    <a href="<?= $section['url'] ?>" <?= $section['url'] === site_url('kehadiran') ? 'target="_blank"' : '' ?>>
						<div class="autowidth-image bg-white border-grey-soft flexcenter">
							<img src="<?= base_url($section['image']) ?>" alt=""/>
						</div>
						<div class="autowidth-title flexcenter">
							<div>
								<h2><?= $section['title'] ?></h2>
								<h3><?= $section['subtitle'] ?></h3>
							</div>
						</div>
					</a>
				</div>
			<?php
			endforeach;				
            ?>
        </div>
    </div>
</div>
<script>
    function openIdentitas() {
        document.getElementById("identitas").style.height = "100%";
    }
    function closeIdentitas() {
        document.getElementById("identitas").style.height = "0";
    }      
</script>
<?php endif; ?>
