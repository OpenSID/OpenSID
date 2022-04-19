/**
	Javascript untuk penggunaan dan penyesuaian datepicker dan datetimepicker
*/

$(document).ready(function()
{

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
		date: moment(new Date()).add($('#tgl_akhir').data('masa-berlaku'), $('#tgl_akhir').data('satuan-masa-berlaku'))
	});
	$('#tgl_mulai').datetimepicker().on('dp.change', function (e) {
		$('#tgl_akhir').data('DateTimePicker').minDate(moment(new Date(e.date)));
		$(this).data("DateTimePicker").hide();
		var tglAkhir = moment(new Date(e.date));
		tglAkhir.add($('#tgl_akhir').data('masa-berlaku'), $('#tgl_akhir').data('satuan-masa-berlaku'));
		$('#tgl_akhir').data('DateTimePicker').date(tglAkhir);
	});

	$('.tgl_minimal').datetimepicker().on('dp.change', function (e) {
		var tgl_lebih_besar = $(this).data('tgl-lebih-besar');
		$(tgl_lebih_besar).data('DateTimePicker').minDate(moment(new Date(e.date)));
		$(this).data("DateTimePicker").hide();
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
	$('.tgl_sekarang').datetimepicker(
	{
		format: 'DD-MM-YYYY',
		locale:'id',
		maxDate: moment(new Date())
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
	$('#tanggal_cetak_ktp').datetimepicker(
	{
		format: 'DD-MM-YYYY',
		locale:'id',
		maxDate: new(Date)
	});
});
