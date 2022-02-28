function generateTable(tabel, dataTable, ubah, hapus) {
	$.ajax({
		url  : 'load_data',
		type: 'GET',
		dataType: 'json',
		success : function(data) {
			var html = '';
			var i;
			var no;
			var kode;
			for (i=0; i<data.length; i++) {
				no = i+1;
				html += '<tr>'+
				'<td class="padat"><input type="checkbox" name="id_cb[]" value="'+data[i].id+'" /></td>'+
				'<td class="padat">'+no+'</td>'+
				'<td class="aksi">'+ 
					((ubah) ? '<a href="javascript:;" class="btn bg-orange btn-flat btn-sm item_edit" title="Ubah" data="'+data[i].id+'"><i class="fa fa-edit"></i></a>'+
						'&nbsp' : '') + 
					((hapus) ? '<a href="#" data-href="delete_input/'+data[i].id+'" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>'+
						'</td>' : '') + '</td>'+			
				'<td>'+(($('#jenis').val() == '5.BELANJA') ? data[i].Kd_Keg : data[i].Kd_Rincian)+'</td>'+
				'<td class="rupiah">'+formatRupiah(data[i].Nilai_Anggaran)+'</td>'+
				'<td class="rupiah">'+formatRupiah(data[i].Nilai_Realisasi)+'</td>'+
				'</tr>';
			}
			tabel.html(html);

			tabel = dataTable.dataTable({
				"pageLength": 10,
				'columnDefs': [
				{
					"searchable": false,
					"orderable": false,
					"targets": [0, 1, 2]
				},
				],
				"order": [[ 3, 'asc' ],[ 5, 'asc' ]],
				'language': {
					'url': BASE_URL + '/assets/bootstrap/js/dataTables.indonesian.lang'
				},
				'drawCallback': function () {
					$('.dataTables_paginate > .pagination').addClass('pagination-sm no-margin');
				},
			});
		}
	});
	return generateTable;
}

//CREATE
function saveAdd() {
	//Simpan Add Anggaran / Realisasi
	$('#btn_simpan').on('click', function() {
		$('#form-tambah').validate({
			submitHandler: function() {
				var Tahun = $('#Tahun').val();
				var Kd_Akun = $('#Kd_Akun').val();

				if ($("#Kd_Akun").val() == "4.PENDAPATAN") {
					var Kd_Keg = $('#Kd_Keg').val();
					var Kd_Rincian = $('#Kd_Rincian_pd').val();
				} else if ($("#Kd_Akun").val() == "5.BELANJA") {
					var Kd_Keg = $('#Kd_Keg').val();
					var Kd_Rincian='5.0.0';
				} else {
					var Kd_Keg = $('#Kd_Keg').val();
					var Kd_Rincian = $('#Kd_Rincian_by').val();
				}

				var Nilai_Anggaran = $('#Nilai_Anggaran').val();
				var Nilai_Realisasi = $('#Nilai_Realisasi').val();

				$.ajax({
					type : "POST",
					url  : 'simpan_anggaran',
					dataType : "JSON",
					data : {Tahun:Tahun, Kd_Akun:Kd_Akun, Kd_Keg:Kd_Keg, Kd_Rincian:Kd_Rincian, Nilai_Anggaran:Nilai_Anggaran, Nilai_Realisasi:Nilai_Realisasi},
					success: function(data) {
						$('[name="Tahun"]').val("");
						$('[name="Kd_Akun"]').val("");
						$('[name="Kd_Keg_bl"]').val("");
						$('[name="Kd_Rincian_pd"]').val("");
						$('[name="Kd_Rincian_by"]').val("");
						$('[name="Nilai_Anggaran"]').val("");
						$('[name="Nilai_Realisasi"]').val("");
						$('#ModalAdd').modal('hide');
					}
				}).then(function() {
					location.reload();
				});
				return false;
			}
		});
	});

	return saveAdd;
}

//Tampilkan Data Pendapatan Yang Akan Di Ubah
function getEdit(table) {
	table.on('click', '.item_edit', function() {
		var id = $(this).attr('data');
		$.ajax({
			type : "GET",
			url  : 'get_anggaran',
			dataType : "JSON",
			data : {id:id},
			success: function(data) {
				$.each(data, function(id, Tahun, Kd_Akun, Kd_Keg, Kd_Rincian, Nilai_Anggaran, Nilai_Realisasi) {
					$('#ModalEdit').modal('show');
					$('[name="id_edit"]').val(data.id);
					$('[name="Tahun_edit"]').val(data.Tahun);
					$('[name="Kd_Akun_edit"]').val(data.Kd_Akun).change();
					$('[name="Kd_Keg_edit_bl"]').val(data.Kd_Keg).change();
					$('[name="Kd_Rincian_edit_pd"]').val(data.Kd_Rincian).change();
					$('[name="Kd_Rincian_edit_by"]').val(data.Kd_Rincian).change();
					$('[name="Nilai_Anggaran_edit"]').val(data.Nilai_Anggaran);
					$('[name="Nilai_Realisasi_edit"]').val(data.Nilai_Realisasi);

					if ($("#Kd_Akun2").val() == "4.PENDAPATAN") {
						$("#Pendapatan_edit").show();
						$("#Pembiayaan_edit").hide();
						$("#Belanja_edit").hide();
					} else if ($("#Kd_Akun2").val() == "5.BELANJA") {
						$("#Pendapatan_edit").hide();
						$("#Pembiayaan_edit").hide();
						$("#Belanja_edit").show();
					} else if ($("#Kd_Akun2").val() == "6.PEMBIAYAAN") {
						$("#Pendapatan_edit").hide();
						$("#Pembiayaan_edit").show();
						$("#Belanja_edit").hide();
					} else {
						$("#Pendapatan_edit").hide();
						$("#Pembiayaan_edit").hide();
						$("#Belanja_edit").hide();
					}
				});
			}
		});
		return false;
	});
	return getEdit;
}

//Ubah Data Anggaran / Realisasi
function saveEdit() {
	$('#btn_update').on('click', function() {
		$('#form-edit').validate({
			submitHandler: function() {
				var id = $('#id2').val();
				var Tahun = $('#Tahun2').val();
				var Kd_Akun = $('#Kd_Akun2').val();

				if ($("#Kd_Akun2").val() == "4.PENDAPATAN") {
					var Kd_Keg = $('#Kd_Keg2_bl').val();
					var Kd_Rincian = $('#Kd_Rincian2_pd').val();
				} else if ($("#Kd_Akun2").val() == "5.BELANJA") {
					var Kd_Keg = $('#Kd_Keg2_bl').val();
					var Kd_Rincian='5.0.0';
				} else {
					var Kd_Keg = $('#Kd_Keg2_bl').val();
					var Kd_Rincian = $('#Kd_Rincian2_by').val();
				}

				var Nilai_Anggaran = $('#Nilai_Anggaran2').val();
				var Nilai_Realisasi = $('#Nilai_Realisasi2').val();

				$.ajax({
					type : "POST",
					url  : 'update_anggaran',
					dataType : "JSON",
					data : {id:id, Tahun:Tahun, Kd_Akun:Kd_Akun, Kd_Keg:Kd_Keg, Kd_Rincian:Kd_Rincian, Nilai_Anggaran:Nilai_Anggaran, Nilai_Realisasi:Nilai_Realisasi},
					success: function(data) {
						$('[name="id_edit"]').val("");
						$('[name="Tahun_edit"]').val("");
						$('[name="Kd_Akun_edit"]').val("");
						$('[name="Kd_Keg_edit_bl"]').val("");
						$('[name="Kd_Rincian_edit_pd"]').val("");
						$('[name="Kd_Rincian_edit_by"]').val("");
						$('[name="Nilai_Anggaran_edit"]').val("");
						$('[name="Nilai_Realisasi_edit"]').val("");
						$('#ModalEdit').modal('hide');
					}
				}).then(function() {
					location.reload();
				});
				return false;
			}
		});
	});

	return saveAdd;
}

//SALIN TEMPLATE DATA
function salinData() {
	$('#btn_salin').on('click', function() {
		$('#ModalSalin').modal('show');
	});

	$('#btn_salin1').on('click', function() {
		$('#form-salin').validate({
			submitHandler: function() {
				var kode = $('#kodetahun').val();
				$.ajax({
					type : "POST",
					url  : 'salin_anggaran_tpl',
					dataType : "JSON",
					data : {kode: kode},
					success: function(data) {
						$('#ModalSalin').modal('hide');
					}
				}).then(function() {
					location.reload();
				});
				return false;
			}
		});
	});

	return salinData;
}

// MISC
function tools() {
	$("#Kd_Akun").change(function () {
		if ($("#Kd_Akun").val() == "4.PENDAPATAN") {
			$("#Pendapatan").show();
			$("#Pembiayaan").hide();
			$("#Belanja").hide();
		} else if ($("#Kd_Akun").val() == "5.BELANJA") {
			$("#Pendapatan").hide();
			$("#Pembiayaan").hide();
			$("#Belanja").show();
		} else if ($("#Kd_Akun").val() == "6.PEMBIAYAAN") {
			$("#Pendapatan").hide();
			$("#Pembiayaan").show();
			$("#Belanja").hide();
		} else {
			$("#Pendapatan").hide();
			$("#Pembiayaan").hide();
			$("#Belanja").hide();
		}
	});

	return tools;
}
