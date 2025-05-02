<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-default">
<div class="artikelhome bggrad-color2 bordergrey1">
	<div class="headmod bgcolor-3 flexleft">
	<h1>Terbaru</h1>
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
				<div class="gridstyle">
					<?php $this->load->view("$folder_themes/plus/artikel_home_item"); ?>
				</div>	
			</section>
			<section>
				<div class="rowstyle">
					<?php $this->load->view("$folder_themes/plus/artikel_home_item"); ?>
				</div>
			</section>
		</div>
	</div>
</div>
</div>
