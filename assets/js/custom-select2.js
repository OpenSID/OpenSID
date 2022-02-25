/**
	Javascript untuk penggunaan dan penyesuaian select2
*/

$(document).ready(function()
{

	// Select2 dengan fitur pencarian
	$('.select2').select2({
		width: '100%',
		dropdownAutoWidth : true
	});

	// Select2 tanpadrowdown width
	$('.select2-non-auto').select2({
		width: '100%',
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

	// Select2 menampilkan ikon
	// https://stackoverflow.com/questions/37386293/how-to-add-icon-in-select2
	function format_ikon (state) {
    if (!state.id) { return state.text; }
    return '<i class="fa fa-lg '+state.id.toLowerCase()+'"></i>&nbsp;&nbsp; '+state.text;
	}
	$('.select2-ikon').select2(
	{
    templateResult: format_ikon,
    templateSelection: format_ikon,
    escapeMarkup: function(m) { return m; }
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

	// Reset select2 ke nilai asli
	// https://stackoverflow.com/questions/10319289/how-to-execute-code-after-html-form-reset-with-jquery
	$('button[type="reset"]').click(function(e)
	{
    e.preventDefault();
    $(this).closest('form').trigger('reset');
		// https://stackoverflow.com/questions/15205262/resetting-select2-value-in-dropdown-with-reset-button
		$('.select2').trigger('change');
		$('.select2-ikon').trigger('change');
	});

});
