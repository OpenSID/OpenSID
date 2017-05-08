<?php $this->load->view($folder_themes.'/layouts/header.php');?>
			<div id="contentwrapper">
				<div id="contentcolumn">
					<div class="innertube">
						<?php
							if($m==1)
								$this->load->view($folder_themes.'/partials/mandiri.php');
							elseif($m==2)
								$this->load->view($folder_themes.'/partials/layanan.php');
							else
								$this->load->view($folder_themes.'/partials/lapor.php');
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
				<?php $this->load->view($folder_themes.'/partials/copywright.tpl.php');?>
			</div>
		</div>
	</body>
</html>
