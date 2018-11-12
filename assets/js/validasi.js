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

})
