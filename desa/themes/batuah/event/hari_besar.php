<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $file = __DIR__ . '/../event/moment.json'; ?>
<?php if(is_file($file)) : ?>
<?php $json = file_get_contents($file); ?>
<?php $array = json_decode($json, true); ?>
<?php if($array['hitungmundur']) : ?>

	<?php shuffle($array['countdown']); foreach($array['countdown'] as $index => $hm) : ?>
	<?php $gambar = base_url($this->theme_folder.'/'.$this->theme .'/moment/image/' . $hm['gambar']) ?>
	
		<div id="countdown" class="box-default margin-topbottom-6 margin-leftright-12 bggrey1 bordergrey1">
		<div class="countdown-panel flexleft">
			<div class="countdown-head bordergrey1 flexcenter">
			<svg viewBox="0 0 24 24"><path d="M20 2V4L4 8V6H2V18H4V16L6 16.5V18.5C6 20.4 7.6 22 9.5 22S13 20.4 13 18.5V18.3L20 20V22H22V2H20M11 18.5C11 19.3 10.3 20 9.5 20S8 19.3 8 18.5V17L11 17.8V18.5Z" /></svg>
			</div>
			<div>
			<h3 class="color-2"><b><?= $hm['title'] ?></b></h3><p><?= $hm['deskripsi'] ?></p>
			</div>

			<div class="countdown-col color-white flexleft">
				<div class="box-date bgbiru flexcenter"><div><span id="days"></span>Hari</div></div>
				<div class="box-date bgorange flexcenter"><div><span id="hours"></span>Jam</div></div>
				<div class="box-date bgtoska flexcenter"><div><span id="minutes"></span>Menit</div></div>
				<div class="box-date bgpink flexcenter"><div><span id="seconds"></span>Detik</div></div>
			</div>

		</div>
		</div>
	
	<?php endforeach; ?>

	<script>
		<?php shuffle($array['countdown']); foreach($array['countdown'] as $index => $hm) : ?>
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
			dayMonth = "<?= $hm['bulan'] ?>/<?= $hm['tanggal'] ?>/",
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
		<?php endforeach; ?>
	</script>
<?php endif ?>

<?php endif ?>

