<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $shalat_aktif = theme_config('shalat_aktif', ''); ?>

<?php if (theme_config('shalat_aktif') == 'true'): ?>
	<script>
		$(document).ready(function() {
			if ($("#jadwal-shalat").length) {
				const a = "https://api.myquran.com/v2/",
					s = `sholat/kota/${KODE_KOTA}`,
					l = `sholat/jadwal/${KODE_KOTA}/${TANGGAL}`;
				try {
					$.ajax({
						url: a + s,
						type: "get",
						dataType: "json",
						crossDomain: !0,
						success: function(a) {
							$("[data-name=kota]").html(a.data.lokasi).removeClass("shimmer line-short")
						},
						error: function(a) {
							$(".line-short").html('<span class="small"><i class="fa fa-exclamation-triangle pr-1"></i> Gagal memuat</span>'), $(".line-short").removeClass("shimmer line-short")
						}
					}), $.ajax({
						url: a + l,
						type: "get",
						dataType: "json",
						crossDomain: !0,
						success: function(a) {
							$(".shimmer").removeClass("shimmer"), $("[data-name=tanggal]").html(`<span>${a.data.jadwal.tanggal}</span>`), $("[data-name=imsak]").html(`<span>${a.data.jadwal.imsak}</span>`), $("[data-name=subuh]").html(`<span>${a.data.jadwal.subuh}</span>`), $("[data-name=terbit]").html(`<span>${a.data.jadwal.terbit}</span>`), $("[data-name=dhuha]").html(`<span>${a.data.jadwal.dhuha}</span>`), $("[data-name=dzuhur]").html(`<span>${a.data.jadwal.dzuhur}</span>`), $("[data-name=ashar]").html(`<span>${a.data.jadwal.ashar}</span>`), $("[data-name=maghrib]").html(`<span>${a.data.jadwal.maghrib}</span>`), $("[data-name=isya]").html(`<span>${a.data.jadwal.isya}</span>`)
						},
						error: function(a) {
							$(".box-shalat").html('<span class="small"><i class="fa fa-exclamation-triangle pr-1"></i> Gagal memuat</span>'), $(".box-shalat").removeClass("shimmer")
						}
					})
				} catch (a) {
					console.log(a)
				}
			}
		});
		$(document).ready(function() {
			if ($("#jadwal-shalat2").length) {
				const b = "https://api.myquran.com/v2/",
					t = `sholat/kota/kode/${KODE_KOTA}`,
					m = `sholat/jadwal/${KODE_KOTA}/${TANGGAL}`;
				try {
					$.ajax({
						url: b + m,
						type: "get",
						dataType: "json",
						crossDomain: !0,
						success: function(b) {
							$(".shimmer").removeClass("shimmer"), $("[data-name=tanggal2]").html(`<span>${b.data.jadwal.tanggal}</span>`), $("[data-name=imsak2]").html(`<span>${b.data.jadwal.imsak}</span>`), $("[data-name=subuh2]").html(`<span>${b.data.jadwal.subuh}</span>`), $("[data-name=terbit2]").html(`<span>${b.data.jadwal.terbit}</span>`), $("[data-name=dhuha2]").html(`<span>${b.data.jadwal.dhuha}</span>`), $("[data-name=dzuhur2]").html(`<span>${b.data.jadwal.dzuhur}</span>`), $("[data-name=ashar2]").html(`<span>${b.data.jadwal.ashar}</span>`), $("[data-name=maghrib2]").html(`<span>${b.data.jadwal.maghrib}</span>`), $("[data-name=isya2]").html(`<span>${b.data.jadwal.isya}</span>`)
						},
						error: function(b) {
							$(".box-shalat").html('<span class="small"><i class="fa fa-exclamation-triangle pr-1"></i> Gagal memuat</span>'), $(".box-shalat").removeClass("shimmer")
						}
					})
				} catch (b) {
					console.log(b)
				}
			}
		});
	</script>

	<?php if (config_item('kode_kota')) : ?>
		<script>
			const KODE_KOTA = "<?= config_item('kode_kota') ?>";
		</script>
	<?php elseif (theme_config('kode_kota', true)) : ?>
		<script>
			const KODE_KOTA = "<?= theme_config('kode_kota', true) ?>";
		</script>
	<?php endif; ?>

	<script>
		const TANGGAL = "<?= date('Y-m-d') ?>";
		const BESOK = "<?= date("Y-m-d", mktime(0, 0, 0, date("n"), date("j") + 1, date("Y"))) ?>";
	</script>

	<div class="col-default margin-top-10">
		<div class="jadwal-shalat bgcolor-soft1 bordergrey" id="jadwal-shalat">
			<div class="rowsame margin-minlr-5">
				<div class="jadwalshalat-judul">
					<div class="mesjid">
						<svg viewBox="0 0 791.000000 929.000000">
							<defs>
								<linearGradient id="Gradient1" gradientTransform="rotate(90)">
									<stop offset="0%" stop-color="transparent" />
									<stop offset="100%" stop-color="var(--clr1)" />
								</linearGradient>
								<linearGradient id="Gradient2" gradientTransform="rotate(90)">
									<stop offset="0%" stop-color="var(--clr2)" />
									<stop offset="40%" stop-color="var(--clr1)" />
								</linearGradient>
							</defs>
							<g transform="translate(0.000000,929.000000) scale(0.100000,-0.100000)">
								<path fill="url(#Gradient1)" d="M3854 9261 c-299 -105 -800 -331 -1264 -568 -323 -166 -601 -338 -1040 -644 -47 -32 -128 -94 -180 -138 -105 -87 -396 -355 -402 -370 -1 -6 -34 -43 -73 -83 -127 -132 -213 -251 -329 -452 -91 -157 -196 -410 -196 -471 0 -12 -32 -15 -182 -17 l-183 -3 -3 -3258 -2 -3257 40 0 40 0 0 3220 0 3220 174 0 c158 0 175 2 180 18 57 199 82 264 162 425 144 289 387 581 739 885 256 223 687 515 1082 735 217 120 797 404 1028 503 189 80 486 194 508 194 43 0 548 -208 832 -343 690 -328 1108 -573 1580 -926 219 -163 518 -447 680 -646 191 -235 348 -539 415 -802 l11 -43 174 0 175 0 0 -3220 0 -3220 40 0 40 0 0 3260 0 3260 -180 0 c-136 0 -182 3 -184 13 -76 257 -188 483 -357 721 -158 221 -574 620 -836 802 -251 174 -559 375 -703 458 -187 110 -753 393 -1030 516 -295 131 -624 260 -662 260 -7 0 -49 -13 -94 -29z" />
								<path fill="url(#Gradient2)" d="M3795 8536 c-633 -263 -1226 -574 -1670 -874 -630 -426 -1009 -835 -1153 -1244 -52 -146 -65 -240 -60 -425 l5 -163 -163 0 -164 0 1 -2167 c0 -1982 1 -2166 16 -2145 21 30 124 111 196 155 78 46 87 67 88 191 1 149 15 162 22 21 7 -151 17 -171 104 -221 95 -56 205 -162 232 -224 l22 -50 66 53 c37 28 95 78 130 110 l62 58 3 159 3 158 -32 11 c-41 15 -90 72 -98 114 -10 52 10 111 50 146 43 37 68 41 35 6 -17 -18 -25 -40 -28 -77 -5 -69 24 -114 90 -142 43 -18 49 -18 93 -4 46 15 46 15 28 -4 -23 -25 -66 -48 -92 -48 -19 0 -20 -5 -14 -157 l5 -158 37 -39 c34 -37 227 -196 237 -196 2 0 -3 15 -13 33 -13 24 -18 55 -18 122 0 142 35 191 264 373 80 64 167 137 192 162 55 55 59 75 59 291 l0 149 -37 15 c-144 60 -164 261 -34 338 40 23 49 21 23 -5 -72 -78 -47 -220 48 -268 47 -24 119 -26 161 -4 29 15 30 15 20 -4 -14 -26 -66 -58 -108 -67 l-33 -7 0 -132 c0 -73 3 -161 7 -196 6 -58 10 -66 55 -111 27 -27 86 -79 130 -115 l82 -66 21 79 c42 153 81 229 192 371 125 161 307 322 480 426 87 52 290 147 401 187 72 26 91 37 86 49 -4 8 -11 34 -17 57 -13 55 10 120 59 162 34 31 35 31 18 63 -13 26 -14 38 -5 70 7 21 23 45 36 54 24 16 25 21 25 120 0 80 -3 104 -13 104 -17 0 -47 32 -47 49 0 6 16 21 35 32 l35 21 0 89 c0 69 -3 89 -14 89 -7 0 -20 7 -27 16 -12 14 -10 19 14 38 l27 22 0 142 0 142 -27 6 c-54 13 -166 78 -214 125 -134 131 -186 327 -134 508 25 85 100 197 167 248 77 58 88 62 46 15 -90 -101 -130 -200 -130 -327 0 -348 353 -568 671 -418 68 32 168 129 205 198 25 48 26 48 20 15 -10 -59 -61 -155 -111 -211 -98 -108 -231 -169 -371 -169 l-75 0 6 -107 c4 -58 7 -123 7 -143 0 -29 5 -39 20 -43 11 -3 20 -12 20 -21 0 -9 -9 -21 -20 -28 -18 -11 -20 -24 -20 -104 0 -89 1 -92 25 -98 13 -3 30 -16 36 -28 10 -18 8 -24 -11 -40 -12 -10 -28 -18 -36 -18 -11 0 -14 -21 -14 -96 0 -96 0 -97 35 -135 37 -41 44 -78 24 -122 -10 -22 -7 -29 29 -65 53 -53 66 -122 37 -208 -6 -18 4 -24 97 -58 307 -112 555 -269 750 -475 183 -192 266 -332 318 -539 l11 -43 61 48 c34 26 94 80 134 118 l73 70 3 194 3 194 -27 8 c-90 24 -148 126 -126 221 11 51 48 102 89 124 l30 15 -25 -38 c-32 -50 -40 -92 -26 -142 29 -107 140 -159 248 -116 l37 15 -37 -35 c-22 -20 -57 -40 -84 -47 l-47 -13 7 -80 c3 -44 6 -128 6 -188 l0 -107 43 -47 c23 -25 116 -105 207 -178 176 -140 228 -200 255 -290 20 -64 18 -108 -5 -175 -10 -30 -18 -56 -16 -57 5 -5 215 168 244 202 l32 36 0 153 0 153 -41 18 c-47 21 -89 84 -89 133 0 56 35 118 82 143 15 8 15 4 -3 -26 -31 -52 -23 -134 16 -175 45 -47 130 -61 173 -27 8 6 12 6 12 -1 0 -16 -54 -51 -89 -58 -30 -6 -31 -8 -31 -59 0 -28 3 -101 7 -160 6 -108 6 -108 43 -145 20 -20 76 -69 125 -108 l88 -71 24 51 c30 65 127 159 225 218 101 60 108 74 108 215 0 65 4 116 10 120 6 4 10 -38 10 -119 0 -76 4 -126 10 -126 5 0 12 -10 15 -23 4 -14 28 -34 66 -55 33 -18 84 -53 114 -78 l55 -44 0 2140 0 2140 -163 0 -164 0 5 160 c7 250 -24 389 -138 609 -238 460 -788 932 -1635 1406 -177 99 -562 292 -770 385 -164 74 -493 211 -504 209 -3 0 -73 -28 -156 -63z" />
							</g>
						</svg>
					</div>
					<div class="jadwal-title">
						<div class="flexleft">
							<div>
								<h2>Jadwal Imsak, Shalat & Berbuka</h2>
								<h3 class="color-1"><b>Wilayah <?= ucwords($this->setting->sebutan_kabupaten_singkat . " " . $desa['nama_kabupaten']) ?></b></h3>
							</div>
						</div>
						<div class="shalat-bottom flexleft" style="align-self: end;"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pngfile/shalat.png") ?>" /><span id="tanggalwaktu"></span></div>
					</div>
					<script>
						var tw = new Date();
						if (tw.getTimezoneOffset() == 0)(a = tw.getTime() + (7 * 60 * 60 * 1000))
						else(a = tw.getTime());
						tw.setTime(a);
						var tahun = tw.getFullYear();
						var hari = tw.getDay();
						var bulan = tw.getMonth();
						var tanggal = tw.getDate();
						var hariarray = new Array("Minggu,", "Senin,", "Selasa,", "Rabu,", "Kamis,", "Jum'at,", "Sabtu,");
						var bulanarray = new Array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "Nopember", "Desember");
						document.getElementById("tanggalwaktu").innerHTML = hariarray[hari] + " " + tanggal + " " + bulanarray[bulan] + " " + tahun;
					</script>
				</div>
				<div class="jadwalshalat-isi">
					<div class="rowsame margin-minlr-5">
						<div class="jadwal-box2 bgcolor-soft1 bordercolor-1 flexcenter">
							<div class="jadwal-box-cover bgwhite-trans1"></div>
							<div class="mosque"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/svgfile/mosque.svg") ?>" /></div>
							<div style="position:relative;z-index:1;">
								<h3 class="color-1">Imsak</h3>
								<span data-name="imsak"></span>
							</div>
						</div>
						<div class="jadwal-box2 bgcolor-soft1 bordercolor-1 flexcenter">
							<div class="jadwal-box-cover bgwhite-trans1"></div>
							<div class="mosque"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/svgfile/mosque.svg") ?>" /></div>
							<div style="position:relative;z-index:1;">
								<h3 class="color-1">Subuh</h3>
								<span data-name="subuh"></span>
							</div>
						</div>
						<div class="jadwal-box2 bgcolor-soft1 bordercolor-1 flexcenter">
							<div class="jadwal-box-cover bgwhite-trans1"></div>
							<div class="mosque"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/svgfile/mosque.svg") ?>" /></div>
							<div style="position:relative;z-index:1;">
								<h3 class="color-1">Dzuhur</h3>
								<span data-name="dzuhur"></span>
							</div>
						</div>
						<div class="jadwal-box2 bgcolor-soft1 bordercolor-1 flexcenter">
							<div class="jadwal-box-cover bgwhite-trans1"></div>
							<div class="mosque"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/svgfile/mosque.svg") ?>" /></div>
							<div style="position:relative;z-index:1;">
								<h3 class="color-1">Ashar</h3>
								<span data-name="ashar"></span>
							</div>
						</div>
						<div class="jadwal-box2 bgcolor-soft1 bordercolor-1 flexcenter">
							<div class="jadwal-box-cover bgwhite-trans1"></div>
							<div class="mosque"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/svgfile/mosque.svg") ?>" /></div>
							<div style="position:relative;z-index:1;">
								<h3 class="color-1">Magrib & Berbuka</h3>
								<span data-name="maghrib"></span>
							</div>
						</div>
						<div class="jadwal-box2 bgcolor-soft1 bordercolor-1 flexcenter">
							<div class="jadwal-box-cover bgwhite-trans1"></div>
							<div class="mosque"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/svgfile/mosque.svg") ?>" /></div>
							<div style="position:relative;z-index:1;">
								<h3 class="color-1">Isya</h3>
								<span data-name="isya"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
