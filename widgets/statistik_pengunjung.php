<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
$ip = $_SERVER['REMOTE_ADDR']."{}";
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

function get_client_ip()
{
	$ipaddress = '';
	if (isset($_SERVER['HTTP_CLIENT_IP']))
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_X_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_FORWARDED']))
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if(isset($_SERVER['REMOTE_ADDR']))
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'IP tidak dikenali';
	return $ipaddress;
}

function browser_user()
{
	$browser = _userAgent();
	return $browser['name'];
}

function _userAgent()
{
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$bname = 'Unknown';
	$platform = 'Unknown';
	$version = "";
	$os_array = array(
		'/windows nt 10.0/i'		=> 'Windows 10',
		'/windows nt 6.2/i'			=> 'Windows 8',
		'/windows nt 6.1/i'			=> 'Windows 7',
		'/windows nt 6.0/i'			=> 'Windows Vista',
		'/windows nt 5.2/i'			=> 'Windows Server 2003/XP x64',
		'/windows nt 5.1/i'			=> 'Windows XP',
		'/windows xp/i'					=> 'Windows XP',
		'/windows nt 5.0/i'			=> 'Windows 2000',
		'/windows me/i'					=> 'Windows ME',
		'/win98/i'							=> 'Windows 98',
		'/win95/i'							=> 'Windows 95',
		'/win16/i'							=> 'Windows 3.11',
		'/macintosh|mac os x/i' => 'Mac OS X',
		'/mac_powerpc/i'				=> 'Mac OS 9',
		'/linux/i'							=> 'Linux',
		'/ubuntu/i'							=> 'Ubuntu',
		'/iphone/i'							=> 'iPhone',
		'/ipod/i'								=> 'iPod',
		'/ipad/i'								=> 'iPad',
		'/android/i'						=> 'Android',
		'/blackberry/i'					=> 'BlackBerry',
		'/webos/i'							=> 'Mobile'
	);

	foreach ($os_array as $regex => $value)
	{
		if (preg_match($regex, $u_agent))
		{
			$platform = $value;
			break;
		}
	}

	if (preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
	{
		$bname = 'Internet Explorer';
		$ub = "MSIE";
	}
	elseif (preg_match('/Firefox/i',$u_agent))
	{
		$bname = 'Mozilla Firefox';
		$ub = "Firefox";
	}
	elseif(preg_match('/Chrome/i',$u_agent))
	{
		$bname = 'Google Chrome';
		$ub = "Chrome";
	}
	elseif (preg_match('/Safari/i',$u_agent))
	{
		$bname = 'Apple Safari';
		$ub = "Safari";
	}
	elseif (preg_match('/Opera/i',$u_agent))
	{
		$bname = 'Opera';
		$ub = "Opera";

	}
	elseif (preg_match('/Netscape/i',$u_agent))
	{
		$bname = 'Netscape';
		$ub = "Netscape";
	}

	$known = array('Version', $ub, 'other');
	$pattern = '#(?<browser>' . join('|', $known) .')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

	if (! preg_match_all($pattern, $u_agent, $matches))
	{
		// we have no matching number just continue
	}

	$i = count($matches['browser']);
	if ($i != 1)
	{
		if (strripos($u_agent,"Version") < strripos($u_agent,$ub))
		{
			$version= $matches['version'][0];
		}
		else
		{
			$version= $matches['version'][1];
		}
	}
	else
	{
		$version= $matches['version'][0];
	}

	$version = ( $version == null || $version == "" ) ? "?" : $version;

	return array(
		'userAgent'	=> $u_agent,
		'name'			=> $bname,
		'version'		=> $version,
		'platform'	=> $platform,
		'pattern'		=> $pattern
	);
}

function os_user()
{
	$OS = _userAgent();
	return $OS['platform'];
}
?>
<div class="archive_style_1">
	<div class="single_bottom_rightbar">
		<h2 class="box-title"><i class="fa fa-bar-chart-o"></i> Statistik Pengunjung</h2>
		<div class="data-case-container">
			<ul class="ants-right-headline">
				<li class="info-case">
					<table style="width: 100%;" cellpadding="0" cellspacing="0" class="table table-striped table-inverse counter" >
						<tr>
							<td class="description">Hari ini</td><td class="dot">:</td><td class="case"><?= number_format($today) ?></td>
						</tr>
						<tr>
							<td class="description">Kemarin</td><td class="dot">:</td><td class="case"><?= number_format($yesterday) ?></td>
						</tr>
						<tr>
							<td class="description">Total Pengunjung</td><td class="dot">:</td><td class="case"><?= number_format($total) ?></td>
						</tr>
						<tr>
							<td class="description">Sistem Operasi</td><td class="dot">:</td><td class="case"><?= os_user() ?></td>
						</tr>
						<tr>
							<td class="description">IP Address</td><td class="dot">:</td><td class="case"><?= get_client_ip() ?></td>
						</tr>
						<tr>
							<td class="description">Browser</td><td class="dot">:</td><td class="case"><?= browser_user() ?></td>
						</tr>
					</table>
				</li>
			</ul>
		</div>
	</div>
</div>
