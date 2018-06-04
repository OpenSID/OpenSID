			<!-- /.content-wrapper -->
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
				  	<b>Version</b> <?php echo AmbilVersi()?>
				</div>
				<strong>Aplikasi <a href="https://github.com/OpenSID/OpenSID" target="_blank"> OpenSID</a> Berbasis SID, Dikembangkan oleh <a href="http://www.combine.or.id" target="_blank">Combine.or.id</a>.</strong> 
			</footer>
		</div>

		<!-- jQuery 3 -->
		<script src="<?php echo base_url()?>assets/plugins/jquery/dist/jquery.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?php echo base_url()?>assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- Select2 -->
		<script src="<?php echo base_url()?>assets/plugins/select2/dist/js/select2.full.min.js"></script>
		<!-- DataTables -->
		<script src="<?php echo base_url()?>assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url()?>assets/plugins/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
		<!-- InputMask -->
		<script src="<?php echo base_url()?>assets/plugins/input-mask/jquery.inputmask.js"></script>
		<script src="<?php echo base_url()?>assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
		<script src="<?php echo base_url()?>assets/plugins/input-mask/jquery.inputmask.extensions.js"></script>		
		<script src="<?php echo base_url()?>assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
		<!-- datepicker -->
		<script src="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
		<!-- bootstrap color picker -->
		<script src="<?php echo base_url()?>assets/plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
		<!-- bootstrap time picker -->
		<script src="<?php echo base_url()?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
		<!-- Bootstrap WYSIHTML5 -->
		<script src="<?php echo base_url()?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
		<!-- Slimscroll -->
		<script src="<?php echo base_url()?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>		
		<!-- FastClick -->
		<script src="<?php echo base_url()?>assets/plugins/fastclick/lib/fastclick.js"></script>			
		<!-- AdminLTE App -->
		<script src="<?php echo base_url()?>assets/js/adminlte.min.js"></script>
		<script src="<?php echo base_url()?>assets/js/validasi.js"></script>
		<script src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
		<!-- PACE -->
		<script src="<?php echo base_url()?>assets/plugins/pace/pace.min.js"></script>		
		<!-- Highcharts -->
		<script src="<?php echo base_url()?>assets/js/highcharts/highcharts.js"></script>
		<script src="<?php echo base_url()?>assets/js/highcharts/highcharts-more.js"></script>
		<script src="<?php echo base_url()?>assets/js/highcharts/exporting.js"></script>	
		<script src="<?php echo base_url()?>assets/js/highcharts/export-data.js"></script>
		<!-- Script-->
		<script src="<?php echo base_url()?>assets/js/script.js"></script>
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
		
		<!-- Pengaturan Grafik Chart Data Statistik-->
		<script type="text/javascript">
			$(document).ready(function () {    	
				chart = new Highcharts.Chart({
					chart: {
						renderTo: 'chart',
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false
					},
					title: {
						text: 'Data Statistik Kependudukan'
					},
					subtitle: {
						text: 'Berdasarkan <?php echo $stat?>'
					},
					plotOptions: {
						index: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true
							},
							showInLegend: true
						}
					},
					legend: {
						layout: 'vertical',
						backgroundColor: '#FFFFFF',
						align: 'right',
						verticalAlign: 'top',
						x: -30,
						y: 0,
						floating: true,
						shadow: true,
                        enabled:true
					},
					series: [{
						type: 'pie',
						name: 'Populasi',
						data: [
							<?php  foreach($main as $data){?>
								<?php if($data['nama'] != "TOTAL" and $data['nama'] != "JUMLAH"){?>
									<?php if($data['jumlah'] != "-"){?>
										['<?php echo strtoupper($data['nama'])?>',<?php echo $data['jumlah']?>],
									<?php }?>
								<?php }?>
							<?php }?>
						]
					}]
				});
			});

		</script>

		<!-- Pengaturan Grafik Chart Surat Keluar-->
		<script type="text/javascript">
			$(function () {
				var chart;				
				$(document).ready(function () {					
					// Build the chart
					chart = new Highcharts.Chart({
						chart: {
							renderTo: 'container',
							plotBackgroundColor: null,
							plotBorderWidth: null,
							plotShadow: false
						},
						title: {
							text: 'Grafik Surat Keluar'
						},
						tooltip: {
							pointFormat: '{series.name}: <b>{point.percentage}%</b>',
							percentageDecimals: 1
						},
						plotOptions: {
							pie: {
								allowPointSelect: true,
								cursor: 'pointer',
								dataLabels: {
									enabled: false
								},
								showInLegend: true
							}
						},
						series: [{
							type: 'pie',
							name: 'Prosentase',
							data: [
									<?php  foreach($stat as $data){?>
										<?php if($data['jumlah'] != "-"){?>
											['<?php echo $data['nama']?>',<?php echo $data['jumlah']?>],
										<?php }?>
									<?php }?>
							]
						}]
					});
				});
				
			});
		</script>
	</body>
</html>

