<?php $this->load->view('layouts/header.php');?>
			<div id="contentwrapper">
				<div id="contentcolumn">
					<div class="innertube">
						<?php 
							if($m==1)
								$this->load->view('partials/mandiri.php');
							elseif($m==2)
								$this->load->view('partials/layanan.php');
							else
								$this->load->view('partials/lapor.php');
						?>
					</div>
				</div>
			</div>

			<div id="rightcolumn">
				<div class="innertube">
					<?php $this->load->view('partials/side.right.php');?>
				</div>
			</div>

			<div id="footer">
				<?php $this->load->view('partials/copywright.tpl.php');?>
			</div>
		</div>
	</body>
</html>
