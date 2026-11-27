function kbvopener(kbelem) {
	kbelem.on('click',function() {
		var kb = $(this).getkeyboard();
		if ( kb.isOpen ) {
			kb.close();
		} else {
			kb.reveal();
		}
	});
}

$(document).ready(function () {

	$('.kbvtext').each(function( index ) {
		$(this)
		.keyboard({
			openOn : null,
			stayOpen : false,
			layout : 'qwerty',
			display: {
				'bksp'   : '\u2190',
				'enter'  : '\u23CE',
				'normal' : 'ABC',
				'accept' : 'Lanjut',
				'cancel' : 'Tutup'
			}
		})
		.addTyping();
		kbvopener($(this));
	});

	$('.kbvnumber').each(function( index ) {
		$(this)
		.keyboard({
			display: {
				'bksp'   : '\u2190',
				'accept' : 'Lanjut',
				'cancel' : 'Tutup',
			},
			openOn : null,
			stayOpen : true,
			layout: 'custom',
			customLayout: {
				'normal': [
					'1 2 3 4 5 6 7 8 9 0 {bksp}',
					'{cancel} {accept}'
				]
			}
		})
		.addTyping();
		kbvopener($(this));
	});

});
