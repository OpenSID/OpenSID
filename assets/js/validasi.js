$(document).ready(function() {
	$("#paging").validate();

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
