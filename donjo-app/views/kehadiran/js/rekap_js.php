<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Script JS untuk Rekap Kehadiran
 *
 * donjo-app/views/kehadiran/js/rekap_js.php
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
var tableDT;

$(function() {
	//$("#validasi").hide();
	
	tableDT = $('#tblRekap').DataTable(
	{
		"language": {
            url: '/assets/bootstrap/js/dataTables.indonesian.lang'
        },
		"dom": '<"top"fl>t<"bottom"ip>',
		/*
		"columnDefs": [
			{
				"render": function ( data, type, row ) {
	 
					button='<a href="<?= site_url("set_hari/edit_tgl")?>?tgl='+row[2]+'"'
						+' title="Ubah Data" data-remote="false" data-toggle="modal" '
						+'data-target="#modalBox" data-title="Info" class="btn bg-orange '
						+'btn-flat btn-sm"><i class="fa fa-edit"></i></a>';
 
					return  button;

				},
				"targets": 5
			} 
		],
		*/
		"columns":[
		{orderable:false, searchable:false,defaultContent:"-"},
		{orderable:true, searchable:false,defaultContent:"-"},
		{orderable:false, searchable:false,defaultContent:"-"},
		{orderable:false, searchable:false,defaultContent:"-"},
		{orderable:false, searchable:false,defaultContent:"-"}
		],
		"order": [[ 1, "asc" ]],
		"lengthMenu": [[ 10, 25, 50, 60,5], [10, 25, 50, 60,5]],
		"processing": true,
        "serverSide": true,
        "ajax": {
			url:"<?=site_url('kehadiran_rekap/api');?>",
			type:"POST",
			data: function (d) {
				types 	 = $('#search_type').val();
				values 	 = $('#search_value').val();
				dateStart = $('#date_start').val();
				dateEnd = $('#date_end').val();
				d.type  	=types;
				d.values  	=values;
				d.dateStart =dateStart;
				d.dateEnd   =dateEnd;
				d.action ='datatables';

			}
		}
    }
	);

  
} );
</script>