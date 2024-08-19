<?= view('admin.layouts.partials.footer') ?>

				<?= view('admin.layouts.partials.control_sidebar') ?>
				</div>
				</div>

				<!-- jQuery 3 -->
				<script src="<?= asset('bootstrap/js/jquery.min.js') ?>"></script>

				<?= view('admin.layouts.components.token') ?>

				<!-- Jquery UI -->
				<script src="<?= asset('bootstrap/js/jquery-ui.min.js') ?>"></script>
				<script src="<?= asset('bootstrap/js/jquery.ui.autocomplete.scroll.min.js') ?>"></script>

				<script src="<?= asset('bootstrap/js/moment.min.js') ?>"></script>
				<script src="<?= asset('bootstrap/js/moment-timezone.js') ?>"></script>
				<script src="<?= asset('bootstrap/js/moment-timezone-with-data.js') ?>"></script>
				<!-- Bootstrap 3.3.7 -->
				<script src="<?= asset('bootstrap/js/bootstrap.min.js') ?>"></script>
				<!-- Select2 -->
				<script src="<?= asset('bootstrap/js/select2.full.min.js') ?>"></script>
				<!-- DataTables -->
				<script src="<?= asset('bootstrap/js/jquery.dataTables.min.js') ?>"></script>
				<script src="<?= asset('bootstrap/js/dataTables.bootstrap.min.js') ?>"></script>
				<!-- bootstrap color picker -->
				<script src="<?= asset('bootstrap/js/bootstrap-colorpicker.min.js') ?>"></script>
				<!-- bootstrap Date time picker -->
				<script src="<?= asset('bootstrap/js/bootstrap-datetimepicker.min.js') ?>"></script>
				<script src="<?= asset('bootstrap/js/id.js') ?>"></script>
				<!-- bootstrap Date picker -->
				<script src="<?= asset('bootstrap/js/bootstrap-datepicker.min.js') ?>"></script>
				<script src="<?= asset('bootstrap/js/bootstrap-datepicker.id.min.js') ?>"></script>
				<!-- Bootstrap WYSIHTML5 -->
				<script src="<?= asset('bootstrap/js/bootstrap3-wysihtml5.all.min.js') ?>"></script>
				<!-- Slimscroll -->
				<script src="<?= asset('bootstrap/js/jquery.slimscroll.min.js') ?>"></script>
				<!-- FastClick -->
				<script src="<?= asset('bootstrap/js/fastclick.js') ?>"></script>
				<!-- AdminLTE App -->
				<script src="<?= asset('js/adminlte.min.js') ?>"></script>
				<script src="<?= asset('js/validasi.js') ?>"></script>
				<script src="<?= asset('js/jquery.validate.min.js') ?>"></script>
				<script src="<?= asset('js/localization/messages_id.js') ?>"></script>
				<!-- Numeral js -->
				<script src="<?= asset('js/numeral.min.js') ?>"></script>
				<!-- Script-->
				<script src="<?= asset('js/script.js') ?>"></script>
				<script src="<?= asset('js/admin.js') ?>"></script>
				<script src="<?= asset('js/custom-select2.js') ?>"></script>
				<script src="<?= asset('js/custom-datetimepicker.js') ?>"></script>

				<!-- numeral js -->
				<script src="<?= asset('js/numeraljs/numeral.min.js') ?>"></script>

				<!-- Sweet Alert -->
				<script src="<?= asset('js/sweetalert2/sweetalert2.all.min.js') ?>"></script>
				<script src="<?= asset('js/Leaflet.fullscreen.min.js') ?>"></script>
				<script type="text/javascript">
					numeral.register("locale", "id-id", {
						delimiters: {
							thousands: ".",
							decimal: ","
						},
						abbreviations: {
							thousand: 'k',
							million: 'm',
							billion: 'b',
							trillion: 't'
						},
						currency: {
							symbol: "Rp." //The currency for UAE is called the Dirham
						}
					});
					numeral.locale('id-id');
					numeral.defaultFormat('0,0.00');
					console.log(numeral.locale())

					// pengaturan peta
					var pengaturan_peta = {
						maxZoom: <?= setting('max_zoom_peta') ?>,
						minZoom: <?= setting('min_zoom_peta') ?>,
						fullscreenControl: {
							position: 'topright' // Menentukan posisi tombol fullscreen
						}
					};

				</script>

				<!-- Token Field -->
				<?php if ($this->controller == 'bumindes_kader') : ?>
					<script src="<?= asset('bootstrap/js/bootstrap-tokenfield.min.js') ?>"></script>
				<?php endif ?>

				<?php if (config_item('demo_mode')) : ?>
					<script src="<?= asset('js/demo.js') ?>"></script>
				<?php endif ?>

				<?php if (! setting('inspect_element')) : ?>
					<script src="<?= asset('js/disabled.min.js') ?>"></script>
				<?php endif ?>

				<!-- set timezone -->
				<script>
					$.extend($.fn.datetimepicker.defaults, {
						timeZone: `<?= date_default_timezone_get() ?>`
					});

					moment.tz.setDefault(`<?= date_default_timezone_get() ?>`);
				</script>

				<!-- NOTIFICATION-->
				<script type="text/javascript">
					$('document').ready(function() {
						var koneksi = '<?= ! cek_koneksi_internet() && setting('notifikasi_koneksi') ?>';

						if (koneksi) {
							cek_koneksi();
						}

						var success = `<?= addslashes((string) $this->session->success) ?>`;
						var message = `<?= addslashes((string) $this->session->error_msg) ?>`;

						if (success == 1) {
							notify = 'success';
							notify_msg = 'Data berhasil disimpan';
						} else if (success == -1) {
							notify = 'error';
							notify_msg = 'Data gagal disimpan. <br>' + message;
						} else if (success == -2) {
							notify = 'error';
							notify_msg = 'Data gagal disimpan. <br>Nama id sudah ada!';
						} else if (success == -3) {
							notify = 'error';
							notify_msg = 'Data gagal disimpan. <br>Nama id sudah ada!';
						} else if (success == 4) {
							notify = 'success';
							notify_msg = 'Data berhasil dihapus';
						} else if (success == -4) {
							notify = 'error';
							notify_msg = 'Data gagal dihapus';
						} else if (success == 5) {
							notify = 'success';
							notify_msg = 'Data berhasil diunggah';
						} else if (success == 6) {
							notify = 'success';
							notify_msg = 'Silahkan Cek Pesan di Email Anda';
						} else if (success == -99) {
							notify = 'error';
							notify_msg = message;
						} else {
							notify = success;
							notify_msg = message;
						}
						notification(notify, notify_msg);

						// Sidebar
						if (typeof(Storage) !== 'undefined' && localStorage.getItem('sidebar') === 'false') {
							$("#sidebar_collapse").addClass('sidebar-collapse');
						}

						$('.sidebar-toggle').on('click', function() {
							localStorage.setItem('sidebar', $("#sidebar_collapse").hasClass('sidebar-collapse'));
						});

						//Format Tabel
						$("#tabel1").DataTable();
						$("#tabel2").DataTable({
							paging: false,
							lengthChange: false,
							searching: false,
							ordering: false,
							info: false,
							autoWidth: false,
							scrollX: true,
						});
						$("#tabel3").DataTable({
							paging: true,
							lengthChange: true,
							searching: true,
							ordering: true,
							info: true,
							autoWidth: false,
							scrollX: true,
						});

						// formatting datatable Program Bantuan
						$("#table-program").DataTable({
							paging: false,
							info: false,
							searching: false,
							columnDefs: [{
									targets: [0, 1, 3, 4, 5, 6, 7],
									orderable: false,
								},
								{
									targets: [4],
									className: "text-center",
								},
								{
									targets: [7],
									render: function(data, type, full, meta) {
										if (data == 0) {
											return "Tidak Aktif";
										}
										return "Aktif";
									},
								},
							],
						});

						// Penggunaan datatable di inventaris
						if (!$.fn.DataTable.isDataTable("#tabel4")) {
							var t = $("#tabel4").DataTable({
								responsive: true,
								processing: true,
								autoWidth: false,
								lengthMenu: [
									[10, 25, 50, 100, -1],
									[10, 25, 50, 100, "Semua"]
								],
								pageLength: 10,
								language: {
									url: "<?= asset('bootstrap/js/dataTables.indonesian.lang') ?>",
								},
							});
							t.on("order.dt search.dt", function() {
								t.column(0, {
										search: "applied",
										order: "applied"
									})
									.nodes()
									.each(function(cell, i) {
										cell.innerHTML = i + 1;
									});
							}).draw();
						}
					});
				</script>
				<?php session_error_clear(); ?>

				<?php if (isset($perbaharui_langganan) && $this->controller != 'pengguna' && ! config_item('demo_mode')) : ?>
					<!-- cek status langganan -->
					<script type="text/javascript">
						var controller = '<?= $this->controller ?>';
						$.ajax({
								url: `<?= config_item('server_layanan') ?>/api/v1/pelanggan/pemesanan`,
								headers: {
									"Authorization": `Bearer <?= setting('layanan_opendesa_token') ?>`,
									"X-Requested-With": `XMLHttpRequest`,
								},
								type: 'Post',
							})
							.done(function(response) {
								let data = {
									body: response
								}
								$.ajax({
									url: `${SITE_URL}pelanggan/pemesanan`,
									type: 'Post',
									dataType: 'json',
									data: data,
								}).done(function() {
									if (controller == 'pelanggan') {
										location.reload();
									}
								});
							})
					</script>
				<?php endif ?>
				</body>

				</html>