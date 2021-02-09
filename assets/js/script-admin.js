/**
 * dependencies: select2, moment, colorpicker
 */
$(document).ready(function()
{
	// Select2 dengan fitur pencarian
	$('.select2').select2({
		width: '100%',
		dropdownAutoWidth : true
	});

	$('.select2-nik-ajax').select2({
	  ajax: {
	    url: function () {
	      return $(this).data('url');
	    },
	    dataType: 'json',
	    delay: 250,
	    data: function (params) {
	      return {
	        q: params.term || '', // search term
	        page: params.page || 1,
 	        filter_sex: $(this).data('filter-sex')
	      };
	    },
	    processResults: function (data, params) {
	      // parse the results into the format expected by Select2
	      // since we are using custom formatting functions we do not need to
	      // alter the remote JSON data, except to indicate that infinite
	      // scrolling can be used
	      // params.page = params.page || 1;

	      return {
	        results: data.results,
	        pagination: data.pagination
	      };
	    },
	    cache: true
	  },
		templateResult: function (penduduk) {
			if (!penduduk.id) {
			  return penduduk.text;
			}
			var _tmpPenduduk = penduduk.text.split('\n');
			var $penduduk = $(
			  '<div>'+_tmpPenduduk[0]+'</div><div>'+_tmpPenduduk[1]+'</div>'
			);
			return $penduduk;
		},
	  placeholder: '--  Cari NIK / Tag ID Card / Nama Penduduk --',
	  minimumInputLength: 0,
	});

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

	$('.select2-ikon').select2(
		{
		templateResult: format_ikon,
		templateSelection: format_ikon,
		escapeMarkup: function(m) { return m; }
	});
	
	// Select2 dengan fitur pencarian dan boleh isi sendiri
	$('.select2-tags').select2({
		tags: true
	});

	// Select2 untuk disposisi pada form
	// surat masuk
	$('#disposisi_kepada').select2({
		placeholder: "Pilih tujuan disposisi"
	});

	//Format Tanggal dan Jam
	$('.datepicker').datepicker({
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
		date: moment(new Date()).add($('#tgl_akhir').data('masa-berlaku'), $('#tgl_akhir').data('satuan-masa-berlaku'))
	});
	$('#tgl_mulai').datetimepicker().on('dp.change', function (e) {
		$('#tgl_akhir').data('DateTimePicker').minDate(moment(new Date(e.date)));
		$(this).data("DateTimePicker").hide();
		var tglAkhir = moment(new Date(e.date));
		tglAkhir.add($('#tgl_akhir').data('masa-berlaku'), $('#tgl_akhir').data('satuan-masa-berlaku'));
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
	$('.tgl').datetimepicker(
	{
		format: 'DD-MM-YYYY',
		useCurrent: false,
		locale:'id'
	});
	$('.tgl_indo').datetimepicker(
	{
		format: 'DD-MM-YYYY',
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

	//color picker with addon
	$('.my-colorpicker2').colorpicker();
	//Text Editor with addon
	$('#min-textarea').wysihtml5();
});