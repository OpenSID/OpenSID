<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if (config_item('ramadan')): ?>
	<div class="ramadan-countdown">
		<div class="ramadan-countdown-cover bgcolor-1"></div>
		<div id="countdown">
			<div class="countdown-panel flexcenter">
				<div class="flexcenter">
					<div class="countdown-head bordergrey1 flexcenter">
						<img src="<?= base_url("$this->theme_folder/$this->theme/images/ramadhan/ketupat.png") ?>"/>
					</div>
					<div>
						<h3 class="desktop-only" style="color:#dbdbdb;"><b>Hari Raya<br/>Idul Fitri 1444 H</b></h3>
						<h3 class="mobile-only" style="color:#dbdbdb;margin:0 0 10px !important;"><b>Hari Raya Idul Fitri 1444 H</b></h3>
					</div>
				</div>
				<div class="countdown-col color-white flexleft">
					<div class="box-date bgbiru flexcenter"><div><span id="days"></span>Hari</div></div>
					<div class="box-date bgorange flexcenter"><div><span id="hours"></span>Jam</div></div>
					<div class="box-date bgtoska flexcenter"><div><span id="minutes"></span>Menit</div></div>
					<div class="box-date bgpink flexcenter"><div><span id="seconds"></span>Detik</div></div>
				</div>
			</div>
		</div>
	</div>
	<div class="ramadanstyle">
	
	</div>
<?php endif; ?>

<script>
		(function () {
		const second = 1000,
			minute = second * 60,
			hour = minute * 60,
			day = hour * 24;
		let today = new Date(),
			dd = String(today.getDate()).padStart(2, "0"),
			mm = String(today.getMonth() + 1).padStart(2, "0"),
			yyyy = today.getFullYear(),
			nextYear = yyyy + 1,
			dayMonth = "4/22/",
			birthday = dayMonth + yyyy;
			today = mm + "/" + dd + "/" + yyyy;
		if (today > birthday) {
		birthday = dayMonth + nextYear;
		}
		const countDown = new Date(birthday).getTime(),
			x = setInterval(function() {    
		const now = new Date().getTime(),
			distance = countDown - now;
			document.getElementById("days").innerText = Math.floor(distance / (day)),
			document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
			document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
			document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);
		if (distance < 0) {
			document.getElementById("countdown").style.display = "none";
			document.getElementById("content").style.display = "none";
			clearInterval(x);
		}
		}, 0)
		}());
	</script>