<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style>
.pilihwarna{position:relative;overflow:hidden;margin:3px 0;border-radius:4px;height:30px;}
.pilihwarna p{font-size:90%;color:#fff;margin:0;padding:0;line-height:1;}
.warna1{background:linear-gradient(to right, #6eab11, #00743e);}
.warna2{background:linear-gradient(to right, #dba518, #cd4f15);}
.warna3{background:linear-gradient(to right, #cc5790, #954fa6);}
.warna4{background:linear-gradient(to right, #92af37, #13717c);}
.warna5{background:linear-gradient(to right, #255ba4, #0099b3);}
.warna6{background:linear-gradient(to right, #d5b226, #313131);}
.warna7{background:linear-gradient(to right, #e43535, #a10000);}
.warna8{background:linear-gradient(to right, #0da9ac, #942963);}
</style>
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

<div class="headertop bggrad-color1 bordergrey1 flexleft">
	<div class="topleft1 flexleft">
		<div class="topleft-inner bgcolor-3 flexcenter" onclick="setVmenuMode(true)" id="vmenuBtn">
		<img src="<?= base_url("$this->theme_folder/$this->theme/images/svgfile/grid.svg") ?>"/>
		</div>
	</div>
	<a href="#" data-toggle="modal" data-target="#menum">
	<div class="topleft2 flexleft">
		<div class="topleft-inner bgcolor-3 flexcenter">
		<img src="<?= base_url("$this->theme_folder/$this->theme/images/svgfile/grid.svg") ?>"/>
		</div>
	</div>
	</a>
	<div class="topdate flexleft">
		<div class="clock">
			<div class="wrap">
				<span class="hour"></span><span class="minute"></span><span class="second"></span><span class="dot"></span>
			</div>
		</div>
		<div class="desktop-only">
		<div id="topdate" class="color-white"></div>
		</div>
	</div>
	<div class="headertopright flexleft">
		<?php $this->load->view("$folder_themes/plus/headertop_right"); ?>
		<div class="desktop-only flexcenter" data-toggle="modal" data-target="#visitor">
		<div class="itemtop borderwhite flexcenter tip-bottom" data-toggle="tooltip" data-original-title="Statistik Pengunjung" onclick="visitoropen()">
			<svg viewBox="0 0 24 24"><path d="M3,21H6V18H3M8,21H11V14H8M13,21H16V9H13M18,21H21V3H18V21Z"/></svg>
		</div>
		</div>
		<a class="desktop-only flexcenter" href="<?= site_url('siteman') ?>">
		<div class="itemtop borderwhite flexcenter tip-bottom" data-toggle="tooltip" data-original-title="Login Administrator">
			<svg viewBox="0 0 24 24"><path d="M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V10A2,2 0 0,1 6,8H15V6A3,3 0 0,0 12,3A3,3 0 0,0 9,6H7A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,17A2,2 0 0,0 14,15A2,2 0 0,0 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17Z"/></svg>
		</div>
		</a>
		<div class="flexcenter" data-toggle="modal" data-target="#setting">
		<div class="itemtop borderwhite flexcenter tip-bottom" data-toggle="tooltip" data-original-title="Pengaturan">
			<svg viewBox="0 0 24 24"><path d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.21,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.21,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.67 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z"/></svg>
		</div>
		</div>
	</div>
</div>

<div class="bigmodal">
<div class="modal right fade" id="setting" role="dialog" aria-labelledby="setting" aria-hidden="true" data-backdrop="false">
	<div class="modal-dialog" role="document">
	<div class="modal-absolute bggrad-color2 bordergrey1">
		<div class="modalhead bgcolor-1 flexcenter"><h1>Pengaturan</h1></div>
		<div class="inner-modal pengaturan">
		<div class="withscroll">
		<div class="padding-10">
			<div class="desktop-only">
			<div style="margin-bottom:30px;">
			<h2 class="colorsoft-1 bordersoft">Pilihan tampilan</h2>
			<div class="view1">
			<div class="change-menu flexleft" onclick="setVmenuMode(true)" id="vmenuBtn">
			<svg viewBox="0 0 24 24"><path d="M3 3H21C22.1 3 23 3.89 23 5V17C23 18.1 22.1 19 21 19H16V21H8V19H3C1.9 19 1 18.1 1 17V5C1 3.89 1.89 3 3 3M3 5V17H21V5H3M9 8H15V14H9V8Z"/></svg>
			<p>Tampilan Batuah Alternatif</p>
			</div>
			</div>
			<div class="view2">
			<div class="change-menu flexleft" onclick="setVmenuMode(false)" id="vmenuBtn">
			<svg viewBox="0 0 24 24"><path d="M3 3C1.89 3 1 3.89 1 5V17C1 18.1 1.9 19 3 19H8V21H16V19H21C22.1 19 23 18.1 23 17V5C23 3.89 22.1 3 21 3M3 5H21V17H3M9 8V14H11V8M13 8V14H15V8"/></svg>
			<p>Tampilan Batuah Default</p>
			</div>
			</div>
			</div>
			</div>
			<h2 class="pilwar colorsoft-1 bordersoft" style="margin:0 0 15px !important;">Pilihan Warna</h2>
			<div style="position:relative;overflow:hidden;">
				<div class="colors">
				<a data-val="01" href="javascript:void(0);"><div class="pilihwarna flexcenter warna1"><p>Warna 01</p></div></a>
				</div>
			</div>
			<div style="position:relative;overflow:hidden;">
				<div class="colors">
				<a data-val="02" href="javascript:void(0);"><div class="pilihwarna flexcenter warna2"><p>Warna 02</p></div></a>
				</div>
			</div>
			<div style="position:relative;overflow:hidden;">
				<div class="colors">
				<a data-val="03" href="javascript:void(0);"><div class="pilihwarna flexcenter warna3"><p>Warna 03</p></div></a>
				</div>
			</div>
			<div style="position:relative;overflow:hidden;">
				<div class="colors">
				<a data-val="04" href="javascript:void(0);"><div class="pilihwarna flexcenter warna4"><p>Warna 04</p></div></a>
				</div>
			</div>
			<div style="position:relative;overflow:hidden;">
				<div class="colors">
				<a data-val="05" href="javascript:void(0);"><div class="pilihwarna flexcenter warna5"><p>Warna 05</p></div></a>
				</div>
			</div>
			<div style="position:relative;overflow:hidden;">
				<div class="colors">
				<a data-val="06" href="javascript:void(0);"><div class="pilihwarna flexcenter warna6"><p>Warna 06</p></div></a>
				</div>
			</div>
			<div style="position:relative;overflow:hidden;">
				<div class="colors">
				<a data-val="07" href="javascript:void(0);"><div class="pilihwarna flexcenter warna7"><p>Warna 07</p></div></a>
				</div>
			</div>
			<div style="position:relative;overflow:hidden;">
				<div class="colors">
				<a data-val="08" href="javascript:void(0);"><div class="pilihwarna flexcenter warna8"><p>Warna 08</p></div></a>
				</div>
			</div>
		</div>	
		</div>
		</div>
		<div class="modalfoot bgcolor-1 bordergrey1 flexcenter" data-dismiss="modal" aria-label="Close">
			<div class="close-btn bgcolor-1"></div>
		</div>	
	</div>
	</div>
</div>
</div>

<div class="bigmodal">
<div class="modal right fade" id="visitor" role="dialog" aria-labelledby="visitor" aria-hidden="true" data-backdrop="false">
	<div class="modal-dialog" role="document">
	<div class="modal-absolute bggrad-color2 bordergrey1">
		<div class="modalhead bgcolor-1 flexcenter"><h1>Statistik Pengunjung</h1></div>
		<div class="inner-modal visitor">
		<div class="withscroll">
		<div class="padding-10">
			<?php $this->load->view("$folder_themes/widgets/statistik_pengunjung"); ?>
		</div>	
		</div>
		</div>
		<div class="modalfoot bgcolor-1 bordergrey1 flexcenter" data-dismiss="modal" aria-label="Close">
			<div class="close-btn bgcolor-1"></div>
		</div>	
	</div>
	</div>
</div>
</div>

<div class="formenu bigmodal">
<div class="modal left fade" id="menum" role="dialog" aria-labelledby="menum" aria-hidden="true" data-backdrop="false">
	<div class="modal-dialog" role="document">
	<div class="modal-absolute bggrey1 bordergrey1">
		<div class="modalhead bgcolor-1 flexcenter"><h2><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h2></div>
		<div class="inner-modal">
		<div class="withscroll">
		<div class="padding-10">
			<?php $this->load->view("$folder_themes/plus/menu_mobile"); ?>
		</div>	
		</div>
		</div>
		<div class="modalfoot bgcolor-1 bordergrey1 flexcenter" data-dismiss="modal" aria-label="Close">
			<div class="close-btn bgcolor-3"></div>
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
		<div class="withscroll">
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
<div class="modal left fade bgmodalcolor1" id="aparatur" role="dialog" aria-labelledby="aparatur" aria-hidden="true" data-backdrop="false">
	<div class="modal-dialog bgwhite" role="document">
	<div class="modal-absolute bggrey1 bordergrey1">
		<div class="modalhead bgcolor-1 flexcenter"><h1>Aparatur <?= ucwords($this->setting->sebutan_desa); ?></h1></div>
		<div class="inner-modal">
		<div class="withscroll">
		<?php $this->load->view("$folder_themes/plus/aparatur_pop"); ?>
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
		<div class="withscroll">
		<?php $this->load->view("$folder_themes/plus/lapor"); ?>
		</div>	
		</div>
		<div class="modalfoot bgwhite bordergrey1 flexcenter" data-dismiss="modal" aria-label="Close">
			<div class="close-btn bgcolor-1"></div>
		</div>	
	</div>
	</div>
</div>
</div>


<div class="totop">
	<nav class="main-nav">
	<div class="full-wrapper clearfix">
	<div style="position:relative;">
		<div class="contact-me flexcenter hover-height" onclick="OpenContact()">
			<svg viewBox="0 0 24 24" class="hover-height"><path d="M12,3C17.5,3 22,6.58 22,11C22,15.42 17.5,19 12,19C10.76,19 9.57,18.82 8.47,18.5C5.55,21 2,21 2,21C4.33,18.67 4.7,17.1 4.75,16.5C3.05,15.07 2,13.13 2,11C2,6.58 6.5,3 12,3M11,14V16H13V14H11M11,12H13V6H11V12Z" /></svg>
		</div>
		<div id="contactopen" class="opencontact">
			<div class="opencontact-absolute">
				<div class="opencontact-inner">
					<div class="cs flexleft">
						<p>Ingin berlangganan Tema Batuah?, hubungi salahsatu kontak dibawah...</p>
					</div>
					<a rel="nofollow" href="https://api.whatsapp.com/send?phone=6282269993730&text=Saya ingin bertanya sesuatu" target="blank">
					<div class="callme flexleft" style="margin:10px 0 0;">
						<img src="<?= base_url("$this->theme_folder/$this->theme/images/pngfile/whatsapp.png") ?>"/>
						<p>+62 822-6999-3730</p>
					</div>
					</a>
					<div class="callme flexleft" style="margin:5px 0 0;">
						<img src="<?= base_url("$this->theme_folder/$this->theme/images/pngfile/telegram.png") ?>"/>
						<p>+62 822-6999-3730</p>
					</div>
				</div>
				<a href="javascript:void(0)" onclick="CloseContact()">
				<div class="closecontact flexcenter">
				<svg viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>
				</div>
				</a>
			</div>
		</div>
	</div>
	<div class="toBack flexcenter hover-height" onclick="goBack()">
		<svg viewBox="0 0 24 24" class="hover-height"><path d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"/></svg>
	</div>
	<div id="ScrollToTop" class="flexcenter hover-height">
		<svg viewBox="0 0 96 96" class="hover-height"><path d="M82.6074,62.1072,52.6057,26.1052a6.2028,6.2028,0,0,0-9.2114,0L13.3926,62.1072a5.999,5.999,0,1,0,9.2114,7.6879L48,39.3246,73.396,69.7951a5.999,5.999,0,1,0,9.2114-7.6879Z"/></svg>
	</div>
	</div>
	</nav>
</div>

<script type="text/javascript">
	function goBack() {
	window.history.back();
	}
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
	function setVmenuMode(isVmenu) {
	var vmenuBtn = document.getElementById('vmenuBtn')
	var novmenuBtn = document.getElementById('novmenuBtn')
		if(isVmenu) {
		novmenuBtn.style.display = "block"
		vmenuBtn.style.display = "none"
	} else {
		novmenuBtn.style.display = "none"
		vmenuBtn.style.display = "block"
	}
	document.body.classList.toggle("vmenumode");
	}
</script>
<script>  if (localStorage.getItem('theme') == 'vmenu')
setVmenuMode()    
	function setVmenuMode() 
	{ let emoticon = ''
	let isVmenu = document.body.classList.toggle('vmenumode')
	if (isVmenu) 
	{      
	emoticon = '<div class="topleft-inner flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/images/svgfile/grid2.svg") ?>"/></div>'      
	localStorage.setItem('theme','vmenu')
	} else {      
	emoticon = '<div class="topleft-inner flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/images/svgfile/grid.svg") ?>"/></div>'
	localStorage.removeItem('theme')    }    
	document.getElementById('vmenuBtn').innerHTML = emoticon  }  
</script>

<script>
	function OpenContact() {
	  document.getElementById("contactopen").style.height = "250px";
	}
	function CloseContact() {
	  document.getElementById("contactopen").style.height = "0";
	}  
</script>