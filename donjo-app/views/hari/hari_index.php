<style>
.info-red{background:red;color:white;text-align:center}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Setting Tanggal Merah</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Setting Tanggal Merah</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
		 								
			<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-3 control-label" for="year">Tahun dan Bulan</label>
						<div class="col-sm-4">
							<select id="month" name="bulan" class="form-control input-sm inp-bulan" ></select>
						</div>
						<div class="col-sm-4">
							<select id="year" name="tahun" class="form-control input-sm inp-tahun"  placeholder="Tahun"></select>
						</div>
					</div>
				</div>
				<div class='box-footer'>
					<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Reset</button>
					<button id='showTanggalMerah' type='button' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Lihat</button>
				</div>
			</form>
		</div>
		<div class="box box-info">
			<div id='showTanggal' style='padding:30px;text-align:middle'></div>
		</div>
	</section>
</div>


<!-- Untuk menampilkan modal bootstrap umum -->
<div class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4 class='modal-title' id='myModalLabel'> Pengaturan Pengguna</h4>
			</div>
			<div class="fetched-data"></div>
		</div>
	</div>
</div>

<!-- Untuk menampilkan pengaturan -->

<script>
$(function() {
	$( ".inp-tahun" ).each(function( index ) {
		for(y=2021;y<=<?=date("Y",strtotime("+1 year"));?>;y++)
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
		showCalender();
	});
	
});

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
		 showCalender();
		 $("#modalBox").modal('hide');
	});
 
	request.fail(function (jqXHR, textStatus, errorThrown){ 
		console.error(
			"The following error occurred: "+
			textStatus, errorThrown
		);
	});
	
}

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
	/*status*/
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
</script>