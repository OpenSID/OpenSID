$(document).ready(function() {

$.validator.addMethod("validkk", function(value, element) {
  return this.optional(element) || /[0-9]{16}/.test( value );
}, "<font color=C24242>Nomer KK tidak valid, harus 16 digit</font>");

	  
	$("#validasi").validate();
	
	$("#mainform").validate();
	
	$("#maincontent").validate();
	
})
