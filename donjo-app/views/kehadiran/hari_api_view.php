<?php <?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View Hari Merah
 *
 * donjo-app/views/kehadiran/hari_api_view.php
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

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