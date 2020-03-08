$(document).ready(function() {
	$("#paging").validate();

	// Untuk form surat memeriksa nomor surat secara remote/ajax
	$("#validasi.form-surat").validate({
		errorElement: "label",
		errorClass: "error",
		highlight:function (element){
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight:function (element){
			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else if (element.hasClass('select2')) {
				error.insertAfter(element.next('span'));
			} else {
				error.insertAfter(element);
			}
		},
		// https://www.bladephp.co/jquery-validation-remote-codeigniter
		rules: {
			url_surat: {
				required: true
			},
			nomor: {
				required: true,
				remote: {
					url: $('#url_remote').val(),
					type: "post",
					data:{
						url: function() {
							return $('#url_surat').val()
						}
					}
				}
			}
		},
		messages: {
			nomor: {
				remote: "Nomor surat itu sudah digunakan",
			},
		}
	});

	// Untuk form surat masuk/keluar memeriksa nomor urut secara remote/ajax
	$("#validasi.nomor-urut").validate({
		errorElement: "label",
		errorClass: "error",
		highlight:function (element){
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight:function (element){
			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else if (element.hasClass('select2')) {
				error.insertAfter(element.next('span'));
			} else {
				error.insertAfter(element);
			}
		},
		// https://www.bladephp.co/jquery-validation-remote-codeigniter
		rules: {
			nomor_urut: {
				required: true,
				remote: {
					url: $('#url_remote').val(),
					type: "post",
					data:{
						nomor_urut_lama: function() {
							return $('#nomor_urut_lama').val()
						}
					}
				}
			}
		},
		messages: {
			nomor_urut: {
				remote: "Nomor urut itu sudah digunakan",
			},
		}
	});

	$("#validasi").validate({
		errorElement: "label",
		errorClass: "error",
		highlight:function (element){
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight:function (element){
			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else if (element.hasClass('select2')) {
				error.insertAfter(element.next('span'));
			} else {
				error.insertAfter(element);
			}
		}
	});

	$("#mainform").validate({
		errorElement: "label",
		errorClass: "error",
		highlight:function (element){
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight:function (element){
			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else if (element.hasClass('select2')) {
				error.insertAfter(element.next('span'));
			} else {
				error.insertAfter(element);
			}
		}
	});

	$("#maincontent").validate({
		errorElement: "label",
		errorClass: "error",
		highlight:function (element){
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight:function (element){
			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else if (element.hasClass('select2')) {
				error.insertAfter(element.next('span'));
			} else {
				error.insertAfter(element);
			}
		}
	});

	jQuery.validator.addMethod("nik", function(value, element) {
		nik_valid = /^\d*$/.test(value) && (value == 0 || value.length == 16);
		return this.optional(element) || nik_valid;
	}, "NIK harus 0 atau bilangan 16 digit");

	$('.nik').each(function() {
		$(this).rules("add",
			{
				nik: true,
			});
	});

	jQuery.validator.addMethod("nama", function(value, element) {
		valid = /^[a-zA-Z '\.,\-]+$/.test(value);
		return this.optional(element) || valid;
	}, "Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip");

	$('.nama').each(function() {
		$(this).rules("add",
			{
				nama: true,
			});
	});

	jQuery.validator.addMethod("nomor_sk", function(value, element) {
		valid = /^[a-zA-Z0-9 \.\-\/]+$/i.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alfanumerik, spasi, titik, garis miring dan strip");

	$('.nomor_sk').each(function() {
		$(this).rules("add",
			{
				nomor_sk: true,
			});
	});

})
