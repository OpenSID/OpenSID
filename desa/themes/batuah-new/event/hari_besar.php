<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $countdown_aktif = theme_config('countdown_aktif', ''); ?>
<?php $tanggal = theme_config('tanggal', ''); ?>
<?php $bulan = theme_config('bulan', ''); ?>
<?php $tahun = theme_config('tahun', ''); ?>
<?php $title = theme_config('title', ''); ?>
<?php $desktitle = theme_config('desktitle', ''); ?>

<?php if (theme_config('countdown_aktif') == 'true'): ?>
	
		<div id="countdown" class="box-default bggrey1 bordergrey margin-top-10" style="border-radius:5px;">
		<div class="countdown-panel flexleft">
			<div class="countdown-head bordergrey flexcenter">
			<svg viewBox="0 0 24 24"><path d="M20 2V4L4 8V6H2V18H4V16L6 16.5V18.5C6 20.4 7.6 22 9.5 22S13 20.4 13 18.5V18.3L20 20V22H22V2H20M11 18.5C11 19.3 10.3 20 9.5 20S8 19.3 8 18.5V17L11 17.8V18.5Z" /></svg>
			</div>
			<div>
			<h3 class="color-2"><b><?=$title?></b></h3><p><?=$desktitle?></p>
			</div>

			<div class="countdown-col color-white flexleft">
				<div class="box-date bgbiru flexcenter"><div><span id="days"></span>Hari</div></div>
				<div class="box-date bgorange flexcenter"><div><span id="hours"></span>Jam</div></div>
				<div class="box-date bgtoska flexcenter"><div><span id="minutes"></span>Menit</div></div>
				<div class="box-date bgpink flexcenter"><div><span id="seconds"></span>Detik</div></div>
			</div>

		</div>
		</div>

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
			dayMonth = "<?=$bulan?>/<?=$tanggal?>/",
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
<?php endif ?>


