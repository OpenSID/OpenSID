/**
 * File ini:
 *
 * Javascript untuk Layanan Mandiri di OpenSID
 *
 * /assets/front/js/mandiri.js
 *
 */

/**
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */

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

$(document).ready(function () {

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
		'ajax': SITE_URL + '/mandiri_web/ajax_table_surat_permohonan',
		'language': {
			url: BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
		},
    "columnDefs": [
	   {
        "targets": [ 5, 6 ],
        "visible": false
	    }
		],
		'aoColumnDefs': [
			{ "sClass": "nowrap", "aTargets": [ 1 ] },
			{
				'aTargets': [1],
				'mData': 'aksi',
				'mRender': function (data, type, row) {
					let action = ``;
					if (row[1] && row[6] == 1) {
						action = `<button type="button" class="btn bg-orange btn-flat btn-sm edit text-center" data-toggle="modal" data-target="#modal" data-title="Ubah Data" title="Ubah Data"  title="Ubah Data" data-id="${row[1]}"><i class="fa fa-edit"></i></button> <button type="button" class="btn bg-red btn-flat btn-sm delete text-center" title="Hapus Data" data-id="${row[1]}"><i class="fa fa-trash"></i> Hapus</button>`;
					}
					return action;
				}
			}
		]
	});

	$('#tambah_dokumen').click(function () {
		$('#unggah_dokumen').trigger('reset');
		$('#file').addClass('required');
		$('.anggota_kk').attr("disabled", false);
		$('.anggota_kk').attr("checked", false);
		$('#myModalLabel').text('Tambah Dokumen');
	})

	$('#list_dokumen').on('click', '.edit', function () {
		let id = $(this).attr('data-id');
		$('#unggah_dokumen').trigger('reset');
		$('#myModalLabel').text('Ubah Dokumen');
		$('#file').removeClass('required');
		$('#modal .modal-body').LoadingOverlay('show');
		$.ajax({
			url: SITE_URL + '/mandiri_web/ajax_get_dokumen_pendukung',
			type: 'POST',
			data: {
				id_dokumen: id
			},
			success: function (response) {
				let data = JSON.parse(response);
				$('#unggah_dokumen').validate().resetForm();
				$('#id_dokumen').val(data.id);
				$('#nama_dokumen').val(data.nama);
				$('#id_syarat').val(data.id_syarat);
				$('#old_file').val(data.satuan);
				$('#modal .modal-body').LoadingOverlay('hide');

				//anggota lain
				$('.anggota_kk').attr("checked", false);
				for (let [key, value] of Object.entries(data.anggota)) {
					if (value.id_pend != data.id_pend) {
						let id_anggota = '#anggota_' + value.id_pend;
						$(id_anggota).attr("checked", true);
					}
				}

				switch (data.success) {
					case -1:
						show_alert('red', 'Error', data.message);
						$('#modal').modal('hide');
						break;
					default:
						break;
				}
			},
			error: function (err) {
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
							url: SITE_URL + '/mandiri_web/ajax_hapus_dokumen_pendukung',
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
	});

	$('#unggah_dokumen').submit(function (e) {
		e.preventDefault();
		if ($(this).valid()) {
			$('#modal .modal-body').LoadingOverlay("show");
			$.ajax({
				url: SITE_URL + '/mandiri_web/ajax_upload_dokumen_pendukung',
				type: 'POST',
				data: new FormData(this),
				processData: false,
				contentType: false,
				cache: false,
				async: true,
				success: function (response) {
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
							show_alert('green', 'Sukses', data.message);
							break;
					}
				},
				error: function (e) {
					console.log(e);
				},
			})
		}
	});

});
