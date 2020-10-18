/**
 * File ini:
 *
 * Javascript untuk Keyboard Layanan Mandiri di OpenSID
 *
 * /assets/front/js/mandiri-keyboard.js
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
