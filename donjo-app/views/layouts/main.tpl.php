<?php $this->load->view('layouts/header.php');?>
			<div id="contentwrapper">
				<div id="contentcolumn">
					<div class="innertube">
						<?php $this->load->view('partials/content.php');?>
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