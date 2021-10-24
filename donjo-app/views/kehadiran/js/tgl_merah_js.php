<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * Script JS untuk Tanggal Merah
 *
 * donjo-app/views/kehadiran/js/tgl_merah_js.php
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
<?php 
/*
function showCalender()
{
	$("#showTanggal").empty();
	urlApi="<?=site_url('set_hari/api');?>";
	var $form = $("#validasi");
	var serializedData = {
		bln:$('.inp-bulan').val(),
		tahun:$('.inp-tahun').val(),
		action:"show"
	};
	request = $.ajax({
		url: urlApi,
		type: "post",
		data: serializedData
	});

	request.done(function (response, textStatus, jqXHR){ 
		$("#showTanggal").empty().html(response.html);
	});
 
	request.fail(function (jqXHR, textStatus, errorThrown){ 
		console.error(
			"The following error occurred: "+
			textStatus, errorThrown
		);
	});
}
*/
?>
	
function hari_edit(tgl)
{
	var $form = $("#frm_tgl");
	
	var $inputs = $form.find("input, select, button, textarea");

    // Serialize the data in the form
    var serializedData = $form.serialize();
	urlApi="<?=site_url('set_hari/api');?>";
	
	request = $.ajax({
		url: urlApi,
		type: "post",
		data: serializedData
	});
		/*status*/
	request.done(function (response, textStatus, jqXHR){ 
		 tableDT.draw();
		 $("#modalBox").modal('hide');
	});
 
	request.fail(function (jqXHR, textStatus, errorThrown){ 
		console.error(
			"The following error occurred: "+
			textStatus, errorThrown
		);
	});
	
}


$(function() {
	//$("#validasi").hide();
	
	tableDT = $('#tblTgl').DataTable(
	{
		"language": {
            url: '/assets/bootstrap/js/dataTables.indonesian.lang'
        },
		//"dom": '<"top"l>rt<"bottom"p>i<"clear">', 
		"dom": '<"top"fl>t<"bottom"ip>',
		"columnDefs": [
			{
				"render": function ( data, type, row ) {
	 
					button='<a href="<?= site_url("set_hari/edit_tgl")?>?tgl='+row[1]+'"'
						+' title="Ubah Data" data-remote="false" data-toggle="modal" '
						+'data-target="#modalBox" data-title="Ubah Tanggal Merah" class="btn bg-orange '
						+'btn-flat btn-sm"><i class="fa fa-edit"></i></a>';
					button=button+'<a href="#" onclick="hapusTanggal(\''
						+row[1]+'\')" class="btn bg-maroon btn-flat btn-sm btn-hapus" title="Hapus" '
						+'data-toggle="modal" data-target="#confirm-delete"><i class="fa '
						+'fa-trash-o"></i></a>';
					return  button;

				},
				"targets": 1
			} 
		],
		"columns":[
		{orderable:false,searchable:false,defaultContent:"-"},
		{orderable:false,searchable:false,defaultContent:"-"},
		{orderable:true,searchable:false,defaultContent:"-"},
		{orderable:false,searchable:false,defaultContent:"-"},
		],
		"order": [[ 2, "asc" ]],
		"lengthMenu": [[ 10, 25, 50, 60,5], [10, 25, 50, 60,5]],
		"processing": true,
        "serverSide": true,
        "ajax": {
			url:"<?=site_url('set_hari/api');?>",
			type:"POST",
			data: function (d) {
<?php /*
				type 	 = $('#date_type').val();
				dateStart = $('#date_start').val();
				dateEnd = $('#date_end').val();
				d.type  	=type;
				d.dateStart =dateStart;
				d.dateEnd   =dateEnd;
*/
?>
				d.search.value = '<?=date("Y");?>';
				d.action ='datatables';

			}
		}
    }
	);
	
	$('.tgl').datetimepicker(
	{
		format: 'YYYY-MM-DD',
		useCurrent: false,
		locale:'id'
	});

	$( ".inp-tahun" ).each(function( index ) {
		for(y=2021;y<=<?=date("Y",strtotime("+5 year"));?>;y++)
		{
			txt="<option>"+y+"</option>";
			$(this).append(txt);
		} 
	});
	
	$( ".inp-bulan" ).each(function( index ) {
		const bln = ["Silakan memilih bulan","Januari", "Februari","Maret","April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober","November", "Desember"];
		for (month=1; month < bln.length; month++) {
			txt='<option value="'+month+'">' + bln[month] + '</option>';
		  $(this).append(txt);
		}

	});

	$("#showTanggalMerah").click(function(){
		/*showCalender();*/
		tableDT.draw();
	});
	
	$('#updateDt').on('click', function () {
		tableDT.draw();
	});

  
} );

function hapusTanggal(tgl)
{
	d = new Date(tgl);
	tgl2 = d.getDate()+"/"+(d.getMonth()+1)+"/"+d.getFullYear();
	if(confirm("Apakah Anda Akan menghapus Tanggal "+tgl2))
	{
		serializedData = {tgl_merah:tgl,status:-1,action:"update_tgl"}
		urlApi="<?=site_url('set_hari/api');?>";
		
		request = $.ajax({
			url: urlApi,
			type: "post",
			data: serializedData
		});
			/*status*/
		request.done(function (response, textStatus, jqXHR){ 
			 tableDT.draw();
			 $("#modalBox").modal('hide');
		});
	 
		request.fail(function (jqXHR, textStatus, errorThrown){ 
			console.error(
				"The following error occurred: "+
				textStatus, errorThrown
			);
		});		
	}
	
		
	return ;
 
}
</script>