<style>
.info-red{background:red;color:white;text-align:center}
#divTblTgl{width:90%;margin:auto}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Tanggal Merah</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="#"> Kehadiran</a></li>
			<li class="active">Pengaturan Tanggal Merah</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-info">
			<a href="<?= site_url("set_hari/edit_tgl")?>?tgl=0" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  data-title="Tambah Tanggal Merah"  data-remote="false" data-toggle="modal" data-target="#modalBox" >
				<i class="fa fa-plus"></i>Tambah Hari Baru
			</a>
			<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="box-body">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="year">Pencarian</label>
						<div class="col-sm-4">
							<select style='display:none' id="month" name="bulan" class="form-control input-sm inp-bulan" ></select>
							<select id="date_type" name="type" class="form-control input-sm" >
								<option>Silahkan pilih</option>
								<option value='date'>Tanggal</option>
							</select>
							<select style='display:none' id="year" name="tahun" class="form-control input-sm inp-tahun"  placeholder="Tahun"></select>
						</div>
						<div class="col-sm-3">
							<div class="input-group input-group-sm date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input id='date_start' name='date_start' value='<?=date("Y-m-d");?>'  class="form-control input-sm tgl"  />
							</div>
							
						</div>
						<div class="col-sm-3">
							<div class="input-group input-group-sm date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input id='date_end' name='date_end' value='<?=date("Y-m-d",strtotime("+1 month"));?>'  class="form-control input-sm tgl"  />
							</div>
							
						</div>
					</div>
				</div>
				<div class='box-footer'>
					<!--button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Reset</button-->
					<button id='showTanggalMerah' type='button' class='btn btn-social btn-flat btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Lihat</button>
				</div>
			</form>
		</div>
		<div class="box box-info">
			<div id='showTanggal' style='padding:30px;text-align:middle'></div>
			<div id='divTblTgl'>
			<table id='tblTgl' width='90%' class="table table-bordered table-striped dataTable table-hover">
				<thead>
					<tr>
						<th width='10%'>NO</th>
						<th width='15%'>Aksi</th>
						<th width='20%'>Tanggal</th>
						<th>Keterangan</th>
					</tr>
				</thead>
				<tbody>
					<tr style='display:none'>
						<td>1</td>
						<td><a><i class="fa fa-edit"></i></a></a></td>
						<td><?=date("Y-m-d");?></td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
			</div>
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
	tableDT = $('#tblTgl').DataTable(
	{
		"language": {
            url: '/assets/bootstrap/js/dataTables.indonesian.lang'
        },
		"dom": '<"top"l>rt<"bottom"p>i<"clear">',
		"columnDefs": [
			{
				"render": function ( data, type, row ) {
	 
					button='<a href="<?= site_url("set_hari/edit_tgl")?>?tgl='+row[2]+'"'
					+' title="Ubah Data" data-remote="false" data-toggle="modal" '
					+'data-target="#modalBox" data-title="Ubah Tanggal Merah" class="btn bg-orange '
					+'btn-flat btn-sm"><i class="fa fa-edit"></i></a>';
					return  button;

				},
				"targets": 1
			} 
		],
		"columns":[
		{orderable:false,searchable:false,defaultContent:"-"},
		{orderable:false,searchable:false,defaultContent:"-"},
		{orderable:false,searchable:false,defaultContent:"-"},
		{orderable:false,searchable:false,defaultContent:"-"},
		],
		"order": [[ 2, "asc" ]],
		"lengthMenu": [[5,10, 25, 50, 60], [5,10, 25, 50, 60]],
		"processing": true,
        "serverSide": true,
        "ajax": {
			url:"<?=site_url('set_hari/api');?>",
			type:"POST",
			data: function (d) {
				type 	 = $('#date_type').val();
				dateStart = $('#date_start').val();
				dateEnd = $('#date_end').val();
				d.type  	=type;
				d.dateStart =dateStart;
				d.dateEnd   =dateEnd;
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
		console.log('Redrawing table, searching for', $(this).val());
		tableDT.draw();
	  });
  
} );


</script>