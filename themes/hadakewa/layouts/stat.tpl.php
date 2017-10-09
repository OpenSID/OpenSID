<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view($folder_themes.'/layouts/header.php');?>
			<div id="contentwrapper">
				<div id="contentcolumn">
					<div class="innertube">
						<?php
						if($tipe == 2){
							if($tipex==1){
								$this->load->view($folder_themes.'/partials/statistik_sos.php');
							}
						}elseif($tipe == 3){
							$this->load->view($folder_themes.'/partials/wilayah.php');
						}else{
							$this->load->view($folder_themes.'/partials/statistik.php');
						}
						?>
					</div>
				</div>
			</div>

			<div id="rightcolumn">
				<div class="innertube">
					<?php $this->load->view($folder_themes.'/partials/side.right.php');?>
				</div>
			</div>

			<div id="footer">
				<?php
				$this->load->view($folder_themes.'/partials/copywright.tpl.php');
				?>
			</div>
		</div>
	</body>
</html>
