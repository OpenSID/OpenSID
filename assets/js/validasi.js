$(document).ready(function() {
	$("#paging").validate();

	// Untuk form surat memeriksa nomor surat secara remote/ajax
	$("#validasi.form-surat").validate({
		ignore: '#wrapper-mandiri input[name=nomor]',
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
		},
    success: function() {
	    csrf_semua_form();
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
		},
    success: function() {
	    csrf_semua_form();
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

	$(".form-validasi").validate({
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
	}, "Harus 0 atau bilangan 16 digit");

	$('.nik').each(function() {
		$(this).rules("add",
			{
				nik: true,
			});
	});

	jQuery.validator.addMethod("angka", function(value, element) {
		angka_valid = /^\d*$/.test(value);
		return this.optional(element) || angka_valid;
	}, "Harus Berisi Angka");

	jQuery.validator.addMethod("luas", function(value, element) {
		luas_valid = /^\d+(\.\d+)*$/.test(value);
		return this.optional(element) || luas_valid;
	}, "Harus Berisi Angka dan untuk koma gunakan \"titik\"");

	jQuery.validator.addMethod("nama", function(value, element) {
		valid = /^[a-zA-Z '\.,\-]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip");

	jQuery.validator.addMethod("nama_terbatas", function(value, element) {
		valid = /^[a-zA-Z0-9 \-]+$/i.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alfanumerik, spasi dan strip");

	jQuery.validator.addMethod("nomor_sk", function(value, element) {
		valid = /^[a-zA-Z0-9 \.\-\/]+$/i.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alfanumerik, spasi, titik, garis miring dan strip");

	jQuery.validator.addMethod("bilangan_titik", function(value, element) {
		valid = /^[0-9\.]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter numerik dan titik");

	$('.bilangan_titik').each(function() {
		$(this).rules("add",
			{
				bilangan_titik: true,
			});
	});

	jQuery.validator.addMethod("bilangan_spasi", function(value, element) {
		valid = /^[0-9 ]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter numerik dan spasi");

	$('.bilangan_spasi').each(function() {
		$(this).rules("add",
			{
				bilangan_spasi: true,
			});
	});

	// Ketentuan kata sandi sesuai US National Institute of Standards and Technology (NIST)
	//https://en.wikipedia.org/wiki/Password_policy#:~:text=Passwords%20must%20be%20at%20least,should%20be%20acceptable%20in%20passwords
	jQuery.validator.addMethod("pwdLengthNist", function(value, element) {
		valid = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/.test(value);
		return this.optional(element) || valid;
	}, "Harus 8 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil dan satu karakter khusus");

	$('.pwdLengthNist').each(function() {
		$(this).rules("add",
			{
				pwdLengthNist: true,
			});
	});

	// Untuk donjo-app/views/man_user/manajemen_user_form.php di mana 'radiisi' berarti password tidak diubah
	// Ketentuan kata sandi sesuai US National Institute of Standards and Technology (NIST)
	jQuery.validator.addMethod("pwdLengthNist_atau_kosong", function(value, element) {
		valid = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/.test(value);
		return this.optional(element) || valid || value == 'radiisi';
	}, "Harus 8 sampai 20 karakter dan sekurangnya berisi satu angka dan satu huruf besar dan satu huruf kecil dan satu karakter khusus");

	jQuery.validator.addMethod("bilangan", function(value, element) {
		valid = /^[0-9]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter numerik");

	jQuery.validator.addMethod("alamat", function(value, element) {
		valid = /^[a-zA-Z0-9 \.,\-\/]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alpha, numerik, spasi, titik, koma, strip dan garis miring");

	jQuery.validator.addMethod("username", function(value, element) {
		valid = /^[a-zA-Z0-9\.\_]{4,30}$/.test(value);
		return this.optional(element) || valid;
	}, "Username hanya boleh berisi karakter alpha, numerik, titik, dan garis bawah dan terdiri dari 4 hingga 30 karakter");

	jQuery.validator.addMethod("pin_mandiri", function(value, element) {
		angka_valid = /^(?=.*\d).{6,6}$/.test(value);
		return this.optional(element) || angka_valid;
	}, "Hanya boleh berisi 6 angka numerik");

	jQuery.validator.addMethod("ip_address", function(value, element) {
		valid = /^(\d|[1-9]\d|1\d\d|2([0-4]\d|5[0-5]))\.(\d|[1-9]\d|1\d\d|2([0-4]\d|5[0-5]))\.(\d|[1-9]\d|1\d\d|2([0-4]\d|5[0-5]))\.(\d|[1-9]\d|1\d\d|2([0-4]\d|5[0-5]))$/.test(value);
		return this.optional(element) || valid;
	}, "Isi IP address yang valid");

})
