	<!-- Perubahan script coding untuk bisa menampilkan footer dalam bentuk tampilan bootstrap (AdminLTE)  -->
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
				  	<b>Version</b> <?php echo AmbilVersi()?>
				</div>
				<strong>Aplikasi <a href="https://github.com/OpenSID/OpenSID" target="_blank"> OpenSID</a> Berbasis SID, Dikembangkan oleh <a href="http://www.combine.or.id" target="_blank">Combine.or.id</a>.</strong>
			</footer>
		</div>

		<!-- jQuery 3 -->
		<script src="<?php echo base_url()?>assets/bootstrap/js/jquery.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
		<!-- Select2 -->
		<script src="<?php echo base_url()?>assets/bootstrap/js/select2.full.min.js"></script>
		<!-- DataTables -->
		<script src="<?php echo base_url()?>assets/bootstrap/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url()?>assets/bootstrap/js/dataTables.bootstrap.min.js"></script>
		<!-- InputMask -->
		<script src="<?php echo base_url()?>assets/bootstrap/js/jquery.inputmask.js"></script>
		<script src="<?php echo base_url()?>assets/bootstrap/js/jquery.inputmask.date.extensions.js"></script>
		<script src="<?php echo base_url()?>assets/bootstrap/js/jquery.inputmask.extensions.js"></script>
		<script src="<?php echo base_url()?>assets/bootstrap/js/daterangepicker.js"></script>
		<!-- datepicker -->
		<script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap-datepicker.min.js"></script>
		<!-- bootstrap color picker -->
		<script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap-colorpicker.min.js"></script>
		<!-- bootstrap time picker -->
		<script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap-timepicker.min.js"></script>
		<!-- Bootstrap WYSIHTML5 -->
		<script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap3-wysihtml5.all.min.js"></script>
		<!-- Slimscroll -->
		<script src="<?php echo base_url()?>assets/bootstrap/js/jquery.slimscroll.min.js"></script>
		<!-- FastClick -->
		<script src="<?php echo base_url()?>assets/bootstrap/js/fastclick.js"></script>
		<!-- AdminLTE App -->
		<script src="<?php echo base_url()?>assets/js/adminlte.min.js"></script>
		<script src="<?php echo base_url()?>assets/js/validasi.js"></script>
		<script src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
		<!-- Highcharts -->
		<script src="<?php echo base_url()?>assets/js/highcharts/highcharts.js"></script>
		<script src="<?php echo base_url()?>assets/js/highcharts/highcharts-more.js"></script>
		<script src="<?php echo base_url()?>assets/js/highcharts/exporting.js"></script>
		<script src="<?php echo base_url()?>assets/js/highcharts/export-data.js"></script>
		<!-- Script-->
		<script src="<?php echo base_url()?>assets/js/script.js"></script>
		<!-- OpenStreetMap Js-->
		<script src="<?php echo base_url()?>assets/js/leaflet.js"></script>
		<script src="<?php echo base_url()?>assets/js/leaflet.pm.min.js"></script>
		<!-- NOTIFICATION-->
		<script type="text/javascript">
			$('document').ready(function(){
				if($('#success-code').val() == 1){
					notify = 'success';
					notify_msg = 'Data berhasil disimpan';
				} else if($('#success-code').val() == -1){
					notify = 'error';
					notify_msg = 'Data gagal disimpan <?php echo $_SESSION["error_msg"]?>';
				} else if($('#success-code').val() == -2){
					notify = 'error';
					notify_msg = 'Data gagal diimpan, nama id sudah ada!';
				} else if($('#success-code').val() == -3){
					notify = 'error';
					notify_msg = 'Data gagal diimpan, nama id sudah ada!';
				} else if($('#success-code').val() == 4){
					notify = 'success';
					notify_msg = 'Data berhasil dihapus';
				} else if($('#success-code').val() == -4){
					notify = 'error';
					notify_msg = 'Data gagal dihapus';
				} else {
					notify = '';
					notify_msg = '';
				}
				notification(notify,notify_msg);
				$('#success-code').val('');
			});
		</script>
		<?php  $_SESSION['success']=0; ?>

		<!-- ************ -->
		<?php if ($this->session->admin_warning && !config_item('demo')): ?>
			<script type="text/javascript">
				<?php if (isset($_SESSION['dari_login'])): ?>
					$(window).on('load',function(){
						$('#massageBox').modal('show');
						$('#ok').click(function() {$('#massageBox').modal('hide');});
					});
					<?php unset($_SESSION['dari_login']) ?>
				<?php endif; ?>
			</script>
		<?php endif ?>

		<!-- ************ -->

	</body>
</html>

