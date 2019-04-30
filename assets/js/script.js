
// https://stackoverflow.com/questions/13261970/how-to-get-the-absolute-path-of-the-current-javascript-file-name/13262027#13262027
// Untuk mendapatkan base_url, karena aplikasi bisa terinstall di subfolder
var scripts = document.getElementsByTagName('script');
var last_script = scripts[scripts.length - 1];
var file_ini = last_script.src;
// Harus mengetahui lokasi & nama file script ini
var base_url = file_ini.replace('assets/js/script.js','');

$( window ).on( "load", function() {
	// Scroll ke menu aktif perlu dilakukan di onload sesudah semua loading halaman selesai
	// Tidak bisa di document.ready
	// preparing var for scroll via query selector
	var activated_menu = $('li.treeview.active.menu-open')[0];
	// autscroll to activated menu/sub menu
	if (activated_menu){
		activated_menu.scrollIntoView({behavior: 'smooth'});
	}
});

$(document).ready(function()
{


	//CheckBox All Selected
	checkAll();
  $("input[name='id_cb[]'").click(function(){
  	enableHapusTerpilih();
  });
	enableHapusTerpilih();

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


	$('.select2-nik').select2({
		templateResult: function (penduduk) {
			if (!penduduk.id) {
			  return penduduk.text;
			}
			var _tmpPenduduk = penduduk.text.split('\n');
			var $penduduk = $(
			  '<div>'+_tmpPenduduk[0]+'</div><div>'+_tmpPenduduk[1]+'</div>'
			);
			return $penduduk;
		}
	});
	// Select2 dengan fitur pencarian dan boleh isi sendiri
	$('.select2-tags').select2(
		{
			tags: true
		});
	// Select2 untuk disposisi pada form
	// surat masuk
	$('#disposisi_kepada').select2({
		placeholder: "Pilih tujuan disposisi"
	});
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
		if ($(this).val() == '')
		{
			$('#'+$(this).data('submit')).attr('disabled','disabled');
		}
		else
		{
			$('#'+$(this).data('submit')).removeAttr('disabled');;
		}
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
	$('#tgl_mulai').datetimepicker({
		locale:'id',
		format: 'DD-MM-YYYY',
		useCurrent: false,
		date: moment(new Date())
	});
	$('#tgl_akhir').datetimepicker({
		locale:'id',
		format: 'DD-MM-YYYY',
		useCurrent: false,
		minDate: moment(new Date()).add(-1, 'day'), // Todo: mengapa harus dikurangi -- bug?
		date: moment(new Date()).add(1, 'M')
	});
	$('#tgl_mulai').datetimepicker().on('dp.change', function (e) {
		$('#tgl_akhir').data('DateTimePicker').minDate(moment(new Date(e.date)));
		$(this).data("DateTimePicker").hide();
		var tglAkhir = moment(new Date(e.date));
		tglAkhir.add(1, 'M');
		$('#tgl_akhir').data('DateTimePicker').date(tglAkhir);
	});

	$('#tgljam_mulai').datetimepicker({
		locale:'id',
		format: 'DD-MM-YYYY HH:mm',
		useCurrent: false,
		date: moment(new Date()),
		sideBySide:true
	});
	$('#tgljam_akhir').datetimepicker({
		locale:'id',
		format: 'DD-MM-YYYY HH:mm',
		useCurrent: false,
		minDate: moment(new Date()).add(-1, 'day'), // Todo: mengapa harus dikurangi -- bug?
		date: moment(new Date()).add(1, 'day'),
		sideBySide:true
	});
	$('#tgljam_mulai').datetimepicker().on('dp.change', function (e) {
		$('#tgljam_akhir').data('DateTimePicker').minDate(moment(new Date(e.date)));
		var tglAkhir = moment(new Date(e.date));
		tglAkhir.add(1, 'day');
		$('#tgljam_akhir').data('DateTimePicker').date(tglAkhir);
	});

	$('.tgl_jam').datetimepicker(
	{
		format: 'DD-MM-YYYY HH:mm:ss',
		locale:'id'
	});
	$('#tgl_1').datetimepicker(
	{
		format: 'DD-MM-YYYY',
		locale:'id'
	});
	$('.tgl_1').datetimepicker(
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

	/* set otomatis hari */
	$('.datepicker.data_hari').change(function()
	{
		var hari = {
			0 : 'Minggu', 1 : 'Senin', 2 : 'Selasa', 3 : 'Rabu', 4 : 'Kamis', 5 : 'Jumat', 6 : 'Sabtu'
		};
		var t = $(this).datepicker('getDate');
		var i = t.getDay();
		$(this).closest('.form-group').find('.hari').val(hari[i]);
	});

	$('[checked="checked"]').parent().addClass('active');
	//Format Tabel
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

	$('ul.sidebar-menu').on('expanded.tree', function(e){
		// Manipulasi menu perlu ada tenggang waktu -- supaya dilakukan sesudah
		// event lain selesai
		e.stopImmediatePropagation();
		setTimeout(scrollTampil($('li.treeview.menu-open')[0]), 500);
	});

	// ========== Tanda tangan laporan dan surat
	$('select[name=pamong_ttd]').change(function(e)
	{
		$('input[name=jabatan_ttd]').val($(this).find(':selected').data('jabatan'));
	});
	$('select[name=pamong_ketahui]').change(function(e)
	{
		$('input[name=jabatan_ketahui]').val($(this).find(':selected').data('jabatan'));
	});
	$('select[name=pamong_ttd]').trigger('change');
	$('select[name=pamong_ketahui]').trigger('change');

	// Untuk input rupiah di form surat
	// Tambahkan 'Rp.' pada saat form di ketik
	// gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
	$('.rupiah').keyup(function(e) {
		var nilai = formatRupiah($(this).val(), 'Rp. ');
		$(this).val(nilai);
	});



	// Penggunaan datatable di inventaris
	var t = $('#tabel4').DataTable({
		'paging'      : true,
    'lengthChange': true,
    'searching'   : true,
    'ordering'    : true,
    'info'        : true,
    'autoWidth'   : false,
		'language' 		: {
				'url': base_url + '/assets/bootstrap/js/dataTables.indonesian.lang'
		}
	});
	t.on('order.dt search.dt', function()
	{
		t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i)
		{
			cell.innerHTML = i+1;
		});
	}).draw();

});

/* Fungsi formatRupiah untuk form surat */
function formatRupiah(angka, prefix, nol_sen=true)
{
	var number_string = angka.replace(/[^,\d]/g, '').toString(),
	split = number_string.split(','),
	sisa = split[0].length % 3,
	rupiah = split[0].substr(0, sisa),
	ribuan = split[0].substr(sisa).match(/\d{3}/gi);

	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if (ribuan)
	{
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}

	rupiah = split[1] != undefined ? rupiah + (nol_sen ? '' : ',' + split[1]) : rupiah;
	return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

function scrollTampil(elem)
{
	elem.scrollIntoView({behavior: 'smooth'});
}

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
		enableHapusTerpilih();
	});
	$("[data-toggle=tooltip]").tooltip();
}

function enableHapusTerpilih()
{
  if ($("input[name='id_cb[]']:checked:not(:disabled)").length <= 0)
  {
    $(".hapus-terpilih").addClass('disabled');
    $(".hapus-terpilih").attr('href','#');
  }
  else
  {
    $(".hapus-terpilih").removeClass('disabled');
    $(".hapus-terpilih").attr('href','#confirm-delete');
  }
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
function formAction(idForm, action, target = '')
{
	if (target != '')
	{
		$('#'+idForm).attr('target', target);
	}
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

// Ganti pilihan RW dan RT di form penduduk
function select_options(select, params)
{
	var url_data = select.attr('data-source') + params;
	select
		.find('option').not('.placeholder')
		.remove()
		.end();

  $.ajax({
    url: url_data,
  }).then(function(options) {
    JSON.parse(options).forEach((option) => {
      var option_elem = $('<option>');

      option_elem
        .val(option[select.attr('data-valueKey')])
        .text(option[select.attr('data-displayKey')]);

      select.append(option_elem);
    });
  });
}

$(function(){
	$('#op_item input:checked').parent().css({'background':'#c9cdff','border':'0.5px solid #7a82eb'});
	$('#op_item input').change(function()
	{
		if ($(this).is('input:checked'))
		{
			$('#op_item input').parent().css({'background':'#fafafa'});
			$('#op_item input:checked').parent().css({'background':'#c9cdff','border':'0.5px solid #7a82eb'});
			$(this).parent().css({'background':'#c9cdff'});
		}
		else
		{
			$(this).parent().css({'background':'#fafafa','border':'0px'});
		}
	});
	$('#op_item label').click(function()
	{
		$(this).prev().trigger('click');
	})
});
