@push('styles')
<style type="text/css">


</style>
@endpush	
	
<div class="header trans-h">
<div class="header-inner trans-h">
	<div class="margin-head flex-left">
		<a href="{{ site_url() }}">
			<div class="logo flex-left">
				<img class="trans-h" src="{{ gambar_desa($desa['logo']) }}"/>
				<div>
					<h1 class="trans-h" >{{ ucwords(setting('sebutan_desa')) }} {{ ucwords($desa['nama_desa']) }}</h1>
					<h2 style="margin:5px 0 0;">{{ ucwords($desa['nama_kecamatan']) }}, {{ ucwords($desa['nama_kabupaten']) }}</h2>
				</div>
			</div>
		</a>
		<div class="header-right flex-right desk-v">
		<div class="datetime flex-right desk-v">
			<div>
				<div id="date"></div>
				<div id="times"></div>
			</div>
		</div>
		<a href="{{ site_url('siteman') }}"><div class="head-login flex-center desk-v">Admin</div></a>
		<div class="search flex-center desk-v" data-toggle="modal" data-target="#search">
			<svg viewBox="0 0 24 24"><path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" /></svg>
		</div>
		
		</div>
	</div>
</div>
<div class="header-menu trans-h desk-v">
		<div class="margin-head">
		@include('theme::partials.menu_head')
		</div>
	</div>
<div class="mobmenu flex-center" onclick="menuOpen()">
			<svg viewBox="0 0 24 24"><path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" /></svg>
		</div>	
</div>

<div class="modal center fade" id="search" role="dialog" aria-labelledby="search" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="search-modal flex-center">
		<form class="flex-center" method="get" action="{{ site_url() }}">
						<input type="text" name="cari" maxlength="50" class="form-control" value="{{ html_escape($cari) }}" placeholder="Cari Artikel">
						<button type="submit" class="btn btn-primary" style="margin:0 5px;"><i class="fa fa-search" aria-hidden="true"></i></button>
			</form>
			<button class="btn btn-warning" data-dismiss="modal" aria-label="Close"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
  </div>
</div>

<div id="openmenu" class="menupanel">
	<div class="menupanel-inner">
		<div class="colscroll" style="padding-bottom:50px;">
			<div class="mobilemenu">
				@include('theme::partials.menu_head')
			</div>
		</div>
		<a href="javascript:void(0)" onclick="menuClose()">
		<div class="close-menu flex-center">
			<svg viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>
		</div>
		</a>
	</div>
</div>

<script>
	function menuOpen() {
	  document.getElementById("openmenu").style.width = "100%";
	}
	function menuClose() {
	  document.getElementById("openmenu").style.width = "0";
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
	document.getElementById("date").innerHTML = hariarray[hari]+" "+tanggal+" "+bulanarray[bulan]+" "+tahun;
</script>
<script>
	function animation(span) {
		span.className = "turn";
		setTimeout(function () {
		span.className = ""
		}, 700);
	}
	function thistime() {
	setInterval(function () {
		var waktu = new Date();
		var thistime   = document.getElementById('times');
		var hours = waktu.getHours();
		var minutes = waktu.getMinutes();
		var seconds = waktu.getSeconds();
		if (waktu.getHours() < 10) {
			hours = '0' + waktu.getHours();
		}
		if (waktu.getMinutes() < 10) {
			minutes = '0' + waktu.getMinutes();
		}
		if (waktu.getSeconds() < 10) {
			seconds = '0' + waktu.getSeconds();
		}
		thistime.innerHTML  = '<span class="jammenit">' + hours + ':</span>' 
                     + '<span class="jammenit">' + minutes + ':</span>'
                     + '<span class="jammenit">' + seconds +'</span>';

		var spans      = thistime.getElementsByTagName('span');
		animation(spans[2]);
		if (seconds == 0) animation(spans[1]);
		if (minutes == 0 && seconds == 0) animation(spans[0]);
	}, 1000);
	}
thistime();
</script>
