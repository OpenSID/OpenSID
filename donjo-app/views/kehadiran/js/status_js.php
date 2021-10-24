<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Script JS untuk melakukan proses kehadiran setelah login 
 *
 * donjo-app/views/kehadiran/js/status_js.php
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
?>
<script>
	var stat=0;
<?php
if ($hadir['waktu_masuk'] == NULL)
{?>
	function cekHadir()
	{
		inp=$("#stat_hadir").is(':checked');
		if (inp == true)
		{
			$("#form_hadir").submit();
		}
		else
		{
			alert("Mohon menekan lingkaran di atas <b>ke kanan </b>untuk kehadiran");
		}
		
	}	
	
<?php
}
elseif ($hadir['waktu_masuk'] != NULL && $hadir['waktu_keluar'] == NULL)
{
?>
	function cekHadir()
	{
		inp = $("#stat_hadir").is(':checked');
		if (inp != true)
		{
			$("#form_hadir").submit();
		}
		else
		{
			alert("Mohon menekan lingkaran di atas <b>ke kiri</b> untuk keluar");
		}
		
	}
	
<?php
} 
else
{
?>
	alert("Maaf, Anda sudah Mengisi Kehadiran Keluar. Pada <?=date('H:i:s',strtotime($hadir['waktu_keluar']));?>");
	window.location.href = '<?=base_url("kehadiran/masuk");?>';
<?php
}
?>
var loginPage = "<?=site_url('kehadiran/masuk').'?form='. $this->session->userdata('login_type'); ?>";
setInterval(function(){  window.location.href = loginPage; }, 20000);
</script>