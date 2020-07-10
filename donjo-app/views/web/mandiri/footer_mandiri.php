			<div id='footer'>
				<div id="footer-left">&copy; 2016-<?= date("Y");?>
				<a target="_blank" href="https://opendesa.id">OpenDesa</a> <i class="fa fa-circle" style="font-size: smaller;"></i> <a target="_blank" href="https://github.com/OpenSID/OpenSID">OpenSID</a> <?= AmbilVersi()?>
				<br>Dikembangkan oleh <a target="_blank" href="https://www.facebook.com/groups/OpenSID/">Komunitas OpenSID</a>
				<br/>Dikelola oleh Pemerintah <?= ucwords($this->setting->sebutan_desa)?> <?= $desa['nama_desa']?>
				<?php if (file_exists('mitra')): ?>
					<br/>Hosting didukung <a target="_blank" href="https://idcloudhost.com"><img src="<?= base_url('/assets/images/Logo-IDcloudhost.png')?>" height='15px' alt="Logo-IDCloudHost" title="Logo-IDCloudHost"></a>
				<?php endif; ?>
			</div>
			<div id="footer-right">
				<ul id="global-nav-right" class="top">
					<?php foreach ($sosmed As $data): ?>
						<?php if (!empty($data['link'])): ?>
							<li><a href="<?= $data['link']?>" target="_blank"><span style="color:#fff" ><i class="fa fa-<?= strtolower($data['nama']);?><?=in_array($data['id'], [1,2])?'-square' : '';?> fa-2x"></i></span></a></li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>

	<!-- jQuery 3 -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.min.js"></script>
	<!-- Jquery UI -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery-ui.min.js"></script>
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.ui.autocomplete.scroll.min.js"></script>

	<script src="<?= base_url()?>assets/bootstrap/js/moment.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
	<!-- Select2 -->
	<script src="<?= base_url()?>assets/bootstrap/js/select2.full.min.js"></script>
	<!-- DataTables -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.dataTables.min.js"></script>
	<script src="<?= base_url()?>assets/bootstrap/js/dataTables.bootstrap.min.js"></script>
	<!-- bootstrap color picker -->
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap-colorpicker.min.js"></script>
	<!-- bootstrap Date time picker -->
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap-datetimepicker.min.js"></script>
	<script src="<?= base_url()?>assets/bootstrap/js/id.js"></script>
	<!-- bootstrap Date picker -->
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap-datepicker.min.js"></script>
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap-datepicker.id.min.js"></script>
	<!-- Bootstrap WYSIHTML5 -->
	<script src="<?= base_url()?>assets/bootstrap/js/bootstrap3-wysihtml5.all.min.js"></script>
	<!-- Slimscroll -->
	<script src="<?= base_url()?>assets/bootstrap/js/jquery.slimscroll.min.js"></script>
	<!-- FastClick -->
	<script src="<?= base_url()?>assets/bootstrap/js/fastclick.js"></script>
	<!-- AdminLTE App -->
	<script src="<?= base_url()?>assets/js/adminlte.min.js"></script>
	<script src="<?= base_url()?>assets/js/validasi.js"></script>
	<script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
	<script src="<?= base_url()?>assets/js/localization/messages_id.js"></script>
	<script src="<?= base_url()?>assets/front/js/jquery.overlay.min.js"></script>
	<script src="<?= base_url()?>assets/front/js/jquery-confirm.min.js"></script>
	<!-- Numeral js -->
	<script src="<?= base_url()?>assets/js/numeral.min.js"></script>
	<!-- Script-->
	<script src="<?= base_url()?>assets/js/script.js"></script>
	<!-- Khusus modul layanan mandiri -->
	<script src="<?= base_url() ?>assets/front/js/mandiri.js"></script>
</body>
</html>
