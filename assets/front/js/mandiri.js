$(document).ready(function() {
	$('#list-rekam').DataTable({
		'pageLength' : 10,
		'language': {
			'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
			}
	});
});