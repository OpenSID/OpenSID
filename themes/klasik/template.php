<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view($folder_themes.'/layouts/header.php');?>

<!-- Ubah setting di desa/config/config.php untuk menampilkan/menyembunyikan data COVID-19 -->
<?php if ($this->setting->covid_data) $this->load->view($folder_themes."/partials/covid.php")?>
<?php if ($this->setting->covid_desa) $this->load->view($folder_themes."/partials/covid_local.php");?>

			<div id="contentwrapper">
				<div id="contentcolumn">
					<div class="innertube">
						<?php $this->load->view($folder_themes.'/partials/content.php');?>
					</div>
				</div>
			</div>

			<div id="rightcolumn">
				<div class="innertube">
					<?php $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/side.right.php'));?>
				</div>
			</div>

			<div id="footer">
				<?php $this->load->view($folder_themes.'/partials/copywright.tpl.php');?>
			</div>
		</div>
	</body>
	<script>		
		// Periksa service worker
		if (!('serviceWorker' in navigator)) {
			console.log("Service worker tidak didukung browser ini.");
		} else {
			window.addEventListener("load", () => {
				navigator.serviceWorker
				.register("./sw.js")
				.then(registration => {
					console.log("Pendaftaran ServiceWorker berhasil", registration);                
				})
				.catch(registrationError => {
					console.log("Pendaftaran ServiceWorker gagal", registrationError);
				});
			})
		}	        
	</script>
</html>
