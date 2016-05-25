<div class="block">
<div id="s-latest" class="blog widget">
<h1>Agenda</h1>
<ul class="sidebar-latest">
	<?php  foreach ($agenda as $l){?>
	<li><a href="<?php echo site_url("first/artikel/$l[id]")?>"><?php echo $l['judul']?></a></li>
	<?php  }?>
</ul>
</div>
</div>

<div class="block">
<div id="s-latest" class="blog widget">
<a href="<?php echo site_url();?>/first/gallery"><h1>Gallery</h1></a>
<ul class="sidebar-latest">
<?php  foreach($w_gal As $data){?>	
	<a class="group3" href="<?php echo base_url()?>assets/front/gallery/sedang_<?php echo $data['gambar']?>">
	<img src="<?php echo base_url()?>assets/front/gallery/kecil_<?php echo $data['gambar']?>" width="142" height="95">
	</a>
<?php  }?>
</ul>
</div>
</div>

<div class="block">
<a href="<?php echo site_url("first/statistik/1")?>"><div id="s-latest" class="blog widget">
<h1>Statistik Desa</h1>
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
                text: ''
            },
			yAxis: {
						title: {
							text: ''
						}
			},
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
            },
			legend: {
				enabled:false
			},
			plotOptions: {
				series: {
					colorByPoint: true
				},
				column: {
					pointPadding: 0,
					borderWidth: 0
				}
			},
            series: [{
                type: 'column',
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
<script src="<?php echo base_url()?>/assets/highchart/highcharts.js"></script>
<div id="container" style="width: 150px; height: 150px; margin: 0 auto"></div>
</div>
</a>
</div>

<div class="block">
<div id="s-latest" class="blog widget">
<h1>Komentar Terkini</h1>
<ul class="sidebar-latest">
<?php  foreach($komen As $data){?>	
	<li>
	<?php echo $data['komentar']?><br />
	<small>Posting <?php echo tgl_indo2($data['tgl_upload'])?>  oleh : <?php echo $data['owner']?></small>
	<br />
	<br />
	</li>
<?php  }?>
</ul>
</div>
</div>

<div class="block" style="">
<div id="s-latest" class="blog widget">
<h1>Media Sosial</h1>
<ul class="sidebar-latest">
<?php  foreach($sosmed As $data){?>	
	<a href="<?php echo $data['link']?>" target="_blank">
	<img src="<?php echo base_url()?>/assets/front/<?php echo $data['gambar']?>" width="55" height="55">
	</a>
<?php  }?>
</ul>
</div>
</div>

<!--Tag FB------------------------------------------------------------------------------------------>
<?php /* $conn = @fsockopen("www.facebook.com", 80, $errno, $errstr, 30);
    if ($conn)
    {
        fclose($conn);
   		if($widget){
   ?>
   
	<div class="block">
		<div class="fb-like-box" data-href="<?php echo $widget['fb_link'] ?>" data-width="292" data-show-faces="true" data-header="true" data-stream="true" data-show-border="true"></div>
	</div>
 <?php  }}*/?>
 
<!--Tag Twitter------------------------------------------------------------------------------------------>
<!--<?php  /*$conn = @fsockopen("www.twitter.com", 80, $errno, $errstr, 30);
    if ($conn)
    {
        fclose($conn);*/
   ?>
	<div class="block">
		<a class="twitter-timeline"  href="<?php //=$widget['twit_link'] ?>"  data-widget-id="<?php //=$widget['twit_code'] ?>">Tweets by <?php //=$widget['twit_et'] ?></a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</div>
 <?php // }?>-->

<!--Counter pengunjung------------------------------------------------------------------------------------------>
<div class="block">
<?php


$ip = $_SERVER['REMOTE_ADDR']."{}";
if(!isset($_SESSION['MemberOnline'])){
$cek = mysql_query("SELECT Tanggal,ipAddress FROM sys_traffic WHERE Tanggal='".date("Y-m-d")."'");
	if(mysql_num_rows($cek)==0){
		$up = mysql_query("INSERT  INTO sys_traffic (Tanggal,ipAddress,Jumlah) VALUES ('".date("Y-m-d")."','".$ip."','1')");
		$_SESSION['MemberOnline']=date('Y-m-d H:i:s');
	}
	else{
		$res 	= mysql_fetch_array($cek);
		$ipaddr = $res['ipAddress'].$ip;
		$up = mysql_query("UPDATE sys_traffic SET Jumlah=Jumlah + 1,ipAddress='".$ipx."' WHERE Tanggal='".date("Y-m-d")."'");
		$_SESSION['MemberOnline']=date('Y-m-d H:i:s');
	}
}

$today 		= mysql_fetch_array(mysql_query('SELECT Jumlah AS Visitor FROM sys_traffic WHERE Tanggal="'.date("Y-m-d").'" LIMIT 1'));
$yesterday	= mysql_fetch_array(mysql_query('SELECT Jumlah AS Visitor FROM sys_traffic WHERE Tanggal=(SELECT DATE_ADD(CURDATE(),INTERVAL -1 DAY) FROM sys_traffic LIMIT 1) LIMIT 1'));
$total 		= mysql_fetch_array(mysql_query('SELECT SUM(Jumlah) as Total FROM sys_traffic'));
  ?>
  <style type="text/css">
	body,html{
		background-color:#ffffee;
	}
	table.counter{
		border:silver 1px solid;
		border-radius:5px;
		background-color:#000000;
	}
	table.counter tr td{
		font:bold 12px Tahoma,Arial,Helvetica;
		color:#ffffff;
		border-right:silver 1px solid;
		border-bottom:silver 1px solid;
		padding:0 5px 0 5px;
	}
	table.counter tr td img{
		width:15px;
		height:18px;
	}
</style>
<h1>Statistik Pengunjung</h1>
<div id="container" align="center">
<table cellpadding="0" cellspacing="0" class="counter">
<tr>
	<td valign="middle" height="20"> Hari ini  </td>
	
	<td valign="middle">
		<?php echo num_toimage($today['Visitor'],5); ?>
	</td>
</tr>
<tr>
	<td valign="middle" height="20">Kemarin </td>
	
	<td valign="middle">
		<?php echo num_toimage($yesterday['Visitor'],5); ?>
	</td>
</tr>
<tr>
	<td valign="middle" height="20">Jumlah pengunjung</td>
	
	<td valign="middle">
		<?php echo num_toimage($total['Total'],5); ?>
	</td>
</tr>
</table>
</div>
</div>

<div class="block">
<div id="s-latest" class="blog widget">
<h1>Arsip Artikel</h1>
	<p style="padding-left:10px;line-height:16px;">
	<?php  foreach ($arsip as $l){?>
	<a href="<?php echo site_url("first/artikel/$l[id]")?>">- <?php echo $l['judul']?></a></br>
	<?php  }?>
	<a class="uibutton special" href="<?php echo site_url("first/arsip")?>">...Lihat Semua Arsip</a>
	</p>
</div>
</div>

<?php  foreach($w_cos As $data){?>
<div class="block">
<h1><?php echo $data['judul']?></h1>
<div class="isi"><?php echo $data['isi']?></div>
</div>
<?php  }?>

<?php 
function num_toimage($tot,$jumlah){
	$pattern='';
	for($j=0;$j<$jumlah;$j++){
		$pattern .= '0';
	}
	$len     = strlen($tot);
	$length  = strlen($pattern)-$len;
	$start   = substr($pattern,0,$length).substr($tot,0,$len-1);
	$last    = substr($tot,$len-1,1);
	$last_rpc= '<img src="_BASE_URL_/assets/images/counter/animasi/'.$last.'.gif" align="absmiddle" />'; 
	$inc     = str_replace($last,$last_rpc,$last);
	for($i=0;$i<=9;$i++){
		$rpc ='<img src="_BASE_URL_/assets/images/counter/'.$i.'.gif" align="absmiddle"/>';
		$start=str_replace($i,$rpc,$start);
	}
	$num = $start.$inc;
	$num = str_replace('_BASE_URL_',base_url(),$num);
	return $num;
}
?>
