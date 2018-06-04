$(document).ready(function(){
	//CheckBox All Selected
	checkAll();
	//Display Modal Box
	modalBox();
	//Confirm Delete Modal
	$('#confirm-delete').on('show.bs.modal', function(e) {
		$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	}); 
	//Delay Alert
	setTimeout(function() {
		$('.alert').fadeIn('slow');
	}, 500);
	setTimeout(function(){
		$('.alert').fadeOut('slow');
	}, 2000);
	// Select2 dengan fitur pencarian
	$('.select2').select2();
	//File Upload
	$('#file_browser').click(function(e){
		e.preventDefault();
		$('#file').click();
	});
	$('#file').change(function(){
		$('#file_path').val($(this).val());
	});
	$('#file_path').click(function(){
		$('#file_browser').click();
	});

	$('#file_browser2').click(function(e){
		e.preventDefault();
		$('#file2').click();
	});
	$('#file2').change(function(){
		$('#file_path2').val($(this).val());
	});
	$('#file_path2').click(function(){
		$('#file_browser2').click();
	});
	
	//Fortmat Tanggal
	$(".datemask").inputmask("dd/mm/yyyy", {"placeholder": "00/00/0000"});	
	$('#datemask').datepicker({autoclose: true}); 	

	//Fortmat Tabel
    $('#tabel1').DataTable();
    $('#tabel2').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
	  'scrollX'		: true
    });
	 $('#tabel3').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    :true,
      'info'        : true,
      'autoWidth'   : false,
	  'scrollX'	: true
    });

	//color picker with addon
    $('.my-colorpicker2').colorpicker();
	
	//Timepicker
    $('.timepicker').timepicker({
		showInputs: false,
		showMeridian: false
    });


});

function checkAll() {
	$("#checkall").click(function () {
		if ($(".table #checkall").is(':checked')) {
			$(".table input[type=checkbox]").each(function () {
				$(this).prop("checked", true);
			});

		} else {
			$(".table input[type=checkbox]").each(function () {
				$(this).prop("checked", false);
			});
		}
	});
	$("[data-toggle=tooltip]").tooltip();
}	

function deleteAllBox(idForm, action) {
	
	$('#confirm-delete').modal('show');
	$('#ok-delete').click(function () {
		$('#' + idForm).attr('action', action);
        $('#' + idForm).submit();	
	});
	return false;        
}

function modalBox() {	
	$('#modalBox').on('show.bs.modal', function(e) {	
		var link = $(e.relatedTarget);
		$('.modal-header #myModalLabel').html(link.attr('data-title'));
		$(this).find('.fetched-data').load(link.attr('href'));
	
	});
	return false;        
}


function formAction(idForm,action){
	$('#'+idForm).attr('action',action);
	$('#'+idForm).submit();
}

function notification(type,message){
	if( type =='') {return};
	$('#maincontent').prepend(''
		+'<div id="notification" class="alert alert-'+type+' alert-dismissible">'
		+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
		+ message+''											
		+'</div>'
		+''
	);										
}

function cari_nik(){
	$('#cari_nik').change(function(){	
		$('#'+'main').submit();
	});

	$('#cari_nik_suami').change(function(){	
		$('#'+'main').submit();
	});

	$('#cari_nik_istri').change(function(){	
		$('#'+'main').submit();
	});
}

