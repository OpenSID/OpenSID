<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php

$CI =& get_instance();
$CI->load->library('user_agent');

if ($CI->agent->is_browser())
{
        $browser = $CI->agent->browser().' '.$CI->agent->version();
}
elseif ($CI->agent->is_robot())
{
        $browser = $CI->agent->robot();
}
elseif ($CI->agent->is_mobile())
{
        $browser = $CI->agent->mobile();
}
else
{
        $browser = 'Tidak ditemukan';
}

$ip = $CI->input->ip_address();
$os = $CI->agent->platform();

if(!isset($_SESSION['MemberOnline']))
{
	$cek = $this->db->query("SELECT Tanggal,ipAddress FROM sys_traffic WHERE Tanggal='".date("Y-m-d")."'");
	if($cek->num_rows()==0)
	{
		$up = $this->db->query("INSERT INTO sys_traffic (Tanggal,ipAddress,Jumlah) VALUES ('".date("Y-m-d")."','".$ip."','1')");
		$_SESSION['MemberOnline']=date('Y-m-d H:i:s');
	}
	else
	{
		$res = $cek->result_array();
		$ipaddr = $res['ipAddress'].$ip;
		$up = $this->db->query("UPDATE sys_traffic SET Jumlah=Jumlah + 1,ipAddress='".$ipx."' WHERE Tanggal='".date("Y-m-d")."'");
		$_SESSION['MemberOnline']=date('Y-m-d H:i:s');
	}
}

$rs = $this->db->query('SELECT Jumlah AS Visitor FROM sys_traffic WHERE Tanggal="'.date("Y-m-d").'" LIMIT 1');
if($rs->num_rows()>0)
{
	$visitor = $rs->row(0);
	$today = $visitor->Visitor;
}
else
{
	$today = 0;
}

$strSQL = "SELECT Jumlah AS Visitor FROM sys_traffic WHERE Tanggal=(SELECT DATE_ADD(CURDATE(),INTERVAL -1 DAY) FROM sys_traffic LIMIT 1) LIMIT 1";
$rs = $this->db->query($strSQL);
if($rs->num_rows()>0)
{
	$visitor = $rs->row(0);
	$yesterday = $visitor->Visitor;
}
else
{
	$yesterday = 0;
}

$rs = $this->db->query('SELECT SUM(Jumlah) as Total FROM sys_traffic');
$visitor = $rs->row(0);
$total = $visitor->Total;
		
?>
<div class="archive_style_1">
	<div class="single_bottom_rightbar">
		<h2 class="box-title"><i class="fa fa-bar-chart-o"></i> Statistik Pengunjung</h2>
		<div class="data-case-container">
			<ul class="ants-right-headline">
				<li class="info-case">
					<table style="width: 100%;" cellpadding="0" cellspacing="0" class="table table-striped table-inverse counter" >
						<tr>
							<td class="description">Hari ini</td><td class="dot">:</td><td class="case"><?= ribuan($today) ?></td>
						</tr>
						<tr>
							<td class="description">Kemarin</td><td class="dot">:</td><td class="case"><?= ribuan($yesterday) ?></td>
						</tr>
						<tr>
							<td class="description">Total Pengunjung</td><td class="dot">:</td><td class="case"><?= ribuan($total) ?></td>
						</tr>
						<tr>
							<td class="description">Sistem Operasi</td><td class="dot">:</td><td class="case"><?= $os; ?></td>
						</tr>
						<tr>
							<td class="description">IP Address</td><td class="dot">:</td><td class="case"><?= $ip; ?></td>
						</tr>
						<tr>
							<td class="description">Browser</td><td class="dot">:</td><td class="case"><?= $browser; ?></td>
						</tr>
					</table>
				</li>
			</ul>
		</div>
	</div>
</div>
