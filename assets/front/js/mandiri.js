$(document).ready(function() {

	var table = $('#syarat_surat').DataTable({
		'processing': true,
		'paging': false,
		'info': false,
		'ordering': false,
		'searching': false,
		'ajax': {
			'url': SITE_URL + '/layanan-mandiri/surat/cek_syarat',
			'type': "POST",
			data: function ( d ) {
				d.id_surat = $("#id_surat").val();
				d.id_permohonan = $("#id_permohonan").val();
			},
		},
		//Set column definition initialisation properties.
		"columnDefs": [
			{
				"targets": [ 0 ], //first column / numbering column
				"orderable": false, //set not orderable
			},
		],
		'aoColumnDefs': [
			{
				"sClass": "padat", "aTargets": [0, 2]
			}
		],
		'language': {
			'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
		},
		'drawCallback': function () {
			$('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
			processInfo(table.page.info());
		}
	});

	function processInfo(info) {
		if (info.recordsTotal <= 0) {
			$('.ada_syarat').hide();
		} else {
			$('.ada_syarat').show();
		}
	}

	$('#id_surat').change(function() {
		table.ajax.reload();
	});

	// Perbaharui daftar pilihan dokumen setelah ada perubahan daftar dokumen yg tersedia
	// Beri tenggang waktu supaya database dokumen selesai di-initialise
	setTimeout(function() {
		// Ambil instance dari datatable yg sudah ada
		var dokumen = $('#dokumen').DataTable({"retrieve": true});
		dokumen.on( 'draw', function () {
			table.ajax.reload();
		} );
	}, 500);

	if ($('input[name=id_permohonan]').val()) {
		$('#id_surat').attr('disabled','disabled');
	}

	$('#validasi').submit(function() {
		var validator = $("#validasi").validate();
		var syarat = $("select[name='syarat[]']");
		var i;
		for (i = 0; i < syarat.length; i++) {
			if (!validator.element(syarat[i])) {
				$("#kata_peringatan").text('Syarat belum dilengkapi');
				$("#dialog").modal('show');
				return false;
			}
		};
	});

	$('.datatable-polos').DataTable({
		'pageLength': 10,
		'responsive': true,
		'aoColumnDefs': [
			{
				"sClass": "padat", "aTargets": [0]
			}
		],
		'language': {
			'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
		}
	});

	function show_alert(type, title, content) {
		const icon = type == 'red' ? 'fa fa-warning' : 'fa fa-check';
		$.alert({
			"type": type,
			"title": title,
			"content": content,
			"icon": icon,
			"backgroundDismiss": true
		});
	}
});
