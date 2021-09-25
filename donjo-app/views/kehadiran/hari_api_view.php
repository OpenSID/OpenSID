<?php 

$aHari		= [
	1=>'Senin', 
	'Selasa', 
	'Rabu',
	'Kamis', 
	'Jum\'at', 
	'Sabtu',
	'Minggu'
];
$bulanTahun	= date("Y-m-", $tgl1);

?>
<div style='padding:5px;background:beiege'>
<table class="border thick" border=1 align='center'>
	<thead>
		<tr class="border thick">
			<th><?=implode($aHari, '</th> <th>');?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
<?php
	$iHari=0;
	for ($i = 1; $i < $start; $i++)
	{
		echo "<td>&nbsp;</td>";
		$iHari++;
	}

	for ($i = 1; $i <= $last; $i++)
	{
		$tgl = sprintf("%s%02s", $bulanTahun, $i);
		if( in_array($tgl, $merah) ){
			$cl = "class='info-red'";
			
		}
		else
		{
			$cl = "";
		}

		echo "<td $cl> $i&nbsp;";
		echo '<a href="'.site_url('set_hari/edit_tgl?tgl='.$tgl).'" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Tanggal" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a></td>';
		$iHari++;
		if ($iHari % 7 == 0)
		{
			echo "</tr>\n<tr>";
		}
	}

	while($iHari % 7 != 0)
	{
		echo "<td>&nbsp;</td>";
		$iHari++;

	}
?>
		</tr>
	</tbody>
</table>
</div>