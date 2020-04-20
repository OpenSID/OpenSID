function show_alert(type, title, content) {
	const icon = type == 'red' ? 'fa fa-warning' : 'fa fa-check';

	$.alert({
		"type": type,
		"title": title,
		"content": content,
		"icon": icon,
		"backgroundDismiss": true
	})
}

$(document).ready(function() {
	$('#unggah_dokumen').validate();
	
	$('.datatable-polos').DataTable({
		'pageLength': 10,
		'responsive': true,
		'language': {
			'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
		}
	});

	$('#dokumen').DataTable({
		'paging': false,
		'ordering': false,
		'info': false,
		'searching': false,
		'responsive': true,
		'rowReorder': {
			'selector': 'td:nth-child(2)'
		},
		'ajax': SITE_URL + '/first/ajax_table_surat_permohonan',
		'language': {
			url: BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
		},
		'aoColumnDefs': [
			{
				'aTargets': [3],
				'mData': 'aksi',
				'mRender': function (data, type, row) {
						return `<button type="button" class="btn bg-orange btn-flat btn-sm edit text-center" data-toggle="modal" data-target="#modal" data-title="Ubah Data" title="Ubah Data"  title="Ubah Data" data-id="${row[4]}"><i class="fa fa-edit"></i> Ubah</button>
						<button type="button" class="btn bg-red btn-flat btn-sm delete text-center" title="Delete Data" data-id="${row[4]}"><i class="fa fa-trash"></i> Hapus</button>`
				}
			 }
		]
	});

	$('#tambah_dokumen').click(function(){
		$('#unggah_dokumen').trigger('reset');
		$('#file').addClass('required');
		$('#myModalLabel').text('Tambah Dokumen');
	})

	$('#list_dokumen').on('click', '.edit', function(){
		let id = $(this).attr('data-id');
		$('#unggah_dokumen').trigger('reset');
		$('#myModalLabel').text('Ubah Dokumen');
		$('#file').removeClass('required');
		$('#modal .modal-body').LoadingOverlay('show');
		$.ajax({
			url: SITE_URL + '/first/ajax_get_dokumen_pendukung',
			type: 'POST',
			data: {
				id_dokumen: id
			},
			success: function(response) {
				let data = JSON.parse(response);
				$('#unggah_dokumen').validate().resetForm();
				$('#id_dokumen').val(data.id);
				$('#nama_dokumen').val(data.nama);
				$('#id_syarat').val(data.id_syarat);
				$('#old_file').val(data.satuan);
				$('#modal .modal-body').LoadingOverlay('hide');
				switch (data.success) {
					case -1:
						show_alert('red', 'Error', data.message);
						$('#modal').modal('hide');
						break;
					default:
						break;
				}
			},
			error: function(err) {
				console.log(err);
			}
		})
	});

	$('#list_dokumen').on('click', '.delete', function() {
		let id = $(this).attr('data-id');
		$.confirm({
			'title': 'Konfirmasi',
			'content': 'Apakah Anda yakin ingin menghapus data ini?',
			'icon': 'fa fa-warning',
			'buttons': {
				'confirm': {
					'text': 'Hapus',
					'btnClass': 'btn btn-danger',
					'action': function() {
						$('#modal .modal-body').LoadingOverlay('show');
						$.ajax({
							url: SITE_URL + '/first/ajax_hapus_dokumen_pendukung',
							type: 'POST',
							data: {
								id_dokumen: id
							},
							success: function(response) {
								let data = JSON.parse(response);
								$('#modal .modal-body').LoadingOverlay('hide');
								switch (data.success) {
									case -1:
										show_alert('red', 'Error', data.message);
										break;
									default:
										show_alert('green', 'Sukses', 'Berhasil menghapus');
										$('#dokumen').DataTable().ajax.reload();
										break;
								}
							}
						})
					},
				},
				'cancel': {
					'text': 'Batalkan'
				}
			}
		})
	})
	
	$('#unggah_dokumen').submit(function(e) {
		e.preventDefault();
		if ($(this).valid()) {
			$('#modal .modal-body').LoadingOverlay("show");
			$.ajax({
				url: SITE_URL + '/first/ajax_upload_dokumen_pendukung',
				type: 'POST',
				data: new FormData(this),
				processData:false,
				contentType:false,
				cache:false,
				async:true,
				success: function(response) {
					let data = JSON.parse(response);
					$('#modal .modal-body').LoadingOverlay("hide");
					switch (data.success) {
						case -1:
							show_alert('red', 'Error', data.message);
							break;
						default:
							$('#dokumen').DataTable().ajax.reload();
							$('#unggah_dokumen').trigger('reset');
							$('#modal').modal('hide');
							show_alert('green', 'Sukses',data.message);
							break;
					}
				},
			  error: function(e) {
			    console.log(e);
			  },
			})
		}
	});
});