<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="headertop flexleft">
	<div class="flexcenter mb-menu" onclick="menuOpen()">
		<svg viewBox="0 0 24 24"><path d="M12 16C13.1 16 14 16.9 14 18S13.1 20 12 20 10 19.1 10 18 10.9 16 12 16M12 10C13.1 10 14 10.9 14 12S13.1 14 12 14 10 13.1 10 12 10.9 10 12 10M12 4C13.1 4 14 4.9 14 6S13.1 8 12 8 10 7.1 10 6 10.9 4 12 4M6 16C7.1 16 8 16.9 8 18S7.1 20 6 20 4 19.1 4 18 4.9 16 6 16M6 10C7.1 10 8 10.9 8 12S7.1 14 6 14 4 13.1 4 12 4.9 10 6 10M6 4C7.1 4 8 4.9 8 6S7.1 8 6 8 4 7.1 4 6 4.9 4 6 4M18 16C19.1 16 20 16.9 20 18S19.1 20 18 20 16 19.1 16 18 16.9 16 18 16M18 10C19.1 10 20 10.9 20 12S19.1 14 18 14 16 13.1 16 12 16.9 10 18 10M18 4C19.1 4 20 4.9 20 6S19.1 8 18 8 16 7.1 16 6 16.9 4 18 4Z" /></svg>
	</div>
	<div class="topdate flexleft">
		<div class="clock">
			<div class="wrap">
				<span class="hour"></span><span class="minute"></span><span class="second"></span><span class="dot"></span>
			</div>
		</div>
		<div class="desktop-only">
			<div id="topdate"></div>
		</div>
	</div>
	<div class="headertopright flexleft">
		<?php $this->load->view("$folder_themes/plus/header_right"); ?>
		<div class="desktop-only flexcenter" data-toggle="modal" data-target="#visitor">
		<div class="itemtop bordergrey flexcenter tip-bottom" data-toggle="tooltip" data-original-title="Statistik Pengunjung" onclick="visitoropen()">
			<svg viewBox="0 0 24 24"><path d="M3,21H6V18H3M8,21H11V14H8M13,21H16V9H13M18,21H21V3H18V21Z"/></svg>
		</div>
		</div>
		<a class="desktop-only flexcenter" href="<?= site_url('siteman') ?>">
		<div class="itemtop bordergrey flexcenter tip-bottom" data-toggle="tooltip" data-original-title="Login Administrator">
			<svg viewBox="0 0 24 24"><path d="M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V10A2,2 0 0,1 6,8H15V6A3,3 0 0,0 12,3A3,3 0 0,0 9,6H7A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,17A2,2 0 0,0 14,15A2,2 0 0,0 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17Z"/></svg>
		</div>
		</a>
	</div>
</div>


<div class="bigmodal">
<div class="modal right fade" id="visitor" role="dialog" aria-labelledby="visitor" aria-hidden="true" data-backdrop="false">
	<div class="modal-dialog" role="document">
	<div class="modal-absolute bggrad-color2 bordergrey1">
		<div class="modalhead bgcolor-1 flexcenter"><h1>Statistik Pengunjung</h1></div>
		<div class="inner-modal">
		<div class="padding-10">
			<?php $this->load->view("$folder_themes/widgets/statistik_pengunjung"); ?>
		</div>	
		</div>
		<div class="modalfoot bgcolor-1 bordergrey1 flexcenter" data-dismiss="modal" aria-label="Close">
			<div class="close-btn bgcolor-1"></div>
		</div>	
	</div>
	</div>
</div>
</div>

<div class="modalcenter">
	<div class="modal fade" id="searching" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
		<div class="modal-dialog" style="background:rgba(0,0,0,0.8);">
			<div class="modal-absolute">
				<div class="searchfull flexcenter">
				<div>
				<div style="position:relative">
				<form method=get action="<?= site_url(); ?>">
					<input type="text" name="cari" maxlength="50" class="form-control bgcolor-1" value="<?= $cari ?>" placeholder="Cari Artikel">
					<button type="submit" class="to-search bgcolor-3 flexcenter">
						<svg x="0px" y="0px" viewBox="0 0 56.966 56.966">
						<path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23 s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92 c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17 s-17-7.626-17-17S14.61,6,23.984,6z"/>
						</svg>
					</button>
				</form>
				</div>
				<div class="flexcenter" data-dismiss="modal" aria-label="Close" style="margin-top:15px;">
					<div class="batal color-1">Batal</div><div class="close-btn bgcolor-1"></div>
				</div>	
				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modalcenter">
	<div class="modal fade" id="multicolor" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
		<div class="modal-dialog" style="background:rgba(0,0,0,0.8);">
			<div class="modal-absolute">
				<div class="change-color">
				<?php $this->load->view("$folder_themes/plus/change_color"); ?>
				<div class="change-color-close bgcolor-1 flexcenter" data-dismiss="modal" aria-label="Close"><svg viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="custom-modal1">
<div class="modal left fade bgmodalcolor1" id="identitas" role="dialog" aria-labelledby="identitas" aria-hidden="true" data-backdrop="false">
	<div class="modal-dialog bgwhite" role="document">
	<div class="modal-absolute bggrey1 bordergrey1">
		<div class="modalhead bgcolor-1 flexcenter"><h1>Identitas <?= ucwords($this->setting->sebutan_desa); ?></h1></div>
		<div class="inner-modal">
		<div class="padding-10">
		<?php $this->load->view("$folder_themes/plus/identitas"); ?>
		</div>	
		</div>
		<div class="modalfoot bgwhite bordergrey1 flexcenter" data-dismiss="modal" aria-label="Close">
			<div class="close-btn bgcolor-1"></div>
		</div>	
	</div>
	</div>
</div>
</div>

<div class="custom-modal1">
<div class="modal left fade bgmodalcolor1" id="lapor" role="dialog" aria-labelledby="lapor" aria-hidden="true" data-backdrop="false">
	<div class="modal-dialog bgwhite" role="document">
	<div class="modal-absolute bggrey1 bordergrey1">
		<div class="modalhead bgcolor-1 flexcenter"><h1>Kontak & Pengaduan</h1></div>
		<div class="inner-modal">
		<div class="scroll-area" id="scrollstyle">
			<div class="padding-10">
				<?php $this->load->view("$folder_themes/plus/lapor"); ?>
			</div>	
		</div>	
		</div>
		<div class="modalfoot bgwhite bordergrey1 flexcenter" data-dismiss="modal" aria-label="Close">
			<div class="close-btn bgcolor-1"></div>
		</div>	
	</div>
	</div>
</div>
</div>

<div id="openmenu" class="menupanel">
	<div class="mb-close flexcenter" onclick="menuClose()">
		<div class="mb-close-inner flexcenter">
		<svg viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>
		</div>
	</div>
	<div class="scroll-area" id="scrollstyle">
		<div class="panel-padding">
			<div class="topmobile">
				<div class="mobilegrid">
					<div class="topmobile-item">
						<a href="<?= site_url('siteman') ?>">
						<div class="topmobile-item-inner bgbiru flexleft">
							<svg viewBox="0 0 24 24"><path d="M6 8C6 5.79 7.79 4 10 4S14 5.79 14 8 12.21 12 10 12 6 10.21 6 8M12 18.2C12 17.24 12.5 16.34 13.2 15.74V15.5C13.2 15.11 13.27 14.74 13.38 14.38C12.35 14.14 11.21 14 10 14C5.58 14 2 15.79 2 18V20H12V18.2M22 18.3V21.8C22 22.4 21.4 23 20.7 23H15.2C14.6 23 14 22.4 14 21.7V18.2C14 17.6 14.6 17 15.2 17V15.5C15.2 14.1 16.6 13 18 13C19.4 13 20.8 14.1 20.8 15.5V17C21.4 17 22 17.6 22 18.3M19.5 15.5C19.5 14.7 18.8 14.2 18 14.2C17.2 14.2 16.5 14.7 16.5 15.5V17H19.5V15.5Z" /></svg>
							<div>
							<p>Login</p><p>Admin</p>
							</div>
						</div>
						</a>
					</div>
					<div class="topmobile-item">
						<a href="<?= site_url('layanan-mandiri/masuk') ?>">
						<div class="topmobile-item-inner bgorange flexleft">
							<svg viewBox="0 0 24 24"><path d="M21 10V9L15 3H5C3.89 3 3 3.89 3 5V19C3 20.11 3.9 21 5 21H11V19.13L19.39 10.74C19.83 10.3 20.39 10.06 21 10M14 4.5L19.5 10H14V4.5M22.85 14.19L21.87 15.17L19.83 13.13L20.81 12.15C21 11.95 21.33 11.95 21.53 12.15L22.85 13.47C23.05 13.67 23.05 14 22.85 14.19M19.13 13.83L21.17 15.87L15.04 22H13V19.96L19.13 13.83Z" /></svg>
							<div>
							<p>Layanan</p><p>Mandiri</p>
							</div>
						</div>
						</a>
					</div>
				</div>
			</div>
			<?php $this->load->view("$folder_themes/partials/menu_head"); ?>
		</div>
	</div>
</div>

<div id="bload">
<div class="bload-container bgcolor-1 flexcenter">
	<?php $this->load->view("$folder_themes/plus/preloader"); ?>
</div>
</div>

<script>
$(document).ready(function(){
$("#bload").delay(300).fadeOut();
})
</script>

<script>
	var tw = new Date();
	if (tw.getTimezoneOffset() == 0) (a=tw.getTime() + ( 7 *60*60*1000))
	else (a=tw.getTime());
	tw.setTime(a);
	var tahun= tw.getFullYear ();
	var hari= tw.getDay ();
	var bulan= tw.getMonth ();
	var tanggal= tw.getDate ();
	var hariarray=new Array("Minggu,","Senin,","Selasa,","Rabu,","Kamis,","Jum'at,","Sabtu,");
	var bulanarray=new Array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
	document.getElementById("topdate").innerHTML = hariarray[hari]+" "+tanggal+" "+bulanarray[bulan]+" "+tahun;
</script>

<script>
var inc = 1000;

clock();

function clock() {
  const date = new Date();

  const hours = ((date.getHours() + 11) % 12 + 1);
  const minutes = date.getMinutes();
  const seconds = date.getSeconds();
  
  const hour = hours * 30;
  const minute = minutes * 6;
  const second = seconds * 6;
  
  document.querySelector('.hour').style.transform = `rotate(${hour}deg)`
  document.querySelector('.minute').style.transform = `rotate(${minute}deg)`
  document.querySelector('.second').style.transform = `rotate(${second}deg)`
}

setInterval(clock, inc);
</script>

<script>
	function menuOpen() {
	  document.getElementById("openmenu").style.width = "100%";
	}
	function menuClose() {
	  document.getElementById("openmenu").style.width = "0";
	}	
</script>