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

$(document).ready(function () {

    $('.kbvtext')
    .keyboard({
      openOn : null,
      stayOpen : true,
      layout: 'custom',

      display: {
        'bksp'   : '\u2190',
        'enter'  : '\u23CE',
        'normal' : 'ABC',
        'accept' : 'Lanjut',
        'cancel' : 'Tutup'
      },

      layout: 'custom',
      customLayout: {
        'normal': [
          'a b c d e f g h i j k l',
          'm n o p q r s t u v w x',
          'y z 1 2 3 4 5 6 7 8 9 0',
          '{enter} {bksp} {space} {cancel} {accept}'
        ]
      }
    })
    .addTyping();

    $('.kbvtext1')
    .keyboard({
      openOn : null,
      stayOpen : true,
      layout: 'custom',

      display: {
        'bksp'   : '\u2190',
        'enter'  : '\u23CE',
        'normal' : 'ABC',
        'accept' : 'Lanjut',
        'cancel' : 'Tutup'
      },

      layout: 'custom',
      customLayout: {
        'normal': [
          'a b c d e f g h i j k l',
          'm n o p q r s t u v w x',
          'y z 1 2 3 4 5 6 7 8 9 0',
          '{enter} {bksp} {space} {cancel} {accept}'
        ]
      }
    })
    .addTyping();

    $('.kbvtext2')
    .keyboard({
      openOn : null,
      stayOpen : true,
      layout: 'custom',

      display: {
        'bksp'   : '\u2190',
        'enter'  : '\u23CE',
        'normal' : 'ABC',
        'accept' : 'Lanjut',
        'cancel' : 'Tutup'
      },

      layout: 'custom',
      customLayout: {
        'normal': [
          'a b c d e f g h i j k l',
          'm n o p q r s t u v w x',
          'y z 1 2 3 4 5 6 7 8 9 0',
          '{enter} {bksp} {space} {cancel} {accept}'
        ]
      }
    })
    .addTyping();

    $('.kbvtext3')
    .keyboard({
      openOn : null,
      stayOpen : true,
      layout: 'custom',

      display: {
        'bksp'   : '\u2190',
        'enter'  : '\u23CE',
        'normal' : 'ABC',
        'accept' : 'Lanjut',
        'cancel' : 'Tutup'
      },

      layout: 'custom',
      customLayout: {
        'normal': [
          'a b c d e f g h i j k l',
          'm n o p q r s t u v w x',
          'y z 1 2 3 4 5 6 7 8 9 0',
          '{enter} {bksp} {space} {cancel} {accept}'
        ]
      }
    })
    .addTyping();

    $('.kbvtext4')
    .keyboard({
      openOn : null,
      stayOpen : true,
      layout: 'custom',

      display: {
        'bksp'   : '\u2190',
        'enter'  : '\u23CE',
        'normal' : 'ABC',
        'accept' : 'Lanjut',
        'cancel' : 'Tutup'
      },

      layout: 'custom',
      customLayout: {
        'normal': [
          'a b c d e f g h i j k l',
          'm n o p q r s t u v w x',
          'y z 1 2 3 4 5 6 7 8 9 0',
          '{enter} {bksp} {space} {cancel} {accept}'
        ]
      }
    })
    .addTyping();

    $('.kbvtext5')
    .keyboard({
      openOn : null,
      stayOpen : true,
      layout: 'custom',

      display: {
        'bksp'   : '\u2190',
        'enter'  : '\u23CE',
        'normal' : 'ABC',
        'accept' : 'Lanjut',
        'cancel' : 'Tutup'
      },

      layout: 'custom',
      customLayout: {
        'normal': [
          'a b c d e f g h i j k l',
          'm n o p q r s t u v w x',
          'y z 1 2 3 4 5 6 7 8 9 0',
          '{enter} {bksp} {space} {cancel} {accept}'
        ]
      }
    })
    .addTyping();

    $('.kbvtext6')
    .keyboard({
      openOn : null,
      stayOpen : true,
      layout: 'custom',

      display: {
        'bksp'   : '\u2190',
        'enter'  : '\u23CE',
        'normal' : 'ABC',
        'accept' : 'Lanjut',
        'cancel' : 'Tutup'
      },

      layout: 'custom',
      customLayout: {
        'normal': [
          'a b c d e f g h i j k l',
          'm n o p q r s t u v w x',
          'y z 1 2 3 4 5 6 7 8 9 0',
          '{enter} {bksp} {space} {cancel} {accept}'
        ]
      }
    })
    .addTyping();

    $('.kbvtext7')
    .keyboard({
      openOn : null,
      stayOpen : true,
      layout: 'custom',

      display: {
        'bksp'   : '\u2190',
        'enter'  : '\u23CE',
        'normal' : 'ABC',
        'accept' : 'Lanjut',
        'cancel' : 'Tutup'
      },

      layout: 'custom',
      customLayout: {
        'normal': [
          'a b c d e f g h i j k l',
          'm n o p q r s t u v w x',
          'y z 1 2 3 4 5 6 7 8 9 0',
          '{enter} {bksp} {space} {cancel} {accept}'
        ]
      }
    })
    .addTyping();

    $('.kbvtext8')
    .keyboard({
      openOn : null,
      stayOpen : true,
      layout: 'custom',

      display: {
        'bksp'   : '\u2190',
        'enter'  : '\u23CE',
        'normal' : 'ABC',
        'accept' : 'Lanjut',
        'cancel' : 'Tutup'
      },

      layout: 'custom',
      customLayout: {
        'normal': [
          'a b c d e f g h i j k l',
          'm n o p q r s t u v w x',
          'y z 1 2 3 4 5 6 7 8 9 0',
          '{enter} {bksp} {space} {cancel} {accept}'
        ]
      }
    })
    .addTyping();

    $('.kbvtext9')
    .keyboard({
      openOn : null,
      stayOpen : true,
      layout: 'custom',

      display: {
        'bksp'   : '\u2190',
        'enter'  : '\u23CE',
        'normal' : 'ABC',
        'accept' : 'Lanjut',
        'cancel' : 'Tutup'
      },

      layout: 'custom',
      customLayout: {
        'normal': [
          'a b c d e f g h i j k l',
          'm n o p q r s t u v w x',
          'y z 1 2 3 4 5 6 7 8 9 0',
          '{enter} {bksp} {space} {cancel} {accept}'
        ]
      }
    })
    .addTyping();

    $('.kbvtext10')
    .keyboard({
      openOn : null,
      stayOpen : true,
      layout: 'custom',

      display: {
        'bksp'   : '\u2190',
        'enter'  : '\u23CE',
        'normal' : 'ABC',
        'accept' : 'Lanjut',
        'cancel' : 'Tutup'
      },

      layout: 'custom',
      customLayout: {
        'normal': [
          'a b c d e f g h i j k l',
          'm n o p q r s t u v w x',
          'y z 1 2 3 4 5 6 7 8 9 0',
          '{enter} {bksp} {space} {cancel} {accept}'
        ]
      }
    })
    .addTyping();

    $('.kbvopener').on('click',function() {
      var kbvtext = $('.kbvtext').getkeyboard();
      if ( kbvtext.isOpen ) {
        kbvtext.close();
      } else {
        kbvtext.reveal();
      }
    });

    $('.kbvopener1').on('click',function() {
      var kbvtext = $('.kbvtext1').getkeyboard();
      if ( kbvtext.isOpen ) {
        kbvtext.close();
      } else {
        kbvtext.reveal();
      }
    });

    $('.kbvopener2').on('click',function() {
      var kbvtext = $('.kbvtext2').getkeyboard();
      if ( kbvtext.isOpen ) {
        kbvtext.close();
      } else {
        kbvtext.reveal();
      }
    });

    $('.kbvopener3').on('click',function() {
      var kbvtext = $('.kbvtext3').getkeyboard();
      if ( kbvtext.isOpen ) {
        kbvtext.close();
      } else {
        kbvtext.reveal();
      }
    });

    $('.kbvopener4').on('click',function() {
      var kbvtext = $('.kbvtext4').getkeyboard();
      if ( kbvtext.isOpen ) {
        kbvtext.close();
      } else {
        kbvtext.reveal();
      }
    });

    $('.kbvopener5').on('click',function() {
      var kbvtext = $('.kbvtext5').getkeyboard();
      if ( kbvtext.isOpen ) {
        kbvtext.close();
      } else {
        kbvtext.reveal();
      }
    });

    $('.kbvopener6').on('click',function() {
      var kbvtext = $('.kbvtext6').getkeyboard();
      if ( kbvtext.isOpen ) {
        kbvtext.close();
      } else {
        kbvtext.reveal();
      }
    });

    $('.kbvopener7').on('click',function() {
      var kbvtext = $('.kbvtext7').getkeyboard();
      if ( kbvtext.isOpen ) {
        kbvtext.close();
      } else {
        kbvtext.reveal();
      }
    });

    $('.kbvopener8').on('click',function() {
      var kbvtext = $('.kbvtext8').getkeyboard();
      if ( kbvtext.isOpen ) {
        kbvtext.close();
      } else {
        kbvtext.reveal();
      }
    });

    $('.kbvopener9').on('click',function() {
      var kbvtext = $('.kbvtext9').getkeyboard();
      if ( kbvtext.isOpen ) {
        kbvtext.close();
      } else {
        kbvtext.reveal();
      }
    });

    $('.kbvopener10').on('click',function() {
      var kbvtext = $('.kbvtext10').getkeyboard();
      if ( kbvtext.isOpen ) {
        kbvtext.close();
      } else {
        kbvtext.reveal();
      }
    });

    $('.kbvnumber')
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

    $('.kbvnumber1')
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

    $('.kbvnumber2')
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

    $('.kbvnumber3')
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

    $('.kbvnumber4')
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

    $('.kbvnumber5')
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

    $('.kbvnumber6')
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

    $('.kbvnumber7')
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

    $('.kbvnumber8')
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

    $('.kbvnumber9')
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

    $('.kbvnumber10')
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

    $('.kbvopenernum').on('click',function() {
      var kbvnumber = $('.kbvnumber').getkeyboard();
      if ( kbvnumber.isOpen ) {
        kbvnumber.close();
      } else {
        kbvnumber.reveal();
      }
    });

    $('.kbvopenernum1').on('click',function() {
      var kbvnumber = $('.kbvnumber1').getkeyboard();
      if ( kbvnumber.isOpen ) {
        kbvnumber.close();
      } else {
        kbvnumber.reveal();
      }
    });

    $('.kbvopenernum2').on('click',function() {
      var kbvnumber = $('.kbvnumber2').getkeyboard();
      if ( kbvnumber.isOpen ) {
        kbvnumber.close();
      } else {
        kbvnumber.reveal();
      }
    });

    $('.kbvopenernum3').on('click',function() {
      var kbvnumber = $('.kbvnumber3').getkeyboard();
      if ( kbvnumber.isOpen ) {
        kbvnumber.close();
      } else {
        kbvnumber.reveal();
      }
    });

    $('.kbvopenernum4').on('click',function() {
      var kbvnumber = $('.kbvnumber4').getkeyboard();
      if ( kbvnumber.isOpen ) {
        kbvnumber.close();
      } else {
        kbvnumber.reveal();
      }
    });

    $('.kbvopenernum5').on('click',function() {
      var kbvnumber = $('.kbvnumber5').getkeyboard();
      if ( kbvnumber.isOpen ) {
        kbvnumber.close();
      } else {
        kbvnumber.reveal();
      }
    });

    $('.kbvopenernum6').on('click',function() {
      var kbvnumber = $('.kbvnumber6').getkeyboard();
      if ( kbvnumber.isOpen ) {
        kbvnumber.close();
      } else {
        kbvnumber.reveal();
      }
    });

    $('.kbvopenernum7').on('click',function() {
      var kbvnumber = $('.kbvnumber7').getkeyboard();
      if ( kbvnumber.isOpen ) {
        kbvnumber.close();
      } else {
        kbvnumber.reveal();
      }
    });

    $('.kbvopenernum8').on('click',function() {
      var kbvnumber = $('.kbvnumber8').getkeyboard();
      if ( kbvnumber.isOpen ) {
        kbvnumber.close();
      } else {
        kbvnumber.reveal();
      }
    });

    $('.kbvopenernum9').on('click',function() {
      var kbvnumber = $('.kbvnumber9').getkeyboard();
      if ( kbvnumber.isOpen ) {
        kbvnumber.close();
      } else {
        kbvnumber.reveal();
      }
    });

    $('.kbvopenernum10').on('click',function() {
      var kbvnumber = $('.kbvnumber10').getkeyboard();
      if ( kbvnumber.isOpen ) {
        kbvnumber.close();
      } else {
        kbvnumber.reveal();
      }
    });

});
