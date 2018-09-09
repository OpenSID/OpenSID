$(document).ready(function()
{
	//CheckBox All Selected
	checkAll();

	//Display Modal Box
	modalBox();

	//Display MAP Box
	mapBox();

	//Confirm Delete Modal
	$('#confirm-delete').on('show.bs.modal', function(e) {
		var string = document.getElementById("confirm-delete").innerHTML;
		var hasil = string.replace("fa fa-text-width text-yellow","fa fa-exclamation-triangle text-red");
		document.getElementById("confirm-delete").innerHTML = hasil;

		var string2 = document.getElementById("confirm-delete").innerHTML;
		var hasil2 = string2.replace("Konfirmasi", "&nbspKonfirmasi");
		document.getElementById("confirm-delete").innerHTML = hasil2;
		$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	});

	$('#confirm-status').on('show.bs.modal', function(e) {
		$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	});
	//Delay Alert
	setTimeout(function()
	{
		$('#notification').fadeIn('slow');
	}, 500);
	setTimeout(function()
	{
		$('#notification').fadeOut('slow');
	}, 2000);

	// Select2 dengan fitur pencarian
	$('.select2').select2();
	$('button[type="reset"]').click(function()
	{
		$('.select2').select2('val', 'All');
	});
	//File Upload
	$('#file_browser').click(function(e)
	{
		e.preventDefault();
		$('#file').click();
	});
	$('#file').change(function()
	{
		$('#file_path').val($(this).val());
	});
	$('#file_path').click(function()
	{
		$('#file_browser').click();
	});

	$('#file_browser1').click(function(e)
	{
		e.preventDefault();
		$('#file1').click();
	});
	$('#file1').change(function()
	{
		$('#file_path1').val($(this).val());
	});
	$('#file_path1').click(function()
	{
		$('#file_browser1').click();
	});

	$('#file_browser2').click(function(e)
	{
		e.preventDefault();
		$('#file2').click();
	});
	$('#file2').change(function()
	{
		$('#file_path2').val($(this).val());
	});
	$('#file_path2').click(function()
	{
		$('#file_browser2').click();
	});

	$('#file_browser3').click(function(e)
	{
		e.preventDefault();
		$('#file3').click();
	});
	$('#file3').change(function()
	{
		$('#file_path3').val($(this).val());
	});
	$('#file_path3').click(function()
	{
		$('#file_browser3').click();
	});

	$('#file_browser4').click(function(e)
	{
		e.preventDefault();
		$('#file4').click();
	});
	$('#file4').change(function()
	{
		$('#file_path4').val($(this).val());
	});
	$('#file_path4').click(function()
	{
		$('#file_browser4').click();
	});
	//Fortmat Tanggal dan Jam
	$('.datepicker').datepicker(
	{
		weekStart : 1,
		language:'id',
		format: 'dd-mm-yyyy',
		autoclose: true
	});
	$('#tgl_mulai,#tgl_akhir').datetimepicker({
		locale:'id',
		format: 'DD-MM-YYYY',
		useCurrent: false
	});
	$('#tgl_mulai').datetimepicker().on('dp.change', function (e) {
		var incrementDay = moment(new Date(e.date));
		incrementDay.add(1, 'days');
		$('#tgl_akhir').data('DateTimePicker').minDate(incrementDay);
		$(this).data("DateTimePicker").hide();
	});
	$('#tgl_akhir').datetimepicker().on('dp.change', function (e) {
		var decrementDay = moment(new Date(e.date));
		decrementDay.subtract(1, 'days');
		$('#tgl_mulai').data('DateTimePicker').maxDate(decrementDay);
		 $(this).data("DateTimePicker").hide();
	});

	$('#tgljam_mulai,#tgljam_akhir').datetimepicker({
		locale:'id',
		format: 'DD-MM-YYYY HH:mm',
		useCurrent: false,
		sideBySide:true
	});
	$('#tgljam_mulai').datetimepicker().on('dp.change', function (e) {
		var incrementDay = moment(new Date(e.date));
		incrementDay.add(1, 'days');
		$('#tgljam_akhir').data('DateTimePicker').minDate(incrementDay);

	});
	$('#tgljam_akhir').datetimepicker().on('dp.change', function (e) {
		var decrementDay = moment(new Date(e.date));
		decrementDay.subtract(1, 'days');
		$('#tgljam_mulai').data('DateTimePicker').maxDate(decrementDay);
	});

	$('#tgl_jam').datetimepicker(
	{
		format: 'DD-MM-YYYY HH:mm:ss',
		locale:'id'
	});
	$('#tgl_1').datetimepicker(
	{
		format: 'DD-MM-YYYY',
		locale:'id'
	});
	$('#tgl_2').datetimepicker(
	{
		format: 'DD-MM-YYYY',
		locale:'id'
	});
	$('#tgl_3').datetimepicker(
	{
		format: 'DD-MM-YYYY',
		locale:'id'
	});
	$('#tgl_4').datetimepicker(
	{
		format: 'DD-MM-YYYY',
		locale:'id'
	});
	$('#tgl_5').datetimepicker(
	{
		format: 'DD-MM-YYYY',
		locale:'id'
	});
	$('#tgl_6').datetimepicker(
	{
			format: 'DD-MM-YYYY',
			locale:'id'
	});
	$('#jam_1').datetimepicker(
	{
		format: 'HH:mm:ss',
		locale:'id'
	});
	$('#jam_2').datetimepicker(
	{
		format: 'HH:mm:ss',
		locale:'id'
	});
	$('#jam_3').datetimepicker(
	{
		format: 'HH:mm:ss',
		locale:'id'
	});

	$('#jammenit_1').datetimepicker(
	{
		format: 'HH:mm',
		locale:'id'
	});
	$('#jammenit_2').datetimepicker(
	{
		format: 'HH:mm',
		locale:'id'
	});

	$('#jammenit_3').datetimepicker(
	{
		format: 'HH:mm',
		locale:'id'
	});

	$('[data-rel="popover"]').popover(
	{
		html: true,
		trigger:"hover"
	});
$('[checked="checked"]').parent().addClass('active')
	//Fortmat Tabel
  $('#tabel1').DataTable();
  $('#tabel2').DataTable({
		'paging'      : false,
    'lengthChange': false,
    'searching'   : false,
    'ordering'    : false,
    'info'        : false,
		'autoWidth'   : false,
		'scrollX'			: true
  });
	$('#tabel3').DataTable({
    'paging'      : true,
    'lengthChange': true,
    'searching'   : true,
    'ordering'    : true,
    'info'        : true,
    'autoWidth'   : false,
		'scrollX'			: true
	});

	//color picker with addon
  $('.my-colorpicker2').colorpicker();
	//Text Editor with addon
	$('#min-textarea').wysihtml5();

	if ($('li.treeview.active.menu-open')[0] !== undefined) {

		// preparing var for scroll via query selector
		var activated_menu = $('li.treeview.active.menu-open')[0];
		// autscroll to activated menu/sub menu
		activated_menu.scrollIntoView({behavior: 'smooth'});
	}

	// scroll full menu by scrolling into last sub menu
	$('ul').on('expanded.tree', function(){
		var last_li = $(this)
			.find('.treeview.menu-open ul.treeview-menu li')
			.last().get(0);

		last_li.scrollIntoView({behavior: 'smooth'});
	});

	// scroll when collapsed
	$('ul').on('collapsed.tree', function(){
		$(this)[0]
			.scrollIntoView({behavior: 'smooth'});
	});
});

function checkAll(id = "#checkall")
{
	$(id).click(function ()
	{
		if ($(".table " + id).is(':checked'))
		{
			$(".table input[type=checkbox]").each(function ()
			{
				$(this).prop("checked", true);
			});
		}
		else
		{
			$(".table input[type=checkbox]").each(function ()
			{
				$(this).prop("checked", false);
			});
		}
	});
	$("[data-toggle=tooltip]").tooltip();
}

function deleteAllBox(idForm, action)
{
	$('#confirm-delete').modal('show');
	$('#ok-delete').click(function ()
	{
		$('#' + idForm).attr('action', action);
    $('#' + idForm).submit();
	});
	return false;
}
function aksiBorongan(idForm, action) {
	$('#confirm-status').modal('show');
	$('#ok-status').click(function ()
	{
		$('#' + idForm).attr('action', action);
    $('#' + idForm).submit();
	});
	return false;
}

function modalBox()
{
	$('#modalBox').on('show.bs.modal', function(e)
	{
		var link = $(e.relatedTarget);
		var title = link.data('title');
		var modal = $(this)
		modal.find('.modal-title').text(title)
		$(this).find('.fetched-data').load(link.attr('href'));
	});
	return false;
}

function mapBox()
{
	$('#mapBox').on('show.bs.modal', function(e){
		var link = $(e.relatedTarget);
		$('.modal-header #myModalLabel').html(link.attr('data-title'));
		$(this).find('.fetched-data').load(link.attr('href'));
	});
}
function formAction(idForm, action)
{
	$('#'+idForm).attr('action', action);
	$('#'+idForm).submit();
}

function notification(type, message)
{
	if ( type =='') {return};
	$('#maincontent').prepend(''
		+'<div id="notification" class="alert alert-'+type+' alert-dismissible">'
		+'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
		+ message+''
		+'</div>'
		+''
	);
}

function cari_nik()
{
	$('#cari_nik').change(function()
	{
		$('#'+'main').submit();
	});

	$('#cari_nik_suami').change(function()
	{
		$('#'+'main').submit();
	});

	$('#cari_nik_istri').change(function()
	{
		$('#'+'main').submit();
	});
}

